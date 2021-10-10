<?php

namespace ArtARTs36\Crontab\Exception;

use Throwable;

class CrontabForUserNotFoundException extends CrontabException
{
    public $failedUser;

    public function __construct(string $user, int $code = 0, Throwable $previous = null)
    {
        $this->failedUser = $user;

        $message = 'crontab: no crontab for '. $user;

        parent::__construct($message, $code, $previous);
    }
}
