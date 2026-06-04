<?php

declare(strict_types=1);

namespace SNXWorks\SafeStagingGuard;

final class EnvironmentSettings
{
    /** @var array<string, mixed> */
    private array $values;

    /** @param array<string, mixed> $values */
    private function __construct(array $values)
    {
        $this->values = $values;
    }

    /** @param array<string, mixed> $raw */
    public static function fromArray(array $raw): self
    {
        $environment = self::choice((string)($raw['environment'] ?? 'staging'), ['local', 'staging', 'production'], 'staging');
        $emailMode = self::choice((string)($raw['email_mode'] ?? 'block'), ['block', 'redirect', 'allow'], 'block');
        $redirectEmail = strtolower(trim((string)($raw['redirect_email'] ?? '')));
        if (!filter_var($redirectEmail, FILTER_VALIDATE_EMAIL)) {
            $redirectEmail = '';
        }

        $noindex = self::boolValue($raw['noindex_enabled'] ?? true);
        if ($environment === 'production') {
            $noindex = false;
        }

        return new self([
            'environment' => $environment,
            'show_admin_bar_label' => self::boolValue($raw['show_admin_bar_label'] ?? true),
            'show_frontend_banner' => self::boolValue($raw['show_frontend_banner'] ?? true),
            'noindex_enabled' => $noindex,
            'email_mode' => $emailMode,
            'redirect_email' => $redirectEmail,
        ]);
    }

    public static function defaults(): self
    {
        return self::fromArray([]);
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return $this->values;
    }

    public function environment(): string
    {
        return (string)$this->values['environment'];
    }

    public function showAdminBarLabel(): bool
    {
        return (bool)$this->values['show_admin_bar_label'];
    }

    public function showFrontendBanner(): bool
    {
        return (bool)$this->values['show_frontend_banner'];
    }

    public function noindexEnabled(): bool
    {
        return (bool)$this->values['noindex_enabled'];
    }

    public function emailMode(): string
    {
        return (string)$this->values['email_mode'];
    }

    public function redirectEmail(): string
    {
        return (string)$this->values['redirect_email'];
    }

    public function isProduction(): bool
    {
        return $this->environment() === 'production';
    }

    /** @param array<int, string> $allowed */
    private static function choice(string $value, array $allowed, string $default): string
    {
        $value = strtolower(trim($value));
        return in_array($value, $allowed, true) ? $value : $default;
    }

    /** @param mixed $value */
    private static function boolValue($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }
        if (is_numeric($value)) {
            return (int)$value === 1;
        }
        $normalized = strtolower(trim((string)$value));
        return in_array($normalized, ['1', 'true', 'yes', 'on'], true);
    }
}
