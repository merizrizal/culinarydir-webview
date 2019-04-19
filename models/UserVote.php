<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "user_vote".
 *
 * @property string $id
 * @property int $vote_value
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 * @property string $rating_component_id
 * @property string $user_post_main_id
 *
 * @property RatingComponent $ratingComponent
 * @property User $userCreated
 * @property User $userUpdated
 * @property UserPostMain $userPostMain
 */
class UserVote extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_vote';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vote_value', 'rating_component_id', 'user_post_main_id'], 'required'],
            [['vote_value'], 'default', 'value' => null],
            [['vote_value'], 'integer'],
            [['created_at', 'updated_at', 'rating_component_id'], 'safe'],
            [['id', 'user_created', 'user_updated', 'user_post_main_id'], 'string', 'max' => 32],
            [['id'], 'unique'],
            [['rating_component_id'], 'exist', 'skipOnError' => true, 'targetClass' => RatingComponent::className(), 'targetAttribute' => ['rating_component_id' => 'id']],
            [['user_created'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_created' => 'id']],
            [['user_updated'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_updated' => 'id']],
            [['user_post_main_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserPostMain::className(), 'targetAttribute' => ['user_post_main_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'vote_value' => Yii::t('app', 'Vote Value'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
            'rating_component_id' => Yii::t('app', 'Rating Component ID'),
            'user_post_main_id' => Yii::t('app', 'User Post Main ID'),
        ];
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPostMain()
    {
        return $this->hasOne(UserPostMain::className(), ['id' => 'user_post_main_id']);
    }
}
