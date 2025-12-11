<?php
namespace Thema;

add_action('after_setup_theme', __NAMESPACE__ . '\setup');
function setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    register_nav_menus([
        'primary' => __('Primary Menu', 'thema'),
    ]);
}

add_action('wp_enqueue_scripts', __NAMESPACE__ . '\assets');
function assets() {
    $ver = wp_get_theme()->get('Version');
    wp_enqueue_style('thema-style', get_stylesheet_uri(), [], $ver);
    $variant = get_theme_mod('thema_variant', 'default');
    if ($variant !== 'default') {
        $variant_css = get_stylesheet_directory_uri() . '/variants/' . $variant . '.css';
        wp_enqueue_style('thema-variant', $variant_css, ['thema-style'], $ver);
    }
}

function get_cat_id_by_slug($slug) {
    $cat = get_category_by_slug($slug);
    return $cat ? (int) $cat->term_id : 0;
}

function render_post_card($post) {
    setup_postdata($post);
    $label = get_post_meta($post->ID, 'label', true);
    echo '<article class="card">';
    if (has_post_thumbnail($post)) {
        echo get_the_post_thumbnail($post, 'large');
    }
    if ($label) {
        echo '<span class="label">' . esc_html($label) . '</span>';
    }
    echo '<h3 class="text-lg leading-tight"><a href="' . esc_url(get_permalink($post)) . '">' . esc_html(get_the_title($post)) . '</a></h3>';
    echo '<div class="meta muted text-xs">' . esc_html(get_the_date('', $post)) . ' · ' . esc_html(get_the_author_meta('display_name', $post->post_author)) . '</div>';
    echo '</article>';
    wp_reset_postdata();
}

require_once get_stylesheet_directory() . '/inc/customizer.php';
