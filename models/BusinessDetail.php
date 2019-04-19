<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "business_detail".
 *
 * @property string $business_id
 * @property int $price_min
 * @property int $price_max
 * @property int $voters
 * @property double $vote_value
 * @property double $vote_points
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 * @property int $love_value
 * @property int $visit_value
 * @property int $total_vote_points
 * @property string $note_business_hour
 *
 * @property Business $business
 * @property User $userCreated
 * @property User $userUpdated
 */
class BusinessDetail extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'business_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business_id'], 'required'],
            [['price_min', 'price_max', 'voters', 'love_value', 'visit_value', 'total_vote_points'], 'default', 'value' => null],
            [['price_min', 'price_max', 'voters', 'love_value', 'visit_value', 'total_vote_points'], 'integer'],
            [['vote_value', 'vote_points'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['note_business_hour'], 'string'],
            [['business_id', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['business_id'], 'unique'],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::className(), 'targetAttribute' => ['business_id' => 'id']],
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
            'business_id' => Yii::t('app', 'Business ID'),
            'price_min' => Yii::t('app', 'Price Min'),
            'price_max' => Yii::t('app', 'Price Max'),
            'voters' => Yii::t('app', 'Voters'),
            'vote_value' => Yii::t('app', 'Vote Value'),
            'vote_points' => Yii::t('app', 'Vote Points'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
            'love_value' => Yii::t('app', 'Love Value'),
            'visit_value' => Yii::t('app', 'Visit Value'),
            'total_vote_points' => Yii::t('app', 'Total Vote Points'),
            'note_business_hour' => Yii::t('app', 'Note Business Hour'),
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
