<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class RecoveryForm extends Model
{
    public $email;
    public $password;
    public $info;
    public $type;
    private $_user = NULL;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
			['password', 'required'],
            // email and password are both required
            ['password', 'string', 'min' => 6]
            // rememberMe must be a boolean value
          //  ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            //['password', 'validatePassword'],
        ];
    }
    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function forgot()
    {
		$user = $this->getUser();
		if(!$user)
			$this->addError('email', "Sorry, we couldn't find any registered account with the provided email address.");
		else
		{
			//$this->addError('info', "<div class='alert alert-success' role='alert'><font color='green'>OK. We've sent you an email describing how to reset your password.</font></div>");
			return true;
		}
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {              
        $this->_user = Member::findByEmail($this->email);

        return $this->_user;
    }

    public function attributeLabels()
    {
        return [
            'type' => 'Role',
        ];
    }
}
