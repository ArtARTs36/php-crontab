<?php

namespace ArtARTs36\Crontab\Saver;

use ArtARTs36\Crontab\Contract\CrontabCommanderInterface;
use ArtARTs36\Crontab\Contract\TaskSaverInterface;
use ArtARTs36\Crontab\Data\Task;
use ArtARTs36\FileSystem\Contracts\FileSystem;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandExecutor;

class CommandTaskSaver implements TaskSaverInterface
{
    private $files;

    private $commander;

    private $executor;

    public function __construct(FileSystem $files, CrontabCommanderInterface $commander, ShellCommandExecutor $executor)
    {
        $this->files = $files;
        $this->commander = $commander;
        $this->executor = $executor;
    }

    /**
     * @param array<Task> $tasks
     */
    public function save(array $tasks): void
    {
        $path = $this->files->getTmpDir() . DIRECTORY_SEPARATOR . 'crontab-file.definition';

        $this->files->createFile($path, $this->buildFileContent($tasks));

        $this
            ->commander
            ->make()
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
