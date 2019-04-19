<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "application_business".
 *
 * @property string $id
 * @property string $user_in_charge
 * @property int $counter
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property User $userInCharge
 * @property User $userCreated
 * @property User $userUpdated
 * @property Business $business
 * @property LogStatusApproval[] $logStatusApprovals
 * @property RegistryBusiness[] $registryBusinesses
 */
class ApplicationBusiness extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'application_business';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_in_charge'], 'required'],
            [['counter'], 'default', 'value' => null],
            [['counter'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['id', 'user_in_charge', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['id'], 'unique'], 
            [['user_in_charge'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_in_charge' => 'id']],
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
            'user_in_charge' => Yii::t('app', 'User In Charge'),
            'counter' => Yii::t('app', 'Counter'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),

            'registryBusinesses.membershipType.name' => Yii::t('app', 'Membership Type'),
            'registryBusinesses.userInCharge.full_name' => Yii::t('app', 'Marketing'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserInCharge()
    {
        return $this->hasOne(User::className(), ['id' => 'user_in_charge']);
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
    public function getBusiness()
    {
        return $this->hasOne(Business::className(), ['application_business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogStatusApprovals()
    {
        return $this->hasMany(LogStatusApproval::className(), ['application_business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistryBusinesses()
    {
        return $this->hasMany(RegistryBusiness::className(), ['application_business_id' => 'id']);
    }
}
