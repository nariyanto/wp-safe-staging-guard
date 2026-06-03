# Worklog Notes: Safe Staging Guard v0.1.0

## Summary

Safe Staging Guard is the second WordPress plugin in the Nariyanto GitHub portfolio roadmap. It focuses on staging-site safety: visible environment labels, noindex controls, and safe email handling.

## Milestone

- Created public GitHub repository.
- Built v0.1.0 with TDD-friendly pure PHP services.
- Deployed to `peepso.nariyanto.id` staging.
- Configured staging mode with noindex and email blocking.
- Ran Plugin Check and fixed i18n issues until no errors remained.

## Technical decisions

- Keep v0.1.0 non-destructive and review-friendly.
- Use `safe-staging-guard` as the WordPress.org-facing slug to avoid restricted `WP` wording.
- Make Production mode automatically disable noindex and email interception.
- Keep email blocking local to WordPress mail flow and avoid external calls.
- Use pure service classes for testable logic before WordPress admin glue.

## Verification

- CI passed.
- Local PHP tests passed.
- PHP syntax checks passed.
- Plugin Check: no errors found.
- PeepSo staging returned HTTP 200.
- Public HTML contained `noindex, nofollow` robots meta.
- Email interception returned active in staging mode.
