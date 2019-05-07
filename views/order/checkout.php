<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use webview\components\Snackbar;

/* @var $this yii\web\View */
/* @var $modelTransactionSession core\models\TransactionSession */
/* @var $modelTransactionSessionOrder core\models\TransactionSessionOrder */
/* @var $promoItemClaimed Array */
/* @var $dataOption Array */

$this->title = 'Checkout'; ?>

<div class="main bg-main">
    <section>
        <div class="detail checkout-order">
            <div class="row">
                <div class="col-12">
                    <div class="card box">

                        <hr class="divider-w">

                        <div class="box-content">

                        	<?php
                            $form = ActiveForm::begin([
                                'id' => 'checkout-form',
                                'action' => ['order/checkout'],
                                'fieldConfig' => [
                                    'template' => '{input}{error}',
                                ]
                            ]); ?>

                                <div class="row">
                                    <div class="col-12 order-list">

                                        <?php
                                        if (!empty($modelTransactionSession)):

                                            foreach ($modelTransactionSession['transactionItems'] as $dataTransactionItem):

                                                $notesField = Html::textInput('transaction_item_notes', $dataTransactionItem['note'], [
                                                    'class' => 'form-control transaction-item-notes',
                                                    'placeholder' => Yii::t('app', 'Note'),
                                                    'data-url' => Yii::$app->urlManager->createUrl(['order-action/save-notes'])
                                                ]);

                                                $amountField = Html::input('number', 'transaction_item_amount', $dataTransactionItem['amount'], [
                                                    'class' => 'transaction-item-amount',
                                                    'data-url' => \Yii::$app->urlManager->createUrl(['order-action/change-qty']),
                                                    'style' => 'width:100%',
                                                    'min' => 1,
                                                    'max' => 50
                                                ]); ?>

                                                <div class="business-menu-group">

                                                    <?= Html::hiddenInput('transaction_item_id', $dataTransactionItem['id'], ['class' => 'transaction-item-id']); ?>

                                                    <div class="business-menu mb-20 d-none d-sm-block d-md-none">
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                                <h5><?= $dataTransactionItem['businessProduct']['name'] ?></h5>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <h5><?= \Yii::$app->formatter->asCurrency($dataTransactionItem['price']) ?></h5>
                                                            </div>
                                                            <div class="col-sm-1 text-right">
                                                                <div class="overlay" style="display: none;"></div>
                                                                <?= Html::a('<i class="aicon aicon-cross"></i>', ['order-action/remove-item'], ['class' => 'remove-item']); ?>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <p class="mb-0"><?= $dataTransactionItem['businessProduct']['description'] ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-8">

                                                                <div class="overlay" style="display: none;"></div>
                                                                <div class="loading-text" style="display: none;"></div>

                                                                <?= $notesField ?>

                                                            </div>
                                                            <div class="col-sm-3 mt-10">

                                                                <div class="overlay" style="display: none;"></div>
                                                                <div class="loading-text" style="display: none;"></div>

                                                                <?= $amountField ?>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="business-menu mb-20 d-block d-sm-none">
                                                        <div class="row">
                                                            <div class="col-10">
                                                                <strong><?= $dataTransactionItem['businessProduct']['name'] ?></strong>
                                                            </div>
                                                            <div class="col-2 text-right">
                                                                <div class="overlay" style="display: none;"></div>
                                                                <?= Html::a('<i class="aicon aicon-cross"></i>', ['order-action/remove-item'], ['class' => 'remove-item']); ?>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <p class="mb-0"><?= $dataTransactionItem['businessProduct']['description'] ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12 mb-10">

                                                                <div class="overlay" style="display: none;"></div>
                                                                <div class="loading-text" style="display: none;"></div>

                                                                <?= $notesField ?>

                                                            </div>
                                                            <div class="col-7">
                                                                <strong><?= \Yii::$app->formatter->asCurrency($dataTransactionItem['price']) ?></strong>
                                                            </div>
                                                            <div class="col-5">

                                                                <div class="overlay" style="display: none;"></div>
                                                                <div class="loading-text" style="display: none;"></div>

                                                                <?= $amountField ?>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php
                                            endforeach; ?>

                                            <div class="row mt-40">
                                                <div class="promo-code-section">
                                                    <div class="col-12">
                                                        <?= \Yii::t('app', 'Got Promo Code?') ?>
                                                    </div>
                                                    <div class="col-12 mb-20">

                                                        <?= $form->field($modelTransactionSession, 'promo_item_id')->dropDownList(
                                                            ArrayHelper::map($promoItemClaimed, 'id',
                                                                function ($data) {

                                                                    return substr($data['id'], 0, 6);
                                                                }
                                                            ),
                                                            [
                                                                'prompt' => Yii::t('app', 'Promo Code'),
                                                                'class' => 'promo-code-field form-control',
                                                                'options' => $dataOption,
                                                                'style' => 'width:100%'
                                                            ]); ?>

                                                        <span class="text-danger"></span>

                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <table class="table checkout-table">
                                                        <tbody>
                                                        	<tr>
                                                                <th class="font-alt">Total</th>
                                                                <td class="total-price"><?= $modelTransactionSession['total_price'] ?></td>
                                                            </tr>
                                                            <tr class="promo-amount" style="display:none">
                                                                <th class="font-alt">Promo</th>
                                                                <td></td>
                                                            </tr>
                                                            <tr class="grand-total" style="display:none">
                                                                <th class="font-alt">Grand Total</th>
                                                                <td></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="order-online-form">
                                                <div class="row mt-30">
                                                    <div class="col-12">
                                                    	<h5 class="font-alt text-center"><?= \Yii::t('app', 'Delivery Methods') ?></h5>
                                                    	<hr>

                                                        <?php
                                                        if (!empty($modelTransactionSession['business']['businessDeliveries'])) {

                                                            echo $form->field($modelTransactionSessionOrder, 'business_delivery_id')->radioList(
                                                                ArrayHelper::map(
                                                                    $modelTransactionSession['business']['businessDeliveries'],
                                                                    'id',
                                                                    function ($data) use ($form, $modelTransactionSessionOrder) {

                                                                        return '
                                                                            <div class="row mb-20">
                                                                                <div class="col-12">' .

                                                                                    $form->field($modelTransactionSessionOrder, 'business_delivery_id', [
                                                                                        'template' => '<div class="radio">{input}{label}</div>',
                                                                                    ])->radio([
                                                                                        'label' => $data['deliveryMethod']['delivery_name'],
                                                                                        'value' => $data['id'],
                                                                                        'uncheck' => null
                                                                                    ])->error(false) .

                                                                                '</div>
                                                                                <div class="col-12">
                                                                                    <strong>' . $data['note'] . '</strong>
                                                                                </div>
                                                                                <div class="col-12">' .
                                                                                    $data['description'] . '
                                                                                </div>
                                                                            </div>';
                                                                    }
                                                                ),
                                                                [
                                                                    'item' => function ($index, $label, $name, $checked, $value) {

                                                                        return $label;
                                                                    }
                                                                ]);
                                                        } else {

                                                            echo \Yii::t('app', 'Currently there is no delivery method available in') . ' ' . $modelTransactionSession['business']['name'];
                                                        } ?>

                                                    </div>
                                                </div>

                                                <div class="row mt-30">
                                                    <div class="col-12">
                                                    	<h5 class="font-alt text-center"><?= \Yii::t('app', 'Payment Methods') ?></h5>
                                                    	<hr>

                                                        <?php
                                                        if (!empty($modelTransactionSession['business']['businessPayments'])) {

                                                            echo $form->field($modelTransactionSessionOrder, 'business_payment_id')->radioList(
                                                                ArrayHelper::map(
                                                                    $modelTransactionSession['business']['businessPayments'],
                                                                    'id',
                                                                    function ($data) use ($form, $modelTransactionSessionOrder) {

                                                                        return '
                                                                            <div class="row mb-20">
                                                                                <div class="col-12">' .

                                                                                    $form->field($modelTransactionSessionOrder, 'business_payment_id', [
                                                                                        'template' => '<div class="radio">{input}{label}</div>',
                                                                                    ])->radio([
                                                                                        'label' => $data['paymentMethod']['payment_name'],
                                                                                        'value' => $data['id'],
                                                                                        'uncheck' => null
                                                                                    ])->error(false) .

                                                                                '</div>
                                                                                <div class="col-12">' .
                                                                                    $data['description'] . '
                                                                                </div>
                                                                            </div>';
                                                                    }
                                                                ),
                                                                [
                                                                    'item' => function ($index, $label, $name, $checked, $value) {

                                                                        return $label;
                                                                    }
                                                                ]);

                                                            echo '<i>*' . \Yii::t('app', 'For transfer or online payments, please send a screenshot of proof of payment') . '</i>';
                                                        } else {

                                                            echo \Yii::t('app', 'Currently there is no payment method available in') . ' ' . $modelTransactionSession['business']['name'];
                                                        } ?>

                                                    </div>
                                                </div>

                                                <div class="row mt-30">
                                                    <div class="col-12">
                                                        <strong><?= \Yii::t('app', 'Order Information') ?></strong>
                                                        <?= $form->field($modelTransactionSession, 'note')->textarea(['rows' => 3, 'placeholder' => \Yii::t('app', 'Add Notes to Seller (Optional)')]) ?>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php
                                        else:

                                            echo \Yii::t('app', 'Your order list is empty') . '. ' . \Yii::t('app', 'Please order the item you want first'); ?>

                                            <div class="row mt-40">
                                                <div class="col-12">
                                                    <table class="table checkout-table">
                                                        <tbody>
                                                            <tr>
                                                                <th class="font-alt">Total</th>
                                                                <td class="total-price">0</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        <?php
                                        endif; ?>

                                        <div class="row">
                                            <div class="col-12">
                                                <?= Html::submitButton(\Yii::t('app', 'Order Now'), ['class' => 'btn btn-raised btn-danger btn-round btn-block btn-order', 'disabled' => empty($modelTransactionSession)]) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php
                            ActiveForm::end(); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="order-confirmation-modal" style="display:none">
	<div class="row">
		<div class="col-12">
			<div class="modal-header-search">
    			<div class="row">
    				<div class="col-10">
                        <div class="input-group">
                        	<div class="input-group-addon">
                        		<button type="button" class="close btn-close text-danger"><i class="aicon aicon-arrow-left2"></i></button>
                        	</div>
                        	<span class="modal-title-search">Order Summary</span>
                    	</div>
                	</div>
            	</div>
            </div>
		</div>
		<div class="col-sm-12 offset-1 col-11">

			<?php
			if (!empty($modelTransactionSession)):

    			foreach ($modelTransactionSession['transactionItems'] as $dataTransactionItem):

    			    $amountPrice = '<span class="item-amount">' . $dataTransactionItem['amount'] . '</span> x ' . \Yii::$app->formatter->asCurrency($dataTransactionItem['price']); ?>

    				<div id=item-<?= $dataTransactionItem['id'] ?>>
        				<div class="row">
        					<div class="col-sm-7 col-12">
        						<strong><?= $dataTransactionItem['businessProduct']['name'] ?></strong>
        					</div>
        					<div class="col-sm-5 d-none d-sm-block d-md-none">
        						<strong><?= $amountPrice ?></strong>
        					</div>
        				</div>

        				<div class="row mb-10">
        					<div class="col-12 item-note">
        						<?= $dataTransactionItem['note'] ?>
        					</div>
        					<div class="col-12 d-block d-sm-none">
        						<strong><?= $amountPrice ?></strong>
        					</div>
        				</div>
    				</div>

    			<?php
    			endforeach;
			endif; ?>

		</div>
	</div>

	<div class="row">
		<div class="col-sm-12 offset-1 col-11">
			<div class="row">
        		<div class="col-11">
        			<div class="delivery-method"></div>
        		</div>
        	</div>
        	<div class="row">
        		<div class="col-11">
        			<div class="payment-method"></div>
        		</div>
        	</div>
        	<div class="row" style="display:none">
        		<div class="col-12">
        			<div class="promo-code"></div>
        		</div>
        	</div>
		</div>
	</div>

	<div class="row" style="display:none">
		<div class="col-sm-12 offset-1 col-11">
			<div class="transaction-note"></div>
		</div>
	</div>

	<div class="row mt-20">
		<div class="col-sm-10 offset-1 col-10">
    		<table class="table checkout-table">
                <tbody>
                	<tr>
                        <th class="font-alt">Total</th>
                        <td class="total-price"><?= $modelTransactionSession['total_price'] ?></td>
                    </tr>
                    <tr class="promo-amount-confirm" style="display:none">
                        <th class="font-alt">Promo</th>
                        <td></td>
                    </tr>
                    <tr class="grand-total-confirm" style="display:none">
                        <th class="font-alt">Grand Total</th>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <?= Html::button(\Yii::t('app', 'Order Now'), ['class' => 'btn btn-raised btn-danger btn-round btn-block btn-submit-order']); ?>

        </div>
	</div>
