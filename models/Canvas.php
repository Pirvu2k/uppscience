<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use app\models\ProjectAttachment;

/**
 * This is the model class for table "canvases".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $status
 * @property string $date_added
 * @property string $date_modified
 */
class Canvas extends \yii\db\ActiveRecord
{   
    public $files;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'canvases';

    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'language','eng_summary', 'selling','outstanding','benefits','marketed','partners','tech_resources','risk','impact','fin_resources','customers','generate'], 'required', 'on' => 'submit'],
            [['title', 'content','eng_summary','language','status'], 'string', 'on' => 'submit'],
            [['selling','outstanding','benefits','marketed','partners','tech_resources','risk','impact','fin_resources','customers','generate','costs'],'string', 'min' => 1 , 'max' => 3000 , 'tooShort' => 'This field should be at least 5 characters long.', 'tooLong' => 'This field should be at most 80 characters long.', 'on' => 'submit'],
            [['date_added', 'date_modified','created_by','overall_technical','overall_economical','overall_creative', 'partners_score', 'tech_resources_score', 'risk_score', 'impact_score', 'fin_resources_score', 'customers_score', 'generate_score', 'selling_score', 'outstanding_score', 'benefits_score', 'marketed_score'], 'safe'],
            ['eng_summary','string', 'max' => 500, 'on' => 'submit'],
            ['title','string','max'=>50,'min'=>5, 'on' => 'submit'],
            ['content','string','max'=>2999, 'on' => 'submit'],
            [['sector','subsector'],'integer', 'on' => 'submit'],
            [['sector','subsector'],'required', 'on' => 'submit'],
            [['files'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, doc, docx , pdf , ppt, pptx ,xls ,xlsx', 'maxFiles' => 3 ,'maxSize' => 1024 * 1024 * 5 , 'tooBig' => 'Maximum accepted file size is 5MB.', 'on' => 'submit'],
            [['title'], 'required', 'on' => 'draft'],
            [['title', 'content','eng_summary','language','status'], 'string', 'on' => 'draft'],
            [['selling','outstanding','benefits','marketed','partners','tech_resources','risk','impact','fin_resources','customers','generate','costs'],'string', 'min' => 1 , 'max' => 3000 , 'tooShort' => 'This field should be at least 5 characters long.', 'tooLong' => 'This field should be at most 80 characters long.', 'on' => 'draft' ],
            [['date_added', 'date_modified','created_by','overall_technical','overall_economical','overall_creative', 'partners_score', 'tech_resources_score', 'risk_score', 'impact_score', 'fin_resources_score', 'customers_score', 'generate_score', 'selling_score', 'outstanding_score', 'benefits_score', 'marketed_score'], 'safe', 'on' => 'draft'],
            ['eng_summary','string', 'max' => 512, 'on' => 'draft'],
            ['title','string','max'=>50, 'on' => 'draft'],
            ['content','string','max'=>2999, 'on' => 'draft'],
            [['sector','subsector'],'integer', 'on' => 'draft'],
            [['files'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, doc, docx , pdf , ppt, pptx ,xls ,xlsx', 'maxFiles' => 3 ,'maxSize' => 1024 * 1024 * 5 , 'tooBig' => 'Maximum accepted file size is 5MB.', 'on' => 'draft']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Summary',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
            'eng_summary' => 'Summary (in English)',
            'language' => 'Language',
            'files' => 'Files'
        ];
    }

     public function upload()
    {
        if ($this->validate()) { 
            foreach ($this->files as $file) {
                if($file->saveAs('uploads/'. $this->id . $file->baseName . '.' . $file->extension))
                    {
                        $attachment = new ProjectAttachment;
                        $attachment->attachment_name = $this->id . $file->baseName . '.' . $file->extension;
                        $attachment->project = $this->id;
                        $attachment->save();
                    };
            }
            return true;
        } else {
            return false;
        }
    }
    
    public function getOwner(){
        return $this->hasOne(Member::className(), ['id' => 'created_by']);
    }   
}
