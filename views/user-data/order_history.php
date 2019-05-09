<?php

use frontend\components\AddressType;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use webview\components\Snackbar;

/* @var $this yii\web\View */
/* @var $modelTransactionSession core\models\TransactionSession */
/* @var $pagination yii\data\Pagination */
/* @var $startItem int */
/* @var $endItem int */
/* @var $totalCount int */

Pjax::begin([
    'enablePushState' => false,
    'linkSelector' => '#pagination-order-history a',
    'options' => ['id' => 'pjax-order-history-container'],
    'timeout' => 7000,
]);

$linkPager = LinkPager::widget([
    'pagination' => $pagination,
    'maxButtonCount' => 5,
    'prevPageLabel' => false,
    'nextPageLabel' => false,
    'firstPageLabel' => '<i class="aicon aicon-icon-left-angle-semantic"></i>',
    'lastPageLabel' => '<i class="aicon aicon-icon-right-angle-semantic"></i>',
    'options' => ['id' => 'pagination-order-history', 'class' => 'pagination'],
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


<div class="row order-history-container" style="position: relative;">

	<div class="overlay" style="display: none;"></div>
	<div class="loading-img" style="display: none;"></div>

    <?php
    if (!empty($modelTransactionSession)):

        foreach ($modelTransactionSession as $dataTransactionSession):

            $img = (!empty($dataTransactionSession['business']['businessImages']) ? $dataTransactionSession['business']['businessImages'][0]['image'] : '') . '&w=88&h=88';

            $btnDetail = Html::a('<i class="aicon aicon-icon-search-semantic"></i> Detail', ['user/detail-order-history', 'id' => $dataTransactionSession['id']], [
                'class' => 'btn btn-raised btn-default btn-small btn-round'
            ]);

            $btnReorder = Html::a($dataTransactionSession['is_closed'] ? '<i class="aicon aicon-icon-online-ordering"></i> ' . \Yii::t('app', 'Reorder') : '<i class="aicon aicon-inspection_checklist"></i> ' . \Yii::t('app', 'Confirmation'), ['user-action/reorder'], [
                'class' => 'btn btn-raised btn-default btn-small btn-round btn-reorder',
                'data-id' => $dataTransactionSession['id']
            ]);

            $urlBusinessDetail = ['page/detail', 'id' => $dataTransactionSession['business']['id']];

            $grandTotal = $dataTransactionSession['total_price'] - $dataTransactionSession['discount_value']; ?>

        	<div class="col-12">
        		<div class="row mb-10">
                    <div class="col-sm-7 col-12">
                        <div class="widget-posts-image image-order-history">
                            <?= Html::a(Html::img(\Yii::$app->params['endPointLoadImage'] . 'registry-business?image=' . $img, ['class' => 'rounded']), $urlBusinessDetail) ?>
                        </div>
                    	<small>
                    		<?= \Yii::$app->formatter->asDate($dataTransactionSession['created_at'], 'long') . ', ' . \Yii::$app->formatter->asTime($dataTransactionSession['created_at'], 'short') ?>
                		</small>
                    	<br>
                        	<?= Html::a($dataTransactionSession['business']['name'], $urlBusinessDetail) ?>
                        <br>
                        <small>
                            <?= AddressType::widget(['businessLocation' => $dataTransactionSession['business']['businessLocation']]); ?>
                        </small>
                    </div>
                </div>
                <div class="row mb-10">
                	<div class="col-sm-6 col-12">
                		Grand Total : <?= \Yii::$app->formatter->asCurrency($grandTotal < 0 ? 0 : $grandTotal) ?> | <i class="aicon aicon-checkbox-checked <?= $dataTransactionSession['is_closed'] ? 'text-success' : 'text-danger' ?>"></i>
                	</div>
                	<div class="col-sm-6 text-right d-none d-sm-block d-md-none">
                		<ul class="list-inline list-review mt-0 mb-0">
                            <li class="list-inline-item"><?= $btnDetail ?></li>
                            <li class="list-inline-item"><?= $btnReorder ?></li>
                        </ul>
                	</div>
                	<div class="col-12 d-block d-sm-none">
                		<ul class="list-inline list-review mt-10 mb-0">
                            <li class="list-inline-item"><?= $btnDetail ?></li>
                            <li class="list-inline-item"><?= $btnReorder ?></li>
                        </ul>
                	</div>
                </div>

                <hr class="divider-w mt-10 mb-10">
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
Snackbar::widget();

$this->registerJs(Snackbar::messageResponse(), View::POS_HEAD);

$jscript = '
    $(".btn-reorder").on("click", function() {

        $.ajax({
            cache: false,
            type: "POST",
            url: $(this).attr("href"),
            data: {
                "id": $(this).data("id"),
            },
            success: function(response) {

                if (!response.success) {

                    messageResponse(response.icon, response.title, response.text, response.type);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");
            }
        });

        return false;
    });

    $(".total-order-history").html(' . $totalCount . ');

    $("#pjax-order-history-container").off("pjax:send");
    $("#pjax-order-history-container").on("pjax:send", function() {

        $(".order-history-container").children(".overlay").show();
        $(".order-history-container").children(".loading-img").show();
    });

    $("#pjax-order-history-container").off("pjax:complete");
    $("#pjax-order-history-container").on("pjax:complete", function() {

        $(".order-history-container").children(".overlay").hide();
        $(".order-history-container").children(".loading-img").hide();
    });

    $("#pjax-order-history-container").off("pjax:end");
    $("#pjax-order-history-container").on("pjax:end", function() {

        $(".order-history-container").bootstrapMaterialDesign();
    });

    $("#pjax-order-history-container").off("pjax:error");
    $("#pjax-order-history-container").on("pjax:error", function(event) {

        event.preventDefault();
    });
';

$this->registerJs($jscript);

Pjax::end(); ?>