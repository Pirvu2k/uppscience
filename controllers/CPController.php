<?php

namespace app\controllers;

use Yii;
use app\models\ExpertAccount;
use app\models\ExpertAccountSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\ExpertEducation;
use app\models\ExpertExperience;
use app\models\ExpertSector;
use app\models\ExpertSubSector;
use app\models\ExpertSpecialization;
use app\models\ExpertInterest;
use app\models\Sector;
use app\models\SubSector;
use app\models\Specialization;
use app\models\Interest;
use app\models\ExpertLanguage;
/**
 * ExpertController implements the CRUD actions for ExpertAccount model.
 */
class CpController extends Controller
{
    /**
     * @inheritdoc
     */
//    public function behaviors()
//    {
//        return [
//             'access' => [
//                'class' => AccessControl::className(),
//                'only' => ['create', 'index','update','delete','view','role'],
//                'rules' => [
//                    [
//                        'allow' => false,
//                        'actions' => ['create', 'index','delete'],
//                        'roles' => ['?','@'],
//                    ],
//                    [
//                        'allow' => true,
//                        'actions' => ['view'],
//                        'roles' => ['@'],
//                    ],
//                    [
//                        'allow' => true,
//                        'actions' => ['update'],
//                        'matchCallback' => function ($rule, $action) {
//                            if(Yii::$app->user->identity->type=='e' && (Yii::$app->user->id == Yii::$app->request->get('id')))
//                                return true;
//                            else return false; 
//                        },
//                    ],
//                    [
//                        'allow' => true,
//                        'actions' => ['role'],
//                        'matchCallback' => function ($rule, $action) {
//                            if(Yii::$app->user->identity->type=='e' && (Yii::$app->user->id == Yii::$app->request->get('id')))
//                                return true;
//                            else return false; 
//                        },
//                    ],
//                ],
//            ],
//
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
//        ];
//    }

    public function ActionListmembers()
    {
        $searchModel = new ExpertExperienceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderPartial('listmembers', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
