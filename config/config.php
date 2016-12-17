<?php

return [
    'resultsPerPage' => 10,
    'user' => [
        'login' => [
            'redirectTo' => 'home',
        ],
        'register' => [
            'before' => null,
            'after' => \Mcms\FrontEnd\UserRegistration\VerifiedAfter::class,
            'validator' => [
                'firstName' => 'required|max:255',
                'lastName' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6|confirmed',
            ],
        ],
        'mailables' => [
            'queue' => 'sync',
            'activation' => [
                'handle' => \Mcms\FrontEnd\UserRegistration\Mailables\Activation::class,
                'view' => 'emails.register.activation',
                'subject' => 'emails.register.activation.subject',
            ],
            'lostPassword' => [
                'handle' => \Mcms\FrontEnd\UserRegistration\Mailables\ResetPasswordNotification::class,
                'view' => 'emails.register.lostPassword',
                'subject' => 'emails.register.lostPassword.subject',
            ],
            'welcome' => [
                'handle' => \Mcms\FrontEnd\UserRegistration\Mailables\Welcome::class,
                'view' => 'emails.register.welcome',
                'subject' => 'emails.register.welcome.subject',
            ],
            'deleteAccount' => [
                'handle' => \Mcms\FrontEnd\UserRegistration\Mailables\DeleteAccount::class,
                'view' => 'emails.register.deleteAccount',
                'subject' => 'emails.register.deleteAccount.subject',
            ],
            'NotifyAdminOnNewUser' => [
                'handle' => \Mcms\Core\Mailables\NotifyAdminOnNewUser::class,
                'view' => 'emails.register.newUserRegistration',
                'subject' => 'emails.register.newUserRegistration.subject',
            ]
        ],
        'welcomeWidget' => [
            'links' => [
                [
                    'title' => 'Manage your users',
                    'href' => 'user-manager',
                    'description' => 'Add/remove/edit system users',
                    'settings' => []
                ],
                [
                    'title' => 'Manage your menus',
                    'href' => 'menu-manager',
                    'description' => 'Add/remove/edit website menus',
                    'settings' => []
                ],
                [
                    'title' => 'Translate your site',
                    'href' => 'lang',
                    'description' => 'Add/remove/edit website translations',
                    'settings' => []
                ],
/*                [
                    'title' => 'view latest pages',
                    'link' => ['type' => 'component', 'link' => '<latest-pages-widget options="VM.options"></latest-pages-widget>'],
                    'description' => 'Add/remove/edit website translations',
                    'settings' => [
                        'locals' => [
                            'options' => [
                                'limit' => 10
                            ]
                        ]
                    ]
                ],*/
/*                [
                    'title' => 'Add an author',
                    'link' => ['type' => 'component', 'link' => '<edit-user user="VM.User" options="VM.options"></edit-user>'],
                    'description' => 'Add a new author to the system',
                    'settings' => [
                        'locals' => [
                            'options' => [
                                'preset' => [
                                    'roles' => ['author', 'admin'],
                                    'user_permissions' => ['create-post', 'edit-user']
                                ]
                            ]
                        ]
                    ]
                ],*/
            ]
        ]
    ]
];