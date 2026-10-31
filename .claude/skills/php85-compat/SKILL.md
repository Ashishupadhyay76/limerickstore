---
name: php85-compat
description: Checks PHP code in the LimerickStore WordPress/WooCommerce site for compatibility with PHP 8.5 (removed or deprecated functions, behaviour changes). Use before and during the PHP upgrade, when reviewing our own theme and plugin code, or when a plugin update is being evaluated.
---

# PHP 8.5 compatibility check

Scope: our own code first (`wp-content/themes/north-wp`, `wp-content/plugins/north-plugin`), then third-party plugins only to report, never to edit.

Steps:
1. If `php` and `composer` are available, run PHPCompatibility with `phpcs --standard=PHPCompatibilityWP --runtime-set testVersion 8.5- <path>` on the target folder. If they are not installed, say so and fall back to a manual search.
2. Search for removed or deprecated patterns: `each()`, `create_function`, `${var}` string interpolation, passing null to non-nullable built-in parameters, dynamic properties on classes without `#[AllowDynamicProperties]`, implicit float to int conversions, and anything listed in the official PHP 8.1 to 8.5 migration guides.
3. Group findings by plugin or theme, then by severity: Fatal (will break the site), Warning (deprecated, will log notices), Info.
4. For our own code, propose fixes as a small diff. For third-party plugins, report the plugin name, installed version, and whether a PHP 8.5-compatible release exists. Do not edit third-party code.

Output: a table of findings (file, line, issue, severity), then a short list of plugins that need updating before the upgrade.
