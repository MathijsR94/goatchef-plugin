<?php
use Goatchef\Plugin;

$images = [];
function gc_show_products($attrs, $content)
{
    $goatchef_plugin = Plugin::get_instance();
    $recipes = json_decode($goatchef_plugin->database->recipe->getRecipes());
    ob_start();?>
<div class="loader">
  <div class="spinner">
    <div class="bounce1"></div>
    <div class="bounce2"></div>
    <div class="bounce3"></div>
  </div>
</div>
<div class="homepage-products hidden">
  <ol class="steps">
    <li class="steps__item steps__item--first steps__item--done steps__item--active" data-progress-step="breakfast"><a
        href="#" class="steps__link">Ontbijt</a></li>
    <li class="steps__item" disabled data-progress-step="lunch"><a href="#" class="steps__link">Lunch</a></li>
    <li class="steps__item" disabled data-progress-step="diner"><a href="#" class="steps__link">Diner</a></li>
    <li class="steps__item hidden" disabled data-progress-step="snack1"><a href="#" class="steps__link">Snack</a></li>
    <li class="steps__item hidden" disabled data-progress-step="snack2"><a href="#" class="steps__link">2e snack</a></li>
    <li class="steps__item hidden" disabled data-progress-step="snack3"><a href="#" class="steps__link">3e snack</a></li>
    <li class="steps__item steps__item--last" disabled data-progress-step="end"><a class="steps__link">Aan de slag</a></li>
  </ol>

  <input type="text" data-id="search-products" placeholder="Zoek naar recepten" />
  <div class="tab-content">
    <div class="loader hidden">
      <div class="spinner">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
      </div>
    </div>
    <div id="breakfast">
      <div class="homepage-products__list">
        <?php
foreach ($recipes as $recipe) {
        echo renderCard($recipe, 'breakfast');
    }
    ?>
      </div>
      <!-- <div class="homepage-products__empty hidden">Geen resultaten gevonden</div> -->
    </div>
    <div id="lunch" class="hidden">
      <div class="homepage-products__list">
        <?php
foreach ($recipes as $recipe) {
        echo renderCard($recipe, 'lunch');
    }
    ?>
      </div>
      <!-- <div class="homepage-products__empty hidden">Geen resultaten gevonden</div> -->
    </div>
    <div id="diner" class="hidden">
      <div class="homepage-products__list">
        <?php
foreach ($recipes as $recipe) {
        echo renderCard($recipe, 'diner');
    }
    ?>
      </div>
      <!-- <div class="homepage-products__empty hidden">Geen resultaten gevonden</div> -->
    </div>
    <div id="snack1" class="hidden">
      <div class="homepage-products__list">
        <?php
foreach ($recipes as $recipe) {
        echo renderCard($recipe, 'snack1');
    }
    ?>
      </div>
      <!-- <div class="homepage-products__empty hidden">Geen resultaten gevonden</div> -->
    </div>
    <div id="snack2" class="hidden">
      <div class="homepage-products__list">
        <?php
foreach ($recipes as $recipe) {
        echo renderCard($recipe, 'snack2');
    }
    ?>
      </div>
      <!-- <div class="homepage-products__empty hidden">Geen resultaten gevonden</div> -->
    </div>
    <div id="snack3" class="hidden">
      <div class="homepage-products__list">
        <?php
foreach ($recipes as $recipe) {
        echo renderCard($recipe, 'snack3');
    }
    ?>
      </div>
      <!-- <div class="homepage-products__empty hidden">Geen resultaten gevonden</div> -->
    </div>
    <div id="end" class="hidden">
      <div class="notify">
        <div class="notify__message"><span class="bold">Klaar!&nbsp;</span><span>Maak nu eenvoudig een
            boodschappenlijst aan of doe direct jouw boodschappen online.</div>
        <div class="notify__button-container">
          <button class="notify__button" data-shoppinglist>boodschappenlijst maken</button>
          <button class="notify__button" data-shop>online boodschappen doen</button>
        </div>
      </div>
      <div class="homepage-products__list">
        <div class="progress-nutritions__item hidden" data-type="breakfast">
          <!-- <h4>Ontbijt</h4> -->
          <?php echo renderPlaceholderCard(); ?>
        </div>
        <div class="progress-nutritions__item hidden" data-type="lunch">
          <!-- <h4>Lunch</h4> -->
          <?php echo renderPlaceholderCard(); ?>
        </div>
        <div class="progress-nutritions__item hidden" data-type="diner">
          <!-- <h4>Diner</h4> -->
          <?php echo renderPlaceholderCard(); ?>
        </div>
        <div class="progress-nutritions__item hidden" data-type="snack1">
          <!-- <h4>Snack</h4> -->
          <?php echo renderPlaceholderCard(); ?>
        </div>
        <div class="progress-nutritions__item hidden" data-type="snack2">
          <!-- <h4>2e Snack</h4> -->
          <?php echo renderPlaceholderCard(); ?>
        </div>
        <div class="progress-nutritions__item hidden" data-type="snack3">
          <!-- <h4>3e Snack</h4> -->
          <?php echo renderPlaceholderCard(); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="progress-nutritions hidden">
  <div class="progress-nutritions__container">
    <div class="progress-nutritions__label"><span>Voortgang</span></div>
    <div class="progress-nutritions__list">
      <div class="progress-nutritions__item" data-type="macro">
        <h4>Macro's</h4>
        <ul class="macro-list">
          <li class="macro">
            <span>Calorieen</span>
            <span class="macro__current"></span>
            <progress class="macro-4" max="100"></progress>
            <span class="macro__total"></span>
          </li>
          <li class="macro">
            <span>Koolhydraten</span>
            <span class="macro__current"></span>
            <progress class="macro-1" max="100"></progress>
            <span class="macro__total"></span>
          </li>
          <li class="macro">
            <span>Eiwitten</span>
            <span class="macro__current"></span>
            <progress class="macro-2" max="100"></progress>
            <span class="macro__total"></span>
          </li>
          <li class="macro">
            <span>Vetten</span>
            <span class="macro__current"></span>
            <progress class="macro-3" max="100"></progress>
            <span class="macro__total"></span>
          </li>

        </ul>
      </div>
    </div>
  </div>
</div>
<?php return ob_get_clean();
}
add_shortcode('homepage-products', 'gc_show_products');

