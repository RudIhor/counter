<?php

declare(strict_types=1);

namespace Ihorrud\Counter\Services\Statistics;

use DateTimeImmutable;
use Ihorrud\Counter\Contracts\CountByTime;

final class TodayCountByTime implements CountByTime
{
    private const string FORMAT = 'today';

    private DateTimeImmutable $date;

    private function __construct()
    {
        $this->date = new DateTimeImmutable(self::FORMAT);
    }

    public static function create(): TodayCountByTime
    {
        return new TodayCountByTime();
    }

    public function getCount(array $logs): int
    {
        /** @var int $count */
        $count = $logs[$this->date->format('Y-m-d')] ?? 0;

        return $count;
    }

    public function getHumanReadableDate(): string
    {
        return 'Today';
    }
}
