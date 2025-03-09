<?php
if (!defined('ABSPATH')) exit;

function srspc_settings_page() { ?>
    <div class="wrap">
        <h1><?php echo __('SRS Post Counter Settings', 'srspcdomain'); ?></h1>
        <form action="options.php" method="POST">
            <?php
            settings_fields('srspc_plugin');
            do_settings_sections('srspc_settings');
            submit_button();
            ?>
        </form>
    </div>
<?php }

// Settings Fields
function srspc_location_field() { ?>
    <select name="srspc_location">
        <option value="0" <?php selected(get_option('srspc_location'), '0'); ?>>
            <?php echo __('Beginning of post', 'srspcdomain'); ?>
        </option>
        <option value="1" <?php selected(get_option('srspc_location'), '1'); ?>>
            <?php echo __('End of post', 'srspcdomain'); ?>
        </option>
    </select>
<?php }

function srspc_headline_field() { ?>
    <input type="text" value="<?php echo esc_attr(get_option('srspc_headline')); ?>" name="srspc_headline">
<?php }

function srspc_checkbox_field($args) { ?>
    <input type="checkbox" <?php checked(get_option($args['name'], '1')); ?> value="1" name="<?php echo $args['name']; ?>">
<?php }
