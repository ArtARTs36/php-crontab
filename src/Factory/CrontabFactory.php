<?php

namespace ArtARTs36\Crontab\Factory;

use ArtARTs36\Crontab\Crontab;
use ArtARTs36\Crontab\UserCrontab;
use ArtARTs36\Crontab\Contract\CrontabInterface;
use ArtARTs36\ShellCommand\Executors\ProcOpenExecutor;
use ArtARTs36\ShellCommand\ShellCommander;
use ArtARTs36\FileSystem\Local\LocalFileSystem;

class CrontabFactory
{
    public static function create(): CrontabInterface
    {
        return new Crontab(new ShellCommander(), new ProcOpenExecutor(), new LocalFileSystem());
    }

    public static function asUser(string $user): CrontabInterface
    {
        return new UserCrontab(new ShellCommander(), new ProcOpenExecutor(), new LocalFileSystem(), $user);
    }
}
