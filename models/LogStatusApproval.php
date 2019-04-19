<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "log_status_approval".
 *
 * @property string $id
 * @property string $application_business_id
 * @property string $status_approval_id
 * @property bool $is_actual
 * @property string $note
 * @property int $application_business_counter
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property ApplicationBusiness $applicationBusiness
 * @property StatusApproval $statusApproval
 * @property User $userCreated
 * @property User $userUpdated
 * @property LogStatusApprovalAction[] $logStatusApprovalActions
 */
class LogStatusApproval extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_status_approval';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['application_business_id', 'status_approval_id', 'is_actual'], 'required'],
            [['is_actual'], 'boolean'],
            [['note'], 'string'],
            [['application_business_counter'], 'default', 'value' => null],
            [['application_business_counter'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['id', 'application_business_id', 'status_approval_id', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['id'], 'unique'],
            [['application_business_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationBusiness::className(), 'targetAttribute' => ['application_business_id' => 'id']],
            [['status_approval_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusApproval::className(), 'targetAttribute' => ['status_approval_id' => 'id']],
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
            'application_business_id' => Yii::t('app', 'Application Business ID'),
            'status_approval_id' => Yii::t('app', 'Status Approval ID'),
            'is_actual' => Yii::t('app', 'Is Actual'),
            'note' => Yii::t('app', 'Note'),
            'application_business_counter' => Yii::t('app', 'Application Business Counter'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
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
    public function getStatusApproval()
    {
        return $this->hasOne(StatusApproval::className(), ['id' => 'status_approval_id']);
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
    public function getLogStatusApprovalActions()
    {
        return $this->hasMany(LogStatusApprovalAction::className(), ['log_status_approval_id' => 'id']);
    }
}
