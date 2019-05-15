<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\web\View;
use webview\components\Snackbar;

/* @var $this yii\web\View */
/* @var $modelUser core\models\User */
/* @var $queryParams array */

$this->title = $modelUser['full_name'];

$ogDescription = $modelUser['full_name'] . ' telah bergabung Asikmakan.com sejak ' . \Yii::$app->formatter->asDate($modelUser['created_at'], 'long');

if (!empty($modelUser['userPerson']['person']['about_me'])) {

    $ogDescription = $modelUser['userPerson']['person']['about_me'];
}

$ogDescription = StringHelper::truncate(preg_replace('/[\r\n]+/','' , $ogDescription), 300);
$ogImage = \Yii::$app->params['endPointLoadImage'] . 'user?image=default-avatar.png';

if (!empty($modelUser['image'])) {

    $ogImage = \Yii::$app->params['endPointLoadImage'] . 'user?image=' . $modelUser['image'];
} ?>

<div class="main bg-main">
    <section>
        <div class="detail user-profile">
            <div class="row user-profile-header mb-50">
                <div class="col-12">

                	<?php
                	$img = \Yii::$app->params['endPointLoadImage'] . 'user?image=default-avatar.png';

                	if (!empty($modelUser['image'])) {

                	    $img = \Yii::$app->params['endPointLoadImage'] . 'user?image=' . $modelUser['image'] . '&w=160&h=160';
                	}

                	$userName = '<h5>' . $modelUser['full_name'] . ' </h5>'; ?>

                    <div class="row mt-10 d-none d-sm-block d-md-none">
                        <div class="col-sm-8 offset-sm-2">
                            <div class="row ">
                                <div class="widget-posts-image">
                                    <?= Html::img($img, ['class' => 'img-fluid rounded-circle']) ?>
                                </div>
                                <div class="widget-posts-body user-profile-identity">
                                    <?= $userName ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row d-block d-sm-none">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12 text-center">
                                	<?= Html::img($img, ['class' => 'img-fluid rounded-circle']) ?>
                                </div>
                            </div>
                            <div class="row mt-20">
                                <div class="col-12 text-center">
                                    <?= $userName ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card view">
                        <ul class="nav nav-tabs mb-10" role="tablist">
                            <li class="nav-item">
                                <a id="journey-tab" class="nav-link active text-center" href="#view-journey" aria-controls="view-journey" role="tab" data-toggle="tab">
                                    <i class="aicon aicon-icon-been-there-fill-1 aicon-1-5x"></i><br>
                                    Journey
                                </a>
                            </li>
                            <li class="nav-item">
                                <a id="photo-tab" class="nav-link text-center" href="#view-photo" aria-controls="view-photo" role="tab" data-toggle="tab">
                                    <i class="aicon aicon-camera1 aicon-1-5x"></i><span class="badge total-user-photo"></span><br>
                                    <?= \Yii::t('app', 'Photo') ?>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade show active p-0" id="view-journey" aria-labelledby="journey-tab">

                                <?= $this->render('user/_journey', [
                                    'username' => $modelUser['username'],
                                    'queryParams' => $queryParams,
                                ]) ?>

                            </div>

                            <div role="tabpanel" class="tab-pane fade p-0" id="view-photo" aria-labelledby="photo-tab">

                                <?= $this->render('user/_photo', [
                                    'username' => $modelUser['username'],
                                    'queryParams' => $queryParams,
                                ]) ?>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

</div>

<?php
$this->registerCssFile(\Yii::$app->homeUrl . 'lib/Magnific-Popup/dist/magnific-popup.css', ['depends' => 'yii\web\JqueryAsset']);

$this->registerJsFile(\Yii::$app->homeUrl . 'lib/Magnific-Popup/dist/jquery.magnific-popup.js', ['depends' => 'yii\web\JqueryAsset']);

Snackbar::widget();
frontend\components\RatingColor::widget();
frontend\components\Readmore::widget();
frontend\components\FacebookShare::widget();

$this->registerJs(Snackbar::messageResponse(), View::POS_HEAD); ?>