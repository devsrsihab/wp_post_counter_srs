<?php
/*
Plugin Name: SRS Post Counter
Plugin URI: https://srsplugins.com
Description: A simple plugin to display the number of posts in a category.
Version: 1.0    
Author: Md. Sohanur Rohman Sihab
Author URI: https://srsplugins.com
Text Domain: srspcdomain
Domain Path: /languages
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin path
define('SRSPC_PLUGIN_PATH', plugin_dir_path(__FILE__));

// Include required files
require_once SRSPC_PLUGIN_PATH . 'includes/settings.php';
require_once SRSPC_PLUGIN_PATH . 'includes/admin-page.php';
require_once SRSPC_PLUGIN_PATH . 'includes/post-counter.php';

class SRSPostCounter {

    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_page']);
        add_action('admin_init', 'srspc_register_settings');
        add_filter('the_content', 'srspc_filter_content');
        add_action('init', array($this, 'languages'));
    }

    function languages() {
        load_plugin_textdomain('srspcdomain', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }


    public function add_admin_page() {
        add_options_page(
            'SRS Post Counter',
            __('SRS Post Counter', 'srspcdomain'),
            'manage_options',
            'srspc_settings',
            'srspc_settings_page'
        );
    }
}

new SRSPostCounter();
