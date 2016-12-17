(function () {
    'use strict';

    angular.module('mcms.frontEnd.editableRegions')
        .controller('EditableRegionController', Controller);

    Controller.$inject = ['Region', 'Dialog', 'FRONTEND_CONFIG', 'configuration',
        'EditableRegionService', 'core.services', 'LangService', '$timeout'];

    function Controller(Region, Dialog, Config, BaseConfig, ERS, Helpers, Lang, $timeout) {
        var vm = this,
            Items = [];

        vm.tmpModel = {};
        vm.Region = Region;
        vm.defaultLang = Lang.defaultLang();
        vm.Locales = Lang.locales();

        vm.add = function (region, item) {
            Dialog.show({
                title: 'Add items',
                contents: '<editable-region region="VM.region" item="VM.item" ' +
                'on-select-item="VM.onSelectItem(region, item, isNew)"></editable-region>',
                locals: {
                    item: item,
                    region: region,
                    onSelectItem: vm.onSelectItem
                }
            });
        };

        vm.reOrder = function () {
            Items = [];
            for (var i in vm.Region.regions) {
                Items.push(vm.Region.regions[i]);
            }

            Dialog.show({
                title: 'Reorder regions',
                templateUrl: Config.templatesDir + 'EditableRegions/Components/editableRegionsList.html',
                locals: {
                    Regions: Items,
                    onSort: vm.onSort
                }
            });
        };

        vm.onSort = function ($item, $partFrom, $partTo, $indexFrom, $indexTo) {
            var tmp = {};
            for (var i in Items) {
                tmp[Items[i].slug] = Items[i];
            }

            vm.Region.regions = {};
            $timeout(function () {
                vm.Region.regions = tmp;
                vm.save();
            });

        };

        vm.edit = function (region) {
            Dialog.show({
                title: 'edit ' + region.label,
                templateUrl: Config.templatesDir + 'EditableRegions/editRegion.html',
                locals: {
                    Region: region,
                    ValidationMessagesTemplate: BaseConfig.validationMessages,
                    onSave: vm.updateRegion,
                    save: vm.save
                }
            });
        };

        vm.updateRegion = function (region) {
        };

        vm.save = function () {
            ERS.update(vm.Region.name, vm.Region)
                .then(function () {
                    Helpers.toast('Saved!');
                });
        };

        vm.onSelectItem = function (region, item, isNew) {
            if (isNew) {
                region.items.push(item);
            }

            vm.save();
            Dialog.close();
        };

        vm.delete = function (region, index) {
            Helpers.confirmDialog({}, {})
                .then(function () {
                    region.items.splice(index, 1);
                    vm.save();
                });

        };
    }

})();
