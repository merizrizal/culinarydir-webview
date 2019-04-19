<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "user_post_love".
 *
 * @property string $id
 * @property string $user_post_main_id
 * @property string $user_id
 * @property bool $is_active
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 * @property string $unique_id
 *
 * @property User $user
 * @property User $userCreated
 * @property User $userUpdated
 * @property UserPostMain $userPostMain
 */
class UserPostLove extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_post_love';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_post_main_id', 'user_id', 'unique_id'], 'required'],
            [['is_active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['id', 'user_post_main_id', 'user_id', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['unique_id'], 'string', 'max' => 65],
            [['unique_id'], 'unique'],
            [['id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'user_post_main_id' => Yii::t('app', 'User Post Main ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
            'unique_id' => Yii::t('app', 'Unique ID'),
        ];
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPostMain()
    {
        return $this->hasOne(UserPostMain::className(), ['id' => 'user_post_main_id']);
    }
}
