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

