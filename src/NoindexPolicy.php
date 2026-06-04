<?php

declare(strict_types=1);

namespace SNXWorks\SafeStagingGuard;

final class NoindexPolicy
{
    public static function shouldNoindex(EnvironmentSettings $settings): bool
    {
        return !$settings->isProduction() && $settings->noindexEnabled();
    }
}
