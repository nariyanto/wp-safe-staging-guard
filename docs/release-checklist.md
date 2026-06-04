# Nariyanto Safe Staging Guard Release Checklist

## v0.1.0

- [x] Keep first release narrow and non-destructive.
- [x] Add pure PHP tests for business logic.
- [x] Run local PHP tests.
- [x] Run PHP syntax checks.
- [x] Build clean release ZIP.
- [x] Deploy ZIP to PeepSo staging.
- [x] Activate and configure staging mode.
- [x] Verify HTTP 200 site health.
- [x] Verify `noindex, nofollow` robots meta on staging.
- [x] Verify email interception is active in staging mode.
- [x] Run Plugin Check on staging.
- [x] Fix Plugin Check i18n findings.

## WordPress.org later

See [`docs/wordpress-org-submission-checklist.md`](wordpress-org-submission-checklist.md) for the full submission checklist.

- Add real screenshots from WP Admin.
- Add banner and icon assets.
- Add automated `readme.txt` / plugin-header metadata validation.
- Re-run Plugin Check against final ZIP.
- Create a patch release after submission-prep metadata/assets are complete.
- Submit only after Cron Inspector Lite review flow is stable and Septiyan explicitly approves WordPress.org submission.
