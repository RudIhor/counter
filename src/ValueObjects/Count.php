<?php

declare(strict_types=1);

namespace Ihorrud\Counter\ValueObjects;

use Webmozart\Assert\Assert;

final class Count
{
    private int $count;

    private function __construct(int $count)
    {
        Assert::integer($count);
        Assert::greaterThanEq($count, 0);
        Assert::lessThan($count, 1_000_000);

        $this->count = $count;
    }

    public static function fromInt(int $count): Count
    {
        return new Count($count);
    }

    public function count(): int
    {
        return $this->count;
    }
}
