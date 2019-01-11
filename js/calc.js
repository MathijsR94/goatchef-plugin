(function ($) {
  $(document).ready(function () {
    var submit = $('#submit');
    var age = $('#age');
    var length = $('#length');
    var weight = $('#weight');
    var days = $('#days');
    var daysSport = $('#daysSport');
    var form = $('#homepage-calculator');

    // Dear god, please cleanse this unholiness.
    $(form).parent().parent().css({
      position: 'static'
    });
    $(form).parent().parent().parent().css({
      position: 'static'
    });
    $(form).parent().parent().parent().parent().css({
      position: 'static'
    });
    $(form).parent().parent().parent().parent().parent().css({
      position: 'static'
    });

    $(".btn-primary").click(function () {
      var childClasses, group;
      // add class "selected" to a clicked button
      $(this).addClass("selected");
      // get the parent's id of button element
      group = $(this).parent().attr('id');
      // store all element of selected group
      childClasses = $("#" + group).children();
      // remove previous selected button when user select a new button in the same group button
      for (var i = 0; i < childClasses.length; i++) {
        if (this.textContent != childClasses[i].textContent) {
          $("#" + childClasses[i].id).removeClass("selected");
        }
      }
    });

    function ageList() {
      var ageOptions = "";
      for (var i = 12; i < 121; i++) {
        ageOptions += "<option>" + i + "</option>"
      }
      $(age).html(ageOptions);
    }
    ageList()

    function lengthList() {
      var lengthOptions = "";
      for (var j = 100; j < 221; j++) {
        lengthOptions += "<option>" + j + "</option>"
      }
      $(length).html(lengthOptions);
    }
    lengthList()

    function weightList() {
      var weightOptions = "";
      for (var k = 30; k < 201; k++) {
        weightOptions += "<option>" + k + "</option>"
      }
      $(weight).html(weightOptions);
    }
    weightList()

    function daysList() {
      var daysOptions = "";
      for (var l = 1; l < 8; l++) {
        daysOptions += "<option>" + l + "</option>"
      }
      $(days).html(daysOptions);
    }
    daysList()

    function daysListSport() {
      var daysOptionsSport = "";
      for (var m = 1; m < 8; m++) {
        daysOptionsSport += "<option>" + m + "</option>"
      }
      $(daysSport).html(daysOptionsSport);
    }
    daysListSport()

    function calculate() {
      var gender = $("#gender").val();
      var goal = $("#goal").val();
      var workSelect = $("#workSelect").val();
      var sportSelect = $("#sportSelect").val();
      //var pal = ($("#workSelect").val()+$("#days").val()*0.25+$("#sportSelect").val()+$("#daysSport").val()*0.25)/2;
      var pal = (+workSelect + (+(days.val() * 0.025)) + (+sportSelect) + (+(daysSport.val()) * 0.025)) / 2;

      switch (gender) {
        case 'men':
          kcal = Math.round((88.362 + weight.val() * 13.397 + length.val() * 4.799 - age.val() * 5.677) * pal * goal);
          break;
        case 'women':
          kcal = Math.round((447.593 + weight.val() * 9.247 + length.val() * 3.098 - age.val() * 4.33) * pal * goal);
          break;
      }
      $("#textbox").val(kcal);
    }

    $(submit).on('click', function (e) {
      e.preventDefault();
      calculate()
    });
  })
})(jQuery);