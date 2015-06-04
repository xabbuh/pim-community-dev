'use strict';

define(
    [
        'backbone',
        'underscore',
        'text!pim/template/product/tab/attribute/attribute-group-selector',
        'pim/user-context',
        'pim/i18n'
    ],
    function (Backbone, _, template, UserContext, i18n) {
        return Backbone.View.extend({
            template: _.template(template),
            state: null,
            events: {
                'click li': 'change'
            },
            initialize: function (options) {
                options = options || {};
                if (!_.has(options, 'model')) {
                    throw new Error('Attribute group selector requires model to be passed');
                }

                this.state = options.model;
                this.listenTo(this.state, 'change', this.render);

                return this;
            },
            render: function () {
                this.$el.html(
                    this.template({
                        state: this.state.toJSON(),
                        locale: UserContext.get('catalogLocale'),
                        i18n: i18n
                    })
                );

                this.delegateEvents();

                return this;
            },
            change: function (event) {
                if (event.currentTarget.dataset.attributeGroup !== this.state.get('currentAttributeGroup')) {
                    this.state.set('currentAttributeGroup', event.currentTarget.dataset.attributeGroup);
                }
            }
        });
    }
);
