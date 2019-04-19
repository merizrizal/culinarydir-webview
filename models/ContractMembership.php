<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "contract_membership".
 *
 * @property string $registry_business_id
 * @property string $business_id
 * @property string $membership_type_id
 * @property int $price
 * @property string $started_at
 * @property string $due_at
 * @property bool $is_current
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property Business $business
 * @property MembershipType $membershipType
 * @property RegistryBusiness $registryBusiness
 * @property User $userCreated
 * @property User $userUpdated
 */
class ContractMembership extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contract_membership';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['registry_business_id', 'business_id', 'membership_type_id'], 'required'],
            [['price'], 'default', 'value' => null],
            [['price'], 'integer'],
            [['started_at', 'due_at', 'created_at', 'updated_at'], 'safe'],
            [['is_current'], 'boolean'],
            [['registry_business_id', 'business_id', 'membership_type_id', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['registry_business_id'], 'unique'],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::className(), 'targetAttribute' => ['business_id' => 'id']],
            [['membership_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MembershipType::className(), 'targetAttribute' => ['membership_type_id' => 'id']],
            [['registry_business_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistryBusiness::className(), 'targetAttribute' => ['registry_business_id' => 'id']],
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
            'registry_business_id' => Yii::t('app', 'Registry Business ID'),
            'business_id' => Yii::t('app', 'Business ID'),
            'membership_type_id' => Yii::t('app', 'Membership Type ID'),
            'price' => Yii::t('app', 'Price'),
            'started_at' => Yii::t('app', 'Started At'),
            'due_at' => Yii::t('app', 'Due At'),
            'is_current' => Yii::t('app', 'Is Current'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
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
    public function getMembershipType()
    {
        return $this->hasOne(MembershipType::className(), ['id' => 'membership_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistryBusiness()
    {
        return $this->hasOne(RegistryBusiness::className(), ['id' => 'registry_business_id']);
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
