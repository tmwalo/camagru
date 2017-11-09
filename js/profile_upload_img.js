function checkImgSelected() {
  var active;
  var activeImg;
  var imgSrc;

  active = document.querySelector('.active');
  if (active)
    return (true);
  else
    return (false);
}

function uploadImg(hiddenFormId, form) {

  var activeImg;
  var imgSrc;
  var hiddenInput;

  if (checkImgSelected()) {
    activeImg = document.querySelector('.active');
    imgSrc = activeImg.getAttribute('src');
    hiddenInput = document.getElementById(hiddenFormId);
    hiddenInput.value = imgSrc;
    form.submit();
  }

}

var uploadForm;
uploadForm = document.getElementById('upload_img');
uploadForm.addEventListener('submit', function(e) {
  e.preventDefault();
  uploadImg('upload_img', uploadForm);
}, false);

var webCamPicForm;
webCamPicForm = document.getElementById('take_pic_form');
webCamPicForm.addEventListener('submit', function(e) {
  e.preventDefault();
  uploadImg('take_pic_form', webCamPicForm);
}, false);