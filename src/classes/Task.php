<?php
declare(strict_types=1);

namespace taskForce\classes;


use taskForce\classes\action\ActionCancel;
use taskForce\classes\action\ActionComplete;
use taskForce\classes\action\ActionDone;
use taskForce\classes\action\ActionRefuse;
use taskForce\classes\action\ActionRequest;
use taskForce\classes\action\ActionResponse;
use taskForce\exceptions\RoleException;
use taskForce\exceptions\TaskException;

class Task
{

	const STATUS_NEW = 'new'; // Новое	Задание опубликовано, исполнитель ещё не найден
	const STATUS_PROGRESS = 'progress'; // В работе	Заказчик выбрал исполнителя для задания

	const STATUS_CANCEL = 'cancelled';  // Отменено	Заказчик отменил задание
	const STATUS_COMPLETE = 'completed'; // Выполнено	Заказчик отметил задание как выполненное
	const STATUS_FAIL = 'failed'; // Провалено	Исполнитель отказался от выполнения задания

	const ROLE_EXECUTOR = 2; // исполнитель
	const ROLE_CLIENT = 1;  //Заказчик


	// actions
	const ACTION_RESPONSE = 'response'; // Откликнуться - действие исполнителя
	const ACTION_REFUSE = 'refuse'; // Отказаться - действие исполнителя
	const ACTION_CANCEL = 'cancel';  // Завершить - действие заказчика

	const ACTION_CONFIRM = 'confirm'; // Подтвердить - действие заказчика
	const ACTION_DONE = 'done'; //


	public $actionCancel, $actionComplete, $actionRefuse, $actionResponse, $actionDone, $actionRequest;

	public $mapStatus = [ // вернуть статус на русском языке
		self::STATUS_NEW => 'Новое',
		self::STATUS_PROGRESS => 'В работе',
		self::STATUS_CANCEL => 'Отменено',
		self::STATUS_COMPLETE => 'Выполнено',
		self::STATUS_FAIL => 'Провалено',
	];


	public $status = ''; // статус


	public function __construct(string $status) // конструктор
	{
		if (!isset($this->mapStatus[$status])) {
			throw new TaskException('Неверно передан статус');
		}


		$this->status = $status;

		$this->actionCancel = new ActionCancel();
		$this->actionComplete = new ActionComplete();
		$this->actionRefuse = new ActionRefuse();
		$this->actionResponse = new ActionResponse();
		$this->actionDone = new ActionDone();
		$this->actionRequest = new ActionRequest();

	}

	public function getAvailableActions(string $role, int $author, int $currentUserId, bool $responded): array
	{
		$actions = []; // пустой массив действий


		//Чтобы стать исполнителем необходимо отметить хотя бы одну специализацию у себя в профиле
		// исполнитель
		if (empty($role)) {
			throw new RoleException('Не передано имя роли в параметрах');
		}
		if (!isset($this->status)) {
			throw new RoleException('Не передан статус');
		}
		//$role == 1 - Заказчик
		//$role == 2 - Исполнитель


		//действия для заказчика
		if($author === $currentUserId) {
			if(self::STATUS_NEW or self::STATUS_PROGRESS == $this->status) {
				$actions = [$this->actionDone];
			}
		} elseif ($author !== $currentUserId) {
			if (self::STATUS_NEW == $this->status) {
				$actions = [$this->actionResponse, $this->actionCancel];
			} elseif (self::STATUS_PROGRESS == $this->status) {
				$actions = [];
			}

		}

		return $actions;
	}
	// он должен возвращать список доступных классов-действий
	// в зависимости от статуса задания и ID пользователя


	public function getAvailableActionsClient(string $role): array
	{
		$actions = []; // пустой массив действий


		//Чтобы стать исполнителем необходимо отметить хотя бы одну специализацию у себя в профиле
		// исполнитель
		if (empty($role)) {
			throw new RoleException('Не передано имя роли в параметрах');
		}

		if ($role == self::ROLE_CLIENT) {
			if ($this->status == self::STATUS_NEW ) {
				$actions = [$this->actionRequest, $this->actionRefuse];
			}
		}
		return $actions;

	}
}
