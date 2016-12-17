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