var GoatchefSingleton = (function() {
  let instance;
  const recipes = JSON.parse(gcMacros.recipes);
  const weight = JSON.parse(localStorage.getItem('weight')) || 0;
  const goal = JSON.parse(localStorage.getItem('goal')) || '0'; // Healthy Balance
  const { macros } = gcMacros;

  const allowedEvents = {
    updateMacros: 'updateMacros',
    updateNumMeals: 'updateNumMeals',
    updateKcal: 'updateKcal',
    updateTotalKcal: 'updateTotalKcal'
  };
  // possible values
  const kcal = {
    breakfast: { protein: 0, fat: 0, carbs: 0 },
    lunch: { protein: 0, fat: 0, carbs: 0 },
    diner: { protein: 0, fat: 0, carbs: 0 },
    snack1: { protein: 0, fat: 0, carbs: 0 },
    snack2: { protein: 0, fat: 0, carbs: 0 },
    snack3: { protein: 0, fat: 0, carbs: 0 }
  };
  const userKcal = parseInt(localStorage.getItem('kcal')) || 0;
  const macroMap = {
    '-400': 'weightLoss',
    '-300': 'cutting',
    '0': 'healthyBalance',
    '+300': 'bulking',
    '+500': 'dirtyBulking'
  };

  const tabTranslation = {
    breakfast: {
      nl: 'ontbijt'
    },
    lunch: {
      nl: 'lunch'
    },
    diner: {
      nl: 'avondeten'
    },
    snack1: {
      nl: 'snack'
    },
    snack2: {
      nl: '2e Snack'
    },
    snack3: {
      nl: '3e Snack'
    }
  };

  /**
   * Creates the instance
   */
  function createInstance() {
    var object = new Object();
    return object;
  }
  /**
   *
   * @param {string} event
   * @param {string} payload
   */
  function dispatchEvent(event, payload) {
    const parsedEvent = allowedEvents[event];

    if (parsedEvent) {
      const customEvent = new CustomEvent(parsedEvent, { detail: payload });
      document.dispatchEvent(customEvent);
    }
  }
  /**
   *
   * @param {{}} updatedKcal
   */
  function calculateTotalKcal(updatedKcal) {
    const keys = Object.keys(updatedKcal);
    const calorieObj = {
      protein: 0,
      fat: 0,
      carbs: 0
    };

    return keys.reduce((totalKcal, key) => {
      const entry = updatedKcal[key];
      for (let nutrionalValue in entry) {
        if (entry.hasOwnProperty(nutrionalValue)) {
          totalKcal[nutrionalValue] += entry[nutrionalValue];
        }
      }
      return totalKcal;
    }, calorieObj);
  }

  function calculateProteinNeeded() {
    return weight * macros[macroMap[goal]].factor.protein;
  }

  function calculateFatNeeded() {
    return weight * macros[macroMap[goal]].factor.fat;
  }

  function calculateCarbsNeeded() {
    var userKcal = calculateTotalKcal(kcal);
    var proteinNeeded = calculateProteinNeeded();
    var fatNeeded = calculateFatNeeded();
    return (totalKcal - (proteinNeeded * 4 + fatNeeded * 9)) / 4;
  }

  function calculateTotalKcalNeeded() {
    return (
      calculateProteinNeeded() + calculateFatNeeded() + calculateCarbsNeeded()
    );
  }

  function getInstance() {
    if (!instance) {
      instance = createInstance();
    }
    return instance;
  }

  /*
   * @param {{protein: number, fat: number, carbs: number}} kcalObj
   * @param {string} type
   */
  function setKcal(kcalObj, type) {
    kcal[type] = {
      protein: parseInt(kcalObj.protein),
      fat: parseInt(kcalObj.fat),
      carbs: parseInt(kcalObj.carbs)
    };

    const totalKcal = calculateTotalKcal(kcal);
    dispatchEvent(events.updateKcal, totalKcal);
  }

  function getKcal() {
    return JSON.parse(localStorage.getItem('kcal')) || 2500;
  }

  function getNumMeals() {
    return JSON.parse(localStorage.getItem('numMeals')) || 3;
  }

  function getCurrentSelectedRecipes() {
    return JSON.parse(localStorage.getItem('recipes')) || [];
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
    macros,
    dispatchEvent,
    completedRecipes,
    macroMap,
    events,
    getInstance,
    setGoal,
    setKcal,
    getKcal,
    calculateTotalKcal,
    getNumMeals,
    recipes,
    getCurrentSelectedRecipes,
    tabTranslation
  };
})();

function init() {
  // reset localStorage recipes
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

  GoatchefSingleton.getInstance();
}

init();

// run();
