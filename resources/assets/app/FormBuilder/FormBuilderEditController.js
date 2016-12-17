(function() {
    'use strict';

    angular.module('mcms.frontEnd.formBuilder')
        .controller('FormBuilderEditController',Controller);

    Controller.$inject = ['Item', 'FormBuilderService', 'configuration', 'LangService', 'core.services',
        '$location', '$filter', '$q'];

    function Controller(Item, FormBuilderService, Config, Lang, Helpers, $location, $filter, $q) {
        var vm = this;
        vm.Item = Item;
        vm.Lang = Lang;
        vm.defaultLang = Lang.defaultLang();
        vm.Locales = Lang.locales();
        vm.Components = FormBuilderService.activeSchema();
        vm.ValidationMessagesTemplate = Config.validationMessages;
        vm.Providers = FormBuilderService.providers();
        vm.ProvidersFlat = FormBuilderService.providersFlat();
        vm.Template = FormBuilderService.getTemplate();
console.log(vm.Template.settings)
        //when setting providers, we need to sync it with the meta
        vm.setProvider = function () {
            FormBuilderService.syncProviderWithMeta(vm.Item);
        };

        vm.save = function () {

          FormBuilderService.save(vm.Item)
              .then(function (item) {
                  if (!vm.Item.id) {
                      $location.path($filter('reverseUrl')('form-builder-edit',{id : item.id}).replace('#',''));
                  }
                  Helpers.toast('Saved!!!', null, 'success');
              });
        };

        vm.onDelete = function (field, $index, allFields) {

            return Helpers.confirmDialog({}, {})
                .then(function () {
                    return vm.save();
                });
        };
    }

})();
