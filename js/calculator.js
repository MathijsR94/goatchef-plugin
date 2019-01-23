(function($) {
  $(document).ready(function() {
    const form = $('#homepage-calculator');
    const localStorageKcal = GoatchefSingleton.getKcal();
    const localStorageNumMeals = GoatchefSingleton.getNumMeals();

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

    /**
     * Sets the kcal on input/change
     */
    function setKcal() {
      const value = $(this).val();
      localStorage.setItem('kcal', value);
      GoatchefSingleton.dispatchEvent('updateTotalKcal', value);
    }
    /**
     * sets total calories in the calc form
     */
    function calculate() {
      const age = parseInt($('#age').val()) || 0;
      const length = parseInt($('#length').val()) || 0;
      const weight = parseFloat($('#weight').val()) || 0;
      const gender = $('#gender .selected').data('gender');
      let kcal = 0;
      let harrisBenedict;

      switch (gender) {
        case 'male':
          harrisBenedict =
            88.362 + 13.397 * weight + 4.799 * length - 5.677 * age;
          kcal = calculateKcalFromFormula(harrisBenedict);
          break;
        case 'female':
          harrisBenedict =
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
    /**
     * Calculates kcal based on the Harris Benedict formula
     * @param {number} harrisBenedict
     */
    function calculateKcalFromFormula(harrisBenedict) {
      const goal = parseInt($('#goal').val());
      const workSelect = parseFloat($('#workSelect').val());
      const sportSelect = parseFloat($('#sportSelect').val());

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
    /**
     * Sets kcal
     */
    function addKcalToForm() {
      $('#num_cal').val($('#total-calories').val());
      localStorage.setItem('kcal', $('#total-calories').val());
      localStorage.setItem('goal', $('#goal').val());
      localStorage.setItem('weight', parseFloat($('#weight').val()));
      $('#homepage-calculator').modal('toggle');
    }
  });
})(jQuery);
