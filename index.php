<?php

use taskForce\classes\Task;

use taskForce\classes\action\ActionCancel;
use taskForce\classes\action\ActionComplete;
use taskForce\classes\action\ActionNew;
use taskForce\classes\action\ActionRefuse;
use taskForce\classes\action\ActionResponse;

use taskForce\exceptions\TaskException;
use taskForce\exceptions\RoleException;

require_once 'vendor/autoload.php';


$task = new Task('new');
$task1 = new Task('progress');


$actionNew =  new ActionNew();
$actionCancel = new ActionCancel();
$actionComplete = new ActionComplete();
$actionRefuse = new ActionRefuse();
$actionResponse = new ActionResponse();

try {
    $task->getAvailableActions('executor');
    $task1->getAvailableActions('client');
    $task1->getAvailableActions('');

}
catch (TaskException $e) {
    printf('Error: ' . $e->getMessage());
}
catch (RoleException $e) {
    printf('Error: ' . $e->getMessage());
}

//var_dump($task->getAvailableActions($actionResponse));
//print ('<br>');
//var_dump($task1->getAvailableActions());
//
//
//assert($actionComplete->checkAccess(233, 233, 233) == $actionComplete->getName(), print('Выполнено'));
//assert($actionCancel->checkAccess(233, 233, 233) == $actionCancel->getName(), print('Отменено'));
//assert($actionResponse->checkAccess(233, 233, 233) == $actionResponse->getName(), print('В работе'));
//assert($actionRefuse->checkAccess(233, 233, 233) == $actionRefuse->getName(), print('Провалено'));
//assert($actionNew->checkAccess(233, 233, 233) == $actionNew->getName(), print('Новое'));


