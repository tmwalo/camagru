<?php

  class MergeUploadImg extends MergeImages {

    public function validateForm()
    {
      $effects_img = $_POST['effects_img'];
      $file_upload = $_FILES['upload_pic'];

      $this->validateFormName($this->form_name);
      $this->validateEffectsImg($effects_img);
      $this->validateUploadedImg($file_upload);

      if ($this->validate_form_name && $this->validate_effects_img && $this->validate_file_upload)
        return (true);
      else
        return (false);
    }

    protected function validateFormName($form_name)
    {
      if (!empty($form_name) && ($form_name == "upload_pic_form") )
        $this->validate_form_name = true;
    }

    protected function validateUploadedImg($file_upload) {

      if (isset($file_upload) ) {
        $filename = $file_upload["tmp_name"];

        if (file_exists($filename) && (filesize($filename) < FILE_SIZE_LIMIT) ) {
          $fileinfo = finfo_open(FILEINFO_MIME_TYPE);

          if (in_array(finfo_file($fileinfo, $filename), $this->allowed_mime_types) )
            $this->validate_file_upload = true;
          finfo_close($fileinfo);
        }
      }

    }

  }

?>
