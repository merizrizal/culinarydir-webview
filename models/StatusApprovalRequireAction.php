<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "status_approval_require_action".
 *
 * @property string $id
 * @property string $status_approval_id
 * @property string $status_approval_action_id
 * @property string $created_at
 * @property string $user_created
 * @property string $updated_at
 * @property string $user_updated
 *
 * @property StatusApproval $statusApproval
 * @property StatusApprovalAction $statusApprovalAction
 * @property User $userCreated
 * @property User $userUpdated
 */
class StatusApprovalRequireAction extends \sybase\SybaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'status_approval_require_action';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_approval_id', 'status_approval_action_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['id', 'status_approval_action_id', 'user_created', 'user_updated'], 'string', 'max' => 32],
            [['status_approval_id'], 'string', 'max' => 7],
            [['id'], 'unique'],
            [['status_approval_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusApproval::className(), 'targetAttribute' => ['status_approval_id' => 'id']],
            [['status_approval_action_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusApprovalAction::className(), 'targetAttribute' => ['status_approval_action_id' => 'id']],
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
            'status_approval_id' => Yii::t('app', 'Status Approval ID'),
            'status_approval_action_id' => Yii::t('app', 'Status Approval Action ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_created' => Yii::t('app', 'User Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_updated' => Yii::t('app', 'User Updated'),
        ];
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
    public function getStatusApprovalAction()
    {
        return $this->hasOne(StatusApprovalAction::className(), ['id' => 'status_approval_action_id']);
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
