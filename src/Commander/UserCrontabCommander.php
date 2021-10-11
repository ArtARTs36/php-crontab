<?php

namespace ArtARTs36\Crontab\Commander;

use ArtARTs36\Crontab\Contract\CrontabCommanderInterface;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\ShellCommand;

class UserCrontabCommander implements CrontabCommanderInterface
{
    private $user;

    public function __construct(string $user)
    {
        $this->user = $user;
    }

    public function make(): ShellCommandInterface
    {
        return (new ShellCommand('crontab'))->addCutOption('u')->addArgument($this->user);
    }
}
