<?php

// +----------------------------------------------------------------------
// | SDK、服务商 密钥 公钥等设置
// +----------------------------------------------------------------------
return [
    'wx' => [
        // 状元回收公众号
//        'appId' => 'wx44f968a029d95080',
//        'appSecret' => '4a647f6e5da2cf654ad47eebfb82b6bd',
        // 陈禄伟的测试公众号
        'appId' => 'wxb06bf22b4b5e6a5d',
        'appSecret' => '5199d77fa9ea333cfc2692553b733c12',
        //李烁的测试公众号
//        'appId' => 'wx3df1334ac159289e',
//        'appSecret' => 'edef45c25b7dfb55584fec8471c1c2a4',
//        'appId' => 'wxb1a1311f3cab8ac4',
//        'appSecret' => '9a6f610dbd3b4545eaba03d4f3ff7630',
        'templateId' => [
            'newOrderNotify' => 'F9KCWuOVVU_Elb-7cJ3KAHs8jAmchEBZVC2VQvE00OY'
//            'newOrderNotify' => 'SxOvQtdNKt4msFkKPs0jeSvcv9rKatbM8ODvlJfUO20'
        ],
        'takeOrderUrl' => 'http://testwx2.c2wei.cn/#/pick/take?refresh=1'
    ],
    'qqMap' => [
        'key' => 'NGLBZ-PFLAJ-N2AFY-FYYM3-IKZS7-MABJT'
    ]
];
