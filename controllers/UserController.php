<?php
namespace webview\controllers;

use core\models\TransactionSession;
use core\models\User;
use core\models\UserPerson;
use frontend\models\ChangePassword;
use sycomponent\Tools;
use webview\controllers\base\BaseController;
use yii\base\InvalidArgumentException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * User Controller
 */
class UserController extends BaseController
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
                    ],
                ],
            ]);
    }

    public function actionIndex()
    {
        $modelUser = User::find()
            ->joinWith(['userPerson.person'])
            ->andWhere(['user.id' => \Yii::$app->user->getIdentity()->id])
            ->asArray()->one();

        return $this->render('index', [
            'modelUser' => $modelUser
        ]);
    }

    public function actionUserProfile($user)
    {
        if (!empty(\Yii::$app->user->getIdentity()->id) && \Yii::$app->user->getIdentity()->username == $user) {

            return $this->redirect(ArrayHelper::merge(['user/index'], \Yii::$app->request->getQueryParams()));
        } else {

            $modelUser = User::find()
                ->joinWith(['userPerson.person'])
                ->andWhere(['user.username' => $user])
                ->asArray()->one();

            if (empty($modelUser)) {

                throw new NotFoundHttpException('The requested page does not exist.');
            }

            return $this->render('user_profile', [
                'modelUser' => $modelUser
            ]);
        }
    }

    public function actionUpdateProfile()
    {
        $modelUserPerson = UserPerson::find()
            ->joinWith([
                'user',
                'person',
            ])
            ->andWhere(['user_person.user_id' => \Yii::$app->user->getIdentity()->id])
            ->one();

        $modelUser = $modelUserPerson->user;
        $modelPerson = $modelUserPerson->person;

        if (\Yii::$app->request->isAjax && $modelUser->load(\Yii::$app->request->post())) {

            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($modelUser);
        }

        if (!empty(($post = \Yii::$app->request->post()))) {

            if ($modelPerson->load($post) && $modelUser->load($post)) {

                $transaction = \Yii::$app->db->beginTransaction();
                $flag = false;

                $modelPerson->email = $post['User']['email'];
                $modelPerson->phone = !empty($post['Person']['phone']) ? $post['Person']['phone'] : null;
                $modelPerson->city_id = !empty($post['Person']['city_id']) ? $post['Person']['city_id'] : null;

                if (($flag = $modelPerson->save())) {

                    if (!($modelUser->image = Tools::uploadFile('/img/user/', $modelUser, 'image', 'username', $modelUser->username))) {

                        $modelUser->image = $modelUser->oldAttributes['image'];
                    }

                    $modelUser->full_name = $modelPerson->first_name . ' ' . $modelPerson->last_name;

                    $flag = $modelUser->save();
                }

                if ($flag) {

                    $transaction->commit();

                    \Yii::$app->session->setFlash('message', [
                        'type' => 'success',
                        'delay' => 1000,
                        'icon' => 'aicon aicon-icon-tick-in-circle',
                        'message' => 'Anda berhasil mengubah profile Anda di Asikmakan',
                        'title' => 'Berhasil Update Profile',
                    ]);

                    return $this->redirect(['user/update-profile']);
                } else {

                    $transaction->rollBack();

                    \Yii::$app->session->setFlash('message', [
                        'type' => 'danger',
                        'delay' => 1000,
                        'icon' => 'aicon aicon-icon-info',
                        'message' => 'Gagal mengubah profile Anda di Asikmakan',
                        'title' => 'Gagal Update Profile',
                    ]);
                }
            }
        }

        return $this->render('update_profile', [
            'modelUserPerson' => $modelUserPerson,
            'modelUser' => $modelUser,
            'modelPerson' => $modelPerson,
        ]);
    }

    public function actionChangePassword()
    {
        try {
            $modelChangePassword = new ChangePassword(\Yii::$app->user->getIdentity()->id);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($modelChangePassword->load(\Yii::$app->request->post()) && $modelChangePassword->validate() && $modelChangePassword->changePassword()) {

            \Yii::$app->session->setFlash('message', [
                'message' => 'Anda berhasil mengubah password baru di Asikmakan',
            ]);
        }

        return $this->render('change_password', [
            'modelChangePassword' => $modelChangePassword,
        ]);
    }

    public function actionDetailOrderHistory($id)
    {
        $modelTransactionSession = TransactionSession::find()
            ->joinWith([
                'business',
                'business.businessImages' => function ($query) {

                    $query->andOnCondition(['business_image.is_primary' => true]);
                },
                'business.businessLocation.city',
                'transactionItems' => function ($query) {

                    $query->orderBy(['transaction_item.created_at' => SORT_ASC]);
                },
                'transactionItems.businessProduct',
                'transactionSessionDelivery'
            ])
            ->andWhere(['transaction_session.id' => $id])
            ->asArray()->one();

        if (empty($modelTransactionSession)) {

            throw new NotFoundHttpException('The requested page does not exist.');
        }

        \Yii::$app->formatter->timeZone = 'Asia/Jakarta';

        return $this->render('detail_order_history', [
            'modelTransactionSession' => $modelTransactionSession,
        ]);
    }
}
