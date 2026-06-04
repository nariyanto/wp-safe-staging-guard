=== SNXWorks Safe Staging Guard ===
Contributors: nariyanto
Tags: staging, noindex, email, development, safety
Requires at least: 6.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 0.1.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Prevent staging-site accidents with visible environment labels, noindex controls, and safe email handling.

== Description ==

SNXWorks Safe Staging Guard helps WordPress site owners and developers avoid common staging-site mistakes.

Version 0.1.2 focuses on simple, review-friendly safety controls and WordPress.org prereview remediation:

* Admin bar environment label for administrators.
* Optional frontend staging banner for logged-in users.
* Noindex/nofollow robots control for local and staging environments.
* Email safety mode to block or redirect outgoing staging emails.
* Production mode automatically disables noindex and email interception.

The plugin does not send data to external services and does not collect analytics.

Privacy note: in redirect mode, redirected test emails include an audit note with the original recipients so administrators can confirm what would have been sent. Use a trusted internal test inbox for redirect mode.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`.
2. Activate SNXWorks Safe Staging Guard from the Plugins screen.
3. Open Settings > SNXWorks Safe Staging Guard.
4. Select the environment and safety options.

== Frequently Asked Questions ==

= Does this plugin change production email delivery? =

If Environment is set to Production, SNXWorks Safe Staging Guard does not block or redirect outgoing email.

= Does this plugin send data externally? =

No. Version 0.1.2 does not send data to external services and does not collect analytics.

= Is this a replacement for a proper staging environment? =

No. It is a helper layer for visibility and safety. You should still keep staging and production separate.

= What happens when I deactivate the plugin? =

The visible staging banner, admin bar label, noindex output, and email interception stop when the plugin is deactivated. Settings are preserved so they are available if the plugin is reactivated.

== Screenshots ==

1. SNXWorks Safe Staging Guard settings page after saving staging redirect settings.
2. Frontend view showing the admin bar environment label and staging banner.

== Changelog ==

= 0.1.2 =
* Renamed to SNXWorks Safe Staging Guard with text domain `snxworks-safe-staging-guard` for WordPress.org review.
* Moved frontend banner styling to an enqueued stylesheet.

= 0.1.1 =
* Added WordPress.org submission checklist, metadata validation, release ZIP validation, release workflow, and repository/Packagist polish.

= 0.1.0 =
* Initial release with environment label, staging banner, noindex control, and email safety mode.
