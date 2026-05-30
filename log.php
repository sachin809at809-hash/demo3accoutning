<?php
header('Content-Type: text/plain');

echo "--- NGINX CONFIG ---\n";
echo shell_exec('cat /opt/docker/etc/nginx/vhost.conf 2>&1');
echo shell_exec('cat /opt/docker/etc/nginx/vhost.common.d/*.conf 2>&1');

echo "\n--- LARAVEL LOG ---\n";
$logFile = __DIR__ . '/storage/logs/laravel.log';
if (!file_exists($logFile)) {
    echo "No log file found at $logFile\n";
} else {
    $fp = fopen($logFile, 'r');
    fseek($fp, -10000, SEEK_END);
    echo fread($fp, 10000);
    fclose($fp);
}
