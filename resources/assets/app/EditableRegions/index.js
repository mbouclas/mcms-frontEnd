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