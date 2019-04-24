<?php

use common\components\Helper;
use kartik\rating\StarRating;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $pagination yii\data\Pagination */
/* @var $startItem int */
/* @var $endItem int */
/* @var $totalCount int */
/* @var $modelUserPostMain core\models\UserPostMain */

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
    <div class="col-tab-6 col-xs-12 mb-10">

        <?= Yii::t('app', 'Showing {startItem} - {endItem} of {totalCount} results', ['startItem' => $startItem, 'endItem' => $endItem, 'totalCount' => $totalCount]) ?>

    </div>
    <div class="col-tab-6 visible-tab text-right">

        <?= $linkPager; ?>

    </div>
    <div class="col-xs-12 visible-xs">

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

                <div class="col-xs-12 review-post">

                    <?= Html::hiddenInput('user_post_main_id', $dataUserPostMain['id'], ['class' => 'user-post-main-id']) ?>

                    <div class="row mb-10">
                        <div class="col-tab-7 col-xs-12">
                            <div class="widget">
                                <div class="widget-posts-image">
                                    <?= Html::a(Html::img(Yii::$app->params['endPointLoadImage'] . 'user?image=' . $img, ['class' => 'img-responsive img-circle img-profile-thumb img-component']), ['user/user-profile', 'user' => $dataUserPostMain['user']['username']]) ?>
                                </div>

                                <div class="widget-posts-body">
                                    <?= Html::a($dataUserPostMain['user']['full_name'], ['user/user-profile', 'user' => $dataUserPostMain['user']['username']]) ?>
                                    <br>
                                    <small><?= Helper::asRelativeTime($dataUserPostMain['created_at']) ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-tab-5 visible-tab">
                            <div class="rating">
                            	<h3 class="mt-0 mb-0">
                                    <?= Html::a(number_format($overallValue, 1), '#', ['class' => 'label label-success pt-10']); ?>
                            	</h3>
                            </div>
                        </div>
                        <div class="col-xs-12 visible-xs">
                        	<ul class="list-inline mt-0 mb-0">
                                <li>
                                    <div class="widget star-rating">

                                        <?= StarRating::widget([
                                            'id' => 'rating-' . $dataUserPostMain['id'],
                                            'name' => 'rating_' . $dataUserPostMain['id'],
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
                                    <div class="rating rating-<?= $dataUserPostMain['id']; ?>">
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
                                <?= $dataUserPostMain['text']; ?>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
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

                    <div class="row visible-xs">
                        <div class="col-xs-3">
                            <ul class="list-inline mt-0 mb-0">
                                <li>
                                    <small><?= '<i class="aicon aicon-thumb"></i> ' . $loveSpanCount ?></small>
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
                                    <?= Html::a('<i class="aicon aicon-thumb"></i> ' . $loveSpanCount . ' Like', ['action/submit-likes'], ['class' => 'btn btn-raised btn-small btn-round user-likes-review-trigger ' . $selected . ' visible-tab']); ?>
                                    <?= Html::a('<i class="aicon aicon-thumb"></i> Like', ['action/submit-likes'], ['class' => 'btn btn-raised btn-small btn-round user-likes-review-trigger ' . $selected . ' visible-xs']); ?>
                                </li>
                                <li>
                                    <?= Html::a('<i class="aicon aicon-bubbles"></i> ' . $commentSpanCount . ' Comment', '', ['class' => 'btn btn-raised btn-small btn-round user-comments-review-trigger visible-tab']); ?>
                                    <?= Html::a('<i class="aicon aicon-bubbles"></i> Comment', '', ['class' => 'btn btn-raised btn-small btn-round user-comments-review-trigger visible-xs']); ?>
                                </li>
                                <li class="visible-xs-inline-block">

                                    <?= Html::a('<i class="aicon aicon-share1"></i> ', Yii::$app->urlManager->createAbsoluteUrl([
                                        'page/review',
                                        'id' => $dataUserPostMain['id'],
                                        'uniqueName' => $dataUserPostMain['business']['unique_name'],
                                    ]), ['class' => 'btn btn-raised btn-small btn-round share-review-trigger']); ?>

                                </li>
                            </ul>
                        </div>
                        <div class="col-tab-5 text-right visible-tab">
                            <ul class="list-inline list-review mt-0 mb-0">
                                <li>

                                    <?= Html::a('<i class="aicon aicon-share1"></i> Share', Yii::$app->urlManager->createAbsoluteUrl([
                                        'page/review',
                                        'id' => $dataUserPostMain['id'],
                                        'uniqueName' => $dataUserPostMain['business']['unique_name'],
                                    ]), ['class' => 'btn btn-raised btn-small btn-round share-review-trigger']); ?>

                                </li>
                            </ul>
                        </div>
                    </div>

                    <hr class="divider-w mt-10">

                    <div class="row">
                    	<div class="col-xs-12">
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
                                    <div class="comment-container">

                                        <?php
                                        foreach ($dataUserPostMain['userPostComments'] as $dataUserPostComment): ?>

                                            <div class="comment-post">
                                                <div class="row mb-10">
                                                    <div class="col-xs-12">
                                                        <div class="widget">
                                                            <div class="widget-comments-image">

                                                                <?php
                                                                $img = !empty($dataUserPostComment['user']['image']) ? $dataUserPostComment['user']['image'] . '&w=64&h=64' : 'default-avatar.png';
                                                                echo Html::a(Html::img(Yii::$app->params['endPointLoadImage'] . 'user?image=' . $img, ['class' => 'img-responsive img-circle img-comment-thumb img-component']), ['user/user-profile', 'user' => $dataUserPostComment['user']['username']]); ?>

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

                    <hr class="divider-w mb-10">
                </div>

            <?php
            endforeach;
        endif; ?>

    </div>
</div>

<div class="row mt-20 mb-10">
    <div class="col-tab-6 col-xs-12 mb-10">

        <?= Yii::t('app', 'Showing {startItem} - {endItem} of {totalCount} results', ['startItem' => $startItem, 'endItem' => $endItem, 'totalCount' => $totalCount]) ?>

    </div>
    <div class="col-tab-6 visible-lg visible-tab text-right">

        <?= $linkPager; ?>

    </div>
    <div class="col-xs-12 visible-xs">

        <?= $linkPager; ?>

    </div>
</div>

<?php
$jscript = '
    $(".user-comment-review").hide();

    ratingColor($(".rating"), "a");

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