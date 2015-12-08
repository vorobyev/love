<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\File;

class OurLifeController extends Controller {
    
    
    public function actionViewPhoto ()
    {
        if (Yii::$app->user->isGuest){
            return $this->redirect('index.php?r=site/login');
        } else {
            $href=urlencode(Yii::$app->request->get('image'));
            $file=new File();
            $model=$file->getImage($href);
            return $this->render('image',['image'=>$model]);
        }
        
    }
}
