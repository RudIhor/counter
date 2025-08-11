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
        $result = 0;
        while ($this->date < new DateTimeImmutable('+1 day')) {
            $result += $logs[$this->date->format('Y-m-d')] ?? 0;
            $this->date = $this->date->modify('+1 day');
        }

        return $result;
    }

    public function getHumanReadableDate(): string
    {
        return 'This week';
    }
}
