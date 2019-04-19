<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "registry_business".
 *
 * @property string $id
 * @property string $membership_type_id
 * @property string $name
 * @property string $unique_name
 * @property string $email
 * @property string $phone1
 * @property string $phone2
 * @property string $phone3
 * @property string $address_type
 * @property string $address
 * @property string $address_info
 * @property string $city_id
 * @property string $district_id
 * @property string $village_id
 * @property string $coordinate
 * @property string $application_business_id
 * @property string $user_in_charge
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 * @property int $price_min
 * @property int $price_max
 * @property int $application_business_counter
 * @property string $note
 * @property string $note_business_hour
 * @property string $about
 * @property string $menu 
 *
 * @property ContractMembership $contractMembership
 * @property ApplicationBusiness $applicationBusiness
 * @property City $city
 * @property District $district
 * @property MembershipType $membershipType
 * @property User $userInCharge
 * @property User $userCreated
 * @property User $userUpdated
 * @property Village $village
 * @property RegistryBusinessCategory[] $registryBusinessCategories
 * @property RegistryBusinessContactPerson[] $registryBusinessContactPeople
 * @property RegistryBusinessDelivery[] $registryBusinessDeliveries
 * @property RegistryBusinessFacility[] $registryBusinessFacilities
 * @property RegistryBusinessHour[] $registryBusinessHours
 * @property RegistryBusinessImage[] $registryBusinessImages
 * @property RegistryBusinessPayment[] $registryBusinessPayments
 * @property RegistryBusinessProductCategory[] $registryBusinessProductCategories
 */
class RegistryBusiness extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'registry_business';
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
            [['membership_type_id', 'name', 'unique_name', 'address_type', 'address', 'city_id', 'district_id', 'village_id', 'coordinate', 'application_business_id'], 'required'],
            [['address_type', 'address', 'address_info', 'note', 'note_business_hour', 'about', 'menu'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['price_min', 'price_max', 'application_business_counter'], 'default', 'value' => null],
            [['price_min', 'price_max', 'application_business_counter'], 'integer'],
            [['id', 'membership_type_id', 'city_id', 'district_id', 'village_id', 'application_business_id', 'user_in_charge', 'user_created', 'user_updated'], 'string', 'max' => 32], 
            [['name', 'email'], 'string', 'max' => 48],
            [['unique_name', 'coordinate'], 'string', 'max' => 64],
            [['phone1', 'phone2', 'phone3'], 'string', 'max' => 16],
            [['unique_name'], 'unique', 'on' => self::SCENARIO_CREATE],
            [['unique_name'], 'match', 'pattern' => '/^[a-z0-9-]+$/', 'message' => Yii::t('app', 'Unique Name') . ' hanya boleh angka, huruf kecil dan strip.'],
            [['email'], 'email'],
            [['id'], 'unique'],
            [['application_business_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationBusiness::className(), 'targetAttribute' => ['application_business_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['district_id'], 'exist', 'skipOnError' => true, 'targetClass' => District::className(), 'targetAttribute' => ['district_id' => 'id']],
            [['membership_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MembershipType::className(), 'targetAttribute' => ['membership_type_id' => 'id']],
            [['user_in_charge'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_in_charge' => 'id']],
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
            'id' => Yii::t('app', 'ID'),
            'membership_type_id' => Yii::t('app', 'Membership Type ID'),
            'name' => Yii::t('app', 'Name'),
            'unique_name' => Yii::t('app', 'Unique Name'),
            'email' => Yii::t('app', 'Email'),
            'phone1' => Yii::t('app', 'Phone1'),
            'phone2' => Yii::t('app', 'Phone2'),
            'phone3' => Yii::t('app', 'Phone3'),
            'address_type' => Yii::t('app', 'Address Type'),
            'address' => Yii::t('app', 'Address'),
            'address_info' => Yii::t('app', 'Address Info'),
            'city_id' => Yii::t('app', 'City ID'),
            'district_id' => Yii::t('app', 'District ID'),
            'village_id' => Yii::t('app', 'Village ID'),
            'coordinate' => Yii::t('app', 'Coordinate'),
            'application_business_id' => Yii::t('app', 'Application Business ID'),
            'user_in_charge' => Yii::t('app', 'User In Charge'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
            'price_min' => Yii::t('app', 'Price Min'),
            'price_max' => Yii::t('app', 'Price Max'),
            'application_business_counter' => Yii::t('app', 'Application Business Counter'),
            'note' => Yii::t('app', 'Note'),
            'note_business_hour' => Yii::t('app', 'Note Business Hour'),
            'about' => Yii::t('app', 'About'),
            'membershipType.name' => Yii::t('app', 'Membership Type'),
            'userInCharge.full_name' => Yii::t('app', 'Marketing'),
            'menu' => Yii::t('app', 'Menu'),
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
    public function getContractMembership()
    {
        return $this->hasOne(ContractMembership::className(), ['registry_business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationBusiness()
    {
        return $this->hasOne(ApplicationBusiness::className(), ['id' => 'application_business_id']);
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
    public function getMembershipType()
    {
        return $this->hasOne(MembershipType::className(), ['id' => 'membership_type_id']);
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
    public function getVillage()
    {
        return $this->hasOne(Village::className(), ['id' => 'village_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistryBusinessCategories()
    {
        return $this->hasMany(RegistryBusinessCategory::className(), ['registry_business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistryBusinessContactPeople()
    {
        return $this->hasMany(RegistryBusinessContactPerson::className(), ['registry_business_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistryBusinessDeliveries()
    {
        return $this->hasMany(RegistryBusinessDelivery::className(), ['registry_business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistryBusinessFacilities()
    {
        return $this->hasMany(RegistryBusinessFacility::className(), ['registry_business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistryBusinessHours()
    {
        return $this->hasMany(RegistryBusinessHour::className(), ['registry_business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistryBusinessImages()
    {
        return $this->hasMany(RegistryBusinessImage::className(), ['registry_business_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistryBusinessPayments()
    {
        return $this->hasMany(RegistryBusinessPayment::className(), ['registry_business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistryBusinessProductCategories()
    {
        return $this->hasMany(RegistryBusinessProductCategory::className(), ['registry_business_id' => 'id']);
    }
}
