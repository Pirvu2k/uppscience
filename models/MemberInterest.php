<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "member_interest".
 *
 * @property integer $id
 * @property string $created_on
 * @property string $last_modified_on
 * @property string $trash
 * @property integer $interest
 * @property integer $member
 *
 * @property Member $member0
 * @property Interest $interest0
 */
class MemberInterest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member_interest';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_on', 'last_modified_on'], 'safe'],
            [['trash'], 'string'],
            [['interest', 'member'], 'required'],
            [['interest', 'member'], 'integer'],
            [['member'], 'exist', 'skipOnError' => true, 'targetClass' => Member::className(), 'targetAttribute' => ['member' => 'id']],
            [['interest'], 'exist', 'skipOnError' => true, 'targetClass' => Interest::className(), 'targetAttribute' => ['interest' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_on' => 'Created On',
            'last_modified_on' => 'Last Modified On',
            'trash' => 'Trash',
            'interest' => 'Interest',
            'member' => 'Member',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember0()
    {
        return $this->hasOne(Member::className(), ['id' => 'member']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInterest0()
    {
        return $this->hasOne(Interest::className(), ['id' => 'interest']);
    }
}
