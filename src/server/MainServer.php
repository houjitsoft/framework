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
namespace houoole\server;

class MainServer
{
    protected $_server;

    protected $_config;

    public function __construct()
    {
        $config = config('servers', []);
        $this->_config = $config['main'];
        $this->_server = new $this->_config['class_name'](
            $this->_config['ip'],
            $this->_config['port'],
            $this->_config['mode'] ?? SWOOLE_PROCESS,
            $this->_config['sock_type'] ?? SWOOLE_SOCK_TCP
        );
        $this->_server->set($this->_config['settings']);

        foreach ($this->_config['callbacks'] as $eventKey => $callbackItem) {
            [$class, $func] = $callbackItem;
            $this->_server->on($eventKey, [$class, $func]);
        }

        if (isset($this->_config['process']) && ! empty($this->_config['process'])) {
            foreach ($this->_config['process'] as $processItem) {
                [$class, $func] = $processItem;
                $this->_server->addProcess($class::$func($this->_server));
            }
        }

        if (isset($this->_config['sub']) && ! empty($this->_config['sub'])) {
            foreach ($this->_config['sub'] as $item) {
                $sub_server = $this->_server->addListener($item['ip'], $item['port'], $item['sock_type'] ?? SWOOLE_SOCK_TCP);
                if (isset($item['settings'])) {
                    $sub_server->set($item['settings']);
                }
                foreach ($item['callbacks'] as $eventKey => $callbackItem) {
                    [$class, $func] = $callbackItem;
                    $sub_server->on($eventKey, [$class, $func]);
                }
            }
        }

        $this->_server->start();
    }
}
