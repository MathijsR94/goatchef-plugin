(function($) {
  $(document).ready(function() {
    const headerButton = $('[data-calculate-frontpage]');
    const calcButton = $('.calc-button');
    const goatchefButton = $('.goatchef-button');
    const mealChooseButton = $('.header__submit');

    $('#calculator-form').submit(function(e) {
      e.preventDefault();
      const numCals = $('#num_cal').val();
      const numMeals = $('#num_meals').val();
      const email = $('.header__input-field--email').val();

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
