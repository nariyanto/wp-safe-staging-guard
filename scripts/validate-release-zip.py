#!/usr/bin/env python3
"""Validate SNXWorks Safe Staging Guard release ZIP contents."""
from __future__ import annotations

import sys
import zipfile
from pathlib import Path

zip_path = Path(sys.argv[1] if len(sys.argv) > 1 else 'dist/snxworks-safe-staging-guard.zip')
if not zip_path.exists():
    raise SystemExit(f'ZIP not found: {zip_path}')

required = {
    'snxworks-safe-staging-guard/snxworks-safe-staging-guard.php',
    'snxworks-safe-staging-guard/readme.txt',
    'snxworks-safe-staging-guard/CHANGELOG.md',
    'snxworks-safe-staging-guard/assets/frontend.css',
    'snxworks-safe-staging-guard/languages/snxworks-safe-staging-guard.pot',
    'snxworks-safe-staging-guard/src/Plugin.php',
    'snxworks-safe-staging-guard/src/EnvironmentSettings.php',
    'snxworks-safe-staging-guard/src/EmailSafety.php',
    'snxworks-safe-staging-guard/src/NoindexPolicy.php',
}

forbidden_prefixes = (
    'snxworks-safe-staging-guard/.git/',
    'snxworks-safe-staging-guard/.github/',
    'snxworks-safe-staging-guard/tests/',
    'snxworks-safe-staging-guard/docs/',
    'snxworks-safe-staging-guard/scripts/',
    'snxworks-safe-staging-guard/dist/',
    'snxworks-safe-staging-guard/.wordpress-org/',
    'snxworks-safe-staging-guard/vendor/',
)

forbidden_files = {
    'snxworks-safe-staging-guard/.gitignore',
    'snxworks-safe-staging-guard/README.md',
    'snxworks-safe-staging-guard/composer.json',
}

with zipfile.ZipFile(zip_path) as archive:
    names = set(archive.namelist())

missing = sorted(required - names)
forbidden = sorted(
    name for name in names
    if name in forbidden_files or any(name.startswith(prefix) for prefix in forbidden_prefixes)
)

if missing or forbidden:
    if missing:
        print('Missing required files:')
        for name in missing:
            print(f'- {name}')
    if forbidden:
        print('Forbidden development files in ZIP:')
        for name in forbidden:
            print(f'- {name}')
    raise SystemExit(1)

print(f'ZIP validation passed: {zip_path}')
