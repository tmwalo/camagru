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
      <div class="effects_img active">
        <img src="images/5-2-snapchat-filters-png-image-thumb.png" alt="" />
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
      <button id="take_pic_btn">Take Pic</button>
      <form id="take_pic_form" action="" method="post">
        <input type="hidden" name="form" value="webcam_pic_form">
        <input id="effects_img_webcam" type="hidden" name="effects_img_webcam" value="" />
        <input id="hidden_webcam_pic"type="hidden" name="webcam_pic">
      </form>
      <canvas id="canvas"></canvas>
      <p>or</p>
      <?php

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

          if (isset($_POST['form'])) {

            $form = $_POST['form'];

            if ($form == 'upload_pic_form') {

              $not_empty = false;
              $exists = false;
              $size = false;
              $type = false;
              $uploaded = false;

              $upload = $_FILES['upload_pic'];
              if (isset($upload))
                $not_empty = true;
              $filename = $upload['tmp_name'];
              if (file_exists($filename))
                $exists = true;
              $max_size = 10000000;
              $actual_size = $upload['size'];
              if ($actual_size < $max_size)
                $size = true;
              $allowed = array('image/jpeg', 'image/pjpeg', 'image/png');
              $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
              if (in_array(finfo_file($fileinfo, $filename), $allowed))
                $type = true;
              finfo_close($fileinfo);

              if ($not_empty && $exists && $size && $type) {

                $file = "uploads/{$upload['name']}";
                if (move_uploaded_file($filename, $file))
                  $uploaded = true;

              }

              if (file_exists($filename) && is_file($filename))
                unlink($filename);

              $effects_img = $_POST['effects_img'];

            }

            if ($form == 'webcam_pic_form') {
              $upload_dir = "uploads/";
              $img = $_POST['webcam_pic'];
              $img = str_replace('data:image/jpeg;base64,', '', $img);
              $img = str_replace(' ', '+', $img);
              $data = base64_decode($img);
              $file = $upload_dir . time() . ".jpeg";
              $uploaded = file_put_contents($file, $data);
              if ($uploaded === false) {
                echo "File upload failed big time.";
              }
              else {
                echo "File upload succeeded.";
              }

              $effects_img = $_POST['effects_img_webcam'];
            }

            /* Merge Images */

            if ($uploaded) {

              echo "Merge Images\n";

              $src = $effects_img;
              $dest = $file;
              echo "src: $src\n";
              echo "dest: $dest\n";
              $src = imagecreatefrompng($effects_img);
              $dest = imagecreatefromjpeg($file);
              echo "src after resource creation: $src\n";
              echo "dest after resource creation: $dest\n";
              $src_width = imagesx($src);
              $src_height = imagesy($src);

              if (imagecopy($dest, $src, 0, 0, 0, 0, $src_width, $src_height)) {
                imagejpeg($dest, "uploads/merge" . time() . ".jpg");
              }
              else {
                echo "Image merging failed.\n";
              }

              imagedestroy($src);
              imagedestroy($dest);

            }

          }

        }

      ?>
      <form id="upload_img" enctype="multipart/form-data" action="" method="post">
        <?php
          if (isset($uploaded) && $uploaded)
            echo "<p>File successfully uploaded.</p>";
          else
            echo "";
          $uploaded = false;
        ?>
        <input type="hidden" name="form" value="upload_pic_form">
        <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
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
