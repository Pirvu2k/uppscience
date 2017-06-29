<?php

namespace app\controllers;

class AbuseController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionReport()
    {	
    	
    	
        return $this->redirect(Yii::$app->request->referrer);
    }

}
