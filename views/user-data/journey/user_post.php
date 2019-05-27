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
    'linkSelector' => '#pagination-user-post a',
    'options' => ['id' => 'pjax-user-post-container'],
    'timeout' => 7000,
]);

$linkPager = LinkPager::widget([
    'pagination' => $pagination,
    'maxButtonCount' => 5,
    'prevPageLabel' => false,
    'nextPageLabel' => false,
    'firstPageLabel' => '<i class="aicon aicon-icon-left-angle-semantic"></i>',
    'lastPageLabel' => '<i class="aicon aicon-icon-right-angle-semantic"></i>',
    'options' => ['id' => 'pagination-user-post', 'class' => 'pagination'],
]); ?>

<div class="row mt-10 mb-20">
    <div class="col-sm-6 col-12 mb-10">

    	<?= \Yii::t('app', 'Showing {startItem} - {endItem} of {totalCount} results', ['startItem' => $startItem, 'endItem' => $endItem, 'totalCount' => $totalCount]) ?>

    </div>
    <div class="col-sm-6 d-none d-sm-block d-md-none text-right">

        <?= $linkPager; ?>

    </div>
    <div class="col-12 d-block d-sm-none">

        <?= $linkPager; ?>

    </div>
</div>

<div class="row user-post-container" style="position: relative;">

	<div class="overlay" style="display: none;"></div>
	<div class="loading-img" style="display: none;"></div>

    <?php
    if (!empty($modelUserPostMain)):

        foreach ($modelUserPostMain as $dataUserPostMain):

            $img = (!empty($dataUserPostMain['business']['businessImages']) ? $dataUserPostMain['business']['businessImages'][0]['image'] : '') . '&w=64&h=64';

            $urlBusinessDetail = ['page/detail', 'id' => $dataUserPostMain['business']['id'], 'name' => $dataUserPostMain['business']['name']];

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

            <div class="col-12 user-post-item">

                <?= Html::hiddenInput('user_post_main_id', $dataUserPostMain['id'], ['class' => 'user-post-main-id']) ?>

                <div class="row">
                    <div class="col-sm-7 col-12">
                        <div class="widget-posts-image business-image">
                            <?= Html::a(Html::img(\Yii::$app->params['endPointLoadImage'] . 'registry-business?image=' . $img, ['class' => 'img-fluid rounded-circle']), $urlBusinessDetail) ?>
                        </div>

                        <div class="widget-posts-body business-review">
                            <?= Html::a($dataUserPostMain['business']['name'], $urlBusinessDetail) ?>
                            <br>
                            <small><?= Helper::asRelativeTime($dataUserPostMain['created_at']) ?></small>
                        </div>
                    </div>
                    <div class="col-sm-5 mb-10 d-none d-sm-block d-md-none">
						<div class="rating">
                        	<h4>
                                <?= Html::tag('span', number_format($overallValue, 1), ['class' => 'badge badge-success']); ?>
                        	</h4>
                        </div>
                    </div>
                    <div class="col-12 mt-10 d-block d-sm-none">
                    	<ul class="list-inline mt-0 mb-0">
                            <li class="list-inline-item">
                                <div class="star-rating" data-rating="<?= number_format($overallValue, 1) ?>"></div>
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
                            <?= $dataUserPostMain['text'] ?>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <ul class="works-grid works-grid-gut works-grid-5">

                            <?php
                            if (!empty($dataUserPostMain['userPostMains'])):

                                foreach ($dataUserPostMain['userPostMains'] as $i => $dataUserPostMainChild): ?>

                                    <li class="work-item gallery-photo-review <?= $i > 4 ? 'd-none' : '' ?>">
                                        <div class="gallery-item post-gallery">
                                            <div class="gallery-image">
                                                <div class="work-image">
                                                    <?= Html::img(\Yii::$app->params['endPointLoadImage'] . 'user-post?image=' . $dataUserPostMainChild['image'] . '&w=72&h=72', ['class' => 'img-component']); ?>
                                                </div>
                                                <div class="work-caption">
                                                    <div class="work-descr">

                                                    	<?php
                                                    	$hiddenPhotos = count($dataUserPostMain['userPostMains']) - ($i + 1);

                                                    	if ($i == 4 && $hiddenPhotos != 0) {

                                                    	    echo Html::a('+' . $hiddenPhotos, ['page/review', 'id' => $dataUserPostMain['id']], ['class' => 'btn btn-raised btn-danger btn-small btn-xs btn-circle']);
                                                    	    echo Html::a('<i class="aicon aicon-zoomin"></i>', \Yii::$app->params['endPointLoadImage'] . 'user-post?image=' . $dataUserPostMainChild['image'], ['class' => "btn btn-raised btn-danger btn-small btn-xs btn-circle show-image d-none"]);
                                                    	} else {

                                                    	    echo Html::a('<i class="aicon aicon-zoomin"></i>', \Yii::$app->params['endPointLoadImage'] . 'user-post?image=' . $dataUserPostMainChild['image'], ['class' => "btn btn-raised btn-danger btn-small btn-xs btn-circle show-image"]);
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
                $urlReviewDetail = \Yii::$app->params['rootUrl'] . 'review/' . $dataUserPostMain['id'] . '/di/' . $dataUserPostMain['business']['unique_name'];

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
                            	<?= Html::a('<i class="aicon aicon-thumb"></i> ' . $loveSpanCount . ' Like', ['action/submit-likes'], ['class' => 'btn btn-raised btn-default btn-small btn-round user-likes-review-trigger ' . $selected . ' d-none d-sm-block d-md-none']); ?>
            					<?= Html::a('<i class="aicon aicon-thumb"></i> Like', ['action/submit-likes'], ['class' => 'btn btn-raised btn-default btn-small btn-round user-likes-review-trigger ' . $selected . ' d-block d-sm-none']); ?>
                            </li>
                            <li class="list-inline-item">
                            	<?= Html::a('<i class="aicon aicon-bubbles"></i> ' . $commentSpanCount . ' Comment', '', ['class' => 'btn btn-raised btn-default btn-small btn-round user-comments-review-trigger d-none d-sm-block d-md-none']); ?>
            					<?= Html::a('<i class="aicon aicon-bubbles"></i> Comment', '', ['class' => 'btn btn-raised btn-default btn-small btn-round user-comments-review-trigger d-block d-sm-none']); ?>
                            </li>
                            <li class="list-inline-item">

                            	<?php
                                if (!empty(\Yii::$app->user->getIdentity()->id) && \Yii::$app->user->getIdentity()->id == $dataUserPostMain['user_id']): ?>

                                	<a class="btn btn-raised btn-default btn-small btn-round d-block d-sm-none" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false">
                                        <i class="aicon aicon-more"></i>
                                    </a>
                                    <div class="dropdown-menu pull-right review-btn">
                                    	<?= Html::a('<i class="aicon aicon-share1"></i>&nbsp;Share', $urlReviewDetail, ['class' => 'share-review-trigger dropdown-item']); ?>
                                       	<?= Html::a('<i class="aicon aicon-icon-trash"></i>&nbsp;' . \Yii::t('app', 'Delete'), ['user-action/delete-user-post', 'id' => $dataUserPostMain['id']], ['class' => 'user-delete-review-trigger dropdown-item']) ?>
                                    </div>

                                <?php
                                else:

                                    echo Html::a('<i class="aicon aicon-share1"></i>', $urlReviewDetail, ['class' => 'btn btn-raised btn-default btn-small btn-round share-review-trigger d-block d-sm-none']);
                                endif; ?>

                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-5 text-right d-none d-sm-block d-md-none">
                        <ul class="list-inline list-review mt-0 mb-0">
                            <li class="list-inline-item">
                                <?= Html::a('<i class="aicon aicon-share1"></i> Share', $urlReviewDetail, ['class' => 'btn btn-raised btn-default btn-small btn-round share-review-trigger']); ?>
                            </li>

                            <?php
                            if (!empty(\Yii::$app->user->getIdentity()->id) && \Yii::$app->user->getIdentity()->id == $dataUserPostMain['user_id']) {

                                echo '<li class="list-inline-item">' . Html::a('<i class="aicon aicon-icon-trash"></i> ' . \Yii::t('app', 'Delete'), ['user-action/delete-user-post', 'id' => $dataUserPostMain['id']], ['class' => 'btn btn-raised btn-default btn-small btn-round user-delete-review-trigger']) . '</li>';
                            } ?>

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
                                    'placeholder' => \Yii::t('app', 'Write a Comment')
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

                <hr class="divider-w mb-10">
            </div>

        <?php
        endforeach;
    endif; ?>

</div>

<div class="row mt-20 mb-10">
    <div class="col-sm-6 col-12 mb-10">

        <?= \Yii::t('app', 'Showing {startItem} - {endItem} of {totalCount} results', ['startItem' => $startItem, 'endItem' => $endItem, 'totalCount' => $totalCount]) ?>

    </div>
    <div class="col-sm-6 d-none d-sm-block d-md-none text-right">

        <?= $linkPager; ?>

    </div>
    <div class="col-12 d-block d-sm-none">

        <?= $linkPager; ?>

    </div>
</div>

<?php
$jscript = '
    $(".star-rating").rateYo({
        rating: $(".star-rating").data("rating"),
        starWidth: "18px",
        readOnly: true,
        "starSvg": "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\"><path d=\"M0 0h24v24H0z\" fill=\"none\"/><path d=\"M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z\"/><path d=\"M0 0h24v24H0z\" fill=\"none\"/></svg>"
    });

    $(".user-comment-review").hide();

    ratingColor($(".rating"), "span");

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

    $(".total-user-post").html("' . $totalCount . '");

    $("#pjax-user-post-container").off("pjax:send");
    $("#pjax-user-post-container").on("pjax:send", function() {

        $(".user-post-container").children(".overlay").show();
        $(".user-post-container").children(".loading-img").show();
    });

    $("#pjax-user-post-container").off("pjax:complete");
    $("#pjax-user-post-container").on("pjax:complete", function() {

        $(".user-post-container").children(".overlay").hide();
        $(".user-post-container").children(".loading-img").hide();
    });

    $("#pjax-user-post-container").off("pjax:end");
    $("#pjax-user-post-container").on("pjax:end", function (event) {

        $(".user-post-container").bootstrapMaterialDesign();
    });

    $("#pjax-user-post-container").off("pjax:error");
    $("#pjax-user-post-container").on("pjax:error", function (event) {

        event.preventDefault();
    });
';

$this->registerJs($jscript);

Pjax::end(); ?>