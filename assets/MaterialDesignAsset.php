<?php

namespace webview\assets;

use yii\web\AssetBundle;

class MaterialDesignAsset extends AssetBundle {

    public $sourcePath = '@npm/bootstrap-material-design';

    public $css = [
        'dist/css/bootstrap-material-design.min.css'
    ];
    public $js = [
        'dist/js/bootstrap-material-design.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'webview\assets\PopperJsAsset',
    ];
}
