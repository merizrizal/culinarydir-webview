<?php
namespace webview\controllers;

use core\models\BusinessDetail;
use core\models\BusinessDetailVote;
use core\models\RatingComponent;
use core\models\UserPostComment;
use core\models\UserPostMain;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;

/**
 * Data Controller
 */
class DataController extends base\BaseController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(
            $this->getAccess(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'product-category' => ['POST'],
                        'post-comment' => ['POST'],
                        'business-rating' => ['POST'],
                    ]
                ]
            ]);
    }

    public function actionPostReview($city, $uniqueName)
    {
        if (!\Yii::$app->request->isAjax) {

            $queryParams = \Yii::$app->request->getQueryParams();

            $this->redirect(['page/detail',
                'city' => $city,
                'uniqueName' => $uniqueName,
                'redirect' => 'review',
                'page' => !empty($queryParams['page']) ? $queryParams['page'] : 1,
                'per-page' => !empty($queryParams['per-page']) ? $queryParams['per-page'] : '',
            ]);
        } else {

            $this->layout = 'ajax';
        }

        $modelUserPostMain = UserPostMain::find()
            ->joinWith([
                'business',
                'business.businessLocation',
                'business.businessLocation.city',
                'user',
                'userPostMains child' => function ($query) {

                    $query->andOnCondition(['child.is_publish' => true]);
                },
                'userVotes',
                'userVotes.ratingComponent rating_component' => function ($query) {

                    $query->andOnCondition(['rating_component.is_active' => true]);
                },
                'userPostLoves' => function ($query) {

                    $query->andOnCondition([
                        'user_post_love.user_id' => !empty(\Yii::$app->user->getIdentity()->id) ? \Yii::$app->user->getIdentity()->id : null,
                        'user_post_love.is_active' => true
                    ]);
                },
                'userPostComments',
                'userPostComments.user user_comment',
            ])
            ->andWhere(['user_post_main.parent_id' => null])
            ->andWhere(['user_post_main.type' => 'Review'])
            ->andWhere(['user_post_main.is_publish' => true])
            ->andWhere(['business.unique_name' => $uniqueName])
            ->andWhere(['lower(city.name)' => str_replace('-', ' ', $city)])
            ->andFilterWhere(['<>', 'user_post_main.user_id', !empty(\Yii::$app->user->getIdentity()->id) ? \Yii::$app->user->getIdentity()->id : null])
            ->orderBy(['user_post_main.created_at' => SORT_DESC])
            ->distinct()
            ->asArray();

        $provider = new ActiveDataProvider([
            'query' => $modelUserPostMain,
        ]);

        $modelUserPostMain = $provider->getModels();
        $pagination = $provider->getPagination();

        $pageSize = $pagination->pageSize;
        $totalCount = $pagination->totalCount;
        $offset = $pagination->offset;

        $startItem = !empty($modelUserPostMain) ? $offset + 1 : 0;
        $endItem = min(($offset + $pageSize), $totalCount);

        \Yii::$app->formatter->timeZone = 'Asia/Jakarta';

        return $this->render('post_review', [
            'modelUserPostMain' => $modelUserPostMain,
            'pagination' => $pagination,
            'startItem' => $startItem,
            'endItem' => $endItem,
            'totalCount' => $totalCount,
        ]);
    }

    public function actionPostPhoto($city, $uniqueName)
    {
        if (!\Yii::$app->request->isAjax) {

            $queryParams = \Yii::$app->request->getQueryParams();

            $this->redirect(['page/detail',
                'city' => $city,
                'uniqueName' => $uniqueName,
                'redirect' => 'photo',
                'page' => !empty($queryParams['page']) ? $queryParams['page'] : 1,
                'per-page' => !empty($queryParams['per-page']) ? $queryParams['per-page'] : '',
            ]);
        } else {

            $this->layout = 'ajax';
        }

        $modelUserPostMain = UserPostMain::find()
            ->joinWith([
                'business',
                'business.businessLocation',
                'business.businessLocation.city',
            ])
            ->andWhere(['user_post_main.type' => 'Photo'])
            ->andWhere(['user_post_main.is_publish' => true])
            ->andWhere(['business.unique_name' => $uniqueName])
            ->andWhere(['lower(city.name)' => str_replace('-', ' ', $city)])
            ->orderBy(['user_post_main.created_at' => SORT_DESC])
            ->distinct()
            ->asArray();

        $provider = new ActiveDataProvider([
            'query' => $modelUserPostMain,
        ]);

        $modelUserPostMain = $provider->getModels();
        $pagination = $provider->getPagination();

        $pageSize = $pagination->pageSize;
        $totalCount = $pagination->totalCount;
        $offset = $pagination->offset;

        $startItem = !empty($modelUserPostMain) ? $offset + 1 : 0;
        $endItem = min(($offset + $pageSize), $totalCount);

        \Yii::$app->formatter->timeZone = 'Asia/Jakarta';

        return $this->render('post_photo', [
            'modelUserPostMain' => $modelUserPostMain,
            'pagination' => $pagination,
            'startItem' => $startItem,
            'endItem' => $endItem,
            'totalCount' => $totalCount,
        ]);
    }

    public function actionPostComment()
    {
        $this->layout = 'ajax';

        $modelUserPostComment = UserPostComment::find()
            ->joinWith([
                'user',
                'userPostMain',
            ])
            ->andWhere(['user_post_comment.user_post_main_id' => \Yii::$app->request->post('user_post_main_id')])
            ->orderBy(['user_post_comment.created_at' => SORT_ASC])
            ->distinct()
            ->asArray()->all();

        \Yii::$app->formatter->timeZone = 'Asia/Jakarta';

        return $this->render('post_comment', [
            'userPostId' => \Yii::$app->request->post('user_post_main_id'),
            'modelUserPostComment' => $modelUserPostComment,
        ]);
    }

    public function actionBusinessRating()
    {
        $this->layout = 'ajax';

        $modelBusinessDetail = BusinessDetail::find()
            ->andWhere(['business_detail.business_id' => \Yii::$app->request->post('business_id')])
            ->asArray()->one();

        $modelBusinessDetailVote = BusinessDetailVote::find()
            ->joinWith([
                'ratingComponent' => function ($query) {

                    $query->andOnCondition(['is_active' => true]);
                }
            ])
            ->andWhere(['business_detail_vote.business_id' => \Yii::$app->request->post('business_id')])
            ->asArray()->all();

        $modelRatingComponent = RatingComponent::find()
            ->where(['is_active' => true])
            ->orderBy(['order' => SORT_ASC])
            ->asArray()->all();

        return $this->render('business_rating', [
            'modelBusinessDetail' => $modelBusinessDetail,
            'modelBusinessDetailVote' => $modelBusinessDetailVote,
            'modelRatingComponent' => $modelRatingComponent,
        ]);
    }
}