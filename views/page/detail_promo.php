<?php

use webview\components\Snackbar;
use yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $modelPromo core\models\BusinessPromo */
/* @var $claimInfo String */
/* @var $countUserClaimed Integer */

common\assets\OwlCarouselAsset::register($this);

$this->title = $modelPromo['title'];

$ogTitle = $modelPromo['title'] . ' ' . $modelPromo['type'] . ' sebesar ' . \Yii::$app->formatter->asCurrency($modelPromo['amount']);

$ogUrl = Yii::$app->params['rootUrl'] . 'page/detail-promo?id=' . $modelPromo['id'];

$ogImage = \Yii::$app->params['endPointLoadImage'] . 'promo?image=&w=490&h=276';

if (!empty($modelPromo['image'])) {

    $ogImage = \Yii::$app->params['endPointLoadImage'] . 'promo?image=' . $modelPromo['image'];
}

$ogDescription = $this->title; ?>

<div class="main bg-main">
    <section>
        <div class="detail place-detail">
            <div class="row mb-20">
                <div class="col-12">
					<div class="row">
						<div class="col-12">
							<div class="card view">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#photo" aria-controls="photo" role="tab" data-toggle="tab"><i class="aicon aicon-camera1"></i> <?= \Yii::t('app', 'Photo') ?></a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="photo" role="tabpanel">
                                        <div class="row">
                                            <div class="col-12 text-center">
                                            	<div class="promo-image-container owl-carousel owl-theme">
                                                    <?= Html::img(null, ['class' => 'owl-lazy', 'data-src' => \Yii::$app->params['endPointLoadImage'] . 'promo?image=' . $modelPromo['image']]); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
					</div>

                    <div class="row mt-20">
                        <div class="col-12">
                            <div class="card box">
                                <div class="box-title">
                                    <div class="row mt-10">
                                        <div class="col-8">
                                        	<h5 class="mt-0 mb-0"><?= $modelPromo['title']; ?></h5>
                                        </div>
                                        <div class="col-sm-4 text-right d-none d-sm-block d-md-none">
                                        	<?= Html::button('<i class="aicon aicon-share1"></i> Share', ['class' => 'btn btn-raised btn-default btn-standard btn-round share-promo']); ?>
                                        </div>
                                        <div class="col-4 text-right d-block d-sm-none">
                                        	<?= Html::button('<i class="aicon aicon-share1"></i> Share', ['class' => 'btn btn-raised btn-default btn-standard btn-small btn-round share-promo']); ?>
                                        </div>
                                    </div>
                                </div>

                                <hr class="divider-w">

                                <div class="box-content">
                                	<div class="overlay" style="display:none"></div>
                                	<div class="loading-img" style="display:none"></div>

                                	<div class="row mb-10">
                                		<div class="col-12">
                                			<i class="aicon aicon-price-tag"></i> <?= $modelPromo['type'] ?>
                                			<br>
                                			<i class="aicon aicon-rupiah"></i> <?= \Yii::$app->formatter->asCurrency($modelPromo['amount']) ?>
                                			<br>
                                			<i class="aicon aicon-clock"></i>
                                			<strong>

                                    			<?= \Yii::t('app', 'Valid from {dateStart} until {dateEnd}', [
                                                    'dateStart' => \Yii::$app->formatter->asDate($modelPromo['date_start'], 'medium'),
                                                    'dateEnd' => \Yii::$app->formatter->asDate($modelPromo['date_end'], 'medium')
                                                ]); ?>

                                            </strong>
                                            <br>
                                            <i class="aicon aicon-checkmark"></i><span class="claim-info"> <?= $claimInfo ?></span>
                                		</div>
                                	</div>

                                	<hr class="divider-w">

                                    <div class="row mt-10">
                                        <div class="col-12">
                                            <?= $modelPromo['description'] ?>
                                        </div>
                                    </div>

                                    <?= Html::a('Claim Promo', ['action/claim-promo'], [
                                        'class' => 'btn btn-raised btn-block btn-round btn-danger claim-promo-btn',
                                        'data-promo' => $modelPromo['id']
                                    ]) ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
Snackbar::widget();

frontend\components\FacebookShare::widget();

$this->registerJs(Snackbar::messageResponse(), View::POS_HEAD);

$jscript = '
    $(".promo-image-container").owlCarousel({
        lazyLoad: true,
        items: 1,
        mouseDrag: false,
        touchDrag: false
    });

    $(".claim-promo-btn").on("click", function() {

        var thisObj = $(this);
        var countUserClaimed = ' . $countUserClaimed . ';

        $.ajax({
            cache: false,
            type: "POST",
            data: {
                "promo_id": thisObj.data("promo")
            },
            url: thisObj.attr("href"),
            beforeSend: function(xhr) {

                thisObj.siblings(".overlay").show();
                thisObj.siblings(".loading-img").show();
            },
            success: function(response) {

                if (response.success) {

                    if (countUserClaimed == 0) {

                        $(".claim-info").html(" ' . \Yii::t('app', 'You have claimed this promo') . '");
                    } else {

                        $(".claim-info").html(" ' . \Yii::t('app', 'You and {userClaimed} other user have claimed this promo', ['userClaimed' => $countUserClaimed]) . '");
                    }
                }

                messageResponse(response.icon, response.title, response.message, response.type);

                thisObj.siblings(".overlay").hide();
                thisObj.siblings(".loading-img").hide();
            },
            error: function(xhr, ajaxOptions, thrownError) {

                messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");

                thisObj.siblings(".overlay").hide();
                thisObj.siblings(".loading-img").hide();
            }
        });

        return false;
    });

    $(".share-promo").on("click", function() {

        facebookShare({
            ogUrl: "' . $ogUrl . '",
            ogTitle: "' . $ogTitle . '",
            ogDescription: "' . addslashes($ogDescription) . '",
            ogImage: "' . $ogImage . '",
            type: "Promo"
        });

        return false;
    });
';

$this->registerJs($jscript); ?>