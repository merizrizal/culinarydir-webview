<?php

use frontend\components\AddressType;
use frontend\components\GrowlCustom;
use yii\bootstrap\Modal;
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
/* @var $queryParams array */
/* @var $isOrderOnline boolean */

common\assets\OwlCarouselAsset::register($this);

$this->title = $modelBusiness['name'];

$ogUrl = Yii::$app->urlManager->createAbsoluteUrl([
    'page/detail',
    'city' => Inflector::slug($modelBusiness['businessLocation']['city']['name']),
    'uniqueName' => $modelBusiness['unique_name']
]);

$ogTitle = $modelBusiness['name'];

$ogDescription = 'Kunjungi kami di ' . AddressType::widget([
    'businessLocation' => $modelBusiness['businessLocation'],
    'showDetail' => true
]) . '.';

$ogBusinessCategory = '';
$ogPriceRange = '-';
$ogProductCategory = '';
$ogFacility = '';

$ogImage = Yii::$app->params['endPointLoadImage'] . 'registry-business/image=&w=786&h=425';

$ogBusinessHour = null;

if (!empty($modelBusiness['about'])) {

    $ogDescription = preg_replace('/[\r\n]+/','' , strip_tags($modelBusiness['about'])) . '.';
}

foreach ($modelBusiness['businessCategories'] as $dataBusinessCategory) {

    $ogBusinessCategory .= $dataBusinessCategory['category']['name'] . ',';
}

if (!empty($modelBusiness['businessDetail']['price_min']) && !empty($modelBusiness['businessDetail']['price_max'])) {

    $ogPriceRange = Yii::$app->formatter->asShortCurrency($modelBusiness['businessDetail']['price_min']) . ' - ' . Yii::$app->formatter->asShortCurrency($modelBusiness['businessDetail']['price_max']);
} else if (empty($modelBusiness['businessDetail']['price_min']) && !empty($modelBusiness['businessDetail']['price_max'])) {

    $ogPriceRange =  Yii::t('app', 'Under') . ' ' . Yii::$app->formatter->asShortCurrency($modelBusiness['businessDetail']['price_max']);
} else if (empty($modelBusiness['businessDetail']['price_max']) && !empty($modelBusiness['businessDetail']['price_min'])) {

    $ogPriceRange =  Yii::t('app', 'Above') . ' ' . Yii::$app->formatter->asShortCurrency($modelBusiness['businessDetail']['price_min']);
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

        $ogImage = Yii::$app->params['endPointLoadImage'] . 'registry-business?image=' . $dataImageThumbail['image'];
        break;
    }
}

$ogUrlMenuDetail = ['page/menu', 'uniqueName' => $modelBusiness['unique_name']];

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => 'asik, makan, kuliner, bandung, jakarta'
]);

$this->registerMetaTag([
    'name' => 'description',
    'content' => $ogDescription
]);

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
]);

$noImg = Yii::$app->params['endPointLoadImage'] . 'registry-business?image=&w=756&h=425'; ?>

