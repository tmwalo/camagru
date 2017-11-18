<?php

  abstract class MergeImages {

    protected $form_name;
    protected $validate_form_name;
    protected $validate_effects_img;
    protected $validate_file_upload;
    protected $allowed_mime_types;
    protected $merge_success;

    public function __construct()
    {
      $this->form_name = $_POST['form'];
      $this->validate_form_name = false;
      $this->validate_effects_img = false;
      $this->validate_file_upload = false;
      $this->allowed_mime_types = array('image/jpeg', 'image/pjpeg', 'image/png');
      $this->merge_success = false;
    }

    abstract public function validateForm();

    abstract protected function validateUploadedImg($file_upload);

    abstract protected function validateFormName($form_name);

    protected function validateEffectsImg($effects_img)
    {
      if (!empty($effects_img) && (file_exists($effects_img) ) )
        $this->validate_effects_img = true;
    }

    public function deleteFile($filename)
    {
      if (file_exists($filename) && is_file($filename))
        unlink($filename);
    }

    public function mergeImages($src, $dest)
    {
      $src_resource = imagecreatefrompng($src);
      $dest_resource = imagecreatefromjpeg($dest);
      $src_resource_width = imagesx($src_resource);
      $src_resource_height = imagesy($src_resource);
      $merged_img_name = "merged" . time() . ".jpg";
      $merged_img = UPLOADS_PATH . $merged_img_name;

      if (imagecopy($dest_resource, $src_resource, 0, 0, 0, 0, $src_resource_width, $src_resource_height) && imagejpeg($dest_resource, $merged_img) )
      {
        $this->merge_success = true;
        echo "Image successfully merged." . PHP_EOL;
      }
      else
        echo "Image merging failed." . PHP_EOL;

      imagedestroy($src_resource);
      imagedestroy($dest_resource);

      $this->deleteFile($dest);

      if ($this->merge_success)
        return ($merged_img_name);
      else
        return (false);
    }

  }

?>
