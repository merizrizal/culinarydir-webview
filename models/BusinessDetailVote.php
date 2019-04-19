<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "business_detail_vote".
 *
 * @property string $id
 * @property string $business_id
 * @property string $rating_component_id
 * @property double $vote_value
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 * @property int $total_vote_points
 *
 * @property Business $business
 * @property RatingComponent $ratingComponent
 * @property User $userCreated
 * @property User $userUpdated
 */
class BusinessDetailVote extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'business_detail_vote';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business_id', 'rating_component_id'], 'required'],
            [['vote_value'], 'number'],
            [['created_at', 'updated_at', 'rating_component_id'], 'safe'],
            [['total_vote_points'], 'default', 'value' => null],
            [['total_vote_points'], 'integer'],
            [['id', 'business_id', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['id'], 'unique'],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::className(), 'targetAttribute' => ['business_id' => 'id']],
            [['rating_component_id'], 'exist', 'skipOnError' => true, 'targetClass' => RatingComponent::className(), 'targetAttribute' => ['rating_component_id' => 'id']],
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
            'rating_component_id' => Yii::t('app', 'Rating Component ID'),
            'vote_value' => Yii::t('app', 'Vote Value'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
            'total_vote_points' => Yii::t('app', 'Total Vote Points'),
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
    public function getRatingComponent()
    {
        return $this->hasOne(RatingComponent::className(), ['id' => 'rating_component_id']);
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
