<?php

/* @var $this yii\web\View */ ?>

<div class="row">
    <div class="col-12">
        <div class="card box">
            <div class="box-content">
                <div class="new-promo"></div>
            </div>
        </div>
    </div>
</div>

<?php
$jscript = '
    $.ajax({
        cache: false,
        type: "GET",
        url: "' . \Yii::$app->urlManager->createUrl(['user-data/new-promo']) . '",
        success: function(response) {

            $(".new-promo").html(response);
            $(".new-promo").find(".new-promo-container").bootstrapMaterialDesign();
        },
        error: function(xhr, ajaxOptions, thrownError) {

            messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");
        }
    });
';

$this->registerJs($jscript); ?>