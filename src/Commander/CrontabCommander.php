<?php

namespace ArtARTs36\Crontab\Commander;

use ArtARTs36\Crontab\Contract\CrontabCommanderInterface;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\ShellCommand;

class CrontabCommander implements CrontabCommanderInterface
{
    public function make(): ShellCommandInterface
    {
        return new ShellCommand('crontab');
    }
}
