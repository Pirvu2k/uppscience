<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "member_language".
 *
 * @property integer $id
 * @property integer $language
 * @property integer $member
 */
class MemberLanguage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member_language';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member'], 'integer'],
            [['language'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'language' => 'Language',
            'member' => 'Member',
        ];
    }
}
