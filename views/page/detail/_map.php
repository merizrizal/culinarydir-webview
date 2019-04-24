<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $coordinate array */ ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box bg-white">
            <div class="box-title" id="title-map">
            	<div class="row">
            		<div class="col-xs-6">
						<h4 class="mt-0 mb-0 inline-block"><?= Yii::t('app', 'Map') ?></h4>
            		</div>
            		<div class="col-xs-6 text-right">
            			<?= Html::a('<i class="aicon aicon-icon-thin-location-line"></i> ' . Yii::t('app', 'Open Map'), 'https://www.google.com/maps/search/?api=1&query=' . $coordinate[0] . ',' . $coordinate[1] . '', ['class' => 'btn btn-raised btn-small btn-round', 'target' => '_blank']) ?>
            		</div>
            	</div>
            </div>

            <hr class="divider-w">

            <div class="box-content">
                <div id="see-map-content" style="height: 400px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyDORji7AXzhxgYhuKOGJg6_KYrnTPYPOn8', ['depends' => 'yii\web\YiiAsset']);

$jscript = '
    var coordinate = {lat: ' . $coordinate[0] . ', lng: ' . $coordinate[1] . '};

    var seeMap = new google.maps.Map(document.getElementById("see-map-content"), {
        center: coordinate,
        zoom: 15,
        "zoomControl": true,
        "zoomControlOptions": {
            position: google.maps.ControlPosition.RIGHT_BOTTOM
        },
        "mapTypeControl": false,
        "streetViewControl": false,
        "fullscreenControl": false,
        styles: [ { "featureType": "poi.business", "stylers": [ { "visibility": "off" } ] }, { "featureType": "poi.park", "elementType": "labels.text", "stylers": [ { "visibility": "off" } ] } ],
    });

    var seeMapMarker = new google.maps.Marker({
        position: coordinate,
        map: seeMap,
        icon: {
            url: "' . Yii::$app->request->baseUrl . '/media/img/marker.png",
            scaledSize: new google.maps.Size(32, 32),
            origin: new google.maps.Point(0,0),
            anchor: new google.maps.Point(15, 32)
        }
    });
';

$this->registerJs($jscript); ?>