<?php

declare(strict_types=1);

namespace SNXWorks\SafeStagingGuard;

final class EmailSafety
{
    /**
     * @param array<string, mixed> $args
     * @return array<string, mixed>
     */
    public static function apply(array $args, EnvironmentSettings $settings): array
    {
        $args += [
            'to' => [],
            'subject' => '',
            'message' => '',
            'headers' => [],
            'attachments' => [],
        ];

        if ($settings->isProduction() || $settings->emailMode() === 'allow') {
            $args['blocked'] = false;
            return $args;
        }

        $originalRecipients = self::recipientList($args['to']);

        if ($settings->emailMode() === 'redirect' && $settings->redirectEmail() !== '') {
            $args['to'] = [$settings->redirectEmail()];
            $args['message'] = self::prependAuditNote((string)$args['message'], $originalRecipients, $settings);
            $args['blocked'] = false;
            return $args;
        }

        $args['to'] = [];
        $args['subject'] = 'SNXWorks Safe Staging Guard blocked a staging email.';
        $args['message'] = self::prependAuditNote('Email delivery was blocked by SNXWorks Safe Staging Guard.', $originalRecipients, $settings);
        $args['headers'] = [];
        $args['attachments'] = [];
        $args['blocked'] = true;
        return $args;
    }

    /** @param mixed $to */
    private static function recipientList($to): string
    {
        if (is_array($to)) {
            return implode(', ', array_map('strval', $to));
        }
        return (string)$to;
    }

    private static function prependAuditNote(string $message, string $originalRecipients, EnvironmentSettings $settings): string
    {
        $note = "SNXWorks Safe Staging Guard environment: " . $settings->environment() . "\n";
        $note .= "Original recipients: " . ($originalRecipients !== '' ? $originalRecipients : '(none)') . "\n\n";
        return $note . $message;
    }
}
