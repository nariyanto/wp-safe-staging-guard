<?php

declare(strict_types=1);

namespace Nariyanto\SafeStagingGuard;

final class NoindexPolicy
{
    public static function shouldNoindex(EnvironmentSettings $settings): bool
    {
        return !$settings->isProduction() && $settings->noindexEnabled();
    }
}
