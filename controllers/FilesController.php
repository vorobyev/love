<?php



namespace app\controllers;
use Yii;
use yii\web\Controller;
use app\models\File;

class FilesController extends Controller{
    public $kk;
    
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
               }
               \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
               $kk=$model->getProfilePictureUrl();
               
               $exif = exif_read_data("/var/www/html/basic/web/".$kk, 'IFD0');
echo $exif===false ? "Не найдено данных заголовка.<br />\n" : "Изображение содержит заголовки<br />\n";

$exif = exif_read_data($kk, 0, true);
foreach ($exif as $key => $section) {
    foreach ($section as $name => $val) {
        echo "$key.$name: $val<br />\n";
    }
}
               
               return ["files"=> [
  [
    "name"=> $model->profile_pic,
    "size"=> $image->size,
    "url"=> $kk,
    "thumbnailUrl"=> $kk,
    "deleteUrl"=> $kk,
    "deleteType"=> "DELETE"
  ]
]];
          // }
       }
    }
}
