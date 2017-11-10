var video;
var canvas;
var test_img;
var take_pic_btn;
var width;
var height;

width = 400;
height = 400;

video = document.getElementById('video');
canvas = document.getElementById('canvas');
test_img = document.getElementById('test_img');
take_pic_btn = document.getElementById('take_pic_btn');

if (navigator.mediaDevices === undefined) {
  navigator.mediaDevices = {};
}

if (navigator.mediaDevices.getUserMedia === undefined) {
  navigator.mediaDevices.getUserMedia = function(constraints) {

    var getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
    if (!getUserMedia)
      return (Promise.reject(new Error('getUserMedia is not supported by this browser')));
    return (new Promise(function(resolve, reject) {
      getUserMedia.call(navigator, constraints, resolve, reject);
    }));

  };
}

navigator.mediaDevices.getUserMedia({video: true, audio: false})
.then(function(stream) {
  if ("srcObject" in video)
    video.srcObject = stream;
  else
    video.src = window.URL.createObjectURL(stream);
  video.addEventListener('loadedmetadata', function() {
    video.play();
  }, false);
})
.catch(function(err) {
  console.log(err.name + ": " + err.message);
});

video.setAttribute('width', width);
video.setAttribute('height', height);
canvas.setAttribute('width', width);
canvas.setAttribute('height', height);

function clearImage() {
  var context;
  var data;

  context = canvas.getContext('2d');
  context.fillStyle = "#AAA";
  context.fillRect(0, 0, canvas.width, canvas.height);
  context.drawImage(video, 0, 0, width, height);
  data = canvas.toDataURL('image/png');
  test_img.setAttribute('src', data);
}

function takepicture() {
  var context;
  var data;
  var takePicForm;
  var hiddenWebcamPic;

  context = canvas.getContext('2d');
  context.drawImage(video, 0, 0, width, height);
  data = canvas.toDataURL('image/jpeg');
  hiddenWebcamPic = document.getElementById('hidden_webcam_pic');
  hiddenWebcamPic.value = data;
  takePicForm = document.getElementById('take_pic_form');
  takePicForm.submit();
  test_img.setAttribute('src', data);
}

take_pic_btn.addEventListener('click', function(e) {
  e.preventDefault();
  clearImage();
  takepicture();
});
