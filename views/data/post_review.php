<?php

use common\components\Helper;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $pagination yii\data\Pagination */
/* @var $startItem int */
/* @var $endItem int */
/* @var $totalCount int */
/* @var $modelUserPostMain core\models\UserPostMain */

webview\assets\RateyoAsset::register($this);

Pjax::begin([
    'enablePushState' => false,
    'linkSelector' => '#pagination-review a',
    'options' => ['id' => 'pjax-review-container'],
    'timeout' => 7000,
]);

$linkPager = LinkPager::widget([
    'pagination' => $pagination,
    'maxButtonCount' => 5,
    'prevPageLabel' => false,
    'nextPageLabel' => false,
    'firstPageLabel' => '<i class="aicon aicon-icon-left-angle-semantic"></i>',
    'lastPageLabel' => '<i class="aicon aicon-icon-right-angle-semantic"></i>',
    'options' => ['id' => 'pagination-review', 'class' => 'pagination'],
]); ?>

<div class="row mt-10 mb-20">
    <div class="col-sm-6 col-12 mb-10">

        <?= Yii::t('app', 'Showing {startItem} - {endItem} of {totalCount} results', ['startItem' => $startItem, 'endItem' => $endItem, 'totalCount' => $totalCount]) ?>

    </div>
    <div class="col-sm-6 d-none d-sm-block text-right">

        <?= $linkPager; ?>

    </div>
    <div class="col-12 d-block d-sm-none">

        <?= $linkPager; ?>

    </div>
</div>

