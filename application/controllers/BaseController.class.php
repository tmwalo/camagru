<?php

  class BaseController {

    public function redirect($url, $message = NULL)
    {
      /*

        Set cookie or session var with the message, then redirect the user.

        Message must disappear after page reloads.

      */

      header("Location: " . $url);

    }

    public function isLoggedIn()
    {

    }

  }

?>
