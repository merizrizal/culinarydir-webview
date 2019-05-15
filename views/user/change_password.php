<?php

use webview\components\Snackbar;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $modelChangePassword frontend\models\ChangePassword */

$this->title = \Yii::t('app', 'Change Password'); ?>

<div class="main bg-main">
    <section>
        <div class="register">
            <div class="row">
                <div class="col-12">
                    <div class="card box">
                        <div class="box-content">
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="font-alt"><?= \Yii::t('app', 'Change Password') ?></h4>
                                    <hr class="divider-w mb-20">

                                    <?php
                                    $form = ActiveForm::begin([
                                        'id' => 'change-password-form',
                                        'action' => ['user/change-password'],
                                        'fieldConfig' => [
                                            'template' => '{input}{error}',
                                        ]
                                    ]); ?>

                                        <div class="row">
                                            <div class="col-12">

                                                <?= $form->field($modelChangePassword, 'current_pass')->passwordInput([
                                                    'placeholder' => 'Current Password'
                                                ]) ?>

                                                <?= $form->field($modelChangePassword, 'new_pass')->passwordInput([
                                                    'placeholder' => 'New Password'
                                                ]) ?>

                                                <?= $form->field($modelChangePassword, 'confirm_pass')->passwordInput([
                                                    'placeholder' => 'Confirm Password'
                                                ]) ?>

                                            </div>
                                        </div>
                                        <div class="row mb-30">
                                            <div class="col-12">

                                                <?= Html::submitButton('Update', ['class' => 'btn btn-raised btn-round btn-danger']) ?>
                                                &nbsp;&nbsp;
                                            	<?= Html::a(\Yii::t('app', 'Cancel'), ['user/index'], ['class' => 'btn btn-raised btn-round btn-default']) ?>

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
Snackbar::widget();

$this->registerJs(Snackbar::messageResponse(), View::POS_HEAD);

$jscript = '';

if (!empty(($message = \Yii::$app->session->getFlash('message')))) {

    $jscript = 'messageResponse("aicon aicon-icon-tick-in-circle", "' . \Yii::t('app', 'Change Password Successful') . '" , "' . $message['message'] . '", "success");';
}

$this->registerJs($jscript); ?>