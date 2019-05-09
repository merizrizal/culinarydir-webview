<?php

use common\components\Helper;
use yii\helpers\Html;

/* @var $modelUserPostComment core\models\UserPostComment */
/* @var $userPostId frontend\controllers\DataController */

if (!empty($modelUserPostComment)): ?>

    <div class="comment-container">

        <?php
        echo Html::hiddenInput('commentCount', (!empty($modelUserPostComment) ? count($modelUserPostComment) : '0'), ['class' => 'comment-count']);

        foreach ($modelUserPostComment as $dataUserPostComment): ?>

            <div class="comment-post">
                <div class="row mb-10">
                    <div class="col-12">
                        <div class="widget-comments-image">

                            <?php
                            $img = !empty($dataUserPostComment['user']['image']) ? $dataUserPostComment['user']['image'] . '&w=200&h=200' : 'default-avatar.png';

                            echo Html::a(Html::img(\Yii::$app->params['endPointLoadImage'] . 'user?image=' . $img, [
                                'class' => 'img-fluid rounded-circle'
                            ]), ['user/user-profile', 'user' => $dataUserPostComment['user']['username']]); ?>

                        </div>

                        <div class="widget-comments-body">
                            <?= Html::a($dataUserPostComment['user']['full_name'], ['user/user-profile', 'user' => $dataUserPostComment['user']['username']]); ?>&nbsp;&nbsp;&nbsp;
                            <small><?= Helper::asRelativeTime($dataUserPostComment['created_at']) ?></small>
                            <br>
                            <p class="comment-description">
                                <?= $dataUserPostComment['text']; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        <?php
        endforeach; ?>

    </div>

<?php
endif; ?>