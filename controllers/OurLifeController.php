<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;

class OurLifeController extends Controller {
    
    
    public function actionViewPhoto ()
    {
        if (!Yii::$app->session->isActive){
            return $this->redirect('index.php?r=site/login');
        }
    }
}
