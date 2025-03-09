<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

function srspc_register_settings() {
    add_settings_section('srspc_main_section', null, null, 'srspc_settings');

    // Location Setting
    register_setting('srspc_plugin', 'srspc_location', ['sanitize_callback' => 'srspc_sanitize_location', 'default' => '0']);
    add_settings_field('srspc_location', 'Display Location', 'srspc_location_field', 'srspc_settings', 'srspc_main_section');

    // Headline Setting
    register_setting('srspc_plugin', 'srspc_headline', ['sanitize_callback' => 'sanitize_text_field', 'default' => 'Post Statistics']);
    add_settings_field('srspc_headline', 'Headline Text', 'srspc_headline_field', 'srspc_settings', 'srspc_main_section');

    // Other settings
    $settings = ['srspc_wordcount' => 'Word Count', 'srspc_charactercount' => 'Character Count', 'srspc_readtime' => 'Read Time'];
    foreach ($settings as $name => $label) {
        register_setting('srspc_plugin', $name, ['sanitize_callback' => 'sanitize_text_field', 'default' => '1']);
        add_settings_field($name, $label, 'srspc_checkbox_field', 'srspc_settings', 'srspc_main_section', ['name' => $name]);
    }
}

function srspc_sanitize_location($input) {
    return ($input == '0' || $input == '1') ? $input : get_option('srspc_location');
}
