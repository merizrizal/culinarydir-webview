<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "business".
 *
 * @property string $id
 * @property string $parent_id
 * @property string $name
 * @property string $unique_name
 * @property string $about
 * @property string $email
 * @property string $phone1
 * @property string $phone2
 * @property string $phone3
 * @property string $user_in_charge
 * @property bool $is_active
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 * @property string $membership_type_id
 * @property string $application_business_id
 * @property string $note
 *
 * @property ApplicationBusiness $applicationBusiness
 * @property Business $parent
 * @property Business[] $businesses
 * @property MembershipType $membershipType
 * @property User $userInCharge
 * @property User $userCreated
 * @property User $userUpdated
 * @property BusinessCategory[] $businessCategories
 * @property BusinessContactPerson[] $businessContactPeople
 * @property BusinessDelivery[] $businessDeliveries
 * @property BusinessDetail $businessDetail
 * @property BusinessDetailVote[] $businessDetailVotes
 * @property BusinessFacility[] $businessFacilities
 * @property BusinessHour[] $businessHours
 * @property BusinessImage[] $businessImages
 * @property BusinessLocation $businessLocation
 * @property BusinessPayment[] $businessPayments
 * @property BusinessProduct[] $businessProducts
 * @property BusinessProductCategory[] $businessProductCategories
 * @property BusinessPromo[] $businessPromos
 * @property ContractMembership[] $contractMemberships
 * @property PromoItem[] $promoItems 
 * @property TransactionSession[] $transactionSessions
 * @property UserLove[] $userLoves
 * @property UserPost[] $userPosts
 * @property UserPostMain[] $userPostMains
 * @property UserReport[] $userReports
 * @property UserVisit[] $userVisits
 */
class Business extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'business';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'unique_name', 'membership_type_id', 'application_business_id'], 'required'],
            [['about', 'note'], 'string'],
            [['is_active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['id', 'parent_id', 'user_in_charge', 'user_created', 'user_updated', 'membership_type_id', 'application_business_id'], 'string', 'max' => 32], 
            [['name', 'email'], 'string', 'max' => 48],
            [['unique_name'], 'string', 'max' => 64],
            [['phone1', 'phone2', 'phone3'], 'string', 'max' => 16],
            [['application_business_id'], 'unique'],
            [['unique_name'], 'unique'],
            [['unique_name'], 'match', 'pattern' => '/^[a-z0-9-]+$/', 'message' => Yii::t('app', 'Unique Name') . ' hanya boleh angka, huruf kecil dan strip.'],
            [['email'], 'email'],
            [['id'], 'unique'],
            [['application_business_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationBusiness::className(), 'targetAttribute' => ['application_business_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['membership_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MembershipType::className(), 'targetAttribute' => ['membership_type_id' => 'id']],
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
            'parent_id' => Yii::t('app', 'Parent ID'),
            'name' => Yii::t('app', 'Name'),
            'unique_name' => Yii::t('app', 'Unique Name'),
            'about' => Yii::t('app', 'About'),
            'email' => Yii::t('app', 'Email'),
            'phone1' => Yii::t('app', 'Phone1'),
            'phone2' => Yii::t('app', 'Phone2'),
            'phone3' => Yii::t('app', 'Phone3'),
            'user_in_charge' => Yii::t('app', 'User In Charge'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
            'membership_type_id' => Yii::t('app', 'Membership Type ID'),
            'application_business_id' => Yii::t('app', 'Application Business ID'),
            'note' => Yii::t('app', 'Note'),
            'membershipType.name' => Yii::t('app', 'Membership Type'),
        ];
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
    public function getParent()
    {
        return $this->hasOne(Business::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinesses()
    {
        return $this->hasMany(Business::className(), ['parent_id' => 'id']);
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
    public function getBusinessCategories()
    {
        return $this->hasMany(BusinessCategory::className(), ['business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessContactPeople()
    {
        return $this->hasMany(BusinessContactPerson::className(), ['business_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessDeliveries()
    {
        return $this->hasMany(BusinessDelivery::className(), ['business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessDetail()
    {
        return $this->hasOne(BusinessDetail::className(), ['business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessDetailVotes()
    {
        return $this->hasMany(BusinessDetailVote::className(), ['business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessFacilities()
    {
        return $this->hasMany(BusinessFacility::className(), ['business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessHours()
    {
        return $this->hasMany(BusinessHour::className(), ['business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessImages()
    {
        return $this->hasMany(BusinessImage::className(), ['business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessLocation()
    {
        return $this->hasOne(BusinessLocation::className(), ['business_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessPayments()
    {
        return $this->hasMany(BusinessPayment::className(), ['business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessProducts()
    {
        return $this->hasMany(BusinessProduct::className(), ['business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessProductCategories()
    {
        return $this->hasMany(BusinessProductCategory::className(), ['business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessPromos()
    {
        return $this->hasMany(BusinessPromo::className(), ['business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContractMemberships()
    {
        return $this->hasMany(ContractMembership::className(), ['business_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromoItems()
    {
        return $this->hasMany(PromoItem::className(), ['business_claimed' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionSessions()
    {
        return $this->hasMany(TransactionSession::className(), ['business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserLoves()
    {
        return $this->hasMany(UserLove::className(), ['business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPosts()
    {
        return $this->hasMany(UserPost::className(), ['business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPostMains()
    {
        return $this->hasMany(UserPostMain::className(), ['business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserReports()
    {
        return $this->hasMany(UserReport::className(), ['business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserVisits()
    {
        return $this->hasMany(UserVisit::className(), ['business_id' => 'id']);
    }
}
