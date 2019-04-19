<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "delivery_method".
 *
 * @property string $id
 * @property string $delivery_name
 * @property bool $not_active
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 * @property string $note 
 * @property string $description 
 *
 * @property BusinessDelivery[] $businessDeliveries
 * @property User $userCreated
 * @property User $userUpdated
 * @property RegistryBusinessDelivery[] $registryBusinessDeliveries
 */
class DeliveryMethod extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery_method';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['delivery_name'], 'required'],
            [['not_active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['note', 'description'], 'string'], 
            [['id', 'delivery_name', 'user_created', 'user_updated'], 'string', 'max' => 32],
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
            'delivery_name' => Yii::t('app', 'Delivery Name'),
            'not_active' => Yii::t('app', 'Not Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
            'note' => Yii::t('app', 'Note'),
            'description' => Yii::t('app', 'Description'), 
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessDeliveries()
    {
        return $this->hasMany(BusinessDelivery::className(), ['delivery_method_id' => 'id']);
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
    public function getRegistryBusinessDeliveries()
    {
        return $this->hasMany(RegistryBusinessDelivery::className(), ['delivery_method_id' => 'id']);
    }
}
