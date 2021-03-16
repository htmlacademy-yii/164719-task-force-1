<?php
/**
 * @var yii\web\View $this
 * @var array $detail
 * @var string $dateOfBirth
 */

use yii\helpers\Html;
use yii\helpers\Url;
$this->title = Html::encode($detail->user->name);
?>
<main class="page-main">
    <div class="main-container page-container">
        <section class="content-view">
            <div class="user__card-wrapper">


                <div class="user__card">
                    <img src="<?= $detail->user_pic; ?>" width="120" height="120" alt="Аватар пользователя">
                    <div class="content-view__headline">
                        <h1><?= Html::encode($detail->user->name) ?></h1>
                        <p><?= $detail->address ?>, <?= $dateOfBirth; ?> лет</p>
                        <div class="profile-mini__name five-stars__rate">
                            <span></span><span></span><span></span><span></span><span class="star-disabled"></span>
                            <b>4.25</b>
                        </div>
                        <b class="done-task">Выполнил 5 заказов</b><b class="done-review">Получил 6 отзывов</b>
                    </div>
                    <div class="content-view__headline user__card-bookmark user__card-bookmark--current">
                        <span>Был на сайте 25 минут назад</span>
                        <a href="#"><b></b></a>
                    </div>
                </div>
                <div class="content-view__description">
                    <p><?php
                        if ($detail->about) {
                            echo $detail->about;
                        }
                        ?></p>
                </div>
                <div class="user__card-general-information">
                    <div class="user__card-info">
                        <h3 class="content-view__h3">Специализации</h3>
                        <div class="link-specialization">
                            <?php
                            foreach ($detail->userCategories as $item) {
                                print '<a href="#" class="link-regular">' . $item->category->name . '</a>';
                            }
                            ?>
                        </div>
                        <h3 class="content-view__h3">Контакты</h3>
                        <?php
                        //  показывает, когда не выбрано ни одной категории в настройках акк пользователя
                        // когда чекбокс показывать только заказчику (заказчик - пользователь, у которого не выбрано ни одной категории)
                        if ($detail->siteSettings->show_contacts_client && !count($detail->userCategories)):?>
                        <div class="user__card-link">
                            <a class="user__card-link--tel link-regular" href="#"><?= $detail->phone?></a>
                            <a class="user__card-link--email link-regular" href="#"><?= $detail->user->email?></a>
                            <a class="user__card-link--skype link-regular" href="#"><?= $detail->skype?></a>
                        </div>
                        <?php endif;?>
                    </div>
                    <div class="user__card-photo">
                        <h3 class="content-view__h3">Фото работ</h3>

                        <?php foreach ($detail->portfolioPhoto as $item):?>
                            <a href="<?= $item->url;?>"><img src="<?= $item->url;?>" width="85" height="86" alt="<?= Html::encode($item->title);?>"></a>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
            <div class="content-view__feedback">
                <h2>Отзывы <span><?php
                        if (count($detail->review)) {
                            echo count($detail->review);
                        } else {
                            echo '0';
                        } ?></span></h2>
                <div class="content-view__feedback-wrapper reviews-wrapper">
                    <?php foreach ($detail->review as $item):?>
                    <div class="feedback-card__reviews">
                        <p class="link-task link">Задание <a href="<?= Url::to(['./task/view/'. $item->task->id])?>" class="link-regular">«<?= Html::encode($item->task->name)?>»</a></p>
                        <div class="card__review">
                            <a href="<?= Url::to(['./user/view/'. $item->userInfo->user_id])?>"><img src="<?= $item->userInfo->user_pic?>" width="55" height="54"></a>
                            <div class="feedback-card__reviews-content">
                                <p class="link-name link"><a href="<?= Url::to(['./user/view/'. $item->userInfo->user_id])?>" class="link-regular"><?= Html::encode($item->user->name);?></a></p>
                                <p class="review-text">
                                    <?= Html::encode($item->description);?>
                                </p>
                            </div>
                            <div class="card__review-rate">
                                <p class="five-rate big-rate"><?= $item->rating;?><span></span></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>
        </section>
        <section class="connect-desk">
            <div class="connect-desk__chat">

            </div>
        </section>
    </div>
</main>
