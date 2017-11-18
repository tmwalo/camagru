<?php

  class PagesController extends BaseController {

    public function home()
    {
      require_once(VIEW_PATH . 'home.php');
    }

  }

?>
