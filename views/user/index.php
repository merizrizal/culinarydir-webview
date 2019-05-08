<?php

use webview\components\Snackbar;
use yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $modelUser core\models\User */
/* @var $queryParams array */

$this->title = $modelUser['full_name'];

$ogDescription = $modelUser['full_name'] . ' telah bergabung Asikmakan.com sejak ' . \Yii::$app->formatter->asDate($modelUser['created_at'], 'long');
$ogImage = \Yii::$app->params['endPointLoadImage'] . 'user?image=default-avatar.png';

if (!empty($modelUser['userPerson']['person']['about_me'])) {

    $ogDescription = $modelUser['userPerson']['person']['about_me'];
}

if (!empty($modelUser['image'])) {

    $ogImage = \Yii::$app->params['endPointLoadImage'] . 'user?image=' . $modelUser['image'];
}

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => 'asik, makan, kuliner, bandung, jakarta'
]);

$this->registerMetaTag([
    'name' => 'description',
    'content' => 'Temukan Bisnis Kuliner Favorit Anda di Asikmakan.com'
]);

$this->registerMetaTag([
    'property' => 'og:url',
    'content' => \Yii::$app->urlManager->createAbsoluteUrl(['user/user-profile', 'user' => $modelUser['username']])
]);

$this->registerMetaTag([
    'property' => 'og:type',
    'content' => 'website'
]);

$this->registerMetaTag([
    'property' => 'og:title',
    'content' => $modelUser['full_name']
]);

$this->registerMetaTag([
    'property' => 'og:description',
    'content' => $ogDescription
]);

$this->registerMetaTag([
    'property' => 'og:image',
    'content' => $ogImage
]); ?>

<div class="main bg-main">
    <section>
        <div class="detail user-profile">
            <div class="row mb-50">
                <div class="col-12">

                	<?php
                	$img = \Yii::$app->params['endPointLoadImage'] . 'user?image=default-avatar.png';

                	if (!empty($modelUser['image'])) {

                	    $img = \Yii::$app->params['endPointLoadImage'] . 'user?image=' . $modelUser['image'] . '&w=160&h=160';
                	}

                	$userName = '
                        <h5>' .
                            $modelUser['full_name'] . '<br>
                            <small>' . $modelUser['email'] . '</small>
                        </h5>
                    ';

                    $btnProfile =
                        '<div class="dropdown">' .
                            Html::a('<i class="aicon aicon-pencil1"></i> ' . \Yii::t('app', 'Update Profile'), ['user/update-profile'], ['class' => 'btn btn-raised btn-danger btn-round btn-standard']) . '
                            <a class="btn btn-xs btn-round btn-danger btn-standard btn-raised dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"></a>
                            <div class="dropdown-menu">' .
                                Html::a('<i class="aicon aicon-key1"></i>&nbsp;' . \Yii::t('app', 'Change Password'), ['user/change-password'], ['class' => 'dropdown-item']) .
                               	Html::a('<i class="aicon aicon-logout"></i>&nbsp;' . \Yii::t('app', 'Logout'), ['site/logout'], ['class' => 'dropdown-item']) . '
                            </div>
                        </div>
                    '; ?>

                    <div class="row mt-10 d-none d-sm-block d-md-none">
                        <div class="col-sm-8 offset-sm-2">
                            <div class="widget-posts-image">
                                <?= Html::img($img, ['class' => 'img-fluid rounded-circle']) ?>
                            </div>
                            <div class="widget-posts-body">
                                <?= $userName ?>
                                <?= $btnProfile ?>
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
                            <div class="row mt-10">
                                <div class="col-12 text-center">
                                    <?= $userName ?>
                                    <?= $btnProfile ?>
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
                            <li class="nav-item d-none d-sm-block d-md-none">
                                <a id="history-tab" class="nav-link text-center" href="#view-order-history" aria-controls="view-order-history" role="tab" data-toggle="tab">
                                    <i class="aicon aicon-history aicon-1-5x"></i><span class="badge total-order-history">0</span><br>
                                    <?= \Yii::t('app', 'Order History') ?>
                                </a>
                            </li>
                            <li class="nav-item d-none d-sm-block d-md-none">
                                <a id="promo-tab" class="nav-link text-center" href="#view-new-promo" aria-controls="view-new-promo" role="tab" data-toggle="tab">
                                    <i class="aicon aicon-promo aicon-1-5x"></i><span class="badge total-new-promo"></span><br>
                                    <?= \Yii::t('app', 'New Promo') ?>
                                </a>
                            </li>
                            <li class="nav-item dropdown d-block d-sm-none">
                                <a class="nav-link text-center dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="aicon aicon-more aicon-1-5x"></i><br>
                                    More
                                </a>
                                <div class="dropdown-menu pull-right p-0">
                                    <a id="history-tab" href="#view-order-history" class="dropdown-item" aria-controls="view-order-history" role="tab" data-toggle="tab">
                                    	<h6><i class="aicon aicon-history"></i> <?= \Yii::t('app', 'Order History') ?> (<span class="total-order-history">0</span>)</h6>
                                	</a>
                                    <a id="promo-tab" href="#view-new-promo" class="dropdown-item" aria-controls="view-new-promo" role="tab" data-toggle="tab">
                                    	<h6><i class="aicon aicon-promo"></i> <?= \Yii::t('app', 'New Promo') ?> (<span class="total-new-promo"></span>)</h6>
                                	</a>
                                </div>
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

                            <div role="tabpanel" class="tab-pane fade p-0" id="view-order-history" aria-labelledby="history-tab">

                                <?= $this->render('user/_order_history', [
                                    'username' => $modelUser['username'],
                                    'queryParams' => $queryParams,
                                ]) ?>

                            </div>

                            <div role="tabpanel" class="tab-pane fade p-0" id="view-new-promo" aria-labelledby="promo-tab">
                                <?= $this->render('user/_new_promo') ?>
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
webview\components\RatingColor::widget();
frontend\components\FacebookShare::widget();
frontend\components\Readmore::widget();

$this->registerJs(Snackbar::messageResponse(), View::POS_HEAD);

$this->params['beforeEndBody'][] = function() {

    echo '
        <div id="modal-confirmation" class="modal fade in" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">' . \Yii::t('app', 'Confirmation') . '</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ' . \Yii::t('app', 'Are you sure want to delete this?') . '
                    </div>
                    <div class="modal-footer">
                        <button id="btn-delete" class="btn btn-danger btn-raised" type="button">' . \Yii::t('app', 'Delete') .'</button><br>
                        <button class="btn btn-raised" data-dismiss="modal" type="button">' . \Yii::t('app', 'Cancel') .'</button>
                    </div>
                </div>
            </div>
        </div>
    ';
}; ?>