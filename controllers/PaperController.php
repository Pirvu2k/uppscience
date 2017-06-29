<?php

namespace app\controllers;

use Yii;
use app\models\Paper;
use app\models\PaperSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Sector;
use app\models\SubSector;
use app\models\PaperAbuse;
use yii\helpers\ArrayHelper;
use app\models\PaperSearchFilter;
use app\models\MemberAccount;
use yii\db\Expression;
use yii\helpers\Html;
use app\models\Member;
use app\models\PaperEvaluation;
use app\models\MemberLanguage;
use app\models\MemberSector;
use app\models\Languages;

/**
 * PaperController implements the CRUD actions for Paper model.
 */
class PaperController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'index','update','delete','view'],
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['create', 'index','update','delete','view'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update' , 'view' , 'create'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->identity->type == 's';
//                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index','view'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->identity->type == 'e';
//                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['confirm']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete']
                    ],
                ],
            ],
        ];
    }


    public function actionInvitationsrandom($id)
    {
      
        if(Yii::$app->user->isGuest)
        {
            return $this->redirect('index.php?r=site/index');
        }

        $model = Paper::find()->where(['id' => $id])->one();
        
        if($model->created_by != Yii::$app->user->id)
        {
            die("Invalid operation");
        }
        
        $evaluators = PaperEvaluation::find()->where(['paper' => $model->id])->all();

        if(count($evaluators) < 2)
        {
            $eligible_members = [];

            //Finding evaluators
            $candidate_members = Member::find()->where(['confirmed' => 'Yes'])->andWhere(['not' , ['id' => $model->created_by]])->all();  

            foreach($candidate_members as $member)
            {
                $language_find = Languages::find()->where(['id' => $model->language])->one();

                $language_check = MemberLanguage::find()->where(['language' => $language_find->name , 'member' => $member->id])->one();
                
                if($language_check != null)
                {
                    $sector_check = MemberSector::find()->where(['sector_id' => $model->discipline , 'member' => $member->id])->one();
                  
                  
                    $eligible_members[] = $member;
                        
                     

                }
            }    
            
            if(count($eligible_members) < 2)
            {
                foreach($candidate_members as $member)
                {
                    $language_find = Languages::find()->where(['id' => $model->language])->one();

                    $language_check = MemberLanguage::find()->where(['language' => $language_find->name , 'member' => $member->id])->one();
                    
                    if($language_check != null)
                    {
                        $eligible_members[] = $member;  
                    }
                }
            }

            $random1 = mt_rand (0,count($eligible_members));

            $random2 = $random1;

            while($random2 == $random1)
                $random2 = mt_rand (0,count($eligible_members));

            $selected_members = [$eligible_members[$random1], $eligible_members[$random2]];

            foreach($selected_members as $selected_member)
                if($selected_member != null && $selected_member->id != "")
                {   
                    $new_invitation = new PaperEvaluation();
                    $new_invitation->paper = $model->id;
                    $new_invitation->member = $selected_member->id;
                  
                    $new_invitation->expiry_date= new Expression('DATE_ADD(NOW(), INTERVAL 14 DAY)');
                    
                    if($new_invitation->save())
                        $this->SendInvitationEmail($new_invitation);
                }
        }

        //Refresh after assignation
        $existing_members = array();
        $existing_members["Accepted"] = PaperEvaluation::find()->where(['paper' => $model->id, 'status' => 'Accepted'])->all();
        $existing_members["Pending"] = PaperEvaluation::find()->where(['paper' => $model->id, 'status' => 'Pending'])->andWhere([">", "expiry_date", new Expression('NOW()')])->all();
        $existing_members["Expired"] = PaperEvaluation::find()->where(['paper' => $model->id, 'status' => 'Pending'])->andWhere(["<", "expiry_date", new Expression('NOW()')])->all();
        $existing_members["Finalized"] = PaperEvaluation::find()->where(['paper' => $model->id, 'status' => 'Finalized'])->all();
        
        return $this->render('invitationsrandom', [
            'model' => $model,
            'existing_members' => $existing_members
        ]);
                
    }

    public function actionInvitations1($id)
    {
        if(Yii::$app->user->isGuest)
        {
            return $this->redirect('index.php?r=site/index');
        }
        
        $model = Paper::find()->where(['id' => $id])->one();
        
        if($model->created_by != Yii::$app->user->id)
        {
            die("Invalid operation");
        }
        
        
        return $this->render('invitations1', [
            'model' => $model,
            
        ]);
    }

    public function actionPrecreate()
    {
        $user = MemberAccount::findOne(Yii::$app->user->id);

        return $this->render('precreate', 
        [
            'user' => $user
        ]);
    }

    /**
     * Lists all Paper models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PaperSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Paper model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {      
        $model = $this->findModel($id);

        $abuseModel = new \app\models\AbuseForm();

        if ($abuseModel->load(Yii::$app->request->post()) && $abuseModel->validate()){ // add abuse

            $abuse=new PaperAbuse();
            $abuse->reported_by = Yii::$app->user->id;
            $abuse->paper = $model->id;
            $abuse->note = $abuseModel->info;
            $abuse->created_on = new Expression('NOW()');
            $abuse->status = 'Reported';
            $abuse->save();

            return $this->refresh();

        }

        return $this->render('view', [
            'model' => $model,
            'abuseModel' => $abuseModel
        ]);
    }

    /**
     * Creates a new Paper model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->isGuest)
        {
            return $this->redirect('index.php?r=site/index');
        }

        $model = new Paper();

        
        if ($model->load(Yii::$app->request->post())) 
        {
          $model->created_on = new Expression('NOW()');
          $model->last_modified_on = new Expression('NOW()');
          $model->submission_date = new Expression('NOW()');
          $model->created_by= Yii::$app->user->id;
          $model->last_modified_by = Yii::$app->user->id;

           if ($model->validate())
           {

                if(isset($_REQUEST["mode"]) && $_REQUEST["mode"] == "submit")
                {
                    $model->status = 'Submitted';
                }
                else
                {
                    $model->status = 'Draft';
                }
                $model->save();
                 
                if($model->status == "Submitted")
                {
                    return $this->redirect(['invitations1', 'id' => $model->id]);               
                }
                else
                {
                    return $this->redirect(['update', 'id' => $model->id]);
                }
           } 
        } 
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Paper model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
   public function actionUpdate($id)
    {
        if(Yii::$app->user->isGuest)
        {
            return $this->redirect('index.php?r=site/index');
        }
        
        $model = $this->findModel($id);
        
        if(isset($_REQUEST["mode"]) && $_REQUEST["mode"] == "submit")
        {
            $model->scenario = 'submit';
        }
        else
        {
            $model->scenario = 'draft';
        }

        if(is_null(Paper::find()->where(['created_by' => Yii::$app->user->id , 'id' => $model->id ,'status' => 'Draft'])->one()))
        {
            return $this->render('/site/error', ['message' => 'This paper is currently evaluated or you are not part of it.' , 'name' => 'Error']);
        }

        if ($model->load(Yii::$app->request->post())) 
        {
            $model->last_modified_on = new Expression('NOW()');


           if ($model->validate())
           {

                if(isset($_REQUEST["mode"]) && $_REQUEST["mode"] == "submit")
                {
                    $model->status = 'Submitted';
                }
                else
                {
                    $model->status = 'Draft';
                }
                
                $model->update();

                if($model->status == "Submitted")
                {
                    return $this->redirect(['view', 'id' => $model->id]);             
                }
                else
                {
                    return $this->redirect(['update', 'id' => $model->id]);
                }
           } 
        } 
        
        return $this->render('update', [
            'model' => $model,
        ]);
    }
    /**
     * Deletes an existing Paper model.
     * If deletion is successful, the browser will be redirected to the 'list' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['list']);
    }

    /**
     * Finds the Paper model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Paper the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Paper::findOne($id)) !== null) {
			/*$get_author_details = Yii::$app->db->createCommand("SELECT given_name, family_name FROM member WHERE id = ".$model['created_by'])->queryAll();
			$get_last_modified_details = Yii::$app->db->createCommand("SELECT given_name, family_name FROM member WHERE id = ".$model['last_modified_by'])->queryAll();
			
			$model['status'] = array('status'=>$model['status'], 'created_by'=>$model['created_by']);
			$model['created_by'] = Html::a($get_author_details[0]['given_name'] . " " . $get_author_details[0]['family_name'], ['member/profile', 'id' => $model['created_by']]);
			$model['last_modified_by'] = Html::a($get_last_modified_details[0]['given_name'] . " " . $get_last_modified_details[0]['family_name'], ['member/profile', 'id' => $model['last_modified_by']]);
			*/
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
    public function actionList()
    {
        if(Yii::$app->user->isGuest)
        {
            return $this->redirect('index.php?r=site/index');
        }
        
        $paperfilter = new PaperSearchFilter();
        $subsector = new SubSector();
        $subsectors = array();
        $subsectors[] = $subsector;
        
        if (isset($_REQUEST["PaperSearchFilter"]))
        {
            $paperfilter->discipline = $_REQUEST["PaperSearchFilter"]["discipline"];
            $subsectors = SubSector::find()->where("sector = '" . $paperfilter->discipline . "'")->orderBy(['name' => SORT_ASC])->all();
        }
        
        $sectors = Sector::find()->orderBy(['name' => SORT_ASC])->all();
        $sectors = ArrayHelper::map($sectors, 'id', 'name');
        $subsectors = ArrayHelper::map($subsectors, 'id', 'name');
        
        $searchModel = new PaperSearch();
        $dataProvider = $searchModel->searchByFilter($paperfilter);
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'filtermodel' => $paperfilter,
            'sectors' => $sectors,
            'subsectors' => $subsectors
        ]);
    }

    private function SendInvitationEmail($member_canvas)
    {  
        $name = $member_canvas->evaluator->given_name . " " . $member_canvas->evaluator->family_name;

        $id = openssl_encrypt($member_canvas->id, 'AES-128-ECB', 'n9vwoxd1mv1mf8ka');

        $url = Yii::$app->urlManager->createAbsoluteUrl(
                ['canvas/profile','id'=>$member_canvas->paperinfo->id]);

        $confirmation_url = Yii::$app->urlManager->createAbsoluteUrl(
                            ['canvas/confirm','id'=>$id]);

        Yii::$app->mailer->compose('invitation_mail' , ['title' => $member_canvas->paperinfo->title , 'summary' => $member_canvas->paperinfo->abstract_in_english, 'url' =>$url , 'confirmation_url' => $confirmation_url , 'name' => $name])
            ->setTo($member_canvas->evaluator->email)
            ->setFrom('cop@viscontiproject.eu')
            ->setSubject('An invitation to evaluate a project')
            ->send();
    } 
}
