<?php

// config for Savannabits/Saas
return [
    'shared_models' => [
        'App\Models\User',
        \Savannabits\Saas\Models\User::class,
        \Savannabits\Saas\Models\Role::class,
        \Savannabits\Saas\Models\Permission::class,
        \Savannabits\Saas\Models\Country::class,
        \Savannabits\Saas\Models\Currency::class,
        \Savannabits\Saas\Models\Team::class,
    ]
];
