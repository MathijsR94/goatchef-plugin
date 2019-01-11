(function($) {
  $(document).ready(function() {
    // Lazy loading images with A3 Lazy Loading plugin
    $.lazyLoadXT.scrollContainer = '.homepage-products__list';

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
    var detachedCards;
    var returnToEnd = false;
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
    var debounceTime = 300;
    var loadingSearch = false;
    localStorage.removeItem('shouldDisplaySnackbar');

    var translation = {
      breakfast: 'ontbijt',
      lunch: 'lunch',
      diner: 'avondeten',
      snack1: 'snack',
      snack2: '2e Snack',
      snack3: '3e Snack'
    };

    init();

    function init() {
      lastValue = getLastValue();
      hideTabs();
      attachListeners();
      activateChosenRecipeFabs();
      fillProgressMeals();
      // completeCurrentStep();
      // checkIfRecipesShouldBeHidden();
      var kcal = localStorage.getItem('kcal') || 2500;

      factory.dispatchEvent('updateKcal', kcal);
      $('.loader').addClass('hidden');
      $('.homepage-products').removeClass('hidden');
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
      // activateChosenRecipeFabs();
      fillProgressMeals();
      // completeCurrentStep();
      // checkIfRecipesShouldBeHidden();
      localStorage.setItem('numMeals', parseInt($('#num_meals').val()));
      activateNextStep();
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
      var currentSelectedRecipes = JSON.parse(localStorage.getItem('recipes'));
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

    function toggleLoadingSearch() {
      var loadingDiv = $('.tab-content .loader');
      if (loadingSearch) {
        $(loadingDiv).removeClass('hidden');
      } else {
        $(loadingDiv).addClass('hidden');
      }
    }

    function filterProducts() {
      loadingSearch = true;
      toggleLoadingSearch();
      var container = $(this)
        .parent()
        .find('.homepage-products__list')[currentIndex];

      var images = $(container).find('.card__image img');
      $(images).lazyLoadXT({ show: true });

      var value = $(this).val() || '';
      checkIfRecipesShouldBeHidden(value);
      debouncedRenderProducts(container);
    }

    var debouncedRenderProducts = debounce(function(container) {
      renderProducts(container);
    }, debounceTime);

    function renderProducts(container) {
      var visibleRecipes = 0;
      $(container).append(detachedCards);
      detachedCards = null;

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

      detachedCards = $('.card.hidden').detach();

      var emptyNode = $(container)
        .parent()
        .find('.homepage-products__empty');

      // if (visibleRecipes === 0) {
      //   $(emptyNode).removeClass('hidden');
      // } else {
      //   $(emptyNode).addClass('hidden');
      // }
      loadingSearch = false;
      toggleLoadingSearch();
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
      hideSearch();
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

      var types = ['breakfast', 'lunch', 'diner', 'snack1', 'snack2', 'snack3'];
      var isLast = types.indexOf(type) + 1 === parseInt(numMeals);

      var recipe = recipes.find(function(r) {
        return r.id === id;
      });

      var nextMealMessage = isLast ? '.' : '. Kies nu jouw volgende maaltijd.';
      var message =
        'Goede keuze! Je hebt ' +
        recipe.recipe_title +
        ' gekozen als ' +
        translation[type] +
        nextMealMessage;

      var shouldDisplaySnackbar = !localStorage.getItem(
        'shouldDisplaySnackbar'
      );
      var description = '';
      if (shouldDisplaySnackbar) {
        description =
          'Ander ' +
          translation[type] +
          '? Klik dan op ' +
          translation[type] +
          ' in het menu en kies een nieuw ' +
          translation[type] +
          '. Calorieën en voedingswaarden inzien? Bekijk jouw voortgang hieronder en zie precies hoeveel jouw lichaam binnenkrijgt met de gekozen recepten.';
        localStorage.setItem('shouldDisplaySnackbar', 'true');
      } else {
        description = isLast
          ? 'Alles is gereed. Klik op aan de slag om verder te gaan.'
          : '';
      }

      if (returnToEnd) {
        createSnackbar(
          'Je hebt ' + translation[type] + ' gewijzigd',
          '',
          'Terug naar overzicht'
        );
      } else {
        createSnackbar(
          message,
          description,
          isLast ? 'aan de slag' : 'volgende'
        );
      }

      $('.progress-nutritions').removeClass('hidden');

      var progressNutrionItem = $('[data-type=' + type + ']');
      if (progressNutrionItem) {
        $(progressNutrionItem)
          .find('[placeholder-id="recipe_title"]')
          .html(recipe.recipe_title);

        $(progressNutrionItem)
          .find('[placeholder-id="cook-time"]')
          .html(recipe.cook_time);

        $(progressNutrionItem)
          .find('[placeholder-id="num-ingredients"]')
          .html(recipe.ingredients.length);

        $(progressNutrionItem)
          .find('[placeholder-id="serving_size"]')
          .html(recipe.serving_size);

        $(progressNutrionItem)
          .find('[placeholder-id="serving_size_unit"]')
          .html(recipe.serving_unit);

        $(progressNutrionItem)
          .find('[placeholder-id="recipe_description"]')
          .attr('src', recipe.description);

        $(progressNutrionItem)
          .find('[placeholder-id="recipe_image"]')
          .attr('src', recipe.image);

        var nutritions = '';
        for (var key in recipe.nutrition) {
          nutritions +=
            '<li>' +
            recipe.nutrition[key].name +
            ' ' +
            recipe.nutrition[key].value +
            ' ' +
            recipe.nutrition[key].unit +
            '</li>';
        }

        $(progressNutrionItem)
          .find('.card__detail ul')
          .html(nutritions);
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
          protein: parseInt(protein) / parseInt(recipe.serving_size),
          fat: parseInt(fat) / parseInt(recipe.serving_size),
          carbs: parseInt(carbs) / parseInt(recipe.serving_size)
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

      // activateNextStep();
    }

    function activateNextStep() {
      var steps = $('.steps__item');
      activateChosenRecipeFabs();

      if (returnToEnd) {
        returnToEnd = false;
        return steps[steps.length - 1];
      } else {
        // Find next tab
        var stepsDone = $('.steps__item--done').length;
        $(steps[stepsDone])
          .addClass('steps__item--active')
          .removeAttr('disabled');
        return $(steps[stepsDone]);
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
      $('body').on('click', '.snackbar__button', nextTab);
      $('body').on('click', '[placeholder-id="edit-recipe"]', changeRecipe);
      $('#shopping-list').on('hide.bs.modal', function(event) {
        $('#shopping-list')
          .find('.ingredient__list-container')
          .html('');
      });
      $('body').on('click', '[data-shoppinglist]', onShoppingListClick);
      $('body').on('click', '[data-shop]', onShopButtonClick);
    }

    function onShopButtonClick() {
      window.open('https://www.ah.nl/', '_blank');
    }

    function onShoppingListClick() {
      $('#shopping-list').modal('toggle');

      var ingredients = [];
      var currentSelectedRecipes = JSON.parse(localStorage.getItem('recipes'));

      Object.keys(currentSelectedRecipes).forEach(function(mealType) {
        if (currentSelectedRecipes[mealType]) {
          var recipe = recipes.find(function(r) {
            console.log(r.id, currentSelectedRecipes[mealType]);
            return r.id === currentSelectedRecipes[mealType];
          });

          if (recipe) {
            ingredients.push({
              mealType,
              ingredients: recipe.ingredients,
              servingSize: recipe.serving_size
            });
          }
          // ingredients.push({});
        }
      });

      var ingredientsContainer = $('#shopping-list').find(
        '.ingredient__list-container'
      );

      if ($(ingredientsContainer).children().length === 0) {
        ingredients.forEach(function(meal) {
          var container = $('<div>').addClass('ingredient__list');
          var heading = $('<h3>' + translation[meal.mealType] + '</h3>');
          var list = $('<ul>');
          meal.ingredients.forEach(function(ingredient) {
            var unit = ingredient.quantity_unit
              ? ingredient.quantity_unit + ' '
              : '';
            var listItem = $(
              '<li>' +
                ingredient.quantity / meal.servingSize +
                ' ' +
                unit +
                ingredient.ingredient_title +
                '</li>'
            );
            list.append(listItem);
          });
          $(container)
            .append(heading)
            .append(list);
          $(ingredientsContainer).append(container);
        });
      }
    }

    function changeRecipe(e) {
      var parent = $(e.target).closest('.progress-nutritions__item')[0];

      if (parent) {
        returnToEnd = true;
        if (parent.dataset && parent.dataset.type) {
          goToTab(parent.dataset.type);
        }
      }
    }

    function goToTab(id) {
      if (id) {
        var target = $('[data-progress-step=' + id + ']').find('a.steps__link');
        if (target) {
          $(target).trigger('click');
          activateChosenRecipeFabs();
        }
      }
    }

    function nextTab() {
      var target = activateNextStep();
      var clickTarget = $(target).find('a.steps__link');
      $(clickTarget).trigger('click');
      hideSearch();
    }

    function hideSearch() {
      var currentStep = $('.steps__item--active').attr('data-progress-step');
      var currentIndex = tabIds.indexOf(currentStep);
      if (currentIndex >= parseInt(numMeals)) {
        $('[data-id="search-products"]').addClass('hidden');
      } else {
        $('[data-id="search-products"]').removeClass('hidden');
      }
    }
    function checkIfRecipesShouldBeHidden(value) {
      if (!value) {
        value = '';
      }

      var regex = new RegExp(value, 'i');
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
        if (!regex.test(recipe.recipe_title) && !hasIngredient) {
          recipe.hidden = true;
          return recipe;
        } else {
          recipe.hidden = false;
          // return recipe;
        }

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
        } else {
          recipe.hidden = false;
        }
        return recipe;
      });

      recipes = filteredRecipes;

      var container = $('body').find('.homepage-products__list')[currentIndex];
      debouncedRenderProducts(container);
    }
  });

  function debounce(func, wait, immediate) {
    var timeout;
    return function() {
      var context = this,
        args = arguments;
      var later = function() {
        timeout = null;
        if (!immediate) func.apply(context, args);
      };
      var callNow = immediate && !timeout;
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
      if (callNow) func.apply(context, args);
    };
  }
})(jQuery);
