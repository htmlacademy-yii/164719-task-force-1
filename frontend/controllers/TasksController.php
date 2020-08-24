<?php

namespace frontend\controllers;

use frontend\models\Attachment;
use frontend\models\Categories;
use frontend\models\forms\CreateTaskForm;
use frontend\models\forms\TaskForm;
use frontend\models\Task;
use frontend\models\User;
use frontend\models\UserCategory;
use frontend\models\UserInfo;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;


class TasksController extends SecuredController
{
	public function actionIndex()
	{
		$model = new TaskForm();
		$tasks = Task::find()
			->with('category', 'cities')
			->where(['status' => \taskForce\classes\Task::STATUS_NEW])
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
		$count_tasks = Task::find()
			->where(['author_id'=> $detail->author_id])
			->count('author_id');


		$user_created_at = User::findOne($detail->author_id);

		return $this->render('view', [
			'detail' => $detail,
			'count_tasks' => $count_tasks,
			'user' => $user_created_at
		]);
	}

	public function actionCreate() {
        // По умолчанию, после регистрации пользователю присваивается роль «Заказчик». Чтобы стать исполнителем необходимо
        // отметить хотя бы одну специализацию у себя в профиле.
        // Соответственно, при отмене всех галочек пользователь вновь становится исполнителем.
	    $speciality = UserCategory::find()->where(['user_id' => Yii::$app->user->id])->column();
        //проверяем, является ли пользователь заказчиком

        if(count($speciality) > 0) {
            return $this->redirect('/tasks');
        }


		$model = new CreateTaskForm();
		$task = new Task();
        $categories = Categories::find()->select(['name', 'id'])->indexBy('id')->column();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->files = UploadedFile::getInstances($model, 'files');

            $task->name = $model->name;
            $task->description = $model->description;
            $task->status = 'new';
            $task->price = $model->price;
            $task->category_id = $model->category;
            $task->author_id = Yii::$app->user->getIdentity()->id;
            $task->execution_date = $model->execution_date;

            $task->save(false);

            $paths = [];
            $names = [];

            foreach ($model->files as $item) {
                $names [] = $item->name;
            }

            if(count($model->files)) {
                foreach ($model->upload() as $key => $item) {
                    $attachment = new Attachment();
                    $paths [] = $item;
                    $attachment->task_id = $task->id;
                    $attachment->name = $names[$key];
                    $attachment->url = $item;
                    $attachment->save(false);
                }

            }
            return $this->redirect('/tasks');
		}
		if(!$model->validate()) {
			$model->getErrors();
		}



		return $this->render('create', [
			'model' => $model,
            'categories' => $categories
		]);
	}

}
