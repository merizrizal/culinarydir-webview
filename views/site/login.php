<?php

use frontend\components\GrowlCustom;
use yii\authclient\widgets\AuthChoice;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\LoginForm */

$this->title = 'Login';

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => 'asik, makan, kuliner, bandung, jakarta'
]);

$this->registerMetaTag([
    'name' => 'description',
    'content' => 'Temukan Bisnis Kuliner Favorit Anda di Asikmakan.com'
]);

kartik\select2\Select2Asset::register($this);
kartik\select2\ThemeKrajeeAsset::register($this); ?>

<div class="main">
    <section class="module-small bg-main">
        <div class="container register">
            <div class="row">
                <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-12">
                    <div class="box bg-white">
                        <div class="box-content">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="font-alt"><?= Yii::t('app', 'Login')?></h4>
                                    <hr class="divider-w mb-20">

                                    <?php
                                    $form = ActiveForm::begin([
                                        'id' => 'login-form',
                                        'action' => 'login',
                                        'options' => [
                                        ],
                                        'fieldConfig' => [
                                            'template' => '{input}{error}',
                                        ]
                                    ]); ?>

                                        <div class="row">
                                            <div class="col-md-12">

                                                <?= $form->field($model, 'login_id')->textInput([
                                                    'id' => 'login_id',
                                                    'class' => 'form-control',
                                                    'placeholder' => $model->getAttributeLabel('login_id')
                                                ]) ?>

                                            </div>
                                            <div class="col-md-12">

                                                <?= $form->field($model, 'password')->passwordInput([
                                                    'id' => 'password',
                                                    'class' => 'form-control',
                                                    'placeholder' => $model->getAttributeLabel('password')
                                                ]) ?>

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-7">

                                                        <?= $form->field($model, 'rememberMe')->checkbox() ?>

                                                    </div>
                                                    <div class="col-md-5">

                                                        <?= Html::a(Yii::t('app', 'Forgot Password') . ' ?', ['site/request-reset-password'], ['class' => 'form-group']) ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">

                                                    <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-block btn-round btn-d', 'name' => 'loginButton', 'value' => 'loginButton']) ?>

                                                    <div class="mt-20 mb-20 align-center"><?= Yii::t('app', 'OR') ?></div>

                                                    <div class="mt-10">



                                                    </div>

                                                    <hr class="divider-w mt-20 mb-10">

                                                    <div class="text-center">
                                                        <h4>
                                                            <small><?= Yii::t('app', 'Don\'t have {app} account?', ['app' => Yii::$app->name]) . ' ' . Html::a(Yii::t('app', 'Register now'), ['site/register']) ?></small>
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php
                                    ActiveForm::end(); ?>

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
GrowlCustom::widget();

$this->registerJs(GrowlCustom::messageResponse(), View::POS_HEAD);

$jscript = '';

if (!empty(($message = Yii::$app->session->getFlash('resetSuccess')))) {

    $jscript = 'messageResponse("aicon aicon-icon-tick-in-circle", "Reset Berhasil", "' . $message . '", "success");';
}

$this->registerJs($jscript); ?>