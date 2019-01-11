(function($) {
  $(document).ready(function() {
    var currentSelectedRecipes = JSON.parse(localStorage.getItem('recipes'));
    var progressNutrionItems = $('.progress-nutritions__item');
    var recipes = JSON.parse(gcProgress.recipes);

    $('.progress-nutritions').on('click', function() {
      $('.progress-nutritions').toggleClass('show-progress');
    });

    if (currentSelectedRecipes) {
      for (var recipe in currentSelectedRecipes) {
        if (currentSelectedRecipes[recipe]) {
          $('.progress-nutritions').removeClass('hidden');
        }
      }
    }

    progressNutrionItems.each(function(key) {
      var item = progressNutrionItems[key];
      var type = $(progressNutrionItems[key]).attr('data-type');

      if (currentSelectedRecipes && type && currentSelectedRecipes[type]) {
        var recipe = recipes.find(function(r) {
          return r.id === currentSelectedRecipes[type];
        });

        if (recipe && item) {
          $(item)
            .find('.progress-nutritions__title')
            .html(recipe.recipe_title);
        }
      }
    });
  });
})(jQuery);
