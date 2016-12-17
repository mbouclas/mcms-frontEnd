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
