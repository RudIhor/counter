<?php

declare(strict_types=1);

namespace Ihorrud\Counter\DTOs;

abstract class DTO
{
    /** @return array<string, string> */
    abstract public function validate(): array;
}
