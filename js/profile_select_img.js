var effects;

effects = document.getElementById('effects');
effects.addEventListener('click', function(e) {
  var currentlyActive;
  var newlyActive;

  e.preventDefault();
  currentlyActive = document.querySelector('.active');
  newlyActive = e.target;
  currentlyActive.setAttribute('class', 'effects_img');
  newlyActive.setAttribute('class', 'effects_img active');
}, false);
