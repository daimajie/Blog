<?php
return [
    'layoutPath' => '@app/modules/admin/views/layouts',
    'layout' => 'sub',
    'modules' => [
        'content' => [
            'class' => 'app\modules\admin\modules\content\Module',
        ],
        'member' => [
            'class' => 'app\modules\admin\modules\member\Module',
        ],
        'friend' => [
            'class' => 'app\modules\admin\modules\friend\Module',
        ],
        'setting' => [
            'class' => 'app\modules\admin\modules\setting\Module',
        ],

        'notebook' => [/*未完成*/
            'class' => 'app\modules\admin\modules\notebook\Module',
        ],
    ],
    'components' => [
        // list of component configurations
    ],
    'params' => [
        // list of parameters
    ],
];