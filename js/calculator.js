(function($) {
  $(document).ready(function() {
    var form = $('#homepage-calculator');
    var localStorageKcal =
      (!isNaN(localStorage.getItem('kcal')) &&
        JSON.parse(localStorage.getItem('kcal'))) ||
      2500;
    var localStorageNumMeals = JSON.parse(localStorage.getItem('numMeals'));

    if (localStorageKcal > 0) {
      $('#num_cal').val(localStorageKcal);
    }

    if (localStorageNumMeals > 0 && localStorageNumMeals < 6) {
      $('#num_meals').val(localStorageNumMeals);
    }

    makeParentsStaticForModal();
    $('#homepage-calculator .btn-primary').click(btnGroupSelect);
    $('#homepage-calculator input').on('input', calculate);
    $('#num_cal').on('change', setKcal);
    $('#num_cal').on('input', setKcal);
    $('#homepage-calculator select').on('change', calculate);

    $('#add-kcal').click(addKcalToForm);

    function setKcal() {
      var value = $(this).val();
      localStorage.setItem('kcal', value);
      GoatchefSingleton.dispatchEvent('updateTotalKcal', value);
    }
    function calculate() {
      var age = parseInt($('#age').val()) || 0;
      var length = parseInt($('#length').val()) || 0;
      var weight = parseFloat($('#weight').val()) || 0;
      var gender = $('#gender .selected').data('gender');
      var kcal = 0;

      switch (gender) {
        case 'male':
          var harrisBenedict =
            88.362 + 13.397 * weight + 4.799 * length - 5.677 * age;
          kcal = calculateKcalFromFormula(harrisBenedict);
          break;
        case 'female':
          var harrisBenedict =
            447.593 + 9.247 * weight + 3.098 * length - 4.33 * age;
          kcal = calculateKcalFromFormula(harrisBenedict);
          break;
      }

      if (age && length && weight) {
        $('#total-calories').val(kcal);
        localStorage.setItem('kcal', kcal);
        GoatchefSingleton.dispatchEvent('updateTotalKcal', kcal);
      }
    }

    function calculateKcalFromFormula(harrisBenedict) {
      var goal = parseInt($('#goal').val());
      var workSelect = parseFloat($('#workSelect').val());
      var sportSelect = parseFloat($('#sportSelect').val());

      if (workSelect !== -1 && sportSelect === -1 && goal !== -1) {
        return Math.round(harrisBenedict * workSelect + goal);
      } else if (workSelect === -1 && sportSelect !== -1 && goal !== -1) {
        return Math.round(harrisBenedict * sportSelect + goal);
      } else if (workSelect !== -1 && sportSelect !== -1 && goal !== -1) {
        return Math.round(
          harrisBenedict * ((workSelect + sportSelect) / 2) + goal
        );
      } else {
        return Math.round(harrisBenedict);
      }
    }

    /**
     * Selects the button on click in a button group
     */
    function btnGroupSelect() {
      var childClasses;
      var group;

      $(this).addClass('selected');
      group = $(this)
        .parent()
        .attr('id');
      childClasses = $('#' + group).children();
      // remove previous selected button when user select a new button in the same group button
      for (var i = 0; i < childClasses.length; i++) {
        if (this.textContent != childClasses[i].textContent) {
          $('#' + childClasses[i].id).removeClass('selected');
        }
      }

      calculate();
    }

    /**
     * Dear god, please cleanse this unholiness.
     * Because we make use of the visual builder present in the theme,
     * including a modal proved difficult.
     * By making all the parents position: static we fix this.
     */
    function makeParentsStaticForModal() {
      $(form)
        .parent()
        .parent()
        .css({
          position: 'static'
        })
        .parent()
        .css({
          position: 'static'
        })
        .parent()
        .css({
          position: 'static'
        })
        .parent()
        .css({
          position: 'static'
        });
    }

    function addKcalToForm() {
      $('#num_cal').val($('#total-calories').val());
      localStorage.setItem('kcal', $('#total-calories').val());
      localStorage.setItem('goal', $('#goal').val());
      localStorage.setItem('weight', parseFloat($('#weight').val()));
      $('#homepage-calculator').modal('toggle');
    }
  });
})(jQuery);
