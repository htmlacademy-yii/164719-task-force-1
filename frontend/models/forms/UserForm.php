<?php

namespace frontend\models\forms;
use yii\base\Model;

class UserForm extends Model {
    public $categories;
    public $additional;
    public $name;

    public function rules()
    {
        return parent::rules(); // TODO: Change the autogenerated stub
    }
}
