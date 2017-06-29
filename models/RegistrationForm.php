<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\models;


use Yii;
use yii\base\Model;

/**
 * Registration form collects user input on registration process, validates it and creates new User model.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class RegistrationForm extends Model
{   
    /**
     * @var string
     */
    public $captcha;

    /**
     * @var string Expert checkbox
     */
    public $type='';
    /**
     * @var string User email address
     */
    public $email;
    
    public $given_name;
    
    public $family_name;

    /**
     * @var string Password
     */
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // email rules
            'emailTrim'     => ['email', 'filter', 'filter' => 'trim'],
            'emailRequired' => ['email', 'required'],
            'given_name_required' => ['given_name', 'required'],
            'family_name_required' => ['family_name', 'required'],
            'emailPattern'  => ['email', 'email'],
            'emailUnique'   => [
                'email',
                'unique',
                //'targetClass' => 'app\models\User',
                'message' => 'This email address has already been taken',
            ],
            // password rules
            'passwordRequired' => ['password', 'required'],
            'passwordLength'   => ['password', 'string', 'min' => 6],
            // expert check
            'expertCheck'   => ['type','string'],
            //captcha
            'captcha' => ['captcha','captcha'],
            'captchaRequired' => ['captcha','required'],
            //type
            'type' => ['type','required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'    => 'E-mail',
            'password' => 'Password',
            'type'=> 'Role'
        ];
    }

}
