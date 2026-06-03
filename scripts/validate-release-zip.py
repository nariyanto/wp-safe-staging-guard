#!/usr/bin/env python3
"""Validate Safe Staging Guard release ZIP contents."""
from __future__ import annotations

import sys
import zipfile
from pathlib import Path

zip_path = Path(sys.argv[1] if len(sys.argv) > 1 else 'dist/safe-staging-guard.zip')
if not zip_path.exists():
    raise SystemExit(f'ZIP not found: {zip_path}')

required = {
    'safe-staging-guard/safe-staging-guard.php',
    'safe-staging-guard/readme.txt',
    'safe-staging-guard/README.md',
    'safe-staging-guard/CHANGELOG.md',
    'safe-staging-guard/composer.json',
    'safe-staging-guard/languages/safe-staging-guard.pot',
    'safe-staging-guard/src/Plugin.php',
    'safe-staging-guard/src/EnvironmentSettings.php',
    'safe-staging-guard/src/EmailSafety.php',
    'safe-staging-guard/src/NoindexPolicy.php',
}

forbidden_prefixes = (
    'safe-staging-guard/.git/',
    'safe-staging-guard/.github/',
    'safe-staging-guard/tests/',
    'safe-staging-guard/docs/',
    'safe-staging-guard/scripts/',
    'safe-staging-guard/dist/',
    'safe-staging-guard/.wordpress-org/',
    'safe-staging-guard/vendor/',
)

forbidden_files = {
    'safe-staging-guard/.gitignore',
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
