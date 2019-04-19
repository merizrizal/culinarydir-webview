<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "membership_type_product_service".
 *
 * @property string $id
 * @property string $product_service_id
 * @property string $note
 * @property bool $not_active
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 * @property string $membership_type_id
 *
 * @property MembershipType $membershipType
 * @property ProductService $productService
 * @property User $userCreated
 * @property User $userUpdated
 */
class MembershipTypeProductService extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'membership_type_product_service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_service_id', 'membership_type_id'], 'required'],
            [['note'], 'string'],
            [['not_active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['id', 'product_service_id', 'user_created', 'user_updated', 'membership_type_id'], 'string', 'max' => 32],
            [['id'], 'unique'],
            [['membership_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MembershipType::className(), 'targetAttribute' => ['membership_type_id' => 'id']],
            [['product_service_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductService::className(), 'targetAttribute' => ['product_service_id' => 'id']],
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
            'product_service_id' => Yii::t('app', 'Product Service ID'),
            'note' => Yii::t('app', 'Note'),
            'not_active' => Yii::t('app', 'Not Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
            'membership_type_id' => Yii::t('app', 'Membership Type ID'),
        ];
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
    public function getProductService()
    {
        return $this->hasOne(ProductService::className(), ['id' => 'product_service_id']);
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
