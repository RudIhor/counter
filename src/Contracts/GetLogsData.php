<?php

declare(strict_types=1);

namespace Ihorrud\Counter\Contracts;

use Ihorrud\Counter\ValueObjects\Tag;

interface GetLogsData
{
    /** @return array<string, int> */
    public function getLogsByTag(Tag $tag): array;
}
