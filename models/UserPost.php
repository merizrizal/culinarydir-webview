<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "user_post".
 *
 * @property string $id
 * @property string $parent_id
 * @property string $business_id
 * @property string $type
 * @property string $user_id
 * @property string $text
 * @property bool $is_publish
 * @property string $image
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 * @property int $love_value
 * @property string $user_post_main_id
 *
 * @property Business $business
 * @property User $user
 * @property User $userCreated
 * @property User $userUpdated
 * @property UserPost $parent
 * @property UserPost[] $userPosts
 * @property UserPostMain $userPostMain
 */
class UserPost extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business_id', 'type', 'user_id'], 'required'],
            [['type', 'text', 'image'], 'string'],
            [['is_publish'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['love_value'], 'default', 'value' => null],
            [['love_value'], 'integer'],
            [['id', 'parent_id', 'business_id', 'user_id', 'user_created', 'user_updated', 'user_post_main_id'], 'string', 'max' => 32],
            [['id'], 'unique'],
            [['image'], 'file', 'maxSize' => 1024 * 1024 * 7],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::className(), 'targetAttribute' => ['business_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['user_created'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_created' => 'id']],
            [['user_updated'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_updated' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserPost::className(), 'targetAttribute' => ['parent_id' => 'id']],
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
            'parent_id' => Yii::t('app', 'Parent ID'),
            'business_id' => Yii::t('app', 'Business ID'),
            'type' => Yii::t('app', 'Type'),
            'user_id' => Yii::t('app', 'User ID'),
            'text' => Yii::t('app', 'Text'),
            'is_publish' => Yii::t('app', 'Is Publish'),
            'image' => Yii::t('app', 'Image'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
            'love_value' => Yii::t('app', 'Love Value'),
            'user_post_main_id' => Yii::t('app', 'User Post Main ID'),
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
    public function getParent()
    {
        return $this->hasOne(UserPost::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPosts()
    {
        return $this->hasMany(UserPost::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPostMain()
    {
        return $this->hasOne(UserPostMain::className(), ['id' => 'user_post_main_id']);
    }
}
