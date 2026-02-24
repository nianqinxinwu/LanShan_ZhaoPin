<?php

return [
    'autoload' => false,
    'hooks' => [
        'app_init' => [
            'csmsignin',
            'qrcode',
        ],
        'config_init' => [
            'csmsignin',
            'darktheme',
            'simditor',
            'summernote',
        ],
        'view_filter' => [
            'darktheme',
        ],
        'epay_config_init' => [
            'epay',
        ],
        'addon_action_begin' => [
            'epay',
        ],
        'action_begin' => [
            'epay',
        ],
        'user_sidenav_after' => [
            'invite',
            'vip',
        ],
        'user_register_successed' => [
            'invite',
        ],
        'upgrade' => [
            'simditor',
            'zpwxsys',
        ],
        'wipecache_after' => [
            'tinymce',
        ],
        'set_tinymce' => [
            'tinymce',
        ],
        'upload_config_init' => [
            'vip',
        ],
        'user_login_successed' => [
            'vip',
        ],
    ],
    'route' => [
        '/invite/[:id]$' => 'invite/index/index',
        '/qrcode$' => 'qrcode/index/index',
        '/qrcode/build$' => 'qrcode/index/build',
    ],
    'priority' => [],
    'domain' => '',
];
