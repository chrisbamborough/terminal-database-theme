jQuery(document).ready(function ($) {
  // About expander functionality
  $("#about-trigger").hover(
    function () {
      // Mouse enter
      $("#about-expander").addClass("expanded");
    },
    function () {
      // Mouse leave
      $("#about-expander").removeClass("expanded");
    }
  );

  // Keep expander open when hovering over it
  $("#about-expander").hover(
    function () {
      $(this).addClass("expanded");
    },
    function () {
      $(this).removeClass("expanded");
    }
  );
});
