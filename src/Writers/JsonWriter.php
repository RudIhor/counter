<?php

declare(strict_types=1);

namespace Ihorrud\Counter\Writers;

use Ihorrud\Counter\Contracts\Writer;
use Ihorrud\Counter\Entities\Log;

final class JsonWriter implements Writer
{
    public function write(Log $log): void
    {
        $filename = dirname(__DIR__, 2) . '/var/storage.json';
        if (!file_exists($filename)) {
            touch($filename);
        }
        /** @var string $content */
        $content = file_get_contents($filename);
        /** @var array<string, array<string, int>> $data */
        $data = json_decode($content, true) ?? [];
        file_put_contents($filename, json_encode($this->merge($data, $log)));
    }

    /**
     * @param array<string, array<string, int>> $existingData
     * @param Log $log
     * @return array<string, array<string, int>>
     */
    private function merge(array $existingData, Log $log): array
    {
        $result = $existingData;

        if (array_key_exists($log->tag(), $result)) {
            if (array_key_exists($log->createdAt(), $result[$log->tag()])) {
                $result[$log->tag()][$log->createdAt()] += $log->count();
            } else {
                $result[$log->tag()][$log->createdAt()] = $log->count();
            }
        } else {
            $result[$log->tag()][$log->createdAt()] = $log->count();
        }

        return $result;
    }
}
