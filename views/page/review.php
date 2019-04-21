<?php

use common\components\Helper;
use frontend\components\GrowlCustom;
use kartik\rating\StarRating;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\web\View;

/* @var $this \yii\web\View */
/* @var $modelUserPostMain core\models\UserPostMain */

$this->title = 'Review ' . $modelUserPostMain['business']['name'];

$ogUrl = Yii::$app->urlManager->createAbsoluteUrl([
    'page/review',
    'id' => $modelUserPostMain['id'],
    'uniqueName' => $modelUserPostMain['business']['unique_name'],
]);

$ogTitle = !empty($modelUserPostMain['business']['name']) && !empty($dataUserVoteReview['overallValue']) ? 'Rating ' . number_format($dataUserVoteReview['overallValue'], 1) . ' untuk ' . $modelUserPostMain['business']['name'] : 'Review di Asikmakan';

$ogDescription = !empty($modelUserPostMain['text']) ? $modelUserPostMain['text'] : $this->title;
$ogDescription = StringHelper::truncate(preg_replace('/[\r\n]+/','' , $ogDescription), 300);

$ogImage = Yii::$app->params['endPointLoadImage'] . 'user-post?image=&w=490&h=276';

if (!empty($modelUserPostMain['userPostMains'][0]['image'])) {

    $ogImage = Yii::$app->params['endPointLoadImage'] . 'user-post?image=' . $modelUserPostMain['userPostMains'][0]['image'];
}

$this->registerMetaTag([
    'property' => 'og:url',
    'content' => $ogUrl
]);

$this->registerMetaTag([
    'property' => 'og:type',
    'content' => 'website'
]);

$this->registerMetaTag([
    'property' => 'og:title',
    'content' => $ogTitle
]);

$this->registerMetaTag([
    'property' => 'og:description',
    'content' => $ogDescription
]);

$this->registerMetaTag([
    'property' => 'og:image',
    'content' => $ogImage
]); ?>

