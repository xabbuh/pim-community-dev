// display fields matching the ones on the left
// when checked, save values to be copied into an object
// when copy clicked, values from copy object to the product
// when unchecked, remove from the copy object
// when select all/visible, put these in the copy object
// when switch locale/scope, clear the copy object

'use strict';

define(
    [
        'jquery',
        'underscore',
        'pim/form',
        'text!pim/template/product/tab/attribute/copy',
        'pim/product-edit-form/attributes/copyfield',
        'pim/entity-manager',
        'pim/attribute-manager',
        'pim/attribute-group-manager',
        'pim/product-manager',
        'pim/user-context'
    ],
    function (
        $,
        _,
        BaseForm,
        template,
        CopyField,
        EntityManager,
        AttributeManager,
        ProductManager,
        AttributeGroupManager,
        UserContext
    ) {
        return BaseForm.extend({
            template: _.template(template),
            className: 'attribute-copy-actions',
            copyFields: {},
            copying: false,
            locale: null,
            scope: null,
            events: {
                'click .start-copying': 'startCopying',
                'click .stop-copying': 'stopCopying',
                'click .select-all': 'selectAll',
                'click .select-all-visible': 'selectAllVisible',
                'click .copy': 'copy'
            },
            initialize: function () {
                this.copyFields = {};

                BaseForm.prototype.initialize.apply(this, arguments);
            },
            configure: function () {
                this.locale = UserContext.get('catalogLocale');
                this.scope  = UserContext.get('catalogScope');

                return BaseForm.prototype.configure.apply(this, arguments);
            },
            render: function () {
                this.getParent().$el[this.copying ? 'addClass' : 'removeClass']('comparision-mode');

                this.$el.html(
                    this.template({
                        'copying': this.copying
                    })
                );

                if (this.copying) {
                    this.getCopyFields().done(_.bind(function (copyFields) {
                        _.each(copyFields, _.bind(function (copyField, code) {
                            // orginalField.addElement('comparision', 'copy', copyField);
                            // originalField.render();
                        }, this));
                    }, this));
                }

                this.delegateEvents();

                return this.renderExtensions();
            },
            getCopyFields: function () {
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
                        // get field for attribute, create instance, set the value inside
                    }, this));

                    $.when.apply($, fieldPromisses).done(_.bind(function () {
                        // return the fields
                    }, this));
                }, this));
            },
            // generateCopyFields: function () {
            //     this.copyFields = {};

            //     $.when(
            //         EntityManager.getRepository('attribute').findAll(),
            //         ProductManager.getValues(this.getData())
            //     ).done(_.bind(function (attributes, productValues) {
            //         _.each(productValues, _.bind(function (values, code) {
            //             var attribute = _.findWhere(attributes, {code: code});

            //             if (attribute.scopable || attribute.localizable) {
            //                 var valueToCopy = AttributeManager.getValue(values, attribute, this.locale, this.scope);

            //                 var copyField;
            //                 if (
            //                     this.copyFields[code] &&
            //                     this.copyFields[code].locale === valueToCopy.locale &&
            //                     this.copyFields[code].scope === valueToCopy.scope
            //                 ) {
            //                     copyField = this.copyFields[code];
            //                     copyField.setSelected(this.copyFields[code].selected);
            //                 } else {
            //                     copyField = new CopyField();
            //                 }

            //                 copyField.setLocale(valueToCopy.locale);
            //                 copyField.setScope(valueToCopy.scope);
            //                 copyField.setData(valueToCopy.value);

            //                 this.copyFields[code] = copyField;
            //             }
            //         }, this));
            //     }, this));
            // },
            copy: function () {
                // _.each(this.copyFields, function (copyField) {
                //     if (copyField.selected && copyField.field && copyField.field.getEditable()) {
                //         copyField.field.setCurrentValue(copyField.data);
                //         copyField.selected = false;
                //     }
                // });
            },
            startCopying: function () {
                this.copying = true;
                // this.generateCopyFields();

                this.render();
            },
            stopCopying: function () {
                this.copying = false;

                _.each(this.copyFields, _.bind(function (copyField) {
                    if (copyField.field) {
                        copyField.field.removeElement('comparision', 'copy');
                    }
                }, this));

                this.copyFields = {};
                this.render();
            },
            setLocale: function (locale) {
                this.locale = locale;

                // this.generateCopyFields();
                this.render();
            },
            setScope: function (scope) {
                this.scope = scope;

                // this.generateCopyFields();
                this.render();
            },
            selectAll: function () {
                _.each(this.copyFields, function (copyField) {
                    copyField.selected = true;
                });

                this.getParent().render();
            },
            selectAllVisible: function () {
                _.each(this.copyFields, _.bind(function (copyField, attributeCode) {
                    if (this.getParent().visibleFields[attributeCode]) {
                        copyField.selected = true;
                    }
                }, this));

                this.getParent().render();
            },
            getLocale: function () {
                return this.locale;
            },
            getScope: function () {
                return this.scope;
            }
        });
    }
);
