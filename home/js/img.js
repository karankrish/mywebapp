(function ($) {
  var x=0;
  var y=0;
  var img = $("#img");
  img.css('backgroundPosition', x + 'px' + ' ' + y + 'px');
  window.setInterval(function () {
      img.css('backgroundPosition', x + 'px' + ' ' + y + 'px');
      y--;

    },90);

})(jQuery);
