<?php

namespace frontend\controllers;

use frontend\models\Categories;
use Yii;
use frontend\models\forms\UserForm;
use frontend\models\UserInfo;
use yii\web\Controller;

class UsersController extends Controller
{
    public function actionIndex()
    {
        $users = UserInfo::find()
            ->with(['userCategories.category'])
            ->all();

        $categories = Categories::find()->all();

        $filter = new UserForm();

        if ($filter->load(Yii::$app->request->post())) {
            $request = Yii::$app->request;
	        $formContent = $request->post('UserForm');
	        var_dump($formContent);
        }

        return $this->render('index', [
            'users' => $users,
            'filter' => $filter,
            'categories' => $categories
        ]);
    }

    public function actionDetail($id)
    {
        $detail = UserInfo::findOne($id);



        return $this->render('detail', [
            'detail' => $detail
        ]);
    }

    public function actionFilter($ids) {
//        $users = UserInfo::find()
//            ->with(['userCategories.category'])
//            ->all();
//
//        $filter = new UserForm();
//        $categories = Categories::find()->all();
//
//        if (Yii::$app->request->getIsPost()) {
//            $filter->load(Yii::$app->request->post());
//            var_dump($filter);
//        }
//
//        return $this->render('index', [
//            'users' => $users,
//            'filter' => $filter,
//            'categories' => $categories
//        ]);
    }

}
