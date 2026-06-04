# Changelog

## 0.1.2 - 2026-06-04

WordPress.org prereview remediation release.

### Changed

- Renamed public plugin identity to Nariyanto Safe Staging Guard.
- Updated plugin slug, text domain, main file, release ZIP slug, language template, tests, and documentation to nariyanto-safe-staging-guard.
- Moved frontend banner styling from inline HTML to an enqueued stylesheet.

## 0.1.1 - 2026-06-03

Submission-prep and repository polish release.

### Added

- WordPress.org submission checklist.
- Automated readme/plugin-header metadata validation.
- Release ZIP contents validation.
- GitHub Release workflow for tagged plugin ZIP assets.
- Composer metadata and Packagist publication support.

### Changed

- Added repository badges and Packagist downloads badge.
- Documented redirect-mode privacy behavior for staging email tests.

## 0.1.0 - 2026-06-03

Initial portfolio/staging release.

### Added

- Environment mode: Local, Staging, Production.
- Admin bar environment label for administrators.
- Frontend staging banner for logged-in users.
- Noindex/nofollow robots control for local and staging environments.
- Email safety modes: block, redirect, and allow.
- Production mode guard that disables noindex and email interception.
- Settings page under Settings > Nariyanto Safe Staging Guard.
- Pure PHP tests for settings, email safety, and noindex behavior.
- GitHub Actions CI and clean release ZIP builder.
