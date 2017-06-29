<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "member_specialization".
 *
 * @property integer $id
 * @property string $created_on
 * @property string $last_modified_on
 * @property string $trash
 * @property integer $specialization
 * @property integer $member
 *
 * @property Member $member0
 * @property Specialization $specialization0
 */
class MemberSpecialization extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member_specialization';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_on', 'last_modified_on'], 'safe'],
            [['trash'], 'string'],
            [['specialization', 'member'], 'required'],
            [['specialization', 'member'], 'integer'],
            [['member'], 'exist', 'skipOnError' => true, 'targetClass' => Member::className(), 'targetAttribute' => ['member' => 'id']],
            [['specialization'], 'exist', 'skipOnError' => true, 'targetClass' => Specialization::className(), 'targetAttribute' => ['specialization' => 'id']],
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
            'specialization' => 'Specialization',
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
    public function getSpecialization0()
    {
        return $this->hasOne(Specialization::className(), ['id' => 'specialization']);
    }
}
