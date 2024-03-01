# Simple and Lightweight Email Obfuscator Plugin

Protect your WordPress site from spam by obfuscating email addresses with this simple and lightweight plugin. Designed to prevent spam bots from scanning your site and harvesting email addresses, this plugin offers an efficient solution without the overhead of additional JS or CSS files.

## Description

This Email Obfuscator Plugin provides a straightforward way to secure mailto links and visible email addresses on your WordPress website. Originally developed as a custom solution for a client looking to reduce reliance on bulky plugins, it has evolved into a versatile plugin that enhances email privacy with minimal overhead.

### Features

- **Obfuscate mailto Links**: Automatically replaces mailto links with JavaScript-based obfuscation to prevent spam bots from detecting and collecting email addresses.
- **Transform Visible Emails**: With a single setting, convert any visible email text into obfuscated mailto links, extending protection beyond standard mailto links.
- **Lightweight Implementation**: Unlike other solutions that load multiple JavaScript and CSS files, this plugin achieves its functionality with a simple PHP function and a few lines of JavaScript, ensuring your site's performance is not impacted.

## Installation

### Manual Method

1. **Functions.php**: Copy the code from the `functions.php` file in the "manual_method" directory into your theme's (or child theme's) `functions.php` file.
2. **JavaScript**: Add the code from the `decrypt.js` file in the "manual_method" directory before your closing </body> tag or in the <head> of your website.

### Plugin Method

1. **Download**: Download the repository as a ZIP file.
2. **Upload in WordPress**: Navigate to the WordPress admin area, go to Plugins > Add New > Upload Plugin, and select the downloaded ZIP file.
3. **Activate**: Once uploaded, activate the plugin through the 'Plugins' menu in WordPress.

## Usage

After installation, the plugin works out of the box by obfuscating all mailto links found on your website. To disable the conversion of visible email addresses into mailto links:

1. Go to the plugin settings page in the WordPress admin area.
2. Uncheck the option to transform non-mailto visible emails.
3. Save your changes.

## Coming Soon

We're continually working to improve the Email Obfuscator Plugin. Stay tuned for new features and enhancements designed to provide even greater protection against email harvesting.

## License

This plugin is licensed under GPL2, allowing you to use and modify it freely as long as you maintain the same licensing for any derivative works.

## Contributing

We welcome contributions from the community! Here are some ways you can help:

- Reporting bugs
- Suggesting enhancements
- Writing or improving documentation
- Submitting pull requests to resolve open issues

If you're interested in contributing, please read our [CONTRIBUTING.md](CONTRIBUTING.md) file for more information on how to get started.
