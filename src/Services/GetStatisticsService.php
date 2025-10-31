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
     * @param CountByTime[] $formats
     * @return array<string, int|string|bool>
     */
    public function handle(Tag $tag, array $formats): array
    {
        $logs = $this->service->getLogsByTag($tag);
        $result = ['tag' => $tag->tag()];
        
        // Add metadata to indicate if this is time data
        $result['_isTimeData'] = isset($logs['time']) && is_array($logs['time']);

        foreach ($formats as $format) {
            $result[$format->getHumanReadableDate()] = $format->getCount($logs);
        }

        return $result;
    }
}
