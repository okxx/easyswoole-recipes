<?php
namespace App\WebSocket;

use EasySwoole\FastCache\Cache;

class SocketEvent {

    static function onOpen($ws,$request) {
        $fd         = $request->fd;
        $token      = $request->get["token"];
        Cache::getInstance()->set('t-'.$token, ["fd"=> $fd]);
        Cache::getInstance()->set('fd-'.$fd, ["token"=> $token]);
        echo $token."上线了。".PHP_EOL;
    }

    static function onClose($ws, int $fd,int $reactorId) {
        $fdInfo = Cache::getInstance()->get('fd-'.$fd);
        if (is_array($fdInfo)) {
            Cache::getInstance()->unset('t-'.$fdInfo['token']);
            Cache::getInstance()->unset('fd-'.$fd);
            echo $fdInfo['token'] . "下线。".PHP_EOL;
        }
    }

    static function onMessage ($server, $frame) {
        $data = json_decode($frame->data);
        $form = Cache::getInstance()->get('fd-'.$frame->fd);
        $to = Cache::getInstance()->get('t-'.$data->to);
        if ($to == null) {
            $server->push($frame->fd,$data->to.'用户不在线.');
        } else {
            $server->push($to['fd'],$data->message);
        }
        echo '【'.$form['token'] . ' -> '. $data->to. '】 MSG: ' .$data->message.PHP_EOL;
    }

    static function onTimer() {
        echo "--- ".microtime(true).PHP_EOL;
    }

}