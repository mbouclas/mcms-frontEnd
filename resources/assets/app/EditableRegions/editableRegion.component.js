(function () {
    angular.module('mcms.frontEnd.editableRegions')
        .directive('editableRegion', Directive);

    Directive.$inject = ['FRONTEND_CONFIG'];
    DirectiveController.$inject = [ '$scope', '$timeout', 'ItemSelectorService',
        'FRONTEND_CONFIG', 'lodashFactory', 'LangService'];

    function Directive(Config) {

        return {
            templateUrl: Config.templatesDir + 'EditableRegions/editableRegion.component.html',
            controller: DirectiveController,
            controllerAs: 'VM',
            require : ['editableRegion'],
            scope: {
                region: '=region',
                options: '=?options',
                onSelectItem : '&?onSelectItem',
                item: '=?item',
            },
            restrict: 'E',
            link: function (scope, element, attrs, controllers) {
                var defaults = {
                    hasFilters: true
                };

                controllers[0].init(scope.region);
                scope.options = (!scope.options) ? defaults : angular.extend(defaults, scope.options);
            }
        };
    }

    function DirectiveController($scope, $timeout, ItemSelector, Config, lo, Lang) {
        var vm = this;
        vm.defaultLang = Lang.defaultLang();
        vm.Locales = Lang.locales();
        vm.Region = {};
        vm.Image = {};
        vm.Structured = {};
        vm.HTML = Lang.langFields();
        vm.Connectors = ItemSelector.connectors();
        vm.itemSelectorOptions = {multiple : false, searchOn : true};
        vm.Item = {};
        vm.tabs = [
            {
                label : 'HTML',
                file : Config.templatesDir + 'EditableRegions/Components/tab-html.html',
                active : true,
                default : true,
                alias : 'text',
                type : 'html',
                show : true
            },
            {
                label : 'Image',
                file : Config.templatesDir + 'EditableRegions/Components/tab-image.html',
                active : false,
                alias : 'image',
                type : 'image',
                show : true
            },
            {
                label : 'Item',
                file : Config.templatesDir + 'EditableRegions/Components/tab-item.html',
                active : false,
                default : false,
                alias : 'item',
                type : 'item',
                show : true
            },
            {
                label : 'Structured Data',
                file : Config.templatesDir + 'EditableRegions/Components/tab-structured-data.html',
                active : false,
                alias : 'structured',
                type : 'structured',
                show : true
            },
        ];
        vm.Settings = [];

        var CurrentType;


        vm.init = function (region) {
            vm.Region = region;
            if (typeof $scope.item != 'undefined'){
                if ($scope.item.type == 'image'){
                    vm.Image = $scope.item.item;
                    vm.Item = $scope.item;
                }
                else if ($scope.item.type == 'structured'){
                    vm.Structured = $scope.item.item;
                    vm.Item = $scope.item;
                }
                else if ($scope.item.type == 'html'){
                    vm.HTML = $scope.item.item;
                    vm.Item = $scope.item;
                }
                else {
                    vm.Item = $scope.item;
                }
            }

            vm.Settings  = region.structuredData;
            $timeout(function () {
                setAllowed(region);
                CurrentType = vm.tabs[0].type;
                if (typeof $scope.item != 'undefined') {
                    var selectedTab = lo.find(vm.tabs, {type: $scope.item.type});
                    //set tab
                    setTab(selectedTab);
                }
            }, 500);
        };

        vm.save = function(){
            if (CurrentType == 'image') {
                vm.Item = vm.Image;
            }
            else if (CurrentType == 'structured') {
                vm.Item = vm.Structured;
            }
            else if (CurrentType == 'html') {
                vm.Item = vm.HTML;
            }

            var ret = {
                type : CurrentType,
                item : vm.Item
            };

            if (typeof $scope.onSelectItem == 'function'){

                $timeout(function () {
                    var isNew = (typeof $scope.item == 'undefined');
                    $scope.onSelectItem({region: vm.Region, item: ret, isNew : isNew});
                });
            }

            return ret;
        };

        vm.onTabChange = function (tab) {
            CurrentType = tab.type;

            if (typeof $scope.item != 'undefined'){
                return;
            }

            switch (tab.type) {
                case 'image' : vm.Item = {};
                    break;
                case 'structured' : vm.Item = {};
                    break;
                case 'html' : vm.Item = {};
                    break;
                default : vm.Item = {};
                    break;
            }
        };

        vm.onResult = function (result) {
            vm.Item = angular.copy(result);
            delete vm.Item.section;
        };

        function setTab(tab) {
            //reset

            for (var i in vm.tabs){
                vm.tabs[i].active = false;
                vm.tabs[i].default = false;
            }

            tab.default = true;
            tab.active = true;
        }

        function setAllowed(region) {
            if (!lo.isArray(region.allow)){
                return;
            }


            var toRemove = [];
            lo.forEach(vm.tabs, function (tab, index) {
                if (region.allow.indexOf(tab.alias) == -1){
                    // tab.show = false;
                    toRemove.push(tab.alias);

                }
            });

            for (var i in toRemove){
                var index = lo.findIndex(vm.tabs, {alias : toRemove[i]});
                 vm.tabs.splice(index, 1);
            }

            return vm.tabs;
        }
    }
})();
