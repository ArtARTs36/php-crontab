<?php

namespace ArtARTs36\Crontab\Contract;

use ArtARTs36\Crontab\Data\Task;

interface TaskSaverInterface
{
    /**
     * @param array<Task> $tasks
     */
    public function save(array $tasks): void;
}
