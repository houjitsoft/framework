<?php declare(strict_types=1);
// +----------------------------------------------------------------------
// | houoole [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: amos <amos@houjit.com>
// +----------------------------------------------------------------------
namespace houoole\server\protocol;

interface MqttInterface
{
    // 1
    public function onMqConnect($server, int $fd, $fromId, $data);

    // 12
    public function onMqPingreq($server, int $fd, $fromId, $data): bool;

    // 14
    public function onMqDisconnect($server, int $fd, $fromId, $data): bool;

    // 3
    public function onMqPublish($server, int $fd, $fromId, $data);

    // 8
    public function onMqSubscribe($server, int $fd, $fromId, $data);

    // 10
    public function onMqUnsubscribe($server, int $fd, $fromId, $data);
}
