# SNXWorks Safe Staging Guard WordPress.org Submission Checklist

This checklist prepares **SNXWorks Safe Staging Guard** for WordPress.org plugin directory submission. It is intentionally separate from the GitHub release checklist because WordPress.org review has extra requirements around plugin identity, assets, security, privacy, and final ZIP/SVN submission.

## Current status snapshot

- Repository: <https://github.com/nariyanto/wp-safe-staging-guard>
- Public plugin name: `SNXWorks Safe Staging Guard`
- Plugin slug/text domain: `snxworks-safe-staging-guard`
- Main file: `snxworks-safe-staging-guard.php`
- Current version/stable tag: `0.1.2`
- License: `GPL-2.0-or-later`
- Requires WordPress: `6.0+`
- Requires PHP: `7.4+`
- Current package status: `0.1.2` candidate ZIP builds locally as `dist/snxworks-safe-staging-guard.zip`; GitHub release/tag and Packagist update are pending final approval.

## Submission recommendation

The technical submission checklist is complete. Do **not** submit to WordPress.org until Septiyan gives explicit final approval.

Recommended submission path:

1. Submit to WordPress.org only after explicit approval from Septiyan.

## 1. Plugin identity and metadata

- [x] Main plugin header uses public name `SNXWorks Safe Staging Guard`.
- [x] `Plugin URI` points to `https://nariyanto.id`; GitHub remains documented outside the WordPress.org ZIP.
- [x] Description matches the current feature scope.
- [x] Version is present in `snxworks-safe-staging-guard.php`.
- [x] `Requires at least` is present.
- [x] `Requires PHP` is present.
- [x] Author and Author URI are present.
- [x] License and License URI are present.
- [x] Text domain is `snxworks-safe-staging-guard`.
- [x] Domain path is `/languages`.
- [x] `readme.txt` public plugin name matches the main header.
- [x] `readme.txt` contributors field is `nariyanto`.
- [x] `readme.txt` stable tag matches plugin version: `0.1.2`.
- [x] Tags are relevant and not spammy: `staging`, `noindex`, `email`, `development`, `safety`.
- [x] Add `tests/validate-readme.php` to automatically check the main header and `readme.txt` metadata before submission.

## 2. Security and code review

- [x] Direct file access is guarded with `defined('ABSPATH')` checks.
- [x] Plugin uses a namespaced PHP codebase under `SNXWorks\SafeStagingGuard`.
- [x] Plugin scope is non-destructive.
- [x] Production mode does not intercept email or force noindex behavior.
- [x] No telemetry or external network calls are documented.
- [x] No credentials, tokens, or private support data are included.
- [x] Re-check every admin form/action for capability checks.
- [x] Re-check nonce usage for any state-changing settings save flow.
- [x] Re-check sanitization of every saved option.
- [x] Re-check escaping for all admin/frontend output.
- [x] Search for debug leftovers, hardcoded staging URLs, TODOs, and private environment references.
- [x] Run WordPress Plugin Check on the final submission ZIP/staging install and save the result in this checklist. Result: `Success: Checks complete. No errors found.` on a disposable local WordPress 6.8.3 install with Plugin Check 2.0.0 for `snxworks-safe-staging-guard` 0.1.2.

## 3. Privacy, disclosure, and policy fit

- [x] README/readme state that the plugin does not send data externally.
- [x] Plugin purpose is clear: staging visibility, noindex controls, and email safety.
- [x] The plugin is not positioned as a complete staging replacement.
- [x] Add a short privacy note to `readme.txt` if needed for WordPress.org review clarity.
- [x] Confirm email redirect/block behavior disclosure is documented: redirect mode includes original recipients only in the redirected test email audit note.
- [x] Confirm production mode disables noindex and email interception when selected.

## 4. WordPress.org assets

Required/strongly recommended assets before submission:

- [x] `.wordpress-org/assets/banner-1544x500.png`
- [x] `.wordpress-org/assets/banner-772x250.png`
- [x] `.wordpress-org/assets/icon-256x256.png`
- [x] `.wordpress-org/assets/icon-128x128.png`
- [x] Screenshot 1: SNXWorks Safe Staging Guard settings page after saving staging redirect settings.
- [x] Screenshot 2: Frontend view showing the admin bar environment label and staging banner.
- [x] Admin bar environment label evidence is included in Screenshot 2.
- [x] Frontend staging banner evidence is included in Screenshot 2.
- [x] Page source/robots meta evidence for `noindex, nofollow` on staging was verified in clean/local and PeepSo staging checks.
- [x] Ensure screenshot captions in `readme.txt` match the final screenshot filenames/order.

