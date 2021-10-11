<?php

namespace ArtARTs36\Crontab;

use ArtARTs36\Crontab\Contract\CrontabInterface;
use ArtARTs36\Crontab\Data\CrontabDefinition;
use ArtARTs36\Crontab\Data\Task;
use ArtARTs36\Crontab\Exception\CrontabForUserNotFoundException;
use ArtARTs36\FileSystem\Contracts\FileSystem;
use ArtARTs36\ShellCommand\Exceptions\UserExceptionTrigger;
use ArtARTs36\ShellCommand\Interfaces\CommandBuilder;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandExecutor;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\Result\CommandResult;
use ArtARTs36\Str\Str;

abstract class AbstractCrontab implements CrontabInterface
{
    abstract protected function makeCommand(): ShellCommandInterface;

    protected $builder;

    protected $executor;

    protected $files;

    public function __construct(
        CommandBuilder $builder,
        ShellCommandExecutor $executor,
        FileSystem $files
    ) {
        $this->builder = $builder;
        $this->executor = $executor;
        $this->files = $files;
    }

    public function getAll(): CrontabDefinition
    {
        return new CrontabDefinition($this
            ->makeCommand()
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
            $list = $this->getAll();
        } catch (CrontabForUserNotFoundException $exception) {
            $list = [];
        }

        array_push($list, ...(is_array($task) ? $task : [$task]));

        $this->doSave($list);

        return new CrontabDefinition($list);
    }

    /**
     * @param array<Task> $tasks
     */
    public function set(array $tasks): CrontabDefinition
    {
        $this->doSave($tasks);

        return new CrontabDefinition($tasks);
    }

    public function removeAll(): void
    {
        $this->makeCommand()->addCutOption('r')->executeOrFail($this->executor);
    }

    public function remove(Task $task): CrontabDefinition
    {
        $newTasks = [];

        foreach ($this->getAll() as $existsTask) {
            if (! $existsTask->equals($task)) {
                $newTasks[] = $task;
            }
        }

        $this->doSave($newTasks);

        return new CrontabDefinition($newTasks);
    }

    protected function doSave(array $tasks): void
    {
        $path = $this->files->getTmpDir() . DIRECTORY_SEPARATOR . 'crontab-file.definition';

        $this->files->createFile($path, $this->buildFileContent($tasks));

        $this
            ->makeCommand()
            ->addArgument($path)
            ->executeOrFail($this->executor);

        $this->files->removeFile($path);
    }

    /**
     * @param array<Task> $tasks
     */
    protected function buildFileContent(array $tasks): string
    {
        $content = '';

        foreach ($tasks as $task) {
            $content .= $task->expression . ' ' . $task->commandLine . "\n";
        }

        return $content;
    }
}
