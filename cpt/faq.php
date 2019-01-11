<?php

// Register Custom Post Type
function gc_register_faq()
{
    $labels = array(
        'name' => _x('FAQs', 'Post Type General Name', 'goatchef'),
        'singular_name' => _x('FAQ', 'Post Type Singular Name', 'goatchef'),
        'menu_name' => __('FAQ', 'goatchef'),
        'name_admin_bar' => __('Post Type', 'goatchef'),
        'archives' => __('Item Archives', 'goatchef'),
        'attributes' => __('Item Attributes', 'goatchef'),
        'parent_item_colon' => __('Parent Item:', 'goatchef'),
        'all_items' => __('All Items', 'goatchef'),
        'add_new_item' => __('Add New Item', 'goatchef'),
        'add_new' => __('Add New', 'goatchef'),
        'new_item' => __('New Item', 'goatchef'),
        'edit_item' => __('Edit Item', 'goatchef'),
        'update_item' => __('Update Item', 'goatchef'),
        'view_item' => __('View Item', 'goatchef'),
        'view_items' => __('View Items', 'goatchef'),
        'search_items' => __('Search Item', 'goatchef'),
        'not_found' => __('Not found', 'goatchef'),
        'not_found_in_trash' => __('Not found in Trash', 'goatchef'),
        'featured_image' => __('Featured Image', 'goatchef'),
        'set_featured_image' => __('Set featured image', 'goatchef'),
        'remove_featured_image' => __('Remove featured image', 'goatchef'),
        'use_featured_image' => __('Use as featured image', 'goatchef'),
        'insert_into_item' => __('Insert into item', 'goatchef'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'goatchef'),
        'items_list' => __('Items list', 'goatchef'),
        'items_list_navigation' => __('Items list navigation', 'goatchef'),
        'filter_items_list' => __('Filter items list', 'goatchef'),
    );
    $args = array(
        'label' => __('FAQ', 'goatchef'),
        'description' => __('Adds faq functionality to the Goatchef website', 'goatchef'),
        'labels' => $labels,
        'supports' => array('title', 'editor'),
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-admin-comments',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
    );
    register_post_type('faq', $args);
}
add_action('init', 'gc_register_faq', 0);
