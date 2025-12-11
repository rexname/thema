<?php
namespace Thema;

add_action('after_setup_theme', __NAMESPACE__ . '\setup');
function setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    add_theme_support('automatic-feed-links');
    register_nav_menus([
        'primary' => __('Primary Menu', 'thema'),
    ]);
}

add_action('wp_enqueue_scripts', __NAMESPACE__ . '\assets');
function assets() {
    $ver = wp_get_theme()->get('Version');
    wp_enqueue_style('thema-style', get_stylesheet_uri(), [], $ver);
    $variant = get_theme_mod('thema_variant', 'newspaper');
    $variant_css = get_stylesheet_directory_uri() . '/variants/' . $variant . '.css';
    wp_enqueue_style('thema-variant', $variant_css, ['thema-style'], $ver);
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
require_once get_stylesheet_directory() . '/inc/theme_json.php';

function render_adventure_section($index) {
    $per = (int) get_theme_mod('thema_home_posts_per_section', 4);
    $q = new \WP_Query(['posts_per_page'=>$per,'ignore_sticky_posts'=>true]);
    echo '<div class="grid-posts">';
    while ($q->have_posts()): $q->the_post();
        echo '<article class="card">';
        if (has_post_thumbnail()) the_post_thumbnail('medium');
        echo '<h3 class="text-lg leading-tight"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h3>';
        echo '</article>';
    endwhile;
    echo '</div>';
    wp_reset_postdata();
}

add_action('wp_head', __NAMESPACE__ . '\seo_head_tags', 1);
function seo_head_tags() {
    $is_single = is_singular();
    $title = $is_single ? wp_strip_all_tags(get_the_title()) : wp_get_document_title();
    $desc = $is_single ? wp_trim_words(wp_strip_all_tags(get_the_excerpt()), 30) : get_bloginfo('description');
    $url = $is_single ? get_permalink() : home_url(add_query_arg([]));
    $type = $is_single ? 'article' : 'website';
    $locale = get_locale();
    $img = '';
    if ($is_single && has_post_thumbnail()) {
        $img = wp_get_attachment_image_url(get_post_thumbnail_id(), 'large');
    }
    if (!$img) {
        $site_icon = get_site_icon_url(512);
        if ($site_icon) $img = $site_icon;
    }
    if ($desc) echo '<meta name="description" content="' . esc_attr($desc) . '">';
    echo '<link rel="canonical" href="' . esc_url($url) . '">';
    echo '<meta property="og:title" content="' . esc_attr($title) . '">';
    if ($desc) echo '<meta property="og:description" content="' . esc_attr($desc) . '">';
    echo '<meta property="og:type" content="' . esc_attr($type) . '">';
    echo '<meta property="og:url" content="' . esc_url($url) . '">';
    if ($img) echo '<meta property="og:image" content="' . esc_url($img) . '">';
    echo '<meta property="og:locale" content="' . esc_attr($locale) . '">';
    echo '<meta name="twitter:card" content="summary_large_image">';
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '">';
    if ($desc) echo '<meta name="twitter:description" content="' . esc_attr($desc) . '">';
    if ($img) echo '<meta name="twitter:image" content="' . esc_url($img) . '">';

    $site_json = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'url' => home_url('/'),
        'name' => get_bloginfo('name'),
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => home_url('/?s={search_term_string}'),
            'query-input' => 'required name=search_term_string'
        ]
    ];
    echo '<script type="application/ld+json">' . wp_json_encode($site_json) . '</script>';

    if ($is_single) {
        $article = [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $title,
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => [
                '@type' => 'Person',
                'name' => get_the_author()
            ],
            'mainEntityOfPage' => $url,
        ];
        if ($img) $article['image'] = [$img];
        echo '<script type="application/ld+json">' . wp_json_encode($article) . '</script>';
    }
}

add_filter('wp_get_attachment_image_attributes', __NAMESPACE__ . '\image_attrs', 10, 3);
function image_attrs($attr, $attachment, $size) {
    if (empty($attr['alt'])) {
        $alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
        if (!$alt && !empty($attachment->post_parent)) {
            $alt = get_the_title($attachment->post_parent);
        }
        if ($alt) $attr['alt'] = $alt;
    }
    if (empty($attr['loading'])) $attr['loading'] = 'lazy';
    if (empty($attr['decoding'])) $attr['decoding'] = 'async';
    return $attr;
}

add_filter('the_content', __NAMESPACE__ . '\content_external_links');
function content_external_links($content) {
    $content = preg_replace_callback(
        '/<a\s+([^>]*?target=\"_blank\"[^>]*)>/i',
        function($m){
            $tag = $m[0];
            if (stripos($tag, 'rel=') === false) {
                $tag = str_replace('>', ' rel="noopener noreferrer">', $tag);
            }
            return $tag;
        },
        $content
    );
    return $content;
}
