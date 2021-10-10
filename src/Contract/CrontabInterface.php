<?php

namespace ArtARTs36\Crontab\Contract;

use ArtARTs36\Crontab\Data\Task;

interface CrontabInterface
{
    /**
     * Get all Tasks from crontab
     * @return array<Task>
     */
    public function getAll(): array;

    /**
     * Add Task to crontab
     * @param Task|array<Task> $task
     */
    public function add($task): void;

    /**
     * Remove all tasks of crontab
     */
    public function removeAll(): void;

    /**
     * Remove task of crontab
     */
    public function remove(Task $task): void;

    /**
     * @param array<Task> $tasks
     */
    public function set(array $tasks): void;
}
