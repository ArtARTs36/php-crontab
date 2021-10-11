<?php

namespace ArtARTs36\Crontab\Contract;

use ArtARTs36\Crontab\Data\CrontabDefinition;

interface TaskSaverInterface
{
    public function save(CrontabDefinition $definition): void;
}
