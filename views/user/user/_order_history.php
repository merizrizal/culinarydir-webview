<?php

/* @var $this yii\web\View */
/* @var $username string */
/* @var $queryParams array */ ?>

<div class="row">
    <div class="col-12">
        <div class="card box">
            <div class="box-content">
            	<div class="order-history"></div>
            </div>
        </div>
    </div>
</div>

<?php
$jscript = '
    $.ajax({
        cache: false,
        type: "GET",
        url: "' . \Yii::$app->urlManager->createUrl(['user-data/order-history']) . '",
        success: function(response) {

            $(".order-history").html(response);
            $(".order-history").find(".order-history-container").bootstrapMaterialDesign();
        },
        error: function(xhr, ajaxOptions, thrownError) {

            messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");
        }
    });
';

$this->registerJs($jscript); ?>