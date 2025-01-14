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

if (! function_exists('getInstance')) {
    function getInstance($class)
    {
        return ($class)::getInstance();
    }
}
if (! function_exists('config')) {
    function config($name, $default = null)
    {
        return getInstance('\houoole\Config')->get($name, $default);
    }
}
if (!function_exists('check_ip_allowed')) {
    /**
     * 检测IP是否允许
     * @param string $ip IP地址
     */
    function check_ip_allowed($ip = null)
    {
        $ips = '127.0.0.1';
        $ip = is_null($ip) ? $ips : $ip;
        $forbiddenipArr = ['127.0.0.1','58.39.98.132','47.104.8.8'];
        $forbiddenipArr = !$forbiddenipArr ? [] : $forbiddenipArr;
        $forbiddenipArr = is_array($forbiddenipArr) ? $forbiddenipArr : array_filter(explode("\n", str_replace("\r\n", "\n", $forbiddenipArr)));
        if ($forbiddenipArr && !in_array($ip,$forbiddenipArr))
        {
            return ['请求无权访问', 'html', 403];
        }
    }
}
if (!function_exists('debug')) {
    /**
     * 启用调试模式，定期检查并触发热更新。
     *
     * 此方法通过Swoole的定时器功能，每隔1秒检查一次是否需要进行热更新。
     * 热更新是为了在不重启服务的情况下，更新应用程序的某些部分。
     * 这里使用了一个简单的命令来模拟热更新的过程，实际应用中应根据具体需求定制更新命令。
     */
    function debug()
    {
        // 获取基础路径，用于后续构建热更新命令
        $basePath = BASE_PATH;

        // 定义一个1秒间隔的定时器，用于触发热更新检查
        // 定时器热更新
        // 定时器热更新
        \Swoole\Timer::tick(1000, function() use ($basePath)
        {
            try {
                // 打印当前时间，用于调试和日志记录
                $currentTime = date("Y-m-d H:i:s", time());
                echo "当前时间：{$currentTime}<br />";

                // 定义一个安全的热更新命令，这里使用echo作为示例
                $hotUpdateCommand = "/bin/echo ok"; // 假设这是安全的命令
                // 创建热更新对象，并启动监听
                $hotupdate = new \Phpdic\SwooleAutoRestart\swooleAutoRestart($basePath, $hotUpdateCommand);
                $hotupdate->listen();
            } catch (\Exception $e) {
                // 捕获并记录热更新过程中可能发生的异常
                // 在实际应用中，应该根据异常类型和情况采取不同的错误处理策略
                error_log("热更新过程中发生异常： " . $e->getMessage());
            }
        });
    }
}