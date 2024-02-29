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
    $dom = new DOMDocument();
    @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $xpath = new DOMXPath($dom);

    // Process links with mailto: href
    $links = $xpath->query('//a[contains(@href, "mailto:")]');
    foreach ($links as $link) {
        $href = $link->getAttribute('href');
        if (strpos($href, 'mailto:') === 0) {
            $email = substr($href, 7); // Remove the 'mailto:' part
            $encryptedEmail = encrypt_email($email);
            $decryptedLink = generate_decrypt_script($encryptedEmail);
            $link->setAttribute('href', 'javascript:' . $decryptedLink);
        }
    }

    // Additional logic to obfuscate unlinked emails, avoiding text within <a> tags
    if (!empty($options['option_find_non_mailto'])) {
        // Targeting only text nodes not directly inside <a> tags
        $textNodes = $xpath->query('//text()[not(parent::a)]');
        foreach ($textNodes as $textNode) {
            if (preg_match_all('/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/', $textNode->nodeValue, $emails)) {
                $newContent = $textNode->nodeValue;
                foreach ($emails[0] as $email) {
                    $obfuscatedEmailLink = generate_mailto_link($email);
                    // Replace the plain text email with the obfuscated link
                    $newContent = str_replace($email, $obfuscatedEmailLink, $newContent);
                }
                $newNode = $dom->createDocumentFragment();
                // Use htmlspecialchars to avoid breaking HTML entities
                $newNode->appendXML('<![CDATA[' . htmlspecialchars($newContent) . ']]>');
                if ($newNode->hasChildNodes()) {
                    $textNode->parentNode->replaceChild($newNode, $textNode);
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

function email_obfuscator_add_settings_link($links)
{
    $settings_link = '<a href="options-general.php?page=email_obfuscator">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'email_obfuscator_add_settings_link');

function email_obfuscator_activate()
{
    $default_options = array(
        'email_obfuscator_setting_enable' => 1, // Ensure the default is checked
        'option_find_non_mailto' => 0, // New setting for wrapping unlinked emails
    );
    if (false === get_option('email_obfuscator_options')) {
        add_option('email_obfuscator_options', $default_options);
    }
}
register_activation_hook(__FILE__, 'email_obfuscator_activate');
