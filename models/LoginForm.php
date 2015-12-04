<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $id;
    public $profile_pic;
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
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
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
    
    public function getProfilePictureFile()
{
    return isset($this->profile_pic) ? Yii::$app->params['uploadPath'] ."/". $this->profile_pic : null;
}

    public function getProfilePictureUrl()
    {
        // return a default image placeholder if your source profile_pic is not found
        $profile_pic = isset($this->profile_pic) ? $this->profile_pic : 'default_user.jpg';
        return Yii::$app->params['uploadUrl'] . '/' . $profile_pic;
    }

/**
* Process upload of profile picture
*
* @return mixed the uploaded profile picture instance
*/
public function uploadProfilePicture() {
    // get the uploaded file instance. for multiple file uploads
    // the following data will return an array (you may need to use
    // getInstances method)
    $image = UploadedFile::getInstance($this, 'profile_pic');

    // if no image was uploaded abort the upload
    if (empty($image)) {
        return false;
    }

    // store the source file name
    //$this->filename = $image->name;
    $ext = end((explode(".", $image->name)));

    // generate a unique file name
    $this->profile_pic = Yii::$app->security->generateRandomString().".{$ext}";

    // the uploaded profile picture instance
    return $image;
}
}
