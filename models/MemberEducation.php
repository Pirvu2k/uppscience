<?php



namespace app\models;



use Yii;



/**

 * This is the model class for table "member_education".

 *

 * @property integer $id

 * @property string $user_id

 * @property string $degree

 * @property string $institution

 * @property string $from

 * @property string $degree_details

 * @property string $to

 */

class MemberEducation extends \yii\db\ActiveRecord

{

    /**

     * @inheritdoc

     */

    public static function tableName()

    {

        return 'member_education';

    }



    /**

     * @inheritdoc

     */

    public function rules()

    {

        return [

            [['degree', 'institution', 'from_y', 'to_y', 'from_m', 'to_m'], 'string', 'max' => 100],

            ['user_id','safe'],

            [['degree', 'institution', 'from_y'] , 'required'],

            ['degree_details' , 'string' , 'max' => 300],

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

            'degree' => 'Qualification',

            'institution' => 'Institution',

            'from_m' => 'From',

            'degree_details' => 'Qualification Details',

            'to_m' => 'To',

        ];

    }

}

