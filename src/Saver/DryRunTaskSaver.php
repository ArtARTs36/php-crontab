<?php

namespace ArtARTs36\Crontab\Saver;

use ArtARTs36\Crontab\Contract\TaskSaverInterface;
use ArtARTs36\Crontab\Data\CrontabDefinition;

final class DryRunTaskSaver implements TaskSaverInterface
{
    public function save(CrontabDefinition $definition): void
    {
        // null
    }
}
