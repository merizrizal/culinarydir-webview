<?php

namespace webview\controllers;

use core\models\BusinessDetail;
use core\models\BusinessDetailVote;
use core\models\TransactionItem;
use core\models\TransactionSession;
use core\models\UserPostLove;
use core\models\UserPostMain;
use core\models\UserVote;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * User Action Controller
 */
class UserActionController extends base\BaseController
{

    /**
     *
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
                        'delete-photo' => ['POST'],
                        'delete-user-post' => ['POST'],
                        'reorder' => ['POST']
                    ],
                ],
            ]);
    }

    public function actionDeletePhoto($id)
    {
        $modelUserPostMain = UserPostMain::find()
            ->andWhere(['id' => $id])
            ->one();

        $result = [];

        if (!empty($modelUserPostMain)) {

            $modelUserPostMain->is_publish = false;

            if ($modelUserPostMain->save()) {

                $result['success'] = true;
                $result['icon'] = 'aicon aicon-icon-tick-in-circle';
                $result['title'] = 'Sukses.';
                $result['message'] = 'Foto berhasil di hapus.';
                $result['type'] = 'success';
                $result['id'] = $modelUserPostMain->id;
            } else {

                $result['success'] = false;
                $result['icon'] = 'aicon aicon-icon-info';
                $result['title'] = 'Gagal';
                $result['message'] = 'Foto gagal di hapus.';
                $result['type'] = 'danger';
            }
        } else {

            $result['success'] = false;
            $result['icon'] = 'aicon aicon-icon-info';
            $result['title'] = 'Gagal.';
            $result['message'] = 'Foto tidak ditemukan.';
            $result['type'] = 'danger';
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }

    public function actionDeleteUserPost($id)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        $flag = false;

        $modelUserPostMain = UserPostMain::find()
            ->andWhere(['id' => $id])
            ->one();

        $modelUserPostMain->is_publish = false;
        $modelUserPostMain->love_value = 0;

        $flag = $modelUserPostMain->save();

        if ($flag) {

            $modelUserPostMainPhotos = UserPostMain::find()
                ->andWhere(['parent_id' => $id])
                ->all();

            foreach ($modelUserPostMainPhotos as $modelUserPostMainPhoto) {

                $modelUserPostMainPhoto->is_publish = false;

                if (!($flag = $modelUserPostMainPhoto->save())) {

                    break;
                }
            }
        }

        if ($flag) {

            $modelUserPostLoves = UserPostLove::find()
                ->andWhere(['user_post_main_id' => $id])
                ->all();

            foreach ($modelUserPostLoves as $modelUserPostLove) {

                $modelUserPostLove->is_active = false;

                if (!($flag = $modelUserPostLove->save())) {

                    break;
                }
            }
        }

        $userVoteTotal = 0;
        $userVote = [];

        if ($flag) {

            $modelUserVotes = UserVote::find()
                ->andWhere(['user_post_main_id' => $id])
                ->all();

            foreach ($modelUserVotes as $modelUserVote) {

                $userVoteTotal += $modelUserVote->vote_value;
                $userVote[$modelUserVote->rating_component_id] = $modelUserVote->vote_value;

                $modelUserVote->vote_value = 0;

                if (!($flag = $modelUserVote->save())) {

                    break;
                }
            }
        }

        if ($flag) {

            $modelBusinessDetail = BusinessDetail::find()
                ->andWhere(['business_id' => $modelUserPostMain->business_id])
                ->one();

            $modelBusinessDetail->total_vote_points -= $userVoteTotal;
            $modelBusinessDetail->voters -= 1;
            $modelBusinessDetail->vote_points = $modelBusinessDetail->total_vote_points / count($modelUserVotes);
            $modelBusinessDetail->vote_value = !empty($modelBusinessDetail->voters) ? $modelBusinessDetail->vote_points / $modelBusinessDetail->voters : 0;

            $flag = $modelBusinessDetail->save();
        }

        if ($flag) {

            foreach ($userVote as $ratingComponentId => $votePoint) {

                $modelBusinessDetailVote = BusinessDetailVote::find()
                    ->andWhere(['business_id' => $modelUserPostMain->business_id])
                    ->andWhere(['rating_component_id' => $ratingComponentId])
                    ->one();

                $modelBusinessDetailVote->total_vote_points -= $votePoint;
                $modelBusinessDetailVote->vote_value = !empty($modelBusinessDetail->voters) ? $modelBusinessDetailVote->total_vote_points / $modelBusinessDetail->voters : 0;

                if (!($flag = $modelBusinessDetailVote->save())) {

                    break;
                }
            }
        }

        $result = [];

        if ($flag) {

            $transaction->commit();

            $result['success'] = true;
            $result['publish'] = $modelUserPostMain->is_publish;
            $result['icon'] = 'aicon aicon-icon-tick-in-circle';
            $result['title'] = 'Sukses.';
            $result['message'] = 'Review berhasil dihapus.';
            $result['type'] = 'success';
        } else {

            $transaction->rollBack();

            $result['success'] = false;
            $result['icon'] = 'aicon aicon-icon-info';
            $result['title'] = 'Gagal';
            $result['message'] = 'Review gagal dihapus.';
            $result['type'] = 'danger';
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }

    public function actionReorder()
    {
        $result = [];

        $modelTransactionSession = TransactionSession::find()
            ->andWhere(['user_ordered' => \Yii::$app->user->getIdentity()->id])
            ->andWhere(['status' => 'Open'])
            ->asArray()->one();

        if (!empty($modelTransactionSession)) {

            if ($modelTransactionSession['id'] == \Yii::$app->request->post('id')) {

                $result['redirect'] = true;
                $result['business_id'] = $modelTransactionSession['business_id'];
            } else {

                $result['success'] = false;
                $result['type'] = 'danger';
                $result['icon'] = 'aicon aicon-icon-info';
                $result['title'] = 'Pesan Ulang Gagal';
                $result['text'] = 'Silahkan selesaikan pesanan anda terlebih dahulu.';
            }
        } else {

            $transaction = \Yii::$app->db->beginTransaction();

            $flag = false;

            $oldModelTransaction = TransactionSession::find()
                ->joinWith(['transactionItems'])
                ->andWhere(['transaction_session.id' => \Yii::$app->request->post('id')])
                ->one();

            $modelTransactionSession = new TransactionSession();
            $modelTransactionSession->user_ordered = $oldModelTransaction->user_ordered;
            $modelTransactionSession->business_id = $oldModelTransaction->business_id;
            $modelTransactionSession->note = !empty($oldModelTransaction->note) ? $oldModelTransaction->note : null;
            $modelTransactionSession->total_price = $oldModelTransaction->total_price;
            $modelTransactionSession->total_amount = $oldModelTransaction->total_amount;
            $modelTransactionSession->status = 'Open';

            if (($flag = $modelTransactionSession->save())) {

                foreach ($oldModelTransaction->transactionItems as $dataTransactionItem) {

                    $modelTransactionItem = new TransactionItem();
                    $modelTransactionItem->transaction_session_id = $modelTransactionSession->id;
                    $modelTransactionItem->business_product_id = $dataTransactionItem->business_product_id;
                    $modelTransactionItem->note = !empty($dataTransactionItem->note) ? $dataTransactionItem->note : null;
                    $modelTransactionItem->price = $dataTransactionItem->price;
                    $modelTransactionItem->amount = $dataTransactionItem->amount;

                    if (!($flag = $modelTransactionItem->save())) {

                        break;
                    }
                }
            }

            if ($flag) {

                $transaction->commit();

                $result['success'] = true;
                $result['redirect'] = true;
                $result['business_id'] = $modelTransactionSession['business_id'];
            } else {

                $transaction->rollBack();

                $result['success'] = false;
                $result['type'] = 'danger';
                $result['icon'] = 'aicon aicon-icon-info';
                $result['title'] = 'Pesan Ulang Gagal';
                $result['text'] = 'Silahkan ulangi kembali proses pemesanan anda.';
            }
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }
}