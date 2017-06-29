<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class AbuseForm extends Model
{
    public $info;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['info'], 'required'],
            ['info' , 'string' , 'min' => 10 , 'max' => 1024]
        ];
    }

    public function attributeLabels()
    {
        return [
            'info' => 'Info'
        ];
    }
}
