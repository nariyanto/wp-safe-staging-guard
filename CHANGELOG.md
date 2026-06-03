# Changelog

## 0.1.0 - 2026-06-03

Initial portfolio/staging release.

### Added

- Environment mode: Local, Staging, Production.
- Admin bar environment label for administrators.
- Frontend staging banner for logged-in users.
- Noindex/nofollow robots control for local and staging environments.
- Email safety modes: block, redirect, and allow.
- Production mode guard that disables noindex and email interception.
- Settings page under Settings > Safe Staging Guard.
- Pure PHP tests for settings, email safety, and noindex behavior.
- GitHub Actions CI and clean release ZIP builder.
