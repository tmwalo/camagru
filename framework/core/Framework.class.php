<?php

abstract class Framework {

  private static $controllersAndActions = array(
    'pages' => ['home', 'error'],
  );

  private static function setErrorReporting()
  {
    if (DEVELOPMENT_ENV == true) {
      error_reporting(E_ALL);
      ini_set('display_errors', 'On');
    }else {
      ini_set('display_errors', 'Off');
    }
  }

  private static function autoloadController($classname)
  {
    require_once(CONTROLLER_PATH . "$classname.class.php");
  }

  private static function autoloadModel($classname)
  {
    require_once(MODEL_PATH . "$classname.class.php");
  }

  private static function autoload()
  {
    spl_autoload_register(array(__CLASS__, 'autoloadController'));
    spl_autoload_register(array(__CLASS__, 'autoloadModel'));
  }

  private static function dispatch()
  {
    if (isset($_GET['controller']) && isset($_GET['action'])) {
      $controller = $_GET['controller'];
      $action = $_GET['action'];
    }
    else {
      $controller = 'pages';
      $action = 'home';
    }

    if ( (!array_key_exists($controller, self::$controllersAndActions)) || (!in_array($action, self::$controllersAndActions[$controller])) ) {
      $controller = 'pages';
      $action = 'home';
    }

    $capitalizeController = ucwords($controller);
    $controllerClassName = $capitalizeController . "Controller";
    $controller = new $controllerClassName();
    $controller->$action();
  }

  public static function run()
  {
    self::setErrorReporting();
    self::autoload();
    self::dispatch();
  }

}

?>
