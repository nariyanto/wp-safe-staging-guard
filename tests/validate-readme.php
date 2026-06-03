<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$readme = file_get_contents($root . '/readme.txt');
$plugin = file_get_contents($root . '/safe-staging-guard.php');
$pluginClass = file_get_contents($root . '/src/Plugin.php');
$changelog = file_get_contents($root . '/CHANGELOG.md');

if (false === $readme || false === $plugin || false === $pluginClass || false === $changelog) {
    fwrite(STDERR, "Unable to read plugin metadata files.\n");
    exit(1);
}

$failures = [];

$requiredReadmePatterns = [
    '/^=== Safe Staging Guard ===$/m' => 'readme plugin title',
    '/^Contributors:\s*nariyanto$/m' => 'readme contributors',
    '/^Tags:\s*staging, noindex, email, development, safety$/m' => 'readme tags',
    '/^Requires at least:\s*6\.0$/m' => 'minimum WordPress version',
    '/^Tested up to:\s*7\.0$/m' => 'tested up to version',
    '/^Requires PHP:\s*7\.4$/m' => 'minimum PHP version',
    '/^Stable tag:\s*0\.1\.1$/m' => 'stable tag',
    '/^License:\s*GPLv2 or later$/m' => 'license',
    '/^License URI:\s*https:\/\/www\.gnu\.org\/licenses\/gpl-2\.0\.html$/m' => 'license URI',
    '/== Description ==/' => 'description section',
    '/== Installation ==/' => 'installation section',
    '/== Frequently Asked Questions ==/' => 'FAQ section',
    '/== Screenshots ==/' => 'screenshots section',
    '/== Changelog ==/' => 'changelog section',
    '/= 0\.1\.1 =/' => '0.1.1 changelog entry',
];

foreach ($requiredReadmePatterns as $pattern => $label) {
    if (1 !== preg_match($pattern, $readme)) {
        $failures[] = "Missing or invalid {$label}.";
    }
}

$requiredPluginPatterns = [
    '/Plugin Name:\s*Safe Staging Guard/' => 'plugin name',
    '/Plugin URI:\s*https:\/\/github\.com\/nariyanto\/wp-safe-staging-guard/' => 'plugin URI',
    '/Version:\s*0\.1\.1/' => 'plugin version',
    '/Requires at least:\s*6\.0/' => 'plugin minimum WordPress version',
    '/Requires PHP:\s*7\.4/' => 'plugin minimum PHP version',
    '/Author:\s*Septiyan Nariyanto/' => 'plugin author',
    '/Author URI:\s*https:\/\/nariyanto\.id/' => 'plugin author URI',
    '/License:\s*GPL-2\.0-or-later/' => 'plugin license',
    '/License URI:\s*https:\/\/www\.gnu\.org\/licenses\/gpl-2\.0\.html/' => 'plugin license URI',
    '/Text Domain:\s*safe-staging-guard/' => 'text domain',
    '/Domain Path:\s*\/languages/' => 'domain path',
];

foreach ($requiredPluginPatterns as $pattern => $label) {
    if (1 !== preg_match($pattern, $plugin)) {
        $failures[] = "Missing or invalid {$label}.";
    }
}

if (!str_contains((string)$pluginClass, "public const VERSION = '0.1.1';")) {
    $failures[] = 'Plugin class VERSION constant must match 0.1.1.';
}

if (!str_contains($changelog, '## 0.1.1 - 2026-06-03')) {
    $failures[] = 'CHANGELOG.md must include the 0.1.1 release entry.';
}

if (preg_match('/https?:\/\/(peepso\.nariyanto\.id|api\.nariyanto\.id)/', $readme . $plugin . $changelog)) {
    $failures[] = 'Public metadata should not contain staging/API environment URLs.';
}

if ($failures) {
    foreach ($failures as $failure) {
        fwrite(STDERR, "FAIL: {$failure}\n");
    }
    exit(1);
}

echo "Readme and plugin metadata validation passed.\n";
