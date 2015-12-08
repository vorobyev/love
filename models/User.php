<?php

namespace app\models;
use yii\db\ActiveRecord;

class User extends ActiveRecord implements IdentityInterface 
{
    public $name;
    public $password;

    public static function tableName()
    {
        return "users";
    }
    
    public function rules()
    {
        return [
            // username and password are both required
            [['name', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            [['password','user'], 'validateUser'],
        ];
    }
    
    public function validateUser($attribute, $params){
        
    }
}
