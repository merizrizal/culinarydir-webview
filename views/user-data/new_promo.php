<?php

use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $pagination yii\data\Pagination */
/* @var $startItem int */
/* @var $endItem int */
/* @var $totalCount int */
/* @var $modelBusinessPromo core\models\BusinessPromo */

Pjax::begin([
    'enablePushState' => false,
    'linkSelector' => '#pagination-new-promo a',
    'options' => ['id' => 'pjax-new-promo-container'],
    'timeout' => 7000,
]);

$linkPager = LinkPager::widget([
    'pagination' => $pagination,
    'maxButtonCount' => 5,
    'prevPageLabel' => false,
    'nextPageLabel' => false,
    'firstPageLabel' => '<i class="aicon aicon-icon-left-angle-semantic"></i>',
    'lastPageLabel' => '<i class="aicon aicon-icon-right-angle-semantic"></i>',
    'options' => ['id' => 'pagination-new-promo', 'class' => 'pagination'],
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

<div class="row new-promo-container" style="position: relative;">

	<div class="overlay" style="display: none;"></div>
	<div class="loading-img" style="display: none;"></div>

    <?php
    if (!empty($modelBusinessPromo)):

        foreach ($modelBusinessPromo as $dataBusinessPromo):

            $urlBusinessDetail = [
                'page/detail',
                '#' => 'special',
                'id' => $dataBusinessPromo['business']['id']
            ]; ?>

            <div class="col-sm-6 col-12 mb-10">
                <div class="card box user">
                    <div class="row">
                        <div class="col-12">

                            <?php
                            $img = (!empty($dataBusinessPromo['image']) ? $dataBusinessPromo['image'] : '') . '&w=565&h=350';
                            echo Html::a(Html::img(\Yii::$app->params['endPointLoadImage'] . 'business-promo?image=' . $img), $urlBusinessDetail); ?>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="short-desc">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="m-0">
                                            <?= Html::a($dataBusinessPromo['business']['name'], $urlBusinessDetail); ?>
                                        </h5>
									</div>
                                </div>
                                <div class="row">
                                	<div class="col-12">
                                        <small class="m-0">
                                            <?= $dataBusinessPromo['title']; ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php
        endforeach;
    endif; ?>

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
    $(".total-new-promo").html("' . $totalCount . '");

    $("#pjax-new-promo-container").off("pjax:send");
    $("#pjax-new-promo-container").on("pjax:send", function() {

        $(".new-promo-container").children(".overlay").show();
        $(".new-promo-container").children(".loading-img").show();
    });

    $("#pjax-new-promo-container").off("pjax:complete");
    $("#pjax-new-promo-container").on("pjax:complete", function() {

        $(".new-promo-container").children(".overlay").hide();
        $(".new-promo-container").children(".loading-img").hide();
    });

    $("#pjax-new-promo-container").off("pjax:end");
    $("#pjax-new-promo-container").on("pjax:end", function() {

        $(".new-promo-container").bootstrapMaterialDesign();
    });

    $("#pjax-new-promo-container").off("pjax:error");
    $("#pjax-new-promo-container").on("pjax:error", function (event) {

        event.preventDefault();
    });
';

$this->registerJs($jscript);

Pjax::end(); ?>