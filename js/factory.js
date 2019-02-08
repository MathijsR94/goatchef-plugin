var GoatchefSingleton = (function() {
  var instance;
  localStorage.setItem(
    'recipes',
    JSON.stringify({
      breakfast: '',
      lunch: '',
      diner: '',
      snack1: '',
      snack2: '',
      snack3: ''
    })
  );

  var selectedRecipes = {
    breakfast: '',
    lunch: '',
    diner: '',
    snack1: '',
    snack2: '',
    snack3: ''
  };
  var recipes = JSON.parse(gcMacros.recipes);
  var weight = localStorage.getItem('weight') || '0';
  var goal = localStorage.getItem('goal') || '0'; // Healthy Balance
  var macros = gcMacros.macros;

  var events = {
    updateMacros: 'updateMacros',
    updateNumMeals: 'updateNumMeals',
    updateKcal: 'updateKcal',
    updateTotalKcal: 'updateTotalKcal'
  };
  var kcal = {
    breakfast: { protein: 0, fat: 0, carbs: 0 },
    lunch: { protein: 0, fat: 0, carbs: 0 },
    diner: { protein: 0, fat: 0, carbs: 0 },
    snack1: { protein: 0, fat: 0, carbs: 0 },
    snack2: { protein: 0, fat: 0, carbs: 0 },
    snack3: { protein: 0, fat: 0, carbs: 0 }
  };
  var totalKcal = parseInt(localStorage.getItem('kcal')) || 0;
  var completedRecipes = [];
  var macroMap = {
    '-400': 'weightLoss',
    '-300': 'cutting',
    '0': 'healthyBalance',
    '+300': 'bulking',
    '+500': 'dirtyBulking'
  };
  function createInstance() {
    var object = new Object();
    return object;
  }

  function dispatchEvent(event, payload) {
    var event;
    var parsedEvent = events[event];

    if (parsedEvent) {
      var event = new CustomEvent(parsedEvent, { detail: payload });
      document.dispatchEvent(event);
    }
  }

  function calculateTotalKcal(updatedKcal) {
    var keys = Object.keys(updatedKcal);
    return keys.reduce(
      function(totalKcal, key) {
        var entry = updatedKcal[key];
        for (var nutrionalValue in entry) {
          if (entry.hasOwnProperty(nutrionalValue)) {
            totalKcal[nutrionalValue] += entry[nutrionalValue];
          }
        }

        return totalKcal;
      },
      {
        protein: 0,
        fat: 0,
        carbs: 0
      }
    );
  }

  function setMacrosOnInit() {
    for (var key in selectedRecipes) {
      if (selectedRecipes[key]) {
        var recipeId = selectedRecipes[key];
        var recipe = recipes.find(function(r) {
          return r.id === recipeId;
        });

        if (recipe) {
          var protein = recipe.nutrition.find(function(n) {
            return n.name === 'eiwit';
          });
          var fat = recipe.nutrition.find(function(n) {
            return n.name === 'vet';
          });
          var carbs = recipe.nutrition.find(function(n) {
            return n.name === 'koolhydraten';
          });

          if (protein && fat && carbs) {
            kcal[key] = {
              protein: parseInt(protein.value),
              fat: parseInt(fat.value),
              carbs: parseInt(carbs.value)
            };
          }
        }
      }
    }
  }

  function calculateProteinNeeded() {
    return weight * macros[macroMap[goal]].factor.protein;
  }

  function calculateFatNeeded() {
    return weight * macros[macroMap[goal]].factor.fat;
  }

  function calculateCarbsNeeded() {
    var proteinNeeded = calculateProteinNeeded();
    var fatNeeded = calculateFatNeeded();
    totalKcal = parseInt(localStorage.getItem('kcal'));
    return (totalKcal - (proteinNeeded * 4 + fatNeeded * 9)) / 4;
  }

  function calculateTotalKcalNeeded() {
    return (
      calculateProteinNeeded() + calculateFatNeeded() + calculateCarbsNeeded()
    );
  }
  return {
    user: {
      weight: weight,
      goal: goal,
      kcal: kcal,
      calculateFatNeeded: calculateFatNeeded,
      calculateProteinNeeded: calculateProteinNeeded,
      calculateCarbsNeeded: calculateCarbsNeeded,
      calculateTotalKcalNeeded: calculateTotalKcalNeeded
    },
    selectedRecipes: selectedRecipes,
    macros: macros,
    dispatchEvent: dispatchEvent,
    completedRecipes: completedRecipes,
    macroMap: macroMap,
    events: events,
    getInstance: function() {
      if (!instance) {
        instance = createInstance();
        setMacrosOnInit();
      }
      return instance;
    },
    setRecipes: function(recipes) {
      if (recipes) {
        selectedRecipes = selectedRecipes;
      }
    },
    setKcal: function(kcalObj, type) {
      if (kcal[type]) {
        kcal[type] = {
          protein: parseInt(kcalObj.protein),
          fat: parseInt(kcalObj.fat),
          carbs: parseInt(kcalObj.carbs)
        };

        var totalKcal = calculateTotalKcal(kcal);
        dispatchEvent(events.updateKcal, totalKcal);
      }
    },
    completeRecipe: function(type) {
      if (completedRecipes.indexOf(type) === -1) {
        completedRecipes.push(type);
      }
    },
    calculateTotalKcal: calculateTotalKcal,
    setWeight: function(newWeight) {
      weight = newWeight;
      dispatchEvent('updateMacros', null);
    },
    setGoal: function(newGoal) {
      goal = newGoal;
      dispatchEvent('updateMacros', null);
    }
  };
})();

function init() {
  GoatchefSingleton.getInstance();
}

init();

// run();
