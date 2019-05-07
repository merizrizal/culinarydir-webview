<?php

use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $modelBusiness core\models\Business */
/* @var $modelPostPhoto frontend\models\Post */ ?>

<div class="row">
    <div class="col-12">
        <div class="card box">

            <div class="overlay" style="display: none;"></div>
            <div class="loading-img" style="display: none;"></div>

            <?php
            $form = ActiveForm::begin([
                'id' => 'post-photo-form',
                'action' => ['action/submit-photo'],
                'fieldConfig' => [
                    'template' => '{input}{error}',
                ]
            ]);

                echo Html::hiddenInput('business_id', $modelBusiness['id'], ['id' => 'business_id']); ?>

                <div class="box-title" id="title-post-photo">
                	<div class="row">
                		<div class="col-6">
                    		<h4 class="mt-0 mb-0"><?= \Yii::t('app', 'Add Photo') ?></h4>
                    	</div>
                		<div class="col-6 text-right">
                    		<span id="close-post-photo-container"><a class="text-danger" href=""><?= \Yii::t('app', 'Cancel') ?></a></span>
            			</div>
                    </div>
                </div>

                <div class="box-content">

                    <div class="form-group">
                        <button id="post-photo-trigger" type="button" class="btn btn-raised btn-round btn-danger"><i class="aicon aicon-plus"></i> <?= \Yii::t('app', 'Add Photo') ?></button>
                    </div>

                    <div class="row" id="post-photo-container">
                        <div class="col-12">

                        	<?php
                            echo $form->field($modelPostPhoto, 'text')->textInput([
                                'class' => 'form-control',
                                'placeholder' => \Yii::t('app', 'Photo Caption'),
                            ]);

                            echo $form->field($modelPostPhoto, 'image')->fileInput([
                                'id' => 'add-photo-input',
                                'accept' => 'image/*',
                                'multiple' => false,
                            ]); ?>

                            <div class="form-group">
                                <?= Html::submitButton('<i class="aicon aicon-share"></i> Upload ' . \Yii::t('app', 'Photo'), ['id' => 'submit-post-photo', 'class' => 'btn btn-raised btn-standard btn-round']) ?>&nbsp;
                                <?= Html::a('<i class="aicon aicon-cross"></i> ' . \Yii::t('app', 'Cancel'), null, ['id' => 'cancel-post-photo', 'class' => 'btn btn-raised btn-standard btn-round']) ?>
                            </div>
                        </div>
                    </div>
                </div>

            <?php
            ActiveForm::end(); ?>

        </div>
    </div>
</div>

<div class="row mt-10">
    <div class="col-12">
        <div class="card box">
            <div class="box-content">
                <div class="gallery-section"></div>
            </div>
        </div>
    </div>
</div>

<?php
$jscript = '
    function getUserPhoto(business_id) {

        $.ajax({
            cache: false,
            type: "GET",
            url: "' . \Yii::$app->urlManager->createUrl(['data/post-photo', 'id' => $modelBusiness['id']]) . '",
            success: function(response) {

                $(".gallery-section").html(response);
            },
            error: function(xhr, ajaxOptions, thrownError) {

                messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");
            }
        });
    }

    getUserPhoto($("#business_id").val());

    $("#post-photo-container").hide();
    $("#close-post-photo-container").hide();

    $("#close-post-photo-container > a, #cancel-post-photo").on("click", function(event) {

        $("#post-photo-container, #close-post-photo-container").fadeOut(100, function() {

            $("#post-photo-trigger").fadeIn();
            $("html, body").animate({ scrollTop: $("#title-post-photo").offset().top }, "slow");
        });

        $("#post-text").val("");
        $("#add-photo-input").val("");
        $("#post-photo-container").find(".form-group").removeClass("has-success");
        $("#post-photo-container").find(".form-group").removeClass("has-error");
        $("#post-photo-container").find(".form-group").find(".help-block").html("");

        return false;
    });

    $("#post-photo-trigger").on("click", function(event) {

        var thisObj = $(this);

        $.ajax({
            cache: false,
            type: "POST",
            url: "' . \Yii::$app->urlManager->createUrl(['redirect/add-photo']) . '",
            success: function(response) {

                thisObj.fadeOut(100, function() {

                    $("#post-photo-container").fadeIn();
                    $("#close-post-photo-container").fadeIn();
                });

                if ($("#post-photo-container").find(".form-group").hasClass("has-error")) {

                    $("#post-photo-container").find(".form-group").removeClass("has-error");
                    $("#post-photo-container").find(".form-group").find(".help-block").html("");
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {

                messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");
            }
        });
    });

    $("form#post-photo-form").on("beforeSubmit", function(event) {

        var thisObj = $(this);

        thisObj.siblings(".overlay").show();
        thisObj.siblings(".loading-img").show();

        if(thisObj.find(".has-error").length)  {
            return false;
        }

        var formData = new FormData(this);

        var endUrl = thisObj.attr("action");

        $.ajax({
            cache: false,
            contentType: false,
            processData: false,
            type: "POST",
            data: formData,
            url: thisObj.attr("action"),
            success: function(response) {

                $("#add-photo-input").val("");

                if (response.success) {

                    $("#cancel-post-photo").trigger("click");

                    getUserPhoto($("#business_id").val());

                    messageResponse(response.icon, response.title, response.message, response.type);
                } else {

                    $("#post-photo-container").find(".form-group").removeClass("has-success");
                    $("#post-photo-container").find(".form-group").removeClass("has-error");
                    $("#post-photo-container").find(".form-group").find(".help-block").html("");

                    messageResponse(response.icon, response.title, response.message, response.type);
                }

                thisObj.siblings(".overlay").hide();
                thisObj.siblings(".loading-img").hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {

                messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");

                thisObj.siblings(".overlay").hide();
                thisObj.siblings(".loading-img").hide();
            }
        });

        return false;
    });

    $(".gallery-section").on("click", ".share-image-trigger", function() {

        var url = $(this).attr("href");
        var title = "Foto untuk " + $(".business-name").text().trim();
        var description = $(this).parents(".work-item").find(".photo-caption").text() !== "" ? $(this).parents(".work-item").find(".photo-caption").text() : "Temukan Bisnis Kuliner Favorit Anda di Asikmakan.com";
        var image = window.location.protocol + "//" + window.location.hostname + $(this).parents(".work-item").find(".work-image img").attr("src").replace("200x200", "");

        facebookShare({
            ogUrl: url,
            ogTitle: title,
            ogDescription: description,
            ogImage: image,
            type: "Foto"
        });

        return false;
    });
';

$this->registerJs($jscript); ?>