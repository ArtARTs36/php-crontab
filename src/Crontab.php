<?php

namespace ArtARTs36\Crontab;

use ArtARTs36\Crontab\Contract\CrontabCommanderInterface;
use ArtARTs36\Crontab\Contract\CrontabInterface;
use ArtARTs36\Crontab\Contract\TaskSaverInterface;
use ArtARTs36\Crontab\Data\CrontabDefinition;
use ArtARTs36\Crontab\Data\Task;
use ArtARTs36\Crontab\Exception\CrontabForUserNotFoundException;
use ArtARTs36\FileSystem\Contracts\FileSystem;
use ArtARTs36\ShellCommand\Exceptions\UserExceptionTrigger;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandExecutor;
use ArtARTs36\ShellCommand\Result\CommandResult;
use ArtARTs36\Str\Str;

final class Crontab implements CrontabInterface
{
    protected $builder;

    protected $executor;

    protected $saver;

    public function __construct(
        CrontabCommanderInterface $commander,
        ShellCommandExecutor $executor,
        TaskSaverInterface $saver
    ) {
        $this->builder = $commander;
        $this->executor = $executor;
        $this->saver = $saver;
    }

    public function getAll(): CrontabDefinition
    {
        return new CrontabDefinition($this
            ->builder
            ->make()
            ->addCutOption('l')
            ->setExceptionTrigger(UserExceptionTrigger::fromCallbacks([
                function (CommandResult $result) {
                    $notFound = $result->getError()->match('#crontab: no crontab for (.*)#');

                    if ($notFound->isNotEmpty()) {
                        throw new CrontabForUserNotFoundException($notFound);
                    }
                },
            ]))
            ->executeOrFail($this->executor)
            ->getResult()
            ->trim()
            ->lines()
            ->filter('count')
            ->mapToArray(function (Str $str) {
                $parts = $str->explode(' ');
                $expression = $parts->slice(0, 5)->implode(' ');
                $commandLine = $parts->slice(5)->implode(' ');

                return new Task($expression, $commandLine);
            }));
    }

    public function add($task): CrontabDefinition
    {
        try {
            $list = $this->getAll()->tasks();
        } catch (CrontabForUserNotFoundException $exception) {
            $list = [];
        }

        array_push($list, ...(is_array($task) ? $task : [$task]));

        $this->saver->save($list);

        return new CrontabDefinition($list);
    }

    /**
     * @param array<Task> $tasks
     */
    public function set(array $tasks): CrontabDefinition
    {
        $this->saver->save($tasks);

        return new CrontabDefinition($tasks);
    }

    public function removeAll(): void
    {
        $this->builder->make()->addCutOption('r')->executeOrFail($this->executor);
    }

    public function remove(Task $task): CrontabDefinition
    {
        $newTasks = [];

        foreach ($this->getAll() as $existsTask) {
            if (! $existsTask->equals($task)) {
                $newTasks[] = $task;
            }
        }

        $this->saver->save($newTasks);

        return new CrontabDefinition($newTasks);
    }
}
