<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $pagination yii\data\Pagination */
/* @var $startItem int */
/* @var $endItem int */
/* @var $totalCount int */
/* @var $modelUserPostMainPhoto core\models\UserPostMain */

Pjax::begin([
    'enablePushState' => false,
    'linkSelector' => '#pagination-user-photo a',
    'options' => ['id' => 'pjax-user-photo-container'],
    'timeout' => 7000,
]);

$linkPager = LinkPager::widget([
    'pagination' => $pagination,
    'maxButtonCount' => 5,
    'prevPageLabel' => false,
    'nextPageLabel' => false,
    'firstPageLabel' => '<i class="aicon aicon-icon-left-angle-semantic"></i>',
    'lastPageLabel' => '<i class="aicon aicon-icon-right-angle-semantic"></i>',
    'options' => ['id' => 'pagination-user-photo', 'class' => 'pagination'],
]); ?>

<div class="row mt-10 mb-20">
    <div class="col-sm-6 col-12 mb-10">

    	<?= \Yii::t('app', 'Showing {startItem} - {endItem} of {totalCount} results', ['startItem' => $startItem, 'endItem' => $endItem, 'totalCount' => $totalCount]) ?>

    </div>
    <div class="col-sm-6 d-none d-sm-block d-md-none text-right">

        <?= $linkPager; ?>

    </div>
    <div class="col-12 d-block d-sm-none">

        <?= $linkPager; ?>

    </div>
</div>

<div class="row" style="position: relative;">
    <div class="col-12 user-post-photo-container">

		<div class="overlay" style="display: none;"></div>
		<div class="loading-img" style="display: none;"></div>

        <ul class="works-grid works-grid-gut works-grid-4" id="photo-gallery">

            <?php
            if (!empty($modelUserPostMainPhoto)):

                foreach ($modelUserPostMainPhoto as $dataUserPostMainPhoto):

                    $urlPhoto = \Yii::$app->params['rootUrl'] . 'photo/' . $dataUserPostMainPhoto['id'] . '/di/' . $dataUserPostMainPhoto['business']['unique_name']; ?>

                    <li class="work-item">

                        <?= Html::hiddenInput('business_name', $dataUserPostMainPhoto['business']['name'], ['class' => 'business-name']) ?>

                        <div class="gallery-item place-gallery">
                            <div class="gallery-image">
                                <div class="work-image">
                                    <?= Html::img(\Yii::$app->params['endPointLoadImage'] . 'user-post?image=' . $dataUserPostMainPhoto['image'] . '&w=200&h=200', ['class' => 'img-component', 'data-id' => $dataUserPostMainPhoto['id']]) ?>
                                </div>
                                <div class="work-caption">
                                    <div class="work-descr photo-caption d-none d-sm-block d-md-none"><?= !empty($dataUserPostMainPhoto['text']) ? $dataUserPostMainPhoto['text'] : '' ?></div>
                                    <div class="work-descr">

                                    	<?php
                                    	echo Html::a('<i class="aicon aicon-zoomin"></i>', \Yii::$app->params['endPointLoadImage'] . 'user-post?image=' . $dataUserPostMainPhoto['image'], ['class' => 'btn btn-raised btn-danger btn-small btn-xs btn-circle show-image']) . '&nbsp';
                                    	echo Html::a('<i class="aicon aicon-share1"></i>', $urlPhoto, ['class' => 'btn btn-raised btn-danger btn-small btn-xs btn-circle share-image-trigger']) . '&nbsp';

                                        if (!empty(\Yii::$app->user->getIdentity()->id) && \Yii::$app->user->getIdentity()->id == $dataUserPostMainPhoto['user_id']) {

                                            echo Html::a('<i class="aicon aicon-icon-trash"></i>', ['user-action/delete-photo', 'id' => $dataUserPostMainPhoto['id']], ['class' => 'btn btn-raised btn-danger btn-small btn-xs btn-circle delete-image']);
                                        } ?>

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
    <div class="col-sm-6 col-12 mb-10">

    	<?= \Yii::t('app', 'Showing {startItem} - {endItem} of {totalCount} results', ['startItem' => $startItem, 'endItem' => $endItem, 'totalCount' => $totalCount]) ?>

    </div>
    <div class="col-sm-6 d-none d-sm-block d-md-none text-right">

        <?= $linkPager; ?>

    </div>
    <div class="col-12 d-block d-sm-none">

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

    $(".total-user-photo").html("' . $totalCount . '");

    $("#pjax-user-photo-container").off("pjax:send");
    $("#pjax-user-photo-container").on("pjax:send", function() {

        $(".user-post-photo-container").children(".overlay").show();
        $(".user-post-photo-container").children(".loading-img").show();
    });

    $("#pjax-user-photo-container").off("pjax:complete");
    $("#pjax-user-photo-container").on("pjax:complete", function() {

        $(".user-post-photo-container").children(".overlay").hide();
        $(".user-post-photo-container").children(".loading-img").hide();
    });

    $("#pjax-user-photo-container").off("pjax:end");
    $("#pjax-user-photo-container").on("pjax:end", function() {

        $(".user-post-photo-container").bootstrapMaterialDesign();
    });

    $("#pjax-user-photo-container").off("pjax:error");
    $("#pjax-user-photo-container").on("pjax:error", function (event) {

        event.preventDefault();
    });
';

$this->registerJs($jscript);

Pjax::end(); ?>