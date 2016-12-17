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
