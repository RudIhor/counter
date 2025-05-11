<?php

declare(strict_types=1);

namespace Ihorrud\Counter\Services;

use Ihorrud\Counter\Contracts\GetLogsData;
use Ihorrud\Counter\ValueObjects\Tag;

final class GetLogsDataFileService implements GetLogsData
{
    public function getLogsByTag(Tag $tag): array
    {
        /** @var string $content */
        $content = file_get_contents(dirname(__DIR__, 2) . '/var/storage.json');
        /** @var array<string, array<string, int>> $data */
        $data = json_decode($content, true);

        return $data[$tag->tag()] ?? [];
    }
}
