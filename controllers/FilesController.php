<?php



namespace app\controllers;
use Yii;
use yii\web\Controller;
use app\models\File;

class FilesController extends Controller{
    public $kk;
 
    public function ResizeImage ($filename, $n_height,$n_width , $quality = 85, $path_save, $new_filename)
    {
        /*
        * Адрес директории для сохранения картинки
        */
        $dir=dirname($filename)."/".$path_save."/";
        
        /*
        * Извлекаем формат изображения, то есть получаем 
        * символы находящиеся после последней точки
        */
        $ext=strtolower(end((explode(".", $filename))));
        
        /*
        * Допустимые форматы
        */
        $extentions = array('jpg', 'gif', 'png', 'bmp');
    
        if (in_array($ext, $extentions)) {   
              // Высота изображения миниатюры
        
             list($width, $height) = getimagesize($filename); // Возвращает ширину и высоту
             if ($width>$n_width) {
                 if ($height>$n_height){
                     if (($widtn-$n_width)>($height-$n_height)){
                        $newheight    = $height * $n_width;
                        $newwidth    = $newheight / $height;
                        $percent = $n_width;
                     } else {
                        $newwidth    = $width * $n_height;
                        $newheight    = $newwidth / $height;  
                        $percent = $n_height;
                     }
                 } else {
                    $newheight    = $height * $n_width;
                    $newwidth    = $newheight / $height;
                    $percent = $n_width;
                 }
             } else {
                 if ($height>$n_height) {
                    $newwidth    = $width * $n_height;
                    $newheight    = $newwidth / $height;
                    $percent = $n_height;
                 }
             }

        
             $thumb = imagecreatetruecolor($newheight, $percent);
        
             switch ($ext) {
                 case 'jpg':
                     $source = @imagecreatefromjpeg($filename);
                     break;
                
                  case 'gif':
                     $source = @imagecreatefromgif($filename);
                     break;
                
                  case 'png':
                     $source = @imagecreatefrompng($filename);
                     break;
                
                  case 'bmp':
                      $source = @imagecreatefromwbmp($filename);
              }
    
            /*
            * Функция наложения, копирования изображения
            */
            imagecopyresized($thumb, $source, 0, 0, 0, 0, $newheight, $percent, $width, $height);
        
            /*
            * Создаем изображение
            */
            switch ($ext) {
                case 'jpg':
                    imagejpeg($thumb, $dir . $new_filename, $quality);
                    break;
                    
                case 'gif':
                    imagegif($thumb, $dir . $new_filename);
                    break;
                    
                case 'png':
                    imagepng($thumb, $dir . $new_filename, $quality);
                    break;
                    
                case 'bmp':
                    imagewbmp($thumb, $dir . $new_filename);
                    break;
            }    
    } else {
        return false;
    }
    
    /* 
    *  Очищаем оперативную память сервера от временных файлов, 
    *  которые потребовались для создания миниатюры
    */
        @imagedestroy($thumb);         
        @imagedestroy($source);  
            
        return true;
    }
    
    public function actionAdd(){
       $model = new File();
       $oldFile = $model->getProfilePictureFile();
       $oldProfilePic = $model->profile_pic;
       $kk=Yii::$app->request->post();
       if ($model->load(Yii::$app->request->post())) {

           // process uploaded image file instance
           $image = $model->uploadProfilePicture();

           if($image === false && !empty($oldProfilePic)) {
               $model->profile_pic = $oldProfilePic;
           }

          // if ($model->save()) {
               // upload only if valid uploaded file instance found
               if ($image !== false) { // delete old and overwrite
                   if(!empty($oldFile) && file_exists($oldFile)) {
                       unlink($oldFile);
                   }
                   $path = $model->getProfilePictureFile();
                   $image->saveAs($path);
                   list($width, $height) = getimagesize($path);
                   $this->ResizeImage ($path, 100, 60, 85, "thumbnail",basename($path));
                   $this->ResizeImage ($path, 1024, 768, 85, "medium",basename($path));

               }
               \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
               $kk=$model->getProfilePictureUrl();
                try {
                    $exif = exif_read_data("/var/www/html/basic/web/".$kk, 'IFD0');
                    if ($exif!==false) {
                        $exif["path"]=$path;
                        $model->save(false,$exif,$model->profile_pic);
                    } else {
                        $model->save(false,$path,$model->profile_pic);
                    }
                }
                catch (Exception $e) {
                    $model->save(false,$path,$model->profile_pic);
                }
                $kkk=$model->getProfilePictureUrl("medium/");
                $kkkk=$model->getProfilePictureUrl("thumbnail/");
               return ["files"=> [
  [
    "name"=> $model->profile_pic,
    "size"=> $image->size,
    "url"=> $kkk,
    "thumbnailUrl"=> $kkkk,
    "deleteUrl"=> $kk,
    "deleteType"=> "DELETE"
  ]
]];
          // }
       }
    }
}
