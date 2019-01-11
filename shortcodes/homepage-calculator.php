<?php

function gc_show_homepage_calculator($attrs, $content)
{
    ob_start();
    echo file_get_contents(dirname(__FILE__) . '/homepage-calculator.html');
    echo file_get_contents(dirname(__FILE__) . '/shopping-list.html');
    return ob_get_clean();
}
add_shortcode('homepage-calculator', 'gc_show_homepage_calculator');
