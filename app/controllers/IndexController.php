<?php
namespace HelloWorld\controllers;

require_once __DIR__ . "/../helpers/functions.php";

class IndexController {

  public function show() {
    return function () {
      return renderResponse('index.tpl.html');
    };
  }

  public function show_name() {
    return function ($name) {
      return renderResponse('show.tpl.html', array('name' => $name));
    };
  }
}
