<?php
return [
    'config'    =>  [
        //打开调试模式
        'debug'     => true,
        //微信基本配置，从公众平台获取
        'app_id'    => 'wx691ecb171f3e9e15',
        'secret'    => '5866c9b64d8b74470f344b13e7ba9476',
        'token'     => 'jorkboy',
        'aes_key' => '',                    // EncodingAESKey，安全模式下请一定要填写！！！
        //页面授权配置
        'oauth' => [
            'scopes'   => ['snsapi_userinfo'],
            'callback' => '/wechat/callback',
        ],
        //支付配置
        //'payment' => [
        //    'merchant_id'        => 'your-mch-id',
        //    'key'                => 'key-for-signature',
        //    'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
        //    'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！
        //    'notify_url'         => '默认的订单回调地址',       // 你也可以在下单时单独设置来想覆盖它
            // 'device_info'     => '013467007045764',
            // 'sub_app_id'      => '',
            // 'sub_merchant_id' => '',
            // ...
        //],
    ],

    //事件出发时推送的内容
    'event' =>  [
        //关注事件
        'subscribe' =>  [
            [
                'title'       => '一首歌，一部电影，一种心情',
                'description' => '欢迎关注本公众号，这里会不时的推荐一些好电影给大家，点击浏览我的博客发现更多内容',
                'url'         => 'www.jorkboy.xyz',
                'image'       => 'http://weixin.jorkboy.xyz/upload/show.jpg',
            ]
        ],
        //点击事件
        'click' =>  [
            'music' =>  [
                [
                    'title'       => '富士山下',
                    'description' => '归家需要几里路谁能预算',
                    'url'         => 'https://music.163.com/#/song?id=65766',
                    'image'       => 'http://weixin.jorkboy.xyz/upload/fushishanxia.jpg',
                ]
            ],
            'movie' =>  [
                [
                    'title'       => '霸王别姬',
                    'description' => '说好的一辈子就是一辈子，差一年，一个月，一天，一个时辰都不是一辈子',
                    'url'         => 'https://movie.douban.com/subject/1291546',
                    'image'       => 'http://weixin.jorkboy.xyz/upload/bawangbieji.jpg',
                ]
            ]
        ]
    ],

    //菜单配置
    'menu'  =>  [
        [
            "type" => "click",
            "name" => "每日电影",
            "key"  => "V1001_MOVIE"
        ],
        [
            "type" => "click",
            "name" => "随机一曲",
            "key"  => "V1001_MUSIC"
        ],
        [
            "name"       => "我的",
            "sub_button" => [
                [
                    "type" => "view",
                    "name" => "博客",
                    "url"  => "http://www.jorkboy.xyz/"
                ],
                [
                    "type" => "click",
                    "name" => "二维码",
                    "key" => "V1001_QRCODE"
                ],
            ],
        ]
    ],


    'tag'   =>  [
        '爱情',
        '喜剧',
        '剧情',
        '动画',
        '科幻',
        '动作',
        '经典',
        '悬疑',
        '青春',
        '犯罪',
        '惊悚',
        '文艺',
        '搞笑',
        '纪录片',
        '励志',
        '恐怖',
        '战争',
        '短片',
        '黑色幽默',
        '魔幻',
        '传记',
        '情色',
        '感人',
        '家庭',
        '童年',
        '浪漫',
        '女性',
        '同志',
        '美国',
        '中国',
        '日本',
        '香港',
        '英国',
        '韩国',
        '法国',
        '台湾',
        '德国',
        '意大利',
        '印度',
        '泰国',
        '俄罗斯',
        '澳大利亚',
        '伊朗',
        '丹麦',
        '欧洲',
    ],

    //聊天机器人的数据结构
    'robot' => [
        'key' => '1fdef5166fa041c8b7f86e448d894b65',
    ],

    //url
    'movieURL' => 'https://api.douban.com/v2/movie/search?tag=',
    'musicURL' => 'https://music.163.com/api/song/detail/?ids=',
    'robotURL' => 'http://www.tuling123.com/openapi/api',
    'link'      => 'http://weixin.jorkboy.xyz/upload/link.jpg',
    'foodie'      => 'http://weixin.jorkboy.xyz/upload/foodie.jpg',

    //mediaID
    'qrcode' => 'GYD_SEFE1y5-c9sZ0DgdsTWUGEXRCJ0PGjQW8y27D9U'
];