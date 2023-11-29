<?php

// config for Risky2k1/ApplicationManager
return [
    'application' => [
        'default' => 'request_application',
        'shift' => [
            'morning', //ca sáng
            'afternoon' // ca chiều
        ]
    ],


    'prefix' => 'applications',


    'middleware' => ['web', 'auth', '2fa', 'auth.active', 'check.permission'],


];
