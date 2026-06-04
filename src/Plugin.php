<?php

declare(strict_types=1);

namespace SNXWorks\SafeStagingGuard;

final class Plugin
{
    public const OPTION_NAME = 'safe_staging_guard_settings';
    public const VERSION = '0.1.2';

    public function register(): void
    {
        add_action('admin_init', [$this, 'registerSettings']);
        add_action('admin_menu', [$this, 'registerAdminPage']);
        add_action('admin_bar_menu', [$this, 'addAdminBarLabel'], 100);
        add_action('wp_enqueue_scripts', [$this, 'enqueueFrontendAssets']);
        add_action('wp_footer', [$this, 'renderFrontendBanner']);
        add_filter('wp_robots', [$this, 'filterRobots']);
        add_filter('wp_mail', [$this, 'filterMail']);
        add_filter('pre_wp_mail', [$this, 'maybeBlockMail'], 10, 2);
        add_filter('plugin_action_links_snxworks-safe-staging-guard/snxworks-safe-staging-guard.php', [$this, 'settingsLink']);
    }

    /** @param array<int, string> $links @return array<int, string> */
    public function settingsLink(array $links): array
    {
        $url = admin_url('options-general.php?page=snxworks-safe-staging-guard');
        array_unshift($links, '<a href="' . esc_url($url) . '">' . esc_html__('Settings', 'snxworks-safe-staging-guard') . '</a>');
        return $links;
    }

    public function registerSettings(): void
    {
        register_setting('safe_staging_guard', self::OPTION_NAME, [
            'type' => 'array',
            'sanitize_callback' => [$this, 'sanitizeSettings'],
            'default' => EnvironmentSettings::defaults()->toArray(),
        ]);
    }

    /** @param mixed $raw @return array<string, mixed> */
    public function sanitizeSettings($raw): array
    {
        return EnvironmentSettings::fromArray(is_array($raw) ? $raw : [])->toArray();
    }

    public function registerAdminPage(): void
    {
        add_options_page(
            __('SNXWorks Safe Staging Guard', 'snxworks-safe-staging-guard'),
            __('SNXWorks Safe Staging Guard', 'snxworks-safe-staging-guard'),
            'manage_options',
            'snxworks-safe-staging-guard',
            [$this, 'renderSettingsPage']
        );
    }

