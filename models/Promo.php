<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "promo".
 *
 * @property string $id
 * @property string $title
 * @property string $type
 * @property int $amount
 * @property int $item_amount
 * @property string $date_start
 * @property string $date_end
 * @property bool $not_active
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 * @property string $image
 * @property string $description
 * @property int $minimum_amount_order
 *
 * @property User $userCreated
 * @property User $userUpdated
 * @property PromoItem[] $promoItems
 * @property UserPromoItem[] $userPromoItems
 */
class Promo extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'type', 'amount', 'item_amount'], 'required'],
            [['type', 'image', 'description'], 'string'],
            [['amount', 'item_amount', 'minimum_amount_order'], 'default', 'value' => null],
            [['amount', 'item_amount', 'minimum_amount_order'], 'integer'],
            [['date_start', 'date_end', 'created_at', 'updated_at'], 'safe'],
            [['not_active'], 'boolean'],
            [['id', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['title'], 'string', 'max' => 64],
            [['id'], 'unique'],
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
            'title' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'amount' => Yii::t('app', 'Amount'),
            'item_amount' => Yii::t('app', 'Item Amount'),
            'date_start' => Yii::t('app', 'Date Start'),
            'date_end' => Yii::t('app', 'Date End'),
            'not_active' => Yii::t('app', 'Not Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
            'image' => Yii::t('app', 'Image'),
            'description' => Yii::t('app', 'Description'),
            'minimum_amount_order' => Yii::t('app', 'Minimum Amount Order'),
        ];
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
    public function getPromoItems()
    {
        return $this->hasMany(PromoItem::className(), ['promo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPromoItems()
    {
        return $this->hasMany(UserPromoItem::className(), ['promo_id' => 'id']);
    }
}