Current asset note: branded banner/icon assets and screenshots exist under `.wordpress-org/assets/`.

## 5. Testing and release package verification

Run locally before final submission:

```bash
php tests/run.php
php tests/validate-readme.php
find . -path ./vendor -prune -o -path ./dist -prune -o -name '*.php' -print0 | xargs -0 -n1 php -l
php -r 'json_decode(file_get_contents("composer.json")); if (json_last_error()) { fwrite(STDERR, json_last_error_msg().PHP_EOL); exit(1); } echo "composer.json valid JSON\n";'
bash scripts/build-release.sh
python3 scripts/validate-release-zip.py
```

Final submission checks:

- [x] Pure PHP tests exist.
- [x] Deterministic ZIP builder exists.
- [x] Current ZIP excludes development-only paths such as `.git`, `.github`, `tests`, `docs`, `scripts`, and `dist`.
- [x] Add/port metadata validation script from Cron Inspector Lite.
- [x] Validate ZIP contents programmatically after build.
- [ ] Optional: install the renamed final ZIP on PeepSo staging after backup if we want fresh staging evidence for the new public identity.
- [x] Install final ZIP on a clean local WordPress test site with `WP_DEBUG` enabled.
- [x] Activate plugin with `WP_DEBUG` enabled and confirm no PHP warnings/notices on a clean WordPress install (`no-debug-log`).
- [x] Verify settings save flow manually in WP Admin after screenshot capture (`Settings saved.` notice and persisted `redirect_email=staging@example.com`).
- [x] Verify staging mode adds noindex/nofollow on PeepSo staging (`https://peepso.nariyanto.id/` returned HTTP 200 and robots meta included noindex/nofollow).
- [x] Verify production mode does not add noindex/nofollow on a clean test install.
- [x] Verify email block mode is configured on PeepSo staging (`email_mode=block`).
- [x] Verify email redirect mode with a safe test recipient; outgoing mail args were redirected to `staging@example.com` and included the original-recipient audit note.
- [x] Verify uninstall/deactivation behavior is acceptable and documented: deactivation removes visible/interception behavior and preserves settings for reactivation.

## 6. GitHub, Packagist, and release alignment

- [x] GitHub repo exists.
- [x] GitHub CI passes on `main`.
- [x] GitHub release `v0.1.0` and `v0.1.1` exist.
- [x] Composer metadata exists.
- [x] Packagist package exists.
- [ ] Create `v0.1.2` after final remediation is approved.
- [ ] After `v0.1.2`, update Packagist and confirm the package exposes a stable version.
- [x] README badge already points to the existing Packagist package.
- [x] Confirm local WordPress.org submission ZIP content is aligned with the remediated source.

## 7. WordPress.org submission form prep

Prepare these values before opening the submission form:

- Plugin name: `SNXWorks Safe Staging Guard`
- Plugin slug preference: `snxworks-safe-staging-guard`
- Plugin URL: <https://github.com/nariyanto/wp-safe-staging-guard>
- Short description: `Prevent staging-site accidents with visible environment labels, noindex controls, and safe email handling.`
- Long description: use `readme.txt` Description section.
- Tags: `staging`, `noindex`, `email`, `development`, `safety`
- Requires at least: `6.0`
- Tested up to: current value in `readme.txt`
- Requires PHP: `7.4`
- License: `GPLv2 or later`
- Support/development reference: GitHub repository and Nariyanto Worklog case study.

## 8. Approval gates

These steps need explicit approval before execution:

- [ ] Submitting the plugin to WordPress.org.
- [ ] Creating a new public release tag, e.g. `v0.1.2`.
- [ ] Uploading assets or release ZIPs to external services beyond GitHub.
- [ ] Any production/staging server changes beyond local verification-only checks.

## Suggested next work item

Before WordPress.org submission, the remaining decision is final approval to submit the prepared plugin package and assets to WordPress.org.