<div class="main bg-main">
    <section>
        <div class="detail place-detail">
            <div class="row">
                <div class="col-xs-12">

                    <div class="row mb-20">
                        <div class="col-xs-12">

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="view">
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li role="presentation" class="nav-item active">
                                                <a class="nav-link" href="#photo" aria-controls="photo" role="tab" data-toggle="tab"><i class="aicon aicon-camera1"></i> <?= Yii::t('app', 'Ambience') ?></a>
                                            </li>
                                            <li role="presentation" class="nav-item">
                                                <a class="nav-link" href="#menu" aria-controls="menu" role="tab" data-toggle="tab"><i class="aicon aicon-icon-budicon"></i> Menu</a>
                                            </li>
                                        </ul>

                                        <div class="tab-content box bg-white">
                                            <div role="tabpanel" class="tab-pane fade in active" id="photo">
                                                <div class="row">
                                                    <div class="col-xs-12">
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

                                                                        $img = Yii::$app->params['endPointLoadImage'] . 'registry-business?image=' . $businessImage['image'] . '&w=1252&h=706';
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
                                            <div role="tabpanel" class="tab-pane fade in active" id="menu">
                                                <div class="row">
                                                    <div class="col-xs-12">
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

                                                                        $img = Yii::$app->params['endPointLoadImage'] . 'registry-business?image=' . $businessImage['image'] . '&w=1252&h=706';
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
                                <div class="col-xs-12">
                                    <div class="box bg-white">
                                        <div class="box-title">
                                            <div class="row">
                                                <div class="col-sm-12 col-xs-12">
                                                    <h4 class="mb-0 business-name"><?= $modelBusiness['name']; ?></h4>
                                                </div>

												<div class="d-none d-sm-block d-md-none col-sm-12 clearfix"></div>
                                                <div class="d-block d-sm-none clearfix"></div>

                                                <div class="col-sm-12 col-xs-12">

                                                    <?php
                                                    $categories = '';

                                                    foreach ($modelBusiness['businessCategories'] as $dataBusinessCategory) {

                                                        $categories .= $dataBusinessCategory['category']['name'] . ' / ';
                                                    } ?>

                                                    <h5 class="d-none d-sm-block d-md-none mt-10"><?= trim($categories, ' / ') ?></h5>
                                                    <h6 class="d-block d-sm-none mt-10"><?= trim($categories, ' / ') ?></h6>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="divider-w">

                                        <div class="box-content">
                                            <div class="row mt-10">
                                                <div class="col-xs-12 pull-right mb-10">
                                                    <div class="business-rating">

                                                        <?= $this->render('@webview/views/data/business_rating.php', [
                                                            'modelBusinessDetail' => $modelBusiness['businessDetail'],
                                                            'modelBusinessDetailVote' => $modelBusiness['businessDetailVotes'],
                                                            'modelRatingComponent' => $modelRatingComponent,
                                                        ]) ?>

                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="widget">
                                                                <ul class="icon-list">
                                                                    <li>
                                                                        <i class="aicon aicon-home1"></i>

                                                                        <?php
                                                                        echo AddressType::widget([
                                                                            'businessLocation' => $modelBusiness['businessLocation'],
                                                                            'showDetail' => true
                                                                        ]);

                                                                        echo !empty($modelBusiness['businessLocation']['address_info']) ? '<br>' . $modelBusiness['businessLocation']['address_info'] : '';

                                                                        echo Html::a(Yii::t('app', 'See Map'), '', ['class' => 'btn btn-standard see-map-shortcut font-12 d-none d-sm-block d-md-none', 'style' => 'width: 70px']);
                                                                        echo Html::a(Yii::t('app', 'See Map'), '', ['class' => 'btn btn-standard see-map-shortcut xs font-12 d-block d-sm-none', 'style' => 'width: 70px']); ?>

                                                                    </li>
                                                                    <li>
                                                                        <i class="aicon aicon-rupiah"></i>
                                                                        <?= $ogPriceRange; ?>
                                                                    </li>
                                                                    <li><i class="aicon aicon-icon-phone-fill"></i> <?= !empty($modelBusiness['phone1']) ? $modelBusiness['phone1'] : '-' ?></li>
                                                                    <li class="icon-list-parent">
                                                                    	<i class="aicon aicon-clock"></i> <?= Yii::t('app', 'Operational Hours') ?>

                                                                    	<?php
                                                                        if (!empty($modelBusiness['businessHours'])):

                                                                            $days = Yii::$app->params['days'];

                                                                            Yii::$app->formatter->timeZone = 'Asia/Jakarta';

                                                                            $now = Yii::$app->formatter->asTime(time());

                                                                            Yii::$app->formatter->timeZone = 'UTC';

                                                                            $isOpen = false;
                                                                            $listSchedule = '';
                                                                            $hour = null;
                                                                            $hourAdditional = null;

                                                                            $ogBusinessHour = '"openingHoursSpecification": [';

                                                                            foreach ($modelBusiness['businessHours'] as $dataBusinessHour) {

                                                                                $day = $days[$dataBusinessHour['day'] - 1];

                                                                                $openAt = Yii::$app->formatter->asTime($dataBusinessHour['open_at'], 'HH:mm');
                                                                                $closeAt = Yii::$app->formatter->asTime($dataBusinessHour['close_at'], 'HH:mm');

                                                                                $isOpenToday = false;
                                                                                $is24Hour = (($dataBusinessHour['open_at'] == '00:00:00') && ($dataBusinessHour['close_at'] == '24:00:00'));

                                                                                $businessHour = $is24Hour ? Yii::t('app','24 Hours') : ($openAt . ' - ' . $closeAt);
                                                                                $businessHourAdditional = '';

                                                                                if (date('l') == $day) {

                                                                                    $isOpen = $now >= $dataBusinessHour['open_at'] && $now <= $dataBusinessHour['close_at'];

                                                                                    $isOpenToday = true;
                                                                                    $hour = $businessHour;
                                                                                }

                                                                                $ogBusinessHour .= '{"@type": "OpeningHoursSpecification", "dayOfWeek": "' . $day . '", "opens": "' . $openAt . '", "closes": "' . $closeAt . '"},';

                                                                                if (!empty($dataBusinessHour['businessHourAdditionals'])) {

                                                                                    foreach ($dataBusinessHour['businessHourAdditionals'] as $dataBusinessHourAdditional) {

                                                                                        $openAt = Yii::$app->formatter->asTime($dataBusinessHourAdditional['open_at'], 'HH:mm');
                                                                                        $closeAt = Yii::$app->formatter->asTime($dataBusinessHourAdditional['close_at'], 'HH:mm');

                                                                                        $businessHourAdditional .= '<div class="col-xs-offset-5 col-xs-7 p-0">' . $openAt . ' - ' . $closeAt . '</div>';

                                                                                        if (date('l') == $day) {

                                                                                            $hourAdditional .= '<br>' . Yii::$app->formatter->asTime($dataBusinessHourAdditional['open_at'], 'HH:mm') . ' - ' . Yii::$app->formatter->asTime($dataBusinessHourAdditional['close_at'], 'HH:mm');

                                                                                            if (!$isOpen) {

                                                                                                $isOpen = $now >= $dataBusinessHourAdditional['open_at'] && $now <= $dataBusinessHourAdditional['close_at'];
                                                                                            }
                                                                                        }

                                                                                        $ogBusinessHour .= '{"@type": "OpeningHoursSpecification", "dayOfWeek": "' . $day . '", "opens": "' . $openAt . '", "closes": "' . $closeAt . '"},';
                                                                                    }
                                                                                }

                                                                                $listSchedule .= '
                                                                                    <li>
                                                                                        <div class="col-xs-5">' .
                                                                                            ($isOpenToday ? '<strong>' . Yii::t('app', $day) . '</strong>' : Yii::t('app', $day)) .
                                                                                        '</div>
                                                                                        <div class="col-xs-7 p-0">' .
                                                                                            ($isOpenToday ? '<strong>' . $businessHour . '</strong>' : $businessHour) .
                                                                                        '</div>' .
                                                                                        ($isOpenToday ? '<strong>' . $businessHourAdditional . '</strong>' : $businessHourAdditional) .
                                                                                    '</li>';
                                                                            }

                                                                            $ogBusinessHour = trim($ogBusinessHour, ',') .  '],'; ?>

                                                                            <span class="label <?= $isOpen ? 'label-success' : 'label-danger' ?>"><strong><?= $isOpen ? Yii::t('app', 'Open') : Yii::t('app', 'Closed') ?></strong></span>
    																		<div class="btn-group m-0">
                                                                                <button type="button" class="btn btn-raised btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    <span class="caret"></span>
                                                                                </button>
                                                                                <ul class="dropdown-menu list-schedule pull-right">
                                                                                    <?= $listSchedule ?>
                                                                                </ul>
                                                                            </div>

                                                                            <?php
                                                                            if ($isOpen): ?>

                                                                                <table style="margin-left: 18px">
                                                                                    <tr>
    																					<td valign="top"><?= Yii::t('app', 'Today') ?></td>
    																					<td valign="top">&nbsp; : &nbsp;</td>
    																					<td>
    																						<?= $hour . $hourAdditional ?>
    																					</td>
    																				</tr>
                                                                                </table>

                                                                            <?php
                                                                            endif;
                                                                        else:

                                                                            echo '<br><span style="margin-left: 18px">' . Yii::t('app', 'Data Not Available') . '</span>';
                                                                        endif; ?>

                                                                    </li>
                                                                    <li class="tag">

                                                                        <?php
                                                                        foreach ($modelBusiness['businessProductCategories'] as $dataBusinessProductCategory) {

                                                                            if (!empty($dataBusinessProductCategory['productCategory'])) {

                                                                                echo '<strong class="text-red">#</strong>' . $dataBusinessProductCategory['productCategory']['name'] . ' ';
                                                                            }
                                                                        } ?>

                                                                    </li>
                                                                    <li class="tag">

                                                                        <?php
                                                                        foreach ($modelBusiness['businessFacilities'] as $dataBusinessFacility) {

                                                                            echo '<strong class="text-blue">#</strong>' . $dataBusinessFacility['facility']['name'] . ' ';
                                                                        } ?>

                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php
                                            $orderbtn = Html::a('<i class="aicon aicon-icon-online-ordering aicon-1-2x"></i> ' . Yii::t('app', 'Online Order'), $ogUrlMenuDetail, [
                                                'class' => 'btn btn-standard btn-danger btn-block btn-raised btn-round'
                                            ]);

                                            if (!$isOrderOnline) {

                                                $orderbtn = Html::a('<i class="fas fa-utensils"></i> ' . Yii::t('app', 'Menu List'), $ogUrlMenuDetail, [
                                                    'class' => 'btn btn-standard btn-danger btn-block btn-raised btn-round'
                                                ]);
                                            }

                                            $reportbtn = Html::a('<i class="aicon aicon-warning aicon-1-2x"></i> ' .  Yii::t('app', 'Report'), '', [
                                                'class' => 'btn btn-standard btn-danger btn-block btn-raised btn-round report-business-trigger'
                                            ]);

                                            $messagebtn = Html::a('<i class="aicon aicon-icon-envelope aicon-1-2x"></i> Message', '', [
                                                'class' => 'btn btn-standard btn-danger btn-block btn-raised btn-round message-feature'
                                            ]); ?>

                                            <div class="row mt-10 mb-10">
                                                <div class="col-sm-3 col-xs-6 col">
                                                    <?= $reportbtn ?>
                                                </div>
                                                <div class="col-sm-3 col-xs-6 col">
                                                    <?= $messagebtn ?>
                                                </div>
                                                <div class="col-sm-6 col-xs-12 pull-right">
                                                    <?= $orderbtn ?>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="divider-w">

                                        <div class="box-footer">

                                            <?= Html::hiddenInput('business_id', $modelBusiness['id'], ['class' => 'business-id']) ?>

                                            <div class="row">
                                                <div class="col-xs-12">

                                                    <?php
                                                    $selectedVisit = !empty($modelBusiness['userVisits'][0]) ? 'selected' : '';
                                                    $selectedLove = !empty($modelBusiness['userLoves'][0]) ? 'selected' : '';

                                                    $visitValue = !empty($modelBusiness['businessDetail']['visit_value']) ? $modelBusiness['businessDetail']['visit_value'] : 0;
                                                    $loveValue = !empty($modelBusiness['businessDetail']['love_value']) ? $modelBusiness['businessDetail']['love_value'] : 0; ?>

                                                    <ul class="list-inline list-default mt-0 mb-0">
                                                        <li>
                                                            <div class="btn-group" role="group">

                                                                <?= Html::a('<i class="aicon aicon-icon-been-there"></i> <span class="been-here-count">' . $visitValue . '</span> Visit', ['action/submit-user-visit'], [
                                                                    'class' => 'btn btn-raised btn-default btn-standard btn-round btn-md been-here ' . $selectedVisit,
                                                                ]) ?>

                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="btn-group" role="group">

                                                                <?= Html::a('<i class="aicon aicon-heart1"></i> <span class="love-place-count">' . $loveValue . '</span> Love', ['action/submit-user-love'], [
                                                                    'class' => 'btn btn-raised btn-default btn-standard btn-round btn-md love-place ' . $selectedLove,
                                                                ]) ?>

                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="btn-group" role="group">
                                                                <?= Html::a('<i class="aicon aicon-share1"></i>', '', ['class' => 'btn btn-raised btn-standard btn-round btn-md share-feature d-block d-sm-none']) ?>
                                                                <?= Html::a('<i class="aicon aicon-share1"></i> Share', '', ['class' => 'btn btn-raised btn-standard btn-round btn-md share-feature d-none d-sm-block d-md-none']) ?>
                                                            </div>
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
                                    <div class="col-xs-12">
                                        <div class="box bg-white">
                                            <div class="box-title">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <h4 class="m-0"><?= Yii::t('app', 'Special & Discount') ?> !!</h4>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr class="divider-w">

                                            <div class="box-content">
                                                <div class="row">
                                                    <div class="col-xs-12">

                                                        <?php
                                                        foreach ($modelBusiness['businessPromos'] as $dataBusinessPromo):

                                                            $urlPromoDetail = [
                                                                'page/detail-business-promo',
                                                                'id' => $dataBusinessPromo['id'],
                                                                'uniqueName' => $modelBusiness['unique_name'],
                                                            ];

                                                            $img = Yii::$app->params['endPointLoadImage'] . 'business-promo?image=' . $dataBusinessPromo['image'] . '&w=1252&h=706';

                                                            $dateStart = Yii::$app->formatter->asDate($dataBusinessPromo['date_start'], 'medium');
                                                            $dateEnd = Yii::$app->formatter->asDate($dataBusinessPromo['date_end'], 'medium'); ?>

                                                            <div class="row mb-10">
                                                                <div class="col-xs-12">
                                                                    <?= Html::a(Html::img($img, ['class' => 'img-responsive']), $urlPromoDetail); ?>
                                                                </div>

                                                                <div class="col-xs-12 mt-10">
                                                                    <h4 class="promo-title">
                                                                        <?= Html::a($dataBusinessPromo['title'], $urlPromoDetail) ?>
                                                                    </h4>
                                                                    <p class="description mb-10">
                                                                        <?= $dataBusinessPromo['short_description'] ?>
                                                                    </p>
                                                                    <p>
                                                                        <?= Yii::t('app', 'Valid from {dateStart} until {dateEnd}', ['dateStart' => $dateStart, 'dateEnd' => $dateEnd]); ?>
                                                                    </p>
                                                                    <p>
                                                                        <?= Html::a(Yii::t('app', 'View Details'), $urlPromoDetail, ['class' => 'btn btn-standard btn-danger btn-round btn-raised']) ?>
																	</p>
                                                                </div>
                                                            </div>

                                                            <hr class="divider-w mb-10">

                                                        <?php
                                                        endforeach; ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php
                            endif; ?>

                            <div class="row mt-20">
                                <div class="col-xs-12">

                                    <div class="view">
                                        <ul class="nav nav-tabs widget mb-10" role="tablist">
                                            <li role="presentation" class="active">
                                                <a href="#view-review" aria-controls="view-review" role="tab" data-toggle="tab">
                                                    <ul class="link-icon list-inline tab-detail">
                                                        <li>
                                                            <ul class="text-center">
                                                                <li><i class="aicon aicon-document-edit aicon-1-5x"></i><span class="badge total-review"></span></li>
                                                                <li><?= Yii::t('app', 'Review') ?></li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#view-about" aria-controls="view-about" role="tab" data-toggle="tab">
                                                    <ul class="link-icon list-inline tab-detail">
                                                        <li>
                                                            <ul class="text-center">
                                                                <li><i class="aicon aicon-icon-restaurant aicon-1-5x"></i></li>
                                                                <li><?= Yii::t('app', 'About') ?></li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </a>
                                            </li>
                                            <li role="presentation" class="visible-tab">
                                                <a href="#view-photo" aria-controls="view-photo" role="tab" data-toggle="tab">
                                                    <ul class="link-icon list-inline tab-detail">
                                                        <li>
                                                            <ul class="text-center">
                                                                <li><i class="aicon aicon-camera1 aicon-1-5x"></i><span class="badge total-photo"></span></li>
                                                                <li><?= Yii::t('app', 'Photo') ?></li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </a>
                                            </li>
                                            <li role="presentation" class="visible-tab">
                                                <a href="#view-map" aria-controls="view-map" role="tab" data-toggle="tab">
                                                    <ul class="link-icon list-inline tab-detail">
                                                        <li>
                                                            <ul class="text-center">
                                                                <li><i class="aicon aicon-icon-thin-location-line aicon-1-5x"></i></li>
                                                                <li><?= Yii::t('app', 'Map')?></li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </a>
                                            </li>
                                            <li role="presentation" class="dropdown visible-xs">
                                                <a data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                                    <ul class="link-icon list-inline tab-detail">
                                                        <li>
                                                            <ul class="text-center">
                                                                <li><i class="aicon aicon-more aicon-1-5x"></i></li>
                                                                <li>More <span class="caret"></span></li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </a>
                                                <ul class="dropdown-menu pull-right">
                                                    <li role="presentation">
                                                        <a href="#view-photo" aria-controls="view-photo-xs" role="tab" data-toggle="tab">
                                                        	<h6><i class="aicon aicon-camera1"></i> <?= Yii::t('app', 'Photo') ?>(<span class="total-photo"></span>)</h6>
                                                        </a>
                                                    </li>
                                                    <li role="presentation">
                                                       	<a href="#view-map" aria-controls="view-map-xs" role="tab" data-toggle="tab">
                                                   			<h6><i class="aicon aicon-icon-thin-location-line"></i> <?= Yii::t('app', 'Map')?></h6>
                                               			</a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>

                                        <div class="tab-content">

                                            <div role="tabpanel" class="tab-pane fade in active p-0" id="view-review">

                                                <?= $this->render('detail/_review.php', [
                                                    'modelBusiness' => $modelBusiness,
                                                    'modelUserPostMain' => $modelUserPostMain,
                                                    'dataUserVoteReview' => $dataUserVoteReview,
                                                    'modelPost' => $modelPost,
                                                    'modelRatingComponent' => $modelRatingComponent,
                                                    'queryParams' => $queryParams,
                                                ]) ?>

                                            </div>

                                            <div role="tabpanel" class="tab-pane fade p-0" id="view-about">

                                                <?= $this->render('detail/_about.php', [
                                                    'businessAbout' => $modelBusiness['about'],
                                                    'businessName' => $modelBusiness['name'],
                                                ]) ?>

                                            </div>

                                            <div role="tabpanel" class="tab-pane fade p-0" id="view-photo">

                                                <?= $this->render('detail/_photo.php', [
                                                    'modelBusiness' => $modelBusiness,
                                                    'modelPostPhoto' => $modelPostPhoto,
                                                    'queryParams' => $queryParams,
                                                ]) ?>

                                            </div>

                                            <div role="tabpanel" class="tab-pane fade p-0" id="view-map">

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
        <div id="modal-coming-soon" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">' . Yii::t('app', 'Coming Soon') . '</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Fitur ini akan segera hadir.</p>
                    </div>
                </div>
            </div>
        </div>';

    echo '
        <div id="modal-confirmation" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">' . Yii::t('app', 'Confirmation') . '</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ' . Yii::t('app', 'Are you sure want to delete this?') . '
                    </div>
                    <div class="modal-footer">
                        <button id="btn-delete" class="btn btn-danger btn-raised" type="button">' . Yii::t('app', 'Delete') .'</button><br>
                        <button class="btn btn-raised" data-dismiss="modal" type="button">' . Yii::t('app', 'Cancel') .'</button>
                    </div>
                </div>
            </div>
        </div>';

    Modal::begin([
        'header' => '<i class="aicon aicon-warning"></i> ' . Yii::t('app', 'Report'),
        'id' => 'modal-report',
    ]);

        echo '<div class="overlay" style="display: none;"></div>';
        echo '<div class="loading-img" style="display: none;"></div>';

        $form = ActiveForm::begin([
            'id' => 'report-form',
            'action' => ['action/submit-report'],
            'fieldConfig' => [
                'template' => '{label}{input}{error}'
            ],
        ]);

            echo Html::hiddenInput('business_id', $modelBusiness['id']);

            echo $form->field($modelUserReport, 'report_status')
                    ->radioList([
                        'Closed' => Yii::t('app', 'Closed'),
                        'Moved'=> Yii::t('app', 'Moved'),
                        'Duplicate' => Yii::t('app', 'Duplicate'),
                        'Inaccurate' => Yii::t('app', 'Inaccurate'),
                    ],
                    [
                        'separator' => '<br>',
                        'itemOptions' => [
                            'class' => 'report-subject icheck',
                        ],
                    ])
                    ->label(Yii::t('app', 'This business:'));

            echo $form->field($modelUserReport, 'text')
                    ->textArea([
                        'rows' => 3,
                        'placeholder' => Yii::t('app', 'Tell about your situation or complaint.')
                    ])
                    ->label(Yii::t('app', 'Note'));

            echo '
                <div class="row">
                    <div class="col-xs-12 text-center">
                        ' . Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-round btn-danger btn-raised btn-submit-modal-report']) . '
                        ' . Html::a(Yii::t('app', 'Cancel'), null, ['class' => 'btn btn-round btn-raised btn-close-modal-report']) . '
                    </div>
                </div>';

        ActiveForm::end();

    Modal::end();
};

