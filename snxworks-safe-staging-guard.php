<?php
/**
 * Plugin Name: SNXWorks Safe Staging Guard
 * Description: Prevent staging-site accidents with visible environment labels, noindex controls, and safe email handling.
 * Version: 0.1.2
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Septiyan Nariyanto
 * Author URI: https://nariyanto.id
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: snxworks-safe-staging-guard
 * Domain Path: /languages
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

define('SNXWORKS_SAFE_STAGING_GUARD_FILE', __FILE__);

require_once __DIR__ . '/src/EnvironmentSettings.php';
require_once __DIR__ . '/src/EmailSafety.php';
require_once __DIR__ . '/src/NoindexPolicy.php';
require_once __DIR__ . '/src/Plugin.php';

add_action('plugins_loaded', static function (): void {
    $plugin = new \SNXWorks\SafeStagingGuard\Plugin();
    $plugin->register();
});
