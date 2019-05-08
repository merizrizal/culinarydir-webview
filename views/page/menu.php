<?php

use webview\components\Snackbar;
use yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $modelBusiness core\models\Business */
/* @var $modelTransactionSession core\models\TransactionSession */
/* @var $dataMenuCategorised Array */
/* @var $isOrderOnline boolean */

$this->title = \Yii::t('app', 'Product') . ' ' . $modelBusiness['name']; ?>

<div class="main bg-main">
    <section>
        <div class="detail menu-list">
        	<div class="row">
                <div class="col-12">
            		<div class="row">
            			<div class="col-12">
            				<div class="card box">

            					<?php
            					if (!empty($dataMenuCategorised)):

            					    ksort($dataMenuCategorised);

                					if (!(count($dataMenuCategorised) == 1 && key_exists('emptyCategory', $dataMenuCategorised)) && count($dataMenuCategorised) > 1): ?>

                    					<div class="nav-menu view">
                        					<ul class="nav nav-tabs" role="tablist">
                        						<li class="nav-item">
                        							<a class="nav-link active dropdown-toggle m-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                                        <?= \Yii::t('app', 'Category') ?>
                                                    </a>

                                					<?php
                                					$businessProductCategory = '<ul class="dropdown-menu product-category-shortcut">';

                                					foreach ($dataMenuCategorised as $data) {

                                					    foreach ($data as $productCategory => $dataMenu) {

                                    					    if ($productCategory != 'emptyCategory') {

                                    					       $category = explode('|', $productCategory);

                                    					       $businessProductCategory .= '
                                                                    <li>' .
                                                                        Html::a($category[1] . ' (' . count($dataMenu) . ')', '', [
                                                                            'class' => 'menu-shortcut dropdown-item',
                                                                            'data-id' => $category[0]
                                                                        ]) . '
                                                                    </li>';
                                    					    }
                                					    }
                                					}

                                					echo $businessProductCategory . '</ul>'; ?>

                                                </li>
                                            </ul>
                    					</div>

            						<?php
            					    endif;
            					endif; ?>

            					<div class="box-content">
            						<div class="row">
										<div class="col-12">

											<?php
											if (!empty($modelBusiness['businessProducts'])):

    											echo Html::hiddenInput('business_id', $modelBusiness['id'], ['class' => 'business-id']);
    											echo Html::hiddenInput('business_name', $modelBusiness['name'], ['class' => 'business-name']);

    											if (!empty($modelTransactionSession)) {

    											    echo Html::hiddenInput('transaction_session_id', $modelTransactionSession['id'], ['class' => 'transaction-session-id']);
    											}

    											foreach ($dataMenuCategorised as $data):

        											foreach ($data as $productCategory => $dataMenu):

                                                        $productCategoryId = 'uncategorized';
                                                        $productCategoryName = \Yii::t('app', 'Uncategorized');

                                                        if ($productCategory != 'emptyCategory') {

                                                            $category = explode('|', $productCategory);
                                                            $productCategoryId = $category[0];
                                                            $productCategoryName = $category[1];
                                                        } ?>

                                                        <div class="row" id="menu-<?= $productCategoryId ?>">
                                                        	<div class="col-12">
                                                        		<h5><?= $productCategoryName ?></h5>
                                                            	<hr class="divider-w mb-10">
                                                    		</div>
                                                        </div>

                                                    	<?php
                                                        foreach ($dataMenu as $dataBusinessProduct):

                                                            $existOrderClass = 'd-none';
                                                            $addOrderClass = '';
                                                            $transactionItemId = null;
                                                            $transactionItemNotes = null;
                                                            $transactionItemAmount = 1;

                                                            if (!empty($modelTransactionSession['transactionItems'])) {

                                                                foreach ($modelTransactionSession['transactionItems'] as $dataTransactionItem) {

                                                                    if ($dataBusinessProduct['id'] === $dataTransactionItem['business_product_id']) {

                                                                        $existOrderClass = '';
                                                                        $addOrderClass = 'd-none';
                                                                        $transactionItemId = $dataTransactionItem['id'];
                                                                        $transactionItemNotes = $dataTransactionItem['note'];
                                                                        $transactionItemAmount = $dataTransactionItem['amount'];

                                                                        break;
                                                                    }
                                                                }
                                                            }

                                                            $orderbtn = Html::button('<i class="aicon aicon-plus"></i> ' . \Yii::t('app', 'Order This'), [
                                                                'class' => 'btn btn-raised btn-danger btn-round btn-xs add-item',
                                                                'data-url' => \Yii::$app->urlManager->createUrl(['order-action/save-order']),
                                                                'data-product-id' => $dataBusinessProduct['id'],
                                                                'data-product-price' => $dataBusinessProduct['price']
                                                            ]);

                                                            $notesField = Html::textInput('transaction_item_notes', $transactionItemNotes, [
                                                                'class' => 'form-control transaction-item-notes',
                                                                'placeholder' => \Yii::t('app', 'Note'),
                                                                'data-url' => \Yii::$app->urlManager->createUrl(['order-action/save-notes'])
                                                            ]);

                                                            $amountField = Html::textInput('transaction_item_amount', $transactionItemAmount, [
                                                                'class' => 'form-control transaction-item-amount text-center',
                                                                'data-url' => \Yii::$app->urlManager->createUrl(['order-action/change-qty'])
                                                            ]); ?>

                                                            <div class="business-menu-group">

                                                            	<?= Html::hiddenInput('transaction_item_id', $transactionItemId, ['class' => 'transaction-item-id']); ?>

                                    							<div class="business-menu mb-30 d-none d-sm-block d-md-none">
                                    								<div class="row">
                                                                        <div class="col-sm-8">
                                                                            <strong><?= $dataBusinessProduct['name'] ?></strong>
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <strong>&nbsp;&nbsp;<?= \Yii::$app->formatter->asCurrency($dataBusinessProduct['price']) ?></strong>
                                                                        </div>
                                                                        <div class="col-sm-1 text-right <?= $existOrderClass ?>">
                                                                			<div class="overlay" style="display: none;"></div>
                                                                			<?= Html::a('<i class="aicon aicon-cross"></i>', ['order-action/remove-item'], ['class' => 'remove-item']); ?>
                                                                		</div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <p class="mb-0"><?= $dataBusinessProduct['description'] ?></p>
                                                                        </div>
                                                                    </div>

                                                                    <?php
                                                                    if ($isOrderOnline): ?>

                                                                        <div class="row <?= $addOrderClass ?>">
                                                                        	<div class="offset-sm-8 col-sm-4">
                                                                        		<?= $orderbtn ?>
                                                                        	</div>
                                                                        </div>

                                                                        <div class="row input-order <?= $existOrderClass ?>">
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

                                                                    <?php
                                                                    endif; ?>

                                    							</div>
                                    							<div class="business-menu mb-30 d-block d-sm-none">
                                    								<div class="row">
                                                                        <div class="col-7">
                                                                            <strong><?= $dataBusinessProduct['name'] ?></strong>
                                                                        </div>
                                                                        <div class="col-5 product-price <?= $addOrderClass ?>">
                                                                            <strong><?= \Yii::$app->formatter->asCurrency($dataBusinessProduct['price']) ?></strong>
                                                                        </div>
                                                                        <div class="col-5 text-right <?= $existOrderClass ?>">
                                                                			<div class="overlay" style="display: none;"></div>
                                                                			<?= Html::a('<i class="aicon aicon-cross"></i>', ['order-action/remove-item'], ['class' => 'remove-item']); ?>
                                                                		</div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <p class="mb-0"><?= $dataBusinessProduct['description'] ?></p>
                                                                        </div>
                                                                    </div>

                                                                    <?php
                                                                    if ($isOrderOnline): ?>

                                                                        <div class="row <?= $addOrderClass ?>">
                                                                        	<div class="offset-7 col-5">
                                                                        		<?= $orderbtn ?>
                                                                        	</div>
                                                                        </div>
                                                                        <div class="row input-order <?= $existOrderClass ?>">
                                                                        	<div class="col-12 mb-10">
                                                                    			<div class="overlay" style="display: none;"></div>
                                                                    			<div class="loading-text" style="display: none;"></div>
                                                                    			<?= $notesField ?>
                                                                    		</div>
                                                                            <div class="col-7">
                                                                                <strong><?= \Yii::$app->formatter->asCurrency($dataBusinessProduct['price']) ?></strong>
                                                                            </div>
                                                                        	<div class="col-5">
                                                                    			<div class="overlay" style="display: none;"></div>
                                                                    			<div class="loading-text" style="display: none;"></div>
                                                                                <?= $amountField ?>
                                                                    		</div>
                                                                        </div>

                                                                    <?php
                                                                    endif; ?>

                                    							</div>
                                							</div>

                                                        <?php
                                                        endforeach;
                                                    endforeach;
                                                endforeach;
                                            else:

                                                echo '<p>' . \Yii::t('app', 'Currently there is no menu available') . '.</p>';
                                            endif; ?>

										</div>
									</div>
            					</div>

            				</div>
        				</div>
    				</div>
        		</div>
    		</div>

    		<div class="clearfix blank-space">&nbsp;</div>
        </div>
    </section>
