=== Safe Staging Guard ===
Contributors: nariyanto
Tags: staging, noindex, email, development, safety
Requires at least: 6.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Prevent staging-site accidents with visible environment labels, noindex controls, and safe email handling.

== Description ==

Safe Staging Guard helps WordPress site owners and developers avoid common staging-site mistakes.

Version 0.1.0 focuses on simple, review-friendly safety controls:

* Admin bar environment label for administrators.
* Optional frontend staging banner for logged-in users.
* Noindex/nofollow robots control for local and staging environments.
* Email safety mode to block or redirect outgoing staging emails.
* Production mode automatically disables noindex and email interception.

The plugin does not send data to external services and does not collect analytics.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`.
2. Activate Safe Staging Guard from the Plugins screen.
3. Open Settings > Safe Staging Guard.
4. Select the environment and safety options.

== Frequently Asked Questions ==

= Does this plugin change production email delivery? =

If Environment is set to Production, Safe Staging Guard does not block or redirect outgoing email.

= Does this plugin send data externally? =

No. Version 0.1.0 does not send data to external services and does not collect analytics.

= Is this a replacement for a proper staging environment? =

No. It is a helper layer for visibility and safety. You should still keep staging and production separate.

== Screenshots ==

1. Safe Staging Guard settings page.
2. Admin bar environment label.
3. Frontend staging banner.

== Changelog ==

= 0.1.0 =
* Initial release with environment label, staging banner, noindex control, and email safety mode.
