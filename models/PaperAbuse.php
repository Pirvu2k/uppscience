<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "paper_abuses".
 *
 * @property integer $id
 * @property integer $reported_by
 * @property integer $paper
 * @property string $created_on
 * @property string $note
 * @property string $status
 * @property string $resolution_notes
 * @property integer $resolved_by
 * @property string $resolved_on
 *
 * @property Administrators $resolvedBy
 * @property Member $reportedBy
 * @property Paper $paper0
 */
class PaperAbuse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'paper_abuses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reported_by', 'paper', 'created_on', 'note'], 'required'],
            [['reported_by', 'paper', 'resolved_by'], 'integer'],
            [['created_on', 'resolved_on'], 'safe'],
            [['note', 'status', 'resolution_notes'], 'string'],
            //[['resolved_by'], 'exist', 'skipOnError' => true, 'targetClass' => Administrators::className(), 'targetAttribute' => ['resolved_by' => 'ID']],
            [['reported_by'], 'exist', 'skipOnError' => true, 'targetClass' => Member::className(), 'targetAttribute' => ['reported_by' => 'id']],
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
            'reported_by' => 'Reported By',
            'paper' => 'Paper',
            'created_on' => 'Created On',
            'note' => 'Note',
            'status' => 'Status',
            'resolution_notes' => 'Resolution Notes',
            'resolved_by' => 'Resolved By',
            'resolved_on' => 'Resolved On',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResolvedBy()
    {
        return $this->hasOne(Administrators::className(), ['ID' => 'resolved_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReportedBy()
    {
        return $this->hasOne(Member::className(), ['id' => 'reported_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaper0()
    {
        return $this->hasOne(Paper::className(), ['id' => 'paper']);
    }
}
