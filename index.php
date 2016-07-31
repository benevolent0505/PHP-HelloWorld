<?php
namespace HelloWorld;

require_once __DIR__ . '/app/libs/request.php';
require_once __DIR__ . '/app/libs/router.php';

// ルーティング定義用の配列の作成
$routing_map = array();

// ルーティング定義
$routing_map[] = array(
  'GET', '/', array('IndexController', 'show'),
);
$routing_map[] = array(
  'GET', '/:name', array('IndexController', 'show_name'), // パラメータ付URL
);

$router = new Router($routing_map);          // ルーターの作成
$request = new Request($_SERVER, $_REQUEST); // HTTPリクエストの作成
$response = $router->match($request);        // ルーターが合致するレスポンスを返す

// Controllerのクラスとメソッドの取り出し
$controller = $response->controller;
$method = $response->controller_method;

// Controllerメソッドの実行
$content = call_user_func($controller->$method(), $response->params);

// headerの指定
header('Content-Type: text/html; charset=utf-8');
header('Content-Length: ' . strlen($content));

// レスポンス内容の表示
echo $content;
