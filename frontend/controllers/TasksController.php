<?php

namespace frontend\controllers;

use frontend\models\forms\TaskForm;
use frontend\models\Task;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


class TasksController extends Controller
{
	public function actionIndex()
	{
		$model = new TaskForm();
		$tasks = Task::find()
			->where(['status' => 'new'])
			->orderBy('created_at DESC')->all();

		if (Yii::$app->request->getIsPost()) {

			$model->load(Yii::$app->request->post());
			if (!$model->validate()) {
				$errors = $model->getErrors();
			}
			$request = Yii::$app->request;
			$formContent = $request->post('TaskForm');

			$query = Task::find()
				->orderBy('created_at ASC');


			foreach ($formContent as $key => $item) {

				if ($item) {
					switch ($key) {
						//Группа чекбоксов «Категории» выводит все категории, существующие на сайте.
						case 'categories':
							$query->joinWith('category c')->where(['c.id' => $item]);
							break;

						//Выпадающий список «Период» добавляет к условию фильтрации диапазон времени, когда было создано задание.
						//Доступные значения: за день, за неделю, за месяц, за всё время.
						case 'period':
							switch ($item) {
								case 'day':
									$query->andWhere(['>=', 'created_at', date("Y-m-d H:i:s", strtotime("-1 day +3 hour"))]);
									break;
								case 'week':
									$query->andWhere(['>=', 'created_at', date("Y-m-d H:i:s", strtotime("-1 week +3 hour"))]);
									break;

								case 'month':
									$query->andWhere(['>=', 'created_at', date("Y-m-d H:i:s", strtotime("-1 month +3 hour"))]);
									break;
								case 'all':
									$query->andWhere(['>=', 'created_at', date("Y-m-d H:i:s", strtotime("last year +3 hour"))]);
									break;
							}
							break;

						//«Без откликов» — добавляет к условию фильтрации показ заданий только без откликов исполнителей;
						case 'noResponse':
							$query->joinWith('response r');
							$query->andWhere(['r.task_id' => null]);
							break;

						//«Удалённая работа» — добавляет к условию фильтрации показ заданий без географической привязки.
						case 'remote':
							$query->andWhere(['task.city_id' => null]);
							break;
						case 'search':
							$query->andWhere(['LIKE', 'task.name', $item]);
							break;
					}
				}
			}
			$tasks = $query->all();
		}
		return $this->render('index', [
			'tasks' => $tasks,
			'model' => $model
		]);
	}

	public function actionView($id = null)
	{
		$detail = Task::findOne($id);

		if (empty($detail)) {
			throw new NotFoundHttpException("Задание с № $id не найдено");
		}

		return $this->render('view', [
			'detail' => $detail,
		]);
	}

}
