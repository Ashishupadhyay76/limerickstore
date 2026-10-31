# LimerickStore (WooCommerce on WordPress)

Production e-commerce site at https://www.limerickstore.com. The repo holds the full WordPress install (core, plugins, theme). A PHP 8.5 upgrade and a move to AWS are planned.

## Where our own code lives
- Theme: `wp-content/themes/north-wp`. Make theme changes in a child theme, not in the parent.
- Custom plugin: `wp-content/plugins/north-plugin`
- Everything else under `wp-content/plugins/` and `wp-admin/`, `wp-includes/` is third-party or core. Don't edit it directly. Propose a hook, filter or override in our own code instead.

## Never do
- Never read, create or commit `wp-config.php`, `.env`, SQL dumps or `wp-content/uploads`. Use `wp-config-sample.php` for structure.
- Never put passwords, API keys or salts in any file.
- Never push to `master` directly. Work on a branch named `feature/...` or `fix/...` and open a pull request.
- Never run `git push --force`.

## Stack and constraints
- WordPress + WooCommerce, MySQL on the same host, SSL connection flags in the config.
- Target: PHP 8.5. New code must be compatible (no deprecated functions, typed properties, null-safe handling).
- Payments: PayPal Payments, Razorpay, PayGlocal. Treat checkout and payment code as high risk. Explain changes before making them.
- Premium plugins (revslider, js_composer, ubermenu, woocommerce-pdf-invoice) are updated by replacing the folder from the vendor zip, not by editing.

## Conventions
- Follow WordPress Coding Standards. Prefix functions and hooks with `north_`.
- Escape output (`esc_html`, `esc_attr`, `esc_url`), sanitize input, use nonces and capability checks.
- Use WooCommerce hooks and template overrides instead of editing plugin files.
- Keep each change small and explain what and why in the commit message.
