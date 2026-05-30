<?php
$logFile = __DIR__ . '/storage/logs/laravel.log';
if (!file_exists($logFile)) {
    echo "No log file found at $logFile";
    exit;
}
$fp = fopen($logFile, 'r');
fseek($fp, -10000, SEEK_END);
$content = fread($fp, 10000);
fclose($fp);
header('Content-Type: text/plain');
echo $content;
