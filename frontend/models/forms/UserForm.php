<?php

namespace frontend\models\forms;
use yii\base\Model;

class UserForm extends Model {
    public $categories;
	public $additional;
	public $name;
	public $online;
	public $isFree;
	public $review;
	public $favorite;

//    public function rules()
//    {
//        return parent::rules(); // TODO: Change the autogenerated stub
//    }

    public function attributeLabels()
    {
	    return [
		    	'online' => 'Сейчас онлайн',
			    'isFree' => 'Сейчас свободен',
			    'review' => 'Есть отзывы',
			    'favorite' => 'В избранном',
			    'name' => 'Поиск по имени'
	    ];
    }
}
