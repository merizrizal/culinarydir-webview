<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "registry_business_image".
 *
 * @property string $id
 * @property string $registry_business_id
 * @property string $image
 * @property string $title
 * @property string $caption
 * @property string $type
 * @property bool $is_primary
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 * @property string $category
 * @property int $order
 *
 * @property RegistryBusiness $registryBusiness
 * @property User $userCreated
 * @property User $userUpdated
 */
class RegistryBusinessImage extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'registry_business_image';
    }

    public function scenarios() {

        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = $this->attributes();

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['registry_business_id', 'type'], 'required'],
            [['image', 'caption', 'type', 'category'], 'string'],
            [['is_primary'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['order'], 'default', 'value' => null],
            [['order'], 'integer'],
            [['id', 'registry_business_id', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['title'], 'string', 'max' => 128],
            [['image'], 'required', 'on' => self::SCENARIO_CREATE],
            [['image'], 'file', 'maxSize' => 1024 * 1024 * 7, 'maxFiles' => 10],
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
            'registry_business_id' => Yii::t('app', 'Registry Business ID'),
            'image' => Yii::t('app', 'Image'),
            'title' => Yii::t('app', 'Title'),
            'caption' => Yii::t('app', 'Caption'),
            'type' => Yii::t('app', 'Type'),
            'is_primary' => Yii::t('app', 'Is Primary'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
            'category' => Yii::t('app', 'Category'),
            'order' => Yii::t('app', 'Order'),
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
}
