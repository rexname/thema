<?php
namespace Thema;

add_action('customize_register', __NAMESPACE__ . '\customize');
function customize($wp_customize) {
    $wp_customize->add_section('thema_variant_section', [
        'title' => __('Theme Variant', 'thema'),
        'priority' => 30,
    ]);
    $wp_customize->add_setting('thema_variant', [
        'default' => 'newspaper',
        'sanitize_callback' => 'sanitize_key',
    ]);
    $wp_customize->add_control('thema_variant', [
        'type' => 'radio',
        'section' => 'thema_variant_section',
        'label' => __('Choose Variant', 'thema'),
        'choices' => [
            'newspaper' => __('Newspaper', 'thema'),
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
    $wp_customize->add_setting('thema_home_posts_per_section', [
        'default' => 4,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('thema_home_posts_per_section', [
        'type' => 'number',
        'section' => 'thema_home_sections',
        'label' => __('Posts per Section', 'thema'),
        'input_attrs' => ['min' => 1, 'max' => 12],
    ]);
    $wp_customize->add_setting('thema_just_in_category', [
        'default' => 0,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('thema_just_in_category', [
        'type' => 'select',
        'section' => 'thema_home_sections',
        'label' => __('Just In Category (optional)', 'thema'),
        'choices' => [0 => __('All', 'thema')] + $choices,
    ]);
    $wp_customize->add_setting('thema_editors_pick_category', [
        'default' => 0,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('thema_editors_pick_category', [
        'type' => 'select',
        'section' => 'thema_home_sections',
        'label' => __('Editor’s Pick Category (optional)', 'thema'),
        'choices' => [0 => __('None', 'thema')] + $choices,
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
