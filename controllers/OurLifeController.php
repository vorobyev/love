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
            $href=Yii::$app->request->get('href');
            $file=new File();
            $query=File::find();
            $pagination=new Pagination([
                'defaultPageSize'=>81,
                'totalCount'=>$query->count(),
                'pageSizeLimit' => [1, 81]
            ]);
            $model=$query->orderBy("id")
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->all();
            $index=0;
            foreach ($model as $value){
                if ($value->href==$href){
                    break;
                }
                $index+=1;
            }
            return $this->render('image',[
                'index'=>$index,
                'href'=>$href,
                'image'=>$model,
                'pagination'=>$pagination
                ]);
        }
        
    }
}
