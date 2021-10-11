<?php

namespace ArtARTs36\Crontab\Contract;

use ArtARTs36\Crontab\Data\CrontabDefinition;
use ArtARTs36\Crontab\Data\Task;

interface CrontabInterface
{
    /**
     * Get all Tasks from crontab
     */
    public function getAll(): CrontabDefinition;

    /**
     * Add Task to crontab
     * @param Task|array<Task> $task
     */
    public function add($task): CrontabDefinition;

    /**
     * Remove all tasks of crontab
     */
    public function removeAll(): void;

    /**
     * Remove task of crontab
     */
    public function remove(Task $task): CrontabDefinition;

    /**
     * @param array<Task> $tasks
     */
    public function set(array $tasks): CrontabDefinition;
}
