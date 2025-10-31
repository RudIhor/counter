<?php

namespace Ihorrud\Counter\Services\Output;

use Bramus\Ansi\Ansi;
use Bramus\Ansi\ControlSequences\EscapeSequences\Enums\SGR;
use Bramus\Ansi\Writers\BufferWriter;
use Bramus\Ansi\Writers\StreamWriter;
use Ihorrud\Counter\Contracts\PrintOutput;

final class StdOutputService implements PrintOutput
{
    private Ansi $ansi;

    public function __construct()
    {
        $this->ansi = new Ansi(new BufferWriter());
    }

    public function printErrors(array $errors): void
    {
        /** @var array<string, string> $translations */
        $translations = require_once dirname(__DIR__, 3) . '/translations/validation.php';

        foreach ($errors as $key => $value) {
            $errorMessage = $translations[str_replace('validation', $key, $value)];
            echo $this->ansi
                    ->color([SGR::COLOR_BG_RED])
                    ->bold()
                    ->text($errorMessage)
                    ->reset()
                    ->get()
                . PHP_EOL;
        }
    }

    public function printStatistics(array $data): void
    {
        $tag = $data['tag'];
        unset($data['tag']);
        
        // Check if we're displaying time data using explicit metadata
        $isTimeData = $data['_isTimeData'] ?? false;
        unset($data['_isTimeData']);

        echo $this->ansi->bold()->text('Statistics:')->reset()->get() . PHP_EOL;
        echo '*-------------------------*' . PHP_EOL;
        echo <<<EOF
                {$this->ansi->bold()->text(strval($tag))->reset()->get()}
        EOF;
        foreach ($data as $date => $count) {
            $displayValue = $count;
            if ($isTimeData && $count > 0) {
                // Format time as HH:MM:SS or MM:SS
                $hours = floor($count / 3600);
                $minutes = floor(($count % 3600) / 60);
                $seconds = $count % 60;
                if ($hours > 0) {
                    $displayValue = sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
                } else {
                    $displayValue = sprintf('%d:%02d', $minutes, $seconds);
                }
            }
            echo <<<EOF

                    $date: {$this->ansi->bold()->text(strval($displayValue))->reset()->get()}
            EOF;
        }
        echo PHP_EOL . '*-------------------------*' . PHP_EOL;
    }
}
