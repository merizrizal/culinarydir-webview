<?php

use frontend\components\AddressType;
use webview\components\Snackbar;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $modelBusiness core\models\Business */
/* @var $dataBusinessImage core\models\BusinessImage */
/* @var $modelRatingComponent core\models\RatingComponent */
/* @var $modelUserReport core\models\UserReport */
/* @var $modelUserPostMain core\models\UserPostMain */
/* @var $modelPost frontend\models\Post */
/* @var $modelPostPhoto frontend\models\Post */
/* @var $modelTransactionSession core\models\TransactionSession */
/* @var $dataUserVoteReview array */
/* @var $isOrderOnline boolean */

common\assets\OwlCarouselAsset::register($this);

$this->title = $modelBusiness['name'];

$ogUrl = \Yii::$app->params['rootUrl'] . 'kuliner/di/' . Inflector::slug($modelBusiness['businessLocation']['city']['name']) . '/' . $modelBusiness['unique_name'];

$ogTitle = $modelBusiness['name'];

$ogDescription = 'Kunjungi kami di ' . AddressType::widget([
    'businessLocation' => $modelBusiness['businessLocation'],
    'showDetail' => true
]) . '.';

$ogBusinessCategory = '';
$ogPriceRange = '-';
$ogProductCategory = '';
$ogFacility = '';

$ogImage = \Yii::$app->params['endPointLoadImage'] . 'registry-business/image=&w=786&h=425';

$ogBusinessHour = null;

if (!empty($modelBusiness['about'])) {

    $ogDescription = preg_replace('/[\r\n]+/','' , strip_tags($modelBusiness['about'])) . '.';
}

foreach ($modelBusiness['businessCategories'] as $dataBusinessCategory) {

    $ogBusinessCategory .= $dataBusinessCategory['category']['name'] . ',';
}

if (!empty($modelBusiness['businessDetail']['price_min']) && !empty($modelBusiness['businessDetail']['price_max'])) {

    $ogPriceRange = \Yii::$app->formatter->asShortCurrency($modelBusiness['businessDetail']['price_min']) . ' - ' . \Yii::$app->formatter->asShortCurrency($modelBusiness['businessDetail']['price_max']);
} else if (empty($modelBusiness['businessDetail']['price_min']) && !empty($modelBusiness['businessDetail']['price_max'])) {

    $ogPriceRange =  \Yii::t('app', 'Under') . ' ' . \Yii::$app->formatter->asShortCurrency($modelBusiness['businessDetail']['price_max']);
} else if (empty($modelBusiness['businessDetail']['price_max']) && !empty($modelBusiness['businessDetail']['price_min'])) {

    $ogPriceRange =  \Yii::t('app', 'Above') . ' ' . \Yii::$app->formatter->asShortCurrency($modelBusiness['businessDetail']['price_min']);
}

foreach ($modelBusiness['businessProductCategories'] as $dataBusinessProductCategory) {

    $ogProductCategory .= $dataBusinessProductCategory['productCategory']['name'] . ',';
}

foreach ($modelBusiness['businessFacilities'] as $dataBusinessFacility) {

    $ogFacility .= $dataBusinessFacility['facility']['name'] . ',';
}

$ogDescription = $ogDescription . ' ' . trim($ogBusinessCategory, ',') . '. Kisaran biaya rata-rata: ' . $ogPriceRange . '. ' . trim($ogProductCategory, ',') . '. ' . trim($ogFacility, ',');
$ogDescription = StringHelper::truncate($ogDescription, 300);

foreach ($modelBusiness['businessImages'] as $dataImageThumbail) {

    if ($dataImageThumbail['is_primary']) {

        $ogImage = \Yii::$app->params['endPointLoadImage'] . 'registry-business?image=' . $dataImageThumbail['image'];
        break;
    }
}

$urlMenuDetail = ['page/menu', 'id' => $modelBusiness['id']];

$noImg = \Yii::$app->params['endPointLoadImage'] . 'registry-business?image=&w=756&h=425'; ?>

