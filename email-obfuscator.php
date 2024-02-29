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

function email_obfuscator_init()
{
    $options = get_option('email_obfuscator_options');
    if (!empty($options['email_obfuscator_setting_enable'])) {

        function obfuscate_emails($content)
        {
            global $options; // Ensure $options is accessible inside this function
            $dom = new DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $xpath = new DOMXPath($dom);

            if (isset($options['email_obfuscator_wrap_unlinked_emails']) && $options['email_obfuscator_wrap_unlinked_emails']) {
                $textNodes = $xpath->query('//text()');
                foreach ($textNodes as $textNode) {
                    if (preg_match_all('/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/', $textNode->nodeValue, $emails)) {
                        foreach ($emails[0] as $email) {
                            $encryptedEmail = encrypt_email($email);
                            $decryptedLink = generate_decrypt_script($encryptedEmail);
                            $replacementNode = $dom->createElement('a', htmlspecialchars($email));
                            $replacementNode->setAttribute('href', 'javascript:' . $decryptedLink);
                            $textNode->parentNode->replaceChild($replacementNode, $textNode);
                        }
                    }
                }
            }

            return $dom->saveHTML();
        }
        add_filter('the_content', 'obfuscate_emails');

        function email_obfuscator_enqueue_scripts()
        {
            wp_enqueue_script('email-decrypt', plugin_dir_url(__FILE__) . 'js/decrypt.js', array(), '1.0', true);
        }
        add_action('wp_enqueue_scripts', 'email_obfuscator_enqueue_scripts');
    }
}
add_action('init', 'email_obfuscator_init');

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
        'email_obfuscator_setting_enable' => 1, // Ensure the default is checked
        'email_obfuscator_wrap_unlinked_emails' => 0, // New setting for wrapping unlinked emails
    );
    if (false === get_option('email_obfuscator_options')) {
        add_option('email_obfuscator_options', $default_options);
    }
}
register_activation_hook(__FILE__, 'email_obfuscator_activate');
