<?php

declare(strict_types=1);

namespace Ihorrud\Counter\Entities;

use Ihorrud\Counter\ValueObjects\Count;
use Ihorrud\Counter\ValueObjects\Tag;
use Ihorrud\Counter\ValueObjects\Time;
use DateTimeImmutable;

final readonly class Log
{
    public function __construct(
        private Tag               $tag,
        private Count             $count,
        private DateTimeImmutable $createdAt,
        private ?Time             $time = null,
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

    public function time(): ?Time
    {
        return $this->time;
    }

    public function hasTime(): bool
    {
        return $this->time !== null;
    }
}
