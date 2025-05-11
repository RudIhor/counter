<?php

declare(strict_types=1);

namespace Ihorrud\Counter\Entities;

use Ihorrud\Counter\ValueObjects\Count;
use Ihorrud\Counter\ValueObjects\Tag;
use DateTimeImmutable;

final readonly class Log
{
    public function __construct(
        private Tag               $tag,
        private Count             $count,
        private DateTimeImmutable $createdAt,
    ) {
    }

    public function tag(): string
    {
        return $this->tag->tag();
    }

    public function count(): int
    {
        return $this->count->count();
    }

    public function createdAt(): string
    {
        return $this->createdAt->format('Y-m-d');
    }
}
