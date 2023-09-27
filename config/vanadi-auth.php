<?php

return [
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => 'App\Models\User',
        ],

        'staff' => [
            'driver' => 'ldap',
            'model' => \Savannabits\Saas\Ldap\Staff::class,
            'rules' => [],
            'scopes' => [],
            'database' => [
                'model' => 'App\Models\User',
                'sync_passwords' => true,
                'sync_attributes' => [
                    'name' => 'cn',
                    'username' => 'samaccountname',
                    'email' => 'mail',
                    'uac' => 'useraccountcontrol',
                ],
                'sync_existing' => [
                    'email' => 'mail',
                    'username' => 'samaccountname',
                    'name' => 'cn',
                    'uac' => 'useraccountcontrol',
                ],
                'password_column' => 'password',
            ],

        ],
        'students' => [
            'driver' => 'ldap',
            'model' => \Savannabits\Saas\Ldap\Student::class,
            'rules' => [],
            'scopes' => [],
            'database' => [
                'model' => 'App\Models\User',
                'sync_passwords' => true,
                'sync_attributes' => [
                    'name' => 'cn',
                    'username' => 'samaccountname',
                    'uac' => 'useraccountcontrol',
                    'email' => 'mail',
                ],
                'sync_existing' => [
                    'name' => 'cn',
                    'username' => 'samaccountname',
                    'email' => 'mail',
                    'uac' => 'useraccountcontrol',
                ],
                'password_column' => 'password',
            ],

        ],

    ],
];
