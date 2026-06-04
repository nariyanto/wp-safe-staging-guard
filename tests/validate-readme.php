<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$readme = file_get_contents($root . '/readme.txt');
$plugin = file_get_contents($root . '/nariyanto-safe-staging-guard.php');
$pluginClass = file_get_contents($root . '/src/Plugin.php');
$changelog = file_get_contents($root . '/CHANGELOG.md');

if (false === $readme || false === $plugin || false === $pluginClass || false === $changelog) {
    fwrite(STDERR, "Unable to read plugin metadata files.\n");
    exit(1);
}

$failures = [];

$requiredReadmePatterns = [
    '/^=== Nariyanto Safe Staging Guard ===$/m' => 'readme plugin title',
    '/^Contributors:\s*nariyanto$/m' => 'readme contributors',
    '/^Tags:\s*staging, noindex, email, development, safety$/m' => 'readme tags',
    '/^Requires at least:\s*6\.0$/m' => 'minimum WordPress version',
    '/^Tested up to:\s*7\.0$/m' => 'tested up to version',
    '/^Requires PHP:\s*7\.4$/m' => 'minimum PHP version',
    '/^Stable tag:\s*0\.1\.2$/m' => 'stable tag',
    '/^License:\s*GPLv2 or later$/m' => 'license',
    '/^License URI:\s*https:\/\/www\.gnu\.org\/licenses\/gpl-2\.0\.html$/m' => 'license URI',
    '/== Description ==/' => 'description section',
    '/== Installation ==/' => 'installation section',
    '/== Frequently Asked Questions ==/' => 'FAQ section',
    '/== Screenshots ==/' => 'screenshots section',
    '/== Changelog ==/' => 'changelog section',
    '/= 0\.1\.2 =/' => '0.1.2 changelog entry',
];

foreach ($requiredReadmePatterns as $pattern => $label) {
    if (1 !== preg_match($pattern, $readme)) {
        $failures[] = "Missing or invalid {$label}.";
    }
}

$requiredPluginPatterns = [
    '/Plugin Name:\s*Nariyanto Safe Staging Guard/' => 'plugin name',
    '/Version:\s*0\.1\.2/' => 'plugin version',
    '/Requires at least:\s*6\.0/' => 'plugin minimum WordPress version',
    '/Requires PHP:\s*7\.4/' => 'plugin minimum PHP version',
    '/Author:\s*Septiyan Nariyanto/' => 'plugin author',
    '/Author URI:\s*https:\/\/nariyanto\.id/' => 'plugin author URI',
    '/License:\s*GPL-2\.0-or-later/' => 'plugin license',
    '/License URI:\s*https:\/\/www\.gnu\.org\/licenses\/gpl-2\.0\.html/' => 'plugin license URI',
    '/Text Domain:\s*nariyanto-safe-staging-guard/' => 'text domain',
    '/Domain Path:\s*\/languages/' => 'domain path',
];

foreach ($requiredPluginPatterns as $pattern => $label) {
    if (1 !== preg_match($pattern, $plugin)) {
        $failures[] = "Missing or invalid {$label}.";
    }
}

if (
    1 === preg_match('/Plugin URI:\s*(\S+)/', $plugin, $pluginUriMatches)
    && 1 === preg_match('/Author URI:\s*(\S+)/', $plugin, $authorUriMatches)
    && $pluginUriMatches[1] === $authorUriMatches[1]
) {
    $failures[] = 'Plugin URI and Author URI must not be the same for WordPress.org submission.';
}

if (false === strpos((string)$pluginClass, "public const VERSION = '0.1.2';")) {
    $failures[] = 'Plugin class VERSION constant must match 0.1.2.';
}

if (false === strpos($changelog, '## 0.1.2 - 2026-06-04')) {
    $failures[] = 'CHANGELOG.md must include the 0.1.2 release entry.';
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
