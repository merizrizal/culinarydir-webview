<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $modelBusinessPromo core\models\BusinessPromo */

common\assets\OwlCarouselAsset::register($this);

$this->title = $modelBusinessPromo['title'];

$ogUrl = [
    'page/detail-business-promo',
    'id' => $modelBusinessPromo['id'],
    'uniqueName' => $modelBusinessPromo['business']['unique_name']
];

$ogImage = Yii::$app->params['endPointLoadImage'] . 'business-promo?image=&w=490&h=276';

if (!empty($modelBusinessPromo['image'])) {

    $ogImage = Yii::$app->params['endPointLoadImage'] . 'business-promo?image=' . $modelBusinessPromo['image'];
}

$ogDescription = !empty($modelBusinessPromo['short_description']) ? $modelBusinessPromo['short_description'] : $this->title;

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => 'asik, makan, kuliner, bandung, jakarta'
]);

$this->registerMetaTag([
    'name' => 'description',
    'content' => $ogDescription
]);

$this->registerMetaTag([
    'property' => 'og:url',
    'content' => Yii::$app->urlManager->createAbsoluteUrl($ogUrl)
]);

$this->registerMetaTag([
    'property' => 'og:type',
    'content' => 'website'
]);

$this->registerMetaTag([
    'property' => 'og:title',
    'content' => !empty($modelBusinessPromo['title']) ? $modelBusinessPromo['title'] : 'Promo di Asikmakan'
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
        <div class="detail place-detail">
            <div class="row mb-20">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="card view">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#photo" aria-controls="photo" role="tab" data-toggle="tab"><i class="aicon aicon-camera1"></i> <?= Yii::t('app', 'Photo') ?></a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="photo" role="tabpanel">
                                        <div class="row">
                                            <div class="col-12 text-center">
                                            	<div class="business-promo-image-container owl-carousel owl-theme">
                                                    <?= Html::img(null, ['class' => 'owl-lazy', 'data-src' => Yii::$app->params['endPointLoadImage'] . 'business-promo?image=' . $modelBusinessPromo['image']]); ?>
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
                                    <div class="row">
                                        <div class="col-12">
                                            <h4 class="m-0"><?= $modelBusinessPromo['title']; ?></h4>
                                        </div>
                                    </div>
                                </div>

                                <hr class="divider-w">

								<?php
								$promoRange = Yii::t('app', 'Valid from {dateStart} until {dateEnd}', [
								    'dateStart' => Yii::$app->formatter->asDate($modelBusinessPromo['date_start'], 'medium'),
								    'dateEnd' => Yii::$app->formatter->asDate($modelBusinessPromo['date_end'], 'medium')
								]); ?>

                                <div class="box-content">
                                    <div class="row">
                                        <div class="col-12">
                                            <h4 class="m-0 d-none d-sm-block d-md-none"><small><?= $promoRange ?></small></h4>
                                            <small class="d-block d-sm-none"><?= $promoRange ?></small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <?= $modelBusinessPromo['description'] ?>
                                        </div>
                                    </div>
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
$jscript = '
    $(".business-promo-image-container").owlCarousel({
        lazyLoad: true,
        items: 1,
        mouseDrag: false,
        touchDrag: false
    });
';

$this->registerJs($jscript); ?>