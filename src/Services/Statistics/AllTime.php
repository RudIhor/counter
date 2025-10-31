<?php

declare(strict_types=1);

namespace Ihorrud\Counter\Services\Statistics;

use DateTimeImmutable;
use Ihorrud\Counter\Contracts\CountByTime;

final class AllTime implements CountByTime
{
    private function __construct()
    {
    }

    public static function create(): AllTime
    {
        return new AllTime();
    }

    public function getCount(array $logs): int
    {
        // Check if this is time data
        if (isset($logs['time']) && is_array($logs['time'])) {
            $timeLogs = $logs['time'];
            ksort($timeLogs);
            
            if (empty($timeLogs)) {
                return 0;
            }

            /** @var string $day */
            $day = current(array_keys($timeLogs));

            /** @var DateTimeImmutable $date */
            $date = DateTimeImmutable::createFromFormat('Y-m-d', $day);

            $result = 0;
            while ($date < new DateTimeImmutable()) {
                $result += $timeLogs[$date->format('Y-m-d')] ?? 0;
                $date = $date->modify('+1 day');
            }

            return $result;
        }

        ksort($logs);

        if (empty($logs)) {
            return 0;
        }

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
