<?php

declare(strict_types=1);

// Проверка версии PHP
use La\ApplicationError;
use La\FrontController\Dispatcher;
use La\HttpError404Exception;

if (PHP_VERSION_ID < 80401) {
    print "PHP 8.4 Required";
    exit(1);
}

// Запись логов
ini_set("log_errors", "/var/log/php/php-error.log");

// Установка локали
setlocale(LC_ALL, 'ru_RU.UTF-8');

// Константы
const LA_ROOT_DIR = __DIR__ . "/../";
const LA_AUTOLOAD_DIR = LA_ROOT_DIR . 'vendor/autoload.php';
const LA_SRC_DIR = LA_ROOT_DIR . 'src/';
const LA_LOCAL_CONFIG_DIR = LA_SRC_DIR . 'config.local.php';
const LA_GLOBAL_CONFIG_DIR = LA_SRC_DIR . 'config.global.php';

define("LA_USE_SSL", (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)) OR (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'));
define("LA_SERVER_PORT", $_SERVER['SERVER_PORT'] ?? 80);

// Загрузка Composer's autoloader
if (!file_exists(LA_AUTOLOAD_DIR) || !is_readable(LA_AUTOLOAD_DIR)) {
    print "Composer autoloader not found";
    exit(1);
}

require_once LA_AUTOLOAD_DIR;

if (file_exists(LA_GLOBAL_CONFIG_DIR)) {
    // Если существует конфиг основной - подключаем (для production)
    if (!is_readable(LA_GLOBAL_CONFIG_DIR)) {
        print sprintf("Error: %s. not readable", LA_GLOBAL_CONFIG_DIR);
        exit(1);
    } else {
        require_once LA_GLOBAL_CONFIG_DIR;
    }
} else {
    // Нет основного конфига - подключаем
    if (!file_exists(LA_LOCAL_CONFIG_DIR) || !is_readable(LA_LOCAL_CONFIG_DIR)) {
        print sprintf("Error: %s not found or not readable", LA_LOCAL_CONFIG_DIR);
        exit(1);
    }
    require_once LA_LOCAL_CONFIG_DIR;
}

try {

    $dispatcher = new Dispatcher($_SERVER['REQUEST_URI'] ?? "/");

    if ($dispatcher->routeViaFiles()) {
        // Успешная маршрутизация через файлы, render-им
        $dispatcher->controller_entity->layout->render($dispatcher->controller_entity->view);
    } else {
        // Маршрутизация через файлы не удалась, выводим 404
        throw new HttpError404Exception("Page not found");
    }

} catch (Error | ApplicationError $e) {
    if (!headers_sent()) {
        header("HTTP/1.1 500 Internal Server Error", true, 500);
    }
    error_log(sprintf("%s: %s\nTrace: %s", $e::class, $e->getMessage(), $e->getTraceAsString()), E_USER_ERROR);
    print $e->getMessage();
    exit(1);

} catch (HttpError404Exception $e) {
    if (!headers_sent()) {
        header("HTTP/1.1 404 Not Found", true, 404);
    }
    print $e->getMessage();
    exit(1);
}