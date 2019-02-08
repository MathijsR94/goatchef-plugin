(function($) {
  $(document).ready(function() {
    var kcal = parseInt(localStorage.getItem('kcal')) || 2500;
    var userKcal = GoatchefSingleton.calculateTotalKcal(
      GoatchefSingleton.user.kcal
    );
    var macros = GoatchefSingleton.macros;
    var user = GoatchefSingleton.user;
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

    document.addEventListener('updateMacros', function(e) {
      setProgress();
    });

    function setProgress() {
      var carbs = userKcal.carbs;
      var carbsNeeded = user.calculateCarbsNeeded();
      var protein = userKcal.protein;
      var proteinNeeded = user.calculateProteinNeeded();
      var fat = userKcal.fat;
      var fatNeeded = user.calculateFatNeeded();
      var totalKcal = user.calculateTotalKcalNeeded();

      // Carbs
      setProgressBarValues({
        el: $('progress.macro-1'),
        amountNeeded: carbsNeeded,
        value: carbs,
        unit: 'g'
      });
      // Proteins
      setProgressBarValues({
        el: $('progress.macro-2'),
        amountNeeded: proteinNeeded,
        value: protein,
        unit: 'g'
      });
      // Fat
      setProgressBarValues({
        el: $('progress.macro-3'),
        amountNeeded: fatNeeded,
        value: fat,
        unit: 'g'
      });
      // Kcal
      setProgressBarValues({
        el: $('progress.macro-4'),
        amountNeeded: kcal,
        value: totalKcal,
        unit: 'kCal'
      });

      if (carbs > carbsNeeded) {
        $('progress.macro-1').addClass('macro__overflow');
      } else {
        $('progress.macro-1').removeClass('macro__overflow');
      }
      if (protein > proteinNeeded) {
        $('progress.macro-2').addClass('macro__overflow');
      } else {
        $('progress.macro-2').removeClass('macro__overflow');
      }
      if (fat > fatNeeded) {
        $('progress.macro-3').addClass('macro__overflow');
      } else {
        $('progress.macro-3').removeClass('macro__overflow');
      }
      if (totalKcal > kcal) {
        $('progress.macro-4').addClass('macro__overflow');
      } else {
        $('progress.macro-4').removeClass('macro__overflow');
      }
    }
  });

  function setProgressBarValues({ el, amountNeeded, value, unit }) {
    $(el)
      .attr('max', amountNeeded)
      .attr('value', value)
      .parent()
      .find('.macro__total')
      .html(Math.floor(amountNeeded) + ' ' + unit)
      .parent()
      .find('.macro__current')
      .html(Math.floor(value))
      .css({
        left:
          value / (amountNeeded / 100) > 100
            ? 100 + '%'
            : value / (amountNeeded / 100) + '%'
      });
  }
})(jQuery);