</div>

<?php
Snackbar::widget();

$this->registerJsFile(Yii::$app->homeUrl . '/lib/jquery-currency/jquery.currency.js', ['depends' => 'yii\web\YiiAsset']);
$this->registerJsFile(Yii::$app->homeUrl . '/lib/touchspin/touchspin.js', ['depends' => 'yii\web\YiiAsset']);

$this->registerJs(Snackbar::messageResponse() . Snackbar::stickySnackbar(), View::POS_HEAD);

$jscript = '
    $(".transaction-item-amount").each(function() {

        $(this).TouchSpin({
            "min": 1,
            "max": 50,
            "buttonup_txt": "<i class=\"aicon aicon-plus\"></i>",
            "buttondown_txt": "<i class=\"aicon aicon-minus\"></i>",
            "buttondown_class": "btn btn-raised btn-small",
            "buttonup_class": "btn btn-raised btn-small"
        });
    });

    $(window).scroll(function() {

        var st = $(this).scrollTop();

        if (st > $(".nav-menu").offset().top) {

            $(".nav-tabs").addClass("nav-tabs-fixed");
            $(".nav-menu").addClass("stick");
        } else {

            $(".nav-tabs").removeClass("nav-tabs-fixed");
            $(".nav-menu").removeClass("stick");
        }
    });

    var cart = null;
    var totalPrice = "";

    if ($(".transaction-session-id").length) {

        totalPrice = $("<span>").html("' . $modelTransactionSession['total_price'] . '").currency({' . \Yii::$app->params['currencyOptions'] . '}).html();

        cart = stickySnackbar(
            "aicon aicon-icon-online-ordering aicon-1x",
            "<b>' . $modelTransactionSession['total_amount'] . '" + " menu | Total : " + totalPrice + "</b>",
            "' . $modelTransactionSession['business']['name'] . '",
            "info"
        );

        cart.on("click", function() {

            window.location = "' . \Yii::$app->urlManager->createUrl(['order/checkout']) . '";
            $(this).snackbar("show");
        });
    }

    $(".add-item").on("click", function() {

        var thisObj = $(this);

        $.ajax({
            cache: false,
            type: "POST",
            url: thisObj.data("url"),
            data: {
                "business_id": $(".business-id").val(),
                "product_id": thisObj.data("product-id"),
                "product_price": thisObj.data("product-price")
            },
            success: function(response) {

                if (response.success) {

                    totalPrice = $("<span>").html(response.total_price).currency({' . \Yii::$app->params['currencyOptions'] . '}).html();

                    if (cart != null) {

                        $(".snackbar-title").html("<b>" + response.total_amount + " menu" + " | Total : " + totalPrice + "</b>");
                    } else {

                        cart = stickySnackbar(
                            "aicon aicon-icon-online-ordering aicon-1x",
                            "<b>" + response.total_amount + " menu | Total : " + totalPrice + "</b>",
                            $(".business-name").val(),
                            "info"
                        );

                        cart.on("click", function() {

                            window.location = "' . \Yii::$app->urlManager->createUrl(['order/checkout']) . '";
                            $(this).snackbar("show");
                        });
                    }

                    var parentClass = thisObj.parents(".business-menu-group");

                    parentClass.find(".add-item").parent().parent().addClass("d-none");
                    parentClass.find(".input-order").removeClass("d-none");
                    parentClass.find(".remove-item").parent().removeClass("d-none");
                    parentClass.find(".product-price").addClass("d-none");
                    parentClass.find(".transaction-item-id").val(response.item_id);
                } else {

                    messageResponse(response.icon, response.title, response.text, response.type);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");
            }
        });
    });

    $(".transaction-item-amount").on("change", function() {

        var thisObj = $(this);
        var amount = parseInt(thisObj.val());

        $.ajax({
            cache: false,
            type: "POST",
            url: thisObj.data("url"),
            data: {
                "id": thisObj.parents(".business-menu-group").find(".transaction-item-id").val(),
                "amount": amount
            },
            beforeSend: function(xhr) {

                thisObj.parent().parent().siblings(".overlay").show();
                thisObj.parent().parent().siblings(".loading-text").show();
            },
            success: function(response) {

                if (response.success) {

                    totalPrice = $("<span>").html(response.total_price).currency({' . \Yii::$app->params['currencyOptions'] . '}).html();
                    $(".snackbar-title").html("<b>" + response.total_amount + " menu" + " | Total : " + totalPrice + "</b>");

                    thisObj.parents(".business-menu-group").find(".transaction-item-amount").val(amount);
                } else {

                    messageResponse(response.icon, response.title, response.text, response.type);
                }

                thisObj.parent().parent().siblings(".overlay").hide();
                thisObj.parent().parent().siblings(".loading-text").hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {

                messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");

                thisObj.parent().parent().siblings(".overlay").hide();
                thisObj.parent().parent().siblings(".loading-text").hide();
            }
        });
    });

    $(".transaction-item-notes").on("change", function() {

        var thisObj = $(this);
        var notes = thisObj.val();

        $.ajax({
            cache: false,
            type: "POST",
            url: thisObj.data("url"),
            data: {
                "id": thisObj.parents(".business-menu-group").find(".transaction-item-id").val(),
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

        $.ajax({
            cache: false,
            type: "POST",
            url: thisObj.attr("href"),
            data: {
                "id": thisObj.parents(".business-menu-group").find(".transaction-item-id").val()
            },
            beforeSend: function(xhr) {

                thisObj.siblings(".overlay").show();
                thisObj.children().removeClass("aicon-cross").addClass("aicon-spinner1");
            },
            success: function(response) {

                if (response.success) {

                    if (!response.total_amount) {

                        $("#snackbar-container .snackbar").remove();

                        cart = null;
                    } else {

                        totalPrice = $("<span>").html(response.total_price).currency({' . \Yii::$app->params['currencyOptions'] . '}).html();
                        $(".snackbar-title").html("<b>" + response.total_amount + " menu" + " | Total : " + totalPrice + "</b>");
                    }

                    var parentClass = thisObj.parents(".business-menu-group");

                    parentClass.find(".remove-item").parent().addClass("d-none");
                    parentClass.find(".input-order").addClass("d-none");
                    parentClass.find(".add-item").parent().parent().removeClass("d-none");
                    parentClass.find(".product-price").removeClass("d-none");
                    parentClass.find(".transaction-item-id").val("");
                    parentClass.find(".transaction-item-notes").val("");
                    parentClass.find(".transaction-item-amount").val(1);
                } else {

                    messageResponse(response.icon, response.title, response.text, response.type);
                }

                thisObj.siblings(".overlay").hide();
                thisObj.children().removeClass("aicon-spinner1").addClass("aicon-cross");
            },
            error: function (xhr, ajaxOptions, thrownError) {

                messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");

                thisObj.siblings(".overlay").hide();
                thisObj.children().removeClass("aicon-spinner1").addClass("aicon-cross");
            }
        });

        return false;
    });

    $(".menu-shortcut").on("click", function() {

        $("html, body").animate({ scrollTop: $("#menu-" + $(this).data("id")).offset().top - 50 }, "slow");

        $(this).parent().parent().siblings("a").dropdown("toggle");

        return false;
    });
';

$this->registerJs($jscript); ?>