$this->registerCssFile(Yii::$app->homeUrl . 'lib/Magnific-Popup/dist/magnific-popup.css', ['depends' => 'yii\web\YiiAsset']);
$this->registerCssFile(Yii::$app->homeUrl . 'lib/customicheck/customicheck.css', ['depends' => 'yii\web\YiiAsset']);

GrowlCustom::widget();
frontend\components\RatingColor::widget();
frontend\components\Readmore::widget();
frontend\components\FacebookShare::widget();

$this->registerJs(GrowlCustom::messageResponse() . GrowlCustom::stickyResponse(), View::POS_HEAD);

$this->registerJsFile(Yii::$app->homeUrl . 'lib/Magnific-Popup/dist/jquery.magnific-popup.js', ['depends' => 'yii\web\YiiAsset']);
$this->registerJsFile(Yii::$app->homeUrl . 'lib/customicheck/customicheck.js', ['depends' => 'yii\web\YiiAsset']);

$jscript = '
    $("#img-for-share-link").hide();

    $("#menu").removeClass("in active");

    $(".see-map-shortcut").on("click", function(event) {

        var xs = $(this).hasClass("xs") ? "-xs" : "";

        if (!$("a[aria-controls=\"view-map" + xs + "\"]").parent().hasClass("active")) {

            $("a[aria-controls=\"view-map" + xs + "\"]").tab("show");

            $("a[aria-controls=\"view-map" + xs + "\"]").on("shown.bs.tab", function (e) {

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

        thisObj.siblings(".overlay").show();
        thisObj.siblings(".loading-img").show();

        if (thisObj.find(".has-error").length)  {

            thisObj.siblings(".overlay").hide();
            thisObj.siblings(".loading-img").hide();

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