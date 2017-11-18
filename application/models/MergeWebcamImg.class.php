<?php

  class MergeWebcamImg extends MergeImages {

    public function validateForm()
    {
      $effects_img = $_POST['effects_img_webcam'];
      $file_upload_resource = $_POST['webcam_pic'];

      $this->validateFormName($this->form_name);
      $this->validateEffectsImg($effects_img);
      $filename = $this->validateUploadedImg($file_upload_resource);

      echo "form_name: ";
      var_dump($this->validate_form_name);
      echo "effects_img: ";
      var_dump($this->validate_effects_img);
      echo "file_upload: ";
      var_dump($this->validate_file_upload);

      if ($this->validate_form_name && $this->validate_effects_img && $this->validate_file_upload)
        return ($filename);
      else
        return (false);
    }

    protected function validateFormName($form_name)
    {
      if (!empty($form_name) && ($form_name == "webcam_pic_form") )
        $this->validate_form_name = true;
    }

    protected function validateUploadedImg($file_upload_resource) {

      if (!empty($file_upload_resource) ) {
        $img_data = $this->decodeImgResource($file_upload_resource);
        $filename = UPLOADS_PATH . time() . '.jpg';
        $create_img = file_put_contents($filename, $img_data);

        if ($create_img && (filesize($filename) < FILE_SIZE_LIMIT) ) {
          $fileinfo = finfo_open(FILEINFO_MIME_TYPE);

          if (in_array(finfo_file($fileinfo, $filename), $this->allowed_mime_types) )
            $this->validate_file_upload = true;
          finfo_close($fileinfo);
        }
      }

      return ($filename);

    }

    private function decodeImgResource($img_resource)
    {
      $img_resource = str_replace('data:image/jpeg;base64,', '', $img_resource);
      $img_resource = str_replace(' ', '+', $img_resource);
      $img_data = base64_decode($img_resource);
      return ($img_data);
    }

  }

?>
