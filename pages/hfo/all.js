// Hide the loader once the page content is fully loaded
window.addEventListener('load', function () {
    var loader = document.querySelector('.loader');
    loader.style.display = 'none';
});
var scroll = new SmoothScroll('a[href*="#"]', {
    speed: 800
  });