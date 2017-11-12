<?php

  define('DEVELOPMENT_ENV', 'true');
  define('FILE_SIZE_LIMIT', 10000000);

  define('DS', DIRECTORY_SEPARATOR);
  define('ROOT', dirname(__FILE__) . DS);
  define('APP_PATH', ROOT . 'application' . DS);
  define('CONTROLLER_PATH', APP_PATH . 'controllers' . DS);
  define('MODEL_PATH', APP_PATH . 'models' . DS);
  define('VIEW_PATH', APP_PATH . 'views' . DS);
  define('CONFIG_PATH', ROOT . 'config' . DS);
  define('FRAMEWORK_PATH', ROOT . 'framework' . DS);
  define('CORE_PATH', FRAMEWORK_PATH . 'core' . DS);
  define('DATABASE_PATH', FRAMEWORK_PATH . 'database' . DS);
  define('INCLUDES_PATH', ROOT . 'includes' . DS);
  define('INCLUDES_DATABASE', INCLUDES_PATH . 'database' . DS);
  define('INCLUDES_VIEWS', INCLUDES_PATH . 'views' . DS);
  define('UPLOADS_PATH', ROOT . 'uploads' . DS);

  require_once(CONFIG_PATH . 'setup.php');

  require_once(CORE_PATH . 'Framework.class.php');
  Framework::run();

?>
