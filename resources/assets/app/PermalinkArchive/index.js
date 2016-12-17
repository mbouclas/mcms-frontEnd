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
