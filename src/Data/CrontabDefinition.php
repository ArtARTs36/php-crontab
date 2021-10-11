<?php

namespace ArtARTs36\Crontab\Data;

class CrontabDefinition implements \IteratorAggregate
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
}
