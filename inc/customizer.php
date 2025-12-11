<?php
namespace Thema;

add_action('customize_register', __NAMESPACE__ . '\customize');
function customize($wp_customize) {
    $wp_customize->add_section('thema_variant_section', [
        'title' => __('Theme Variant', 'thema'),
        'priority' => 30,
    ]);
    $wp_customize->add_setting('thema_variant', [
        'default' => 'default',
        'sanitize_callback' => 'sanitize_key',
    ]);
    $wp_customize->add_control('thema_variant', [
        'type' => 'radio',
        'section' => 'thema_variant_section',
        'label' => __('Choose Variant', 'thema'),
        'choices' => [
            'default' => __('Default', 'thema'),
            'magazine' => __('Magazine', 'thema'),
            'compact' => __('Compact', 'thema'),
        ],
    ]);

    $choices = [];
    foreach (get_categories(['hide_empty' => false]) as $c) {
        $choices[$c->term_id] = $c->name;
    }
    $wp_customize->add_section('thema_home_sections', [
        'title' => __('Homepage Sections', 'thema'),
        'priority' => 35,
    ]);
    for ($i = 1; $i <= 4; $i++) {
        $setting_id = 'thema_home_section_' . $i;
        $wp_customize->add_setting($setting_id, [
            'default' => 0,
            'sanitize_callback' => 'absint',
        ]);
        $wp_customize->add_control($setting_id, [
            'type' => 'select',
            'section' => 'thema_home_sections',
            'label' => sprintf(__('Section %d Category', 'thema'), $i),
            'choices' => $choices,
        ]);
    }
}

