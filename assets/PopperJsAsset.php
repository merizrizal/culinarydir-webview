<?php

namespace webview\assets;

use yii\web\AssetBundle;

class PopperJsAsset extends AssetBundle {

    public $sourcePath = '@npm/popper.js';

    public $js = [
        'dist/umd/popper.min.js',
    ];
}
