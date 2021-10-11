<?php

namespace ArtARTs36\Crontab\Data;

class CrontabDefinition implements \Countable, \IteratorAggregate
{
    protected $tasks;

    /**
     * @param array<Task> $tasks
     */
    public function __construct(array $tasks)
    {
        $this->tasks = $tasks;
    }

    public function diff(self $definition): self
    {
        return new self(array_diff($this->tasks, $definition->tasks));
    }

    /**
     * @return iterable<Task>
     */
    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->tasks);
    }

    public function count(): int
    {
        return count($this->tasks);
    }

    /**
     * @return Task[]
     */
    public function tasks(): array
    {
        return $this->tasks;
    }

    public function asFile(): string
    {
        return implode("\n", array_map(function (Task $task) {
            return $task->expression . ' ' . $task->commandLine;
        }, $this->tasks));
    }

    public function add(array $tasks): self
    {
        $existsTasks = $this->tasks;

        array_push($existsTasks, ...$tasks);

        return new self($existsTasks);
    }
}
