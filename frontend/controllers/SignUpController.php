<?php

namespace frontend\controllers;


use frontend\models\Cities;
use frontend\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\widgets\ActiveForm;

class SignupController extends Controller
{

    public function actionIndex()
	{
	    if(!Yii::$app->user->isGuest) {
            return $this->redirect('/tasks');
        }
        $model = new User();
        $model->load(\Yii::$app->request->post());


        if (Yii::$app->request->isAjax) {
            return ActiveForm::validate($model);
        }

		if (Yii::$app->request->getIsPost()) {

            if ($model->validate()) {
                $model->setPassword($model->password);
                return $model->save() && $this->goHome();
            }
		}

		return $this->render('index', [
			'model' => $model
		]);
	}
}
