<?php

namespace ArtARTs36\Crontab\Saver;

use ArtARTs36\Crontab\Contract\CrontabCommanderInterface;
use ArtARTs36\Crontab\Contract\TaskSaverInterface;
use ArtARTs36\Crontab\Data\CrontabDefinition;
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

    public function save(CrontabDefinition $definition): void
    {
        $path = $this->files->getTmpDir() . DIRECTORY_SEPARATOR . 'crontab-file.definition';

        $this->files->createFile($path, $definition->asFile());

        $this
            ->commander
            ->make()
            ->addArgument($path)
            ->executeOrFail($this->executor);

        $this->files->removeFile($path);
    }
}
