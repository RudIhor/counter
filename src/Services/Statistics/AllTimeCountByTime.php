<?php

declare(strict_types=1);

namespace Ihorrud\Counter\Services\Statistics;

use DateTimeImmutable;
use Ihorrud\Counter\Contracts\CountByTime;

final class AllTimeCountByTime implements CountByTime
{
    private function __construct()
    {
    }

    public static function create(): AllTimeCountByTime
    {
        return new AllTimeCountByTime();
    }

    public function getCount(array $logs): int
    {
        ksort($logs);

        /** @var string $day */
        $day = current(array_keys($logs));

        /** @var DateTimeImmutable $date */
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $day);

        $result = 0;
        while ($date < new DateTimeImmutable()) {
            $result += $logs[$date->format('Y-m-d')] ?? 0;
            $date = $date->modify('+1 day');
        }

        return $result;
    }

    public function getHumanReadableDate(): string
    {
        return 'All time';
    }
}
