<?php



namespace app\models;



use Yii;



/**

 * This is the model class for table "student_experience".

 *

 * @property integer $id

 * @property string $user_id

 * @property string $job_title

 * @property string $institution

 * @property string $from

 * @property string $to

 */

class StudentExperience extends \yii\db\ActiveRecord

{

    /**

     * @inheritdoc

     */

    public static function tableName()

    {

        return 'student_experience';

    }



    /**

     * @inheritdoc

     */

    public function rules()

    {

        return [

            [['job_title', 'institution', 'from_y', 'to_y', 'from_m', 'to_m'], 'string', 'max' => 50],

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

            'job_title' => 'Work Experience',

            'institution' => 'Organisation',

            'from_m' => 'From',

            'to_m' => 'To',

        ];

    }

}

