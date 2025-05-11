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

    /** @var array<string, string> */
    private array $errors = [];

    /**
     * @param array<int, string> $args
     * @return CommandInputDTO
     */
    public static function fromArray(array $args): CommandInputDTO
    {
        $commandInputDTO = new CommandInputDTO();

        $commandInputDTO->tag = $args[1] ?? '';
        $commandInputDTO->count = intval($args[2] ?? 0);
        $commandInputDTO->createdAt = $args[3] ?? 'now';

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

        if ($this->count === 0) {
            $this->addError('count', 'validation.required');
        } elseif ($this->count < 0) {
            $this->addError('count', 'validation.min');
        } elseif ($this->count > 1_000_000) {
            $this->addError('count', 'validation.max');
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
