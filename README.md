# Safe Staging Guard

Prevent staging-site accidents with visible environment labels, noindex controls, and safe email handling.

## Why

Staging sites are useful, but they can accidentally:

- get indexed by search engines,
- send real emails to users,
- look too similar to production,
- confuse support and testing workflows.

Safe Staging Guard adds a small safety layer for WordPress staging/local environments.

## v0.1.0 features

- Admin bar environment label.
- Frontend staging banner for logged-in users.
- Noindex/nofollow for local/staging environments.
- Email block or redirect mode.
- Production mode disables email interception and noindex.

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
