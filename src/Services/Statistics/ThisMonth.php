<?php

declare(strict_types=1);

namespace Ihorrud\Counter\Services\Statistics;

use DateTimeImmutable;
use Ihorrud\Counter\Contracts\CountByTime;

final class ThisMonth implements CountByTime
{
    private const string FORMAT = 'first day of this month';

    private DateTimeImmutable $date;

    private function __construct()
    {
        $this->date = new DateTimeImmutable(self::FORMAT);
    }

    public static function create(): ThisMonth
    {
        return new ThisMonth();
    }

    public function getCount(array $logs): int
    {
        // Check if this is time data
        if (isset($logs['time']) && is_array($logs['time'])) {
            $timeLogs = $logs['time'];
            $result = 0;
            $date = clone $this->date;
            while ($date < new DateTimeImmutable()) {
                $result += $timeLogs[$date->format('Y-m-d')] ?? 0;
                $date = $date->modify('+1 day');
            }
            return $result;
        }

        $result = 0;
        $date = clone $this->date;
        while ($date < new DateTimeImmutable()) {
            $result += $logs[$date->format('Y-m-d')] ?? 0;
            $date = $date->modify('+1 day');
        }

        return $result;
    }

    public function getHumanReadableDate(): string
    {
        return 'This month';
    }
}
