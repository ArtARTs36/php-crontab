<?php

namespace ArtARTs36\Crontab\Data;

class Task
{
    public $expression;

    public $commandLine;

    public function __construct(string $expression, string $commandLine)
    {
        $this->expression = $expression;
        $this->commandLine = $commandLine;
    }

    public function equals(self $task): bool
    {
        return $this->expression === $task->expression && $this->commandLine === $task->commandLine;
    }
}
