<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;


class Member extends User
{
    public $name;


    public function init() {

        parent::init();
    }

    public function rules() {
        $rules = parent::rules();
        unset($rules['emailRequired']);
        return $rules;
    }
    
    public static function findByEmail($email) 
    {
        $member = Member::find()->where(['email' => $email , 'confirmed' => 'Yes'])->one();
        
        if($member != null)
        {
            return $member;
        }
        
        return null;
    }
    
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }
    
    public static function tableName()
    {
        return 'member';
    }   
}