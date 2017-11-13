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

        function validateUploadPicForm()
        {
          $validate_form_name = false;
          $validate_effects_img = false;
          $validate_file_upload = false;

          $form_name = $_POST['form'];
          $effects_img = $_POST['effects_img'];
          $file_upload = $_FILES['upload_pic'];

          if (!empty($form_name) && ($form_name == "upload_pic_form") )
            $validate_form_name = true;
          if (!empty($effects_img) && (file_exists($effects_img) ) )
            $validate_effects_img = true;
          if (isset($file_upload) ) {
            $allowed_mime_types = array('image/jpeg', 'image/pjpeg', 'image/png');
            $filename = $file_upload["tmp_name"];

            if (file_exists($filename) && (filesize($filename) < FILE_SIZE_LIMIT) ) {
              $fileinfo = finfo_open(FILEINFO_MIME_TYPE);

              if (in_array(finfo_file($fileinfo, $filename), $allowed_mime_types) )
                $validate_file_upload = true;
              finfo_close($fileinfo);
            }
          }

          if ($validate_form_name && $validate_effects_img && $validate_file_upload)
            return (true);
          else
            return (false);

        }

        function deleteFile($filename)
        {
          if (file_exists($filename) && is_file($filename))
            unlink($filename);
        }

        function decodeImgResource($img_resource)
        {
          $img_resource = str_replace('data:image/jpeg;base64,', '', $img_resource);
          $img_resource = str_replace(' ', '+', $img_resource);
          $img_data = base64_decode($img_resource);
          return ($img_data);
        }

        function validateWebcamPicForm()
        {
          $validate_form_name = false;
          $validate_effects_img = false;
          $validate_file_upload = false;

          $form_name = $_POST['form'];
          $effects_img = $_POST['effects_img_webcam'];
          $file_upload_resource = $_POST['webcam_pic'];

          if (!empty($form_name) && ($form_name == "webcam_pic_form") )
            $validate_form_name = true;
          if (!empty($effects_img) && (file_exists($effects_img) ) )
            $validate_effects_img = true;
          if (!empty($file_upload_resource) ) {
            $allowed_mime_types = array('image/jpeg', 'image/pjpeg', 'image/png');
            $img_data = decodeImgResource($file_upload_resource);
            $filename = UPLOADS_PATH . time() . '.jpg';
            $create_img = file_put_contents($filename, $img_data);

            if ($create_img && (filesize($filename) < FILE_SIZE_LIMIT) ) {
              $fileinfo = finfo_open(FILEINFO_MIME_TYPE);

              if (in_array(finfo_file($fileinfo, $filename), $allowed_mime_types) )
                $validate_file_upload = true;
              finfo_close($fileinfo);
            }
          }

          if ($validate_form_name && $validate_effects_img && $validate_file_upload)
            return ($filename);
          else
            return (false);

        }

        function mergeImages($src, $dest)
        {
          $src_resource = imagecreatefrompng($src);
          $dest_resource = imagecreatefromjpeg($dest);
          $src_resource_width = imagesx($src_resource);
          $src_resource_height = imagesy($src_resource);

          if (imagecopy($dest_resource, $src_resource, 0, 0, 0, 0, $src_resource_width, $src_resource_height) )
            imagejpeg($dest_resource, UPLOADS_PATH . "merged" . time() . ".jpg");
          else
            echo "Image merging failed.\n";

          imagedestroy($src_resource);
          imagedestroy($dest_resource);

          deleteFile($dest);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

          if (isset($_POST['form'])) {

            $form = $_POST['form'];

            if ($form == 'upload_pic_form') {
              $file_upload = $_FILES['upload_pic'];
              $src = $file_upload['tmp_name'];
              $effects_img = $_POST['effects_img'];
              $dest = UPLOADS_PATH . $file_upload['name'];
              $file = $dest;

              if (validateUploadPicForm() && move_uploaded_file($src, $dest) )
                $uploaded = true;
              else
                $uploaded = false;

              deleteFile($src);
            }

            if ($form == 'webcam_pic_form') {
              $effects_img = $_POST['effects_img_webcam'];

              $file = validateWebcamPicForm();
              if ($file)
                $uploaded = true;
              else
                $uploaded = false;
            }

            if ($uploaded)
            {
              mergeImages($effects_img, $file);
              deleteFile($file);
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
