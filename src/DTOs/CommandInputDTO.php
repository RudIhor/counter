<?php

declare(strict_types=1);

namespace Ihorrud\Counter\DTOs;

use DateMalformedStringException;
use DateTimeImmutable;

final class CommandInputDTO extends DTO
{
    public string $tag;

    public int $count;

    public string $createdAt;

    public ?string $timeString = null;

    public bool $isTimeMode = false;

    /** @var array<string, string> */
    private array $errors = [];

    /**
     * @param array<int, string> $args
     * @return CommandInputDTO
     */
    public static function fromArray(array $args): CommandInputDTO
    {
        $commandInputDTO = new CommandInputDTO();

        // Remove the script name
        array_shift($args);

        // Check for -t or --time flag
        $timeFlags = ['-t', '--time'];
        $commandInputDTO->isTimeMode = !empty(array_intersect($timeFlags, $args));

        // Remove flags from args
        $args = array_values(array_filter($args, fn($arg) => !in_array($arg, $timeFlags)));

        $commandInputDTO->tag = $args[0] ?? '';

        if ($commandInputDTO->isTimeMode) {
            // In time mode: count [tag] [MM:SS] -t
            $commandInputDTO->timeString = $args[1] ?? null;
            $commandInputDTO->count = 1; // Default count for time mode
            $commandInputDTO->createdAt = $args[2] ?? 'now';
        } else {
            // Regular mode: count [tag] [count] [date]
            $commandInputDTO->count = intval($args[1] ?? 0);
            $commandInputDTO->createdAt = $args[2] ?? 'now';
        }

        return $commandInputDTO;
    }

    /**
     * @throws DateMalformedStringException
     */
    public function validate(): array
    {
        if ($this->tag === '') {
            $this->addError('tag', 'validation.required');
        } elseif (strlen($this->tag) < 3) {
            $this->addError('tag', 'validation.min');
        } elseif (strlen($this->tag) > 50) {
            $this->addError('tag', 'validation.max');
        }

        if ($this->isTimeMode) {
            // Validate time format
            if ($this->timeString === null || $this->timeString === '') {
                $this->addError('time', 'validation.required');
            } elseif (!preg_match('/^\d+:\d{1,2}$/', $this->timeString)) {
                $this->addError('time', 'validation.invalid_format');
            } else {
                // Validate time values
                $parts = explode(':', $this->timeString);
                $minutes = (int) $parts[0];
                $seconds = (int) $parts[1];
                
                if ($seconds >= 60) {
                    $this->addError('time', 'validation.seconds_invalid');
                } elseif ($minutes < 0 || $seconds < 0) {
                    $this->addError('time', 'validation.negative_time');
                } elseif (($minutes * 60 + $seconds) >= 86400) {
                    $this->addError('time', 'validation.time_too_large');
                }
            }
        } else {
            // Validate count in regular mode
            if ($this->count === 0) {
                $this->addError('count', 'validation.required');
            } elseif ($this->count < 0) {
                $this->addError('count', 'validation.min');
            } elseif ($this->count > 1_000_000) {
                $this->addError('count', 'validation.max');
            }
        }

        if (!preg_match('/\d{4}-\d{2}-\d{2}/', $this->createdAt) && $this->createdAt !== 'now') {
            $this->addError('created_at', 'validation.invalid_format');
        } elseif ((new DateTimeImmutable($this->createdAt))->getTimestamp() <= 0) {
            $this->addError('created_at', 'validation.timestamp_is_negative');
        } elseif ((new DateTimeImmutable($this->createdAt))->getTimestamp() > time()) {
            $this->addError('created_at', 'validation.timestamp_is_future');
        }

        return $this->errors;
    }

    private function addError(string $field, string $error): void
    {
        $this->errors[$field] = $error;
    }
}
