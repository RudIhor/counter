<?php

declare(strict_types=1);

namespace Ihorrud\Counter\Contracts;

interface CountByTime
{
    /** @param array<string, int> $logs */
    public function getCount(array $logs): int;

    public function getHumanReadableDate(): string;
}
