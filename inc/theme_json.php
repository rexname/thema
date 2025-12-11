<?php
namespace Thema;

add_filter('wp_theme_json_data_theme', __NAMESPACE__ . '\variant_theme_json');
function variant_theme_json($theme_json) {
    $variant = get_theme_mod('thema_variant', 'default');
    if ($variant === 'default') {
        return $theme_json;
    }
    $path = get_stylesheet_directory() . '/variants/' . $variant . '.theme.json';
    if (file_exists($path)) {
        $json = file_get_contents($path);
        $data = json_decode($json, true);
        if (is_array($data) && method_exists($theme_json, 'update_with')) {
            $theme_json->update_with($data);
        }
    }
    return $theme_json;
}

