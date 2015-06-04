 'use strict';

define(
    [
        'jquery',
        'underscore',
        'backbone',
        'oro/mediator',
        'routing',
        'pim/form',
        'pim/field-manager',
        'pim/entity-manager',
        'pim/attribute-manager',
        'pim/product-manager',
        'pim/attribute-group-manager',
        'pim/user-context',
        'pim/security-context',
        'pim/product-edit-form/attributes/attribute-group-selector',
        'text!pim/template/product/tab/attributes',
        'pim/dialog',
        'oro/messenger'
    ],
    function (
        $,
        _,
        Backbone,
        mediator,
        Routing,
        BaseForm,
        FieldManager,
        EntityManager,
        AttributeManager,
        ProductManager,
        AttributeGroupManager,
        UserContext,
        SecurityContext,
        AttributeGroupSelector,
        formTemplate,
        Dialog,
        messenger
    ) {
        var FormView = BaseForm.extend({
            template: _.template(formTemplate),
            className: 'tabbable tabs-left product-attributes',
            events: {
                'click .remove-attribute': 'removeAttribute'
            },
            visibleFields: {},
            rendering: false,
            initialize: function () {
                this.model = new Backbone.Model({ badges: {} });

                this.groupSelector = new AttributeGroupSelector({model: this.model});

                this.listenTo(this.model, 'change', this.render);

                return BaseForm.prototype.initialize.apply(this, arguments);
            },
            configure: function () {
                this.getRoot().addTab('attributes', 'Attributes');

                this.listenTo(this.getRoot().model, 'change', this.render);
                this.listenTo(UserContext, 'change:catalogLocale change:catalogScope', this.render);
                this.listenTo(mediator, 'product:action:post_update', this.postSave);
                this.listenTo(mediator, 'product:action:post_validation_error', this.postValidationError);
                this.listenTo(mediator, 'show_attribute', this.showAttribute);
                window.addEventListener('resize', _.bind(this.resize, this));
                FieldManager.clear();

                return BaseForm.prototype.configure.apply(this, arguments);
            },
            render: function () {
                if (!this.configured || this.rendering) {
                    return this;
                }

                this.rendering = true;

                this.getConfig().done(_.bind(function () {
                    this.$el.html(this.template());

                    this.groupSelector.setElement(this.$('.attribute-group-selector')).render();

                    this.resize();
                    var product = this.getData();
                    $.when(
                        EntityManager.getRepository('family').findAll(),
                        ProductManager.getValues(product)
                    ).done(_.bind(function (families, values) {
                        var productValues = AttributeGroupManager.getAttributeGroupValues(
                            values,
                            this.model.get('attributeGroups')[this.model.get('currentAttributeGroup')]
                        );

                        var fieldPromisses = [];
                        _.each(productValues, _.bind(function (productValue, attributeCode) {
                            fieldPromisses.push(this.getField(product, attributeCode, productValue, families));
                        }, this));

                        $.when.apply($, fieldPromisses).done(_.bind(function () {
                            var $productValuesPanel = this.$('.product-values');
                            $productValuesPanel.empty();

                            this.visibleFields = {};
                            _.each(arguments, _.bind(function (field) {
                                field.render();
                                this.visibleFields[field.attribute.code] = field;
                                $productValuesPanel.append(field.$el);
                            }, this));
                            this.rendering = false;
                        }, this));
                    }, this));
                    this.delegateEvents();

                    this.renderExtensions();
                }, this));

                return this;
            },
            resize: function () {
                var productValuesContainer = this.$('.product-values');
                if (productValuesContainer.length && this.getRoot().$el.length && productValuesContainer.offset()) {
                    productValuesContainer.css(
                        {'height': ($(window).height() - productValuesContainer.offset().top - 4) + 'px'}
                    );
                }
            },
            getField: function (product, attributeCode, values, families) {
                return FieldManager.getField(attributeCode).then(function (Field) {
                    // todo: pass the attribute to the field?
                    var field = new Field();
                    field.setContext({
                        locale: UserContext.get('catalogLocale'),
                        scope: UserContext.get('catalogScope'),
                        uiLocale: UserContext.get('catalogLocale'),
                        optional: AttributeManager.isOptional(attributeCode, product, families),
                        removable: SecurityContext.isGranted('pim_enrich_product_remove_attribute')
                    });
                    field.setValues(values);

                    return field;
                });
            },
            getConfig: function () {
                var promises = [];
                var product = this.getData();

                promises.push(this.updateModel(product));
                if (this.extensions['add-attribute']) {
                    promises.push(this.extensions['add-attribute'].updateOptionalAttributes(product));
                }

                return $.when.apply($, promises).promise();
            },
            updateModel: function (product) {
                return AttributeGroupManager.getAttributeGroupsForProduct(product)
                    .then(_.bind(function (attributeGroups) {
                        this.model.set('attributeGroups', attributeGroups);

                        if (undefined === this.model.get('currentAttributeGroup') ||
                            !attributeGroups[this.model.get('currentAttributeGroup')]) {
                            this.model.set('currentAttributeGroup', _.first(_.keys(attributeGroups)));
                        }

                        return attributeGroups;
                    }, this));
            },
            addAttributes: function (attributeCodes) {
                EntityManager.getRepository('attribute').findAll().done(_.bind(function (attributes) {
                    var product = this.getData();

                    var hasRequiredValues = true;
                    _.each(attributeCodes, function (attributeCode) {
                        var attribute = _.findWhere(attributes, {code: attributeCode});
                        if (!product.values[attribute.code]) {
                            product.values[attribute.code] = [AttributeManager.getValue(
                                [],
                                attribute,
                                UserContext.get('catalogLocale'),
                                UserContext.get('catalogScope')
                            )];
                            hasRequiredValues = false;
                        }
                    });

                    this.model.set(
                        'currentAttributeGroup',
                        _.findWhere(attributes, {code: _.first(attributeCodes)}).group
                    );

                    if (hasRequiredValues) {
                        this.getRoot().model.trigger('change');
                        return;
                    }

                    this.setData(product);
                }, this));

            },
            removeAttribute: function (event) {
                if (!SecurityContext.isGranted('pim_enrich_product_remove_attribute')) {
                    return;
                }
                var attributeCode = event.currentTarget.dataset.attribute;
                var product = this.getData();
                var fields = FieldManager.getFields();

                Dialog.confirm(
                    _.__('pim_enrich.confirmation.delete.product_attribute'),
                    _.__('pim_enrich.confirmation.delete_item'),
                    _.bind(function () {
                        EntityManager.getRepository('attribute').find(attributeCode).done(_.bind(function (attribute) {
                            $.ajax({
                                type: 'DELETE',
                                url: Routing.generate(
                                    'pim_enrich_product_remove_attribute_rest',
                                    {
                                        productId: product.meta.id,
                                        attributeId: attribute.id
                                    }
                                ),
                                contentType: 'application/json'
                            }).then(_.bind(function () {
                                if (this.extensions['add-attribute']) {
                                    this.extensions['add-attribute'].updateOptionalAttributes(product);
                                }

                                delete product.values[attributeCode];
                                delete fields[attributeCode];

                                this.setData(product, {silent: true});

                                this.getRoot().model.trigger('change');
                            }, this)).fail(function () {
                                messenger.notificationFlashMessage(
                                    'error',
                                    _.__('pim_enrich.form.product.flash.attribute_deletion_error')
                                );
                            });
                        }, this));
                    }, this)
                );
            },
            setScope: function (scope, options) {
                UserContext.set('catalogScope', scope, options);
            },
            getScope: function () {
                return UserContext.get('catalogScope');
            },
            setLocale: function (locale, options) {
                UserContext.set('catalogLocale', locale, options);
            },
            getLocale: function () {
                return UserContext.get('catalogLocale');
            },
            postValidationError: function () {
                this.updateAttributeGroupBadges();
                _.each(FieldManager.getFields(), function (field) {
                    if (!field.getValid()) {
                        mediator.trigger('show_attribute', {attribute: field.attribute.code});
                        return;
                    }
                });
            },
            postSave: function () {
                FieldManager.fields = {};
                this.model.set('badges', {});

                this.render();
            },
            updateAttributeGroupBadges: function () {
                var fields = FieldManager.getFields();

                var badges = {};

                _.each(fields, _.bind(function (field) {
                    if (!field.getValid()) {
                        var group = field.attribute.group;
                        badges[group] = badges[group] || {};
                        badges[group].invalid = (badges[group].invalid || 0) + 1;
                    }
                }, this));

                this.model.set('badges', badges);
            },
            showAttribute: function (event) {
                AttributeGroupManager.getAttributeGroupsForProduct(this.getData())
                    .done(_.bind(function (attributeGroups) {
                        var attributeGroup = AttributeGroupManager.getAttributeGroupForAttribute(
                            attributeGroups,
                            event.attribute
                        );

                        if (!attributeGroup) {
                            return;
                        }

                        if (event.scope) {
                            this.setScope(event.scope);
                        }
                        if (event.locale) {
                            this.setLocale(event.locale);
                        }

                        this.model.set('currentAttributeGroup', attributeGroup.code);

                        FieldManager.getFields()[event.attribute].setFocus();
                    }, this));
            }
        });

        return FormView;
    }
);
