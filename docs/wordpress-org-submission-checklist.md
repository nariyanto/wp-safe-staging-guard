# Safe Staging Guard WordPress.org Submission Checklist

This checklist prepares **Safe Staging Guard** for WordPress.org plugin directory submission. It is intentionally separate from the GitHub release checklist because WordPress.org review has extra requirements around plugin identity, assets, security, privacy, and final ZIP/SVN submission.

## Current status snapshot

- Repository: <https://github.com/nariyanto/wp-safe-staging-guard>
- Public plugin name: `Safe Staging Guard`
- Plugin slug/text domain: `safe-staging-guard`
- Main file: `safe-staging-guard.php`
- Current version/stable tag: `0.1.0`
- License: `GPL-2.0-or-later`
- Requires WordPress: `6.0+`
- Requires PHP: `7.4+`
- Current package status: GitHub release exists; Packagist exists but only exposes `dev-main` until the next tag includes `composer.json`.

## Submission recommendation

Do **not** submit immediately until the pending items below are complete.

Recommended submission path:

1. Finish WordPress.org assets and screenshot evidence.
2. Add automated readme/header metadata validation, similar to Cron Inspector Lite.
3. Run Plugin Check against the final release ZIP.
4. Create a patch release, likely `v0.1.1`, so GitHub, Packagist, and WordPress.org submission metadata all include current Composer/badge/package metadata.
5. Submit to WordPress.org only after explicit approval from Septiyan.

## 1. Plugin identity and metadata

- [x] Main plugin header uses public name `Safe Staging Guard`.
- [x] `Plugin URI` points to the GitHub repository.
- [x] Description matches the current feature scope.
- [x] Version is present in `safe-staging-guard.php`.
- [x] `Requires at least` is present.
- [x] `Requires PHP` is present.
- [x] Author and Author URI are present.
- [x] License and License URI are present.
- [x] Text domain is `safe-staging-guard`.
- [x] Domain path is `/languages`.
- [x] `readme.txt` public plugin name matches the main header.
- [x] `readme.txt` contributors field is `nariyanto`.
- [x] `readme.txt` stable tag matches plugin version: `0.1.0`.
- [x] Tags are relevant and not spammy: `staging`, `noindex`, `email`, `development`, `safety`.
- [ ] Add `tests/validate-readme.php` to automatically check the main header and `readme.txt` metadata before submission.

## 2. Security and code review

- [x] Direct file access is guarded with `defined('ABSPATH')` checks.
- [x] Plugin uses a namespaced PHP codebase under `Nariyanto\SafeStagingGuard`.
- [x] Plugin scope is non-destructive.
- [x] Production mode does not intercept email or force noindex behavior.
- [x] No telemetry or external network calls are documented.
- [x] No credentials, tokens, or private support data are included.
- [ ] Re-check every admin form/action for capability checks.
- [ ] Re-check nonce usage for any state-changing settings save flow.
- [ ] Re-check sanitization of every saved option.
- [ ] Re-check escaping for all admin/frontend output.
- [ ] Search for debug leftovers, hardcoded staging URLs, TODOs, and private environment references.
- [ ] Run WordPress Plugin Check on the final submission ZIP and save the result in `docs/` or the release notes.

## 3. Privacy, disclosure, and policy fit

- [x] README/readme state that the plugin does not send data externally.
- [x] Plugin purpose is clear: staging visibility, noindex controls, and email safety.
- [x] The plugin is not positioned as a complete staging replacement.
- [ ] Add a short privacy note to `readme.txt` if needed for WordPress.org review clarity.
- [ ] Confirm email redirect/block behavior cannot accidentally disclose recipient addresses in the UI or logs.
- [ ] Confirm production mode is the safest default if the plugin is installed on a live site.

## 4. WordPress.org assets

Required/strongly recommended assets before submission:

- [ ] `assets/banner-1544x500.png`
- [ ] `assets/banner-772x250.png`
- [ ] `assets/icon-256x256.png`
- [ ] `assets/icon-128x128.png`
- [ ] Screenshot 1: Safe Staging Guard settings page.
- [ ] Screenshot 2: Admin bar environment label.
- [ ] Screenshot 3: Frontend staging banner.
- [ ] Screenshot 4, optional: page source/robots meta evidence for `noindex, nofollow` on staging.
- [ ] Ensure screenshot captions in `readme.txt` match the final screenshot filenames/order.

Current asset note: `.wordpress-org/README.md` exists as an asset plan, but actual WordPress.org assets are not present yet.

## 5. Testing and release package verification

Run locally before final submission:

```bash
php tests/run.php
find . -path ./vendor -prune -o -path ./dist -prune -o -name '*.php' -print0 | xargs -0 -n1 php -l
php -r 'json_decode(file_get_contents("composer.json")); if (json_last_error()) { fwrite(STDERR, json_last_error_msg().PHP_EOL); exit(1); } echo "composer.json valid JSON\n";'
bash scripts/build-release.sh
```

Final submission checks:

- [x] Pure PHP tests exist.
- [x] Deterministic ZIP builder exists.
- [x] Current ZIP excludes development-only paths such as `.git`, `.github`, `tests`, `docs`, `scripts`, and `dist`.
- [ ] Add/port metadata validation script from Cron Inspector Lite.
- [ ] Validate ZIP contents programmatically after build.
- [ ] Install final ZIP on a clean WordPress test site.
- [ ] Activate plugin with `WP_DEBUG` enabled and confirm no PHP warnings/notices.
- [ ] Verify settings save flow.
- [ ] Verify staging mode adds noindex/nofollow.
- [ ] Verify production mode does not add noindex/nofollow.
- [ ] Verify email block mode.
- [ ] Verify email redirect mode with a safe test recipient.
- [ ] Verify uninstall/deactivation behavior is acceptable and documented.

## 6. GitHub, Packagist, and release alignment

- [x] GitHub repo exists.
- [x] GitHub CI passes on `main`.
- [x] GitHub release `v0.1.0` exists.
- [x] Composer metadata exists.
- [x] Packagist package exists.
- [ ] Create `v0.1.1` after final submission-prep docs/scripts/assets are complete, because `v0.1.0` was tagged before `composer.json` existed.
- [ ] After `v0.1.1`, update Packagist and confirm the package exposes a stable version.
- [ ] If stable Packagist version exists, switch README badge from downloads to version.
- [ ] Confirm GitHub release ZIP and WordPress.org submission ZIP are aligned.

## 7. WordPress.org submission form prep

Prepare these values before opening the submission form:

- Plugin name: `Safe Staging Guard`
- Plugin slug preference: `safe-staging-guard`
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
- [ ] Creating a new public release tag, e.g. `v0.1.1`.
- [ ] Uploading assets or release ZIPs to external services beyond GitHub/Packagist already approved.
- [ ] Any production/staging server changes beyond verification-only checks.

## Suggested next work item

Before WordPress.org submission, the highest-value next patch is:

1. Add `tests/validate-readme.php`.
2. Add WordPress.org asset placeholders or final assets.
3. Re-run Plugin Check on the final ZIP.
4. Cut `v0.1.1` and update Packagist.
