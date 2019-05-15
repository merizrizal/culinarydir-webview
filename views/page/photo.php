<?php

use common\components\Helper;
use webview\components\Snackbar;
use yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $modelUserPostMain core\models\UserPostMain */

common\assets\OwlCarouselAsset::register($this);

$this->title = \Yii::t('app', 'Photo') . ' ' . $modelUserPostMain['business']['name'];

$ogUrl = \Yii::$app->params['rootUrl'] . 'photo/' . $modelUserPostMain['id'] . '/di/' . $modelUserPostMain['business']['unique_name'];

$ogTitle = !empty($modelUserPostMain['business']['name']) ? 'Foto untuk ' . $modelUserPostMain['business']['name'] : 'Foto di Asikmakan';
$ogDescription = !empty($modelUserPostMain['text']) ? $modelUserPostMain['text'] : $this->title;
$ogImage = \Yii::$app->params['endPointLoadImage'] . 'user-post?image=&w=490&h=276';

if (!empty($modelUserPostMain['image'])) {

    $ogImage = \Yii::$app->params['endPointLoadImage'] . 'user-post?image=' . $modelUserPostMain['image'];
} ?>

<div class="main bg-main">
    <section>
        <div class="detail photo">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="card box">
                                <div class="box-content">

                                    <?php
                                    if (!empty($modelUserPostMain)):

                                        $img = !empty($modelUserPostMain['user']['image']) ? $modelUserPostMain['user']['image'] . '&w=200&h=200' : 'default-avatar.png'; ?>

                                        <div class="photo-container">

                                            <?= Html::hiddenInput('user_post_main_id', $modelUserPostMain['id'], ['class' => 'user-post-main-id']) ?>

                                            <div class="row mb-10">
                                                <div class="col-sm-7 col-12">
                                                    <div class="widget-posts-image">

    												    <?= Html::a(Html::img(\Yii::$app->params['endPointLoadImage'] . 'user?image=' . $img, [
    												        'class' => 'img-fluid rounded-circle'
    												    ]), ['user/user-profile', 'user' => $modelUserPostMain['user']['username']]) ?>

                                                    </div>

                                                    <div class="widget-posts-body">
                                                        <?= Html::a($modelUserPostMain['user']['full_name'], ['user/user-profile', 'user' => $modelUserPostMain['user']['username']]) ?>
                                                        <br>
                                                        <small><?= Helper::asRelativeTime($modelUserPostMain['created_at']) ?></small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="photo-review mt-10 mb-10">
                                                    	<div class="owl-carousel owl-theme">
                                                        	<?= Html::img(null, ['class' => 'owl-lazy img-fluid', 'data-src' => \Yii::$app->params['endPointLoadImage'] . 'user-post?image=' . $modelUserPostMain['image']]) ?>
                                                        </div>
                                                    </div>

                                                    <p class="review-description">
                                                        <?= $modelUserPostMain['text']; ?>
                                                    </p>

    												<?php
                                					$loveCount = !empty($modelUserPostMain['love_value']) ? $modelUserPostMain['love_value'] : 0;
                                					$commentCount = !empty($modelUserPostMain['userPostComments']) ? count($modelUserPostMain['userPostComments']) : 0;

                                					$loveSpanCount = '<span class="total-likes-photo">' . $loveCount . '</span>';
                                					$commentSpanCount = '<span class="total-comments-photo">' . $commentCount . '</span>';

                                					$selected = !empty($modelUserPostMain['userPostLoves'][0]) ? 'selected' : ''; ?>

                                                    <div class="row">
                                                        <div class="col-3 d-block d-sm-none">
                                                            <ul class="list-inline mt-0 mb-0">
                                                                <li class="list-inline-item">
                                                                    <small><?= '<i class="aicon aicon-thumb"></i> <span class="total-likes-photo">' . $loveCount . '</span>' ?></small>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="col-9 d-block d-sm-none text-right">
                                                            <ul class="list-inline mt-0 mb-0">
                                                                <li class="list-inline-item">
                                                                    <small><?= $commentSpanCount . ' Comment' ?></small>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-7 col-12">
                                                            <ul class="list-inline list-review mt-0 mb-0">
                                                                <li class="list-inline-item">
                                                                    <?= Html::a('<i class="aicon aicon-thumb"></i> ' . $loveSpanCount . ' Like', ['action/submit-likes'] , ['class' => 'btn btn-raised btn-small btn-round likes-photo-trigger ' . $selected . ' d-none d-sm-block d-md-none']); ?>
                                                                    <?= Html::a('<i class="aicon aicon-thumb"></i> Like', ['action/submit-likes'], ['class' => 'btn btn-raised btn-small btn-round likes-photo-trigger ' . $selected . ' d-block d-sm-none']); ?>
                                                                </li>
                                                                <li class="list-inline-item">
                                                                    <?= Html::a('<i class="aicon aicon-bubbles"></i> ' . $commentSpanCount . ' Comment', '', ['class' => 'btn btn-raised btn-small btn-round comments-photo-trigger d-none d-sm-block d-md-none']); ?>
                                                                    <?= Html::a('<i class="aicon aicon-bubbles"></i> Comment', '', ['class' => 'btn btn-raised btn-small btn-round comments-photo-trigger d-block d-sm-none']); ?>
                                                                </li>
                                                                <li class="list-inline-item">
                                                                	<?= Html::a('<i class="aicon aicon-share1"></i> ', '', ['class' => 'btn btn-raised btn-small btn-round share-photo-trigger d-block d-sm-none']); ?>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="col-sm-5 d-none d-sm-block d-md-none text-right">
                                                            <ul class="list-inline list-review mt-0 mb-0">
                                                                <li class="list-inline-item">
                                                                	<?= Html::a('<i class="aicon aicon-share1"></i> Share', '', ['class' => 'btn btn-raised btn-small btn-round share-photo-trigger']); ?>
                                                                </li>
                                                            </ul>
                                                    	</div>
                                                    </div>

                                                    <hr class="divider-w mt-10">

                                                     <div class="row">
                                        				<div class="col-12">
                                                        	<div class="user-comment-review" id="comments-photo-container">

        														<div class="input-group mt-10 mb-10">
                                                                    <span class="input-group-text"><i class="aicon aicon-bubble"></i></span>
                                                                    &nbsp;&nbsp;&nbsp;
                                                                    <?= Html::textInput('comment_input', null, ['id' => 'input-comments-photo', 'class' => 'form-control', 'placeholder' => \Yii::t('app', 'Write a Comment')]); ?>
                                                                </div>

                                                                <div class="overlay" style="display: none;"></div>
                                                                <div class="loading-img" style="display: none;"></div>

                                                                <div class="comment-section">

                                                                    <?php
                                                                    foreach ($modelUserPostMain['userPostComments'] as $dataUserPostComment): ?>

                                                                        <div class="comment-post">
                                                                            <div class="row mb-10">
                                                                                <div class="col-12">
                                                                                    <div class="widget-comments-image">

                                                                                        <?php
                                                                                        $img = !empty($dataUserPostComment['user']['image']) ? $dataUserPostComment['user']['image'] . '&w=200&h=200' : 'default-avatar.png';

                                                                                        echo Html::a(Html::img(\Yii::$app->params['endPointLoadImage'] . 'user?image=' . $img, [
                                                                                            'class' => 'img-fluid rounded-circle'
                                                                                        ]), ['user/user-profile', 'user' => $dataUserPostComment['user']['username']]); ?>

                                                                                    </div>

                                                                                    <div class="widget-comments-body">
                                                                                        <?= Html::a($dataUserPostComment['user']['full_name'], ['user/user-profile', 'user' => $dataUserPostComment['user']['username']]); ?>&nbsp;&nbsp;&nbsp;
                                                                                        <small><?= Helper::asRelativeTime($dataUserPostComment['created_at']) ?></small>
                                                                                        <br>
                                                                                        <p class="comment-description">
                                                                                            <?= $dataUserPostComment['text']; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    <?php
                                                                    endforeach; ?>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php
                                    endif; ?>

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
frontend\components\FacebookShare::widget();

