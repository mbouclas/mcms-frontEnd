(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
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

},{}],2:[function(require,module,exports){
(function() {
    'use strict';

    angular.module('mcms.frontEnd.editableRegions')
        .controller('EditableRegionsHomeController',Controller);

    Controller.$inject = ['Regions'];

    function Controller(Regions) {
        var vm = this;
        vm.Regions = Regions;

    }

})();

},{}],3:[function(require,module,exports){
(function () {
    'use strict';

    angular.module('mcms.frontEnd.editableRegions')
        .service('EditableRegionDataService',Service);

    Service.$inject = ['$http', '$q'];

    function Service($http, $q) {
        var _this = this;
        var baseUrl = '/admin/api/editableRegions/';

        this.index = index;
        this.update = update;

        function index(filters) {
            return $http.get(baseUrl, {params : filters}).then(returnData);
        }

        function update(id, item) {
            return $http.put(baseUrl + id, item)
                .then(returnData);
        }

        function returnData(response) {
            return response.data;
        }
    }
})();

},{}],4:[function(require,module,exports){
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
            if (vm.Region.regionSettings && lo.isObject(vm.Region.regionSettings.itemSelector)) {
                vm.itemSelectorOptions = angular.extend(vm.itemSelectorOptions, vm.Region.regionSettings.itemSelector);
            }

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

},{}],5:[function(require,module,exports){
(function(){
    'use strict';

    angular.module('mcms.frontEnd.editableRegions', [])
        .run(run);

    run.$inject = ['mcms.menuService'];

    function run(Menu) {

    }
})();
require('./dataService');
require('./service');
require('./EditableRegionsHomeController');
require('./EditableRegionController');
require('./editableRegion.component');
require('./routes');

},{"./EditableRegionController":1,"./EditableRegionsHomeController":2,"./dataService":3,"./editableRegion.component":4,"./routes":6,"./service":7}],6:[function(require,module,exports){
(function() {
    'use strict';

    angular.module('mcms.frontEnd.editableRegions')
        .config(config);

    config.$inject = ['$routeProvider','FRONTEND_CONFIG'];

    function config($routeProvider,Config) {

        $routeProvider
            .when('/front/editableRegions', {
                templateUrl:  Config.templatesDir + 'EditableRegions/index.html',
                controller: 'EditableRegionsHomeController',
                controllerAs: 'VM',
                reloadOnSearch : false,
                resolve: {
                    Regions : ["AuthService", '$q', 'EditableRegionService', function (ACL, $q, ERS) {
                        return (!ACL.inGates('editableRegions.menu')) ? $q.reject(403) : ERS.init();
                    }]
                },
                name: 'editable-regions-home'
            })
            .when('/front/editableRegions/:id', {
                templateUrl:  Config.templatesDir + 'EditableRegions/edit.html',
                controller: 'EditableRegionController',
                controllerAs: 'VM',
                reloadOnSearch : false,
                resolve: {
                    Region : ["AuthService", '$q', 'EditableRegionService', '$route', function (ACL, $q, ERS, $route) {
                        return (!ACL.inGates('editableRegions.edit')) ? $q.reject(403) : ERS.region($route.current.params.id);
                    }]
                },
                name: 'edit-editable-region'
            });
    }


})();

},{}],7:[function(require,module,exports){
(function () {
    'use strict';

    angular.module('mcms.frontEnd.editableRegions')
        .service('EditableRegionService',Service);

    Service.$inject = ['lodashFactory', 'LangService', 'EditableRegionDataService', '$q', 'ItemSelectorService'];

    function Service(lo, Lang, DS, $q, ItemSelector) {
        var _this = this,
            Regions = [];

        this.init = init;
        this.region = region;
        this.update = update;


        function init() {
            return DS.index().then(function (response) {
                Regions = response.regions;
                ItemSelector.register(response.connectors);
                return Regions;
            });
        }

        function region(id) {

            if (Regions.length == 0) {
                return init()
                    .then(function (r) {
                        return region(id);
                    });
            }

            return $q.resolve(lo.find(Regions, {name : id}));

        }

        function regions() {
            return Regions;
        }

        function update(id, region) {
            return DS.update(id, region);
        }

    }
})();

},{}],8:[function(require,module,exports){
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

},{}],9:[function(require,module,exports){
(function() {
    'use strict';

    angular.module('mcms.frontEnd.formBuilder')
        .controller('FormBuilderHomeController',Controller);

    Controller.$inject = ['Items'];

    function Controller(Items) {
        var vm = this;
        vm.Items = Items;

    }

})();

},{}],10:[function(require,module,exports){
(function() {
    'use strict';

    angular.module('mcms.frontEnd.formBuilder')
        .controller('FormLogController',Controller);

    Controller.$inject = ['Item', 'FormLogService'];

    function Controller(Item, FormLogService) {
        var vm = this;
        vm.Item = Item;

    }

})();

},{}],11:[function(require,module,exports){
(function() {
    'use strict';

    angular.module('mcms.frontEnd.formBuilder')
        .controller('FormLogHomeController',Controller);

    Controller.$inject = ['Items', 'FormLogService', '$mdBottomSheet', '$mdSidenav', '$rootScope',
        '$location', 'core.services', '$scope'];

    function Controller(Items, FormLogService, $mdBottomSheet, $mdSidenav, $rootScope, $location, Helpers, $scope) {
        var vm = this;
        vm.Items = Items.data;
        vm.Pagination = Items;
        vm.filters = FormLogService.availableFilters();
        vm.Forms = FormLogService.forms();


        Helpers.clearLocation($scope);

        function filter() {
            vm.Loading = true;
            vm.Items = [];

            return FormLogService.get(vm.filters)
                .then(function (res) {
                    $location.search(vm.filters);
                    vm.Loading = false;
                    vm.Pagination = res;
                    vm.Items = res.data;
                    $rootScope.$broadcast('scroll.to.top');
                });
        }

        vm.changePage = function (page, limit) {
            vm.filters.page = page;
            // console.log(vm.filters);
            filter();
        };

        vm.applyFilters = function () {
            vm.filters.page = 1;
            filter();
        };

        vm.listItemClick = function($index) {
            $mdBottomSheet.hide(clickedItem);
        };

        vm.toggleFilters = function () {
            $mdSidenav('filters').toggle();
        };

        vm.resetFilters = function () {
            resetFilters();
            filter();
        };


        function resetFilters() {
            vm.filters = FormLogService.availableFilters(true);
        }
    }

})();

},{}],12:[function(require,module,exports){
(function () {
    'use strict';

    angular.module('mcms.frontEnd.formBuilder')
        .service('FormLogService',Service);

    Service.$inject = ['lodashFactory', 'LangService', 'FormLogDataService', '$q', 'core.services',
        '$location', 'FormBuilderService'];

    function Service(lo, Lang, DS, $q, Helpers, $location, FormBuilderService) {
        var _this = this,
            Filters = {},
            Forms = [];


        this.init = init;
        this.get = get;
        this.find = find;
        this.newForm = newForm;
        this.save = save;
        this.destroy = destroy;
        this.availableFilters = availableFilters;
        this.forms = forms;

        function init() {
            var tasks = [
                get(),
                FormBuilderService.get()
            ];

            return $q.all(tasks)
                .then(function (response) {
                    Forms = response[1];
                    return response[0];
                });
        }


        function get(filters) {

            Filters = Helpers.parseLocation(availableFilters(), $location.search());
            if (lo.isObject(filters)){
                Filters = angular.extend(Filters, filters);
            }

            var toFilter = angular.copy(Filters);
            if (typeof toFilter != 'undefined' && (toFilter.field && toFilter.fieldValue)){
                toFilter.data = toFilter.field + '::' + toFilter.fieldValue;
            }

            return DS.index(toFilter)
                .then(function (response) {

                    return response;
                });
        }

        function find(id) {
            return DS.show(id)
                .then(function (item) {
                    return item;
                });

        }

        function newForm() {
            return {
                id : null,
                title : '',
                slug : '',
                provider : [],
                label : Lang.langFields(),
                description : Lang.langFields(),
                fields : [],
                settings : {},
                meta : {},
            };
        }

        function save(item) {
            if (!item.id){
                return DS.store(item);
            }

            return DS.update(item);
        }

        function destroy(item) {
            return DS.destroy(item.id);
        }

        function availableFilters(reset) {
            if (!lo.isEmpty(Filters) && !reset){
                return Filters;
            }

            return {
                id: null,
                field: null,
                form_id: null,
                fieldValue: null,
                dateMode: 'created_at',
                orderBy : 'created_at',
                way : 'DESC',
                page: 1,
                limit :  10
            };
        }

        function forms() {
            return Forms;
        }
    }
})();

},{}],13:[function(require,module,exports){
(function () {
    'use strict';

    angular.module('mcms.frontEnd.formBuilder')
        .service('FormBuilderDataService',Service);

    Service.$inject = ['$http', '$q'];

    function Service($http, $q) {
        var _this = this;
        var baseUrl = '/admin/api/formBuilder/';

        this.index = index;
        this.store = store;
        this.show = show;
        this.update = update;
        this.destroy = destroy;
        this.schema = schema;
        this.template = template;
        this.providers = providers;

        function index(filters) {
            return $http.get(baseUrl, {params : filters}).then(returnData);
        }

        function store(item) {
            return $http.post(baseUrl, item)
                .then(returnData);
        }

        function show(id) {
            return $http.get(baseUrl + id)
                .then(returnData);
        }

        function update(item) {
            return $http.put(baseUrl + item.id, item)
                .then(returnData);
        }

        function destroy(id) {
            return $http.delete(baseUrl + id)
                .then(returnData);
        }

        function returnData(response) {
            return response.data;
        }

        function schema(name) {
            return $http.get(baseUrl + 'schema', {params : {schema : name}}).then(returnData);
        }

        function template() {
            return $http.get(baseUrl + 'template').then(returnData);
        }

        function providers() {
            return $http.get(baseUrl + 'providers').then(returnData);
        }
    }
})();

},{}],14:[function(require,module,exports){
(function () {
    'use strict';

    angular.module('mcms.frontEnd.formBuilder')
        .service('FormLogDataService',Service);

    Service.$inject = ['$http', '$q'];

    function Service($http, $q) {
        var _this = this;
        var baseUrl = '/admin/api/formLog/';

        this.index = index;
        this.store = store;
        this.show = show;
        this.update = update;
        this.destroy = destroy;

        function index(filters) {
            return $http.get(baseUrl, {params : filters}).then(returnData);
        }

        function store(item) {
            return $http.post(baseUrl, item)
                .then(returnData);
        }

        function show(id) {
            return $http.get(baseUrl + id)
                .then(returnData);
        }

        function update(item) {
            return $http.put(baseUrl + item.id, item)
                .then(returnData);
        }

        function destroy(id) {
            return $http.delete(baseUrl + id)
                .then(returnData);
        }

        function returnData(response) {
            return response.data;
        }
    }
})();

},{}],15:[function(require,module,exports){
(function(){
    'use strict';

    angular.module('mcms.frontEnd.formBuilder', [])
        .run(run);

    run.$inject = ['mcms.widgetService'];

    function run(Widget) {

    }
})();


require('./dataService');
require('./services');
require('./formLogDataService');
require('./FormLogService');
require('./FormBuilderHomeController');
require('./FormLogHomeController');
require('./FormLogController');
require('./FormBuilderEditController');
require('./routes');
},{"./FormBuilderEditController":8,"./FormBuilderHomeController":9,"./FormLogController":10,"./FormLogHomeController":11,"./FormLogService":12,"./dataService":13,"./formLogDataService":14,"./routes":16,"./services":17}],16:[function(require,module,exports){
(function() {
    'use strict';

    angular.module('mcms.frontEnd.formBuilder')
        .config(config);

    config.$inject = ['$routeProvider','FRONTEND_CONFIG'];

    function config($routeProvider,Config) {

        $routeProvider
            .when('/front/formBuilder', {
                templateUrl:  Config.templatesDir + 'FormBuilder/index.html',
                controller: 'FormBuilderHomeController',
                controllerAs: 'VM',
                reloadOnSearch : false,
                resolve: {
                    Items : ["AuthService", '$q', 'FormBuilderService', function (ACL, $q, FormBuilder) {
                        return (!ACL.level(5)) ? $q.reject(403) : FormBuilder.get();
                    }]
                },
                name: 'form-builder-home'
            })
            .when('/front/formBuilder/:id', {
                templateUrl:  Config.templatesDir + 'FormBuilder/editForm.html',
                controller: 'FormBuilderEditController',
                controllerAs: 'VM',
                reloadOnSearch : false,
                resolve: {
                    Item : ["AuthService", '$q', 'FormBuilderService', '$route', function (ACL, $q, FormBuilder, $route) {
                        return (!ACL.level(5)) ? $q.reject(403) : FormBuilder.init($route.current.params.id);
                    }]
                },
                name: 'form-builder-edit'
            })
            .when('/front/formLog', {
                templateUrl:  Config.templatesDir + 'FormBuilder/formLog.html',
                controller: 'FormLogHomeController',
                controllerAs: 'VM',
                reloadOnSearch : false,
                resolve: {
                    Items : ["AuthService", '$q', 'FormLogService', function (ACL, $q, FormLog) {
                        return (!ACL.level(5)) ? $q.reject(403) : FormLog.init();
                    }]
                },
                name: 'form-log-home'
            })
            .when('/front/formLog/:id', {
                templateUrl:  Config.templatesDir + 'FormBuilder/viewLog.html',
                controller: 'FormLogController',
                controllerAs: 'VM',
                reloadOnSearch : false,
                resolve: {
                    Item : ["AuthService", '$q', 'FormLogService', '$route', function (ACL, $q, FormLogService, $route) {
                        return (!ACL.level(5)) ? $q.reject(403) : FormLogService.find($route.current.params.id);
                    }]
                },
                name: 'form-log-view'
            });
    }
})();

},{}],17:[function(require,module,exports){
(function () {
    'use strict';

    angular.module('mcms.frontEnd.formBuilder')
        .service('FormBuilderService',Service);

    Service.$inject = ['lodashFactory', 'LangService', 'FormBuilderDataService', '$q'];

    function Service(lo, Lang, DS, $q) {
        var _this = this,
            Schema = [],
            Providers,
            Template = {};

        this.init = init;
        this.get = get;
        this.find = find;
        this.newForm = newForm;
        this.save = save;
        this.destroy = destroy;
        this.schema = schema;
        this.template = template;
        this.activeSchema = activeSchema;
        this.providers = providers;
        this.getProviders = getProviders;
        this.getTemplate = getTemplate;
        this.providersFlat = providersFlat;
        this.syncProviderWithMeta = syncProviderWithMeta;

        function init(id) {
            return $q.all([
                (isNaN(parseInt(id))) ? $q.resolve(newForm()) : DS.show(id),
                schema(),
                getProviders(),
                template()
            ])
                .then(function (response) {
                    Schema = response[1];
                    Providers = response[2];
                    Template = response[3];
                    return normalizeItem(response[0]);
                });
        }

        function get(filters) {
            return DS.index(filters)
                .then(function (response) {

                    return response;
                });
        }

        function find(id) {
            return DS.show(id)
                .then(function (item) {
                    if (!lo.isObject(item.settings) || lo.isArray(item.settings)){
                        item.settings = {};
                    }

                    if (!lo.isObject(item.meta) || lo.isArray(item.meta)){
                        item.meta = {};
                    }
                    return item;
                });

        }

        function newForm() {
            return {
                id : null,
                title : '',
                slug : '',
                provider : [],
                label : Lang.langFields(),
                description : Lang.langFields(),
                fields : [],
                settings : {},
                meta : {},
            };
        }

        function save(item) {
            if (!item.id){
                return DS.store(item);
            }

            return DS.update(item);
        }

        function destroy(item) {
            return DS.destroy(item.id);
        }

        function schema(name) {
            return DS.schema(name);
        }

        function template() {
            return DS.template();
        }

        function activeSchema() {
            return Schema;
        }

        function providers() {
            return Providers;
        }

        function getProviders() {
            return DS.providers();
        }

        function getTemplate() {
            return Template;
        }

        function providersFlat() {
            var flat = {};
            for (var i in Providers){
                flat[Providers[i].varName] = Providers[i];
            }

            return flat;
        }

        function normalizeItem(item) {
            if (!lo.isObject(item.meta) || lo.isArray(item.meta)){
                item.meta = {
                    providers : {}
                };
            }

            if (!lo.isObject(item.meta.providers) || lo.isArray(item.meta.providers)){
                item.meta.providers = {};
            }

            if (!lo.isArray(item.provider)){
                item.provider = [];
            }


            return item;
        }

        function syncProviderWithMeta(item) {
            //what we need to do is sync the provider array with the meta object
            for (var i in item.provider){
                var id = item.provider[i];
                //check if it's there already
                if (item.meta.providers[id]){
                    continue;
                }
                //not there, add it
                item.meta.providers[id] = {}
            }
        }
    }
})();

},{}],18:[function(require,module,exports){
(function() {
    'use strict';

    angular.module('mcms.frontEnd.frontPage')
        .controller('FrontPageHomeController',Controller);

    Controller.$inject = [];

    function Controller() {
        var vm = this;

    }

})();

},{}],19:[function(require,module,exports){
(function () {
    'use strict';

    angular.module('mcms.frontEnd.frontPage')
        .service('FrontPageDataService',Service);

    Service.$inject = ['$http', '$q'];

    function Service($http, $q) {
        var _this = this;
        var baseUrl = '/admin/api/front-page/';



        function returnData(response) {
            return response.data;
        }
    }
})();

},{}],20:[function(require,module,exports){
(function(){
    'use strict';

    angular.module('mcms.frontEnd.frontPage', [])
        .run(run);

    run.$inject = ['mcms.menuService'];

    function run(Menu) {

    }
})();

require('./routes');
require('./dataService');
require('./service');
require('./FrontPageHomeController');

},{"./FrontPageHomeController":18,"./dataService":19,"./routes":21,"./service":22}],21:[function(require,module,exports){
(function() {
    'use strict';

    angular.module('mcms.frontEnd.frontPage')
        .config(config);

    config.$inject = ['$routeProvider','FRONTEND_CONFIG'];

    function config($routeProvider,Config) {

        $routeProvider
            .when('/front/frontPage', {
                templateUrl:  Config.templatesDir + 'FrontPage/index.html',
                controller: 'FrontPageHomeController',
                controllerAs: 'VM',
                reloadOnSearch : false,
                resolve: {
                    init : ["AuthService", '$q', 'FrontPageService', function (ACL, $q, FrontPage) {
                        return (!ACL.role('admin')) ? $q.reject(403) : FrontPage.init();
                    }]
                },
                name: 'front-page-home'
            });
    }

})();

},{}],22:[function(require,module,exports){
(function () {
    'use strict';

    angular.module('mcms.frontEnd.frontPage')
        .service('FrontPageService',Service);

    Service.$inject = ['FrontPageDataService', 'LangService', 'lodashFactory', '$q'];

    function Service(DS, Lang, lo, $q) {
        var _this = this;
        this.init = init;


        function init() {

            return $q.resolve([]);
        }

    }
})();

},{}],23:[function(require,module,exports){
(function(){
    'use strict';

    angular.module('mcms.frontEnd.layoutManager', [])
        .run(run);

    run.$inject = ['LayoutManagerService'];

    function run(LMS) {
        if (typeof window.Layouts != 'undefined'){
            LMS.init(window.Layouts);
        }
    }
})();

require('./service');


},{"./service":24}],24:[function(require,module,exports){
(function () {
    'use strict';

    angular.module('mcms.frontEnd.layoutManager')
        .service('LayoutManagerService',Service);

    Service.$inject = ['lodashFactory'];

    function Service(lo) {
        var _this = this,
            Layouts = [],
            appliedFilter = null;

        this.init = init;
        this.layouts = layouts;
        this.setModel = setModel;
        this.toObj = toObj;

        function init(layouts) {
            Layouts = layouts;
        }

        function setModel(model) {
            if (typeof model.settings.Layout !='undefined' && model.settings){
                return;
            }

            if (typeof model.settings == 'undefined' || lo.isArray(model.settings)){
                model.settings = {};
            }

            model.settings.Layout = {
                id : null,
                settings : {}
            };

            return _this;
        }

        function layouts(filter) {
            if (!filter || typeof filter == 'undefined'){
                return Layouts;
            }

            var ret = [];
            appliedFilter = filter;
            //we need to check which layouts actually have the area property and filter them out
            lo.forEach(Layouts, function (item) {
                if (typeof item.area != 'undefined' && item.area){
                    if (typeof item.area == 'string' && item.area != filter){
                        return
                    }

                    if (lo.isArray(item.area) && item.area.indexOf(filter) == -1){
                        return;
                    }
                }

                ret.push(item);
            });

            return ret;
        }

        function toObj() {
            var obj = {},
                layouts = _this.layouts(appliedFilter);

            for (var i in layouts){
                obj[layouts[i].varName] = layouts[i];
            }

            return obj;
        }
    }
})();

},{}],25:[function(require,module,exports){
(function() {
    'use strict';

    angular.module('mcms.frontEnd.permalinkArchive')
        .controller('PermalinkArchiveHomeController',Controller);

    Controller.$inject = ['Items', 'PermalinkArchiveService', 'core.services', 'configuration'];

    function Controller(Items, PAS, Helpers, Config) {
        var vm = this;

        vm.ValidationMessagesTemplate = Config.validationMessages;
        vm.Items = Items;
        vm.newItem = PAS.newItem();

        vm.edit = function (id) {
            vm.editNow = id;
        };

        vm.save = function (item, $index) {
            PAS.save(item)
                .then(function () {
                    vm.editNow = null;
                    Helpers.toast('Saved!');
                });

        };

        vm.add = function () {
            PAS.save(vm.newItem)
                .then(function (items) {
                    vm.newItem = PAS.newItem();
                    vm.Items = items;
                    Helpers.toast('Saved!');
                });
        };


        vm.delete = function (item, $index) {
            Helpers.confirmDialog({}, {})
                .then(function () {
                    PAS.destroy(item).then(function () {
                        vm.Items.splice($index, 1);
                        Helpers.toast('Saved!');
                    });
                });

        };
    }

})();

},{}],26:[function(require,module,exports){
(function () {
    'use strict';

    angular.module('mcms.frontEnd.permalinkArchive')
        .service('PermalinkArchiveDataService',Service);

    Service.$inject = ['$http', '$q'];

    function Service($http, $q) {
        var _this = this;
        var baseUrl = '/admin/api/permalinkArchive/';

        this.index = index;
        this.update = update;
        this.index = index;
        this.store = store;
        this.show = show;
        this.update = update;
        this.destroy = destroy;

        function index() {
            return $http.get(baseUrl).then(returnData);
        }

        function store(item) {
            return $http.post(baseUrl, item)
                .then(returnData);
        }

        function show(id) {

        }

        function update(item) {
            return $http.put(baseUrl + item.id, item)
                .then(returnData);
        }

        function destroy(id) {
            return $http.delete(baseUrl + id)
                .then(returnData);
        }

        function returnData(response) {
            return response.data;
        }
    }
})();

},{}],27:[function(require,module,exports){
(function(){
    'use strict';

    angular.module('mcms.frontEnd.permalinkArchive', [])
        .run(run);

    run.$inject = ['mcms.menuService'];

    function run(Menu) {

    }
})();
require('./dataService');
require('./service');
require('./PermalinkArchiveHomeController');
require('./routes');

},{"./PermalinkArchiveHomeController":25,"./dataService":26,"./routes":28,"./service":29}],28:[function(require,module,exports){
(function() {
    'use strict';

    angular.module('mcms.frontEnd.permalinkArchive')
        .config(config);

    config.$inject = ['$routeProvider','FRONTEND_CONFIG'];

    function config($routeProvider,Config) {

        $routeProvider
            .when('/front/permalinkArchive', {
                templateUrl:  Config.templatesDir + 'PermalinkArchive/index.html',
                controller: 'PermalinkArchiveHomeController',
                controllerAs: 'VM',
                reloadOnSearch : false,
                resolve: {
                    Items : ["AuthService", '$q', 'PermalinkArchiveService', function (ACL, $q, PAS) {
                        return (!ACL.inGates('website.permalinkArchive.menu')) ? $q.reject(403) : PAS.init();
                    }]
                },
                name: 'permalink-archive-home'
            });
    }


})();

},{}],29:[function(require,module,exports){
(function () {
    'use strict';

    angular.module('mcms.frontEnd.permalinkArchive')
        .service('PermalinkArchiveService',Service);

    Service.$inject = ['lodashFactory', 'PermalinkArchiveDataService', '$q'];

    function Service(lo, DS, $q) {
        var _this = this,
            Items = [];

        this.init = init;
        this.newItem = newItem;
        this.save = save;
        this.destroy = destroy;

        function init() {
            return DS.index().then(function (response) {
                Items = response;
                return Items;
            });
        }

        function newItem() {
            return {
                old_link : '',
                new_link : ''
            };
        }

        function save(item) {
            if (!item.id){
                return DS.store(item);
            }


            return DS.update(item);
        }

        function destroy(item) {
            return DS.destroy(item.id);
        }
    }
})();

},{}],30:[function(require,module,exports){
(function(){
    'use strict';

    angular.module('mcms.frontEnd.seo', [])
        .run(run);

    run.$inject = ['mcms.menuService'];

    function run(Menu) {

    }
})();
require('./service');

/*
require('./routes');
require('./dataService');

require('./PageHomeController');
require('./PageController');
require('./editPage.component');
*/

},{"./service":31}],31:[function(require,module,exports){
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

},{}],32:[function(require,module,exports){
(function(){
    'use strict';

    angular.module('mcms.frontEnd.settings', [])
        .run(run);

    run.$inject = ['mcms.menuService'];

    function run(Menu) {

    }
})();

/*
require('./routes');
require('./dataService');
require('./service');
require('./PageHomeController');
require('./PageController');
require('./editPage.component');
*/

},{}],33:[function(require,module,exports){
(function () {
    'use strict';

    angular.module('mcms.frontEnd.ssg')
        .service('SsgDataService',Service);

    Service.$inject = ['$http', '$q'];

    function Service($http, $q) {
        const _this = this;
        var baseUrl = '/admin/api/ssg/';

        this.index = index;
        this.update = update;

        this.startBuild = () => {
            return $http.post(`${baseUrl}start-build`).then(res => res.data);
        }


        function index(filters) {
            return $http.get(baseUrl, {params : filters}).then(res => res.data);
        }

        function update(id, item) {
            return $http.put(baseUrl + id, item)
                .then(returnData);
        }

        function returnData(response) {
            return response.data;
        }
    }
})();

},{}],34:[function(require,module,exports){
(function(){
    'use strict';

    angular.module('mcms.frontEnd.ssg', [])
        .run(run);

    run.$inject = ['mcms.menuService'];

    function run(Menu) {

    }
})();
require('./dataService');
require('./service');
require('./ssgHomeController');
require('./routes');

},{"./dataService":33,"./routes":35,"./service":36,"./ssgHomeController":37}],35:[function(require,module,exports){
(function() {
    'use strict';

    angular.module('mcms.frontEnd.ssg')
        .config(config);

    config.$inject = ['$routeProvider','FRONTEND_CONFIG'];

    function config($routeProvider,Config) {
        $routeProvider
            .when('/front/ssg', {
                templateUrl:  Config.templatesDir + 'Ssg/index.html',
                controller: 'SsgHomeController',
                controllerAs: 'VM',
                reloadOnSearch : false,
                resolve: {
                    Init : ["AuthService", '$q', 'SsgService', function (ACL, $q, SSGS) {
                        return (!ACL.inGates('editableRegions.menu')) ? $q.reject(403) : SSGS.init();
                    }]
                },
                name: 'ssg-home'
            });
    }


})();

},{}],36:[function(require,module,exports){
(function () {
    'use strict';


    angular.module('mcms.frontEnd.ssg')
        .service('SsgService',Service);

    Service.$inject = ['lodashFactory', 'LangService', 'SsgDataService', '$rootScope'];

    function Service(lo, Lang, DS, $rootScope) {

        const _this = this;
        this.records = [];
        this.init = init;

        this.all = () => {
           return DS.index()
                .then(items => {
                    this.records = items;

                    return items;
                });
        }

        this.startBuild = () => {
            const source = new EventSource("/admin/api/ssg/notifications");
            const eventListener  = (event) => {
                let data = JSON.parse(event.data);
                if (data.state && data.state === 'completed') {
                    $rootScope.$broadcast('buildCompleted', data);
                    source.close();
                }

                if (data.state && data.state === 'failed') {
                    $rootScope.$broadcast('buildFailed', data);
                    source.close();
                }

                if (data.state && data.state === 'progress') {
                    $rootScope.$broadcast('buildProgress', data);
                }
            };

            source.addEventListener('message', eventListener, false);

            return DS.startBuild();
        };

        function init() {

            return _this.all();
        }
    }
})();

},{}],37:[function(require,module,exports){
(function() {
    'use strict';

    angular.module('mcms.frontEnd.ssg')
        .controller('SsgHomeController',Controller);

    Controller.$inject = ['Init', 'SsgService', '$rootScope', '$scope'];

    function Controller(InitialLoad, service, $rootScope, $scope) {
        this.building = false;
        this.buildComplete = false;
        this.buildFailed = false;
        this.buildProgress = false;
        this.currentBuild;
        this.items = InitialLoad;
        this.progressOutput = '';
        // Once the build is complete, refresh the data
        $rootScope.$on('buildCompleted', ($event, data) => {
            service.all()
                .then(items => {
                    this.items = items;
                    this.building = false;
                    this.buildComplete = true;

                    setTimeout(() => {
                        this.buildComplete = false;
                        $scope.$apply();
                    }, 5000);
                });
        });

        $rootScope.$on('buildFailed', ($event, data) => {
            this.buildFailed = true;
            setTimeout(() => {
                this.buildFailed = false;
                $scope.$apply();
            }, 5000);
        });

        $rootScope.$on('buildProgress', ($event, data) => {

        });

        this.build = () => {
            this.building = true;
            service.startBuild()
                .then(res => {
                    this.currentBuild = res;
                    console.log(this.currentBuild)
                    return res;
                })
                .then(() => service.all())
                .then(items => this.items = items)
                .catch(e => console.log(e));
        }
    }

})();

},{}],38:[function(require,module,exports){
(function () {
    'use strict';

    angular.module('mcms.frontEnd.widgets')
        .service('WelcomeWidgetDataService',Service);

    Service.$inject = ['$http', '$q'];

    function Service($http, $q) {
        var _this = this;
        var baseUrl = '/admin/api/welcomeWidget/';

        this.index = index;
        this.update = update;

        function index(filters) {
            return $http.get(baseUrl, {params : filters}).then(returnData);
        }

        function update(id, item) {
            return $http.put(baseUrl + id, item)
                .then(returnData);
        }

        function returnData(response) {
            return response.data;
        }
    }
})();

},{}],39:[function(require,module,exports){
(function(){
    'use strict';

    angular.module('mcms.frontEnd.widgets', [])
        .run(run);

    run.$inject = ['mcms.widgetService'];

    function run(Widget) {
        Widget.registerWidget(Widget.newWidget({
            id : 'welcome',
            title : 'Quick links',
            template : '<welcome-widget></welcome-widget>',
            settings : {},
            order : 0
        }));
    }
})();


require('./welcome.widget');
require('./dataService');
require('./service');
},{"./dataService":38,"./service":40,"./welcome.widget":41}],40:[function(require,module,exports){
(function () {
    'use strict';

    angular.module('mcms.frontEnd.widgets')
        .service('WelcomeWidgetService',Service);

    Service.$inject = ['lodashFactory', 'LangService', 'WelcomeWidgetDataService', '$q'];

    function Service(lo, Lang, DS, $q) {
        var _this = this,
            WelcomeWidget = [];

        this.get = get;
        this.update = update;


        function get(filters) {
            return DS.index(filters)
                .then(function (response) {
                    WelcomeWidget = response;
                    return response;
                });
        }


        function update(id, item) {
            return DS.update(id, item);
        }

    }
})();

},{}],41:[function(require,module,exports){
(function(){
    'use strict';

    angular.module('mcms.frontEnd.widgets')
        .directive('welcomeWidget', Component);

    Component.$inject = ['FRONTEND_CONFIG', '$filter', 'WelcomeWidgetService', 'lodashFactory', '$location',
        'Dialog', 'AuthService'];

    function Component(Config, $filter, WelcomeWidgetService, lo, $location, Dialog, ACL){

        return {
            templateUrl: Config.templatesDir + "Widgets/welcome.widget.html",
            restrict : 'E',
            link : function(scope, element, attrs, controllers){
                // $location.path($filter('reverseUrl')('pages-edit',{id : id}).replace('#',''));
                var defaults = [
                    {
                        title : 'Manage your users',
                        link : {type : 'href', link : 'user-list'},
                        description : 'Add/remove/edit system users',
                        acl : {type : 'level', permission : 98}
                    },
                    {
                        title : 'Manage your menus',
                        link : {type : 'href', link :  'menu-manager'},
                        description : 'Add/remove/edit website menus',
                        acl : {type : 'level', permission : 98}
                    },
                    {
                        title : 'Translate your site',
                        link : {type : 'href', link :  'lang'},
                        description : 'Add/remove/edit website translations',
                        acl : {type : 'level', permission : 98}
                    }
                ];


                scope.Items = [];
                WelcomeWidgetService.get()
                    .then(function (WelcomeWidget) {
                        if (typeof WelcomeWidget == 'undefined' || typeof WelcomeWidget.links == 'undefined' || !lo.isArray(WelcomeWidget.links) || WelcomeWidget.links.length == 0){
                            scope.Items = defaults;
                            return;
                        }

                        lo.forEach(WelcomeWidget.links, function (item) {
                            if (typeof item.acl == 'undefined' || !item.acl){
                                scope.Items.push(item);
                                return;
                            }

                            var acl = ACL[item.acl.type](item.acl.permission);
                            if (typeof acl == 'undefined' || !acl){
                                return;
                            }

                            scope.Items.push(item);
                        });

                    });

                scope.activate = function ($index) {
                  var item = scope.Items[$index];
                  if (item.link.type == 'href'){
                      $location.path($filter('reverseUrl')(item.link.link).replace('#', ''));
                  }
                  else if (item.link.type == 'component') {
                      //open a dialog
                      Dialog.show({
                          title :item.title,
                          contents : item.link.link,
                          locals : (typeof item.settings.locals == 'undefined') ? {} : item.settings.locals
                      });
                  }
                };
            }
        };
    }
})();
},{}],42:[function(require,module,exports){
(function(){
    'use strict';
    var assetsUrl = '/assets/',
        appUrl = '/app/',
        componentsUrl = appUrl + 'Components/',
        templatesDir = '/front-end/app/templates/';

    var config = {
        apiUrl : '/api/',
        prefixUrl : '/admin',
        templatesDir : templatesDir,
        imageUploadUrl: '/admin/api/upload/image',
        imageBasePath: assetsUrl + 'img',
        validationMessages : templatesDir + 'Components/validationMessages.html',
        appUrl : appUrl,
        componentsUrl : componentsUrl,
        fileTypes : {
            image : {
                accept : 'image/*',
                acceptSelect : 'image/jpg,image/JPG,image/jpeg,image/JPEG,image/PNG,image/png,image/gif,image/GIF'
            },
            document : {
                accept : 'application/pdf,application/doc,application/docx',
                acceptSelect : 'application/pdf,application/doc,application/docx'
            },
            file : {
                accept : 'application/*',
                acceptSelect : 'application/*'
            },
            audio : {
                accept : 'audio/*',
                acceptSelect : 'audio/*'
            }
        }
    };

    angular.module('mcms.core')
        .constant('FRONTEND_CONFIG',config);
})();
},{}],43:[function(require,module,exports){
(function () {
    'use strict';

    angular.module('mcms.frontEnd', [
        'mcms.frontEnd.frontPage',
        'mcms.frontEnd.settings',
        'mcms.frontEnd.seo',
        'mcms.frontEnd.editableRegions',
        'mcms.frontEnd.layoutManager',
        'mcms.frontEnd.permalinkArchive',
        'mcms.frontEnd.widgets',
        'mcms.frontEnd.formBuilder',
        'mcms.frontEnd.ssg',
        'mcms.mediaFiles',
        'ngFileUpload'
    ])
        .run(run);

    run.$inject = ['mcms.menuService'];



    function run(Menu) {
        Menu.addMenu(Menu.newItem({
            id: 'FrontEnd',
            title: 'Website',
            permalink: '',
            icon: 'web',
            order: 5,
            gate : 'website.menu',
            acl: {
                type: 'role',
                permission: 'admin'
            }
        }));

        var pagesMenu = Menu.find('FrontEnd');

        pagesMenu.addChildren([
            Menu.newItem({
                id: 'editableRegions-settings',
                title: 'Editable regions',
                permalink: '/front/editableRegions',
                icon: 'format_shapes',
                order : 2
            })
        ]);

        pagesMenu.addChildren([
            Menu.newItem({
                id: 'permalink-archive',
                title: '301 Redirects',
                permalink: '/front/permalinkArchive',
                gate : 'website.permalinkArchive.menu',
                icon: 'link',
                order: 4
            }),
            Menu.newItem({
                id: 'form-builder',
                title: 'Form Builder',
                permalink: '/front/formBuilder',
                icon: 'build',
                gate : 'website.formBuilder.menu',
                order: 5
            }),
            Menu.newItem({
                id: 'form-log',
                title: 'Form Log',
                permalink: '/front/formLog',
                gate : 'website.formLog.menu',
                icon: 'history',
                order: 6
            }),
            Menu.newItem({
                id: 'ssg',
                title: 'SSG',
                permalink: '/front/ssg',
                gate : 'website.formLog.menu',
                icon: 'cloud_upload',
                order: 7
            }),
        ]);
    }

})();

require('./config');
require('./FrontPage');
require('./EditableRegions');
require('./Settings');
require('./Seo');
require('./LayoutManager');
require('./PermalinkArchive');
require('./Widgets');
require('./FormBuilder');
require('./Ssg');

},{"./EditableRegions":5,"./FormBuilder":15,"./FrontPage":20,"./LayoutManager":23,"./PermalinkArchive":27,"./Seo":30,"./Settings":32,"./Ssg":34,"./Widgets":39,"./config":42}]},{},[43])