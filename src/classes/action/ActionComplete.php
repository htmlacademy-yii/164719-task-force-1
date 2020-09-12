<?php

namespace taskForce\classes\action;


class ActionComplete extends Action
{
    public function __construct()
    {
        $this->actionName = 'Выполнено';
        $this->innerName = 'complete';
	    $this->class = 'complete';

    }

    public function checkAccess(int $executorId, int $clientId, int $currentUserId): bool
    {
        if ($clientId == $currentUserId) {
            return true;
        }
        return false;
    }


    public function getInnerName(): string
    {
        return $this->innerName;
    }


    public function getPublicName(): string
    {
        return $this->actionName;
    }
}
