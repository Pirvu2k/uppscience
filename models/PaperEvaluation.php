<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "paper_evaluation".
 *
 * @property integer $id
 * @property integer $member
 * @property integer $paper
 * @property string $status
 * @property double $rating
 * @property string $created_on
 * @property string $last_modified_on
 * @property integer $trash
 * @property string $acceptance_date
 * @property string $rating_date
 *
 * @property Member $member0
 * @property Paper $paper0
 */
class PaperEvaluation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'paper_evaluation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member', 'paper'], 'required'],
            [['member', 'paper', 'trash'], 'integer'],
            [['status'], 'string'],
            [['rating'], 'number'],
            [['created_on', 'last_modified_on', 'acceptance_date', 'rating_date', 'expiry_date'], 'safe'],
            [['member'], 'exist', 'skipOnError' => true, 'targetClass' => Member::className(), 'targetAttribute' => ['member' => 'id']],
            [['paper'], 'exist', 'skipOnError' => true, 'targetClass' => Paper::className(), 'targetAttribute' => ['paper' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member' => 'Member',
            'paper' => 'Paper',
            'status' => 'Status',
            'rating' => 'Rating',
            'created_on' => 'Created On',
            'last_modified_on' => 'Last Modified On',
            'trash' => 'Trash',
            'acceptance_date' => 'Acceptance Date',
            'rating_date' => 'Rating Date',
            'expiry_date' => 'Expiry Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvaluator()
    {
        return $this->hasOne(Member::className(), ['id' => 'member']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaperinfo()
    {
        return $this->hasOne(Paper::className(), ['id' => 'paper']);
    }
}
