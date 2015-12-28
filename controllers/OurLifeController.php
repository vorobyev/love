<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\File;
use yii\data\Pagination;
use yii\helpers\Url;

class OurLifeController extends Controller {
    
    public function actionEvents () {
        if (Yii::$app->user->isGuest){
            return $this->redirect('index.php?r=site/login');
        } else {
            return $this->render('events',[

                    ]);
        }
    }
    
    public function actionOther () {
        if (Yii::$app->user->isGuest){
            return $this->redirect('index.php?r=site/login');
        } else {
            return $this->render('other',[

                    ]);
        }
    }
    
    public function actionViewPhoto ()
    {
        if (Yii::$app->user->isGuest){
            return $this->redirect('index.php?r=site/login');
        } else {
            $href=Yii::$app->request->get('href');
            $file=new File();
            $image1=File::find()->where(['href'=>$href])->one();
            $showOriginal=Yii::$app->request->get('showOriginal');
            $happy=Yii::$app->request->get('happyNewYear');
            if (isset($showOriginal)){  
               return $this->redirect("image/".$image1->name); 
            }
          
            

            $query=File::find();
            $pagination=new Pagination([
                'defaultPageSize'=>81,
                'totalCount'=>$query->count(),
                'pageSizeLimit' => [1, 81]
            ]);
             if (!isset($happy)){
            $model=$query->orderBy("id")
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->all();
             } else {
                 $model=$query->where(['effects'=>1])->orderBy("id")
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->all();
             }
            $modelNext=$query->orderBy("id")
                    ->offset($pagination->offset+$pagination->limit)
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
            
            if (isset($happy)){  
               return $this->render('imageHappyNewYear',[
                    'countQuery'=>$query->count(),
                    'image'=>$model
                    ]);
            }  
            
            $index=0;
            foreach ($model as $value){
                if ($value->href==$href){
                    break;
                }
                $index+=1;
            }
            $edit=Yii::$app->request->get('edit');
            if (isset($edit)) {
                if (isset($href)){
                    $imageEdit1=new File();
                    $imageEdit=$imageEdit1->find()->where(['href'=>$href])->one();
                    $logB=Yii::$app->request->post("login-button");
                     if (isset($logB)){
                         $File=Yii::$app->request->post("File");
                        $descr=$File["descr"];
                        $fullName=$File["fullName"];
                        $effects=$File["effects"];
                        $post=Yii::$app->request->post();
                   
                        $imageEdit->descr=$descr;
                        $imageEdit->fullName=$fullName;
                        $imageEdit->effects=$effects;
                        
                        $imageEdit->save(false,"standart");
                    }
               
                } else {
                        $imageEdit="";
                    }
                    $modelFile=new File();
                return $this->render('imageEdit',[
                    'model'=>$modelFile,
                    'imageEdit'=>$imageEdit,
                    'countQuery'=>$query->count(),
                    'index'=>$index,
                    'href'=>$href,
                    'image'=>$model,
                    'pagination'=>$pagination
                    ]);
            } else {
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
}
