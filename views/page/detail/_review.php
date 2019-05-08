<?php

use common\components\Helper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $modelBusiness core\models\Business */
/* @var $modelRatingComponent core\models\RatingComponent */
/* @var $modelUserPostMain core\models\UserPostMain */
/* @var $modelPost frontend\models\Post */
/* @var $dataUserVoteReview array */
/* @var $queryParams array */

webview\assets\RateyoAsset::register($this);

$urlMyReviewDetail = [
    'page/review',
    'id' => $modelUserPostMain['id'],
]; ?>

<div class="row">
    <div class="col-12">
        <div class="card box">

            <div class="overlay" style="display: none;"></div>
            <div class="loading-img" style="display: none;"></div>

            <div class="box-title" id="title-write-review">
            	<div class="row">
            		<div class="col-6">
                    	<h6 class="mt-0 mb-0"><?= !empty($modelUserPostMain) ? \Yii::t('app', 'Your Review') : \Yii::t('app', 'Write a Review') ?></h6>
                	</div>
                	<div class="col-6 text-right text-danger" id="close-review-container">
                		<a class="text-danger" href=""><?= \Yii::t('app', 'Cancel') ?></a>
            		</div>
                </div>
            </div>

            <div class="box-content">
                <div class="form-group p-0" id="edit-review-container">

                	<?php
                	if (!empty(\Yii::$app->user->getIdentity())):

            	        $img = !empty(\Yii::$app->user->getIdentity()->image) ? \Yii::$app->user->getIdentity()->image . '&w=64&h=64' : 'default-avatar.png';
                    	$overallValue = !empty($dataUserVoteReview['overallValue']) ? $dataUserVoteReview['overallValue'] : 0;

                    	echo Html::hiddenInput('user_post_main_id', $modelUserPostMain['id'], ['class' => 'my-user-post-main-id']); ?>

                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-sm-7 col-12 mb-10">
                                        <div class="widget-posts-image">

                                            <?= Html::a(Html::img(\Yii::$app->params['endPointLoadImage'] . 'user?image=' . $img, [
                                                'class' => 'img-fluid rounded-circle'
                                            ]), ['user/user-profile', 'user' => \Yii::$app->user->getIdentity()->username]) ?>

                                        </div>

                                        <div class="widget-posts-body">
                                            <?= Html::a(\Yii::$app->user->getIdentity()->full_name, ['user/user-profile', 'user' => \Yii::$app->user->getIdentity()->username], ['class' => 'my-review-user-name']) ?>
                                            <br>
                                            <small class="my-review-created"><?= Helper::asRelativeTime($modelUserPostMain['created_at']) ?></small>
                                        </div>
                                    </div>
                                    <div class="col-sm-5 d-none d-sm-block d-md-none">
										<div class="rating my-rating">
                                        	<h4>
                                        		<?= Html::tag('span', number_format($overallValue, 1), ['class' => 'badge badge-success']); ?>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-12 d-block d-sm-none">
                                    	<ul class="list-inline mt-0 mb-0">
                                            <li class="list-inline-item">
                                                <div class="star-rating-review" data-rating="<?= number_format($overallValue, 1) ?>">
                                                </div>
                                            </li>
                                            <li class="list-inline-item">
                                                <div class="rating my-rating rating-<?= $modelUserPostMain['id']; ?>">
                                                    <h6 class="mt-0 mb-0">
                                                    	<?= Html::tag('span', number_format($overallValue, 1), ['class' => 'badge badge-success']); ?>
                                                    </h6>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <p class="my-review-description">
                                            <?= !empty($modelUserPostMain['text']) ? $modelUserPostMain['text'] : null ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <ul class="works-grid works-grid-gut works-grid-5" id="review-uploaded-photo">

                                            <?php
                                            if (!empty($modelUserPostMain['userPostMains'])):

                                                foreach ($modelUserPostMain['userPostMains'] as $i => $modelUserPostMainChild): ?>

                                                    <li id="image-<?= $modelUserPostMainChild['id'] ?>" class="work-item gallery-photo-review <?= $i > 4 ? 'd-none' : '' ?>">
                                                        <div class="gallery-item review-post-gallery">
                                                            <div class="gallery-image">
                                                                <div class="work-image">
                                                                    <?= Html::img(\Yii::$app->params['endPointLoadImage'] . 'user-post?image=' . $modelUserPostMainChild['image'] . '&w=72&h=72', ['class' => 'img-component']); ?>
                                                                </div>
                                                                <div class="work-caption">
                                                                    <div class="work-descr">

                                                                    	<?php
                                                                    	$hiddenPhotos = count($modelUserPostMain['userPostMains']) - ($i + 1);

                                                                    	if ($i == 4 && $hiddenPhotos != 0) {

                                                                    	    echo Html::a('+' . $hiddenPhotos, \Yii::$app->urlManager->createUrl($urlMyReviewDetail), ['class' => 'btn btn-raised btn-danger btn-small btn-xs btn-circle']);
                                                                    	    echo Html::a('<i class="aicon aicon-zoomin"></i>', \Yii::$app->params['endPointLoadImage'] . 'user-post?image=' . $modelUserPostMainChild['image'], ['class' => 'btn btn-raised btn-danger btn-small btn-xs btn-circle show-image d-none']);
                                                                    	} else {

                                                                    	    echo Html::a('<i class="aicon aicon-zoomin"></i>', \Yii::$app->params['endPointLoadImage'] . 'user-post?image=' . $modelUserPostMainChild['image'], ['class' => 'btn btn-raised btn-danger btn-small btn-xs btn-circle show-image']);
                                                                    	} ?>

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

                        	    $loveSpanCount = '<span class="my-total-likes-review">' . $loveCount . '</span>';
                        	    $commentSpanCount = '<span class="my-total-comments-review">' . $commentCount . '</span>';
                        	    $photoSpanCount = '<span class="my-total-photos-review">' . $photoCount . '</span>';

                        	    $selected = !empty($modelUserPostMain['userPostLoves'][0]) ? 'selected' : ''; ?>

                                <div class="row">
                                    <div class="col-3 d-block d-sm-none">
                                        <ul class="list-inline mt-0 mb-0">
                                            <li class="list-inline-item">
                                                <small><?= '<i class="aicon aicon-thumb"></i> <span class="my-total-likes-review">' . $loveCount . '</span>' ?></small>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-9 text-right d-block d-sm-none">
                                        <ul class="list-inline mt-0 mb-0">
                                            <li class="list-inline-item">
                                                <small><?= $commentSpanCount . ' Comment'?></small>
                                            </li>
                                            <li class="list-inline-item">
                                                <small><?= $photoSpanCount . ' Photo' ?></small>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 col-12">
                                        <ul class="list-inline list-review mt-0 mb-0">
                                            <li class="list-inline-item">

                                                <?= Html::a('<i class="aicon aicon-thumb"></i> ' . $loveSpanCount . ' Like', ['action/submit-likes'], [
                                                    'class' => 'btn btn-raised btn-small btn-round my-likes-review-trigger ' . $selected . ' d-none d-sm-block d-md-none'
                                                ]); ?>

                                                <?= Html::a('<i class="aicon aicon-thumb"></i> Like', ['action/submit-likes'], [
                                                    'class' => 'btn btn-raised btn-small btn-round my-likes-review-trigger ' . $selected . ' d-block d-sm-none'
                                                ]); ?>

                                            </li>
                                            <li class="list-inline-item">

                                                <?= Html::a('<i class="aicon aicon-bubbles"></i> ' . $commentSpanCount . ' Comment', '', [
                                                    'class' => 'btn btn-raised btn-small btn-round my-comments-review-trigger d-none d-sm-block d-md-none'
                                                ]); ?>

                                                <?= Html::a('<i class="aicon aicon-bubbles"></i> Comment', '', [
                                                    'class' => 'btn btn-raised btn-small btn-round my-comments-review-trigger d-block d-sm-none'
                                                ]); ?>

                                            </li>
                                            <li class="list-inline-item">
                                            	<a class="btn btn-raised btn-small btn-round d-block d-sm-none" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false">
                                                    <i class="aicon aicon-more"></i>
                                                </a>
                                                <div class="dropdown-menu pull-right review-btn">
                                                	<?= Html::a('<i class="aicon aicon-share1"></i>&nbsp;Share', \Yii::$app->urlManager->createAbsoluteUrl($urlMyReviewDetail), ['class' => 'share-my-review-trigger dropdown-item']); ?>
                                                   	<?= Html::a('<i class="aicon aicon-icon-edit-profile-new"></i>&nbsp;Edit', '', ['class' => 'edit-my-review-trigger dropdown-item']) ?>
                                                   	<?= Html::a('<i class="aicon aicon-icon-trash"></i>&nbsp;' . \Yii::t('app', 'Delete'), ['user-action/delete-user-post', 'id' => $modelUserPostMain['id']], ['class' => 'delete-my-review-trigger dropdown-item']) ?>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-6 text-right d-none d-sm-block d-md-none">
                                        <ul class="list-inline list-review mt-0 mb-0">
                                    		<li class="list-inline-item">
                                                <?= Html::a('<i class="aicon aicon-share1"></i> Share', \Yii::$app->urlManager->createAbsoluteUrl($urlMyReviewDetail), ['class' => 'btn btn-raised btn-small btn-round share-my-review-trigger']) ?>
                                            </li>
                                            <li class="list-inline-item">
                                                <?= Html::a('<i class="aicon aicon-icon-edit-profile-new"></i> Edit', '', ['class' => 'btn btn-raised btn-small btn-round edit-my-review-trigger']) ?>
                                            </li>
                                            <li class="list-inline-item">
                                                <?= Html::a('<i class="aicon aicon-icon-trash"></i> ' . \Yii::t('app', 'Delete'), ['user-action/delete-user-post', 'id' => $modelUserPostMain['id']], ['class' => 'btn btn-raised btn-small btn-round delete-my-review-trigger']) ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <hr class="divider-w mt-10">

                                <div class="row">
                                	<div class="col-12">
                                    	<div class="user-comment-review" id="my-comments-review-container">
                                            <div class="input-group mt-10 mb-10">
                                                <span class="input-group-text"><i class="aicon aicon-bubble"></i></span>
                                                &nbsp;&nbsp;&nbsp;
                                                <?= Html::textInput('comment_input', null, ['id' => 'input-my-comments-review', 'class' => 'form-control', 'placeholder' => 'Tuliskan komentar']); ?>
                                            </div>

                                            <div class="overlay" style="display: none;"></div>
                                            <div class="loading-img" style="display: none;"></div>

                                            <div class="my-comment-section">
                                                <div class="comment-container">

                                                    <?php
                                                    if (!empty($modelUserPostMain['userPostComments'])):

                                                        foreach ($modelUserPostMain['userPostComments'] as $dataUserPostComment): ?>

                                                            <div class="comment-post">
                                                                <div class="row mb-10">
                                                                    <div class="col-12">
                                                                        <div class="widget-comments-image">

                                                                            <?php
                                                                            $img = !empty($dataUserPostComment['user']['image']) ? $dataUserPostComment['user']['image'] . '&w=64&h=64' : 'default-avatar.png';

                                                                            echo Html::a(Html::img(\Yii::$app->params['endPointLoadImage'] . 'user?image=' . $img, [
                                                                                'class' => 'img-fluid rounded-circle'
                                                                            ]), ['user/user-profile', 'user' => $dataUserPostComment['user']['username']]); ?>

                                                                        </div>

                                                                        <div class="widget-comments-body">
                                                                            <?= Html::a($dataUserPostComment['user']['full_name'], \Yii::$app->urlManager->createUrl(['user/user-profile', 'user' => $dataUserPostComment['user']['username']])); ?>&nbsp;&nbsp;&nbsp;
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
                                                        endforeach;
                                                    endif; ?>

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

                <div id="write-review-trigger">
                	<div class="input-group mt-10">
                        <span class="input-group-text"><i class="aicon aicon-pencil"></i></span>
                        &nbsp;&nbsp;&nbsp;
                        <?= Html::textInput('', null, [
                            'class' => 'form-control',
                            'placeholder' => \Yii::t('app', 'Share your experience here')
                        ]); ?>

                    </div>
                </div>

                <?php
                $form = ActiveForm::begin([
                    'id' => 'review-form',
                    'action' => ['action/submit-review'],
                    'enableClientValidation' => false,
                    'fieldConfig' => [
                        'template' => '{input}',
                    ]
                ]);

                    echo Html::hiddenInput('business_id', $modelBusiness['id'], ['id' => 'business_id']); ?>

                    <div class="row" id="write-review-container">
                        <div class="col-sm-6 col-12 mb-20">
                            <div class="row">
                                <div class="col-6">

                                	<?= Html::hiddenInput('temp_overall_rating', null, ['class' => 'temp-overall-rating']) ?>

                                    <span><strong>Overall Rating</strong></span>
                                	<div class="overall-rating mt-10"></div>
                                </div>
                                <div class="col-6 pt-10">
                                    <h3 class="rating-overall mt-0 mb-0"></h3>
                                </div>
                            </div>

                            <div class="row mt-20">
                                <div class="col-12">
                                    <span><strong>O R</strong></span>
                                </div>
                            </div>

                            <div class="row form-rating">
                                <div class="col-12">

                                    <?php
                                    if (!empty($modelRatingComponent)):

                                        foreach ($modelRatingComponent as $dataRatingComponent):

                                            if (!empty($dataUserVoteReview['ratingComponentValue'])) {

                                                foreach ($dataUserVoteReview['ratingComponentValue'] as $ratingComponentId => $vote_value) {

                                                    if ($dataRatingComponent['id'] == $ratingComponentId) {

                                                        $valueRatingComponent = $vote_value;
                                                    }
                                                }
                                            } ?>

                                            <div class="row">
                                                <div class="col-6">

                                                	<?php
                                                    echo Html::hiddenInput('rating_component_id', $dataRatingComponent['id'], ['class' => 'rating-component-id', 'data-prior' => '']);
                                                    echo Html::hiddenInput('temp_rating_' . $dataRatingComponent['id'], null, ['class' => 'temp-rating-' . $dataRatingComponent['id']]);

                                                    echo $form->field($modelPost, '[review]rating[' . $dataRatingComponent['id'] . ']')->hiddenInput(['value' => !empty($valueRatingComponent) ? $valueRatingComponent : null]); ?>

													<div id="rating-<?= $dataRatingComponent['id'] ?>" class="component-rating" data-rating=<?= !empty($valueRatingComponent) ? $valueRatingComponent : 0 ?>></div>
                                                </div>

                                                <div class="col-6 business-rating-components p-15">

                                                	<?php
													if (!empty($valueRatingComponent)) {

													    echo $valueRatingComponent == 1 ? '<span>1</span> &nbsp;&nbsp;&nbsp;' . \Yii::t('app', $dataRatingComponent['name']) : '<span>' . $valueRatingComponent . '</span>&nbsp;&nbsp;&nbsp;' . \Yii::t('app', $dataRatingComponent['name']);
													} else {

													    echo '<span></span>&nbsp;&nbsp;&nbsp;' . \Yii::t('app', $dataRatingComponent['name']);
													} ?>

                                                    <div class="rating-<?= $dataRatingComponent['id'] ?>"></div>
                                                </div>
                                            </div>

                                        <?php
                                        endforeach;
                                    endif; ?>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-12">
                            <div class="form-group">

                                <?= $form->field($modelPost, '[review]text')->textarea([
                                    'class' => 'form-control',
                                    'placeholder' => \Yii::t('app', 'What do you like about this place? Criticism and suggestions for improvement?'),
                                    'rows' => 6,
                                ]); ?>

                            </div>

                            <div class="form-group">

                                <div class="row" id="form-photos-review-container">
                                    <div class="col-12">
                                        <ul class="works-grid works-grid-gut works-grid-5" id="form-review-uploaded-photo">

                                            <?php
                                            if (!empty($modelUserPostMain['userPostMains'])):

                                                foreach ($modelUserPostMain['userPostMains'] as $modelUserPostMainChild):

                                                    echo Html::hiddenInput('user_post_main_child_id', $modelUserPostMainChild['id'], ['class' => 'user-post-main-child-id']); ?>

                                                    <li id="image-<?= $modelUserPostMainChild['id'] ?>" class="work-item gallery-photo-review text-center">
                                                        <div class="gallery-item review-post-gallery mb-10">
                                                            <div class="gallery-image">
                                                                <div class="work-image">
                                                                    <?= Html::img(\Yii::$app->params['endPointLoadImage'] . 'user-post?image=' . $modelUserPostMainChild['image'] . '&w=72&h=72', ['class' => 'img-component']); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="checkbox">
                                                        	<?= Html::checkbox('ImageReviewDelete[]', false, ['class' => 'report-subject', 'label' => '<i class="aicon aicon-icon-trash"></i>', 'value' => $modelUserPostMainChild['id']]) ?>
                                                    	</div>

                                                    </li>

                                                <?php
                                                endforeach;
                                            endif; ?>

                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">

                                <?= $form->field($modelPost, '[photo]image[]')->fileInput([
                                    'id' => 'post-photo-input',
                                    'accept' => 'image/*',
                                    'multiple' => true,
                                ]); ?>

                            </div>
                            <div class="form-group">
                                <?= Html::submitButton('<i class="aicon aicon-icon-share"></i> Post review', ['id' => 'submit-write-review', 'class' => 'btn btn-raised btn-standard btn-round']) ?>
                                <?= Html::a('<i class="aicon aicon-cross"></i> ' . \Yii::t('app', 'Cancel'), '', ['id' => 'cancel-write-review', 'class' => 'btn btn-raised btn-standard btn-round']) ?>
                            </div>
                        </div>
                    </div>

                <?php
                ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>

