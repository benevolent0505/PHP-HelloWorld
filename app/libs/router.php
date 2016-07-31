<?php
namespace HelloWorld;

require_once 'response.php';

class Router {

  /**
   * @var response[]
   */
  public $responses;

  /**
   * ルーティングの定義された配列からレスポンス作成し、$responsesに格納していく
   * @param mixed $routing_map
   */
  public function __construct(array $routing_map) {
    foreach ($routing_map as $value) {
      $this->setResponse($value);
    }
  }

  public function match($request) {
    foreach ($this->responses as $response) {
      if ($response->isMatch($request)) {
        // 対応するレスポンスにリクエストパラメータを設定する
        $response->setParams($request->split_path, $request->request_params);
        return $response;
      }
    }

    return null;
  }

  private function setResponse($request_tuple) {
    $method = array_shift($request_tuple);
    $path = array_shift($request_tuple);
    list($controller_name, $controller_method)= array_shift($request_tuple);

    $this->responses[] = Response::create($method, $path, $controller_name, $controller_method);
  }
}
