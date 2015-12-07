<?php

namespace app\models;
use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class File Extends ActiveRecord {
    public $profile_pic;
    public $id;
    
    public static function tableName()
    {
        return "photos";
    }
    
    public function save($runValidation = false, $attributeNames = NULL, $name=NULL)//тут ошибки
    {
        if (gettype($attributeNames)=="array"){
            $this->name=$attributeNames['FileName'];
            $this->time =(isset($attributeNames["DateTime"])) ? date("Y-m-d H:i:s", strtotime($attributeNames['DateTime'])):date ("Y-m-d H:i:s", filemtime($attributeNames["path"]));

        } else {
            $this->name=$name;
            $this->time=date ("Y-m-d H:i:s", filemtime($attributeNames));
        }
        $this->access=0;
        $this->uploadTime=date("Y-m-d H:i:s");
        return parent::save($runValidation);
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
    public function uploadProfilePicture() 
    {
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
