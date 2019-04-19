<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "business_location".
 *
 * @property string $business_id
 * @property string $address_type
 * @property string $address
 * @property string $address_info
 * @property string $city_id
 * @property string $district_id
 * @property string $village_id
 * @property string $coordinate
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property Business $business
 * @property City $city
 * @property District $district
 * @property User $userCreated
 * @property User $userUpdated
 * @property Village $village
 */
class BusinessLocation extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'business_location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business_id', 'address_type', 'address', 'city_id', 'district_id', 'village_id', 'coordinate'], 'required'],
            [['address_type', 'address', 'address_info'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['business_id', 'city_id', 'district_id', 'village_id', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['coordinate'], 'string', 'max' => 64],
            [['business_id'], 'unique'],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::className(), 'targetAttribute' => ['business_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['district_id'], 'exist', 'skipOnError' => true, 'targetClass' => District::className(), 'targetAttribute' => ['district_id' => 'id']],
            [['user_created'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_created' => 'id']],
            [['user_updated'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_updated' => 'id']],
            [['village_id'], 'exist', 'skipOnError' => true, 'targetClass' => Village::className(), 'targetAttribute' => ['village_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'business_id' => Yii::t('app', 'Business ID'),
            'address_type' => Yii::t('app', 'Address Type'),
            'address' => Yii::t('app', 'Address'),
            'address_info' => Yii::t('app', 'Address Info'),
            'city_id' => Yii::t('app', 'City ID'),
            'district_id' => Yii::t('app', 'District ID'),
            'village_id' => Yii::t('app', 'Village ID'),
            'coordinate' => Yii::t('app', 'Coordinate'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
        ];
    }

    public function setCoordinate() {

        if (!empty($this->coordinate)) {

            $coordinate = explode(',', $this->coordinate);
            $this->coordinate = trim($coordinate[0]) . ',' . trim($coordinate[1]);
        }
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
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(District::className(), ['id' => 'district_id']);
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
    public function getVillage()
    {
        return $this->hasOne(Village::className(), ['id' => 'village_id']);
    }
}
