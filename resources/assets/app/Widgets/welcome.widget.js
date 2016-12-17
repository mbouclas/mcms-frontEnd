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