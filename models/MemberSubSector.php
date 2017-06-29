<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "member_sub_sector".
 *
 * @property integer $id
 * @property string $created_on
 * @property string $last_modified_on
 * @property string $trash
 * @property integer $subsector
 * @property integer $member
 *
 * @property Member $member0
 * @property SubSector $subsector0
 */
class MemberSubSector extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member_sub_sector';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_on', 'last_modified_on'], 'safe'],
            [['trash'], 'string'],
            [['subsector', 'member'], 'required'],
            [['subsector', 'member'], 'integer'],
            [['member'], 'exist', 'skipOnError' => true, 'targetClass' => Member::className(), 'targetAttribute' => ['member' => 'id']],
            [['subsector'], 'exist', 'skipOnError' => true, 'targetClass' => SubSector::className(), 'targetAttribute' => ['subsector' => 'id']],
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
            'subsector' => 'Subsector',
            'member' => 'Member',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'member']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubsector()
    {
        return $this->hasOne(SubSector::className(), ['id' => 'subsector']);
    }
}
