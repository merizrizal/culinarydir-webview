<?php

use core\models\City;
use webview\components\Snackbar;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $modelUserPerson core\models\UserPerson */
/* @var $modelUser core\models\User */
/* @var $modelPerson core\models\Person */

$this->title = 'Update Profile ' . $modelUser->full_name; ?>

<div class="main bg-main">
    <section>
        <div class="detail user-update-profile">
            <div class="row">
                <div class="col-12">
                    <div class="card box">
                        <div class="box-content">
                            <div class="row mt-10">
                                <div class="col-12 text-center">
                                    <h4 class="font-alt"><?= \Yii::t('app', 'Update Profile') ?></h4>
                                </div>
                                <div class="col-12">

                                    <?php
                                    $form = ActiveForm::begin([
                                        'id' => 'update-profile-form',
                                        'action' => ['user/update-profile'],
                                        'options' => ['enctype' => 'multipart/form-data'],
                                        'fieldConfig' => [
                                            'template' => '{input}{error}',
                                        ]
                                    ]); ?>

                                        <div class="widget-posts-body">

                                        	<?= $form->field($modelUser, 'image')->fileInput([
                                                'options' => [
                                                    'id' => 'input-img-profile',
                                                    'accept' => 'image/jpeg',
                                                ]
                                            ]); ?>

                                            <?= $form->field($modelPerson, 'first_name')->textInput([
                                                'class' => 'form-control',
                                                'placeholder' => \Yii::t('app', 'First Name'),
                                            ]) ?>


                                            <?= $form->field($modelPerson, 'last_name')->textInput([
                                                'class' => 'form-control',
                                                'placeholder' => \Yii::t('app', 'Last Name'),
                                            ]) ?>

                                            <?= $form->field($modelPerson, 'city_id')->dropDownList(
                                                ArrayHelper::map(
                                                    City::find()->orderBy('name')->asArray()->all(),
                                                    'id',
                                                    function($data) {
                                                        return $data['name'];
                                                    }
                                                ),
                                                [
                                                    'prompt' => '',
                                                    'style' => 'width: 100%'
                                                ]) ?>

                                            <?= $form->field($modelPerson, 'phone')->widget(MaskedInput::className(), [
                                                'mask' => ['999-999-9999', '9999-999-9999', '9999-9999-9999', '9999-99999-9999'],
                                                'options' => [
                                                    'class' => 'form-control',
                                                    'placeholder' => \Yii::t('app', 'Phone'),
                                                ],
                                            ]) ?>

                                            <?= $form->field($modelUser, 'email', [
                                                'enableAjaxValidation' => true
                                            ])->textInput([
                                                'class' => 'form-control',
                                                'placeholder' => 'Email',
                                            ]) ?>

                                            <?= $form->field($modelUser, 'username', [
                                                'enableAjaxValidation' => true
                                            ])->textInput([
                                                'class' => 'form-control',
                                                'placeholder' => 'Username',
                                                'readonly' => 'readonly',
                                            ]) ?>

                                            <?= Html::submitButton('Update', ['class' => 'btn btn-raised btn-round btn-danger mb-30']) ?>
                                            &nbsp;&nbsp;
                                            <?= Html::a(\Yii::t('app', 'Cancel'), ['user/index'], ['class' => 'btn btn-raised btn-round btn-default mb-30']) ?>

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
$cssScript = '
    .img-profile {
        position: relative;
        top: 35px;
    }

    .file-input {
        position: absolute;
        bottom:0;
        left:0;
        width:100%;
    }

    .btn-file {
        width:100%;
    }
';

$this->registerCss($cssScript);

Snackbar::widget();

$this->registerJs(Snackbar::messageResponse(), View::POS_HEAD);

if (!empty(($message = \Yii::$app->session->getFlash('message')))) {

    $jscript = 'messageResponse("aicon aicon-icon-tick-in-circle", "' . \Yii::t('app', 'Update Profile Successful') . '", "' . $message['message'] . '", "success");';

    $this->registerJs($jscript);
} ?>