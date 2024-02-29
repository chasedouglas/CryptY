<?php
/*
Plugin Name: Email Obfuscator Settings 
Description: Simple settings panel do enable or disable the code from running. More features coming soon.
Version: 1.0
Author: Chase Douglas
*/

// Hook into the admin menu to add the settings page
add_action('admin_menu', 'email_obfuscator_add_admin_menu');
function email_obfuscator_add_admin_menu()
{
    add_options_page('Email Obfuscator Settings', 'Email Obfuscator', 'manage_options', 'email_obfuscator', 'email_obfuscator_settings_page');
}

// Render the settings page content
function email_obfuscator_settings_page()
{
?>
    <div class="wrap">
        <h2>Email Obfuscator Settings</h2>
        <form action="options.php" method="post">
            <?php
            settings_fields('email_obfuscator_options_group');
            do_settings_sections('email_obfuscator');
            submit_button();
            ?>
        </form>
    </div>
<?php
}

// Initialize settings
add_action('admin_init', 'email_obfuscator_settings_init');
function email_obfuscator_settings_init()
{
    register_setting('email_obfuscator_options_group', 'email_obfuscator_options', 'email_obfuscator_options_sanitize');

    add_settings_section(
        'email_obfuscator_settings_section',
        'General Settings',
        'email_obfuscator_settings_section_callback',
        'email_obfuscator'
    );

    add_settings_field(
        'email_obfuscator_setting_enable',
        'Enable Email Obfuscation',
        'email_obfuscator_setting_enable_render',
        'email_obfuscator',
        'email_obfuscator_settings_section'
    );

    // Add a new setting field for "option_find_non_mailto"
    add_settings_field(
        'option_find_non_mailto',
        'Automatically link non-mailto emails',
        'email_obfuscator_option_find_non_mailto_render',
        'email_obfuscator',
        'email_obfuscator_settings_section'
    );
}

function email_obfuscator_settings_section_callback()
{
    echo 'You can turn the Obfuscator on or off below. More features are coming soon!';
}

function email_obfuscator_setting_enable_render()
{
    $options = get_option('email_obfuscator_options');
?>
    <input type='checkbox' name='email_obfuscator_options[email_obfuscator_setting_enable]' <?php checked(isset($options['email_obfuscator_setting_enable']), 1); ?> value='1'>
<?php
}

// Render the new checkbox for "option_find_non_mailto"
function email_obfuscator_option_find_non_mailto_render()
{
    $options = get_option('email_obfuscator_options');
?>
    <input type='checkbox' name='email_obfuscator_options[option_find_non_mailto]' <?php checked(isset($options['option_find_non_mailto']), 1); ?> value='1'>
<?php
}

// Sanitize and validate input
function email_obfuscator_options_sanitize($options)
{
    if (!is_array($options)) {
        $options = [];
    }

    if (isset($options['email_obfuscator_setting_enable'])) {
        $options['email_obfuscator_setting_enable'] = (bool)$options['email_obfuscator_setting_enable'];
    } else {
        $options['email_obfuscator_setting_enable'] = false;
    }

    // Sanitize the new "option_find_non_mailto" setting
    if (isset($options['option_find_non_mailto'])) {
        $options['option_find_non_mailto'] = (bool)$options['option_find_non_mailto'];
    } else {
        $options['option_find_non_mailto'] = false;
    }

    return $options;
}
