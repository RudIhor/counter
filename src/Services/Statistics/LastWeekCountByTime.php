<?php

declare(strict_types=1);

namespace Ihorrud\Counter\Services\Statistics;

use DateTimeImmutable;
use Ihorrud\Counter\Contracts\CountByTime;

final class LastWeekCountByTime implements CountByTime
{
    private const string FORMAT = 'last week';

    private DateTimeImmutable $date;

    private function __construct()
    {
        $this->date = new DateTimeImmutable(self::FORMAT);
    }

    public static function create(): LastWeekCountByTime
    {
        return new LastWeekCountByTime();
    }

    public function getCount(array $logs): int
    {
        $result = 0;
        while ($this->date < new DateTimeImmutable('last monday')) {
            $result += $logs[$this->date->format('Y-m-d')] ?? 0;
            $this->date = $this->date->modify('+1 day');
        }

        return $result;
    }

    public function getHumanReadableDate(): string
    {
        return 'Last week';
    }
}
