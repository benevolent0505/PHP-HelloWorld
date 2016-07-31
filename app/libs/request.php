<?php
namespace HelloWorld;

class Request {
  /**
   * 送られてきたHTTPメソッド
   * @var string
   */
  public $request_method;

  /**
   * リクエスト先のURLの配列
   * 例: /users/benevolent0505 -> array('users', 'benevolent0505')
   * @var string[]
   */
  public $split_path;

  /**
   * クエリ文字列を格納する変数
   * @var string[]
   */
  public $params;

  /**
   * $_REQURSTを格納する変数
   * @var array
   */
  public $request_params;

  public function __construct(array $server, array $request) {
    $this->request_method = $server['REQUEST_METHOD'];
    $this->split_path = $this->parsePathInfo($server['PATH_INFO']);
    $this->params = $this->parseQuery($server['QUERY_STRING']);
    $this->request_params = $request;
  }

  private function parsePathInfo($path_info) {
    $tmp_arr = explode("/", $path_info);
    array_shift($tmp_arr);

    return $tmp_arr;
  }

  private function parseQuery($query_string) {
    $querys = explode("&", $query_string);

    $params = array_map(function ($query) {
      return explode("=", $query);
    }, $querys);

    return $params;
  }
}
