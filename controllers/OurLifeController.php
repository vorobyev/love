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
                'defaultPageSize'=>18,
                'totalCount'=>$query->count(),
                'pageSizeLimit' => [1, 18]
            ]);
            $model=$query->orderBy("id")
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->all();
            $modelNext=$query->orderBy("id")
                    ->offset($pagination->limit)
                    ->limit(1)
                    ->all();
            $modelPrev=$query->orderBy("id")
                    ->offset($pagination->offset-1)
                    ->limit(1)
                    ->all();           
            $modelLast=$query->orderBy("id")
                    ->offset($query->count()-1)
                    ->limit(1)
                    ->all();
            $modelFirst=$query->orderBy("id")
                    ->offset(0)
                    ->limit(1)
                    ->all();
            $index=0;
            foreach ($model as $value){
                if ($value->href==$href){
                    break;
                }
                $index+=1;
            }
            return $this->render('image',[
                'modelPrev'=>$modelPrev,
                'modelFirst'=>$modelFirst,
                'countQuery'=>$query->count(),
                'modelLast'=>$modelLast,
                'modelNext'=>$modelNext,
                'index'=>$index,
                'href'=>$href,
                'image'=>$model,
                'pagination'=>$pagination
                ]);
        }
        
    }
}
