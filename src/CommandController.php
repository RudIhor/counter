<?php

declare(strict_types=1);

namespace Ihorrud\Counter;

use DateMalformedStringException;
use Ihorrud\Counter\Contracts\PrintOutput;
use Ihorrud\Counter\DTOs\CommandInputDTO;
use Ihorrud\Counter\Services\GetLogsDataFileService;
use Ihorrud\Counter\Services\GetStatisticsService;
use Ihorrud\Counter\Services\LogCountService;
use Ihorrud\Counter\Services\Output\StdOutputService;
use Ihorrud\Counter\Services\Statistics\AllTime;
use Ihorrud\Counter\Services\Statistics\LastMonth;
use Ihorrud\Counter\Services\Statistics\LastWeek;
use Ihorrud\Counter\Services\Statistics\ThisMonth;
use Ihorrud\Counter\Services\Statistics\ThisWeek;
use Ihorrud\Counter\Services\Statistics\Today;
use Ihorrud\Counter\ValueObjects\Tag;
use Ihorrud\Counter\Writers\JsonWriter;

class CommandController
{
    private LogCountService $logCountService;
    private GetStatisticsService $getStatisticsService;
    private PrintOutput $outputService;

    public function __construct()
    {
        $this->logCountService = new LogCountService(new JsonWriter());
        $this->getStatisticsService = new GetStatisticsService(new GetLogsDataFileService());
        $this->outputService = new StdOutputService();
    }

    /**
     * @throws DateMalformedStringException
     */
    public function handle(): void
    {
        /** @var array<int, string> $args */
        // TODO: ideally, we should provide an interface for retrieving data not only from the CLI
        $args = $_SERVER['argv'];
        $commandInputDTO = CommandInputDTO::fromArray($args);

        $errors = $commandInputDTO->validate();
        if (!empty($errors)) {
            $this->outputService->printErrors($errors);

            return;
        }

        $this->logCountService->handle($commandInputDTO);

        $statistics = $this->getStatisticsService->handle(Tag::fromString($commandInputDTO->tag), [
            Today::create(),
            ThisWeek::create(),
            LastWeek::create(),
            ThisMonth::create(),
            LastMonth::create(),
            AllTime::create(),
        ]);

        $this->outputService->printStatistics($statistics);
    }
}
