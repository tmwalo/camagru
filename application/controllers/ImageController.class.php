<?php

  class ImageController extends BaseController {

    public function show()
    {
      $title = "Image";
      require_once(VIEW_PATH . 'image.php');
    }

  }

?>
