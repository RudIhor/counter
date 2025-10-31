<?php

declare(strict_types=1);

namespace Ihorrud\Counter\Services;

use DateTimeImmutable;
use Ihorrud\Counter\Contracts\Writer;
use Ihorrud\Counter\DTOs\CommandInputDTO;
use Ihorrud\Counter\Entities\Log;
use Ihorrud\Counter\ValueObjects\Count;
use Ihorrud\Counter\ValueObjects\Tag;
use Ihorrud\Counter\ValueObjects\Time;

final readonly class LogCountService
{
    public function __construct(private Writer $writer)
    {
    }

    public function handle(CommandInputDTO $dto): void
    {
        $time = null;
        if ($dto->isTimeMode && $dto->timeString !== null) {
            $time = Time::fromString($dto->timeString);
        }

        $log = new Log(
            Tag::fromString($dto->tag),
            Count::fromInt($dto->count),
            new DateTimeImmutable($dto->createdAt ?? 'now'),
            $time
        );

        $this->writer->write($log);
    }
}
