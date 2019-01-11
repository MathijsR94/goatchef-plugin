<?php

function gc_show_faq($attrs, $content)
{
    $num_posts = empty($attrs['faqs']) ? 3 : $attrs['faqs'];
    $args = array(
        'post_type' => 'faq',
        'post_status' => 'publish',
    );
    $query = new WP_Query($args);
    $faqs = $query->posts;
    ob_start();
    if (!empty($faqs)) {
        echo '<ul class="faq">';
        foreach ($faqs as $post) {
            echo '<li class="faq__entry">
                  <p class="h2">' . $post->post_title . '</p>
                  <p>' . $post->post_content . '</p>
                  <a href="' . get_permalink($post->ID) . '">Lees meer</a>
                </li>';
        }
        echo "</ul>";
    } else {
        echo 'Geen items gevonden';
    }

    return ob_get_clean();
}
add_shortcode('faq', 'gc_show_faq');
