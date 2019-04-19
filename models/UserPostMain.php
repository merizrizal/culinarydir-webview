<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "user_post_main".
 *
 * @property string $id
 * @property string $parent_id
 * @property string $unique_id
 * @property string $business_id
 * @property string $user_id
 * @property string $type
 * @property string $text
 * @property string $image
 * @property bool $is_publish
 * @property int $love_value
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property UserPost[] $userPosts
 * @property UserPostComment[] $userPostComments
 * @property UserPostLove[] $userPostLoves
 * @property Business $business
 * @property User $user
 * @property User $userCreated
 * @property User $userUpdated
 * @property UserPostMain $parent
 * @property UserPostMain[] $userPostMains
 * @property UserVote[] $userVotes
 */
class UserPostMain extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_post_main';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unique_id', 'business_id', 'user_id', 'type'], 'required'],
            [['type', 'text', 'image'], 'string'],
            [['is_publish'], 'boolean'],
            [['love_value'], 'default', 'value' => null],
            [['love_value'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['id', 'parent_id', 'business_id', 'user_id', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['unique_id'], 'string', 'max' => 65],
            [['unique_id'], 'unique'],
            [['id'], 'unique'],
            [['image'], 'file', 'maxSize' => 1024 * 1024 * 7],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::className(), 'targetAttribute' => ['business_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['user_created'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_created' => 'id']],
            [['user_updated'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_updated' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserPostMain::className(), 'targetAttribute' => ['parent_id' => 'id']],
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
            'unique_id' => Yii::t('app', 'Unique ID'),
            'business_id' => Yii::t('app', 'Business ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'type' => Yii::t('app', 'Type'),
            'text' => Yii::t('app', 'Text'),
            'image' => Yii::t('app', 'Image'),
            'is_publish' => Yii::t('app', 'Is Publish'),
            'love_value' => Yii::t('app', 'Love Value'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPosts()
    {
        return $this->hasMany(UserPost::className(), ['user_post_main_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPostComments()
    {
        return $this->hasMany(UserPostComment::className(), ['user_post_main_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPostLoves()
    {
        return $this->hasMany(UserPostLove::className(), ['user_post_main_id' => 'id']);
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
        return $this->hasOne(UserPostMain::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPostMains()
    {
        return $this->hasMany(UserPostMain::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserVotes()
    {
        return $this->hasMany(UserVote::className(), ['user_post_main_id' => 'id']);
    }
}
