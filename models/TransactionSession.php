<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "transaction_session".
 *
 * @property string $id
 * @property string $user_ordered
 * @property string $business_id
 * @property string $note
 * @property int $total_price
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 * @property bool $is_closed
 * @property int $total_amount
 * @property string $promo_item_id
 * @property string $discount_type
 * @property int $discount_value
 *
 * @property TransactionItem[] $transactionItems
 * @property Business $business
 * @property PromoItem $promoItem
 * @property User $userOrdered
 * @property User $userCreated
 * @property User $userUpdated
 * @property TransactionSessionOrder $transactionSessionOrder
 */
class TransactionSession extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaction_session';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_ordered', 'business_id'], 'required'],
            [['note', 'discount_type'], 'string'],
            [['total_price', 'total_amount', 'discount_value'], 'default', 'value' => null],
            [['total_price', 'total_amount', 'discount_value'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['is_closed'], 'boolean'],
            [['id', 'user_ordered', 'business_id', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['promo_item_id'], 'string', 'max' => 14],
            [['id'], 'unique'],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::className(), 'targetAttribute' => ['business_id' => 'id']],
            [['promo_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => PromoItem::className(), 'targetAttribute' => ['promo_item_id' => 'id']],
            [['user_ordered'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_ordered' => 'id']],
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
            'user_ordered' => Yii::t('app', 'User Ordered'),
            'business_id' => Yii::t('app', 'Business ID'),
            'note' => Yii::t('app', 'Note'),
            'total_price' => Yii::t('app', 'Total Price'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
            'is_closed' => Yii::t('app', 'Is Closed'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'promo_item_id' => Yii::t('app', 'Promo Item ID'),
            'discount_type' => Yii::t('app', 'Discount Type'),
            'discount_value' => Yii::t('app', 'Discount Value'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionItems()
    {
        return $this->hasMany(TransactionItem::className(), ['transaction_session_id' => 'id']);
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
    public function getPromoItem()
    {
        return $this->hasOne(PromoItem::className(), ['id' => 'promo_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserOrdered()
    {
        return $this->hasOne(User::className(), ['id' => 'user_ordered']);
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
    public function getTransactionSessionOrder()
    {
        return $this->hasOne(TransactionSessionOrder::className(), ['transaction_session_id' => 'id']);
    }
}
