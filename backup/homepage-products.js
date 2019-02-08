(function($) {
  $(document).ready(function() {
    var recipes = JSON.parse(gcProducts.recipes);
    var slicedRecipes = recipes.slice(0, recipes.length);
    var productContainers = $('.homepage-products__list');
    var input = $('.homepage-products').find('[data-id="search-products"]');
    var currentIndex = 0;
    var currentSelectedRecipes = JSON.parse(localStorage.getItem('recipes'));
    var progressNutrionItems = $('.progress-nutritions__item');
    var steps = $('.steps__item');
    var numMeals = $('#num_meals').val();
    var detachedTabs;
    var tabIds = [
      'breakfast',
      'lunch',
      'diner',
      'snack1',
      'snack2',
      'snack3',
      'end'
    ];
    var lastValue;
    var factory = GoatchefSingleton;

    init();

    function init() {
      lastValue = getLastValue();
      hideTabs();
      attachListeners();
      activateChosenRecipeFabs();
      fillProgressMeals();
      completeCurrentStep();
      checkIfRecipesShouldBeHidden();
      var kcal = localStorage.getItem('kcal') || 2500;

      factory.dispatchEvent('updateKcal', kcal);
    }

    function hideTabs() {
      steps.each(function(step) {
        // always show last tab
        if (step !== steps.length - 1) {
          $(steps[step]).addClass('hidden');
        }
      });

      for (var i = 0; i < numMeals; i++) {
        $(steps[i]).removeClass('hidden');
      }

      detachedTabs = $('.steps .steps__item.hidden').detach();
    }

    function changeNumMeals() {
      var steps = $('.steps .steps__item');

      detachedTabs.insertAfter(steps[numMeals - 1]);
      numMeals = $('#num_meals').val();
      hideTabs();
      activateChosenRecipeFabs();
      fillProgressMeals();
      completeCurrentStep();
      checkIfRecipesShouldBeHidden();
      localStorage.setItem('numMeals', parseInt($('#num_meals').val()));
    }

    function fillProgressMeals() {
      var progressNutritionItems = $('.progress-nutritions__item');
      $(progressNutritionItems).addClass('hidden');
      $(progressNutritionItems[progressNutritionItems.length - 1]).removeClass(
        'hidden'
      );
      for (var i = 0; i < numMeals; i++) {
        $(progressNutritionItems[i]).removeClass('hidden');
      }
    }

    function activateChosenRecipeFabs() {
      for (var key in currentSelectedRecipes) {
        if (currentSelectedRecipes[key]) {
          var el = $(
            '[data-recipe=' + key + '-' + currentSelectedRecipes[key] + ']'
          );
          $(el)
            .find('.fab')
            .addClass('check');
        }
      }
    }

    function filterProducts() {
      var container = $(this)
        .parent()
        .find('.homepage-products__list')[currentIndex];

      var value = $(this).val() || '';
      checkIfRecipesShouldBeHidden(value);
      renderProducts(container);
    }

    function renderProducts(container) {
      var visibleRecipes = 0;

      var type = $(container)
        .parent()
        .attr('id');
      var products = Object.keys(recipes).map(function(key) {
        var recipe = $('[data-recipe=' + type + '-' + recipes[key].id + ']');

        if (!recipes[key].hidden) {
          visibleRecipes++;
        }

        recipes[key].hidden
          ? $(recipe).addClass('hidden')
          : $(recipe).removeClass('hidden');
      });

      var emptyNode = $(container)
        .parent()
        .find('.homepage-products__empty');

      if (visibleRecipes === 0) {
        $(emptyNode).removeClass('hidden');
      } else {
        $(emptyNode).addClass('hidden');
      }
    }

    function activateTab(e) {
      activateChosenRecipeFabs();
      e.preventDefault();
      var target = $(e.currentTarget)
        .parent()
        .attr('data-progress-step');
      currentIndex = tabIds.indexOf(target);
      if (target) {
        tabIds.map(function(tab) {
          $('#' + tab).addClass('hidden');
          $('[data-progress-step=' + tab + ']').removeClass(
            'steps__item--active'
          );
        });
        $('#' + target).removeClass('hidden');
        $('[data-progress-step=' + target + ']').addClass(
          'steps__item--active'
        );
      }
      checkIfRecipesShouldBeHidden();
    }

    function toggleViewMoreRecipe() {
      $(this)
        .closest('.card')
        .find('.card__detail')
        .toggleClass('hidden');
    }

    function onFabButtonClick() {
      $('.fab').removeClass('check');
      $(this).addClass('check');
      var id = $(this).attr('data-id');
      var type = $(this)
        .closest('[data-recipe]')
        .attr('data-recipe')
        .split('-')[0];

      var currentValues = JSON.parse(localStorage.getItem('recipes'));
      var newValues = Object.assign(
        {
          breakfast: '',
          lunch: '',
          diner: '',
          snack1: '',
          snack2: '',
          snack3: ''
        },
        currentValues,
        { [type]: id }
      );

      localStorage.setItem('recipes', JSON.stringify(newValues));

      factory.setRecipes(newValues);
      // factory.dispatchEvent('updateNumMeals', $('#num_meals').val());

      var translation = {
        breakfast: 'ontbijt',
        lunch: 'lunch',
        diner: 'avondeten',
        snack1: 'snack 1',
        snack2: 'snack 2',
        snack3: 'snack 3'
      };

      var recipe = recipes.find(function(r) {
        return r.id === id;
      });
      var shortMessage =
        recipe.recipe_title + ' is ingesteld als ' + translation[type];
      createSnackbar(shortMessage);

      $('.progress-nutritions').removeClass('hidden');

      var progressNutrionItem = $('[data-type=' + type + ']');
      if (progressNutrionItem) {
        $(progressNutrionItem)
          .find('.progress-nutritions__title')
          .html(recipe.recipe_title);
      }

      var protein =
        recipe.nutrition.find(function(n) {
          return n.name === 'eiwit';
        }).value || 0;
      var fat =
        recipe.nutrition.find(function(n) {
          return n.name === 'vet';
        }).value || 0;
      var carbs =
        recipe.nutrition.find(function(n) {
          return n.name === 'koolhydraten';
        }).value || 0;

      factory.setKcal(
        {
          protein: parseInt(protein),
          fat: parseInt(fat),
          carbs: parseInt(carbs)
        },
        type
      );

      checkIfRecipesShouldBeHidden();

      // factory.completeRecipe(type);
      completeCurrentStep();
      activateNextStep();
    }

    function completeCurrentStep() {
      var newValues = JSON.parse(localStorage.getItem('recipes'));
      for (var newValue in newValues) {
        if (newValues[newValue]) {
          $('[data-progress-step=' + newValue + ']')
            .removeAttr('disabled')
            .addClass('steps__item--done');
          lastValue = newValue;
        }
      }

      activateNextStep();
    }

    function activateNextStep() {
      var newValues = JSON.parse(localStorage.getItem('recipes'));
      if (!newValues) {
        return;
      }

      var keys = Object.keys(newValues);
      var currentIndex = newValues[keys[getLastValue()]];
      if (currentIndex) {
        var nextIndex = currentIndex + 1;
        var nextItem = keys[nextIndex];

        if (nextItem) {
          $('[data-progress-step=' + getLastValue() + ']');
          $('#' + getLastValue()).addClass('hidden');
        }
      } else {
        // Find next tab
        var stepsDone = $('.steps__item--done').length;
        var steps = $('.steps__item');
        $(steps[stepsDone])
          .addClass('steps__item--active')
          .removeAttr('disabled');
      }
    }

    function getLastValue() {
      var localStorageRecipes = JSON.parse(localStorage.getItem('recipes'));
      var lastLocalStorageValue;
      var isFound = false;
      for (var key in localStorageRecipes) {
        if (!localStorageRecipes[key] && !isFound) {
          lastLocalStorageValue = localStorageRecipes[lastLocalStorageValue];
          isFound = true;
        }

        if (!isFound) {
          lastLocalStorageValue = key;
        }
      }

      return parseInt(lastLocalStorageValue);
    }

    function attachListeners() {
      input.on('input', filterProducts);
      $('body').on('change', '#num_meals', changeNumMeals);
      $('body').on('click', '.card__button', toggleViewMoreRecipe);
      $('body').on('click', '.fab', onFabButtonClick);
      $('body').on('click', '[data-progress-step] a', activateTab);
    }

    function checkIfRecipesShouldBeHidden(value) {
      if (!value) {
        value = '';
      }

      var regex = new RegExp(value, 'gi');
      var kcal = localStorage.getItem('kcal') || 2500;
      var userGoal = localStorage.getItem('goal') || GoatchefSingleton.goal;
      var userKcal = GoatchefSingleton.calculateTotalKcal(
        GoatchefSingleton.kcal
      );
      var macros = GoatchefSingleton.macros;
      var mappedGoal = GoatchefSingleton.macroMap[userGoal];
      var filteredRecipes = recipes.map(function(recipe) {
        var hasIngredient = recipe.ingredients.some(function(ingredient) {
          return regex.test(ingredient.ingredient_title);
        });
        var carbs = userKcal.carbs;
        var carbsNeeded = kcal * macros[mappedGoal].gram.carbs;
        var recipeCarbs = recipe.nutrition.find(function(n) {
          return n.name === 'koolhydraten';
        });

        var carbsExceeded = false;
        if (recipeCarbs && recipeCarbs.value) {
          carbsExceeded = carbsNeeded - carbs - parseInt(recipeCarbs.value) < 0;
        }

        var protein = userKcal.protein;
        var proteinNeeded = kcal * macros[mappedGoal].gram.protein;
        var recipeProtein = recipe.nutrition.find(function(n) {
          return n.name === 'eiwit';
        });

        var proteinExceeded = false;
        if (recipeProtein && recipeProtein.value) {
          proteinExceeded =
            proteinNeeded - protein - parseInt(recipeProtein.value) < 0;
        }

        var fat = userKcal.fat;
        var fatNeeded = kcal * macros[mappedGoal].gram.fat;
        var recipeFat = recipe.nutrition.find(function(n) {
          return n.name === 'vet';
        });

        var fatExceeded = false;
        if (recipeFat && recipeFat.value) {
          fatExceeded = fatNeeded - fat - parseInt(recipeFat.value) < 0;
        }

        if (proteinExceeded || fatExceeded || carbsExceeded) {
          recipe.hidden = true;
        }

        if (!regex.test(recipe.recipe_title) && !hasIngredient) {
          recipe.hidden = true;
        }

        return recipe;
      });

      recipes = filteredRecipes;

      var container = $('body').find('.homepage-products__list')[currentIndex];
      renderProducts(container);
    }
  });
})(jQuery);