<div class="main bg-main">

    <section>
        <div class="detail place-detail">

            <div class="row">
                <div class="col-12">

                    <div class="row mb-20">
                        <div class="col-12">

                            <div class="row">
                                <div class="col-12">
                                    <div class="card view">
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a id="photo-tab" href="#photo" class="nav-link active" data-toggle="tab" role="tab" aria-controls="photo" aria-selected="true"><i class="aicon aicon-camera1"></i> <?= \Yii::t('app', 'Ambience') ?></a>
                                            </li>
                                            <li class="nav-item">
                                                <a id="menu-tab" href="#menu" class="nav-link" data-toggle="tab" role="tab" aria-controls="#menu" aria-selected="false"><i class="aicon aicon-icon-budicon"></i> Menu</a>
                                            </li>
                                        </ul>

                                        <div class="tab-content">
                                            <div id="photo" class="tab-pane fade show active" role="tabpanel" aria-labelledby="photo-tab">
                                                <div class="row">
                                                    <div class="col-12">
														<div class="ambience-gallery owl-carousel owl-theme">

                                                            <?php
                                                            if (!empty($dataBusinessImage['Ambience']) && count($dataBusinessImage['Ambience']) > 0) {

                                                                $orderedBusinessImage = [];

                                                                foreach ($dataBusinessImage['Ambience'] as $businessImage) {

                                                                    $orderedBusinessImage[$businessImage['order']] = $businessImage;
                                                                }

                                                                ksort($orderedBusinessImage);

                                                                foreach ($orderedBusinessImage as $businessImage) {

                                                                    $img = $noImg;

                                                                    if (!empty($businessImage['image'])) {

                                                                        $img = \Yii::$app->params['endPointLoadImage'] . 'registry-business?image=' . $businessImage['image'] . '&w=1252&h=706';
                                                                    }

                                                                    echo Html::img(null, ['class' => 'owl-lazy', 'data-src' => $img]);
                                                                }
                                                            } else {

                                                                echo Html::img(null, ['class' => 'owl-lazy', 'data-src' => $noImg]);
                                                            } ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="menu" class="tab-pane fade" role="tabpanel" aria-labelledby="menu-tab">
                                                <div class="row">
                                                    <div class="col-12">
                                                    	<div class="menu-gallery owl-carousel owl-theme">

                                                            <?php
                                                            if (!empty($dataBusinessImage['Menu']) && count($dataBusinessImage['Menu']) > 0) {

                                                                $orderedBusinessImage = [];

                                                                foreach ($dataBusinessImage['Menu'] as $businessImage) {

                                                                    $orderedBusinessImage[$businessImage['order']] = $businessImage;
                                                                }

                                                                ksort($orderedBusinessImage);

                                                                foreach ($orderedBusinessImage as $businessImage) {

                                                                    $img = $noImg;

                                                                    if (!empty($businessImage['image'])) {

                                                                        $img = \Yii::$app->params['endPointLoadImage'] . 'registry-business?image=' . $businessImage['image'] . '&w=1252&h=706';
                                                                    }

                                                                    echo Html::img(null, ['class' => 'owl-lazy', 'data-src' => $img]);
                                                                }
                                                            } else {

                                                                echo Html::img(null, ['class' => 'owl-lazy', 'data-src' => $noImg]);
                                                            } ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-20">
                                <div class="col-12">
                                    <div class="card box">
                                        <div class="box-title">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6 class="business-name"><?= $modelBusiness['name']; ?></h6>
                                                </div>

												<div class="clearfix"></div>

                                                <div class="col-12">

                                                	<?php
                                                    $categories = '';

                                                    foreach ($modelBusiness['businessCategories'] as $dataBusinessCategory) {

                                                        $categories .= $dataBusinessCategory['category']['name'] . ' / ';
                                                    } ?>

                                                    <h6><small><?= trim($categories, ' / ') ?></small></h6>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="divider-w">

                                        <div class="box-content">
                                            <div class="row mt-10">
                                                <div class="col-12 pull-right">
                                                    <div class="business-rating">

                                                        <?= $this->render('@webview/views/data/business_rating.php', [
                                                            'modelBusinessDetail' => $modelBusiness['businessDetail'],
                                                            'modelBusinessDetailVote' => $modelBusiness['businessDetailVotes'],
                                                            'modelRatingComponent' => $modelRatingComponent,
                                                        ]) ?>

                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <ul class="list-inline">
                                                        <li>
                                                            <i class="aicon aicon-home1"></i>

															<?= AddressType::widget([
                                                                'businessLocation' => $modelBusiness['businessLocation'],
                                                                'showDetail' => true
                                                            ]); ?>

                                                            <?= !empty($modelBusiness['businessLocation']['address_info']) ? '<br>' . $modelBusiness['businessLocation']['address_info'] : ''; ?>
                                                            <?= Html::a('<i class="aicon aicon-icon-thin-location-line"></i> ' . \Yii::t('app', 'See Map'), '', ['class' => 'btn btn-small btn-xs btn-round see-map-shortcut']); ?>
                                                        </li>
                                                        <li>
                                                            <i class="aicon aicon-rupiah"></i> <?= $ogPriceRange; ?>
                                                        </li>
                                                        <li>
                                                        	<i class="aicon aicon-icon-phone-fill"></i> <?= !empty($modelBusiness['phone1']) ? $modelBusiness['phone1'] : '-' ?>
                                                    	</li>
                                                        <li>
                                                        	<i class="aicon aicon-clock"></i> <?= \Yii::t('app', 'Operational Hours') ?>

                                                        	<?php
                                                            if (!empty($modelBusiness['businessHours'])):

                                                                $days = \Yii::$app->params['days'];

                                                                \Yii::$app->formatter->timeZone = 'Asia/Jakarta';

                                                                $now = \Yii::$app->formatter->asTime(time());

                                                                \Yii::$app->formatter->timeZone = 'UTC';

                                                                $isOpen = false;
                                                                $listSchedule = '';
                                                                $hour = null;
                                                                $hourAdditional = null;

                                                                $ogBusinessHour = '"openingHoursSpecification": [';

                                                                foreach ($modelBusiness['businessHours'] as $dataBusinessHour) {

                                                                    $day = $days[$dataBusinessHour['day'] - 1];

                                                                    $openAt = \Yii::$app->formatter->asTime($dataBusinessHour['open_at'], 'HH:mm');
                                                                    $closeAt = \Yii::$app->formatter->asTime($dataBusinessHour['close_at'], 'HH:mm');

                                                                    $isOpenToday = false;
                                                                    $is24Hour = (($dataBusinessHour['open_at'] == '00:00:00') && ($dataBusinessHour['close_at'] == '24:00:00'));

                                                                    $businessHour = $is24Hour ? \Yii::t('app','24 Hours') : ($openAt . ' - ' . $closeAt);
                                                                    $businessHourAdditional = '';

                                                                    if (date('l') == $day) {

                                                                        $isOpen = $now >= $dataBusinessHour['open_at'] && $now <= $dataBusinessHour['close_at'];

                                                                        $isOpenToday = true;
                                                                        $hour = $businessHour;
                                                                    }

                                                                    $ogBusinessHour .= '{"@type": "OpeningHoursSpecification", "dayOfWeek": "' . $day . '", "opens": "' . $openAt . '", "closes": "' . $closeAt . '"},';

                                                                    if (!empty($dataBusinessHour['businessHourAdditionals'])) {

                                                                        foreach ($dataBusinessHour['businessHourAdditionals'] as $dataBusinessHourAdditional) {

                                                                            $openAt = \Yii::$app->formatter->asTime($dataBusinessHourAdditional['open_at'], 'HH:mm');
                                                                            $closeAt = \Yii::$app->formatter->asTime($dataBusinessHourAdditional['close_at'], 'HH:mm');

                                                                            $businessHourAdditional .= '<div class="col-7 offset-5">' . ($isOpenToday ? '<strong>' . $openAt . ' - ' . $closeAt . '</strong></div>' : $openAt . ' - ' . $closeAt);

                                                                            if (date('l') == $day) {

                                                                                $hourAdditional .= '<br>' . \Yii::$app->formatter->asTime($dataBusinessHourAdditional['open_at'], 'HH:mm') . ' - ' . \Yii::$app->formatter->asTime($dataBusinessHourAdditional['close_at'], 'HH:mm');

                                                                                if (!$isOpen) {

                                                                                    $isOpen = $now >= $dataBusinessHourAdditional['open_at'] && $now <= $dataBusinessHourAdditional['close_at'];
                                                                                }
                                                                            }

                                                                            $ogBusinessHour .= '{"@type": "OpeningHoursSpecification", "dayOfWeek": "' . $day . '", "opens": "' . $openAt . '", "closes": "' . $closeAt . '"},';
                                                                        }
                                                                    }

                                                                    $listSchedule .= '
                                                                        <li>
                                                                            <div class="row">
                                                                                <div class="col-5">
                                                                                    <span class="pl-3">' . ($isOpenToday ? '<strong>' . \Yii::t('app', $day) . '</strong>' : \Yii::t('app', $day)) . '</span>
                                                                                </div>
                                                                                <div class="col-7">' .
                                                                                    ($isOpenToday ? '<strong>' . $businessHour . '</strong>' : $businessHour) .
                                                                                '</div>' .
                                                                                $businessHourAdditional .
                                                                            '</div>
                                                                        </li>';
                                                                }

                                                                $ogBusinessHour = trim($ogBusinessHour, ',') .  '],'; ?>

																<div class="dropdown inline-block">
                                                                    <button id="dropdown-business-hour" type="button" class="btn btn-small btn-xs btn-round <?= $isOpen ? 'btn-success' : 'btn-danger' ?> active dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        <?= $isOpen ? \Yii::t('app', 'Open') : \Yii::t('app', 'Closed') ?> <span class="caret"></span>
                                                                    </button>
                                                                    <ul class="dropdown-menu list-inline" aria-labelledby="dropdown-business-hour">
                                                                        <?= $listSchedule ?>
                                                                    </ul>
                                                                </div>

                                                                <?php
                                                                if ($isOpen): ?>

                                                                    <table style="margin-left: 18px">
                                                                        <tr>
																			<td valign="top"><?= \Yii::t('app', 'Today') ?></td>
																			<td valign="top">&nbsp; : &nbsp;</td>
																			<td>
																				<?= $hour . $hourAdditional ?>
																			</td>
																		</tr>
                                                                    </table>

                                                                <?php
                                                                endif;
                                                            else:

                                                                echo '<br><span style="margin-left: 18px">' . \Yii::t('app', 'Data Not Available') . '</span>';
                                                            endif; ?>

                                                        </li>
                                                        <li>

                                                            <?php
                                                            foreach ($modelBusiness['businessProductCategories'] as $dataBusinessProductCategory) {

                                                                if (!empty($dataBusinessProductCategory['productCategory'])) {

                                                                    echo '<strong class="text-danger">#</strong>' . $dataBusinessProductCategory['productCategory']['name'] . ' ';
                                                                }
                                                            } ?>

                                                        </li>
                                                        <li>

                                                            <?php
                                                            foreach ($modelBusiness['businessFacilities'] as $dataBusinessFacility) {

                                                                echo '<strong class="text-info">#</strong>' . $dataBusinessFacility['facility']['name'] . ' ';
                                                            } ?>

                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <?php
                                            $orderbtn = Html::a('<i class="aicon aicon-icon-online-ordering aicon-1-2x"></i> ' . \Yii::t('app', 'Online Order'), $urlMenuDetail, [
                                                'class' => 'btn btn-raised btn-standard btn-danger btn-block btn-round'
                                            ]);

                                            if (!$isOrderOnline) {

                                                $orderbtn = Html::a('<i class="aicon aicon-icon-cuisine"></i> ' . \Yii::t('app', 'Menu List'), $urlMenuDetail, [
                                                    'class' => 'btn btn-raised btn-standard btn-danger btn-block btn-round'
                                                ]);
                                            }

                                            $reportbtn = Html::a('<i class="aicon aicon-warning aicon-1-2x"></i> ' .  \Yii::t('app', 'Report'), '', [
                                                'class' => 'btn btn-raised btn-standard btn-danger btn-block btn-round report-business-trigger'
                                            ]);

                                            $messagebtn = Html::a('<i class="aicon aicon-icon-envelope aicon-1-2x"></i> Message', '', [
                                                'class' => 'btn btn-raised btn-standard btn-danger btn-block btn-round message-feature'
                                            ]); ?>

                                            <div class="row mt-10 mb-10">
                                                <div class="col-sm-3 d-none d-sm-block d-md-none">
                                                    <?= $reportbtn ?>
                                                </div>
                                                <div class="col-sm-3 d-none d-sm-block d-md-none">
                                                    <?= $messagebtn ?>
                                                </div>
                                                <div class="col-sm-6 d-none d-sm-block d-md-none">
                                                    <?= $orderbtn ?>
                                                </div>

                                                <div class="col-6 d-block d-sm-none">
                                                	<?= $reportbtn ?>
                                            	</div>
                                            	<div class="col-6 d-block d-sm-none">
                                                	<?= $messagebtn ?>
                                                </div>

                                                <div class="clearfix d-block d-sm-none mb-10"></div>

                                                <div class="col-12 d-block d-sm-none">
                                                	<?= $orderbtn ?>
                                                </div>
                                            </div>

                                        </div>

                                        <hr class="divider-w">

                                        <div class="box-footer">

                                            <?= Html::hiddenInput('business_id', $modelBusiness['id'], ['class' => 'business-id']) ?>

                                            <div class="row">
                                                <div class="col-12">

                                                    <?php
                                                    $selectedVisit = !empty($modelBusiness['userVisits'][0]) ? 'selected' : '';
                                                    $selectedLove = !empty($modelBusiness['userLoves'][0]) ? 'selected' : '';

                                                    $visitValue = !empty($modelBusiness['businessDetail']['visit_value']) ? $modelBusiness['businessDetail']['visit_value'] : 0;
                                                    $loveValue = !empty($modelBusiness['businessDetail']['love_value']) ? $modelBusiness['businessDetail']['love_value'] : 0;

                                                    $visitSpanCount = '<span class="been-here-count">' . $visitValue . '</span>';
                                                    $loveSpanCount = '<span class="love-place-count">' . $loveValue . '</span>'; ?>

                                                    <ul class="list-inline mt-0 mb-0">
                                                        <li class="list-inline-item">

                                                            <?= Html::a('<i class="aicon aicon-icon-been-there"></i> ' . $visitSpanCount . ' Visit', ['action/submit-user-visit'], [
                                                                'class' => 'btn btn-raised btn-standard btn-round d-none d-sm-block d-md-none been-here ' . $selectedVisit . '',
                                                            ]) ?>

                                                            <?= Html::a('<i class="aicon aicon-icon-been-there"></i> ' . $visitSpanCount . ' Visit', ['action/submit-user-visit'], [
                                                                'class' => 'btn btn-raised btn-standard btn-round d-block d-sm-none been-here ' . $selectedVisit . '',
                                                            ]) ?>

                                                        </li>
                                                        <li class="list-inline-item">

                                                            <?= Html::a('<i class="aicon aicon-heart1"></i> ' . $loveSpanCount . ' Love', ['action/submit-user-love'], [
                                                                'class' => 'btn btn-raised btn-standard btn-round d-none d-sm-block d-md-none love-place ' . $selectedLove . '',
                                                            ]) ?>

                                                            <?= Html::a('<i class="aicon aicon-heart1"></i> ' . $loveSpanCount . ' Love', ['action/submit-user-love'], [
                                                                'class' => 'btn btn-raised btn-standard btn-round d-block d-sm-none love-place ' . $selectedLove . '',
                                                            ]) ?>

                                                        </li>
                                                        <li class="list-inline-item">

                                                            <?= Html::a('<i class="aicon aicon-share1"></i> Share', '', [
                                                                'class' => 'btn btn-raised btn-standard btn-round d-none d-sm-block d-md-none share-feature'
                                                            ]) ?>

                                                            <?= Html::a('<i class="aicon aicon-share1"></i>', '', [
                                                                'class' => 'btn btn-raised btn-standard btn-round d-block d-sm-none share-feature'
                                                            ]) ?>

                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <?php
                            if (!empty($modelBusiness['businessPromos'])): ?>

                                <div id="special" class="row mt-10">
                                    <div class="col-12">
                                        <div class="card box">
                                            <div class="box-title">
                                                <h6 class="m-0"><?= \Yii::t('app', 'Special & Discount') ?> !!</h6>
                                            </div>

                                            <hr class="divider-w">

                                            <div class="box-content">

                                                <?php
                                                foreach ($modelBusiness['businessPromos'] as $dataBusinessPromo):

                                                    $urlPromoDetail = ['page/detail-business-promo', 'id' => $dataBusinessPromo['id']];

                                                    $img = \Yii::$app->params['endPointLoadImage'] . 'business-promo?image=' . $dataBusinessPromo['image'] . '&w=1252&h=706';

                                                    $dateStart = \Yii::$app->formatter->asDate($dataBusinessPromo['date_start'], 'medium');
                                                    $dateEnd = \Yii::$app->formatter->asDate($dataBusinessPromo['date_end'], 'medium'); ?>

                                                    <div class="row mb-10">
                                                        <div class="col-sm-5 col-12">
                                                            <?= Html::a(Html::img($img, ['class' => 'img-fluid']), $urlPromoDetail); ?>
                                                        </div>

                                                        <div class="clearfix d-block d-sm-none">&nbsp;</div>

                                                        <div class="col-sm-7 col-12">
                                                            <h6 class="m-0">
                                                                <?= Html::a($dataBusinessPromo['title'], $urlPromoDetail) ?>
                                                            </h6>
                                                            <p class="mb-10">
                                                                <?= $dataBusinessPromo['short_description'] ?>
                                                            </p>
                                                            <p>
                                                                <?= \Yii::t('app', 'Valid from {dateStart} until {dateEnd}', ['dateStart' => $dateStart, 'dateEnd' => $dateEnd]); ?>
                                                            </p>
                                                            <p>
                                                                <?= Html::a('<span class="text-danger"><i class="aicon aicon-circle-right"></i> ' . \Yii::t('app', 'View Details') . '</span>', $urlPromoDetail) ?>
															</p>
                                                        </div>
                                                    </div>

                                                    <div class="divider-w mb-10"></div>

                                                <?php
                                                endforeach; ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php
                            endif; ?>

                            <div class="row mt-20">
                                <div class="col-12">

                                    <div class="card view">
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a id="review-tab" class="nav-link active text-center" href="#view-review" aria-controls="view-review" role="tab" data-toggle="tab">
                                                	<i class="aicon aicon-document-edit aicon-1-5x"></i><span class="badge total-review"></span><br>
                                                	<?= \Yii::t('app', 'Review') ?>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a id="about-tab" class="nav-link text-center" href="#view-about" aria-controls="view-about" role="tab" data-toggle="tab">
                                                    <i class="aicon aicon-icon-restaurant aicon-1-5x"></i><br>
                                                    <?= \Yii::t('app', 'About') ?>
                                                </a>
                                            </li>
                                            <li class="nav-item d-none d-sm-block">
                                                <a id="photo-tab" class="nav-link text-center" href="#view-photo" aria-controls="view-photo" role="tab" data-toggle="tab">
                                                    <i class="aicon aicon-camera1 aicon-1-5x"></i><span class="badge total-photo"></span><br>
                                                    <?= \Yii::t('app', 'Photo') ?>
                                                </a>
                                            </li>
                                            <li class="nav-item d-none d-sm-block">
                                                <a id="map-tab" class="nav-link text-center" href="#view-map" aria-controls="view-map" role="tab" data-toggle="tab">
                                                    <i class="aicon aicon-icon-thin-location-line aicon-1-5x"></i><br>
                                                    <?= \Yii::t('app', 'Map') ?>
                                                </a>
                                            </li>
                                            <li class="nav-item dropdown d-block d-sm-none">
                                                <a class="nav-link dropdown-toggle more-detail-menu text-center" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                                    <i class="aicon aicon-more aicon-1-5x"></i><br>
                                                    More <span class="caret"></span>
                                                </a>
                                                <div class="dropdown-menu pull-right p-0">
                                                    <a id="photo-tab" class="dropdown-item" href="#view-photo" aria-controls="view-photo" role="tab" data-toggle="tab">
                                                    	<h6><i class="aicon aicon-camera1"></i> <?= \Yii::t('app', 'Photo') ?> (<span class="total-photo"></span>)</h6>
                                                    </a>
                                                   	<a id="map-tab" class="dropdown-item" href="#view-map" aria-controls="view-map" role="tab" data-toggle="tab">
                                               			<h6><i class="aicon aicon-icon-thin-location-line"></i> <?= \Yii::t('app', 'Map')?></h6>
                                           			</a>
                                                </div>
                                            </li>
                                        </ul>

                                        <div class="tab-content">

                                            <div role="tabpanel" class="tab-pane fade show active p-0" id="view-review" aria-labelledby="review-tab">

                                                <?= $this->render('detail/_review.php', [
                                                    'modelBusiness' => $modelBusiness,
                                                    'modelUserPostMain' => $modelUserPostMain,
                                                    'dataUserVoteReview' => $dataUserVoteReview,
                                                    'modelPost' => $modelPost,
                                                    'modelRatingComponent' => $modelRatingComponent
                                                ]) ?>

                                            </div>

                                            <div role="tabpanel" class="tab-pane fade p-0" id="view-about" aria-labelledby="about-tab">

                                                <?= $this->render('detail/_about.php', [
                                                    'businessAbout' => $modelBusiness['about'],
                                                    'businessName' => $modelBusiness['name'],
                                                ]) ?>

                                            </div>

                                            <div role="tabpanel" class="tab-pane fade p-0" id="view-photo" aria-labelledby="photo-tab">

                                                <?= $this->render('detail/_photo.php', [
                                                    'modelBusiness' => $modelBusiness,
                                                    'modelPostPhoto' => $modelPostPhoto
                                                ]) ?>

                                            </div>

                                            <div role="tabpanel" class="tab-pane fade p-0" id="view-map" aria-labelledby="map-tab">

                                                <?= $this->render('detail/_map.php', [
                                                    'coordinate' => explode(',', $modelBusiness['businessLocation']['coordinate']),
                                                ]) ?>

                                            </div>
                                        </div>
                                    </div>

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
echo Html::img($ogImage, ['id' => 'img-for-share-link']);

