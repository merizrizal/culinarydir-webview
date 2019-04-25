<?php

namespace webview\assets;

use yii\web\AssetBundle;

class RateyoAsset extends AssetBundle {

    public $sourcePath = '@npm/rateyo';

    public $css = [
        'min/jquery.rateyo.min.css'
    ];
    public $js = [
        'min/jquery.rateyo.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
