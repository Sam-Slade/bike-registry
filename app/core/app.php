<?php
class App
{

  protected $controller = 'page_error';
  protected $method = 'index';
  protected $params = [];

  public function __construct()
  {
    // Require and declare class instance
    require_once 'consts.php';
    $constant = new Constant;

    // Get URL
    $url = $this->parseUrl();

    /*
      Check if controller exists in controller folder (first URL part passed)
    */
    if ($url && file_exists($constant->CONTROLLERS_DIR . $url[0] . '.php')) {
      // Change controller from default if exists
      $this->controller = $url[0];

      // Unset first arg from GET superglobal
      unset($url[0]);
    } elseif ($url == null || count($url) == 0) {
      $this->controller = 'home';
    }

    // Require controller, default if passed one didn't exist
    require_once $constant->CONTROLLERS_DIR . $this->controller . '.php';

    // Instantiate new controller object
    $this->controller = new $this->controller;

    /*
      Check if method name was passed in the URL
    */
    if (isset($url[1])) {
      if (method_exists($this->controller, $url[1])) {
        $this->method = $url[1];
        unset($url[1]);
      }
    }

    // Check params exist before defining the param array
    $this->params = $url ? array_values($url) : [];

    //
    call_user_func_array([$this->controller, $this->method], $this->params);
  }

  /*
    Using Apache rewrites, the url is sent as a query.
    The trailing slash is then trimmed, the URL is sanitised and then an exploded array delimited by / is returned, containing each part of the url.
  */
  public function parseUrl()
  {
    if (isset($_GET['url'])) {
      $url = filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL);
      return explode('/', $url);
    }
  }
}
