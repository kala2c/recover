<?php

// +----------------------------------------------------------------------
// | SDK、服务商 密钥 公钥等设置
// +----------------------------------------------------------------------
return [
    'wx' => [
        // 状元回收公众号
//       'appId' => 'wx44f968a029d95080',
//       'appSecret' => '4a647f6e5da2cf654ad47eebfb82b6bd',
        // 陈禄伟的测试公众号
//          'appId' => 'wx3df1334ac159289e',
//          'appSecret' => 'edef45c25b7dfb55584fec8471c1c2a4',
        //李烁的测试公众号
        'appId' => 'wx3df1334ac159289e',
        'appSecret' => 'edef45c25b7dfb55584fec8471c1c2a4',
//        'appId' => 'wxb1a1311f3cab8ac4',
//        'appSecret' => '9a6f610dbd3b4545eaba03d4f3ff7630',
        'templateId' => [
//            状元回收通知
            'newOrderNotify' => 'GlIOJ2nTR67ATUt1a2d8mx6ur1MHZ9zTZpP9rIQfaUc'
//            测试号通知
//            'newOrderNotify' => 'F9KCWuOVVU_Elb-7cJ3KAHs8jAmchEBZVC2VQvE00OY'
//            'newOrderNotify' => 'SxOvQtdNKt4msFkKPs0jeSvcv9rKatbM8ODvlJfUO20'
        ],
        // 'takeOrderUrl' => 'http://testwx2.c2wei.cn/#/pick/take?refresh=1'
        'takeOrderUrl' => 'http://zyhs.hihigher.com/#/depot'
    ],
    'qqMap' => [
        'key' => 'NGLBZ-PFLAJ-N2AFY-FYYM3-IKZS7-MABJT'
    ],
    'gdMap' => [
        'key' => 'b69bffd760e45373603cd0bb0e73604c'
    ]
];
