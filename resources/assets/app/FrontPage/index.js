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
