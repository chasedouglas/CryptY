<?php

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