<div class="row" style="position: relative;">
	<div class="post-review-container">

		<div class="overlay" style="display: none;"></div>
		<div class="loading-img" style="display: none;"></div>

        <?php
        if (!empty($modelUserPostMain)):

            foreach ($modelUserPostMain as $dataUserPostMain):

                $img = !empty($dataUserPostMain['user']['image']) ? $dataUserPostMain['user']['image'] . '&w=64&h=64' : 'default-avatar.png';

                $totalVoteValue = 0;
                $ratingComponent = [];

                if (!empty($dataUserPostMain['userVotes'])) {

                    foreach ($dataUserPostMain['userVotes'] as $dataUserVote) {

                        if (!empty($dataUserVote['ratingComponent'])) {

                            $totalVoteValue += $dataUserVote['vote_value'];

                            $ratingComponent[$dataUserVote['rating_component_id']] = $dataUserVote;
                        }
                    }
                }

                $overallValue = !empty($totalVoteValue) && !empty($ratingComponent) ? ($totalVoteValue / count($ratingComponent)) : 0;

                ksort($ratingComponent); ?>

                <div class="col-12 review-post">

                    <?= Html::hiddenInput('user_post_main_id', $dataUserPostMain['id'], ['class' => 'user-post-main-id']) ?>

                    <div class="row mb-10">
                        <div class="col-sm-7 col-12">
                            <div class="widget-posts-image">
                                <?= Html::a(Html::img(Yii::$app->params['endPointLoadImage'] . 'user?image=' . $img, ['class' => 'img fluid rounded-circle']), ['user/user-profile', 'user' => $dataUserPostMain['user']['username']]) ?>
                            </div>

                            <div class="widget-posts-body">
                                <?= Html::a($dataUserPostMain['user']['full_name'], ['user/user-profile', 'user' => $dataUserPostMain['user']['username']]) ?>
                                <br>
                                <small><?= Helper::asRelativeTime($dataUserPostMain['created_at']) ?></small>
                            </div>
                        </div>
                        <div class="col-sm-5 d-none d-sm-block">
                            <div class="rating">
                            	<h4>
                                    <?= Html::tag('span', number_format($overallValue, 1), ['class' => 'badge badge-success']); ?>
                            	</h4>
                            </div>
                        </div>
                        <div class="col-12 d-block d-sm-none">
                        	<ul class="list-inline mt-0 mb-0">
                                <li class="list-inline-item">
                                    <div class="star-rating" data-rating="<?= number_format($overallValue, 1) ?>">
                                    </div>
                                </li>
                                <li class="list-inline-item">
                                    <div class="rating rating-<?= $dataUserPostMain['id']; ?>">
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
                            <p class="review-description">
                                <?= $dataUserPostMain['text']; ?>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <ul class="works-grid works-grid-gut works-grid-5">

                                <?php
                                if (!empty($dataUserPostMain['userPostMains'])):

                                    foreach ($dataUserPostMain['userPostMains'] as $i => $dataUserPostMainChild): ?>

                                        <li class="work-item gallery-photo-review <?= $i > 4 ? 'hidden' : '' ?>">
                                            <div class="gallery-item post-gallery">
                                                <div class="gallery-image">
                                                    <div class="work-image">
                                                        <?= Html::img(Yii::$app->params['endPointLoadImage'] . 'user-post?image=' . $dataUserPostMainChild['image'] . '&w=72&h=72', ['class' => 'img-component']); ?>
                                                    </div>
                                                    <div class="work-caption">
                                                        <div class="work-descr">

                                                        	<?php
                                                        	if ($i == 4) {

                                                        	    echo Html::a('+' . (count($dataUserPostMain['userPostMains']) - $i), ['page/review', 'id' => $dataUserPostMain['id']], ['class' => 'btn btn-d btn-small btn-xs btn-circle']);
                                                        	    echo Html::a('<i class="aicon aicon-zoomin"></i>', Yii::$app->params['endPointLoadImage'] . 'user-post?image=' . $dataUserPostMainChild['image'], ['class' => 'btn btn-raised btn-danger btn-small btn-xs btn-circle show-image hidden']);
                                                        	} else {

                                                        	    echo Html::a('<i class="aicon aicon-zoomin"></i>', Yii::$app->params['endPointLoadImage'] . 'user-post?image=' . $dataUserPostMainChild['image'], ['class' => 'btn btn-raised btn-danger btn-small btn-xs btn-circle show-image']);
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
                    $loveCount = !empty($dataUserPostMain['love_value']) ? $dataUserPostMain['love_value'] : 0;
                    $commentCount = !empty($dataUserPostMain['userPostComments']) ? count($dataUserPostMain['userPostComments']) : 0;
                    $photoCount = !empty($dataUserPostMain['userPostMains']) ? count($dataUserPostMain['userPostMains']) : 0;

                    $loveSpanCount = '<span class="total-likes-review">' . $loveCount . '</span>';
                    $commentSpanCount = '<span class="total-comments-review">' . $commentCount . '</span>';
                    $photoSpanCount = '<span class="total-photos-review">' . $photoCount . '</span>';

                    $selected = !empty($dataUserPostMain['userPostLoves'][0]) ? 'selected' : ''; ?>

                    <div class="row">
                        <div class="col-3 d-block d-sm-none">
                            <ul class="list-inline mt-0 mb-0">
                                <li class="list-inline-item">
                                    <small><?= '<i class="aicon aicon-thumb"></i> ' . $loveSpanCount ?></small>
                                </li>
                            </ul>
                        </div>
                        <div class="col-9 text-right d-block d-sm-none">
                            <ul class="list-inline mt-0 mb-0">
                                <li class="list-inline-item">
                                    <small><?= $commentSpanCount . ' Comment' ?></small>
                                </li>
                                <li class="list-inline-item">
                                    <small><?= $photoSpanCount . ' Photo' ?></small>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-7 col-12">
                            <ul class="list-inline list-review mt-0 mb-0">
                                <li class="list-inline-item">
                                    <?= Html::a('<i class="aicon aicon-thumb"></i> ' . $loveSpanCount . ' Like', ['action/submit-likes'], ['class' => 'btn btn-raised btn-small btn-round user-likes-review-trigger ' . $selected . ' d-none d-sm-block d-md-none']); ?>
                                    <?= Html::a('<i class="aicon aicon-thumb"></i> Like', ['action/submit-likes'], ['class' => 'btn btn-raised btn-small btn-round user-likes-review-trigger ' . $selected . ' d-block d-sm-none']); ?>
                                </li>
                                <li class="list-inline-item">
                                    <?= Html::a('<i class="aicon aicon-bubbles"></i> ' . $commentSpanCount . ' Comment', '', ['class' => 'btn btn-raised btn-small btn-round user-comments-review-trigger d-none d-sm-block']); ?>
                                    <?= Html::a('<i class="aicon aicon-bubbles"></i> Comment', '', ['class' => 'btn btn-raised btn-small btn-round user-comments-review-trigger d-block d-sm-none']); ?>
                                </li>
                                <li class="list-inline-item">

                                    <?= Html::a('<i class="aicon aicon-share1"></i> ', Yii::$app->urlManager->createAbsoluteUrl([
                                        'page/review',
                                        'id' => $dataUserPostMain['id'],
                                    ]), ['class' => 'btn btn-raised btn-small btn-round share-review-trigger d-block d-sm-none']); ?>

                                </li>
                            </ul>
                        </div>
                        <div class="col-sm-5 text-right d-none d-sm-block">
                            <ul class="list-inline list-review mt-0 mb-0">
                                <li>

                                    <?= Html::a('<i class="aicon aicon-share1"></i> Share', Yii::$app->urlManager->createAbsoluteUrl([
                                        'page/review',
                                        'id' => $dataUserPostMain['id'],
                                    ]), ['class' => 'btn btn-raised btn-small btn-round share-review-trigger']); ?>

                                </li>
                            </ul>
                        </div>
                    </div>

                    <hr class="divider-w mt-10">

                    <div class="row">
                    	<div class="col-12">
                        	<div class="user-comment-review">
                                <div class="input-group mt-10 mb-10">
                                    <span class="input-group-text"><i class="aicon aicon-bubble"></i></span>
                                    &nbsp;&nbsp;&nbsp;
                                    <?= Html::textInput('comment_input', null, [
                                        'class' => 'form-control input-comments-review',
                                        'placeholder' => Yii::t('app', 'Write a Comment')
                                    ]); ?>

                                </div>

                                <div class="overlay" style="display: none;"></div>
                                <div class="loading-img" style="display: none;"></div>

                                <div class="comment-section">

                                    <?php
                                    foreach ($dataUserPostMain['userPostComments'] as $dataUserPostComment): ?>

                                        <div class="comment-post">
                                            <div class="row mb-10">
                                                <div class="col-12">
                                                    <div class="widget-comments-image">

                                                        <?php
                                                        $img = !empty($dataUserPostComment['user']['image']) ? $dataUserPostComment['user']['image'] . '&w=64&h=64' : 'default-avatar.png';
                                                        echo Html::a(Html::img(Yii::$app->params['endPointLoadImage'] . 'user?image=' . $img, ['class' => 'img-fluid rounded-circle']), ['user/user-profile', 'user' => $dataUserPostComment['user']['username']]); ?>

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

                    <hr class="divider-w mb-10">
                </div>

            <?php
            endforeach;
        endif; ?>

    </div>
</div>

<div class="row mt-20 mb-10">
    <div class="col-sm-6 col-12 mb-10">

        <?= Yii::t('app', 'Showing {startItem} - {endItem} of {totalCount} results', ['startItem' => $startItem, 'endItem' => $endItem, 'totalCount' => $totalCount]) ?>

    </div>
    <div class="col-sm-6 d-none d-sm-block text-right">

        <?= $linkPager; ?>

    </div>
    <div class="col-12 d-block d-sm-none">

        <?= $linkPager; ?>

    </div>
</div>

<?php
$jscript = '
    $(".user-comment-review").hide();

    ratingColor($(".rating"), "a");

    $(".star-rating").rateYo({
        rating: $(".star-rating").data("rating"),
        starWidth: "18px",
        readOnly: true,
        "starSvg": "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\"><path d=\"M0 0h24v24H0z\" fill=\"none\"/><path d=\"M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z\"/><path d=\"M0 0h24v24H0z\" fill=\"none\"/></svg>"
    });

    $(".user-post-main-id").each(function() {

        $(this).parent().find(".post-gallery").magnificPopup({
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
    });

    readmoreText({
        element: $(".review-description"),
        minChars: 500,
        ellipsesText: " . . . ",
        moreText: "See more",
        lessText: "See less",
    });

    $(".total-review").html(parseInt($(".total-review").html()) + ' . $totalCount . ');

    $("#pjax-review-container").off("pjax:send");
    $("#pjax-review-container").on("pjax:send", function() {

        $(".post-review-container").children(".overlay").show();
        $(".post-review-container").children(".loading-img").show();
    });

    $("#pjax-review-container").off("pjax:complete");
    $("#pjax-review-container").on("pjax:complete", function() {

        $(".post-review-container").children(".overlay").hide();
        $(".post-review-container").children(".loading-img").hide();
    });

    $("#pjax-review-container").off("pjax:error");
    $("#pjax-review-container").on("pjax:error", function (event) {

        event.preventDefault();
    });

    $(".rating").children().children(".label").on("click", function() {

        return false;
    });
';

$this->registerJs($jscript);

Pjax::end(); ?>