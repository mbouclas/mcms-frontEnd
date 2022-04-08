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
