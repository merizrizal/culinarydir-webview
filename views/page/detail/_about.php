<?php

/* @var $businessName string */
/* @var $businessAbout string */ ?>

<div class="row">
    <div class="col-12">
        <div class="card box">
            <div class="box-title">
                <div class="row">
                    <div class="col-12">
                        <h6 class="mt-0 mb-0 inline-block"><?= \Yii::t('app', 'About') . ' ' . $businessName ?> </h6>
                    </div>
                </div>
            </div>

            <hr class="divider-w">

            <div class="box-content mt-10">
            	<div class="row">
            		<div class="col-12">
                        <?= !empty($businessAbout) ? $businessAbout : '<p>' . \Yii::t('app', 'Data Not Available') . '.</p>' ?>
            		</div>
            	</div>
            </div>
        </div>
    </div>
</div>