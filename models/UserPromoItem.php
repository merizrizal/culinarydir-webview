<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "user_promo_item".
 *
 * @property string $promo_item_id
 * @property string $unique_id
 * @property string $promo_id
 * @property string $user_id
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property Promo $promo
 * @property PromoItem $promoItem
 * @property User $user
 * @property User $userCreated
 * @property User $userUpdated
 */
class UserPromoItem extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_promo_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['promo_item_id', 'unique_id', 'promo_id', 'user_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['promo_item_id'], 'string', 'max' => 14],
            [['unique_id'], 'string', 'max' => 65],
            [['promo_id', 'user_id', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['unique_id'], 'unique'],
            [['promo_item_id'], 'unique'],
            [['promo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Promo::className(), 'targetAttribute' => ['promo_id' => 'id']],
            [['promo_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => PromoItem::className(), 'targetAttribute' => ['promo_item_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'promo_item_id' => Yii::t('app', 'Promo Item ID'),
            'unique_id' => Yii::t('app', 'Unique ID'),
            'promo_id' => Yii::t('app', 'Promo ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromo()
    {
        return $this->hasOne(Promo::className(), ['id' => 'promo_id']);
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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