$this->registerJs(Snackbar::messageResponse(), View::POS_HEAD);

$jscript = '
    var photoId = $(".user-post-main-id");

    $(".likes-photo-trigger").on("click", function() {

        $.ajax({
            cache: false,
            type: "POST",
            data: {
                "user_post_main_id": photoId.val()
            },
            url: $(this).attr("href"),
            success: function(response) {

                if (response.success) {

                    var loveValue = parseInt($(".total-likes-photo").html());

                    if (response.is_active) {

                        $(".likes-photo-trigger").addClass("selected");
                        $(".total-likes-photo").html((loveValue + 1));
                    } else {

                        $(".likes-photo-trigger").removeClass("selected");
                        $(".total-likes-photo").html((loveValue - 1));
                    }
                } else {

                    messageResponse(response.icon, response.title, response.message, response.type);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {

                messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");
            }
        });

        return false;
    });

    $(".comments-photo-trigger").on("click", function() {

        $("#comments-photo-container").slideToggle();
        $("#input-comments-photo").trigger("focus");

        return false;
    });

    $("#input-comments-photo").on("keypress", function(event) {

        if (event.which == 13 && $(this).val().trim()) {

            $.ajax({
                cache: false,
                type: "POST",
                data: {
                    "user_post_main_id": photoId.val(),
                    "text": $(this).val(),
                },
                url: "' . \Yii::$app->urlManager->createUrl(['action/submit-comment']) . '",
                beforeSend: function(xhr) {

                    $(".comment-section").siblings(".overlay").show();
                    $(".comment-section").siblings(".loading-img").show();
                },
                success: function(response) {

                    if (response.success) {

                        $("#input-comments-photo").val("");

                        $.ajax({
                            cache: false,
                            type: "POST",
                            data: {
                                "user_post_main_id": response.user_post_main_id
                            },
                            url: "' . \Yii::$app->urlManager->createUrl(['data/post-comment']) . '",
                            success: function(response) {

                                $(".comment-section").html(response);

                                $(".total-comments-photo").html(commentCount);
                            },
                            error: function(xhr, ajaxOptions, thrownError) {

                                messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");
                            }
                        });
                    } else {

                        messageResponse(response.icon, response.title, response.message, response.type);
                    }

                    $(".comment-section").siblings(".overlay").hide();
                    $(".comment-section").siblings(".loading-img").hide();
                },
                error: function (xhr, ajaxOptions, thrownError) {

                    messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");

                    $(".comment-section").siblings(".overlay").hide();
                    $(".comment-section").siblings(".loading-img").hide();
                }
            });
        }
    });

    $(".share-photo-trigger").on("click", function() {

        facebookShare({
            ogUrl: "' . $ogUrl . '",
            ogTitle: "' . $ogTitle . '",
            ogDescription: "' . addslashes($ogDescription) . '",
            ogImage: "' . $ogImage . '",
            type: "Foto"
        });

        return false;
    });

    $(".photo-review").find(".owl-carousel").owlCarousel({
        lazyLoad: true,
        items: 1,
        mouseDrag: false,
        touchDrag: false
    });
';

$this->registerJs($jscript); ?>