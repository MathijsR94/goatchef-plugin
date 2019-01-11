(function($) {
  $(document).ready(function() {
    var kcal = parseInt(localStorage.getItem('kcal')) || 2500;
    var userKcal = GoatchefSingleton.calculateTotalKcal(GoatchefSingleton.kcal);
    var macros = GoatchefSingleton.macros;
    var numMeals =
      parseInt(localStorage.getItem('numMeals')) ||
      parseInt($('num_meals').val()) ||
      3;

    var userGoal =
      GoatchefSingleton.macroMap[localStorage.getItem('goal')] ||
      GoatchefSingleton.macroMap['0'];
    // setProgress();

    document.addEventListener('updateNumMeals', function(e) {
      numMeals = parseInt(e.detail);
      setProgress();
    });

    document.addEventListener('updateKcal', function(e) {
      userKcal = e.detail;
      setProgress();
    });

    document.addEventListener('updateTotalKcal', function(e) {
      kcal = e.detail;
      setProgress();
    });

    function setProgress() {
      var carbs = userKcal.carbs;
      var carbsNeeded = kcal * macros[userGoal].gram.carbs;
      var protein = userKcal.protein;
      var proteinNeeded = kcal * macros[userGoal].gram.protein;
      var fat = userKcal.fat;
      var fatNeeded = kcal * macros[userGoal].gram.fat;

      var totalKcal =
        carbs / macros[userGoal].gram.carbs +
        protein / macros[userGoal].gram.protein +
        fat / macros[userGoal].gram.fat;

      $('progress.macro-1')
        .attr('max', carbsNeeded)
        .attr('value', carbs)
        .parent()
        .find('.macro__total')
        .html(Math.floor(carbsNeeded) + ' g')
        .parent()
        .find('.macro__current')
        .html(Math.floor(carbs))
        .css({ left: carbs / (carbsNeeded / 100) + '%' });
      $('progress.macro-2')
        .attr('max', proteinNeeded)
        .attr('value', protein)
        .parent()
        .find('.macro__total')
        .html(Math.floor(proteinNeeded) + ' g')
        .parent()
        .find('.macro__current')
        .html(Math.floor(protein))
        .css({ left: protein / (proteinNeeded / 100) + '%' });
      $('progress.macro-3')
        .attr('max', fatNeeded)
        .attr('value', fat)
        .parent()
        .find('.macro__total')
        .html(Math.floor(fatNeeded) + ' g')
        .parent()
        .find('.macro__current')
        .html(Math.floor(fat))
        .css({ left: fat / (fatNeeded / 100) + '%' });
      $('progress.macro-4')
        .attr('max', kcal)
        .attr('value', totalKcal)
        .parent()
        .find('.macro__total')
        .html(kcal + ' kCal')
        .parent()
        .find('.macro__current')
        .html(Math.floor(totalKcal))
        .css({ left: totalKcal / (kcal / 100) + '%' });

      if (carbs > carbsNeeded) {
        $('progress.macro-1').addClass('macro__overflow');
      } else if (protein > proteinNeeded) {
        $('progress.macro-2').addClass('macro__overflow');
      } else if (fat > fatNeeded) {
        $('progress.macro-3').addClass('macro__overflow');
      }
    }
  });
})(jQuery);
