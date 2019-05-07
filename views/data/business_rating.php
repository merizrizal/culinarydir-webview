<?php

/* @var $this yii\web\View */
/* @var $modelBusinessDetail core\models\BusinessDetail */
/* @var $modelBusinessDetailVote core\models\BusinessDetailVote */
/* @var $modelRatingComponent core\models\RatingComponent */

webview\assets\RateyoAsset::register($this); ?>

<div class="row">
    <div class="col-6 text-right">
        <div class="rating">
            <h3 class="mb-0"><span class="badge badge-success pt-10"><?= number_format(!empty($modelBusinessDetail['vote_value']) ? $modelBusinessDetail['vote_value'] : 0, 1) ?></span></h3>
            <?= \Yii::t('app', '{value, plural, =0{# Vote} =1{# Vote} other{# Votes}}', ['value' => !empty($modelBusinessDetail['voters']) ? $modelBusinessDetail['voters'] : 0]) ?>
        </div>
    </div>
    <div class="col-6">
        <h3 class="mb-0"><span class="badge badge-light pt-10"><?= number_format(!empty($modelBusinessDetail['vote_points']) ? $modelBusinessDetail['vote_points'] : 0, 1) ?></span></h3>
        Points
    </div>
</div>

<div class="row">
    <div class="col-12">
        <ul class="list-inline">

            <?php
            if (!empty($modelBusinessDetailVote)):

                $ratingComponent = [];

                foreach ($modelBusinessDetailVote as $dataBusinessDetailVote) {

                    if (!empty($dataBusinessDetailVote['ratingComponent'])) {

                        $ratingComponent[$dataBusinessDetailVote['ratingComponent']['order']] = $dataBusinessDetailVote;
                    }
                }

                ksort($ratingComponent);

                foreach($ratingComponent as $dataBusinessDetailVote):

                    $ratingValue = !empty($dataBusinessDetailVote['vote_value']) ? $dataBusinessDetailVote['vote_value'] : 0; ?>

                    <li>
                        <div class="row">
                            <div class="col-6 text-right">
                            	<div class="star-rating float-right business-rating-components" data-rating="<?= number_format($ratingValue, 1) ?>">
                                </div>
                            </div>

                            <div class="col-6">
                                <?= number_format($ratingValue, 1) . ' &nbsp; ' . \Yii::t('app', $dataBusinessDetailVote['ratingComponent']['name']) ?>
                            </div>
                        </div>
                    </li>

                <?php
                endforeach;

            else:

                foreach($modelRatingComponent as $dataRatingComponent): ?>

                    <li>
                        <div class="row">
                            <div class="col-6">
                            	<div class="star-rating float-right business-rating-components" data-rating="0">
                                </div>
                            </div>

                            <div class="col-6">
                                <?= 0 . ' &nbsp; ' . \Yii::t('app', $dataBusinessDetailVote['ratingComponent']['name']) ?>
                            </div>
                        </div>
                    </li>

                <?php
                endforeach;
            endif;?>

        </ul>
    </div>
</div>

<?php
$cssScript = '
    .business-rating-components {
        padding-top: 2px;
    }
';

$this->registerCss($cssScript);

webview\components\RatingColor::widget();

$jscript = '
    ratingColor($(".rating"), "span");

    $(".star-rating").each(function() {

        $(this).rateYo({
            rating: $(this).data("rating"),
            starWidth: "18px",
            readOnly: true,
            "starSvg": "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\"><path d=\"M0 0h24v24H0z\" fill=\"none\"/><path d=\"M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z\"/><path d=\"M0 0h24v24H0z\" fill=\"none\"/></svg>"
        });
    });
';

$this->registerJs($jscript); ?>