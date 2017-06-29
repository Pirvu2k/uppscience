<?php



namespace app\models;



use Yii;



/**

 * This is the model class for table "member_experience".

 *

 * @property integer $id

 * @property string $user_id

 * @property string $job_title

 * @property string $institution

 * @property string $from

 * @property string $to

 * @property string $job_description

 */

class MemberExperience extends \yii\db\ActiveRecord

{

    /**

     * @inheritdoc

     */

    public static function tableName()

    {

        return 'member_experience';

    }



    /**

     * @inheritdoc

     */

    public function rules()

    {

        return [

            [['job_title', 'institution', 'from_y', 'to_y','from_m', 'to_m'], 'string', 'max' => 100],

            [['job_title', 'institution', 'from_y'], 'required'],

            ['user_id' , 'safe'],

            ['job_description' , 'string' , 'max' => 1024],

        ];

    }



    /**

     * @inheritdoc

     */

    public function attributeLabels()

    {

        return [

            'id' => 'ID',

            'user_id' => 'User ID',

            'job_title' => 'Job Title',

            'institution' => 'Organisation',

            'from_m' => 'From',

            'to_m' => 'To',

            'job_description' => 'Job Description',

        ];

    }

}

