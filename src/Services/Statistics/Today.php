<?php

declare(strict_types=1);

namespace Ihorrud\Counter\Services\Statistics;

use DateTimeImmutable;
use Ihorrud\Counter\Contracts\CountByTime;

final class Today implements CountByTime
{
    private const string FORMAT = 'today';

    private DateTimeImmutable $date;

    private function __construct()
    {
        $this->date = new DateTimeImmutable(self::FORMAT);
    }

    public static function create(): Today
    {
        return new Today();
    }

    public function getCount(array $logs): int
    {
        // Check if this is time data
        if (isset($logs['time']) && is_array($logs['time'])) {
            /** @var int $seconds */
            $seconds = $logs['time'][$this->date->format('Y-m-d')] ?? 0;
            return $seconds;
        }

        /** @var int $count */
        $count = $logs[$this->date->format('Y-m-d')] ?? 0;

        return $count;
    }

    public function getHumanReadableDate(): string
    {
        return 'Today';
    }
}
