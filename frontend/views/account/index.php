<?php
/**
 * @var array $model
 * @var array $userInfo
 */

use frontend\models\Categories;
use frontend\models\UserCategory;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$formatter = \Yii::$app->formatter;
//var_dump($userInfo);
?>

<main class="page-main">
    <div class="main-container page-container">
        <section class="account__redaction-wrapper">
            <h1>Редактирование настроек профиля</h1>

            <?php $form = ActiveForm::begin([
                'id' => 'account',
                'enableClientValidation' => true,
                'options' => [
                    'enctype' => 'multipart/form-data',
                ],
                'fieldConfig' => [
                    'template' => "{label}{input}<span>{hint}</span><span style='color: red'>{error}</span>",
                ]

            ]) ?>

                <div class="account__redaction-section">
                    <h3 class="div-line">Настройки аккаунта</h3>
                    <div class="account__redaction-section-wrapper">
                        <div class="account__redaction-avatar">
                            <img src="./img/man-glasses.jpg" width="156" height="156">
                            <input type="file" name="avatar" id="upload-avatar">
                            <label for="upload-avatar" class="link-regular">Сменить аватар</label>
                        </div>
                        <div class="account__redaction">
                            <div class="field-container account__input account__input--name">
                                <label for="200">Ваше имя</label>
                                <input class="input textarea" id="200" name="" placeholder="<?= $userInfo->user->name?>" disabled value="<?= $userInfo->user->name?>">
                            </div>
                            <div class="field-container account__input account__input--email">


                                <?= $form->field($model, 'email', [
                                    'options' => ['tag' => false]
                                ])->textInput([
                                    'class' => 'input textarea',
                                    'placeholder' => 'DenisT@bk.ru',
                                    'value' => $userInfo->user->email
                                ]) ?>
                            </div>
                            <div class="field-container account__input account__input--address">

                                <?= $form->field($model, 'address', [
                                    'options' => ['tag' => false]
                                ])->textInput([
                                    'class' => 'input textarea',
                                    'placeholder' => 'Санкт-Петербург, Московский район',
                                    'value' => $userInfo->city->city
                                ]) ?>


                            </div>
                            <div class="field-container account__input account__input--date">

                                <?= $form->field($model, 'date_of_birth', [
                                    'options' => ['tag' => false]
                                ])->widget(\yii\jui\DatePicker::className(),
                                    [ 'dateFormat' => 'php:m/d/Y',
                                        'clientOptions' => [
                                            'changeYear' => true,
                                            'changeMonth' => true,
                                            'yearRange' => '-50:-12',
                                            'altFormat' => 'yy-mm-dd',

                                        ]],['placeholder' => 'dd.mm.yyyy'])
                                    ->textInput([
                                        'placeholder' => \Yii::t('app', '15.08.1987'),
                                        'class'=> 'input-middle input input-date',
                                        'value' => $formatter->asDate($userInfo->date_birth, 'php:d.m.Y'),
                                    ]) ;?>
                            </div>
                            <div class="field-container account__input account__input--info">
                                <?= $form->field($model, 'about_myself', [
                                    'labelOptions' => [
                                        'style' => 'display: block;'
                                    ]
                                ])->textarea([
                                    'class' => 'input textarea',
                                    'style' => 'width: 100%;box-sizing: border-box',
                                    'rows' => 7,
                                    'placeholder' => 'Place your text',
                                    'value' => $userInfo->about
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                    <h3 class="div-line">Выберите свои специализации</h3>
                    <div class="account__redaction-section-wrapper">
                        <div class="search-task__categories account_checkbox--bottom">

                                <?php
                                $cat_list = Categories::find()->select(['name'])->orderBy('id')->asArray()->all();
                                $result = ArrayHelper::getColumn($cat_list, 'name');

                                function getCheckboxList($index, $label, $name, $checked, $value): string
                                {

                                        $cat_list = UserCategory::find()->where(['user_id' => Yii::$app->user->getId()])->all();
                                        $result = ArrayHelper::getColumn($cat_list, 'category_id');

                                        foreach ($result as  $item) {

                                            if ($value == $item-1) { // тк массив начинается с 0
                                                $checked = 'checked';
                                            }
                                        }

                                        return "<label for='{$index}' class='checkbox__legend'>
                                                            <input class=\"visually-hidden checkbox__input\" id='{$index}' type='checkbox' name='{$name}' value='{$value}' $checked >
                                                            <span>{$label}</span>
							                            </label>";
                                }


                                echo $form->field($model, 'user_category')
                                    ->checkboxList($result,
                                        [
                                            'item' => 'getCheckboxList',
                                            'class'=> 'search-task__categories account_checkbox--bottom'])->label(false) ?>

                        </div>
                    </div>
                    <h3 class="div-line">Безопасность</h3>
                    <div class="account__redaction-section-wrapper account__redaction">
                        <div class="field-container account__input">

                            <?= $form->field($model, 'password', [
                                'options' => ['tag' => false]
                            ])->passwordInput([
                                'class' => 'input textarea',

                            ]) ?>

                        </div>
                        <div class="field-container account__input">

                            <?= $form->field($model, 're_password', [
                                'options' => ['tag' => false]
                            ])->passwordInput([
                                'class' => 'input textarea',
                            ]) ?>

                        </div>
                    </div>

                    <h3 class="div-line">Фото работ</h3>

                    <div class="account__redaction-section-wrapper account__redaction">
                        <span class="dropzone">Выбрать фотографии</span>
                    </div>

                    <h3 class="div-line">Контакты</h3>
                    <div class="account__redaction-section-wrapper account__redaction">
                        <div class="field-container account__input">

                            <?= $form->field($model, 'phone', [
                                'options' => ['tag' => false]
                            ])->textInput([
                                'class' => 'input textarea',
                                'placeholder' => '8 (555) 187 44 87',
                                'value' => $userInfo->phone
                            ]) ?>
                        </div>
                        <div class="field-container account__input">

                            <?= $form->field($model, 'skype', [
                                'options' => ['tag' => false]
                            ])->textInput([
                                'class' => 'input textarea',
                                'placeholder' => 'DenisT',
                                'value' => $userInfo->skype
                            ]) ?>
                        </div>
                        <div class="field-container account__input">


                            <?= $form->field($model, 'another_messenger', [
                                'options' => ['tag' => false]
                            ])->textInput([
                                'class' => 'input textarea',
                                'placeholder' => '@DenisT',
                                'value' => $userInfo->telegram
                            ]) ?>
                        </div>
                    </div>
                    <h3 class="div-line">Настройки сайта</h3>
                    <h4>Уведомления</h4>
                    <div class="account__redaction-section-wrapper account_section--bottom">
                        <div class="search-task__categories account_checkbox--bottom">


                            <?= $form->field($model, 'show_new_messages', [
                                'labelOptions' => [
                                    'class' => 'checkbox__legend'
                                ],
                                'template' => '<label class="checkbox__legend">
                                {input}
                                <span>Новое сообщение</span>
                            </label>',

                            ])->checkbox([
                                'class' => 'visually-hidden checkbox__input',
                                'checked' => $userInfo->user->siteSettings->new_message ? true: false
                            ],
                                false)->label(false); ?>
                            <?= $form->field($model, 'show_actions_of_task', [
                                'labelOptions' => [
                                    'class' => 'checkbox__legend'
                                ],
                                'template' => '<label class="checkbox__legend">
                                {input}
                                <span>Действия по заданию</span>
                            </label>',

                            ])->checkbox([
                                'class' => 'visually-hidden checkbox__input',
                                'checked' => $userInfo->user->siteSettings->actions_in_task ? true: false
                            ],
                                false)->label(false); ?>

                            <?= $form->field($model, 'show_new_review', [
                                'labelOptions' => [
                                    'class' => 'checkbox__legend'
                                ],
                                'template' => '<label class="checkbox__legend">
                                {input}
                                <span>Новый отзыв</span>
                            </label>',

                            ])->checkbox([
                                'class' => 'visually-hidden checkbox__input',
                                'checked' => $userInfo->user->siteSettings->new_review ? true: false
                            ],
                                false)->label(false); ?>



                        </div>
                        <div class="search-task__categories account_checkbox account_checkbox--secrecy">


                            <?= $form->field($model, 'show_my_contacts_customer', [
                                'labelOptions' => [
                                    'class' => 'checkbox__legend'
                                ],
                                'template' => '<label class="checkbox__legend">
                                {input}
                                <span>Показывать мои контакты только заказчику</span>
                            </label>',

                            ])->checkbox([
                                'class' => 'visually-hidden checkbox__input',
                                'checked' => $userInfo->user->siteSettings->show_contacts_client ?  true : false
                            ],
                                false)->label(false); ?>

                            <?= $form->field($model, 'hide_account', [
                                'labelOptions' => [
                                    'class' => 'checkbox__legend'
                                ],
                                'template' => '<label class="checkbox__legend">
                                {input}
                                <span>Не показывать мой профиль</span>
                            </label>',

                            ])->checkbox([
                                'class' => 'visually-hidden checkbox__input',
                                'checked' => $userInfo->user->siteSettings->show_profile ? true : false
                            ],
                                false)->label(false); ?>
                        </div>
                    </div>
                </div>

                <?= Html::submitButton('Сохранить изменения', ['class' => 'button']) ?>

                <?php ActiveForm::end(); ?>
        </section>
    </div>
</main>