$this->params['beforeEndBody'][] = function() use ($modelBusiness, $modelUserReport) {

    echo '
        <div id="modal-coming-soon" class="modal fade in" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">' . \Yii::t('app', 'Coming Soon') . '</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Fitur ini akan segera hadir.</p>
                    </div>
                </div>
            </div>
        </div>
    ';

    echo '
        <div id="modal-report" class="modal fade in" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">';

                    $form = ActiveForm::begin([
                        'id' => 'report-form',
                        'action' => ['action/submit-report'],
                        'fieldConfig' => [
                            'template' => '{label}{input}{error}'
                        ],
                    ]);

                        echo '
                            <div class="modal-header">
                                <h6 class="modal-title"><i class="aicon aicon-warning"></i> ' . \Yii::t('app', 'Report') . '</h6>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="overlay" style="display: none;"></div>
                                <div class="loading-img" style="display: none;"></div>';

                                echo Html::hiddenInput('business_id', $modelBusiness['id']);

                                echo Html::label(\Yii::t('app', 'This business:'));
                                echo $form->field($modelUserReport, 'report_status')->radioList([
                                        'Closed' => \Yii::t('app', 'Closed'),
                                        'Moved'=> \Yii::t('app', 'Moved'),
                                        'Duplicate' => \Yii::t('app', 'Duplicate'),
                                        'Inaccurate' => \Yii::t('app', 'Inaccurate'),
                                    ],
                                    [
                                        'item' => function ($index, $label, $name, $checked, $value) {

                                            return '
                                                <div class="radio">
                                                    <label>' .
                                                        Html::radio($name, $checked, ['class' => 'report-subject', 'value' => $value]) .
                                                        $label . '
                                                    </label>
                                                </div>
                                            ';
                                        }
                                    ])
                                    ->label(false);

                                echo $form->field($modelUserReport, 'text')->textArea([
                                        'rows' => 4,
                                        'placeholder' => \Yii::t('app', 'Tell about your situation or complaint.')
                                    ])
                                    ->label(false);

                                echo '
                            </div>
                            <div class="modal-footer">' .
                                Html::submitButton(\Yii::t('app', 'Submit'), ['class' => 'btn btn-raised btn-danger btn-round btn-submit-modal-report']) . '&nbsp&nbsp' .
                                Html::a(\Yii::t('app', 'Cancel'), null, ['class' => 'btn btn-raised btn-round btn-close-modal-report']) . '
                            </div>';

                    ActiveForm::end();

                echo '
                </div>
            </div>
        </div>
    ';

    echo '
        <div id="modal-confirmation" class="modal fade in" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">' . \Yii::t('app', 'Confirmation') . '</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ' . \Yii::t('app', 'Are you sure want to delete this?') . '
                    </div>
                    <div class="modal-footer">
                        <button id="btn-delete" class="btn btn-danger btn-raised" type="button">' . \Yii::t('app', 'Delete') .'</button><br>
                        <button class="btn btn-raised" data-dismiss="modal" type="button">' . \Yii::t('app', 'Cancel') .'</button>
                    </div>
                </div>
            </div>
        </div>
    ';
};

