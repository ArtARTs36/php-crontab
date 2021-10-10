<?php

namespace ArtARTs36\Crontab;

use ArtARTs36\FileSystem\Contracts\FileSystem;
use ArtARTs36\ShellCommand\Interfaces\CommandBuilder;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandExecutor;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;

final class UserCrontab extends AbstractCrontab
{
    private $user;

    public function __construct(
        CommandBuilder $builder,
        ShellCommandExecutor $executor,
        FileSystem $fileSystem,
        string $user
    ) {
        parent::__construct($builder, $executor, $fileSystem);

        $this->user = $user;
    }

    protected function makeCommand(): ShellCommandInterface
    {
        return $this->builder->make('crontab')->addCutOption('u')->addArgument($this->user);
    }
}
