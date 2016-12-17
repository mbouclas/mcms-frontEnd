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