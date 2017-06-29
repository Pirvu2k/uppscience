<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "paper".
 *
 * @property integer $id
 * @property string $title
 * @property string $title_in_english
 * @property integer $discipline
 * @property string $language
 * @property string $abstract
 * @property string $abstract_in_english
 * @property string $keywords
 * @property string $introduction
 * @property string $content
 * @property string $conclusion
 * @property string $references
 * @property integer $created_by
 * @property integer $last_modified_by
 * @property string $created_on
 * @property string $last_modified_on
 * @property string $submission_date
 * @property integer $trash
 * @property string $status
 * @property double $rating
 *
 * @property Languages $language0
 * @property Member $createdBy
 * @property Member $lastModifiedBy
 * @property Sector $discipline0
 */
class Paper extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'paper';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ['keywords', 'validateKeywords'],

            [['title', 'title_in_english', 'discipline', 'language', 'abstract', 'abstract_in_english', 'keywords', 'introduction', 'content', 'conclusion', 'references'], 'required', 'on' => 'submit'],
            [['title'] , 'required', 'on' => 'draft'],
            [['discipline', 'created_by', 'last_modified_by'], 'integer'],
            [['status'], 'string', 'on' => 'submit'],
            [['language'],'integer', 'on' => 'submit'],
            [['introduction', 'content', 'conclusion', 'references', 'status'], 'string'],
            [['created_on', 'last_modified_on', 'submission_date'], 'safe'],
            [['rating'], 'number'],
            [['title', 'title_in_english', 'keywords'], 'string', 'max' => 255],
            [['introduction', 'conclusion'], 'string', 'max' => 2000],
            [['content'], 'string', 'max' => 10000],
            [['references'], 'string', 'max' => 5000],
            [['abstract', 'abstract_in_english'], 'string', 'max' => 2000],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Member::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['last_modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => Member::className(), 'targetAttribute' => ['last_modified_by' => 'id']],
            [['discipline'], 'exist', 'skipOnError' => true, 'targetClass' => Sector::className(), 'targetAttribute' => ['discipline' => 'id']],
            [['language'],'exist','skipOnError' => true, 'targetClass' => Languages::className(), 'targetAttribute' => ['language' => 'id']],
        ];
    }

    public function validateKeywords($attribute, $params, $validator)
    {
        $value = $this->keywords;

        $value = trim($value);
        $value = preg_replace('/\s+/', ' ', $value);

        $words=explode(" ", $value);

        if((count($words) < 3) || (count($words) > 5))
             $this->addError($attribute, 'You may select between 3 and 5 keywords.');

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'title_in_english' => 'Title In English',
            'discipline' => 'Discipline',
            'language' => 'Language',
            'abstract' => 'Abstract',
            'abstract_in_english' => 'Abstract In English',
            'keywords' => 'Keywords',
            'introduction' => 'Introduction',
            'content' => 'Content',
            'conclusion' => 'Conclusion',
            'references' => 'References',
            'created_by' => 'Created By',
            'last_modified_by' => 'Last Modified By',
            'created_on' => 'Created On',
            'last_modified_on' => 'Last Modified On',
            'submission_date' => 'Submission Date',
            'trash' => 'Trash',
            'status' => 'Status',
            'rating' => 'Rating',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage0()
    {
        return $this->hasOne(Languages::className(), ['id' => 'Language']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Member::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastModifiedBy()
    {
        return $this->hasOne(Member::className(), ['id' => 'last_modified_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscipline0()
    {
        return $this->hasOne(Sector::className(), ['id' => 'Discipline']);
    }
    
    public function getOwner(){
        return $this->hasOne(Member::className(), ['id' => 'created_by']);
    }   
}
