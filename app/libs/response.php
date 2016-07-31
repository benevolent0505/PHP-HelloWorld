<?php
namespace HelloWorld;

class Response {

  /**
   * HTTPメソッド
   * @var string
   */
  public $method;

  /**
   * 定義したURLルーティングの配列
   * @var string[]
   */
  public $split_path;

  /**
   * URLのパラメータの位置を格納する配列
   * 例: /users/:username -> array(0 => '', 1 => 'username')
   * @var string[]
   */
  public $param_pos;

  /**
   * GETリクエストのときはURLのパラメータの値, POSTリクエストのときは送られてきたパラメータの値
   * 例: GET  /users/benevolent0505 -> array('benevolent0505')
   *     POST username=benevolent0505 password=testpassword -> array('username' => 'benevolent0505', 'password' => 'testpassword')
   * @var string[]
   */
  public $params;

  /**
   * Controllerのクラス名
   * @var string
   */
  public $controller;

  /**
   * Controllerのメソッド名
   * @var string
   */
  public $controller_method;

  public function __construct($method, $split_path, $param_pos, $controller_name, $controller_method) {
    $this->method = $method;
    $this->split_path = $split_path;
    $this->param_pos = $param_pos;
    $this->controller_method = $controller_method;

    require_once __DIR__ . "/../controllers/$controller_name.php";
    $controller = "\\HelloWorld\\controllers\\$controller_name";
    $this->controller = new $controller();
  }

  /**
   * 引数のリクエストに自身がマッチするかどうかを判断するメソッド
   * @params request @request
   * @return bool
   */
  public function isMatch($request) {
    $request_length = count($request->split_path);

    // リクエストのHTTPメソッドが違うか、パス(URL)の長さが違う場合はマッチしない
    if ($request->request_method !== $this->method ||
        $request_length !== count($this->split_path)) return false;


    // パスが全て同じか違う場合はパラメータに登録されているかをチェック
    foreach ($request->split_path as $index => $path) {
      if ($this->split_path[$index] !== $path) {
        if (isset($this->param_pos[$index])) {
          continue;
        } else {
          return false;
        }
      }
    }

    return true;  // 以上のチェックが出来ればマッチ
  }

  /**
   * リクエストパラメータを設定するメソッド
   * @param string[] $split_path
   * @param mixed    $request_params
   */
  public function setParams(array $split_path, array $request_params) {
    $method = $this->method;

    if ($method == 'GET') {
      foreach ($split_path as $index => $path) {
        if ($this->split_path[$index] !== $path) {
          $this->params = $path;
        }
      }
    } else if ($method == 'POST') {
      $this->params = $request_params;
    }
  }

  public static function create($method, $path, $controller_name, $controller_method) {
    $split_path = self::parsePath($path);
    $param_pos = self::setParamPos($split_path);

    return new Response($method, $split_path, $param_pos, $controller_name, $controller_method);
  }

  public static function parsePath($path) {
    $tmp_arr = explode("/", $path);
    array_shift($tmp_arr);

    return $tmp_arr;
  }

  public static function setParamPos($split_path) {
    $param_pos = array();
    foreach ($split_path as $index => $path) {
      if (strpos($path, ':') !== false) {
        $param_pos[$index] = substr($path, 1);
      }
    }

    return $param_pos;
  }
}
