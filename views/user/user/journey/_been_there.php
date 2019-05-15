<?php

/* @var $this yii\web\View */
/* @var $id string */ ?>

<div class="row been-there">
    <div class="col-12">
        <div class="user-visit-section"></div>
    </div>
</div>

<?php
$jscript = '
    $.ajax({
        cache: false,
        type: "GET",
        url: "' . \Yii::$app->urlManager->createUrl(['user-data/user-visit', 'id' => $id]) . '",
        success: function(response) {

            $(".user-visit-section").html(response);
            $(".user-visit-section").find(".user-visit-container").bootstrapMaterialDesign();
        },
        error: function(xhr, ajaxOptions, thrownError) {

            messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");
        }
    });
';

$this->registerJs($jscript); ?>