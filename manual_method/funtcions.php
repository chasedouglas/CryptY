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
    // Ensure only the JavaScript call is being used, without additional HTML nesting
    return '<a href="javascript:' . htmlspecialchars($javascript_code) . '">' . htmlspecialchars($email) . '</a>';
}

function obfuscate_emails($content)
{
    // Create a new DOMDocument and load the content
    $dom = new DOMDocument();
    @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $links = $dom->getElementsByTagName('a');

    foreach ($links as $link) {
        // Check if the link is a mailto link
        $href = $link->getAttribute('href');
        if (strpos($href, 'mailto:') === 0) {
            $email = substr($href, 7); // Remove the 'mailto:' part
            $encryptedEmail = encrypt_email($email);
            $decryptedLink = generate_decrypt_script($encryptedEmail);

            // Set the new href to call the JavaScript decrypt function
            $link->setAttribute('href', 'javascript:' . $decryptedLink);
            // Optionally, set the display text to the encrypted email or leave it as is
            // $link->nodeValue = $encryptedEmail;
        }
    }

    // Save the modified content
    return $dom->saveHTML();
}
add_filter('the_content', 'obfuscate_emails');
