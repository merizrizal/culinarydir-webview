<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "registry_business_hour".
 *
 * @property string $id
 * @property string $unique_id
 * @property string $registry_business_id
 * @property string $day
 * @property bool $is_open
 * @property string $open_at
 * @property string $close_at
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property RegistryBusiness $registryBusiness
 * @property User $userCreated
 * @property User $userUpdated
 * @property RegistryBusinessHourAdditional[] $registryBusinessHourAdditionals
 */
class RegistryBusinessHour extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'registry_business_hour';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unique_id', 'registry_business_id', 'day'], 'required'],
            [['day'], 'string'],
            [['is_open'], 'boolean'],
            [['open_at', 'close_at', 'created_at', 'updated_at'], 'safe'],
            [['id', 'registry_business_id', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['unique_id'], 'string', 'max' => 34],
            [['unique_id'], 'unique'],
            [['id'], 'unique'],
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
            'id' => Yii::t('app', 'ID'),
            'unique_id' => Yii::t('app', 'Unique ID'),
            'registry_business_id' => Yii::t('app', 'Registry Business ID'),
            'day' => Yii::t('app', 'Day'),
            'is_open' => Yii::t('app', 'Is Open'),
            'open_at' => Yii::t('app', 'Open At'),
            'close_at' => Yii::t('app', 'Close At'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
        ];
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistryBusinessHourAdditionals()
    {
        return $this->hasMany(RegistryBusinessHourAdditional::className(), ['registry_business_hour_id' => 'id']);
    }
}
