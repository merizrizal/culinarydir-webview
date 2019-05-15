<?php

/* @var $this yii\web\View */
/* @var $id string */ ?>

<div class="row">
    <div class="col-12">
        <ul class="view-journey nav nav-tabs mb-10" role="tablist">
            <li class="nav-item">
                <a id="review-tab" class="nav-link active" href="#view-review" aria-controls="view-review" role="tab" data-toggle="tab" aria-selected="true"><i class="aicon aicon-document-edit"></i> <?= \Yii::t('app', 'Review') ?> (<span class="total-user-post"></span>)</a>
            </li>
            <li class="nav-item">
                <a id="love-tab" class="nav-link" href="#view-love" aria-controls="view-love" role="tab" data-toggle="tab" aria-selected="false"><i class="aicon aicon-heart1"></i> Love (<span class="total-user-love"></span>)</a>
            </li>
            <li class="nav-item">
                <a id="visit-tab" class="nav-link" href="#view-been-there" aria-controls="view-been-there" role="tab" data-toggle="tab" aria-selected="false"><i class="aicon aicon-icon-been-there"></i> Visit (<span class="total-user-visit"></span>)</a>
            </li>
        </ul>

        <div class="tab-content p-15">
            <div role="tabpanel" class="tab-pane fade show active p-0" id="view-review" aria-labelledby="review-tab">

                <?= $this->render('journey/_review', [
                    'id' => $id
                ]) ?>

            </div>

            <div role="tabpanel" class="tab-pane fade p-0" id="view-love" aria-labelledby="love-tab">

                <?= $this->render('journey/_love', [
                    'id' => $id
                ]) ?>

            </div>

            <div role="tabpanel" class="tab-pane fade p-0" id="view-been-there" aria-labelledby="visit-tab">

                <?= $this->render('journey/_been_there', [
                    'id' => $id
                ]) ?>

            </div>
        </div>
    </div>
</div>