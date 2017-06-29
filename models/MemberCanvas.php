<?php

namespace app\models;

use Yii;
use app\models\Canvas;
/**
 * This is the model class for table "member_project_canvas_assignation".
 *
 * @property integer $id
 * @property string $created_on
 * @property string $last_modified_on
 * @property integer $member
 * @property integer $project
 * @property integer $role
 * @property string $status
 * @property string $expiry_date
 * @property string $notes
 * @property double $score
 *
 * @property Member $member0
 * @property MemberRoles $role0
 * @property ProjectCanvas $project0
 */
class MemberCanvas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member_project_canvas_assignation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_on', 'last_modified_on', 'expiry_date'], 'safe'],
            [['member', 'project'], 'integer'],
            [['status', 'notes' ,'role'], 'string'],
            [['score'], 'number'],
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
            'member' => 'Member',
            'project' => 'Project',
            'role' => 'Role',
            'status' => 'Status',
            'expiry_date' => 'Expiry Date',
            'notes' => 'Notes',
            'score' => 'Score',
        ];
    }

    public function getCanvas(){
        return $this->hasOne(Canvas::className(), ['id' => 'project']);
    }
    
    public function getEvaluator(){
        return $this->hasOne(Member::className(), ['id' => 'member']);
    }    
}
