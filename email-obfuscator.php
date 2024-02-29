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

// Code to hide emails from bots
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
    return '<a href="javascript:' . $javascript_code . '">' . $email . '</a>';
}

function obfuscate_emails($content)
{
    $pattern = '/([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/';

    $content = preg_replace_callback($pattern, function ($matches) {
        $email = $matches[1];
        return generate_mailto_link($email);
    }, $content);

    return $content;
}
add_filter('the_content', 'obfuscate_emails');


function email_obfuscator_enqueue_scripts()
{
    wp_enqueue_script('email-decrypt', plugin_dir_url(__FILE__) . 'js/decrypt.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'email_obfuscator_enqueue_scripts');
