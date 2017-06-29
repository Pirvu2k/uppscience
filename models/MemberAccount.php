<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $created_on
 * @property string $last_modified_on
 * @property string $trash
 * @property string $last_login_activity
 * @property string $title
 * @property string $given_name
 * @property string $family_name
 * @property string $email
 * @property integer $birth_year
 * @property string $password
 * @property string $reset_pass_exp_date
 * @property string $website
 * @property string $bio
 * @property string $country
 * @property string $zip
 * @property string $city
 * @property string $address
 * @property string $state
 * @property string $mobile
 * @property string $phone
 * @property string $fax
 * @property string $terms
 * @property string $confirmed
 * @property integer $active_projects
 *
 * @property MemberInterest[] $memberInterests
 * @property MemberProjectCanvasAssignation[] $memberProjectCanvasAssignations
 * @property MemberSector[] $memberSectors
 * @property MemberSpecialization[] $memberSpecializations
 * @property MemberSubSector[] $memberSubSectors
 */
class MemberAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_on', 'last_modified_on', 'last_login_activity', 'reset_pass_exp_date','auth_key', 'no_work_experience', 'is_pro', 'is_student'], 'safe'],
            [['trash', 'terms', 'confirmed'], 'string'],
            [['birth_year', 'active_projects'], 'integer'],
            [['title', 'given_name', 'family_name'], 'string', 'max' => 512],
            [['email'], 'string', 'max' => 512],
            [['password'], 'string', 'max' => 255],
            [['mobile' , 'phone'] , 'integer' , 'message' => 'Please enter a valid number.'],
            [['website', 'country', 'role'], 'string', 'max' => 512],
            [['bio'], 'string', 'max' => 1024],
            [['email'], 'unique'],
            'websiteUrl' => ['website', 'url', 'defaultScheme' => 'http'],
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
            'trash' => 'Trash',
            'last_login_activity' => 'Last Login Activity',
            'title' => 'Title',
            'given_name' => 'Given Name',
            'family_name' => 'Family Name',
            'email' => 'Email',
            'birth_year' => 'Birth Year',
            'password' => 'Password',
            'reset_pass_exp_date' => 'Reset Pass Exp Date',
            'website' => 'Website',
            'bio' => 'General professional biography',
            'country' => 'Country',
            'zip' => 'Zip',
            'city' => 'City',
            'address' => 'Address',
            'state' => 'State',
            'mobile' => 'Mobile',
            'phone' => 'Phone',
            'fax' => 'Fax',
            'terms' => 'Terms',
            'confirmed' => 'Confirmed',
            'active_projects' => 'Active Projects',
            'is_pro' => 'I\'m a professional',
            'is_student' => 'I\'m a student'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberInterests()
    {
        return $this->hasMany(MemberInterest::className(), ['CoP' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberProjectCanvasAssignations()
    {
        return $this->hasMany(MemberProjectCanvasAssignation::className(), ['Member' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberSectors()
    {
        return $this->hasMany(MemberSector::className(), ['CoP' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberSpecializations()
    {
        return $this->hasMany(MemberSpecialization::className(), ['CoP' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberSubSectors()
    {
        return $this->hasMany(MemberSubSector::className(), ['CoP' => 'id']);
    }
}
