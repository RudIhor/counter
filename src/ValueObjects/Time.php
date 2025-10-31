<?php

declare(strict_types=1);

namespace Ihorrud\Counter\ValueObjects;

use Webmozart\Assert\Assert;

final class Time
{
    private int $seconds;

    private function __construct(int $seconds)
    {
        Assert::integer($seconds);
        Assert::greaterThanEq($seconds, 0);
        Assert::lessThan($seconds, 86400); // Less than 24 hours

        $this->seconds = $seconds;
    }

    public static function fromMinutesSeconds(int $minutes, int $seconds): Time
    {
        Assert::greaterThanEq($minutes, 0);
        Assert::greaterThanEq($seconds, 0);
        Assert::lessThan($seconds, 60);

        $totalSeconds = ($minutes * 60) + $seconds;
        return new Time($totalSeconds);
    }

    public static function fromString(string $timeString): Time
    {
        if (!preg_match('/^(\d+):(\d{1,2})$/', $timeString, $matches)) {
            throw new \InvalidArgumentException('Time must be in format MM:SS or M:SS');
        }

        $minutes = (int) $matches[1];
        $seconds = (int) $matches[2];

        return self::fromMinutesSeconds($minutes, $seconds);
    }

    public function seconds(): int
    {
        return $this->seconds;
    }

    public function minutes(): int
    {
        return (int) floor($this->seconds / 60);
    }

    public function remainingSeconds(): int
    {
        return $this->seconds % 60;
    }

    public function toString(): string
    {
        return sprintf('%d:%02d', $this->minutes(), $this->remainingSeconds());
    }
}
