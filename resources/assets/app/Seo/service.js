(function () {
    'use strict';

    angular.module('mcms.frontEnd.seo')
        .service('SeoService',Service);

    Service.$inject = ['lodashFactory', 'LangService'];

    function Service(lo, Lang) {
        var _this = this,
            Fields;

        this.init = init;
        this.fields = fields;
        this.prefill = prefill;
        this.fillFields = fillFields;

        function init(fields) {
            Fields = fields;
            return _this;
        }

        function fields() {
            return Fields;
        }

        function prefill(destinationModel, sourceModel, lang) {
            lo.forEach(Fields, function (field) {
                if (field.prefill && (!destinationModel[field.varName]) || typeof destinationModel[field.varName] == 'undefined'){
                    if (typeof sourceModel[field.prefill] == 'undefined' || !sourceModel[field.prefill] || typeof sourceModel[field.prefill][lang] == 'undefined'){
                        return;
                    }

                    destinationModel[field.varName] = (!sourceModel[field.prefill][lang]) ? '' : sourceModel[field.prefill][lang].replace(/(<([^>]+)>)/ig,"");
                }
            });

            return _this;
        }

        /**
         * Responsible for creating the settings fields in multiple languages
         * Pass something like item.settings and it will create the seo object for all languages
         *
         * @param model
         * @param inject
         */
        function fillFields(model, inject) {
            var Locales = Lang.locales();
            if (lo.isArray(model) || typeof model == 'undefined' || !model){
                model = {
                    seo : {}
                };
            }

            if (typeof model.seo == 'undefined'){
                model.seo = {};
            }

            for (var key in Locales){
                if (typeof model.seo[key] == 'undefined'){
                    model.seo[key] = {};
                }

                if (typeof inject == 'function'){
                    inject.call(this, model.seo[key], key);
                }


            }
        }
    }
})();
