#!/usr/bin/env python3
"""Validate Nariyanto Safe Staging Guard release ZIP contents."""
from __future__ import annotations

import sys
import zipfile
from pathlib import Path

zip_path = Path(sys.argv[1] if len(sys.argv) > 1 else 'dist/nariyanto-safe-staging-guard.zip')
if not zip_path.exists():
    raise SystemExit(f'ZIP not found: {zip_path}')

required = {
    'nariyanto-safe-staging-guard/nariyanto-safe-staging-guard.php',
    'nariyanto-safe-staging-guard/readme.txt',
    'nariyanto-safe-staging-guard/CHANGELOG.md',
    'nariyanto-safe-staging-guard/assets/frontend.css',
    'nariyanto-safe-staging-guard/languages/nariyanto-safe-staging-guard.pot',
    'nariyanto-safe-staging-guard/src/Plugin.php',
    'nariyanto-safe-staging-guard/src/EnvironmentSettings.php',
    'nariyanto-safe-staging-guard/src/EmailSafety.php',
    'nariyanto-safe-staging-guard/src/NoindexPolicy.php',
}

forbidden_prefixes = (
    'nariyanto-safe-staging-guard/.git/',
    'nariyanto-safe-staging-guard/.github/',
    'nariyanto-safe-staging-guard/tests/',
    'nariyanto-safe-staging-guard/docs/',
    'nariyanto-safe-staging-guard/scripts/',
    'nariyanto-safe-staging-guard/dist/',
    'nariyanto-safe-staging-guard/.wordpress-org/',
    'nariyanto-safe-staging-guard/vendor/',
)

forbidden_files = {
    'nariyanto-safe-staging-guard/.gitignore',
    'nariyanto-safe-staging-guard/README.md',
    'nariyanto-safe-staging-guard/composer.json',
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
