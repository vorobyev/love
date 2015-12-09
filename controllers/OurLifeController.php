<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\File;
use yii\data\Pagination;

class OurLifeController extends Controller {
    
    
    public function actionViewPhoto ()
    {
        if (Yii::$app->user->isGuest){
            return $this->redirect('index.php?r=site/login');
        } else {
            $href=urlencode(Yii::$app->request->get('image'));
            $file=new File();
            $query=File::find();
            $pagination=new Pagination([
                'defaultPageSize'=>7,
                'totalCount'=>$query->count()
            ]);
            $model=$query->orderBy("id")
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->all();
            return $this->render('image',[
                'image'=>$model,
                'pagination'=>$pagination
                ]);
        }
        
    }
}