$this->registerCssFile(\Yii::$app->homeUrl . 'lib/Magnific-Popup/dist/magnific-popup.css', ['depends' => 'yii\web\JqueryAsset']);

Snackbar::widget();
webview\components\RatingColor::widget();
frontend\components\Readmore::widget();
frontend\components\FacebookShare::widget();

$this->registerJs(Snackbar::messageResponse(), View::POS_HEAD);

$this->registerJsFile(\Yii::$app->homeUrl . 'lib/Magnific-Popup/dist/jquery.magnific-popup.js', ['depends' => 'yii\web\JqueryAsset']);

$jscript = '
    $("#img-for-share-link").hide();

    $(".see-map-shortcut").on("click", function(event) {

        var viewMapElement = "";

        $("a[aria-controls=\"view-map\"]").each(function() {

            if ($(".more-detail-menu").is(":visible")) {

                viewMapElement = $(".more-detail-menu").siblings().find("a[aria-controls=\"view-map\"]");
            } else {

                if ($(this).hasClass("nav-link")) {

                    viewMapElement = $(this);
                }
            }
        });

        if (!viewMapElement.hasClass("active")) {

            viewMapElement.tab("show");

            viewMapElement.on("shown.bs.tab", function (e) {

                $("html, body").animate({ scrollTop: $("#title-map").offset().top }, "slow");
                $(this).off("shown.bs.tab");
            });
        } else {

            $("html, body").animate({ scrollTop: $("#title-map").offset().top }, "slow");
        }

        return false;
    });

    $(".love-place").on("click", function() {

        $.ajax({
            cache: false,
            url: $(this).attr("href"),
            type: "POST",
            data: {
                "business_id": $(".business-id").val()
            },
            success: function(response) {

                if (response.success) {

                    var count = parseInt($(".love-place-count").html());

                    if (response.is_active) {

                        $(".love-place").addClass("selected");
                        $(".love-place-count").html(count + 1);
                    } else {

                        $(".love-place").removeClass("selected");
                        $(".love-place-count").html(count - 1);
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

    $(".been-here").on("click", function() {

        $.ajax({
            cache: false,
            url: $(this).attr("href"),
            type: "POST",
            data: {
                "business_id": $(".business-id").val()
            },
            success: function(response) {

                if (response.success) {

                    var count = parseInt($(".been-here-count").html());

                    if (response.is_active) {

                        $(".been-here").addClass("selected");
                        $(".been-here-count").html(count + 1);
                    } else {

                        $(".been-here").removeClass("selected");
                        $(".been-here-count").html(count - 1);
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

    $(".message-feature").on("click", function() {

        $("#modal-coming-soon").modal("show");

        return false;
    });

    $(".share-feature").on("click", function() {

        facebookShare({
            ogUrl: "' . $ogUrl . '",
            ogTitle: "' . $ogTitle . '",
            ogDescription: "' . addslashes($ogDescription) . '",
            ogImage: "' . $ogImage . '",
            type: "Halaman Bisnis"
        });

        return false;
    });

    $(".report-business-trigger").on("click", function() {

        $("#modal-report").modal("show");

        return false;
    });

    $(".btn-close-modal-report").on("click", function() {

        $("#modal-report").modal("hide");

        return false;
    });

    $("form#report-form").on("beforeSubmit", function(event) {

        var thisObj = $(this);

        thisObj.find(".overlay").show();
        thisObj.find(".loading-img").show();

        if (thisObj.find(".has-error").length)  {

            thisObj.find(".overlay").hide();
            thisObj.find(".loading-img").hide();

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

                if (response.success) {

                    $("#modal-report").modal("hide");

                    $("#modal-report").find("#userreport-text").val("");
                    $("#modal-report").find(".form-group").removeClass("has-success");

                    messageResponse(response.icon, response.title, response.message, response.type);
                } else {

                    messageResponse(response.icon, response.title, response.message, response.type);
                }

                thisObj.find(".overlay").hide();
                thisObj.find(".loading-img").hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {

                messageResponse("aicon aicon-icon-info", xhr.status, xhr.responseText, "danger");

                thisObj.find(".overlay").hide();
                thisObj.find(".loading-img").hide();
            }
        });

        return false;
    });

    $("#modal-report").on("hidden.bs.modal", function() {

        $(this).find("#userreport-report_status").find("input.report-subject").prop("checked", false).trigger("change");
        $(this).find(".form-group").removeClass("has-error");
        $(this).find(".form-group").removeClass("has-success");
        $(this).find(".form-group").find(".help-block").html("");
    });

    $(".ambience-gallery, .menu-gallery").owlCarousel({
        lazyLoad: true,
        items: 1,
        margin: 1,
        autoHeight: true,
    });
';

$this->registerJs($jscript); ?>