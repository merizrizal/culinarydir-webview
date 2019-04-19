<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "rating_component".
 *
 * @property string $id
 * @property string $name
 * @property bool $is_active
 * @property int $order
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property BusinessDetailVote[] $businessDetailVotes
 * @property User $userCreated
 * @property User $userUpdated
 * @property UserVote[] $userVotes
 */
class RatingComponent extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rating_component';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_active'], 'boolean'],
            [['order'], 'default', 'value' => null],
            [['order'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['id', 'name', 'user_created', 'user_updated'], 'string', 'max' => 32],
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
            'name' => Yii::t('app', 'Name'),
            'is_active' => Yii::t('app', 'Is Active'),
            'order' => Yii::t('app', 'Order'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessDetailVotes()
    {
        return $this->hasMany(BusinessDetailVote::className(), ['rating_component_id' => 'id']);
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
    public function getUserVotes()
    {
        return $this->hasMany(UserVote::className(), ['rating_component_id' => 'id']);
    }
}
