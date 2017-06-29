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
class CanvasDraft extends Canvas
{   
    public $files;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'content','eng_summary','language','status'], 'string'],
            [['selling','outstanding','benefits','marketed','partners','tech_resources','risk','impact','fin_resources','customers','generate','costs'],'string', 'min' => 1 , 'max' => 3000 , 'tooShort' => 'This field should be at least 5 characters long.', 'tooLong' => 'This field should be at most 80 characters long.' ],
            [['date_added', 'date_modified','created_by','overall_technical','overall_economical','overall_creative', 'partners_score', 'tech_resources_score', 'risk_score', 'impact_score', 'fin_resources_score', 'customers_score', 'generate_score', 'selling_score', 'outstanding_score', 'benefits_score', 'marketed_score'], 'safe'],
            ['eng_summary','string', 'max' => 512,'min'=>10 ],
            ['title','string','max'=>50,'min'=>5],
            ['content','string','max'=>2999],
            [['sector','subsector'],'integer'],
            [['files'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, doc, docx , pdf , ppt, pptx ,xls ,xlsx', 'maxFiles' => 3 ,'maxSize' => 1024 * 1024 * 5 , 'tooBig' => 'Maximum accepted file size is 5MB.']
        ];
    }
}