<div class="main bg-main">
	<section>
        <div class="detail review">

        	<div class="row mb-20">
                <div class="col-sm-12 col-xs-12">

                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="box bg-white">
                                <div class="box-content">

                                    <?php
                                    if (!empty($modelUserPostMain)):

                                        $img = !empty($modelUserPostMain['user']['image']) ? $modelUserPostMain['user']['image'] . '&w=200&h=200' : 'default-avatar.png';

										$overallValue = !empty($dataUserVoteReview['overallValue']) ? $dataUserVoteReview['overallValue'] : 0; ?>

                                        <div class="review-container">

                                            <?= Html::hiddenInput('user_post_main_id', $modelUserPostMain['id'], ['class' => 'user-post-main-id']) ?>

                                            <div class="row mb-10">
                                                <div class="col-tab-7 col-xs-12">
                                                    <div class="widget">
                                                        <div class="widget-posts-image">

        												    <?= Html::a(Html::img(Yii::$app->params['endPointLoadImage'] . 'user?image=' . $img, [
        												        'class' => 'img-responsive img-circle img-profile-thumb img-component'
        												    ]), ['user/user-profile', 'user' => $modelUserPostMain['user']['username']]) ?>

                                                        </div>

                                                        <div class="widget-posts-body">
                                                            <?= Html::a($modelUserPostMain['user']['full_name'], ['user/user-profile', 'user' => $modelUserPostMain['user']['username']]) ?>
                                                            <br>
                                                            <small><?= Helper::asRelativeTime($modelUserPostMain['created_at']) ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-tab-5 visible-tab">
													<div class="rating">
                                                    	<h3 class="mt-0 mb-0">
                                                    		<?= Html::a(number_format($overallValue, 1), '#', ['class' => 'label label-success']); ?>
                                                        </h3>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 visible-xs">
                                                	<ul class="list-inline mt-0 mb-0">
                                                        <li>
                                                            <div class="widget star-rating">

                                                                <?= StarRating::widget([
                                                                    'id' => 'rating-' . $modelUserPostMain['id'],
                                                                    'name' => 'rating_' . $modelUserPostMain['id'],
                                                                    'value' => $overallValue,
                                                                    'pluginOptions' => [
                                                                        'displayOnly' => true,
                                                                        'filledStar' => '<span class="aicon aicon-star-full"></span>',
                                                                        'emptyStar' => '<span class="aicon aicon-star-empty"></span>',
                                                                        'showCaption' => false,
                                                                    ]
                                                                ]); ?>

                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="rating rating-<?= $modelUserPostMain['id']; ?>">
                                                                <h4 class="mt-0 mb-0">
                                                                	<?= Html::a(number_format($overallValue, 1), '#', ['class' => 'label label-success']); ?>
                                                                </h4>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <p class="review-description">
                                                        <?= $modelUserPostMain['text']; ?>
                                                    </p>
                                             	</div>
                                          	</div>

                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <ul class="works-grid works-grid-gut works-grid-5">

                                                        <?php
                                                        if (!empty($modelUserPostMain['userPostMains'])):

                                                            foreach ($modelUserPostMain['userPostMains'] as $modelUserPostMainChild): ?>

                                                                <li class="work-item gallery-photo-review">
                                                                    <div class="gallery-item post-gallery">
                                                                        <div class="gallery-image">
                                                                            <div class="work-image">
                                                                                <?= Html::img(Yii::$app->params['endPointLoadImage'] . 'user-post?image=' . $modelUserPostMainChild['image'] . '&w=200&h=200', ['class' => 'img-component']); ?>
                                                                            </div>
                                                                            <div class="work-caption">
                                                                                <div class="work-descr">
                                                                                	<a class="btn btn-d btn-small btn-xs btn-circle show-image" href="<?= Yii::$app->params['endPointLoadImage'] . 'user-post?image=' . $modelUserPostMainChild['image']; ?>"><i class="aicon aicon-zoomin"></i></a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>

                                                            <?php
                                                            endforeach;
                                                        endif; ?>

                                                    </ul>
                                                </div>
                                            </div>

                                            <?php
                        					$loveCount = !empty($modelUserPostMain['love_value']) ? $modelUserPostMain['love_value'] : 0;
                        					$commentCount = !empty($modelUserPostMain['userPostComments']) ? count($modelUserPostMain['userPostComments']) : 0;
                        					$photoCount = !empty($modelUserPostMain['userPostMains']) ? count($modelUserPostMain['userPostMains']) : 0;

                        					$loveSpanCount = '<span class="total-likes-review">' . $loveCount . '</span>';
                        					$commentSpanCount = '<span class="total-comments-review">' . $commentCount . '</span>';
                        					$photoSpanCount = '<span class="total-photos-review">' . $photoCount . '</span>';

                        					$selected = !empty($modelUserPostMain['userPostLoves'][0]) ? 'selected' : ''; ?>

                                            <div class="row visible-xs">
                                                <div class="col-xs-3">
                                                    <ul class="list-inline mt-0 mb-0">
                                                        <li>
                                                            <small><?= '<i class="aicon aicon-heart"></i> <span class="total-likes-review">' . $loveCount . '</span>' ?></small>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <ul class="list-inline mt-0 mb-0">
                                                        <li>
                                                            <small><?= $commentSpanCount . ' Comment' ?></small>
                                                        </li>
                                                        <li>
                                                            <small><?= $photoSpanCount . ' Photo' ?></small>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-tab-7 col-xs-12">
                                                    <ul class="list-inline list-review mt-0 mb-0">
                                                        <li>
                                                            <?= Html::a('<i class="aicon aicon-heart"></i> ' . $loveSpanCount . ' Like', ['action/submit-likes'] , ['class' => 'btn btn-raised btn-small btn-round likes-review-trigger ' . $selected . ' visible-tab']); ?>
                                                            <?= Html::a('<i class="aicon aicon-heart"></i> Like', ['action/submit-likes'], ['class' => 'btn btn-raised btn-small btn-round likes-review-trigger ' . $selected . ' visible-xs']); ?>
                                                        </li>
                                                        <li>
                                                            <?= Html::a('<i class="aicon aicon-bubbles"></i> ' . $commentSpanCount . ' Comment', '', ['class' => 'btn btn-raised btn-small btn-round comments-review-trigger visible-tab']); ?>
                                                            <?= Html::a('<i class="aicon aicon-bubbles"></i> Comment', '', ['class' => 'btn btn-raised btn-small btn-round comments-review-trigger visible-xs']); ?>
                                                        </li>
                                                        <li class="visible-xs-inline-block">
                                                            <?= Html::a('<i class="aicon aicon-share2"></i> ', '', ['class' => 'btn btn-raised btn-small btn-round share-review-trigger']); ?>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-tab-5 text-right visible-tab">
                                                    <ul class="list-inline list-review mt-0 mb-0">
                                                        <li>
                                                            <?= Html::a('<i class="aicon aicon-share2"></i> Share', '', ['class' => 'btn btn-raised btn-small btn-round share-review-trigger']); ?>
                                                        </li>
                                                    </ul>
                                            	</div>
                                            </div>

                                            <hr class="divider-w mt-10">

                                            <div class="row">
                                            	<div class="col-xs-12">
                                                	<div class="user-comment-review" id="comments-review-container">

                                                        <div class="input-group mt-10 mb-10">
                                                            <span class="input-group-text"><i class="aicon aicon-bubble"></i></span>
                                                            &nbsp;&nbsp;&nbsp;
                                                            <?= Html::textInput('comment_input', null, ['id' => 'input-comments-review', 'class' => 'form-control', 'placeholder' => Yii::t('app', 'Write a Comment')]); ?>
                                                        </div>

                                                        <div class="overlay" style="display: none;"></div>
                                                        <div class="loading-img" style="display: none;"></div>

                                                        <div class="comment-section">

                                                            <?php
                                                            foreach ($modelUserPostMain['userPostComments'] as $dataUserPostComment): ?>

                                                                <div class="comment-post">
                                                                    <div class="row mb-10">
                                                                        <div class="col-xs-12">
                                                                            <div class="widget">
                                                                                <div class="widget-comments-image">

                                                                                    <?php
                                                                                    $img = !empty($dataUserPostComment['user']['image']) ? $dataUserPostComment['user']['image'] . '&w=200&h=200' : 'default-avatar.png';

                                                                                    echo Html::a(Html::img(Yii::$app->params['endPointLoadImage'] . 'user?image=' . $img, [
                                                                                        'class' => 'img-responsive img-circle img-comment-thumb img-component'
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
                                                                </div>

                                                            <?php
                                                            endforeach; ?>

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
$this->registerCssFile(Yii::$app->homeUrl . 'lib/Magnific-Popup/dist/magnific-popup.css', ['depends' => 'yii\web\YiiAsset']);

GrowlCustom::widget();
frontend\components\RatingColor::widget();
frontend\components\Readmore::widget();
frontend\components\FacebookShare::widget();

$this->registerJs(GrowlCustom::messageResponse(), View::POS_HEAD);

$this->registerJsFile(Yii::$app->homeUrl . 'lib/Magnific-Popup/dist/jquery.magnific-popup.js', ['depends' => 'yii\web\YiiAsset']);

$jscript = '
    var reviewId = $(".user-post-main-id");

    ratingColor($(".rating"), "a");

    readmoreText({
        element: $(".review-description"),
        minChars: 500,
        ellipsesText: " . . . ",
        moreText: "See more",
        lessText: "See less",
    });

    $(".likes-review-trigger").on("click", function() {

        $.ajax({
            cache: false,
            type: "POST",
            data: {
                "user_post_main_id": reviewId.val()
            },
            url: $(this).attr("href"),
            success: function(response) {

                if (response.success) {

                    var loveValue = parseInt($(".total-likes-review").html());

                    if (response.is_active) {

                        $(".likes-review-trigger").addClass("selected");
                        $(".total-likes-review").html((loveValue + 1));
                    } else {

                        $(".likes-review-trigger").removeClass("selected");
                        $(".total-likes-review").html((loveValue - 1));
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

    $(".comments-review-trigger").on("click", function() {

        $("#comments-review-container").slideToggle();
        $("#input-comments-review").trigger("focus");

        return false;
    });

    $("#input-comments-review").on("keypress", function(event) {

        if (event.which == 13 && $(this).val().trim()) {

            $.ajax({
                cache: false,
                type: "POST",
                data: {
                    "user_post_main_id": reviewId.val(),
                    "text": $(this).val(),
                },
                url: "' . Yii::$app->urlManager->createUrl(['action/submit-comment']) . '",
                beforeSend: function(xhr) {

                    $(".comment-section").siblings(".overlay").show();
                    $(".comment-section").siblings(".loading-img").show();
                },
                success: function(response) {

                    if (response.success) {

                        $("#input-comments-review").val("");

                        $.ajax({
                            cache: false,
                            type: "POST",
                            data: {
                                "user_post_main_id": response.user_post_main_id
                            },
                            url: "' . Yii::$app->urlManager->createUrl(['data/post-comment']) . '",
                            success: function(response) {

                                $(".comment-section").html(response);

                                $(".total-comments-review").html(commentCount);
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

    $(".post-gallery").magnificPopup({

        delegate: "a.show-image",
        type: "image",
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0,1]
        },
        image: {
            titleSrc: "title",
            tError: "The image could not be loaded."
        }
    });

    $(".share-review-trigger").on("click", function() {

        facebookShare({
            ogUrl: "' . $ogUrl . '",
            ogTitle: "' . $ogTitle . '",
            ogDescription: "' . addslashes($ogDescription) . '",
            ogImage: "' . $ogImage . '",
            type: "Review"
        });

        return false;
    });
';

$this->registerJs($jscript); ?>