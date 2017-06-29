<?php

namespace app\controllers;

use Yii;
use app\models\StudentAccount;
use app\models\StudentAccountSearch;
use app\models\StudentExperience;
use app\models\StudentEducation;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * StudentController implements the CRUD actions for StudentAccount model.
 */
class StudentController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'index','update','delete','view'],
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['create', 'index','delete'],
                        'roles' => ['?','@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'matchCallback' => function ($rule, $action) {
                            if(Yii::$app->user->identity->type=='s' && (Yii::$app->user->id == Yii::$app->request->get('id')))
                                return true;
                            else return false; 
                        },
                    ],
                ],
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all StudentAccount models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StudentAccountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StudentAccount model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $experience=StudentExperience::find()->where(['user_id' => $id]) -> all();
        $education=StudentEducation::find()->where(['user_id' => $id]) -> all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'education' => $education,
            'experience' => $experience,
        ]);
    }

    /**
     * Creates a new StudentAccount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StudentAccount();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing StudentAccount model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $tabid1 = "tab-1";
        $tabid2 = "tab-1";
        
        if($_REQUEST != null && isset($_REQUEST["tabid"]))
        {
            $arr_tabid = explode("-", $_REQUEST["tabid"]);
            
            if(count($arr_tabid) == 2)
            {
                $tabid1 = "tab-" . $arr_tabid[1];
                $arr_tabid[1]++;
                $tabid2 = "tab-" . $arr_tabid[1];
            }
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) 
        {
            return $this->redirect(['update', 'id' => $model->id, 'tabid' => $tabid2]);
        } 
        else 
        {
            return $this->render('update', [
                'model' => $model,
                'tabid' => $tabid1
            ]);
        }
    }

    /**
     * Deletes an existing StudentAccount model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the StudentAccount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StudentAccount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StudentAccount::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
