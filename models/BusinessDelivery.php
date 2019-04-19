<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "business_delivery".
 *
 * @property string $id
 * @property string $business_id
 * @property string $delivery_method_id
 * @property bool $is_active
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 * @property string $note
 * @property string $description
 *
 * @property Business $business
 * @property DeliveryMethod $deliveryMethod
 * @property User $userCreated
 * @property User $userUpdated
 * @property TransactionSessionOrder[] $transactionSessionOrders
 */
class BusinessDelivery extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'business_delivery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business_id', 'delivery_method_id'], 'required'],
            [['is_active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['note', 'description'], 'string'],
            [['id', 'business_id', 'delivery_method_id', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['id'], 'unique'], 
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::className(), 'targetAttribute' => ['business_id' => 'id']],
            [['delivery_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => DeliveryMethod::className(), 'targetAttribute' => ['delivery_method_id' => 'id']],
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
            'business_id' => Yii::t('app', 'Business ID'),
            'delivery_method_id' => Yii::t('app', 'Delivery Method ID'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
            'note' => Yii::t('app', 'Note'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusiness()
    {
        return $this->hasOne(Business::className(), ['id' => 'business_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryMethod()
    {
        return $this->hasOne(DeliveryMethod::className(), ['id' => 'delivery_method_id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionSessionOrders()
    {
        return $this->hasMany(TransactionSessionOrder::className(), ['business_delivery_id' => 'id']);
    }
}
