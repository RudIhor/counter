<?php

declare(strict_types=1);

namespace Ihorrud\Counter\Services;

use Ihorrud\Counter\Contracts\CountByTime;
use Ihorrud\Counter\Contracts\GetLogsData;
use Ihorrud\Counter\ValueObjects\Tag;

final readonly class GetStatisticsService
{
    public function __construct(private GetLogsData $service)
    {
    }

    /**
     * @param Tag $tag
     * @param CountByTime[] $counts
     * @return array<string, int|string>
     */
    public function handle(Tag $tag, array $counts): array
    {
        $result = ['tag' => $tag->tag()];

        foreach ($counts as $count) {
            $result[$count->getHumanReadableDate()] = $count->getCount($this->service->getLogsByTag($tag));
        }

        return $result;
    }
}
