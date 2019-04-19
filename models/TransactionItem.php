<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "transaction_item".
 *
 * @property string $id
 * @property string $transaction_session_id
 * @property string $business_product_id
 * @property string $note
 * @property int $price
 * @property int $amount
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property BusinessProduct $businessProduct
 * @property TransactionSession $transactionSession
 * @property User $userCreated
 * @property User $userUpdated
 */
class TransactionItem extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaction_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transaction_session_id', 'business_product_id'], 'required'],
            [['note'], 'string'],
            [['price', 'amount'], 'default', 'value' => null],
            [['price', 'amount'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['id', 'transaction_session_id', 'business_product_id', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['id'], 'unique'],
            [['business_product_id'], 'exist', 'skipOnError' => true, 'targetClass' => BusinessProduct::className(), 'targetAttribute' => ['business_product_id' => 'id']],
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
            'id' => Yii::t('app', 'ID'),
            'transaction_session_id' => Yii::t('app', 'Transaction Session ID'),
            'business_product_id' => Yii::t('app', 'Business Product ID'),
            'note' => Yii::t('app', 'Note'),
            'price' => Yii::t('app', 'Price'),
            'amount' => Yii::t('app', 'Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessProduct()
    {
        return $this->hasOne(BusinessProduct::className(), ['id' => 'business_product_id']);
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
