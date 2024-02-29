# Email Obfuscator

A Simple and Lightweight Email Obfuscator Plugin

This was created for a client as a part of a solution to get rid of bulky plugins in their wordpress website. The orginial plugin added a ton of JS files and CSS files when this could all be handled with a simple PHP function and a couple of lines of JS. No more SPAM by spiders scanning you site for email adresses. With this plugin you can hide all your email adresses, with a mailto-link, by converting them using javascript.

What It Does:

- This plugin simply replaces the mailto: link for any email links on your website with a long string so that spiders can't crawl your site for emails to send spam to.

Two Ways To Use:

ONE - the manual way:

- Place the code from the functions.php file in the "manual_method" directory in your theme (or child theme) functions.php file
- Copy the code from the decrypt.js file in the "manual_method" directory to the footer or head of your website

TWO - as a plugin:

- download the repository as a zip file and upload as a plugin in wordpress
