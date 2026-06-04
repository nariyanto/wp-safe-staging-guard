# SNXWorks Safe Staging Guard

[![CI](https://github.com/nariyanto/wp-safe-staging-guard/actions/workflows/ci.yml/badge.svg)](https://github.com/nariyanto/wp-safe-staging-guard/actions/workflows/ci.yml)
[![Latest Release](https://img.shields.io/github/v/release/nariyanto/wp-safe-staging-guard?label=release)](https://github.com/nariyanto/wp-safe-staging-guard/releases)
[![Packagist Version](https://img.shields.io/packagist/v/nariyanto/wp-safe-staging-guard?label=packagist)](https://packagist.org/packages/nariyanto/wp-safe-staging-guard)
[![PHP](https://img.shields.io/badge/PHP-%3E%3D%207.4-777bb4.svg)](https://www.php.net/)
[![WordPress](https://img.shields.io/badge/WordPress-%3E%3D%206.0-21759b.svg)](https://wordpress.org/)
[![License](https://img.shields.io/badge/license-GPL--2.0--or--later-blue.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

Prevent staging-site accidents with visible environment labels, noindex controls, and safe email handling.

## Why

Staging sites are useful, but they can accidentally:

- get indexed by search engines,
- send real emails to users,
- look too similar to production,
- confuse support and testing workflows.

SNXWorks Safe Staging Guard adds a small safety layer for WordPress staging/local environments.

## v0.1.0 features

- Admin bar environment label.
- Frontend staging banner for logged-in users.
- Noindex/nofollow for local/staging environments.
- Email block or redirect mode.
- Production mode disables email interception and noindex.

## Staging verification

Installed and verified on `peepso.nariyanto.id` staging with:

- staging environment mode enabled,
- `noindex, nofollow` robots meta active,
- email safety mode set to block,
- Plugin Check completed with no errors.

## Development

Run tests:

```bash
php tests/run.php
```

Run PHP syntax checks:

```bash
find . -path ./.git -prune -o -name '*.php' -print0 | xargs -0 -n1 php -l
```

Build release ZIP:

```bash
bash scripts/build-release.sh
```

## Safety notes

- No tracking by default.
- No external network calls.
- No destructive operations.
- Production mode keeps emails and robots behavior untouched.

## License

GPL-2.0-or-later.
