<?php

use frontend\components\AddressType;
use webview\components\Snackbar;
use yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $modelTransactionSession core\models\TransactionSession */

$this->title = \Yii::t('app', 'Order Details');

$urlBusinessDetail = ['page/detail', 'id' => $modelTransactionSession['business']['id']];

$img = (!empty($modelTransactionSession['business']['businessImages']) ? $modelTransactionSession['business']['businessImages'][0]['image'] : '') . '&w=88&h=88'; ?>

<div class="main bg-main">
    <section>
        <div class="detail user-profile order-history">
        	<div class="row">
                <div class="col-12">
                    <div class="card box">
                        <div class="box-content">
                    		<div class="row mt-10 mb-10">
                                <div class="col-sm-7 col-12">
                                    <div class="widget-posts-image image-order-history">
                                        <?= Html::a(Html::img(\Yii::$app->params['endPointLoadImage'] . 'registry-business?image=' . $img, ['class' => 'rounded']), $urlBusinessDetail) ?>
                                    </div>
                                	<small>
                                		<?= \Yii::$app->formatter->asDate($modelTransactionSession['created_at'], 'long') . ', ' . \Yii::$app->formatter->asTime($modelTransactionSession['created_at'], 'short') ?>
                            		</small>
                                	<br>
                                		<?= Html::a($modelTransactionSession['business']['name'], $urlBusinessDetail) ?>
                                    <br>
                                    <small>
                                        <?= AddressType::widget(['businessLocation' => $modelTransactionSession['business']['businessLocation']]); ?>
                                    </small>
                                </div>
                            </div>

                            <hr class="divider-w mb-10">

                            <p><strong><?= \Yii::t('app', 'Order Details') ?></strong></p>

                            <div class="row mb-10">
                            	<div class="col-12">

                            		<?php
                            		foreach ($modelTransactionSession['transactionItems'] as $dataTransactionItem):

                            		    $amountPrice = $dataTransactionItem['amount'] . ' x ' . \Yii::$app->formatter->asCurrency($dataTransactionItem['price']); ?>

                                		<div class="row mb-10">
                                			<div class="col-12">
                                    			<div class="row">
                                                    <div class="col-sm-8 col-12">
                                                        <strong><?= $dataTransactionItem['businessProduct']['name'] ?></strong>
                                                    </div>
                                                    <div class="col-sm-4 text-right d-none d-sm-block d-md-none">
                                                        <strong><?= $amountPrice ?></strong>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                	<div class="col-12">
                                                		<small><?= $dataTransactionItem['note'] ?></small>
                                            		</div>
                                            		<div class="col-12 d-block d-sm-none">
                                                    	<strong><?= $amountPrice ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

	   	                            <?php
                            		endforeach; ?>

                            	</div>
                            </div>

                            <hr class="divider-w mb-10">

                            <div class="row">
                            	<div class="col-sm-8 col-12 mb-10">

                            		<?php
                            		$grandTotal = $modelTransactionSession['total_price'] - $modelTransactionSession['discount_value'];
                            		$checkSymbol = ' | <i class="aicon aicon-checkbox-checked ' . ($modelTransactionSession['status'] != 'Open' ? "text-success" : "text-danger") . '"></i>';

                            		if (!empty($modelTransactionSession['discount_value'])) {

                            		    echo 'Subtotal : ' . \Yii::$app->formatter->asCurrency($modelTransactionSession['total_price']) . '
                                            <br>Promo : ' . \Yii::$app->formatter->asCurrency($modelTransactionSession['discount_value']) . '
                                            <br>Grand Total : ' . \Yii::$app->formatter->asCurrency($grandTotal < 0 ? 0 : $grandTotal) . $checkSymbol;
                            		} else {

                            		    echo 'Grand Total : ' . \Yii::$app->formatter->asCurrency($grandTotal) . $checkSymbol;
                            		} ?>

                            	</div>
                            	<div class="col-sm-4 col-12">

                            		<?= Html::a($modelTransactionSession['status'] != 'Open' ? \Yii::t('app', 'Reorder') : \Yii::t('app', 'Confirmation'), ['user-action/reorder'], [
                                        'class' => 'btn btn-raised btn-danger btn-block btn-round btn-reorder',
                                        'data-id' => $modelTransactionSession['id']
                                    ]); ?>

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

    $(".order-history").bootstrapMaterialDesign();
';

$this->registerJs($jscript); ?>
