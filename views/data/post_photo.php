<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $pagination yii\data\Pagination */
/* @var $startItem int */
/* @var $endItem int */
/* @var $totalCount int */
/* @var $modelUserPostMain core\models\UserPostMain */

Pjax::begin([
    'enablePushState' => false,
    'linkSelector' => '#pagination-photo a',
    'options' => ['id' => 'pjax-photo-container'],
    'timeout' => 7000,
]);

$linkPager = LinkPager::widget([
    'pagination' => $pagination,
    'maxButtonCount' => 5,
    'prevPageLabel' => false,
    'nextPageLabel' => false,
    'firstPageLabel' => '<i class="aicon aicon-icon-left-angle-semantic"></i>',
    'lastPageLabel' => '<i class="aicon aicon-icon-right-angle-semantic"></i>',
    'options' => ['id' => 'pagination-photo', 'class' => 'pagination'],
]); ?>

<div class="row mt-10 mb-20">
    <div class="col-tab-6 col-xs-12 mb-10">

    	<?= Yii::t('app', 'Showing {startItem} - {endItem} of {totalCount} results', ['startItem' => $startItem, 'endItem' => $endItem, 'totalCount' => $totalCount]) ?>

    </div>
    <div class="col-tab-6 visible-tab text-right">

        <?= $linkPager; ?>

    </div>
    <div class="col-xs-12 visible-xs">

        <?= $linkPager; ?>

    </div>
</div>

<div class="row" style="position: relative;">
    <div class="col-xs-12 post-photo-container">

    	<div class="overlay" style="display: none;"></div>
		<div class="loading-img" style="display: none;"></div>

        <ul class="works-grid works-grid-gut works-grid-4" id="photo-gallery">

            <?php
            if (!empty($modelUserPostMain)):

                foreach ($modelUserPostMain as $dataUserPostMain): ?>

                    <li class="work-item">
                        <div class="gallery-item place-gallery">
                            <div class="gallery-image">
                                <div class="work-image">
                                    <?= Html::img(Yii::$app->params['endPointLoadImage'] . 'user-post?image=' . $dataUserPostMain['image'] . '&w=200&h=200', ['class' => 'img-component', 'data-id' => $dataUserPostMain['id']]) ?>
                                </div>
                                <div class="work-caption">
                                    <div class="work-descr photo-caption hidden-xs"><?= !empty($dataUserPostMain['text']) ? $dataUserPostMain['text'] : '' ?></div>
                                    <div class="work-descr">

                                        <?php
                                        echo Html::a('<i class="aicon aicon-zoomin"></i>', Yii::$app->params['endPointLoadImage'] . 'user-post?image=' . $dataUserPostMain['image'], ['class' => 'btn btn-raised btn-danger btn-small btn-xs btn-circle show-image']) . '&nbsp';

                                        echo Html::a('<i class="aicon aicon-share1"></i>', Yii::$app->urlManager->createAbsoluteUrl([
                                            'page/photo',
                                            'id' => $dataUserPostMain['id'],
                                            'uniqueName' => $dataUserPostMain['business']['unique_name'],
                                        ]), ['class' => 'btn btn-danger btn-raised btn-small btn-xs btn-circle share-image-trigger']) . '&nbsp'; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                <?php
                endforeach;
            endif; ?>

        </ul>
    </div>
</div>

<div class="row mt-20 mb-10">
    <div class="col-tab-6 col-xs-12 mb-10">

        <?= Yii::t('app', 'Showing {startItem} - {endItem} of {totalCount} results', ['startItem' => $startItem, 'endItem' => $endItem, 'totalCount' => $totalCount]) ?>

    </div>
    <div class="col-tab-6 visible-tab text-right">

        <?= $linkPager; ?>

    </div>
    <div class="col-xs-12 visible-xs">

        <?= $linkPager; ?>

    </div>
</div>

<?php
$jscript = '
    $("#photo-gallery .place-gallery").magnificPopup({

        delegate: "a.show-image",
        type: "image",
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0,1]
        },
        image: {
            titleSrc: "title",
            tError: "The image could not be loaded."
        }
    });

    $(".total-photo").html("' . $totalCount . '");

    $("#pjax-photo-container").off("pjax:send");
    $("#pjax-photo-container").on("pjax:send", function() {

        $(".post-photo-container").children(".overlay").show();
        $(".post-photo-container").children(".loading-img").show();
    });

    $("#pjax-photo-container").off("pjax:complete");
    $("#pjax-photo-container").on("pjax:complete", function() {

        $(".post-photo-container").children(".overlay").hide();
        $(".post-photo-container").children(".loading-img").hide();
    });

    $("#pjax-photo-container").off("pjax:error");
    $("#pjax-photo-container").on("pjax:error", function (event) {

        event.preventDefault();
    });
';

$this->registerJs($jscript);

Pjax::end(); ?>