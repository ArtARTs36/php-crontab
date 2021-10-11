<?php

namespace ArtARTs36\Crontab\Contract;

use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;

interface CrontabCommanderInterface
{
    public function make(): ShellCommandInterface;
}
