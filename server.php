<?php

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Redirect published vendor assets to the public directory
if (strpos($uri, '/vendor/') === 0) {
    header('Location: /public' . $uri, true, 301);
    exit;
}

// Redirect favicon to the correct public path
if ($uri === '/favicon.ico') {
    header('Location: /public/img/favicon.ico', true, 301);
    exit;
}

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.
if ($uri !== '/' && file_exists(__DIR__.$uri)) {
    return false;
}

require_once __DIR__.'/index.php';