function renderCard($recipe, $type)
{
    if (!isset($images[$recipe->id])) {
        $images[$recipe->id] = $recipe->image;
    }
    ob_start();?>

<div class="card" data-recipe="<?php echo $type . '-' . $recipe->id ?>">
  <div class="card__image">
    <div class="card__vendor">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 55 57">
        <g>
          <title>Layer 1</title>
          <g id="Enveloppe">
            <path id="svg_1" d="m51.916397,24.327343c0,0 -7.400002,-13.299999 -10.900002,-19.799999c-1.099998,-2 -2.299999,-3 -3.5,-3.4v0c-1.200001,-0.3 -2.700001,-0.1 -4.799995,1c-6.49999,3.6 -19.799992,10.9 -19.799992,10.9s-4.700003,2 -5.700003,5.5c-1,3.4 -6.199999,21.200001 -6.199999,21.200001s-0.6,3.899998 2.699999,4.899998c3.3,1 19.500004,5.600002 19.500004,5.600002s16.199987,4.700001 19.499987,5.599998c3.299999,1 4.899998,-2.700001 4.899998,-2.700001s5.200001,-17.799999 6.200001,-21.199999c1,-3.4 -1.899998,-7.6 -1.899998,-7.6z"
              fill="#179EDA" clip-rule="evenodd" fill-rule="evenodd" />
          </g>
          <g id="AH">
            <path id="svg_2" d="m30,24.249609c1.200001,-1.4 2.599998,-3.6 4.299999,-4.799999c1.700001,-1 4.100002,-1.4 5.900002,-0.5c2.099998,1 3.5,3 3.700001,5.4v18.099998h-4.5l0,-17.299999c0,-0.799999 -0.600002,-1.4 -1.300003,-1.699999c-0.899998,-0.200001 -1.699997,0.199999 -2.299999,0.799999l-5.799999,7v11.1h-4.5v-5c-1.1,1.200001 -2.200001,2.599998 -3.299999,3.599998c-1.400002,1.200001 -3.300001,1.700001 -5.400002,1.400002c-0.5,-0.200001 -1.099999,-0.200001 -1.599999,-0.5c-5.5,-2.5 -3.8,-10 -4,-15.700001c0.2,-2.599998 0.8,-5.5 3.1,-6.699999v-0.1c0.5,-0.1 0.9,-0.5 1.4,-0.5c0.400001,-0.1 0.8,-0.200001 1.3,-0.200001c0.4,-0.199999 1,-0.099998 1.5,-0.099998v0c2.6,0.4 4.5,2.4 6.1,4.4c0.299999,0.5 0.6,0.9 1,1.4l0.1,-0.1v-3.6l4.599998,-6.099999v9.7l-0.299999,0zm-12,-1.1c-0.799999,-0.099998 -1.6,0.300001 -1.9,1c-0.900001,2.900002 -0.5,6.5 -0.5,9.700001c0.2,1.400002 0.099999,2.900002 1.199999,3.700001c0.1,0.099998 0.300001,0 0.300001,0.200001c0.9,0.099998 1.699999,-0.100002 2.299999,-0.600002c2.200001,-1.700001 3.6,-4 5.200001,-6.099998c-1,-1.800001 -2.1,-3.400002 -3.300001,-5.1c-0.9,-1 -1.799999,-2.300001 -3.299999,-2.800001l0,0z"
              fill="#FFFFFF" clip-rule="evenodd" fill-rule="evenodd" />
          </g>
        </g>
      </svg>
    </div>
    <img src="<?php echo $images[$recipe->id] ?>" />
    <button class="fab ripple" data-id="<?php echo $recipe->id; ?>">&#43;</button>
  </div>
  <div class="card__content">
    <span class="card__title">
      <?php echo $recipe->recipe_title ?></span>
    <div class="card__meta">
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60">
          <path d="M30 0a30 30 0 1 0 0 60 30 30 0 0 0 0-60zm0 58a28 28 0 1 1 0-56 28 28 0 0 1 0 56z" />
          <path d="M31 26V16a1 1 0 1 0-2 0V26a4 4 0 0 0-2.9 2.9H19a1 1 0 1 0 0 2h7.1A4 4 0 1 0 31 26zM30 32a2 2 0 1 1 0-4 2 2 0 0 1 0 4zM30 9.9c.6 0 1-.5 1-1v-1a1 1 0 1 0-2 0v1c0 .5.4 1 1 1zM30 49.9a1 1 0 0 0-1 1v1a1 1 0 1 0 2 0v-1c0-.6-.4-1-1-1zM52 28.9h-1a1 1 0 1 0 0 2h1a1 1 0 1 0 0-2zM9 28.9H8a1 1 0 1 0 0 2h1a1 1 0 1 0 0-2zM44.8 13.6l-.7.7a1 1 0 1 0 1.5 1.4l.7-.7a1 1 0 1 0-1.5-1.4zM14.4 44l-.7.7a1 1 0 1 0 1.5 1.4l.7-.7a1 1 0 1 0-1.5-1.4zM45.6 44a1 1 0 1 0-1.5 1.4l.7.7a1 1 0 0 0 1.5 0c.4-.3.4-1 0-1.4l-.7-.7zM15.2 13.6a1 1 0 1 0-1.5 1.4l.7.7a1 1 0 0 0 1.5 0c.3-.4.3-1 0-1.4l-.7-.7z" /></svg>
        <span>
          <?php echo $recipe->cook_time ?></span>
        <p>minuten</p>
      </div>
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
          <path d="M71 443.8h-.1A10 10 0 1 0 57 458.1h.1a10 10 0 0 0 14.2-.3 10 10 0 0 0-.3-14.1zM71 341.8h-.1A10 10 0 1 0 57 356.1h.1a10 10 0 0 0 14.2-.3 10 10 0 0 0-.3-14.1zM454.9 443.8l-.1-.1a10 10 0 0 0-13.8 14.5h.1a10 10 0 0 0 14.2-.3 10 10 0 0 0-.4-14.1zM454.9 341.8l-.1-.1a10 10 0 0 0-13.8 14.5h.1a10 10 0 0 0 14.2-.3 10 10 0 0 0-.4-14.1zM142.7 390h-.2a10 10 0 0 0 0 20h.2a10 10 0 0 0 0-20z" />
          <path d="M512 297.1a79.7 79.7 0 0 0-70-79 79.7 79.7 0 0 0-36.8-60c4.3-2.5 8.3-5.5 12-9.2C431 135 437 115 433.5 93.7a10 10 0 0 0-8.2-8.2 62.4 62.4 0 0 0-55.2 16.4 60.8 60.8 0 0 0-17.3 44.6 79.8 79.8 0 0 0-69.3 71.6 79.5 79.5 0 0 0-50.4 26.8 56 56 0 0 0-3.2-13.2 56.6 56.6 0 0 0-20.6-109.2h-.6c3-8.8 5.6-20.2 5-33.2a10 10 0 0 0-7-9c-12.6-4.2-27.2-.1-37.6 4.3a85.2 85.2 0 0 0-25-44.4 10 10 0 0 0-11.4-1.6 84.3 84.3 0 0 0-41 49.2A49.7 49.7 0 0 0 70 81a10 10 0 0 0-10 6.6 61 61 0 0 0-1.7 35h-1.7a56.6 56.6 0 0 0-24.8 107.2 56.2 56.2 0 0 0 9.1 58.2H10a10 10 0 0 0-10 10v204a10 10 0 0 0 10 10h492a10 10 0 0 0 10-10V298v-.5-.4zm-20.7-9.1H373.4a59.7 59.7 0 0 1 117.9 0zm-107-172a41 41 0 0 1 30.2-11.5 41 41 0 0 1-11.5 30.3 41.1 41.1 0 0 1-30.3 11.5 41 41 0 0 1 11.5-30.3zm-21.6 49.8a59.7 59.7 0 0 1 59.2 52.4 79.8 79.8 0 0 0-59.2 40.3 79.8 79.8 0 0 0-59.1-40.3 59.7 59.7 0 0 1 59.1-52.4zm-69.6 71.7c29.8 0 54.5 22 59 50.5H234a59.7 59.7 0 0 1 59-50.5zm-83.8-95a36.5 36.5 0 0 1 5 72.7h-.1a36.7 36.7 0 0 1-28.4-8.3 56.2 56.2 0 0 0-5.3-50.3c6.9-8.8 17.5-14 28.8-14zm-76.4 81a36.6 36.6 0 1 1 1.2 0H133zm1 64.5H127c1.2-1.5 2.4-3 3.4-4.5 1.1 1.6 2.3 3 3.5 4.5zm-13.5-36.5a36.6 36.6 0 1 1-69.2-16.2 57 57 0 0 0 39.2-11.1 56.6 56.6 0 0 0 28.7 17.6c.9 3.1 1.3 6.4 1.3 9.7zm21-8.7c13.5-2 25.4-8.8 34-18.6a56.4 56.4 0 0 0 34 11.3h.4a36.6 36.6 0 1 1-68.3 7.3zm-64.7-140a38.4 38.4 0 0 1 13.6 9.1 10 10 0 0 0 17.5-5.6c0-.3 3-29.2 27.8-46.1a67.5 67.5 0 0 1 15.3 39.6 10 10 0 0 0 15.3 8.6 70 70 0 0 1 27.3-9.8 70.8 70.8 0 0 1-9.5 30 56.5 56.5 0 0 0-17.2 13.2 56.2 56.2 0 0 0-67.8 0 57 57 0 0 0-17.4-13.4c-2.1-3.9-6.4-13.7-4.9-25.7zM20 179a36.6 36.6 0 0 1 65.3-22.4 56.1 56.1 0 0 0-5.2 50.3 36 36 0 0 1-31.7 7.7A36.6 36.6 0 0 1 20 179zm472 211H182.3a10 10 0 0 0 0 20H492v82H20v-82h82.7a10 10 0 0 0 0-20H20v-82h472v82z" />
          <path d="M360.4 62.1a55.9 55.9 0 0 0-79 0 10 10 0 0 0 14.2 14.2c14-14 36.7-14 50.6 0a10 10 0 0 0 14.2 0c3.9-4 3.9-10.3 0-14.2z" />
          <path d="M392.7 29.7a101.7 101.7 0 0 0-143.6 0A10 10 0 1 0 263.2 44a81.7 81.7 0 0 1 115.4 0c2 2 4.5 3 7 3s5.2-1 7.1-3a10 10 0 0 0 0-14.2zM328 94.7a10 10 0 0 0-14.2 0 10 10 0 0 0 0 14.2 10 10 0 0 0 14.2 0 10 10 0 0 0 0-14.2z" />
        </svg>
        <span>
          <?php echo count($recipe->ingredients) ?></span>
        <p>ingredienten</p>
      </div>

      <div>
        <svg version="1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 483 483">
          <path d="M240 260h3c29 0 53-11 70-30 39-44 32-118 32-125-3-54-28-79-49-91-15-9-33-14-53-14h-1-1c-11 0-33 2-54 14s-47 37-49 91c-1 7-7 81 31 125 18 19 41 30 71 30zm-75-153c3-72 54-80 76-80h1c27 1 73 12 76 80 0 1 7 69-25 105a66 66 0 0 1-51 21h-1c-22 0-39-7-52-21-31-36-24-104-24-105z" />
          <path d="M447 384v-1-2c-1-20-2-66-46-81h-1c-45-12-82-38-83-38a13 13 0 1 0-15 22c2 1 41 29 91 42 23 8 26 33 27 56v2l-2 31c-16 9-80 41-176 41-97 0-161-32-177-41-2-8-2-22-2-31v-3c1-22 3-47 27-56 49-12 89-40 91-41a13 13 0 1 0-15-22c-1 0-38 26-83 37l-1 1c-44 15-45 61-46 81v2c0 6 0 32 5 46l5 6c3 2 75 48 196 48s192-46 195-48l5-6c5-13 5-40 5-45z" /></svg>
        <span>
          <?php echo $recipe->serving_size ?></span>
        <p>
          <?php echo $recipe->serving_unit ?>
        </p>
      </div>
    </div>
  </div>
  <div class="card__action">
    <button class="card__button">Bekijk recept</button>
  </div>
  <div class="card__detail hidden">
    <p class="card__title">
      <?php echo $recipe->recipe_title ?>
    </p>
    <ul>
      <?php foreach ($recipe->nutrition as $nutrition): ?>
      <li>
        <?php echo $nutrition->name . ' ' . $nutrition->value . ' ' . $nutrition->unit; ?>
      </li>
      <?php endforeach;?>
    </ul>
    <div class="recipe__summary">
      <p>
        <?php
echo '<p>' . $recipe->preparation_summary
    ?>
      </p>
    </div>
    <button class="card__button">Sluiten</button>
  </div>
</div>

<?php return ob_get_clean();
}
function renderCard1($recipe, $type)
{
    if (!isset($images[$recipe->id])) {
        $images[$recipe->id] = $recipe->image;
    }
    ob_start();?>

<div class="card" data-recipe="<?php echo $type . '-' . $recipe->id ?>">
  <div class="card__image">
    <div class="card__vendor">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 55 57">
        <g>
          <title>Layer 1</title>
          <g id="Enveloppe">
            <path id="svg_1" d="m51.916397,24.327343c0,0 -7.400002,-13.299999 -10.900002,-19.799999c-1.099998,-2 -2.299999,-3 -3.5,-3.4v0c-1.200001,-0.3 -2.700001,-0.1 -4.799995,1c-6.49999,3.6 -19.799992,10.9 -19.799992,10.9s-4.700003,2 -5.700003,5.5c-1,3.4 -6.199999,21.200001 -6.199999,21.200001s-0.6,3.899998 2.699999,4.899998c3.3,1 19.500004,5.600002 19.500004,5.600002s16.199987,4.700001 19.499987,5.599998c3.299999,1 4.899998,-2.700001 4.899998,-2.700001s5.200001,-17.799999 6.200001,-21.199999c1,-3.4 -1.899998,-7.6 -1.899998,-7.6z"
              fill="#179EDA" clip-rule="evenodd" fill-rule="evenodd" />
          </g>
          <g id="AH">
            <path id="svg_2" d="m30,24.249609c1.200001,-1.4 2.599998,-3.6 4.299999,-4.799999c1.700001,-1 4.100002,-1.4 5.900002,-0.5c2.099998,1 3.5,3 3.700001,5.4v18.099998h-4.5l0,-17.299999c0,-0.799999 -0.600002,-1.4 -1.300003,-1.699999c-0.899998,-0.200001 -1.699997,0.199999 -2.299999,0.799999l-5.799999,7v11.1h-4.5v-5c-1.1,1.200001 -2.200001,2.599998 -3.299999,3.599998c-1.400002,1.200001 -3.300001,1.700001 -5.400002,1.400002c-0.5,-0.200001 -1.099999,-0.200001 -1.599999,-0.5c-5.5,-2.5 -3.8,-10 -4,-15.700001c0.2,-2.599998 0.8,-5.5 3.1,-6.699999v-0.1c0.5,-0.1 0.9,-0.5 1.4,-0.5c0.400001,-0.1 0.8,-0.200001 1.3,-0.200001c0.4,-0.199999 1,-0.099998 1.5,-0.099998v0c2.6,0.4 4.5,2.4 6.1,4.4c0.299999,0.5 0.6,0.9 1,1.4l0.1,-0.1v-3.6l4.599998,-6.099999v9.7l-0.299999,0zm-12,-1.1c-0.799999,-0.099998 -1.6,0.300001 -1.9,1c-0.900001,2.900002 -0.5,6.5 -0.5,9.700001c0.2,1.400002 0.099999,2.900002 1.199999,3.700001c0.1,0.099998 0.300001,0 0.300001,0.200001c0.9,0.099998 1.699999,-0.100002 2.299999,-0.600002c2.200001,-1.700001 3.6,-4 5.200001,-6.099998c-1,-1.800001 -2.1,-3.400002 -3.300001,-5.1c-0.9,-1 -1.799999,-2.300001 -3.299999,-2.800001l0,0z"
              fill="#FFFFFF" clip-rule="evenodd" fill-rule="evenodd" />
          </g>
        </g>
      </svg>
    </div>
    <!-- <img src="<?php echo $images[$recipe->id] ?>"/> -->
    <img src="<?php $images[$recipe->id]?>" />
    <div class="fab" data-id="<?php echo $recipe->id; ?>">&#43;</div>
  </div>
  <div class="card__content">
    <span class="card__title">
      <?php echo $recipe->recipe_title ?></span>
    <div class="card__meta">
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60">
          <path d="M30 0a30 30 0 1 0 0 60 30 30 0 0 0 0-60zm0 58a28 28 0 1 1 0-56 28 28 0 0 1 0 56z" />
          <path d="M31 26V16a1 1 0 1 0-2 0V26a4 4 0 0 0-2.9 2.9H19a1 1 0 1 0 0 2h7.1A4 4 0 1 0 31 26zM30 32a2 2 0 1 1 0-4 2 2 0 0 1 0 4zM30 9.9c.6 0 1-.5 1-1v-1a1 1 0 1 0-2 0v1c0 .5.4 1 1 1zM30 49.9a1 1 0 0 0-1 1v1a1 1 0 1 0 2 0v-1c0-.6-.4-1-1-1zM52 28.9h-1a1 1 0 1 0 0 2h1a1 1 0 1 0 0-2zM9 28.9H8a1 1 0 1 0 0 2h1a1 1 0 1 0 0-2zM44.8 13.6l-.7.7a1 1 0 1 0 1.5 1.4l.7-.7a1 1 0 1 0-1.5-1.4zM14.4 44l-.7.7a1 1 0 1 0 1.5 1.4l.7-.7a1 1 0 1 0-1.5-1.4zM45.6 44a1 1 0 1 0-1.5 1.4l.7.7a1 1 0 0 0 1.5 0c.4-.3.4-1 0-1.4l-.7-.7zM15.2 13.6a1 1 0 1 0-1.5 1.4l.7.7a1 1 0 0 0 1.5 0c.3-.4.3-1 0-1.4l-.7-.7z" /></svg>
        <span>
          <?php echo $recipe->cook_time ?></span>
        <p>minuten</p>
      </div>
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
          <path d="M71 443.8h-.1A10 10 0 1 0 57 458.1h.1a10 10 0 0 0 14.2-.3 10 10 0 0 0-.3-14.1zM71 341.8h-.1A10 10 0 1 0 57 356.1h.1a10 10 0 0 0 14.2-.3 10 10 0 0 0-.3-14.1zM454.9 443.8l-.1-.1a10 10 0 0 0-13.8 14.5h.1a10 10 0 0 0 14.2-.3 10 10 0 0 0-.4-14.1zM454.9 341.8l-.1-.1a10 10 0 0 0-13.8 14.5h.1a10 10 0 0 0 14.2-.3 10 10 0 0 0-.4-14.1zM142.7 390h-.2a10 10 0 0 0 0 20h.2a10 10 0 0 0 0-20z" />
          <path d="M512 297.1a79.7 79.7 0 0 0-70-79 79.7 79.7 0 0 0-36.8-60c4.3-2.5 8.3-5.5 12-9.2C431 135 437 115 433.5 93.7a10 10 0 0 0-8.2-8.2 62.4 62.4 0 0 0-55.2 16.4 60.8 60.8 0 0 0-17.3 44.6 79.8 79.8 0 0 0-69.3 71.6 79.5 79.5 0 0 0-50.4 26.8 56 56 0 0 0-3.2-13.2 56.6 56.6 0 0 0-20.6-109.2h-.6c3-8.8 5.6-20.2 5-33.2a10 10 0 0 0-7-9c-12.6-4.2-27.2-.1-37.6 4.3a85.2 85.2 0 0 0-25-44.4 10 10 0 0 0-11.4-1.6 84.3 84.3 0 0 0-41 49.2A49.7 49.7 0 0 0 70 81a10 10 0 0 0-10 6.6 61 61 0 0 0-1.7 35h-1.7a56.6 56.6 0 0 0-24.8 107.2 56.2 56.2 0 0 0 9.1 58.2H10a10 10 0 0 0-10 10v204a10 10 0 0 0 10 10h492a10 10 0 0 0 10-10V298v-.5-.4zm-20.7-9.1H373.4a59.7 59.7 0 0 1 117.9 0zm-107-172a41 41 0 0 1 30.2-11.5 41 41 0 0 1-11.5 30.3 41.1 41.1 0 0 1-30.3 11.5 41 41 0 0 1 11.5-30.3zm-21.6 49.8a59.7 59.7 0 0 1 59.2 52.4 79.8 79.8 0 0 0-59.2 40.3 79.8 79.8 0 0 0-59.1-40.3 59.7 59.7 0 0 1 59.1-52.4zm-69.6 71.7c29.8 0 54.5 22 59 50.5H234a59.7 59.7 0 0 1 59-50.5zm-83.8-95a36.5 36.5 0 0 1 5 72.7h-.1a36.7 36.7 0 0 1-28.4-8.3 56.2 56.2 0 0 0-5.3-50.3c6.9-8.8 17.5-14 28.8-14zm-76.4 81a36.6 36.6 0 1 1 1.2 0H133zm1 64.5H127c1.2-1.5 2.4-3 3.4-4.5 1.1 1.6 2.3 3 3.5 4.5zm-13.5-36.5a36.6 36.6 0 1 1-69.2-16.2 57 57 0 0 0 39.2-11.1 56.6 56.6 0 0 0 28.7 17.6c.9 3.1 1.3 6.4 1.3 9.7zm21-8.7c13.5-2 25.4-8.8 34-18.6a56.4 56.4 0 0 0 34 11.3h.4a36.6 36.6 0 1 1-68.3 7.3zm-64.7-140a38.4 38.4 0 0 1 13.6 9.1 10 10 0 0 0 17.5-5.6c0-.3 3-29.2 27.8-46.1a67.5 67.5 0 0 1 15.3 39.6 10 10 0 0 0 15.3 8.6 70 70 0 0 1 27.3-9.8 70.8 70.8 0 0 1-9.5 30 56.5 56.5 0 0 0-17.2 13.2 56.2 56.2 0 0 0-67.8 0 57 57 0 0 0-17.4-13.4c-2.1-3.9-6.4-13.7-4.9-25.7zM20 179a36.6 36.6 0 0 1 65.3-22.4 56.1 56.1 0 0 0-5.2 50.3 36 36 0 0 1-31.7 7.7A36.6 36.6 0 0 1 20 179zm472 211H182.3a10 10 0 0 0 0 20H492v82H20v-82h82.7a10 10 0 0 0 0-20H20v-82h472v82z" />
          <path d="M360.4 62.1a55.9 55.9 0 0 0-79 0 10 10 0 0 0 14.2 14.2c14-14 36.7-14 50.6 0a10 10 0 0 0 14.2 0c3.9-4 3.9-10.3 0-14.2z" />
          <path d="M392.7 29.7a101.7 101.7 0 0 0-143.6 0A10 10 0 1 0 263.2 44a81.7 81.7 0 0 1 115.4 0c2 2 4.5 3 7 3s5.2-1 7.1-3a10 10 0 0 0 0-14.2zM328 94.7a10 10 0 0 0-14.2 0 10 10 0 0 0 0 14.2 10 10 0 0 0 14.2 0 10 10 0 0 0 0-14.2z" />
        </svg>
        <span>
          <?php echo count($recipe->ingredients) ?></span>
        <p>ingredienten</p>
      </div>

      <div>
        <svg version="1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 483 483">
          <path d="M240 260h3c29 0 53-11 70-30 39-44 32-118 32-125-3-54-28-79-49-91-15-9-33-14-53-14h-1-1c-11 0-33 2-54 14s-47 37-49 91c-1 7-7 81 31 125 18 19 41 30 71 30zm-75-153c3-72 54-80 76-80h1c27 1 73 12 76 80 0 1 7 69-25 105a66 66 0 0 1-51 21h-1c-22 0-39-7-52-21-31-36-24-104-24-105z" />
          <path d="M447 384v-1-2c-1-20-2-66-46-81h-1c-45-12-82-38-83-38a13 13 0 1 0-15 22c2 1 41 29 91 42 23 8 26 33 27 56v2l-2 31c-16 9-80 41-176 41-97 0-161-32-177-41-2-8-2-22-2-31v-3c1-22 3-47 27-56 49-12 89-40 91-41a13 13 0 1 0-15-22c-1 0-38 26-83 37l-1 1c-44 15-45 61-46 81v2c0 6 0 32 5 46l5 6c3 2 75 48 196 48s192-46 195-48l5-6c5-13 5-40 5-45z" /></svg>
        <span>
          <?php echo $recipe->serving_size ?></span>
        <p>
          <?php echo $recipe->serving_unit ?>
        </p>
      </div>
    </div>
    <div class="card__description">
      <p>
        <?php echo $recipe->description ?>
      </p>
    </div>
    <div class="card__detail hidden">
      <p class="card__title">
        <?php echo $recipe->recipe_title ?>
      </p>
      <ul>
        <?php foreach ($recipe->nutrition as $nutrition): ?>
        <li>
          <?php echo $nutrition->name . ' ' . $nutrition->value . ' ' . $nutrition->unit; ?>
        </li>
        <?php endforeach;?>
      </ul>

      <button class="card__button">Sluiten</button>
    </div>
    <button class="card__button">Bekijk recept</button>
  </div>
</div>

<?php return ob_get_clean();
}

