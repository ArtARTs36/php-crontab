<?php

namespace ArtARTs36\Crontab\Builder;

use ArtARTs36\Crontab\Commander\CrontabCommander;
use ArtARTs36\Crontab\Commander\UserCrontabCommander;
use ArtARTs36\Crontab\Contract\CrontabCommanderInterface;
use ArtARTs36\Crontab\Contract\CrontabInterface;
use ArtARTs36\Crontab\Crontab;
use ArtARTs36\Crontab\Saver\CommandTaskSaver;
use ArtARTs36\Crontab\Saver\DryRunTaskSaver;
use ArtARTs36\FileSystem\Contracts\FileSystem;
use ArtARTs36\FileSystem\Local\LocalFileSystem;
use ArtARTs36\ShellCommand\Executors\ProcOpenExecutor;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandExecutor;

class CrontabBuilder
{
    protected $params = [
        'saver' => null,
        'command_make' => null,
    ];

    public function __construct()
    {
        $this->params = [
            'command_make' => function () {
                return new CrontabCommander();
            },
            'saver' => function (
                FileSystem $fileSystem,
                CrontabCommanderInterface $commander,
                ShellCommandExecutor $executor
            ) {
                return new CommandTaskSaver($fileSystem, $commander, $executor);
            },
        ];

        $this->params['command_make'] = function () {
            return new CrontabCommander();
        };
    }

    public function as(string $user): self
    {
        $this->params['command_make'] = function () use ($user) {
            return new UserCrontabCommander($user);
        };

        return $this;
    }

    public function dryRun(): self
    {
        $this->params['saver'] = function () {
            return new DryRunTaskSaver();
        };

        return $this;
    }

    public function build(FileSystem $fileSystem = null, ShellCommandExecutor $executor = null): CrontabInterface
    {
        $fileSystem = $fileSystem ?? new LocalFileSystem();
        $executor = $executor ?? new ProcOpenExecutor();

        $commander = $this->params['command_make']();
        $saver = $this->params['saver']($fileSystem, $commander, $executor);

        return new Crontab($commander, $executor, $saver);
    }
}
