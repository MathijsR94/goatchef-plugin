<?php
/*
Plugin Name:  Goatchef Site Specific Plugin
Description:  Adds site specific functionality to the Goatchef website.
 */
use Goatchef\Goal\GoalEnum;
use Goatchef\Plugin;

include_once 'cpt/faq.php';

include_once 'shortcodes/faq.php';
include_once 'shortcodes/homepage-products.php';
include_once 'shortcodes/homepage-calculator.php';

function gc_enqueue_scripts()
{
    $goatchef_plugin = Plugin::get_instance();
    $recipes = $goatchef_plugin->database->recipe->getRecipes();
    wp_enqueue_script('gc-polyfills', plugin_dir_url(__FILE__) . 'js/polyfills.js', array('jquery'), '1.0', true);

    wp_enqueue_script('front-page-factory', plugin_dir_url(__FILE__) . 'js/factory.js', array(), '1.0.0');

    wp_localize_script('front-page-factory', 'gcMacros', array(
        'macros' => [
            'cutting' => GoalEnum::Cutting,
            'weightLoss' => GoalEnum::WeightLoss,
            'healthyBalance' => GoalEnum::HealthyBalance,
            'bulking' => GoalEnum::Bulking,
            'dirtyBulking' => GoalEnum::DirtyBulking,
            'cardio' => GoalEnum::Cardio,
        ],
        'recipes' => $recipes,
    ));

    if (is_front_page()) {
        wp_enqueue_script('front-page', plugin_dir_url(__FILE__) . 'js/front-page.js', array('jquery'), '1.4', true);
        wp_localize_script('front-page', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
        ));
        wp_enqueue_script('front-page-calculator', plugin_dir_url(__FILE__) . 'js/calculator.js', array('jquery'), '1.0', true);
        wp_enqueue_script('front-page-products', plugin_dir_url(__FILE__) . 'js/homepage-products.js', array('jquery'), '1.1', true);
        wp_localize_script('front-page', 'gcProducts', array(
            'recipes' => $recipes,
            'pluginUrl' => plugin_dir_url(__FILE__),
        ));

        wp_enqueue_script('front-page-snackbar', plugin_dir_url(__FILE__) . 'js/snackbar.js', array('jquery'), '1.0', true);
        wp_enqueue_script('front-page-progress', plugin_dir_url(__FILE__) . 'js/progress.js', array('jquery'), '1.0', true);
        wp_localize_script('front-page-progress', 'gcProgress', array(
            'recipes' => $goatchef_plugin->database->recipe->getRecipes(),
            'pluginUrl' => plugin_dir_url(__FILE__),
        ));

        wp_enqueue_script('front-page-macros', plugin_dir_url(__FILE__) . 'js/macros.js', array('jquery'), '1.0', true);
    }
}

add_action('wp_enqueue_scripts', 'gc_enqueue_scripts');
