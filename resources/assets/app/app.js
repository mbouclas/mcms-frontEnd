(function () {
    'use strict';

    angular.module('mcms.frontEnd', [
        'mcms.frontEnd.frontPage',
        'mcms.frontEnd.settings',
        'mcms.frontEnd.seo',
        'mcms.frontEnd.editableRegions',
        'mcms.frontEnd.layoutManager',
        'mcms.frontEnd.permalinkArchive',
        'mcms.frontEnd.widgets',
        'mcms.frontEnd.formBuilder',
        'mcms.frontEnd.ssg',
        'mcms.mediaFiles',
        'ngFileUpload'
    ])
        .run(run);

    run.$inject = ['mcms.menuService'];



    function run(Menu) {
        Menu.addMenu(Menu.newItem({
            id: 'FrontEnd',
            title: 'Website',
            permalink: '',
            icon: 'web',
            order: 5,
            gate : 'website.menu',
            acl: {
                type: 'role',
                permission: 'admin'
            }
        }));

        var pagesMenu = Menu.find('FrontEnd');

        pagesMenu.addChildren([
            Menu.newItem({
                id: 'editableRegions-settings',
                title: 'Editable regions',
                permalink: '/front/editableRegions',
                icon: 'format_shapes',
                order : 2
            })
        ]);

        pagesMenu.addChildren([
            Menu.newItem({
                id: 'permalink-archive',
                title: '301 Redirects',
                permalink: '/front/permalinkArchive',
                gate : 'website.permalinkArchive.menu',
                icon: 'link',
                order: 4
            }),
            Menu.newItem({
                id: 'form-builder',
                title: 'Form Builder',
                permalink: '/front/formBuilder',
                icon: 'build',
                gate : 'website.formBuilder.menu',
                order: 5
            }),
            Menu.newItem({
                id: 'form-log',
                title: 'Form Log',
                permalink: '/front/formLog',
                gate : 'website.formLog.menu',
                icon: 'history',
                order: 6
            }),
            Menu.newItem({
                id: 'ssg',
                title: 'SSG',
                permalink: '/front/ssg',
                gate : 'website.formLog.menu',
                icon: 'cloud_upload',
                order: 7
            }),
        ]);
    }

})();

require('./config');
require('./FrontPage');
require('./EditableRegions');
require('./Settings');
require('./Seo');
require('./LayoutManager');
require('./PermalinkArchive');
require('./Widgets');
require('./FormBuilder');
require('./Ssg');