    public function renderSettingsPage(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to manage SNXWorks Safe Staging Guard.', 'snxworks-safe-staging-guard'));
        }
        $settings = $this->settings();
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('SNXWorks Safe Staging Guard', 'snxworks-safe-staging-guard'); ?></h1>
            <p><?php echo esc_html__('Prevent staging-site accidents with environment labels, noindex controls, and safe email handling.', 'snxworks-safe-staging-guard'); ?></p>
            <?php if ($settings->isProduction()) : ?>
                <div class="notice notice-warning"><p><?php echo esc_html__('Production mode is selected. Noindex and email interception are disabled in production mode.', 'snxworks-safe-staging-guard'); ?></p></div>
            <?php endif; ?>
            <form method="post" action="options.php">
                <?php settings_fields('safe_staging_guard'); ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="ssg-environment"><?php echo esc_html__('Environment', 'snxworks-safe-staging-guard'); ?></label></th>
                        <td>
                            <select id="ssg-environment" name="<?php echo esc_attr(self::OPTION_NAME); ?>[environment]">
                                <?php foreach (['local', 'staging', 'production'] as $environment) : ?>
                                    <option value="<?php echo esc_attr($environment); ?>" <?php selected($settings->environment(), $environment); ?>><?php echo esc_html(ucfirst($environment)); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description"><?php echo esc_html__('Use production only on the real live site. Staging/local modes enable safety controls.', 'snxworks-safe-staging-guard'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo esc_html__('Visual labels', 'snxworks-safe-staging-guard'); ?></th>
                        <td>
                            <label><input type="checkbox" name="<?php echo esc_attr(self::OPTION_NAME); ?>[show_admin_bar_label]" value="1" <?php checked($settings->showAdminBarLabel()); ?>> <?php echo esc_html__('Show admin bar environment label', 'snxworks-safe-staging-guard'); ?></label><br>
                            <label><input type="checkbox" name="<?php echo esc_attr(self::OPTION_NAME); ?>[show_frontend_banner]" value="1" <?php checked($settings->showFrontendBanner()); ?>> <?php echo esc_html__('Show frontend staging banner for logged-in users', 'snxworks-safe-staging-guard'); ?></label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo esc_html__('Search engines', 'snxworks-safe-staging-guard'); ?></th>
                        <td>
                            <label><input type="checkbox" name="<?php echo esc_attr(self::OPTION_NAME); ?>[noindex_enabled]" value="1" <?php checked($settings->noindexEnabled()); ?> <?php disabled($settings->isProduction()); ?>> <?php echo esc_html__('Add noindex/nofollow to staging/local pages', 'snxworks-safe-staging-guard'); ?></label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="ssg-email-mode"><?php echo esc_html__('Email safety mode', 'snxworks-safe-staging-guard'); ?></label></th>
                        <td>
                            <select id="ssg-email-mode" name="<?php echo esc_attr(self::OPTION_NAME); ?>[email_mode]">
                                <option value="block" <?php selected($settings->emailMode(), 'block'); ?>><?php echo esc_html__('Block outgoing mail', 'snxworks-safe-staging-guard'); ?></option>
                                <option value="redirect" <?php selected($settings->emailMode(), 'redirect'); ?>><?php echo esc_html__('Redirect outgoing mail', 'snxworks-safe-staging-guard'); ?></option>
                                <option value="allow" <?php selected($settings->emailMode(), 'allow'); ?>><?php echo esc_html__('Allow outgoing mail', 'snxworks-safe-staging-guard'); ?></option>
                            </select>
                            <p class="description"><?php echo esc_html__('Email interception is skipped automatically when environment is Production.', 'snxworks-safe-staging-guard'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="ssg-redirect-email"><?php echo esc_html__('Redirect email', 'snxworks-safe-staging-guard'); ?></label></th>
                        <td><input id="ssg-redirect-email" type="email" class="regular-text" name="<?php echo esc_attr(self::OPTION_NAME); ?>[redirect_email]" value="<?php echo esc_attr($settings->redirectEmail()); ?>"></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function addAdminBarLabel(\WP_Admin_Bar $adminBar): void
    {
        if (!is_user_logged_in() || !current_user_can('manage_options')) {
            return;
        }
        $settings = $this->settings();
        if (!$settings->showAdminBarLabel()) {
            return;
        }
        $adminBar->add_node([
            'id' => 'snxworks-safe-staging-guard-label',
            'title' => 'SSG: ' . strtoupper($settings->environment()),
            'href' => admin_url('options-general.php?page=snxworks-safe-staging-guard'),
            'meta' => ['class' => 'snxworks-safe-staging-guard-admin-label'],
        ]);
    }

    public function enqueueFrontendAssets(): void
    {
        $settings = $this->settings();
        if ($settings->isProduction() || !$settings->showFrontendBanner() || !is_user_logged_in()) {
            return;
        }

        wp_enqueue_style(
            'snxworks-safe-staging-guard-frontend',
            plugins_url('assets/frontend.css', SNXWORKS_SAFE_STAGING_GUARD_FILE),
            [],
            self::VERSION
        );
    }

    public function renderFrontendBanner(): void
    {
        $settings = $this->settings();
        if ($settings->isProduction() || !$settings->showFrontendBanner() || !is_user_logged_in()) {
            return;
        }
        echo '<div class="snxworks-safe-staging-guard-banner">';
        /* translators: 1: Current environment label, 2: Email safety mode. */
        echo esc_html(sprintf(__('SNXWorks Safe Staging Guard: %1$s environment — emails are %2$s.', 'snxworks-safe-staging-guard'), strtoupper($settings->environment()), $settings->emailMode()));
        echo '</div>';
    }

    /** @param array<string, bool> $robots @return array<string, bool> */
    public function filterRobots(array $robots): array
    {
        if (NoindexPolicy::shouldNoindex($this->settings())) {
            $robots['noindex'] = true;
            $robots['nofollow'] = true;
        }
        return $robots;
    }

    /** @param array<string, mixed> $args @return array<string, mixed> */
    public function filterMail(array $args): array
    {
        $safe = EmailSafety::apply($args, $this->settings());
        unset($safe['blocked']);
        return $safe;
    }

    /** @param null|bool $return @param array<string, mixed> $atts */
    public function maybeBlockMail($return, array $atts)
    {
        $safe = EmailSafety::apply($atts, $this->settings());
        if (!empty($safe['blocked'])) {
            return true;
        }
        return $return;
    }

    private function settings(): EnvironmentSettings
    {
        $raw = get_option(self::OPTION_NAME, EnvironmentSettings::defaults()->toArray());
        return EnvironmentSettings::fromArray(is_array($raw) ? $raw : []);
    }
}