function renderPlaceholderCard()
{
    ob_start();?>

<div class="card">
  <div class="card__image">
    <div class="card__vendor">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 55 57">
        <g>
          <title>Layer 1</title>
          <g id="Enveloppe">
            <path id="svg_1" d="m51.916397,24.327343c0,0 -7.400002,-13.299999 -10.900002,-19.799999c-1.099998,-2 -2.299999,-3 -3.5,-3.4v0c-1.200001,-0.3 -2.700001,-0.1 -4.799995,1c-6.49999,3.6 -19.799992,10.9 -19.799992,10.9s-4.700003,2 -5.700003,5.5c-1,3.4 -6.199999,21.200001 -6.199999,21.200001s-0.6,3.899998 2.699999,4.899998c3.3,1 19.500004,5.600002 19.500004,5.600002s16.199987,4.700001 19.499987,5.599998c3.299999,1 4.899998,-2.700001 4.899998,-2.700001s5.200001,-17.799999 6.200001,-21.199999c1,-3.4 -1.899998,-7.6 -1.899998,-7.6z"
              fill="#179EDA" clip-rule="evenodd" fill-rule="evenodd" />
          </g>
          <g id="AH">
            <path id="svg_2" d="m30,24.249609c1.200001,-1.4 2.599998,-3.6 4.299999,-4.799999c1.700001,-1 4.100002,-1.4 5.900002,-0.5c2.099998,1 3.5,3 3.700001,5.4v18.099998h-4.5l0,-17.299999c0,-0.799999 -0.600002,-1.4 -1.300003,-1.699999c-0.899998,-0.200001 -1.699997,0.199999 -2.299999,0.799999l-5.799999,7v11.1h-4.5v-5c-1.1,1.200001 -2.200001,2.599998 -3.299999,3.599998c-1.400002,1.200001 -3.300001,1.700001 -5.400002,1.400002c-0.5,-0.200001 -1.099999,-0.200001 -1.599999,-0.5c-5.5,-2.5 -3.8,-10 -4,-15.700001c0.2,-2.599998 0.8,-5.5 3.1,-6.699999v-0.1c0.5,-0.1 0.9,-0.5 1.4,-0.5c0.400001,-0.1 0.8,-0.200001 1.3,-0.200001c0.4,-0.199999 1,-0.099998 1.5,-0.099998v0c2.6,0.4 4.5,2.4 6.1,4.4c0.299999,0.5 0.6,0.9 1,1.4l0.1,-0.1v-3.6l4.599998,-6.099999v9.7l-0.299999,0zm-12,-1.1c-0.799999,-0.099998 -1.6,0.300001 -1.9,1c-0.900001,2.900002 -0.5,6.5 -0.5,9.700001c0.2,1.400002 0.099999,2.900002 1.199999,3.700001c0.1,0.099998 0.300001,0 0.300001,0.200001c0.9,0.099998 1.699999,-0.100002 2.299999,-0.600002c2.200001,-1.700001 3.6,-4 5.200001,-6.099998c-1,-1.800001 -2.1,-3.400002 -3.300001,-5.1c-0.9,-1 -1.799999,-2.300001 -3.299999,-2.800001l0,0z"
              fill="#FFFFFF" clip-rule="evenodd" fill-rule="evenodd" />
          </g>
        </g>
      </svg>
    </div>
    <img src="" placeholder-id="recipe_image" />
    <div class="card__change-recipe" placeholder-id="edit-recipe"><span>Wijzigen</span></div>
  </div>
  <div class="card__content">
    <span class="card__title" placeholder-id="recipe_title"></span>
    <div class="card__meta">
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60">
          <path d="M30 0a30 30 0 1 0 0 60 30 30 0 0 0 0-60zm0 58a28 28 0 1 1 0-56 28 28 0 0 1 0 56z" />
          <path d="M31 26V16a1 1 0 1 0-2 0V26a4 4 0 0 0-2.9 2.9H19a1 1 0 1 0 0 2h7.1A4 4 0 1 0 31 26zM30 32a2 2 0 1 1 0-4 2 2 0 0 1 0 4zM30 9.9c.6 0 1-.5 1-1v-1a1 1 0 1 0-2 0v1c0 .5.4 1 1 1zM30 49.9a1 1 0 0 0-1 1v1a1 1 0 1 0 2 0v-1c0-.6-.4-1-1-1zM52 28.9h-1a1 1 0 1 0 0 2h1a1 1 0 1 0 0-2zM9 28.9H8a1 1 0 1 0 0 2h1a1 1 0 1 0 0-2zM44.8 13.6l-.7.7a1 1 0 1 0 1.5 1.4l.7-.7a1 1 0 1 0-1.5-1.4zM14.4 44l-.7.7a1 1 0 1 0 1.5 1.4l.7-.7a1 1 0 1 0-1.5-1.4zM45.6 44a1 1 0 1 0-1.5 1.4l.7.7a1 1 0 0 0 1.5 0c.4-.3.4-1 0-1.4l-.7-.7zM15.2 13.6a1 1 0 1 0-1.5 1.4l.7.7a1 1 0 0 0 1.5 0c.3-.4.3-1 0-1.4l-.7-.7z" /></svg>
        <span placeholder-id="cook-time"></span>
        <p>minuten</p>
      </div>
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
          <path d="M71 443.8h-.1A10 10 0 1 0 57 458.1h.1a10 10 0 0 0 14.2-.3 10 10 0 0 0-.3-14.1zM71 341.8h-.1A10 10 0 1 0 57 356.1h.1a10 10 0 0 0 14.2-.3 10 10 0 0 0-.3-14.1zM454.9 443.8l-.1-.1a10 10 0 0 0-13.8 14.5h.1a10 10 0 0 0 14.2-.3 10 10 0 0 0-.4-14.1zM454.9 341.8l-.1-.1a10 10 0 0 0-13.8 14.5h.1a10 10 0 0 0 14.2-.3 10 10 0 0 0-.4-14.1zM142.7 390h-.2a10 10 0 0 0 0 20h.2a10 10 0 0 0 0-20z" />
          <path d="M512 297.1a79.7 79.7 0 0 0-70-79 79.7 79.7 0 0 0-36.8-60c4.3-2.5 8.3-5.5 12-9.2C431 135 437 115 433.5 93.7a10 10 0 0 0-8.2-8.2 62.4 62.4 0 0 0-55.2 16.4 60.8 60.8 0 0 0-17.3 44.6 79.8 79.8 0 0 0-69.3 71.6 79.5 79.5 0 0 0-50.4 26.8 56 56 0 0 0-3.2-13.2 56.6 56.6 0 0 0-20.6-109.2h-.6c3-8.8 5.6-20.2 5-33.2a10 10 0 0 0-7-9c-12.6-4.2-27.2-.1-37.6 4.3a85.2 85.2 0 0 0-25-44.4 10 10 0 0 0-11.4-1.6 84.3 84.3 0 0 0-41 49.2A49.7 49.7 0 0 0 70 81a10 10 0 0 0-10 6.6 61 61 0 0 0-1.7 35h-1.7a56.6 56.6 0 0 0-24.8 107.2 56.2 56.2 0 0 0 9.1 58.2H10a10 10 0 0 0-10 10v204a10 10 0 0 0 10 10h492a10 10 0 0 0 10-10V298v-.5-.4zm-20.7-9.1H373.4a59.7 59.7 0 0 1 117.9 0zm-107-172a41 41 0 0 1 30.2-11.5 41 41 0 0 1-11.5 30.3 41.1 41.1 0 0 1-30.3 11.5 41 41 0 0 1 11.5-30.3zm-21.6 49.8a59.7 59.7 0 0 1 59.2 52.4 79.8 79.8 0 0 0-59.2 40.3 79.8 79.8 0 0 0-59.1-40.3 59.7 59.7 0 0 1 59.1-52.4zm-69.6 71.7c29.8 0 54.5 22 59 50.5H234a59.7 59.7 0 0 1 59-50.5zm-83.8-95a36.5 36.5 0 0 1 5 72.7h-.1a36.7 36.7 0 0 1-28.4-8.3 56.2 56.2 0 0 0-5.3-50.3c6.9-8.8 17.5-14 28.8-14zm-76.4 81a36.6 36.6 0 1 1 1.2 0H133zm1 64.5H127c1.2-1.5 2.4-3 3.4-4.5 1.1 1.6 2.3 3 3.5 4.5zm-13.5-36.5a36.6 36.6 0 1 1-69.2-16.2 57 57 0 0 0 39.2-11.1 56.6 56.6 0 0 0 28.7 17.6c.9 3.1 1.3 6.4 1.3 9.7zm21-8.7c13.5-2 25.4-8.8 34-18.6a56.4 56.4 0 0 0 34 11.3h.4a36.6 36.6 0 1 1-68.3 7.3zm-64.7-140a38.4 38.4 0 0 1 13.6 9.1 10 10 0 0 0 17.5-5.6c0-.3 3-29.2 27.8-46.1a67.5 67.5 0 0 1 15.3 39.6 10 10 0 0 0 15.3 8.6 70 70 0 0 1 27.3-9.8 70.8 70.8 0 0 1-9.5 30 56.5 56.5 0 0 0-17.2 13.2 56.2 56.2 0 0 0-67.8 0 57 57 0 0 0-17.4-13.4c-2.1-3.9-6.4-13.7-4.9-25.7zM20 179a36.6 36.6 0 0 1 65.3-22.4 56.1 56.1 0 0 0-5.2 50.3 36 36 0 0 1-31.7 7.7A36.6 36.6 0 0 1 20 179zm472 211H182.3a10 10 0 0 0 0 20H492v82H20v-82h82.7a10 10 0 0 0 0-20H20v-82h472v82z" />
          <path d="M360.4 62.1a55.9 55.9 0 0 0-79 0 10 10 0 0 0 14.2 14.2c14-14 36.7-14 50.6 0a10 10 0 0 0 14.2 0c3.9-4 3.9-10.3 0-14.2z" />
          <path d="M392.7 29.7a101.7 101.7 0 0 0-143.6 0A10 10 0 1 0 263.2 44a81.7 81.7 0 0 1 115.4 0c2 2 4.5 3 7 3s5.2-1 7.1-3a10 10 0 0 0 0-14.2zM328 94.7a10 10 0 0 0-14.2 0 10 10 0 0 0 0 14.2 10 10 0 0 0 14.2 0 10 10 0 0 0 0-14.2z" />
        </svg>
        <span placeholder-id="num-ingredients"></span>
        <p>ingredienten</p>
      </div>

      <div>
        <svg version="1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 483 483">
          <path d="M240 260h3c29 0 53-11 70-30 39-44 32-118 32-125-3-54-28-79-49-91-15-9-33-14-53-14h-1-1c-11 0-33 2-54 14s-47 37-49 91c-1 7-7 81 31 125 18 19 41 30 71 30zm-75-153c3-72 54-80 76-80h1c27 1 73 12 76 80 0 1 7 69-25 105a66 66 0 0 1-51 21h-1c-22 0-39-7-52-21-31-36-24-104-24-105z" />
          <path d="M447 384v-1-2c-1-20-2-66-46-81h-1c-45-12-82-38-83-38a13 13 0 1 0-15 22c2 1 41 29 91 42 23 8 26 33 27 56v2l-2 31c-16 9-80 41-176 41-97 0-161-32-177-41-2-8-2-22-2-31v-3c1-22 3-47 27-56 49-12 89-40 91-41a13 13 0 1 0-15-22c-1 0-38 26-83 37l-1 1c-44 15-45 61-46 81v2c0 6 0 32 5 46l5 6c3 2 75 48 196 48s192-46 195-48l5-6c5-13 5-40 5-45z" /></svg>
        <span placeholder-id="serving_size"></span>
        <p placeholder-id="serving_size_unit"></p>
      </div>
    </div>
  </div>
  <div class="card__action">
    <button class="card__button">Bekijk recept</button>
  </div>
  <div class="card__detail hidden">
    <p class="card__title" placeholder-id="recipe_title"></p>
    <ul>

    </ul>
    <button class="card__button">Sluiten</button>
  </div>
</div>

<?php return ob_get_clean();
}
function renderPlaceholderCard1()
{
    ob_start();?>

<div class="card">
  <div class="card__image">
    <div class="card__vendor">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 55 57">
        <g>
          <title>Layer 1</title>
          <g id="Enveloppe">
            <path id="svg_1" d="m51.916397,24.327343c0,0 -7.400002,-13.299999 -10.900002,-19.799999c-1.099998,-2 -2.299999,-3 -3.5,-3.4v0c-1.200001,-0.3 -2.700001,-0.1 -4.799995,1c-6.49999,3.6 -19.799992,10.9 -19.799992,10.9s-4.700003,2 -5.700003,5.5c-1,3.4 -6.199999,21.200001 -6.199999,21.200001s-0.6,3.899998 2.699999,4.899998c3.3,1 19.500004,5.600002 19.500004,5.600002s16.199987,4.700001 19.499987,5.599998c3.299999,1 4.899998,-2.700001 4.899998,-2.700001s5.200001,-17.799999 6.200001,-21.199999c1,-3.4 -1.899998,-7.6 -1.899998,-7.6z"
              fill="#179EDA" clip-rule="evenodd" fill-rule="evenodd" />
          </g>
          <g id="AH">
            <path id="svg_2" d="m30,24.249609c1.200001,-1.4 2.599998,-3.6 4.299999,-4.799999c1.700001,-1 4.100002,-1.4 5.900002,-0.5c2.099998,1 3.5,3 3.700001,5.4v18.099998h-4.5l0,-17.299999c0,-0.799999 -0.600002,-1.4 -1.300003,-1.699999c-0.899998,-0.200001 -1.699997,0.199999 -2.299999,0.799999l-5.799999,7v11.1h-4.5v-5c-1.1,1.200001 -2.200001,2.599998 -3.299999,3.599998c-1.400002,1.200001 -3.300001,1.700001 -5.400002,1.400002c-0.5,-0.200001 -1.099999,-0.200001 -1.599999,-0.5c-5.5,-2.5 -3.8,-10 -4,-15.700001c0.2,-2.599998 0.8,-5.5 3.1,-6.699999v-0.1c0.5,-0.1 0.9,-0.5 1.4,-0.5c0.400001,-0.1 0.8,-0.200001 1.3,-0.200001c0.4,-0.199999 1,-0.099998 1.5,-0.099998v0c2.6,0.4 4.5,2.4 6.1,4.4c0.299999,0.5 0.6,0.9 1,1.4l0.1,-0.1v-3.6l4.599998,-6.099999v9.7l-0.299999,0zm-12,-1.1c-0.799999,-0.099998 -1.6,0.300001 -1.9,1c-0.900001,2.900002 -0.5,6.5 -0.5,9.700001c0.2,1.400002 0.099999,2.900002 1.199999,3.700001c0.1,0.099998 0.300001,0 0.300001,0.200001c0.9,0.099998 1.699999,-0.100002 2.299999,-0.600002c2.200001,-1.700001 3.6,-4 5.200001,-6.099998c-1,-1.800001 -2.1,-3.400002 -3.300001,-5.1c-0.9,-1 -1.799999,-2.300001 -3.299999,-2.800001l0,0z"
              fill="#FFFFFF" clip-rule="evenodd" fill-rule="evenodd" />
          </g>
        </g>
      </svg>
    </div>
    <img src="http://placehold.it/500x281" placeholder-id="recipe_image" />
    <div class="card__change-recipe" placeholder-id="edit-recipe"><span>Wijzigen</span></div>
  </div>
  <div class="card__content">
    <span class="card__title" placeholder-id="recipe_title"></span>
    <div class="card__meta">
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60">
          <path d="M30 0a30 30 0 1 0 0 60 30 30 0 0 0 0-60zm0 58a28 28 0 1 1 0-56 28 28 0 0 1 0 56z" />
          <path d="M31 26V16a1 1 0 1 0-2 0V26a4 4 0 0 0-2.9 2.9H19a1 1 0 1 0 0 2h7.1A4 4 0 1 0 31 26zM30 32a2 2 0 1 1 0-4 2 2 0 0 1 0 4zM30 9.9c.6 0 1-.5 1-1v-1a1 1 0 1 0-2 0v1c0 .5.4 1 1 1zM30 49.9a1 1 0 0 0-1 1v1a1 1 0 1 0 2 0v-1c0-.6-.4-1-1-1zM52 28.9h-1a1 1 0 1 0 0 2h1a1 1 0 1 0 0-2zM9 28.9H8a1 1 0 1 0 0 2h1a1 1 0 1 0 0-2zM44.8 13.6l-.7.7a1 1 0 1 0 1.5 1.4l.7-.7a1 1 0 1 0-1.5-1.4zM14.4 44l-.7.7a1 1 0 1 0 1.5 1.4l.7-.7a1 1 0 1 0-1.5-1.4zM45.6 44a1 1 0 1 0-1.5 1.4l.7.7a1 1 0 0 0 1.5 0c.4-.3.4-1 0-1.4l-.7-.7zM15.2 13.6a1 1 0 1 0-1.5 1.4l.7.7a1 1 0 0 0 1.5 0c.3-.4.3-1 0-1.4l-.7-.7z" /></svg>
        <span placeholder-id="cook-time"></span>
        <p>minuten</p>
      </div>
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
          <path d="M71 443.8h-.1A10 10 0 1 0 57 458.1h.1a10 10 0 0 0 14.2-.3 10 10 0 0 0-.3-14.1zM71 341.8h-.1A10 10 0 1 0 57 356.1h.1a10 10 0 0 0 14.2-.3 10 10 0 0 0-.3-14.1zM454.9 443.8l-.1-.1a10 10 0 0 0-13.8 14.5h.1a10 10 0 0 0 14.2-.3 10 10 0 0 0-.4-14.1zM454.9 341.8l-.1-.1a10 10 0 0 0-13.8 14.5h.1a10 10 0 0 0 14.2-.3 10 10 0 0 0-.4-14.1zM142.7 390h-.2a10 10 0 0 0 0 20h.2a10 10 0 0 0 0-20z" />
          <path d="M512 297.1a79.7 79.7 0 0 0-70-79 79.7 79.7 0 0 0-36.8-60c4.3-2.5 8.3-5.5 12-9.2C431 135 437 115 433.5 93.7a10 10 0 0 0-8.2-8.2 62.4 62.4 0 0 0-55.2 16.4 60.8 60.8 0 0 0-17.3 44.6 79.8 79.8 0 0 0-69.3 71.6 79.5 79.5 0 0 0-50.4 26.8 56 56 0 0 0-3.2-13.2 56.6 56.6 0 0 0-20.6-109.2h-.6c3-8.8 5.6-20.2 5-33.2a10 10 0 0 0-7-9c-12.6-4.2-27.2-.1-37.6 4.3a85.2 85.2 0 0 0-25-44.4 10 10 0 0 0-11.4-1.6 84.3 84.3 0 0 0-41 49.2A49.7 49.7 0 0 0 70 81a10 10 0 0 0-10 6.6 61 61 0 0 0-1.7 35h-1.7a56.6 56.6 0 0 0-24.8 107.2 56.2 56.2 0 0 0 9.1 58.2H10a10 10 0 0 0-10 10v204a10 10 0 0 0 10 10h492a10 10 0 0 0 10-10V298v-.5-.4zm-20.7-9.1H373.4a59.7 59.7 0 0 1 117.9 0zm-107-172a41 41 0 0 1 30.2-11.5 41 41 0 0 1-11.5 30.3 41.1 41.1 0 0 1-30.3 11.5 41 41 0 0 1 11.5-30.3zm-21.6 49.8a59.7 59.7 0 0 1 59.2 52.4 79.8 79.8 0 0 0-59.2 40.3 79.8 79.8 0 0 0-59.1-40.3 59.7 59.7 0 0 1 59.1-52.4zm-69.6 71.7c29.8 0 54.5 22 59 50.5H234a59.7 59.7 0 0 1 59-50.5zm-83.8-95a36.5 36.5 0 0 1 5 72.7h-.1a36.7 36.7 0 0 1-28.4-8.3 56.2 56.2 0 0 0-5.3-50.3c6.9-8.8 17.5-14 28.8-14zm-76.4 81a36.6 36.6 0 1 1 1.2 0H133zm1 64.5H127c1.2-1.5 2.4-3 3.4-4.5 1.1 1.6 2.3 3 3.5 4.5zm-13.5-36.5a36.6 36.6 0 1 1-69.2-16.2 57 57 0 0 0 39.2-11.1 56.6 56.6 0 0 0 28.7 17.6c.9 3.1 1.3 6.4 1.3 9.7zm21-8.7c13.5-2 25.4-8.8 34-18.6a56.4 56.4 0 0 0 34 11.3h.4a36.6 36.6 0 1 1-68.3 7.3zm-64.7-140a38.4 38.4 0 0 1 13.6 9.1 10 10 0 0 0 17.5-5.6c0-.3 3-29.2 27.8-46.1a67.5 67.5 0 0 1 15.3 39.6 10 10 0 0 0 15.3 8.6 70 70 0 0 1 27.3-9.8 70.8 70.8 0 0 1-9.5 30 56.5 56.5 0 0 0-17.2 13.2 56.2 56.2 0 0 0-67.8 0 57 57 0 0 0-17.4-13.4c-2.1-3.9-6.4-13.7-4.9-25.7zM20 179a36.6 36.6 0 0 1 65.3-22.4 56.1 56.1 0 0 0-5.2 50.3 36 36 0 0 1-31.7 7.7A36.6 36.6 0 0 1 20 179zm472 211H182.3a10 10 0 0 0 0 20H492v82H20v-82h82.7a10 10 0 0 0 0-20H20v-82h472v82z" />
          <path d="M360.4 62.1a55.9 55.9 0 0 0-79 0 10 10 0 0 0 14.2 14.2c14-14 36.7-14 50.6 0a10 10 0 0 0 14.2 0c3.9-4 3.9-10.3 0-14.2z" />
          <path d="M392.7 29.7a101.7 101.7 0 0 0-143.6 0A10 10 0 1 0 263.2 44a81.7 81.7 0 0 1 115.4 0c2 2 4.5 3 7 3s5.2-1 7.1-3a10 10 0 0 0 0-14.2zM328 94.7a10 10 0 0 0-14.2 0 10 10 0 0 0 0 14.2 10 10 0 0 0 14.2 0 10 10 0 0 0 0-14.2z" />
        </svg>
        <span placeholder-id="num-ingredients"></span>
        <p>ingredienten</p>
      </div>

      <div>
        <svg version="1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 483 483">
          <path d="M240 260h3c29 0 53-11 70-30 39-44 32-118 32-125-3-54-28-79-49-91-15-9-33-14-53-14h-1-1c-11 0-33 2-54 14s-47 37-49 91c-1 7-7 81 31 125 18 19 41 30 71 30zm-75-153c3-72 54-80 76-80h1c27 1 73 12 76 80 0 1 7 69-25 105a66 66 0 0 1-51 21h-1c-22 0-39-7-52-21-31-36-24-104-24-105z" />
          <path d="M447 384v-1-2c-1-20-2-66-46-81h-1c-45-12-82-38-83-38a13 13 0 1 0-15 22c2 1 41 29 91 42 23 8 26 33 27 56v2l-2 31c-16 9-80 41-176 41-97 0-161-32-177-41-2-8-2-22-2-31v-3c1-22 3-47 27-56 49-12 89-40 91-41a13 13 0 1 0-15-22c-1 0-38 26-83 37l-1 1c-44 15-45 61-46 81v2c0 6 0 32 5 46l5 6c3 2 75 48 196 48s192-46 195-48l5-6c5-13 5-40 5-45z" /></svg>
        <span placeholder-id="serving_size"></span>
        <p placeholder-id="serving_size_unit"></p>
      </div>
    </div>
    <div class="card__description">
      <p placeholder-id="recipe_description"></p>
    </div>

    <div class="card__detail hidden">
      <p class="card__title" placeholder-id="recipe_title"></p>
      <ul>

      </ul>

      <button class="card__button">Sluiten</button>
    </div>

    <button class="card__button">Bekijk recept</button>
  </div>
</div>

<?php return ob_get_clean();
}