</div>

<?php
Snackbar::widget();

$this->registerJsFile(Yii::$app->homeUrl . '/lib/jquery-currency/jquery.currency.js', ['depends' => 'yii\web\YiiAsset']);

$this->registerJs(Snackbar::messageResponse(), View::POS_HEAD);

$jscript = '
    var totalPrice = ' . $modelTransactionSession['total_price'] . ';

    var formIsValid = false;

    $(".total-price").currency({' . \Yii::$app->params['currencyOptions'] . '});

    if ($(".promo-code-field").val() != "") {

        var amount = $(this).find(":selected").data("amount");

        var grandTotal = totalPrice - amount < 0 ? 0 : totalPrice - amount;

        $(".promo-amount").show();
        $(".grand-total").show();
        $(".promo-amount").children().last().html(amount).currency({' . \Yii::$app->params['currencyOptions'] . '});
        $(".grand-total").children().last().html(grandTotal).currency({' . \Yii::$app->params['currencyOptions'] . '});

        $(".promo-amount-confirm").show();
        $(".grand-total-confirm").show();
        $(".promo-amount-confirm").children().last().html(amount).currency({' . \Yii::$app->params['currencyOptions'] . '});
        $(".grand-total-confirm").children().last().html(grandTotal).currency({' . \Yii::$app->params['currencyOptions'] . '});
    }

    $(".transaction-item-amount").on("focusout click", function() {

        var thisObj = $(this);
        var amount = parseInt(thisObj.val());
        var transactionItemId = thisObj.parents(".business-menu-group").find(".transaction-item-id").val();

        $.ajax({
            cache: false,
            type: "POST",
            url: thisObj.data("url"),
            data: {
                "id": transactionItemId,
                "amount": amount
            },
            beforeSend: function(xhr) {

                thisObj.parent().siblings(".overlay").show();
                thisObj.parent().siblings(".loading-text").show();
            },
            success: function(response) {

                if (response.success) {

                    thisObj.parents(".business-menu-group").find(".transaction-item-amount").val(amount);
                    $("#item-" + transactionItemId).find(".item-amount").html(amount);

                    $(".total-price").html(response.total_price).currency({' . \Yii::$app->params['currencyOptions'] . '});

                    totalPrice = response.total_price;

                    if ($(".promo-amount").is(":visible")) {

                        var grandTotal = totalPrice - $(".promo-code-field").find(":selected").data("amount");

                        $(".grand-total").children().last().html(grandTotal < 0 ? 0 : grandTotal).currency({' . \Yii::$app->params['currencyOptions'] . '});
                        $(".grand-total-confirm").children().last().html(grandTotal < 0 ? 0 : grandTotal).currency({' . \Yii::$app->params['currencyOptions'] . '});
                    }
                } else {

                    messageResponse(response.icon, response.title, response.text, response.type);
                }

                thisObj.parent().siblings(".overlay").hide();
                thisObj.parent().siblings(".loading-text").hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {

                messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");

                thisObj.parent().siblings(".overlay").hide();
                thisObj.parent().siblings(".loading-text").hide();
            }
        });
    });

    $(".transaction-item-notes").on("focusout", function() {

        var thisObj = $(this);
        var notes = thisObj.val();
        var transactionItemId = thisObj.parents(".business-menu-group").find(".transaction-item-id").val();

        $.ajax({
            cache: false,
            type: "POST",
            url: thisObj.data("url"),
            data: {
                "id": transactionItemId,
                "note": notes
            },
            beforeSend: function(xhr) {

                thisObj.parent().siblings(".overlay").show();
                thisObj.parent().siblings(".loading-text").show();
            },
            success: function(response) {

                if (!response.success) {

                    messageResponse(response.icon, response.title, response.text, response.type);
                }

                thisObj.parents(".business-menu-group").find(".transaction-item-notes").val(notes);
                $("#item-" + transactionItemId).find(".item-note").html(notes);

                thisObj.parent().siblings(".overlay").hide();
                thisObj.parent().siblings(".loading-text").hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {

                messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");

                thisObj.parent().siblings(".overlay").hide();
                thisObj.parent().siblings(".loading-text").hide();
            }
        });
    });

    $(".remove-item").on("click", function() {

        var thisObj = $(this);
        var transactionItemId = thisObj.parents(".business-menu-group").find(".transaction-item-id").val();

        $.ajax({
            cache: false,
            type: "POST",
            url: thisObj.attr("href"),
            data: {
                "id": transactionItemId
            },
            beforeSend: function(xhr) {

                thisObj.siblings(".overlay").show();
                thisObj.children().removeClass("fa-times").addClass("fa-spinner fa-spin");
            },
            success: function(response) {

                if (response.success) {

                    thisObj.parents(".business-menu-group").remove();
                    $("#item-" + transactionItemId).remove();

                    if (!$(".business-menu-group").length) {

                        $(".promo-code-section").siblings().removeClass("col-sm-offset-3").addClass("col-sm-offset-7");
                        $(".promo-code-section").remove();
                        $(".order-online-form").remove();
                        $(".promo-amount").remove();
                        $(".grand-total").remove();
                        $(".order-list").prepend("' . \Yii::t('app', 'Your order list is empty') . '. ' . \Yii::t('app', 'Please order the item you want first') . '");
                        $(".btn-order").prop("disabled", true);
                    } else {

                        totalPrice = response.total_price;

                        if ($(".promo-amount").is(":visible")) {

                            var grandTotal = totalPrice - $(".promo-code-field").find(":selected").data("amount");

                            $(".grand-total").children().last().html(grandTotal < 0 ? 0 : grandTotal).currency({' . \Yii::$app->params['currencyOptions'] . '});
                            $(".grand-total-confirm").children().last().html(grandTotal < 0 ? 0 : grandTotal).currency({' . \Yii::$app->params['currencyOptions'] . '});
                        }
                    }

                    $(".total-price").html(response.total_price).currency({' . \Yii::$app->params['currencyOptions'] . '});
                } else {

                    messageResponse(response.icon, response.title, response.text, response.type);
                }

                thisObj.siblings(".overlay").hide();
                thisObj.children().removeClass("fa-spinner fa-spin").addClass("fa-times");
            },
            error: function (xhr, ajaxOptions, thrownError) {

                messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");

                thisObj.siblings(".overlay").hide();
                thisObj.children().removeClass("fa-spinner fa-spin").addClass("fa-times");
            }
        });

        return false;
    });

    $(".promo-code-field").on("change", function() {

        if ($(this).val() == "") {

            $(".promo-amount").hide();
            $(".grand-total").hide();

            $(".promo-amount-confirm").hide();
            $(".grand-total-confirm").hide();

            $(this).parent().siblings("span").html("");
        } else {

            var amount = $(this).find(":selected").data("amount");

            var grandTotal = totalPrice - amount < 0 ? 0 : totalPrice - amount;

            $(".promo-amount").show();
            $(".grand-total").show();
            $(".promo-amount").children().last().html(amount).currency({' . \Yii::$app->params['currencyOptions'] . '});
            $(".grand-total").children().last().html(grandTotal).currency({' . \Yii::$app->params['currencyOptions'] . '});

            $(".promo-amount-confirm").show();
            $(".grand-total-confirm").show();
            $(".promo-amount-confirm").children().last().html(amount).currency({' . \Yii::$app->params['currencyOptions'] . '});
            $(".grand-total-confirm").children().last().html(grandTotal).currency({' . \Yii::$app->params['currencyOptions'] . '});

            var minOrder = $("<span>").html($(this).find(":selected").data("minimum-order")).currency({' . \Yii::$app->params['currencyOptions'] . '}).html();
            $(this).parent().siblings("span").html("*Minimal pembelian sebesar " + minOrder);
        }
    });

    $("#checkout-form").on("beforeSubmit", function(event) {

        if ($(".order-online-form").find(".has-error").length) {

            return false;
        }

        $(".order-confirmation-modal").fadeIn("medium");

        var deliveryMethod = $("input[name=\'TransactionSessionOrder[business_delivery_id]\']:checked").parent().parent().text();
        var paymentMethod = $("input[name=\'TransactionSessionOrder[business_payment_id]\']:checked").parent().parent().text();

        $(".order-confirmation-modal").find(".delivery-method").html("<strong>Metode Pengiriman : </strong>" + deliveryMethod);
        $(".order-confirmation-modal").find(".payment-method").html("<strong>Metode Pembayaran : </strong>" + paymentMethod);

        if ($(".promo-code-field").find(":selected").text() != "") {

            $(".promo-code").parent().parent().show();
            $(".promo-code").html("<strong>Kode Promo : </strong>" + $(".promo-code-field").find(":selected").text());
        }

        if ($("#transactionsession-note").val() != "") {

            $(".transaction-note").parent().parent().show();
            $(".transaction-note").html("<strong>Catatan : </strong>" + $("#transactionsession-note").val());
        } else {

            $(".transaction-note").parent().parent().hide();
        }

        return formIsValid;
    });

    $(".btn-submit-order").on("click", function() {

        var selectedPromoMinOrder = $(".promo-code-field").find(":selected").data("minimum-order");

        if ($(".promo-amount").is(":visible")) {

            if (selectedPromoMinOrder <= totalPrice) {

                formIsValid = true;
                $("#checkout-form").submit();
            } else {

                $(".order-confirmation-modal").fadeOut("medium");

                var minOrder = $("<span>").html(selectedPromoMinOrder).currency({' . \Yii::$app->params['currencyOptions'] . '}).html();
                messageResponse("aicon aicon-icon-info", "Gagal Checkout", "Minimal memesan sebesar " + minOrder + " untuk mendapatkan promo", "danger");
            }
        } else {

            formIsValid = true;
            $("#checkout-form").submit();
        }
    });

    $(".btn-close").on("click", function() {

        $(".order-confirmation-modal").fadeOut("medium");
    });
';

if (!empty(($message = \Yii::$app->session->getFlash('message')))) {

    $jscript .= 'messageResponse("aicon aicon-icon-tick-in-circle", "' . $message['title'] . '" , "' . $message['message'] . '", "danger");';
}

$this->registerJs($jscript); ?>