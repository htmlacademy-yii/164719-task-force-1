<?php

namespace taskForce\classes\action;

class ActionCancel extends Action
{

    public $executorId, $clientId, $currentUserId;

    public function getName()
    {
        if ($this->getRole($this->executorId, $this->clientId, $this->currentUserId)) {
            return self::STATUS_CANCEL;
        }
        return null;
    }

    public function getRole($executorId, $clientId, $currentUserId)
    {
        if ($clientId == $currentUserId) {
            return true;
        }
        return false;
    }

    public function getInnerName()
    {
        return self::ACTION_CANCEL;
    }
}
