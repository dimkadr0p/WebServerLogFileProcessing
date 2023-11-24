<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Slim\Factory\AppFactory;
use DI\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Controllers\UploadLogController;

require __DIR__ . '/vendor/autoload.php';

require 'Controllers\UploadLogController.php';

$container = new Container();

AppFactory::setContainer($container);

$app = AppFactory::create();

date_default_timezone_set('Europe/Moscow');


session_start();


$container->set('db', function () {
    $dbHost = 'localhost';
    $dbPort = '5432';
    $dbName = 'Logging';
    $dbUser = 'postgres';
    $dbPass = 'Wecehrfcf';

    $dsn = "pgsql:host=$dbHost;port=$dbPort;dbname=$dbName;user=$dbUser;password=$dbPass";

    try {
        $pdo = new PDO($dsn);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        return $pdo;
    } catch (PDOException $e) {
        die("Ошибка подключения к базе данных: " . $e->getMessage());
    }
});


$app->get('/', function (Request $request, Response $response) {
    $file = file_get_contents('templates/index.html');
    $response->getBody()->write($file);
    return $response;
});


$app->get('/upload_log', function (Request $request, Response $response) {

    $log = new UploadLogController($this);

    $log->upload_log($request, $response);

    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});


$app->get('/logsInfo', function (Request $request, Response $response) {

    $log = new UploadLogController($this);

    $logs = $log->getLogs();

    $response->getBody()->write(json_encode($logs));

    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});


$app->get('/log.html', function (Request $request, Response $response) {


    $file = file_get_contents('templates/log.html');
    $response->getBody()->write($file);

    return $response;
});


$app->get('/clearBD', function (Request $request, Response $response) {

    $clearLogCommand = "TRUNCATE TABLE logs, requesters, website_requests, website_responses CASCADE;";

    $stmt = $this->get('db')->prepare($clearLogCommand);

    if ($stmt->execute()) {
        echo "Данные удалены успешно\n";
    } else {
        echo "Ошибка при выполнении запроса: " . implode(', ', $stmt->errorInfo());
    }

    $resettingCounterCommands = array("ALTER SEQUENCE website_responses_id_seq RESTART WITH 1",
        "ALTER SEQUENCE website_requests_id_seq RESTART WITH 1", "ALTER SEQUENCE requesters_id_seq RESTART WITH 1");

    foreach ($resettingCounterCommands as &$value) {
        $stmt = $this->get('db')->prepare($value);

        if ($stmt->execute()) {
            echo "Счетчик таблицы $value сброшен успешно\n";
        } else {
            echo "Ошибка при выполнении запроса: " . implode(', ', $stmt->errorInfo());
        }
    }

    unset($_SESSION['logs']);

    return $response;
});



$app->run();