<?php

  require_once(INCLUDES_DATABASE . "connection.inc.php");

  require_once(MODEL_PATH . "Image.class.php");

  class ProfileController extends BaseController {

    public function load()
    {
      $title = "Profile";
      require_once(VIEW_PATH . 'profile.php');
    }

    public function deleteImage()
    {
      $image_id = $_GET['image_id'];
      if (!empty($image_id) ) {
        $pdo = Db::getInstance();

        $result = Image::find($pdo, $image_id);
        if ($result) {
          $row = $result;
          $uploader_user_id = $row['user_id'];
          $logged_in_user_id = 1;
          $filename = UPLOADS_PATH . $row['filename'];
          if ($logged_in_user_id == $uploader_user_id) {
            $delete_result = Image::delete($pdo, $image_id);
            if ($delete_result)
            {
              if (file_exists($filename) && is_file($filename))
                unlink($filename);
              echo "Image successfully deleted." . PHP_EOL;
            }
            else
              echo "Image could not be deleted." . PHP_EOL;
          }
          else
            echo "Permission denied." . PHP_EOL;
        }
        else
          echo "Image not found." . PHP_EOL;

        $pdo = NULL;
      }

      $title = "Profile";
      require_once(VIEW_PATH . 'profile.php');
    }

  }

?>
