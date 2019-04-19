<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "transaction_session_order".
 *
 * @property string $transaction_session_id
 * @property string $business_payment_id
 * @property string $business_delivery_id
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property BusinessDelivery $businessDelivery
 * @property BusinessPayment $businessPayment
 * @property TransactionSession $transactionSession
 * @property User $userCreated
 * @property User $userUpdated
 */
class TransactionSessionOrder extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaction_session_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transaction_session_id', 'business_payment_id', 'business_delivery_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['transaction_session_id', 'business_payment_id', 'business_delivery_id', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['transaction_session_id'], 'unique'],
            [['business_delivery_id'], 'exist', 'skipOnError' => true, 'targetClass' => BusinessDelivery::className(), 'targetAttribute' => ['business_delivery_id' => 'id']],
            [['business_payment_id'], 'exist', 'skipOnError' => true, 'targetClass' => BusinessPayment::className(), 'targetAttribute' => ['business_payment_id' => 'id']],
            [['transaction_session_id'], 'exist', 'skipOnError' => true, 'targetClass' => TransactionSession::className(), 'targetAttribute' => ['transaction_session_id' => 'id']],
            [['user_created'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_created' => 'id']],
            [['user_updated'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_updated' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transaction_session_id' => Yii::t('app', 'Transaction Session ID'),
            'business_payment_id' => Yii::t('app', 'Payment Methods'),
            'business_delivery_id' => Yii::t('app', 'Delivery Methods'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessDelivery()
    {
        return $this->hasOne(BusinessDelivery::className(), ['id' => 'business_delivery_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessPayment()
    {
        return $this->hasOne(BusinessPayment::className(), ['id' => 'business_payment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionSession()
    {
        return $this->hasOne(TransactionSession::className(), ['id' => 'transaction_session_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserCreated()
    {
        return $this->hasOne(User::className(), ['id' => 'user_created']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserUpdated()
    {
        return $this->hasOne(User::className(), ['id' => 'user_updated']);
    }
}
