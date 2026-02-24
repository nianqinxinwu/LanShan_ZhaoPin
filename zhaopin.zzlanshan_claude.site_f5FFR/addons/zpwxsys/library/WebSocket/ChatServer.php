<?php
/**
 * 群聊 WebSocket 服务端
 *
 * 基于 Workerman 实现，需先安装:
 *   composer require workerman/workerman
 *
 * 启动命令:
 *   php addons/zpwxsys/library/WebSocket/ChatServer.php start -d
 *
 * 停止命令:
 *   php addons/zpwxsys/library/WebSocket/ChatServer.php stop
 *
 * Nginx 代理配置 (wss):
 *   location /wss {
 *       proxy_pass http://127.0.0.1:2346;
 *       proxy_http_version 1.1;
 *       proxy_set_header Upgrade $http_upgrade;
 *       proxy_set_header Connection "Upgrade";
 *       proxy_set_header X-Real-IP $remote_addr;
 *   }
 *
 * 小程序端连接:
 *   wx.connectSocket({ url: 'wss://yourdomain.com/wss' })
 *
 * 注意:
 *   - 生产环境需配合 Supervisor 或 systemd 守护进程
 *   - 需在小程序后台配置合法域名（socket 合法域名）
 *   - 当前版本提供基础框架，前端已内置 HTTP 轮询作为回退方案
 */

// 如果 Workerman 未安装，给出提示并退出
if (!class_exists('Workerman\Worker')) {
    // 尝试加载 composer autoload
    $autoloadPath = __DIR__ . '/../../../../vendor/autoload.php';
    if (file_exists($autoloadPath)) {
        require_once $autoloadPath;
    }
}

if (!class_exists('Workerman\Worker')) {
    echo "=== 群聊 WebSocket 服务端 ===\n\n";
    echo "Workerman 未安装，请先执行:\n";
    echo "  composer require workerman/workerman\n\n";
    echo "安装后重新运行本脚本。\n";
    echo "当前系统使用 HTTP 轮询作为消息通信回退方案，功能正常。\n";
    exit(0);
}

use Workerman\Worker;
use Workerman\Connection\TcpConnection;

$ws = new Worker('websocket://0.0.0.0:2346');
$ws->count = 1;
$ws->name = 'ChatWebSocket';

// 连接池: groupid => [uid => connection]
$groups = [];
// 连接映射: connection_id => [uid, groupid]
$connMap = [];

$ws->onConnect = function (TcpConnection $connection) {
    echo "New connection: {$connection->id}\n";
};

$ws->onMessage = function (TcpConnection $connection, $data) use (&$groups, &$connMap) {
    $msg = json_decode($data, true);
    if (!$msg || !isset($msg['type'])) return;

    switch ($msg['type']) {
        case 'auth':
            // 客户端认证并加入群
            $groupid = intval($msg['groupid'] ?? 0);
            $token = $msg['token'] ?? '';
            // TODO: 验证 token 获取 uid（生产环境需接入 Token 服务）
            $uid = intval($msg['uid'] ?? $connection->id);

            if ($groupid > 0 && $uid > 0) {
                if (!isset($groups[$groupid])) {
                    $groups[$groupid] = [];
                }
                $groups[$groupid][$uid] = $connection;
                $connMap[$connection->id] = ['uid' => $uid, 'groupid' => $groupid];

                $connection->send(json_encode([
                    'type' => 'auth_ok',
                    'uid' => $uid,
                    'groupid' => $groupid
                ]));
            }
            break;

        case 'ping':
            $connection->send(json_encode(['type' => 'pong']));
            break;
    }
};

$ws->onClose = function (TcpConnection $connection) use (&$groups, &$connMap) {
    if (isset($connMap[$connection->id])) {
        $info = $connMap[$connection->id];
        $groupid = $info['groupid'];
        $uid = $info['uid'];
        if (isset($groups[$groupid][$uid])) {
            unset($groups[$groupid][$uid]);
        }
        unset($connMap[$connection->id]);
    }
    echo "Connection closed: {$connection->id}\n";
};

/**
 * 广播消息到群内所有在线成员
 * 由 HTTP API (Chat::send) 通过内部通信调用
 */
function broadcastToGroup($groupid, $msgData)
{
    global $groups;
    if (!isset($groups[$groupid])) return;

    $payload = json_encode([
        'type' => 'message',
        'data' => $msgData
    ]);

    foreach ($groups[$groupid] as $uid => $conn) {
        $conn->send($payload);
    }
}

Worker::runAll();