<div class="row mt-10">
    <div class="col-12">
        <div class="card box">
            <div class="box-title">
                <h6 class="mt-0 mb-0 inline-block"><?= \Yii::t('app', 'Review') ?></h6>
            </div>

            <hr class="divider-w">

            <div class="box-content">
                <div class="review-section"></div>
            </div>
        </div>
    </div>
</div>

<ul id="container-temp-uploaded-photo" class="d-none">
    <li class="work-item gallery-photo-review">
        <div class="gallery-item review-post-gallery">
            <div class="gallery-image">
                <div class="work-image"></div>
                <div class="work-caption">
                    <div class="work-descr"></div>
                </div>
            </div>
        </div>
    </li>
</ul>

<?php
$jscript = '
    var prevReview;
    var cancelWrite;
    var isSetOverall;

    function getBusinessRating(business_id) {

        $.ajax({
            cache: false,
            type: "POST",
            data: {
                "business_id": business_id
            },
            url: "' . \Yii::$app->urlManager->createUrl(['data/business-rating']) . '",
            success: function(response) {

                $(".business-rating").html(response);
            },
            error: function(xhr, ajaxOptions, thrownError) {

                messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");
            }
        });
    }

    function setOverall() {

        var overall = 0;

        $(".rating-component-id").each(function() {

            var rating = parseInt($(this).parent().find("#rating-" + $(this).val()).rateYo("rating"));

            overall += rating;
        });

        overall = overall / parseInt($(".rating-component-id").length);

        if (!isNaN(overall)) {

            overallRating.rateYo("rating", overall);
            $(".rating-overall").html(parseFloat(overall.toFixed(1)));
        } else {

            $(".rating-overall").html("0");
        }
    }

    function initMagnificPopupMyReview() {

        $("#review-uploaded-photo .review-post-gallery").magnificPopup({

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
    }

    $("#write-review-container").hide();
    $("#close-review-container").hide();

    $("#my-comments-review-container").hide();

    $.ajax({
        cache: false,
        type: "GET",
        url: "' . \Yii::$app->urlManager->createUrl(['data/post-review', 'id' => $modelBusiness['id']]) . '",
        success: function(response) {

            $(".review-section").html(response);
        },
        error: function(xhr, ajaxOptions, thrownError) {

            messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");
        }
    });

    initMagnificPopupMyReview();

    ratingColor($(".my-rating"), "a");

    $(".star-rating-review").rateYo({
        rating: $(".star-rating-review").data("rating"),
        starWidth: "18px",
        readOnly: true,
        "starSvg": "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\"><path d=\"M0 0h24v24H0z\" fill=\"none\"/><path d=\"M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z\"/><path d=\"M0 0h24v24H0z\" fill=\"none\"/></svg>"
    });

    var overallRating = $(".overall-rating").rateYo({
        fullStar: true,
        starWidth: "25px",
        "starSvg": "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\"><path d=\"M0 0h24v24H0z\" fill=\"none\"/><path d=\"M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z\"/><path d=\"M0 0h24v24H0z\" fill=\"none\"/></svg>"
    });

    $(".component-rating").rateYo({
        fullStar: true,
        starWidth: "25px",
        "starSvg": "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\"><path d=\"M0 0h24v24H0z\" fill=\"none\"/><path d=\"M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z\"/><path d=\"M0 0h24v24H0z\" fill=\"none\"/></svg>"
    });

    readmoreText({
        element: $(".my-review-description"),
        minChars: 500,
        ellipsesText: " . . . ",
        moreText: "See more",
        lessText: "See less",
    });

    $(".total-review").html("' . (!empty($modelUserPostMain) ? 1 : 0) . '");

    $(".overall-rating").rateYo().on("rateyo.change", function(e, data) {

        isSetOverall = false;
    });

    $(".overall-rating").rateYo().on("rateyo.set", function(e, data) {

        if (!isSetOverall) {

            $(".rating-overall").html(parseFloat(data.rating));

            $(".rating-component-id").each(function() {

                $(this).parent().find("#rating-" + $(this).val()).rateYo("rating", data.rating);
                $(this).parent().find("#post-review-rating-" + $(this).val()).val(data.rating);
            });
        }
    });

    $(".rating-component-id").each(function() {

        var thisObj = $(this);

        thisObj.parent().find("#rating-" + thisObj.val()).rateYo().on("rateyo.change", function(e, data) {

            isSetOverall = true;
        });

        thisObj.parent().find("#rating-" + thisObj.val()).rateYo().on("rateyo.set", function(e, data) {

            if (data.rating == 0) {

                thisObj.parent().siblings(".business-rating-components").children("span").html("");
            } else {

                thisObj.parent().siblings(".business-rating-components").children("span").html(data.rating);
            }

            if (isSetOverall) {

                $("#post-review-rating-" + thisObj.val()).val(data.rating);

                setOverall();
            }
        });
    });

    $("#close-review-container > a, #cancel-write-review").on("click", function(event) {

        $("#write-review-container, #close-review-container").fadeOut(100, function() {

            if (cancelWrite) {

                $("#write-review-trigger").fadeIn();
                $("#edit-review-container").fadeOut();
            } else {

                $("#write-review-trigger").fadeOut();
                $("#edit-review-container").fadeIn();
            }

            $("html, body").animate({ scrollTop: $("#title-write-review").offset().top }, "slow");
        });

        var tempOverallRating = $(".temp-overall-rating").val();

        if (tempOverallRating == "") {

            overallRating.rateYo("rating", 0);
            $(".rating-overall").html(parseFloat(parseFloat(overallRating.rateYo("rating")).toFixed(1)));
        } else {

            overallRating.rateYo("rating", tempOverallRating);
            $(".rating-overall").html(parseFloat(parseFloat(tempOverallRating).toFixed(1)));
        }

        $(".rating-component-id").each(function() {

            if ($(".temp-rating-" + $(this).val()).val() == "") {

                $(this).parent().find("#rating-" + $(this).val()).rateYo("rating", 0);
                $("#post-review-rating-" + $(this).val()).val($("#rating-" + $(this).val()).val());
            } else {

                $(this).parent().find("#rating-" + $(this).val()).rateYo("rating", $(this).siblings(".component-rating").rateYo("rating"));
                $("#post-review-rating-" + $(this).val()).val($(this).siblings(".component-rating").rateYo("rating"));
            }
        });

        $("#post-review-text").val(prevReview);
        $("#post-photo-input").val("");

        return false;
    });

    $("#write-review-trigger").on("click", function(event) {

        var thisObj = $(this);

        $.ajax({
            cache: false,
            type: "POST",
            url: "' . \Yii::$app->urlManager->createUrl(['redirect/write-review']) . '",
            success: function(response) {

                $("#write-review-trigger").fadeOut(100, function() {

                    prevReview = $("#post-review-text").val();

                    $("#write-review-container").fadeIn();
                    $("#close-review-container").fadeIn();
                    $("#post-review-text").trigger("focus");
                });

                cancelWrite = true;
            },
            error: function(xhr, ajaxOptions, thrownError) {

                messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");
            }
        });
    });

    $(".share-my-review-trigger").on("click", function(event) {

        var url = $(this).attr("href");
        var title = "Rating " + $("#edit-review-container").find(".my-rating > h3").text().trim() + " untuk " + $(".business-name").text().trim();
        var description = $(".my-review-description").text();
        var image = "' . \Yii::$app->params['endPointLoadImage'] . 'user-post?image=";

        if ($("#review-uploaded-photo").children(".gallery-photo-review").length) {

            image = window.location.protocol + "//" + window.location.hostname + $("#review-uploaded-photo").children(".gallery-photo-review").eq(0).find(".show-image").attr("href");
        }

        facebookShare({
            ogUrl: url,
            ogTitle: title,
            ogDescription: description,
            ogImage: image,
            type: "Review"
        });

        $(this).parent().siblings("a").dropdown("toggle");

        return false;
    });

    $(".edit-my-review-trigger").on("click", function(event) {

        $("#edit-review-container").fadeOut(100, function() {

            prevReview = $("#post-review-text").val();

            $("#write-review-container").fadeIn();
            $("#close-review-container").fadeIn();

            $("#post-review-text").trigger("focus");
        });

        cancelWrite = false;

        $(this).parent().siblings("a").dropdown("toggle");

        isSetOverall = true;
        overallRating.rateYo("rating", $(".star-rating-review").rateYo("rating"));

        $(".rating-component-id").each(function() {

            $(this).siblings(".component-rating").rateYo("rating", $(this).siblings(".component-rating").data("rating"));

            var dataRating = $(this).siblings(".component-rating").rateYo("rating");

            $(this).parent().find("#rating-" + $(this).val()).rateYo("rating", dataRating);
            $("#post-review-rating-" + $(this).val()).val(dataRating);
        });

        return false;
    });

    $(".delete-my-review-trigger").on("click", function(event) {

        $("#modal-confirmation").find("#btn-delete").data("href", $(this).attr("href"));

        $("#modal-confirmation").find("#btn-delete").on("click", function() {

            $("#modal-confirmation").find("#btn-delete").off("click");

            $("#modal-confirmation").modal("hide");

            $.ajax({
                cache: false,
                type: "POST",
                url: $(this).data("href"),
                beforeSend: function(xhr) {

                    $("#title-write-review").siblings(".overlay").show();
                    $("#title-write-review").siblings(".loading-img").show();
                },
                success: function(response) {

                    $("#title-write-review").find("h4").html("' . \Yii::t('app', 'Write a Review') . '");

                    if (response.success) {

                        var totalUserPost = parseInt($(".total-review").html());
                        $(".total-review").html(totalUserPost - 1);

                        $("#edit-review-container").fadeOut(100, function() {

                            $("#write-review-trigger").fadeIn();
                        });

                        $(".temp-overall-rating").val(0);
                        overallRating.rateYo("rating", 0);

                        $(".rating-component-id").each(function() {

                            $(".temp-rating-" + $(this).val()).val(0);
                            $(this).parent().find("#rating-" + $(this).val()).rateYo("rating", 0);
                            $("#post-review-rating-" + $(this).val() + "").val($("#rating-" + $(this).val()).val());
                        });

                        $("#post-review-text").val("");

                        $("#form-review-uploaded-photo").children().remove();
                        $("#review-uploaded-photo").children().remove();
                        $(".my-total-photos-review").html(0);

                        if ($(".my-likes-review-trigger").hasClass("selected")) {

                            $(".my-likes-review-trigger").removeClass("selected");
                            $(".my-total-likes-review").html(parseInt($(".my-total-likes-review").html()) - 1);
                        }

                        getBusinessRating($("#business_id").val());

                        messageResponse(response.icon, response.title, response.message, response.type);
                    } else {

                        messageResponse(response.icon, response.title, response.message, response.type);
                    }

                    $("#title-write-review").siblings(".overlay").hide();
                    $("#title-write-review").siblings(".loading-img").hide();
                },
                error: function(xhr, ajaxOptions, thrownError) {

                    messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");

                    $("#title-write-review").siblings(".overlay").show();
                    $("#title-write-review").siblings(".loading-img").show();
                }
            });
        });

        $("#modal-confirmation").modal("show");

        $(this).parent().siblings("a").dropdown("toggle");

        return false;
    });

    $("form#review-form").on("beforeSubmit", function(event) {

        var thisObj = $(this);

        if (overallRating.rateYo("rating") == 0) {

            messageResponse("aicon aicon-icon-info", "Gagal Submit Review", "Harap mengisi rating terlebih dahulu", "danger");
        } else {

            $("#title-write-review").siblings(".overlay").show();
            $("#title-write-review").siblings(".loading-img").show();

            var formData = new FormData(this);

            $.ajax({
                cache: false,
                contentType: false,
                processData: false,
                type: "POST",
                data: formData,
                url: thisObj.attr("action"),
                success: function(response) {

                    $("#post-photo-input").val("");

                    if (response.success) {

                        if (!response.updated) {

                            $(".total-review").html(parseInt($(".total-review").html()) + 1);
                        }

                        $(".my-rating").find("span").html(parseFloat($(".rating-overall").text()).toFixed(1));
                        $(".star-rating-review").rateYo("rating", $(".rating-overall").text());

                        $(".my-review-user-name").html(response.user);
                        $(".my-review-created").html(response.userCreated);
                        $(".my-review-description").html(response.userPostMain.text);

                        $(".share-my-review-trigger").attr("href", response.shareUrl.absolute);
                        $(".delete-my-review-trigger").attr("href", response.deleteUrlReview);

                        readmoreText({
                            element: $(".my-review-description"),
                            minChars: 500,
                            ellipsesText: " . . . ",
                            moreText: "See more",
                            lessText: "See less",
                        });

                        $.each(response.deletedPhotoId, function(i, deletedPhotoId) {

                            $("#review-uploaded-photo").find("li#image-" + deletedPhotoId).remove();
                            $("#form-review-uploaded-photo").find("li#image-" + deletedPhotoId).remove();

                            $(".my-total-photos-review").html(parseInt($(".my-total-photos-review").html()) - 1);
                        });

                        $.each(response.userPostMainPhoto, function(i, userPostMainPhoto) {

                            var cloneImageReviewContainer = $("#container-temp-uploaded-photo").find("li").clone();
                            var cloneImageFormContainer = $("#container-temp-uploaded-photo").find("li").clone();

                            cloneImageReviewContainer.attr("id", "image-" + userPostMainPhoto.id);
                            cloneImageReviewContainer.find(".review-post-gallery").find(".work-image").html("<img class=\"img-component\" src=\"" + userPostMainPhoto.image + "\" title=\"\">");
                            cloneImageReviewContainer.find(".review-post-gallery").find(".work-caption").find(".work-descr").html("<a class=\"btn btn-raised btn-danger btn-small btn-xs btn-circle show-image\" href=\"" + userPostMainPhoto.image.replace("&w=72&h=72", "") + "\"><i class=\"aicon aicon-zoomin\"></i></a>");
                            cloneImageReviewContainer.appendTo($("#review-uploaded-photo"));

                            cloneImageFormContainer.addClass("text-center");
                            cloneImageFormContainer.find(".review-post-gallery").addClass("mb-10");
                            cloneImageFormContainer.attr("id", "image-" + userPostMainPhoto.id);
                            cloneImageFormContainer.find(".review-post-gallery").find(".work-image").html("<img class=\"img-component\" src=\"" + userPostMainPhoto.image + "\" title=\"\">");
                            cloneImageFormContainer.append("<div class=\"checkbox\"><label><input class=\"new-image-" + userPostMainPhoto.id + "\" type=\"checkbox\" name=\"ImageReviewDelete[]\" value=\"" + userPostMainPhoto.id + "\"><span class=\"checkbox-decorator\"><span class=\"check\"></span><div class=\"ripple-container\"></div></span> <i class=\"aicon aicon-icon-trash\"></i></label></div>");
                            cloneImageFormContainer.appendTo($("#form-review-uploaded-photo"));
                        });

                        var countLi = $("#review-uploaded-photo > li").length;

                        $("#review-uploaded-photo > li").each(function (i) {

                            if (i > 4) {

                                $(this).addClass("d-block");
                            } else {

                                $(this).removeClass("d-block");
                            }

                            var hiddenPhotos = countLi - (i + 1);

                            if (i == 4 && hiddenPhotos != 0) {

                                $(this).find(".review-post-gallery").find(".work-caption").find(".work-descr").html("<a class=\"btn btn-raised btn-danger btn-small btn-xs btn-circle show-image d-none\" href=\"" + $(this).find("a.show-image").attr("href") + "\"><i class=\"aicon aicon-zoomin\"></i></a>");
                                $(this).find(".review-post-gallery").find(".work-caption").find(".work-descr").append("<a class=\"btn btn-raised btn-danger btn-small btn-xs btn-circle\" href=\"" + response.shareUrl.notAbsolute + "\">+" + hiddenPhotos + "</a>");
                            } else {

                                $(this).find(".review-post-gallery").find(".work-caption").find(".work-descr").html("<a class=\"btn btn-raised btn-danger btn-small btn-xs btn-circle show-image\" href=\"" + $(this).find("a.show-image").attr("href") + "\"><i class=\"aicon aicon-zoomin\"></i></a>");
                            }
                        });

                        var tempOverall = 0;

                        $(".rating-component-id").each(function() {

                            var tempRating = $("#post-review-rating-" + $(this).val() + "").val();

                            tempOverall += parseInt(tempRating);

                            $(".temp-rating-" + $(this).val()).val(tempRating);
                            $(this).siblings(".component-rating").data("rating", tempRating);
                        });

                        $(".temp-overall-rating").val(tempOverall / parseInt($(".rating-component-id").length));

                        getBusinessRating($("#business_id").val());
                        getUserPhoto($("#business_id").val());
                        initMagnificPopupMyReview();

                        ratingColor($(".my-rating"), "a");

                        $("#edit-review-container").find(".my-total-photos-review").html(parseInt($("#edit-review-container").find(".my-total-photos-review").html()) + parseInt(response.userPostMainPhoto.length));
                        $("#edit-review-container").find(".my-total-comments-review").html(response.commentCount);

                        $(".my-comment-section").html(response.userPostComments);

                        $("#edit-review-container").find(".my-user-post-main-id").val(response.userPostMain.id);

                        $("#title-write-review").find("h4").html("' . \Yii::t('app', 'Your Review') . '");

                        $("#write-review-container, #close-review-container").fadeOut(100, function() {

                            $("#edit-review-container").show();
                            $("html, body").animate({ scrollTop: $("#title-write-review").offset().top }, "slow");
                        });

                        messageResponse(response.icon, response.title, response.message, response.type);
                    } else {

                        messageResponse(response.icon, response.title, response.message, response.type);
                    }

                    $("#title-write-review").siblings(".overlay").hide();
                    $("#title-write-review").siblings(".loading-img").hide();
                },
                error: function (xhr, ajaxOptions, thrownError) {

                    messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");

                    $("#title-write-review").siblings(".overlay").hide();
                    $("#title-write-review").siblings(".loading-img").hide();
                }
            });
        }

        return false;
    });

    $(".my-likes-review-trigger").on("click", function() {

        $.ajax({
            cache: false,
            type: "POST",
            data: {
                "user_post_main_id": $(".my-user-post-main-id").val()
            },
            url: $(this).attr("href"),
            success: function(response) {

                if (response.success) {

                    var loveValue = parseInt($(".my-total-likes-review").html());

                    if (response.is_active) {

                        $(".my-likes-review-trigger").addClass("selected");
                        $(".my-total-likes-review").html(loveValue + 1);
                    } else {

                        $(".my-likes-review-trigger").removeClass("selected");
                        $(".my-total-likes-review").html(loveValue - 1);
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

    $(".my-comments-review-trigger").on("click", function() {

        $("#my-comments-review-container").slideToggle();
        $("#input-my-comments-review").trigger("focus");

        return false;
    });

    $("#input-my-comments-review").on("keypress", function(event) {

        if (event.which == 13 && $(this).val().trim()) {

            $.ajax({
                cache: false,
                type: "POST",
                data: {
                    "user_post_main_id": $(".my-user-post-main-id").val(),
                    "text": $(this).val(),
                },
                url: "' . \Yii::$app->urlManager->createUrl(['action/submit-comment']) . '",
                beforeSend: function(xhr) {

                    $(".my-comment-section").siblings(".overlay").show();
                    $(".my-comment-section").siblings(".loading-img").show();
                },
                success: function(response) {

                    if (response.success) {

                        $("#input-my-comments-review").val("");

                        $.ajax({
                            cache: false,
                            type: "POST",
                            data: {
                                "user_post_main_id": response.user_post_main_id
                            },
                            url: "' . \Yii::$app->urlManager->createUrl(['data/post-comment']) . '",
                            success: function(response) {

                                $(".my-comment-section").html(response);

                                $(".my-total-comments-review").html(commentCount);
                            },
                            error: function(xhr, ajaxOptions, thrownError) {

                                messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");
                            }
                        });
                    } else {

                        messageResponse(response.icon, response.title, response.message, response.type);
                    }

                    $(".my-comment-section").siblings(".overlay").hide();
                    $(".my-comment-section").siblings(".loading-img").hide();
                },
                error: function (xhr, ajaxOptions, thrownError) {

                    messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");

                    $(".my-comment-section").siblings(".overlay").hide();
                    $(".my-comment-section").siblings(".loading-img").hide();
                }
            });
        }
    });

    $(".review-section").on("click", ".user-likes-review-trigger", function() {

        var thisObj = $(this);

        $.ajax({
            cache: false,
            type: "POST",
            data: {
                "user_post_main_id": thisObj.parents(".review-post").find(".user-post-main-id").val()
            },
            url: thisObj.attr("href"),
            success: function(response) {

                if (response.success) {

                    var loveValue = parseInt(thisObj.parents(".review-post").find(".total-likes-review").html());

                    if (response.is_active) {

                        thisObj.addClass("selected");
                        thisObj.parents(".review-post").find(".total-likes-review").html(loveValue + 1);
                    } else {

                        thisObj.removeClass("selected");
                        thisObj.parents(".review-post").find(".total-likes-review").html(loveValue - 1);
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

    $(".review-section").on("click", ".user-comments-review-trigger", function() {

        $(this).parents(".review-post").find(".user-comment-review").slideToggle();
        $(this).parents(".review-post").find(".input-comments-review").trigger("focus");

        return false;
    });

    $(".review-section").on("keypress", ".input-comments-review", function(event) {

        var thisObj = $(this);

        if (event.which == 13 && thisObj.val().trim()) {

            $.ajax({
                cache: false,
                type: "POST",
                data: {
                    "user_post_main_id": thisObj.parents(".review-post").find(".user-post-main-id").val(),
                    "text": $(this).val(),
                },
                url: "' . \Yii::$app->urlManager->createUrl(['action/submit-comment']) . '",
                beforeSend: function(xhr) {

                    thisObj.parent().siblings(".overlay").show();
                    thisObj.parent().siblings(".loading-img").show();
                },
                success: function(response) {

                    if (response.success) {

                        thisObj.val("");

                        $.ajax({
                            cache: false,
                            type: "POST",
                            data: {
                                "user_post_main_id": response.user_post_main_id
                            },
                            url: "' . \Yii::$app->urlManager->createUrl(['data/post-comment']) . '",
                            success: function(response) {

                                thisObj.parent().siblings(".comment-section").html(response);

                                thisObj.parents(".review-post").find("span.total-comments-review").html(commentCount);
                            },
                            error: function(xhr, ajaxOptions, thrownError) {

                                messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");
                            }
                        });
                    } else {

                        messageResponse(response.icon, response.title, response.message, response.type);
                    }

                    thisObj.parent().siblings(".overlay").hide();
                    thisObj.parent().siblings(".loading-img").hide();
                },
                error: function (xhr, ajaxOptions, thrownError) {

                    messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");

                    thisObj.parent().siblings(".overlay").hide();
                    thisObj.parent().siblings(".loading-img").hide();
                }
            });
        }
    });

    $(".review-section").on("click", ".share-review-trigger", function() {

        var url = $(this).attr("href");
        var title = "Rating " + $(this).parents(".review-post").find(".rating > h3").text().trim() + " untuk " + $(".business-name").text().trim();
        var description = $(this).parents(".review-post").find(".review-description").text();
        var image = "' . \Yii::$app->params['endPointLoadImage'] . 'user-post?image=";

        var userPhotoList = $(this).parents(".review-post").find(".gallery-photo-review");

        if (userPhotoList.length) {

            image = window.location.protocol + "//" + window.location.hostname + userPhotoList.eq(0).find(".work-image").children().attr("src").replace("&w=72&h=72", "");
        }

        facebookShare({
            ogUrl: url,
            ogTitle: title,
            ogDescription: description,
            ogImage: image,
            type: "Review"
        });

        return false;
    });

    $(".my-rating").children().children(".label").on("click", function() {

        return false;
    });
';

if (!empty($dataUserVoteReview['overallValue'])) {

    $jscript .= '
        var overall = ' . $dataUserVoteReview['overallValue'] . ';

        $(".rating-overall").html(parseFloat(overall.toFixed(1)));
    ';
}

if (!empty($modelUserPostMain)) {

    $jscript .= '
        $("#write-review-trigger").hide();
    ';
} else {

    $jscript .= '
        $("#edit-review-container").hide();
    ';
}

$this->registerJs($jscript); ?>