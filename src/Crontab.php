<?php

namespace ArtARTs36\Crontab;

use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;

final class Crontab extends AbstractCrontab
{
    protected function makeCommand(): ShellCommandInterface
    {
        return $this->builder->make('crontab');
    }
}
