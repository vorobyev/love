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
            $this->name=$name;
            $this->time =(isset($attributeNames["DateTime"])) ? date("Y-m-d H:i:s", strtotime($attributeNames['DateTime'])):date ("Y-m-d H:i:s", filemtime($attributeNames["path"]));

        } else {
            $this->name=$name;
            $this->time=date ("Y-m-d H:i:s", filemtime($attributeNames));
        }
        $this->href=$this->generateStr($this->name."cxfcnmt",2);//счастье
        $this->access=0;
        $this->uploadTime=date("Y-m-d H:i:s");
        return parent::save($runValidation);
    }
   
    private function generateStr($value,$key) 
    {
        $keyHash = md5(Yii::$app->params["key".$key], true);
        if ($key==1) {
            return str_replace("/","_",base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $keyHash, $value, MCRYPT_MODE_ECB)));
        } else {
            return urlencode(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $keyHash, $value, MCRYPT_MODE_ECB)));
        }
        //return str_replace(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $keyHash, $value, MCRYPT_MODE_ECB)));
    }
    
    public function getProfilePictureFile()
    {
        return isset($this->profile_pic) ? Yii::$app->params['uploadPath'] ."/". $this->profile_pic : null;
    }

    public function getProfilePictureUrl($subFolder="")
    {
        // return a default image placeholder if your source profile_pic is not found
        $profile_pic = isset($this->profile_pic) ? $this->profile_pic : 'default_user.jpg';
        return Yii::$app->params['uploadUrl'] . '/'.$subFolder . $profile_pic;
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
        $this->profile_pic = $this->generateStr($image->name."elfxf",1).".{$ext}";//удача

        // the uploaded profile picture instance
        return $image;
    }    
    
    public function getImage($href=false) 
    {
        if ($href==false){
            return static::findOne(['href'=>$href]);
        } else {
            return static::findOne(['id'=>'58']); 
        }
    }
}
