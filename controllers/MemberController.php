<?php

namespace app\controllers;

use Yii;
use app\models\MemberAccount;
use app\models\MemberAccountSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\MemberEducation;
use app\models\MemberExperience;
use app\models\MemberSector;
use app\models\MemberSubSector;
use app\models\MemberSpecialization;
use app\models\MemberInterest;
use app\models\Sector;
use app\models\SubSector;
use app\models\Specialization;
use app\models\Interest;
use app\models\MemberLanguage;
use app\models\MemberSearch;
use app\models\MemberSearchFilter;
use app\models\Canvas;
use app\models\MemberCanvas;
use yii\helpers\ArrayHelper;
use app\models\Paper;
/**
 * MemberController implements the CRUD actions for MemberAccount model.
 */
class MemberController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
             'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'index','update','delete','view','role'],
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['create', 'index'],
                        'roles' => ['?','@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['role']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete']
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all MemberAccount models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MemberAccountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MemberAccount model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        if(Yii::$app->user->isGuest)
        {
            return $this->redirect('index.php?r=site/index');
        } 
        
        $experience=MemberExperience::find()->where(['user_id' => $id]) -> all();
        $education=MemberEducation::find()->where(['user_id' => $id]) -> all();
        $sectors=MemberSector::find()->where(['member' => $id])->all();
        $subsectors=MemberSubSector::find()->where(['member'=>$id])->all();
        $specializations=MemberSpecialization::find()->where(['member'=>$id])->all();
        $interests=MemberInterest::find()->where(['member'=>$id])->all();
        $languages=MemberLanguage::find()->where(['member'=>$id])->all();
        $papers= Paper::find()->where(['created_by' => $id])->all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'experience' => $experience,
            'education' => $education,
            'sectors' => $sectors,
            'subsectors' => $subsectors,
            'specializations' => $specializations,
            'interests' => $interests,
            'languages' => $languages,
            'papers' => $papers,
        ]);
    }

    public function actionDelete($id)
    {
        if(Yii::$app->user->isGuest || Yii::$app->user->identity->getAdminStatus() == 'no')
        {
            return $this->redirect('index.php?r=site/index');
        }
        
        $member = $this->findModel($id);

        $member->trash = 'Yes';
        $member->update();

        return $this->redirect(['profiles']);
    
    }

    /**
     * Creates a new MemberAccount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MemberAccount();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MemberAccount model.
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
        
        if($id != Yii::$app->user->id)
        {
            die("Invalid operation");
        }
        
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

        if ($model->load(Yii::$app->request->post())) {

            $sectors=Sector::find()->where(['status' => 'Active', 'trash' => NULL])->orderBy("name")->all();

            foreach($sectors as $s)
            {
                if(isset($_POST['sector_'. $s->id]) && $_POST['hidden_sector_'.$s->id] == '0') {
                        //add sector to member_sector table
                        $item=new MemberSector();
                        $item->sector_id=$s->id;
                        $item->member=Yii::$app->user->id;
                        $item->save();
                }

                if(!isset($_POST['sector_'. $s->id]) && $_POST['hidden_sector_'.$s->id] == '1') {
                        //remove sector from member_sector table
                        $item=MemberSector::find()->where(['member'=>Yii::$app->user->id, 'sector_id' => $s->id])->one();
                        $item->delete();
                }
            }

            $subsectors=SubSector::find()->where(['status' => 'Active', 'trash' => NULL])->orderBy("name")->all();

            foreach($subsectors as $s)
            {
                if(isset($_POST['subsector_'. $s->id]) && $_POST['hidden_subsector_'.$s->id] == '0') {
                        //add sector to member_sub_sector table
                        $item=new MemberSubSector();
                        $item->subsector=$s->id;
                        $item->member=Yii::$app->user->id;
                        $item->save();
                }

                if(!isset($_POST['subsector_'. $s->id]) && $_POST['hidden_subsector_'.$s->id] == '1') {
                        //remove sector from member_sub_sector table
                        $item=MemberSubSector::find()->where(['member'=>Yii::$app->user->id, 'subsector' => $s->id])->one();
                        $item->delete();
                }
            }

            $specializations=Specialization::find()->where(['status' => 'Active', 'trash' => NULL])->orderBy("name")->all();

            foreach($specializations as $s)
            {
                if(isset($_POST['specialization_'. $s->id]) && $_POST['hidden_specialization_'.$s->id] == '0') {
                        //add sector to member_specialization table
                        $item=new MemberSpecialization();
                        $item->specialization=$s->id;
                        $item->member=Yii::$app->user->id;
                        $item->save();
                }

                if(!isset($_POST['specialization_'. $s->id]) && $_POST['hidden_specialization_'.$s->id] == '1') {
                        //remove sector from member_specialization table
                        $item=MemberSpecialization::find()->where(['member'=>Yii::$app->user->id, 'specialization' => $s->id])->one();
                        $item->delete();
                }
            }

            $interests=Interest::find()->where(['status' => 'Active', 'trash' => NULL])->orderBy("name")->all();

            foreach($interests as $s)
            {
                if(isset($_POST['interest_'. $s->id]) && $_POST['hidden_interest_'.$s->id] == '0') {
                        //add sector to member_sub_sector table
                        $item=new MemberInterest();
                        $item->interest=$s->id;
                        $item->member=Yii::$app->user->id;
                        $item->save();
                }

                if(!isset($_POST['interest_'. $s->id]) && $_POST['hidden_interest_'.$s->id] == '1') {
                        //remove sector from member_sub_sector table
                        $item=MemberInterest::find()->where(['member'=>Yii::$app->user->id, 'interest' => $s->id])->one();
                        $item->delete();
                }
            }

            if($model->save())
                return $this->redirect(['update', 'id' => $model->id, 'tabid' => $tabid2]);
            } 
            return $this->render('update', [
                'model' => $model,
                'tabid' => $tabid1
            ]);
        
    }

    /**
     * Finds the MemberAccount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MemberAccount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MemberAccount::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionRole($id)
    {
        if(Yii::$app->user->isGuest)
        {
            return $this->redirect('index.php?r=site/index');
        }
        
        if($id != Yii::$app->user->id)
        {
            die("Invalid operation");
        }
            
        $member = MemberAccount::find()->where(['id'=>Yii::$app->user->id])->one();    
        $tw = 0; // technical weight
        $ew = 0; // economical weight
        $cw = 0; // creative weight
        
        if($member->given_name == "" ||
           $member->family_name == "")
        {
            Yii::$app->getSession()->setFlash('error', 'You need to complete personal information tab first');
            return $this->redirect(['update' , 'id'=>Yii::$app->user->id]);
        }

        $ed_records = \app\models\MemberEducation::find()->where(['user_id' => Yii::$app->user->id])->all(); // get ed records

        if(empty($ed_records))
        {
            Yii::$app->getSession()->setFlash('error', 'You need at least one educational record to determine role.');
            return $this->redirect(['update' , 'id'=>Yii::$app->user->id]);
        }

        foreach($ed_records as $ed) 
        {   
            $degree = \app\models\Degrees::find()->where(['code'=>$ed->degree])->one(); // get degree weights

            $tw+= $degree->expert_technical_weight * 0.1; // add degree weights
            $ew+= $degree->expert_economical_weight * 0.1;
            $cw+= $degree->expert_creative_weight * 0.1;
        }

        $exp_records = \app\models\MemberExperience::find()->where(['user_id' => Yii::$app->user->id])->all();

        if(empty($exp_records) && $member->no_work_experience != "1")
        {
            Yii::$app->getSession()->setFlash('error', 'You need at least one experience record to determine role.');
            return $this->redirect(['update' , 'id'=>Yii::$app->user->id]);
        }

        foreach($exp_records as $exp)
        {   
            $job = \app\models\Jobs::find()->where(['code'=>$exp->job_title])->one();

            $tw+= $job->expert_technical_weight * 0.1;
            $ew+= $job->expert_economical_weight * 0.1;
            $cw+= $job->expert_creative_weight * 0.1;
        }

        $sectors = \app\models\MemberSector::find()->where(['member' => Yii::$app->user->id])->all();

        if(empty($sectors))
        {
            Yii::$app->getSession()->setFlash('error', 'You need at least one sector to determine role.');
            return $this->redirect(['update' , 'id'=>Yii::$app->user->id]);
        }

        foreach($sectors as $s)
        {  
        	$count = 0;
         
            $sector = \app\models\Sector::find()->where(['id'=> $s->sector_id])->one();

            $subs = \app\models\SubSector::find()->where(['sector' => $s->sector_id])->all(); //get all subsectors of sector

            foreach($subs as $sub)
            {
                $check= \app\models\MemberSubSector::find()->where(['subsector' => $sub->id , 'member'=>Yii::$app->user->id])->one(); //check if member has at least one checked
                if($check != null)
                {
                    $count++;
                } 
            }
            
            if($count == 0)
            {
	            Yii::$app->getSession()->setFlash('error', 'You need at least one subsector for each sector to determine role.');
                    return $this->redirect(['update' , 'id'=>Yii::$app->user->id]);
            }

            $tw+= $sector->expert_technical_weight * 0.1;
            $ew+= $sector->expert_economical_weight * 0.1;
            $cw+= $sector->expert_creative_weight * 0.1;
        }

        $subsectors = \app\models\MemberSubSector::find()->where(['member' => Yii::$app->user->id])->all();

        foreach($subsectors as $s)
        {   
        
            $subsector = \app\models\SubSector::find()->where(['id'=> $s->subsector])->one();

            $tw+= $subsector->expert_technical_weight * 0.2;
            $ew+= $subsector->expert_economical_weight * 0.2;
            $cw+= $subsector->expert_creative_weight * 0.2;
        }

        $specializations = \app\models\MemberSpecialization::find()->where(['member' => Yii::$app->user->id])->all();

        foreach($specializations as $s)
        {   
            $specialization = \app\models\Specialization::find()->where(['id'=> $s->specialization])->one();

            $tw+= $specialization->expert_technical_weight * 0.4;
            $ew+= $specialization->expert_economical_weight * 0.4;
            $cw+= $specialization->expert_creative_weight * 0.4;
        }

        $interests = \app\models\MemberInterest::find()->where(['member' => Yii::$app->user->id])->all();

        foreach($interests as $i)
        {   
            $interest = \app\models\Interest::find()->where(['id'=> $i->interest])->one();

            $tw+= $interest->expert_technical_weight * 0.8;
            $ew+= $interest->expert_economical_weight * 0.8;
            $cw+= $interest->expert_creative_weight * 0.8;
        }

        $max = max($tw,$ew,$cw);

        if($max==$tw)
        {
            $member->role='Technical';
            $member->save();
        }
        else if($max==$ew) {
            $member->role='Economical';
            $member->save();
        }
        else {
            $member->role='Creative';
            $member->save();
        }

        //Yii::$app->getSession()->setFlash('error', 'Your role has been set : ' . $member->role);
        return $this->redirect(['update', 
                'id' => Yii::$app->user->id, 'tabid' => 'tab-7'
            ]);
    }
    
    public function actionProfiles()
    {   
        if(Yii::$app->user->isGuest)
        {
            return $this->redirect('index.php?r=site/index');
        }
        
        $memberfilter = new MemberSearchFilter();
        $subsector = new SubSector();
        $subsectors = array();
        $subsectors[] = $subsector;
        
        if (isset($_REQUEST["MemberSearchFilter"]))
        {
            $memberfilter->sector = $_REQUEST["MemberSearchFilter"]["sector"];
            $memberfilter->subsector = $_REQUEST["MemberSearchFilter"]["subsector"];
            $subsectors = SubSector::find()->where("sector = '" . $memberfilter->sector . "'")->orderBy(['name' => SORT_ASC])->all();
        }
        
        $sectors = Sector::find()->orderBy(['name' => SORT_ASC])->all();
        $sectors = ArrayHelper::map($sectors, 'id', 'name');
        $subsectors = ArrayHelper::map($subsectors, 'id', 'name');
        
        $searchModel = new MemberSearch();
        $dataProvider = $searchModel->searchByFilter($memberfilter);

        return $this->render('profiles', [
            'dataProvider' => $dataProvider,
            'filtermodel' => $memberfilter,
            'sectors' => $sectors,
            'subsectors' => $subsectors
        ]);
    }
    
    public function actionMailinglist()
    { 
        if(Yii::$app->user->isGuest && Yii::$app->user->identity->getAdminStatus() != "yes")
        {
            return $this->redirect('index.php?r=site/index');
        }
        
        $memberfilter = new MemberSearchFilter();
        $subsector = new SubSector();
        $subsectors = array();
        $subsectors[] = $subsector;
        
        if (isset($_REQUEST["MemberSearchFilter"]))
        {
            $memberfilter->sector = $_REQUEST["MemberSearchFilter"]["sector"];
            $memberfilter->subsector = $_REQUEST["MemberSearchFilter"]["subsector"];
            $subsectors = SubSector::find()->where("sector = '" . $memberfilter->sector . "'")->orderBy(['name' => SORT_ASC])->all();
        }
        
        $sectors = Sector::find()->orderBy(['name' => SORT_ASC])->all();
        $sectors = ArrayHelper::map($sectors, 'id', 'name');
        $subsectors = ArrayHelper::map($subsectors, 'id', 'name');
        
        $searchModel = new MemberSearch();
        $dataProvider = $searchModel->searchByFilterOnlyConfirmed($memberfilter);
        return $this->render('mailinglist', [
            'dataProvider' => $dataProvider,
            'filtermodel' => $memberfilter,
            'sectors' => $sectors,
            'subsectors' => $subsectors
        ]);
    }    
    
    public function actionProfile($id)
    {
        if(Yii::$app->user->isGuest)
        {
            return $this->redirect('index.php?r=site/index');
        }
        
        $experience=MemberExperience::find()->where(['user_id' => $id]) -> all();
        $education=MemberEducation::find()->where(['user_id' => $id]) -> all();
        $sectors=MemberSector::find()->where(['member' => $id])->all();
        $subsectors=MemberSubSector::find()->where(['member'=>$id])->all();
        $specializations=MemberSpecialization::find()->where(['member'=>$id])->all();
        $interests=MemberInterest::find()->where(['member'=>$id])->all();
        $languages=MemberLanguage::find()->where(['member'=>$id])->all();
        $projects_submitted = Canvas::find()->where(['created_by' => $id])->orderBy(['created_by' => SORT_ASC])->all();
        $projects_evaluated = MemberCanvas::find()->where(['member' => $id])->orderBy(['created_on' => SORT_ASC])->all();
        $papers= Paper::find()->where(['created_by' => $id])->all();

        return $this->render('profile', [
            'model' => $this->findModel($id),
            'experience' => $experience,
            'education' => $education,
            'sectors' => $sectors,
            'subsectors' => $subsectors,
            'specializations' => $specializations,
            'interests' => $interests,
            'languages' => $languages,
            'projects_submitted' => $projects_submitted,
            'projects_evaluated' => $projects_evaluated,
            'papers' => $papers
        ]);
    }
}
