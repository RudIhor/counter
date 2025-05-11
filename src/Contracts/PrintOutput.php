<?php

declare(strict_types=1);

namespace Ihorrud\Counter\Contracts;

interface PrintOutput
{
    /** @param array<string, string> $errors */
    public function printErrors(array $errors): void;

    /** @param array<string, int|string> $data */
    public function printStatistics(array $data): void;
}
