<?php

use App\Library\Base\LogHandler;
use EasySwoole\Log\LoggerInterface;

return [
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 9501,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SOCKET_SERVER, //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 8,
            'reload_async' => true,
            'max_wait_time' => 3
        ],
        'TASK' => [
            'workerNum' => 4,
            'maxRunningNum' => 128,
            'timeout' => 15
        ]
    ],
    "LOG" => [
        'dir' => null,
        'level' => LoggerInterface::LOG_LEVEL_DEBUG,
        'handler' => new LogHandler(),
        'logConsole' => true,
        'displayConsole' => true,
        'ignoreCategory' => []
    ],
    'TEMP_DIR' => null,
    // redis
    "REDIS" => [
        'host'          => '127.0.0.1',
        'port'          => '6379',
        'auth'          => '',
        'POOL_MAX_NUM'  => '20',
        'POOL_MIN_NUM'  => '5',
        'POOL_TIME_OUT' => '0.1',
    ],
    "MYSQL" => [
        'host'                 => '127.0.0.1',//数据库连接ip
        'user'                 => 'root',//数据库用户名
        'password'             => 'root',//数据库密码
        'database'             => 'recipes',//数据库
        'port'                 => '3306',//端口
        'timeout'              => '30',//超时时间
        'connect_timeout'      => '5',//连接超时时间
        'charset'              => 'utf8',//字符编码
        'max_reconnect_times ' => '3',//最大重连次数
        'prefix'               => 'tc_',
    ],
    "BASIC_ACTIONS" => [
        '/auth/login',
        '/auth/register',
        '/code/generate',
        '/code/search',
        '/sign/encrypt',
        '/admin/auth/login',
    ],
    "SIGN_ACTIONS" => [
        '/sign/encrypt'
    ]
];
