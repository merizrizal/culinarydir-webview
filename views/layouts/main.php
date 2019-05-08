<?php

/* @var $this \yii\web\View */
/* @var $content string */

use webview\assets\AppAsset;
use yii\helpers\Html;
use yii\web\View;

AppAsset::register($this); ?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= \Yii::$app->language ?>">
    <head>
        <meta charset="<?= \Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="app" content="<?= Html::encode(\Yii::$app->name) ?>">
        <?= Html::csrfMetaTags() ?>

        <title><?= Html::encode(\Yii::$app->name) . ' - ' . Html::encode($this->title) ?></title>
        <?php $this->head(); ?>
    </head>
    <body>
    <?php $this->beginBody() ?>

    	<?= $content ?>

    	<?php
        if (!empty($this->params['beforeEndBody'])) {
            foreach ($this->params['beforeEndBody'] as $value) {
                $value();
            }
        }

        $this->registerJs('
            $(document).on("ready", function() {

                $("body").bootstrapMaterialDesign();
            });', View::POS_END); ?>

    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
