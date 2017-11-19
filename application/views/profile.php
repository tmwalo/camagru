<?php
  $title = "Profile";
  require_once(INCLUDES_VIEWS . 'header_top.inc.php');
?>
<link rel="stylesheet" href="css/profile.css">
<?php
  require_once(INCLUDES_VIEWS . 'header_bottom.inc.php');
?>
<h1>Profile</h1>
<div class="content">
  <h1>My Profile</h1>
  <div class="create_img">
    <h2>Capture Image</h2>
    <div id="effects" class="effects create_img_containers">
      <h3>Effects</h3>
      <div class="effects_img">
        <img src="images/5-2-snapchat-filters-png-image-thumb.png" alt="" class="active" />
      </div>
      <div class="effects_img">
        <img src="images/577bbbba9ccdd155bb555112.png" alt="" />
      </div>
      <div class="effects_img">
        <img src="images/Snapchat-Flower-Crown-Transparent-PNG.png" alt="" />
      </div>
      <div class="effects_img">
        <img src="images/img_8426_400x400_by_kotoreh-db8i3jb.png" alt="" />
      </div>
      <div class="effects_img">
        <img src="images/main-qimg-fbc7f12d10b2106797a2ed0b4ccdf4d0.png" alt="" />
      </div>
    </div>
    <div class="capture_img create_img_containers">
      <video id="video">Video stream not available.</video>
      <form id="take_pic_form" action="" method="post">
        <input type="hidden" name="form" value="webcam_pic_form">
        <input id="effects_img_webcam" type="hidden" name="effects_img_webcam" value="" />
        <input id="hidden_webcam_pic"type="hidden" name="webcam_pic">
        <input type="submit" value="Take Pic">
      </form>
      <canvas id="canvas"></canvas>
      <p>or</p>
      <?php

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

          echo "Begin form handling!" . PHP_EOL;

          if (isset($_POST['form'])) {

            $form = $_POST['form'];

            if ($form == 'upload_pic_form') {

              echo "Handle upload pic form." . PHP_EOL;

              $file_upload = $_FILES['upload_pic'];
              $src = $file_upload['tmp_name'];
              $effects_img = $_POST['effects_img'];
              $dest = UPLOADS_PATH . $file_upload['name'];
              $file = $dest;

              echo "Before instantiating MergeUploadImg class." . PHP_EOL;

              require_once(MODEL_PATH . 'MergeImages.class.php');
              require_once(MODEL_PATH . 'MergeUploadImg.class.php');

              $image_handler = new MergeUploadImg();

              echo "After instantiating MergeUploadImg class." . PHP_EOL;

              if ($image_handler->validateForm() && move_uploaded_file($src, $dest) )
                $uploaded = true;
              else
                $uploaded = false;

              $image_handler->deleteFile($src);
            }

            if ($form == 'webcam_pic_form') {
              $effects_img = $_POST['effects_img_webcam'];

              echo "Before instantiating MergeWebcamImg class." . PHP_EOL;

              require_once(MODEL_PATH . 'MergeImages.class.php');
              require_once(MODEL_PATH . 'MergeWebcamImg.class.php');

              $image_handler = new MergeWebcamImg();

              echo "After instantiating MergeWebcamImg class." . PHP_EOL;

              $file = $image_handler->validateForm();
              echo "file: ";
              var_dump($file);
              if ($file)
                $uploaded = true;
              else
                $uploaded = false;
            }

            if ($uploaded)
            {
              $merged_img = $image_handler->mergeImages($effects_img, $file);
              echo "$merged_img" . PHP_EOL;

              if ($merged_img) {

                require_once(INCLUDES_DATABASE . "connection.inc.php");
                require_once(MODEL_PATH . "Image.class.php");

                $pdo = Db::getInstance();
                $user_id = 1;
                $result = Image::insert($pdo, $user_id, $merged_img);
                if ($result)
                  echo "Image successfully stored." . PHP_EOL;
                else {
                  deleteFile($merged_img);
                  echo "Image storage failed." . PHP_EOL;
                }
                $pdo = NULL;

              }
              else
                echo "Image merge failed." . PHP_EOL;

            }

          }

        }

      ?>
      <form id="upload_img" enctype="multipart/form-data" action="" method="post">
        <?php
          if (isset($uploaded) && $uploaded)
            echo "<p>File successfully uploaded.</p>";
          elseif (isset($uploaded) && ($uploaded === false) )
            echo "<p>File upload failed.</p>";
          else
            echo "";
          $uploaded = false;
        ?>
        <input type="hidden" name="form" value="upload_pic_form">
        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo FILE_SIZE_LIMIT; ?>">
        <p>Upload image 10Mb or less</p>
        <label>
          Pic <input type="file" name="upload_pic" />
        </label>
        <input id="effects_img" type="hidden" name="effects_img" value="" />
        <input type="submit" value="Upload Pic">
      </form>
    </div>
  </div>
  <div class="personal_gallery">
    <h2>My Pictures</h2>
    <div class="personal_gallery_pictures">
      <img id="test_img" />
      <?php
//        require(INCLUDES_DATABASE . "connection.inc.php");

        $pdo = Db::getInstance();
        $user_id = 1;
        $result = Image::findAll($pdo, $user_id);
        if ($result) {

          $stmt = $result;
          while ($row = $stmt->fetch() ) {
            echo "<div class=\"profile_gallery_img\">
                    <a href=\"image.php?controller=image&action=show&img=" . $row['image_id'] . "\">
                      <img src=\"uploads/" . $row['filename'] . "\" alt=\"\" />
                    </a>
                    <div>
                      <a href=\"?controller=profile&action=deleteImage&image_id=" . $row['image_id'] . "\">Delete</a>
                    </div>
                  </div>";
          }
          $stmt = NULL;

        }
        else {
          echo "Image gallery could not be loaded." . PHP_EOL;
        }

        $pdo = NULL;
      ?>
    </div>
  </div>
</div>
<?php
  require_once(INCLUDES_VIEWS . 'footer_top.inc.php');
?>
<script src="js/profile.js"></script>
<script src="js/profile_select_img.js"></script>
<script src="js/profile_upload_img.js"></script>
<?php
  require_once(INCLUDES_VIEWS . 'footer_bottom.inc.php');
?>
