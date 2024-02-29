<?php
/*
Plugin Name: Email Obfuscator
Plugin URI:  http://chasedouglas.consulting/
Description: A lightweight plugin to obfuscate email addresses and protect them from spam bots.
Version:     1.0
Author:      Chase Douglas
Author URI:  http://chasedouglas.consulting
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

include_once(plugin_dir_path(__FILE__) . 'email-obfuscator-settings.php');

function encrypt_email($email)
{
    return base64_encode($email);
}

function generate_decrypt_script($encrypted_email)
{
    return 'DeCryptX(\'' . $encrypted_email . '\')';
}

function generate_mailto_link($email)
{
    $encrypted_email = encrypt_email($email);
    $javascript_code = generate_decrypt_script($encrypted_email);
    return '<a href="javascript:' . htmlspecialchars($javascript_code) . '">' . htmlspecialchars($email) . '</a>';
}

function obfuscate_emails($content)
{
    $options = get_option('email_obfuscator_options');
    if (isset($options['email_obfuscator_wrap_unlinked_emails']) && $options['email_obfuscator_wrap_unlinked_emails']) {
        // Similar logic for finding and obfuscating unlinked emails goes here.
    }
    return $content; // Return modified or unmodified content.
}

function email_obfuscator_enqueue_scripts()
{
    wp_enqueue_script('email-decrypt', plugin_dir_url(__FILE__) . 'js/decrypt.js', array(), '1.0', true);
}

function email_obfuscator_init()
{
    add_filter('the_content', 'obfuscate_emails');
    add_action('wp_enqueue_scripts', 'email_obfuscator_enqueue_scripts');
}

if (get_option('email_obfuscator_options')['email_obfuscator_setting_enable']) {
    add_action('init', 'email_obfuscator_init');
}

function email_obfuscator_add_settings_link($links)
{
    $settings_link = '<a href="options-general.php?page=email_obfuscator">' . __('Settings') . '</a>';
    array_push($links, $settings_link);
    return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'email_obfuscator_add_settings_link');

function email_obfuscator_activate()
{
    $default_options = array(
        'email_obfuscator_setting_enable' => 1,
        'email_obfuscator_wrap_unlinked_emails' => 0,
    );
    if (false === get_option('email_obfuscator_options')) {
        add_option('email_obfuscator_options', $default_options);
    }
}
register_activation_hook(__FILE__, 'email_obfuscator_activate');
