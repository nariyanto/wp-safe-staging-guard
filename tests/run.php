<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/EnvironmentSettings.php';
require_once __DIR__ . '/../src/EmailSafety.php';
require_once __DIR__ . '/../src/NoindexPolicy.php';

use Nariyanto\SafeStagingGuard\EmailSafety;
use Nariyanto\SafeStagingGuard\EnvironmentSettings;
use Nariyanto\SafeStagingGuard\NoindexPolicy;

$failures = 0;

function assert_true(bool $condition, string $message): void {
    global $failures;
    if (!$condition) {
        $failures++;
        echo "FAIL: {$message}\n";
    }
}

function assert_same($expected, $actual, string $message): void {
    global $failures;
    if ($expected !== $actual) {
        $failures++;
        echo "FAIL: {$message}\nExpected: " . var_export($expected, true) . "\nActual: " . var_export($actual, true) . "\n";
    }
}

// EnvironmentSettings defaults and sanitization.
$defaults = EnvironmentSettings::fromArray([]);
assert_same('staging', $defaults->environment(), 'default environment should be staging');
assert_true($defaults->showAdminBarLabel(), 'admin bar label should default on');
assert_true($defaults->showFrontendBanner(), 'frontend banner should default on');
assert_true($defaults->noindexEnabled(), 'noindex should default on for staging safety');
assert_same('block', $defaults->emailMode(), 'email mode should default to block');
assert_same('', $defaults->redirectEmail(), 'redirect email should default empty');

$production = EnvironmentSettings::fromArray([
    'environment' => 'production',
    'show_admin_bar_label' => '0',
    'show_frontend_banner' => '0',
    'noindex_enabled' => '1',
    'email_mode' => 'redirect',
    'redirect_email' => 'Admin@Example.COM ',
]);
assert_same('production', $production->environment(), 'valid production environment should be accepted');
assert_true(!$production->showAdminBarLabel(), 'admin bar label should accept false-ish value');
assert_true(!$production->showFrontendBanner(), 'frontend banner should accept false-ish value');
assert_true(!$production->noindexEnabled(), 'noindex should be forced off for production');
assert_same('redirect', $production->emailMode(), 'valid redirect mode should be accepted');
assert_same('admin@example.com', $production->redirectEmail(), 'redirect email should be normalized');

$invalid = EnvironmentSettings::fromArray([
    'environment' => 'weird',
    'email_mode' => 'dangerous',
    'redirect_email' => 'not-an-email',
]);
assert_same('staging', $invalid->environment(), 'invalid environment should fall back to staging');
assert_same('block', $invalid->emailMode(), 'invalid email mode should fall back to block');
assert_same('', $invalid->redirectEmail(), 'invalid redirect email should be dropped');

// EmailSafety behavior.
$blocked = EmailSafety::apply([
    'to' => 'customer@example.com',
    'subject' => 'Hello',
    'message' => 'Body',
    'headers' => [],
    'attachments' => [],
], EnvironmentSettings::fromArray(['email_mode' => 'block']));
assert_true($blocked['blocked'], 'block mode should mark email as blocked');
assert_same('Nariyanto Safe Staging Guard blocked a staging email.', $blocked['subject'], 'blocked email should use safe subject');
assert_same([], $blocked['to'], 'blocked email should have no recipients');

$redirected = EmailSafety::apply([
    'to' => 'customer@example.com',
    'subject' => 'Original',
    'message' => 'Original body',
    'headers' => [],
    'attachments' => [],
], EnvironmentSettings::fromArray(['email_mode' => 'redirect', 'redirect_email' => 'dev@example.com']));
assert_true(!$redirected['blocked'], 'redirect mode should not mark email as blocked');
assert_same(['dev@example.com'], $redirected['to'], 'redirect mode should replace recipients');
assert_true(strpos($redirected['message'], 'Original recipients: customer@example.com') !== false, 'redirect body should include original recipient audit note');

$allowed = EmailSafety::apply([
    'to' => 'customer@example.com',
    'subject' => 'Allowed',
    'message' => 'Body',
    'headers' => [],
    'attachments' => [],
], EnvironmentSettings::fromArray(['email_mode' => 'allow']));
assert_same('customer@example.com', $allowed['to'], 'allow mode should leave recipients unchanged');

// Noindex behavior.
assert_true(NoindexPolicy::shouldNoindex(EnvironmentSettings::fromArray(['environment' => 'staging', 'noindex_enabled' => '1'])), 'staging noindex should be enabled');
assert_true(!NoindexPolicy::shouldNoindex(EnvironmentSettings::fromArray(['environment' => 'production', 'noindex_enabled' => '1'])), 'production noindex should never be enabled');

if ($failures > 0) {
    echo "{$failures} failure(s)\n";
    exit(1);
}

echo "All tests passed\n";
