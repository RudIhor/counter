<?php

namespace Ihorrud\Counter\Services\Statistics;

use DateTimeImmutable;
use Ihorrud\Counter\Contracts\CountByTime;

final class ThisWeek implements CountByTime
{
    private const string FORMAT = 'this week';

    private DateTimeImmutable $date;

    private function __construct()
    {
        $this->date = new DateTimeImmutable(self::FORMAT);
    }

    public static function create(): self
    {
        return new self();
    }

    public function getCount(array $logs): int
    {
        // Check if this is time data
        if (isset($logs['time']) && is_array($logs['time'])) {
            $timeLogs = $logs['time'];
            $result = 0;
            $date = clone $this->date;
            while ($date < new DateTimeImmutable('+1 day')) {
                $result += $timeLogs[$date->format('Y-m-d')] ?? 0;
                $date = $date->modify('+1 day');
            }
            return $result;
        }

        $result = 0;
        $date = clone $this->date;
        while ($date < new DateTimeImmutable('+1 day')) {
            $result += $logs[$date->format('Y-m-d')] ?? 0;
            $date = $date->modify('+1 day');
        }

        return $result;
    }

    public function getHumanReadableDate(): string
    {
        return 'This week';
    }
}
