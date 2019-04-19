<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "membership_type".
 *
 * @property string $id
 * @property string $name
 * @property bool $is_premium
 * @property int $time_limit
 * @property int $price
 * @property string $note
 * @property bool $is_active
 * @property int $order
 * @property bool $as_archive
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property Business[] $businesses
 * @property ContractMembership[] $contractMemberships
 * @property User $userCreated
 * @property User $userUpdated
 * @property MembershipTypeProductService[] $membershipTypeProductServices
 * @property RegistryBusiness[] $registryBusinesses
 */
class MembershipType extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'membership_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_premium', 'is_active', 'as_archive'], 'boolean'],
            [['time_limit', 'price', 'order'], 'default', 'value' => null],
            [['time_limit', 'price', 'order'], 'integer'],
            [['note'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['id', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 48],
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
            'is_premium' => Yii::t('app', 'Is Premium'),
            'time_limit' => Yii::t('app', 'Time Limit'),
            'price' => Yii::t('app', 'Price'),
            'note' => Yii::t('app', 'Note'),
            'is_active' => Yii::t('app', 'Is Active'),
            'order' => Yii::t('app', 'Order'),
            'as_archive' => Yii::t('app', 'As Archive'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinesses()
    {
        return $this->hasMany(Business::className(), ['membership_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContractMemberships()
    {
        return $this->hasMany(ContractMembership::className(), ['membership_type_id' => 'id']);
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
    public function getMembershipTypeProductServices()
    {
        return $this->hasMany(MembershipTypeProductService::className(), ['membership_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistryBusinesses()
    {
        return $this->hasMany(RegistryBusiness::className(), ['membership_type_id' => 'id']);
    }
}
