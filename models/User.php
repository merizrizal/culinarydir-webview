<?php

namespace core\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $user_level_id
 * @property string $email
 * @property string $username
 * @property string $full_name
 * @property string $password
 * @property string $image
 * @property bool $not_active
 * @property string $created_at
 * @property string $updated_at
 * @property string $password_reset_token
 * @property string $account_activation_token
 * @property string $login_token
 *
 * @property ApplicationBusiness[] $applicationBusinesses
 * @property Business[] $businesses
 * @property PromoItem[] $promoItems
 * @property RegistryBusiness[] $registryBusinesses
 * @property TransactionSession[] $transactionSessions
 * @property UserLevel $userLevel
 * @property UserLove[] $userLoves
 * @property UserPerson $userPerson
 * @property UserPost[] $userPosts
 * @property UserPostComment[] $userPostComments
 * @property UserPostLove[] $userPostLoves
 * @property UserPromoItem[] $userPromoItems
 * @property UserReport[] $userReports
 * @property UserSocialMedia $userSocialMedia
 * @property UserVisit[] $userVisits
 */
class User extends \sybase\SybaseModel implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function($event) {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_level_id', 'email', 'username', 'full_name', 'password'], 'required'],
            [['image'], 'string'],
            [['not_active'], 'boolean'],
            [['created_at', 'updated_at', 'user_level_id'], 'safe'],
            [['id', 'full_name'], 'string', 'max' => 32],
            [['email', 'username', 'password'], 'string', 'max' => 64],
            [['account_activation_token', 'login_token'], 'string', 'max' => 75],
            [['password_reset_token'], 'string', 'max' => 80],
            [['email'], 'unique'],
            [['username'], 'unique'],
            [['id'], 'unique'],
            [['email'], 'email'],
            [['username'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => 'Username hanya boleh angka, huruf, garis bawah dan strip.'],
            [['image'], 'file', 'maxSize' => 1024*1024*2],
            [['user_level_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserLevel::className(), 'targetAttribute' => ['user_level_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_level_id' => Yii::t('app', 'User Level ID'),
            'email' => Yii::t('app', 'Email'),
            'username' => Yii::t('app', 'Username'),
            'full_name' => Yii::t('app', 'Full Name'),
            'password' => Yii::t('app', 'Password'),
            'image' => Yii::t('app', 'Image'),
            'not_active' => Yii::t('app', 'Not Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'account_activation_token' => Yii::t('app', 'Account Activation Token'),
            'login_token' => Yii::t('app', 'Login Token'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationBusinesses()
    {
        return $this->hasMany(ApplicationBusiness::className(), ['user_in_charge' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinesses()
    {
        return $this->hasMany(Business::className(), ['user_in_charge' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromoItems()
    {
        return $this->hasMany(PromoItem::className(), ['user_claimed' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistryBusinesses()
    {
        return $this->hasMany(RegistryBusiness::className(), ['user_in_charge' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionSessions()
    {
        return $this->hasMany(TransactionSession::className(), ['user_ordered' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserLevel()
    {
        return $this->hasOne(UserLevel::className(), ['id' => 'user_level_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserLoves()
    {
        return $this->hasMany(UserLove::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPerson()
    {
        return $this->hasOne(UserPerson::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPosts()
    {
        return $this->hasMany(UserPost::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPostComments()
    {
        return $this->hasMany(UserPostComment::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPostLoves()
    {
        return $this->hasMany(UserPostLove::className(), ['user_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPromoItems()
    {
        return $this->hasMany(UserPromoItem::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserReports()
    {
        return $this->hasMany(UserReport::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSocialMedia()
    {
        return $this->hasOne(UserSocialMedia::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserVisits()
    {
        return $this->hasMany(UserVisit::className(), ['user_id' => 'id']);
    }

    /////////////////////////////////
    ////IdentityInterface Section////
    /////////////////////////////////

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['login_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'not_active' => false,
        ]);
    }
    
    public static function findByEmailAndPasswordResetToken($email, $token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            
            return null;
        }
        
        $tokenParts = explode('_', $token);
        
        return static::find()
            ->andWhere(['email' => $email])
            ->andWhere(['ilike', 'password_reset_token', $tokenParts[0] . '_'])
            ->andWhere(['not_active' => false])
            ->one();
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            
            return false;
        }
        
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $timeToken = explode('_', $token);
        $timestamp = (int) end($timeToken);
        
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        //return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        //return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = substr(str_shuffle("0123456789"), 0, 6) . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
