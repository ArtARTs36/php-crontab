<?php

namespace ArtARTs36\Crontab\Saver;

use ArtARTs36\Crontab\Contract\TaskSaverInterface;

class DryRunTaskSaver implements TaskSaverInterface
{
    public function save(array $tasks): void
    {
        // null
    }
}
