/* This is a prototype */
var createSnackbar = (function() {
  // Any snackbar that is already shown
  var previous = null;

  return function(title, description, actionText, autoHide) {
    if (previous) {
      previous.dismiss();
    }

    var snackbar = document.createElement('div');
    snackbar.className = 'paper-snackbar';
    snackbar.dismiss = function() {
      snackbar.style.opacity = 0;
    };
    var titleTag = document.createElement('p');
    var title = document.createTextNode(title);
    titleTag.className = 'snackbar__title';
    var descriptionDiv = document.createElement('div');
    descriptionDiv.className = 'snackbar__description';
    var descriptionText = document.createTextNode(description);

    titleTag.appendChild(title);
    snackbar.appendChild(titleTag);
    descriptionDiv.appendChild(descriptionText);
    snackbar.appendChild(descriptionDiv);

    if (actionText) {
      var actionTextDiv = document.createElement('div');
      actionTextDiv.className = 'snackbar__action';

      var actionButton = document.createElement('button');
      actionButton.className = 'snackbar__button';
      actionButton.innerHTML = actionText;
      actionButton.addEventListener('click', snackbar.dismiss);
      actionTextDiv.appendChild(actionButton);
      snackbar.appendChild(actionTextDiv);
    }

    if (autoHide) {
      setTimeout(
        function() {
          if (previous === this) {
            previous.dismiss();
          }
        }.bind(snackbar),
        5000
      );
    }

    snackbar.addEventListener(
      'transitionend',
      function(event, elapsed) {
        if (event.propertyName === 'opacity' && this.style.opacity == 0) {
          this.parentElement.removeChild(this);
          if (previous === this) {
            previous = null;
          }
        }
      }.bind(snackbar)
    );

    previous = snackbar;
    document.body.appendChild(snackbar);
    // In order for the animations to trigger, I have to force the original style to be computed, and then change it.
    getComputedStyle(snackbar).bottom;
    // snackbar.style.bottom = '60px';
    snackbar.style.bottom = '50%';
    snackbar.style.opacity = 1;
    snackbar.style.transform = 'translate(-50%, 50%)';
  };
})();

// document.querySelectorAll('.fab').addEventListener('click', function() {
// });
