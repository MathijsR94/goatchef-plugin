(function($) {
  $(document).ready(function() {
    var headerButton = $('[data-calculate-frontpage]');
    var calcButton = $('.calc-button');
    var goatchefButton = $('.goatchef-button');
    var mealChooseButton = $('.header__submit');

    $('#calculator-form').submit(function(e) {
      e.preventDefault();
      var numCals = $('#num_cal').val();
      var numMeals = $('#num_meals').val();
      var email = $('.header__input-field--email').val();

      if (numCals && numMeals) {
        $('html, body').animate(
          {
            scrollTop: $('#homepage-meals').offset().top - 300
          },
          1000
        );
      }
    });
  });
})(jQuery);
