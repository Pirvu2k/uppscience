<?php

namespace app\controllers;

use Yii;
use app\models\Canvas;
use app\models\CanvasDraft;
use app\models\CanvasSearch;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use app\models\Member;
use app\models\MemberSector;
use app\models\MemberSubSector;
use app\models\MemberCanvas;
use app\models\Sector;
use app\models\SubSector;
use app\models\CanvasActivity;
use app\models\ProjectAttachment;
use yii\data\Pagination;
use app\models\MemberLanguage;
use app\models\CanvasSearchFilter;
use yii\helpers\ArrayHelper;
use app\models\MemberAccount;
/**
 * CanvasController implements the CRUD actions for Canvas model.
 */
class CanvasController extends Controller
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



    /**
     * Lists all Canvas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CanvasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Canvas model.
     * @param integer $id
     * @return mixed
     */
    
    public function actionInvitations1($id)
    {
        if(Yii::$app->user->isGuest)
        {
            return $this->redirect('index.php?r=site/index');
        }
        
        $model = Canvas::find()->where(['id' => $id])->one();
        
        if($model->created_by != Yii::$app->user->id)
        {
            die("Invalid operation");
        }
        
        $existing_members = array();
        $existing_members["Active"] = MemberCanvas::find()->with("evaluator")->where(['project' => $model->id, 'status' => 'Active'])->all();
        $existing_members["Pending"] = MemberCanvas::find()->with("evaluator")->where(['project' => $model->id, 'status' => 'Pending'])->andWhere([">", "expiry_date", new Expression('NOW()')])->all();
        $existing_members["Expired"] = MemberCanvas::find()->with("evaluator")->where(['project' => $model->id, 'status' => 'Pending'])->andWhere(["<", "expiry_date", new Expression('NOW()')])->all();
        $existing_members["Finalized"] = MemberCanvas::find()->with("evaluator")->where(['project' => $model->id, 'status' => 'Finalized'])->all();
        
        return $this->render('invitations1', [
            'model' => $model,
            'existing_members' => $existing_members
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

        return $this->redirect(['list']);
    
    }

    public function actionInvitationslist($id)
    {
        if(Yii::$app->user->isGuest)
        {
            return $this->redirect('index.php?r=site/index');
        }
        
        $model = Canvas::find()->where(['id' => $id])->one();
        
        if($model->created_by != Yii::$app->user->id)
        {
            die("Invalid operation");
        }
        
        if(isset($_REQUEST["evaluation_request_submission"]) && 
           $_REQUEST["evaluation_request_submission"] == "1")
        {   
            $perspectives = array("Technical", "Economical", "Creative");
            
            foreach($perspectives as $perspective)
            {
                if(isset($_REQUEST[$perspective]) && 
                   is_numeric($_REQUEST[$perspective]))
                {
                    $new_member = $_REQUEST[$perspective];
                    $matching_members = array();
                    $matching_members["Active"] = MemberCanvas::find()->with("evaluator")->where(['role' => $perspective, 'project' => $model->id, 'status' => 'Active'])->all();
                    $matching_members["Pending"] = MemberCanvas::find()->with("evaluator")->where(['role' => $perspective, 'project' => $model->id, 'status' => 'Pending'])->andWhere([">", "expiry_date", new Expression('NOW()')])->all();
                    $matching_members["Finalized"] = MemberCanvas::find()->with("evaluator")->where(['role' => $perspective, 'project' => $model->id, 'status' => 'Finalized'])->all();
                    
                    if(count($matching_members["Finalized"]) > 0 ||
                       count($matching_members["Active"]) > 0)
                    {
                        continue;
                    }
                    
                    if(count($matching_members["Pending"]) > 0)
                    {
                        $do_not_create = false;

                        foreach($matching_members["Pending"] as $item2)
                        {
                            if($item2->member != $new_member)
                            {
                                $item2->status = "Cancelled";
                                $item2->update(false);
                            }
                            else
                            {
                                $do_not_create = true;
                            }
                        }
                    }
                    
                    if(count($matching_members["Pending"]) == 0 ||
                       !$do_not_create)
                    {
                        $new_invitation = new MemberCanvas();
                        $new_invitation->project = $model->id;
                        $new_invitation->member = $new_member;
                        $new_invitation->status = 'Pending';
                        $new_invitation->role = $perspective;
                        $new_invitation->expiry_date = new Expression('DATE_ADD(NOW(), INTERVAL 7 DAY)');
                        $new_invitation->save();

                        $this->SendInvitationEmail(MemberCanvas::find()
                               ->with("evaluator")->with("canvas")
                               ->where(['id' => $new_invitation->id])->one());
                    }
                }
            }
            
            return $this->redirect(['invitations1', 'id' => $model->id]);             
        }
        
        $existing_members = array();
        $existing_members["Active"] = MemberCanvas::find()->where(['project' => $model->id, 'status' => 'Active'])->all();
        $existing_members["Pending"] = MemberCanvas::find()->where(['project' => $model->id, 'status' => 'Pending'])->andWhere([">", "expiry_date", new Expression('NOW()')])->all();
        $existing_members["Expired"] = MemberCanvas::find()->where(['project' => $model->id, 'status' => 'Pending'])->andWhere(["<", "expiry_date", new Expression('NOW()')])->all();
        $existing_members["Finalized"] = MemberCanvas::find()->where(['project' => $model->id, 'status' => 'Finalized'])->all();
        
        $candidate_technical_members = array();
        $candidate_creative_members = array();
        $candidate_economical_members = array();
        
        $candidate_technical_members_alt = array();
        $candidate_creative_members_alt = array();
        $candidate_economical_members_alt = array();
        
        //Finding technical evaluators
        $candidate_members = Member::find()->where(['role' => 'Technical', 'confirmed' => 'Yes'])->andWhere(['not' , ['email' => Yii::$app->user->identity->email]])->orderBy('active_projects')->all();
        
        if(count($candidate_members) == 0)
        {
            $candidate_technical_members = null;
            $candidate_technical_members_alt = null;
        }

        foreach($candidate_members as $member)
        {
            $alternative = false;

            $language_check = MemberLanguage::find()->where(['language' => $model->language , 'member' => $member->id])->one();

            if($language_check != null)
            {
                $subsector_check = MemberSubSector::find()->where(['subsector' => $model->subsector , 'member' => $member->id])->one();

                if($subsector_check == null)
                {
                    $sector_check = MemberSector::find()->where(['sector_id' => $model->sector , 'member' => $member->id])->one();
                    $alternative = true;
                }

                if($sector_check != null || $subsector_check != null)
                {   
                    if($alternative)
                    {
                        $candidate_technical_members_alt[] = $member;
                    }
                    else
                    {
                        $candidate_technical_members[] = $member;
                    }
                } 
            }
        }
        
        //Finding economical evaluators
        $candidate_members = Member::find()->where(['role' => 'Economical', 'confirmed' => 'Yes'])->andWhere(['not' , ['email' => Yii::$app->user->identity->email]])->orderBy('active_projects')->all();
        
        if(count($candidate_members) == 0)
        {
            $candidate_economical_members = null;
            $candidate_economical_members_alt = null;
        }

        foreach($candidate_members as $member)
        {
            $alternative = false;

            $language_check = MemberLanguage::find()->where(['language' => $model->language , 'member' => $member->id])->one();
            
            if($language_check != null)
            {
                $subsector_check = MemberSubSector::find()->where(['subsector' => $model->subsector , 'member' => $member->id])->one();
               
                if($subsector_check == null)
                {                    
                    $sector_check = MemberSector::find()->where(['sector_id' => $model->sector , 'member' => $member->id])->one();
                    $alternative = true;
                }

                if($sector_check != null || $subsector_check != null)
                {   
                    if($alternative)
                    {
                        $candidate_economical_members_alt[] = $member;
                    }
                    else
                    {
                        $candidate_economical_members[] = $member;
                    }
                } 
            }
        }       
        
        //Finding creative evaluators
        $candidate_members = Member::find()->where(['role' => 'Creative', 'confirmed' => 'Yes'])->andWhere(['not' , ['email' => Yii::$app->user->identity->email]])->orderBy('active_projects')->all();
        
        if(count($candidate_members) == 0)
        {
            $candidate_creative_members = null;
            $candidate_creative_members_alt = null;
        }

        foreach($candidate_members as $member)
        {
            $alternative = false;

            $language_check = MemberLanguage::find()->where(['language' => $model->language , 'member' => $member->id])->one();

            if($language_check != null)
            {
                $subsector_check = MemberSubSector::find()->where(['subsector' => $model->subsector , 'member' => $member->id])->one();

                if($subsector_check == null)
                {
                    $sector_check = MemberSector::find()->where(['sector_id' => $model->sector , 'member' => $member->id])->one();
                    $alternative = true;
                }

                if($sector_check != null || $subsector_check != null)
                {   
                    if($alternative)
                    {
                        $candidate_creative_members_alt[] = $member;
                    }
                    else
                    {
                        $candidate_creative_members[] = $member;
                    }
                } 
            }
        }
        
        if(count($candidate_technical_members) == 0)
        {
            $candidate_technical_members = $candidate_technical_members_alt;
        }
        
        if(count($candidate_economical_members) == 0)
        {
            $candidate_economical_members = $candidate_economical_members_alt;
        }
        
        if(count($candidate_creative_members) == 0)
        {
            $candidate_creative_members = $candidate_creative_members_alt;
        }        
        
        return $this->render('invitationslist', [
            'model' => $model,
            'technical_members' => $candidate_technical_members,
            'creative_members' => $candidate_creative_members,
            'economical_members' => $candidate_economical_members,
            'existing_members' => $existing_members
        ]);
    }
    
    public function actionInvitationsrandom($id)
    {
        if(Yii::$app->user->isGuest)
        {
            return $this->redirect('index.php?r=site/index');
        }
        
        $model = Canvas::find()->where(['id' => $id])->one();
        
        if($model->created_by != Yii::$app->user->id)
        {
            die("Invalid operation");
        }
        
        $candidate_technical_members = array();
        $candidate_creative_members = array();
        $candidate_economical_members = array();
        
        $candidate_technical_members_alt = array();
        $candidate_creative_members_alt = array();
        $candidate_economical_members_alt = array();
        
        //Finding technical evaluators
        $candidate_members = Member::find()->where(['role' => 'Technical', 'confirmed' => 'Yes'])->andWhere(['not' , ['email' => Yii::$app->user->identity->email]])->orderBy('active_projects')->all();
        
        if(count($candidate_members) == 0)
        {
            $candidate_technical_members = null;
            $candidate_technical_members_alt = null;
        }

        foreach($candidate_members as $member)
        {
            $alternative = false;

            $language_check = MemberLanguage::find()->where(['language' => $model->language , 'member' => $member->id])->one();

            if($language_check != null)
            {
                $subsector_check = MemberSubSector::find()->where(['subsector' => $model->subsector , 'member' => $member->id])->one();

                if($subsector_check == null)
                {
                    $sector_check = MemberSector::find()->where(['sector_id' => $model->sector , 'member' => $member->id])->one();
                    $alternative = true;
                }

                if($sector_check != null || $subsector_check != null)
                {   
                    if($alternative)
                    {
                        $candidate_technical_members_alt[] = $member;
                    }
                    else
                    {
                        $candidate_technical_members[] = $member;
                    }
                } 
            }
        }
        
        //Finding economical evaluators
        $candidate_members = Member::find()->where(['role' => 'Economical', 'confirmed' => 'Yes'])->andWhere(['not' , ['email' => Yii::$app->user->identity->email]])->orderBy('active_projects')->all();
        
        if(count($candidate_members) == 0)
        {
            $candidate_economical_members = null;
            $candidate_economical_members_alt = null;
        }

        foreach($candidate_members as $member)
        {
            $alternative = false;

            $language_check = MemberLanguage::find()->where(['language' => $model->language , 'member' => $member->id])->one();

            if($language_check != null)
            {
                $subsector_check = MemberSubSector::find()->where(['subsector' => $model->subsector , 'member' => $member->id])->one();

                if($subsector_check == null)
                {
                    $sector_check = MemberSector::find()->where(['sector_id' => $model->sector , 'member' => $member->id])->one();
                    $alternative = true;
                }

                if($sector_check != null || $subsector_check != null)
                {   
                    if($alternative)
                    {
                        $candidate_economical_members_alt[] = $member;
                    }
                    else
                    {
                        $candidate_economical_members[] = $member;
                    }
                } 
            }
        }       
        
        //Finding creative evaluators
        $candidate_members = Member::find()->where(['role' => 'Creative', 'confirmed' => 'Yes'])->andWhere(['not' , ['email' => Yii::$app->user->identity->email]])->orderBy('active_projects')->all();
        
        if(count($candidate_members) == 0)
        {
            $candidate_creative_members = null;
            $candidate_creative_members_alt = null;
        }

        foreach($candidate_members as $member)
        {
            $alternative = false;

            $language_check = MemberLanguage::find()->where(['language' => $model->language , 'member' => $member->id])->one();

            if($language_check != null)
            {
                $subsector_check = MemberSubSector::find()->where(['subsector' => $model->subsector , 'member' => $member->id])->one();

                if($subsector_check == null)
                {
                    $sector_check = MemberSector::find()->where(['sector_id' => $model->sector , 'member' => $member->id])->one();
                    $alternative = true;
                }

                if($sector_check != null || $subsector_check != null)
                {   
                    if($alternative)
                    {
                        $candidate_creative_members_alt[] = $member;
                    }
                    else
                    {
                        $candidate_creative_members[] = $member;
                    }
                } 
            }
        }
        
        if(count($candidate_technical_members) == 0)
        {
            $candidate_technical_members = $candidate_technical_members_alt;
        }
        
        if(count($candidate_economical_members) == 0)
        {
            $candidate_economical_members = $candidate_economical_members_alt;
        }
        
        if(count($candidate_creative_members) == 0)
        {
            $candidate_creative_members = $candidate_creative_members_alt;
        }
        
        //Choosing random experts to invite
        $perspectives = array("Technical" => $candidate_technical_members, 
                              "Economical" => $candidate_economical_members, 
                              "Creative" => $candidate_creative_members);
            
        foreach($perspectives as $perspective => $members)
        {
            if(count($members) > 0)
            {
                $matching_members = array();
                $matching_members["Active"] = MemberCanvas::find()->where(['role' => $perspective, 'project' => $model->id, 'status' => 'Active'])->all();
                $matching_members["Pending"] = MemberCanvas::find()->where(['role' => $perspective, 'project' => $model->id, 'status' => 'Pending'])->all();
                $matching_members["Finalized"] = MemberCanvas::find()->where(['role' => $perspective, 'project' => $model->id, 'status' => 'Finalized'])->all();

                if(count($matching_members["Finalized"]) > 0 ||
                   count($matching_members["Active"]) > 0)
                {
                    continue;
                }
                
                $random = mt_rand (0,count($members));
                $selected_member = $members[$random];

                if(count($matching_members["Pending"]) == 0)
                {
                    if($selected_member != null && $selected_member->id != "")
                    {
                        $new_invitation = new MemberCanvas();
                        $new_invitation->project = $model->id;
                        $new_invitation->member = $selected_member->id;
                        $new_invitation->status = 'Pending';
                        $new_invitation->role = $perspective;
                        $new_invitation->expiry_date = new Expression('DATE_ADD(NOW(), INTERVAL 7 DAY)');
                        $new_invitation->save();
                    }

                    $this->SendInvitationEmail(MemberCanvas::find()
                               ->with("evaluator")->with("canvas")
                               ->where(['id' => $new_invitation->id])->one());
                }
            }
        }
        
        //Refresh after assignation
        $existing_members = array();
        $existing_members["Active"] = MemberCanvas::find()->where(['project' => $model->id, 'status' => 'Active'])->all();
        $existing_members["Pending"] = MemberCanvas::find()->where(['project' => $model->id, 'status' => 'Pending'])->andWhere([">", "expiry_date", new Expression('NOW()')])->all();
        $existing_members["Expired"] = MemberCanvas::find()->where(['project' => $model->id, 'status' => 'Pending'])->andWhere(["<", "expiry_date", new Expression('NOW()')])->all();
        $existing_members["Finalized"] = MemberCanvas::find()->where(['project' => $model->id, 'status' => 'Finalized'])->all();
        
        return $this->render('invitationsrandom', [
            'model' => $model,
            'existing_members' => $existing_members
        ]);
    }    
    
    public function actionView($id)
    {   
        if(Yii::$app->user->isGuest)
        {
            return $this->redirect('index.php?r=site/index');
        }
        
        $model = $this->findModel($id);
        
        if($model->created_by != Yii::$app->user->id)
        {
            $found = false;
            
            $canvas_members = MemberCanvas::find()
                          ->where(['project' => $id])
                          ->andWhere(['or',['status'=>'Active'],['status'=>'Finalized']])
                          ->all();

            foreach($canvas_members as $e)
            {
                if($e->member == Yii::$app->user->id)
                {
                    $found = true;
                    break;
                }
            }
            
            if(!found)
            {
                die("Invalid operation");
            }
        }

        $attachments = ProjectAttachment::find()->where(['project' => $model->id])->all();

        $activities = CanvasActivity::find()->where(['canvas' => $model->id]);
		
		$activities_pages = new Pagination(['totalCount' => $activities->count(), 'pageSize'=>5, 'pageParam' => 'activities']);
		
		$activities = $activities->offset($activities_pages->offset)
			->orderBy(['created_on' => SORT_DESC])
			->limit($activities_pages->limit)
			->all();

        $memberCanvasRecord = '';

        $scoreModel = new \app\models\ScoreForm();

        $noteModel = new \app\models\NoteForm();

        $member = Member::find()->where(['id' => $model->created_by])->one();

        $canvas_members = MemberCanvas::find()
                          ->where(['project' => $id])
                          ->andWhere(['or',['status'=>'Active'],['status'=>'Finalized']])
                          ->all();

        $sector = Sector::find()->where(['id' => $model->sector])->one()->name;

        $subsector = SubSector::find()->where(['id' => $model->subsector])->one()->name;

        $members=[];

        foreach($canvas_members as $e)
        {
            $member= Member::find()->where(['id' => $e->member])->one();

            if($e->member == Yii::$app->user->id)
            {
                $memberCanvasRecord = $e;
            }

            /* if(!empty($member->given_name) && !empty($member->family_name))
            {
                $member = $member->given_name . ' ' . $member->family_name;
            }
            else $member = $member->email; */
            
            array_push($members, $member);
        }        

        if ($model->load(Yii::$app->request->post()) && isset($_REQUEST["score_submission"]))
        {   
            $model->update(false);
            return $this->refresh();
        }
        
        if (isset($_REQUEST["evaluation_submission"]))
        {
            if($memberCanvasRecord->role == "Technical")
            {
                $model->overall_technical = ($model->partners_score + $model->tech_resources_score + $model->risk_score + $model->impact_score) / 4;
            }
            
            if($memberCanvasRecord->role == "Economical")
            {
                $model->overall_economical = ($model->fin_resources_score + $model->customers_score + $model->generate_score) / 3;
            }
            
            if($memberCanvasRecord->role == "Creative")
            {
                $model->overall_creative = ($model->selling_score + $model->outstanding_score + $model->benefits_score + $model->marketed_score) / 4;
            }
            
            $model->update(false);
            
            $memberCanvasRecord->status = "Finalized";
            $memberCanvasRecord->update(false);            
            
            if($model->overall_technical >= 0 &&
               $model->overall_economical >= 0 &&
               $model->overall_creative >= 0)
            {
                $model->status = 'Evaluation complete';
                $model->update(false);
            }
            
            return $this->refresh();
        }

        if ($noteModel->load(Yii::$app->request->post()) && $noteModel->validate()){ // add note

            $activity=new CanvasActivity();
            $activity->added_by = Yii::$app->user->id;
            $activity->added_by_type = (Yii::$app->user->identity->type=='s' ? 'Member' : 'Member');
            $activity->canvas = $model->id;
            $activity->activity_text = $noteModel->note ;
            $activity->action_type = 'Note';
            $activity->save();

            return $this->refresh();

        }

        return $this->render('view', [
            'model' => $model,
            'member' => $member,
            'members' => $members,
            'sector' => $sector,
            'subsector' => $subsector,
            'scoreModel' => $scoreModel,
            'noteModel' => $noteModel,
            'memberCanvasRecord' => $memberCanvasRecord,
            'activities' => $activities,
            'activities_pages' => $activities_pages,
            'attachments' => $attachments,
        ]);
    }
    
    public function actionPrecreate()
    {
        if(Yii::$app->user->isGuest)
        {
            return $this->redirect('index.php?r=site/index');
        }
        
        $user = MemberAccount::findOne(Yii::$app->user->id);
        return $this->render('precreate', 
        [
            'user' => $user
        ]);
    }
    
    public function actionCreate_subsectorlist($sector)
    {
        
    }

    /**
     * Creates a new Canvas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    
    public function actionCreate()
    {
        if(Yii::$app->user->isGuest)
        {
            return $this->redirect('index.php?r=site/index');
        }

        $model = new Canvas();
        
        if(isset($_REQUEST["mode"]) && $_REQUEST["mode"] == "submit")
        {
            $model->scenario = 'submit';
        }
        else
        {
            $model->scenario = 'draft';
        }

        if ($model->load(Yii::$app->request->post())) 
        {
          $model->date_added = new Expression('NOW()');
          $model->date_modified = new Expression('NOW()');
          $model->created_by= Yii::$app->user->id;
          
            $model->files = UploadedFile::getInstances($model, 'files');

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
               
                $model->upload();
                $model->files = null;

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
     * Updates an existing Canvas model.
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

        if(is_null(Canvas::find()->where(['created_by' => Yii::$app->user->id , 'id' => $model->id ,'status' => 'Draft'])->one()))
        {
            return $this->render('/site/error', ['message' => 'This project is currently evaluated or you are not part of it.' , 'name' => 'Error']);
        }

        if ($model->load(Yii::$app->request->post())) 
        {
            $model->date_modified = new Expression('NOW()');
          
            $model->files = UploadedFile::getInstances($model, 'files');

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
               
                $model->upload();
                $model->files = null;

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
        
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Canvas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Canvas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Canvas::findOne($id)) !== null) 
        {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function findModelDraft($id)
    {
        if (($model = CanvasDraft::findOne($id)) !== null) 
        {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }    

    public function findMembers($model)
    {       
        $technical_members = Member::find()->where(['role' => 'Technical', 'confirmed' => 'Yes'])->andWhere(['not' , ['email' => Yii::$app->user->identity->email]])->orderBy('active_projects')->all();

        $candidate_technical_members = null;

        $exist1 = true;
        $exist2 = true;
        $exist3 = true;

        $tech_exist = MemberCanvas::find()->where([ 'role' => 'Technical' , 'project' => $model->id])->one(); // check if there's already a technical member for this canvas

        if($tech_exist == NULL) {

            foreach($technical_members as $member)
            {      
                    $sector_check = MemberSector::find()->where(['sector_id' => $model->sector , 'member' => $member->id])->one();

                    $language_check = MemberLanguage::find()->where(['language' => $model->language , 'member' => $member->id])->one();

                    if($sector_check != NULL && $language_check != NULL)
                        {   

                            $exist1=true;
                            $linkid = MemberCanvas::find()->orderBy(['id' => SORT_DESC])->one()->id;

                            $id = openssl_encrypt($linkid+1, 'AES-128-ECB', 'n9vwoxd1mv1mf8ka');

                            $check=MemberCanvas::find()->where(['member' => $member->id , 'project' => $model->id])->one();

                            if(!empty($member->given_name)) $name=$member->given_name;
                                else $name = $member->email;

                            if(is_null($check)){

                                $url = Yii::$app->urlManager->createAbsoluteUrl(
                                        ['canvas/view','id'=>$model->id]);

                                $confirmation_url = Yii::$app->urlManager->createAbsoluteUrl(
                                                    ['canvas/confirm','id'=>$id]);

                                Yii::$app->mailer->compose('invitation_mail' , ['title' => $model->title , 'summary' => $model->eng_summary, 'url' =>$url , 'confirmation_url' => $confirmation_url , 'name' => $name])
                                    ->setTo($member->email)
                                    ->setFrom('cop@viscontiproject.eu')
                                    ->setSubject('You received an invitation for a project!')
                                    ->send();
                            }

                            $record = new MemberCanvas();
                            $record->project = $model->id;
                            $record->member = $member->id;
                            $record->status = 'Pending';
                            $record->role = 'Technical';
                            $record->expiry_date = new Expression('DATE_ADD(NOW(), INTERVAL 14 DAY)');
                            $record->save();




                            break;
                        } 
                    else $exist1 = false;
                }
        }

        $economical_members = Member::find()->where(['role' => 'Economical', 'confirmed' => 'Yes'])->andWhere(['not' , ['email' => Yii::$app->user->identity->email]])->orderBy('active_projects')->all();

        if($economical_members == NULL) return false;

        $econ_exist = MemberCanvas::find()->where([ 'role' => 'Economical' , 'project' => $model->id])->one(); // check if canvas has economical member already assigned

        if($econ_exist == NULL) {

                foreach($economical_members as $member)
                    {      
                        $sector_check = MemberSector::find()->where(['sector_id' => $model->sector , 'member' => $member->id])->one();

                        $language_check = MemberLanguage::find()->where(['language' => $model->language , 'member' => $member->id])->one();

                        if($sector_check != NULL && $language_check != NULL)
                            {   
                                $exist2=true;

                                $linkid = MemberCanvas::find()->orderBy(['id' => SORT_DESC])->one()->id;

                                $id = openssl_encrypt($linkid+1, 'AES-128-ECB', 'n9vwoxd1mv1mf8ka');

                                $check=MemberCanvas::find()->where(['member' => $member->id , 'project' => $model->id])->one();

                                if(!empty($member->given_name)) $name=$member->given_name;
                                    else $name = $member->email;

                                if(is_null($check)){

                                    $url = Yii::$app->urlManager->createAbsoluteUrl(
                                            ['canvas/view','id'=>$model->id]);

                                    $confirmation_url = Yii::$app->urlManager->createAbsoluteUrl(
                                                        ['canvas/confirm','id'=>$id]);

                                    Yii::$app->mailer->compose('invitation_mail' , ['title' => $model->title , 'summary' => $model->eng_summary, 'url' =>$url , 'confirmation_url' => $confirmation_url , 'name' => $name])
                                        ->setTo($member->email)
                                        ->setFrom('cop@viscontiproject.eu')
                                        ->setSubject('You received an invitation for a project!')
                                        ->send();
                                }

                                $record = new MemberCanvas();
                                $record->project = $model->id;
                                $record->member = $member->id;
                                $record->status = 'Pending';
                                $record->role = 'Economical';
                                $record->expiry_date = new Expression('DATE_ADD(NOW(), INTERVAL 14 DAY)');
                                $record->save();

                                break;
                            }
                        else {$exist2=false;} 
                    }
        }

        $creative_members = Member::find()->where(['role' => 'Creative', 'confirmed' => 'Yes'])->andWhere(['not' , ['email' => Yii::$app->user->identity->email]])->orderBy('active_projects')->all();

        if($creative_members == NULL) {return false;echo '1';die();}

        $creative_exist = MemberCanvas::find()->where([ 'role' => 'Creative' , 'project' => $model->id])->one(); // check if canvas has creative member already assigned

        if($creative_exist == NULL) 
        {
            foreach($creative_members as $member)
            { 
                $sector_check = MemberSector::find()->where(['sector_id' => $model->sector , 'member' => $member->id])->one();

                $language_check = MemberLanguage::find()->where(['language' => $model->language , 'member' => $member->id])->one();

                if($sector_check != NULL && $language_check != NULL)
                {   
                    $exist3=true;
                    $linkid = MemberCanvas::find()->orderBy(['id' => SORT_DESC])->one()->id;

                    $id = openssl_encrypt($linkid+1, 'AES-128-ECB', 'n9vwoxd1mv1mf8ka');

                    $check=MemberCanvas::find()->where(['member' => $member->id , 'project' => $model->id])->one();

                    if(!empty($member->given_name)) $name=$member->given_name;
                        else $name = $member->email;

                    if(is_null($check)){

                        $url = Yii::$app->urlManager->createAbsoluteUrl(
                                ['canvas/view','id'=>$model->id]);

                        $confirmation_url = Yii::$app->urlManager->createAbsoluteUrl(
                                            ['canvas/confirm','id'=>$id]);

                        Yii::$app->mailer->compose('invitation_mail' , ['title' => $model->title , 'summary' => $model->eng_summary, 'url' =>$url , 'confirmation_url' => $confirmation_url , 'name' => $name])
                            ->setTo($member->email)
                            ->setFrom('cop@viscontiproject.eu')
                            ->setSubject('You received an invitation for a project!')
                            ->send();
                    }

                    $record = new MemberCanvas();
                    $record->project = $model->id;
                    $record->member = $member->id;
                    $record->status = 'Pending';
                    $record->role = 'Creative';
                    $record->expiry_date = new Expression('DATE_ADD(NOW(), INTERVAL 14 DAY)');
                    $record->save();

                    break;
                }
                else 
                {
                    $exist3 = false;
                }  
            }
        }
        if(!$exist1 || !$exist2 || !$exist3)
            return false;
        else
            return true;
    }
    
    public function actionFindmembersbulk()
    {       
        $canvas_list = Canvas::find(['overall_technical' => null]);
        
        foreach($canvas_list as $model)
        {
            $this->findMembers($model);
        }
    }    

    public function actionConfirm($id){ // $id - id of project-canvas record

        $record = MemberCanvas::find()->where(['id' => openssl_decrypt($id, 'AES-128-ECB', 'n9vwoxd1mv1mf8ka') , 'status' => 'Pending' ])->one();

        if($record == NULL)
        {
            Yii::$app->getSession()->setFlash('warning','Invalid confirmation link or link has been already accessed.');
            return $this->redirect('index.php?r=site/confirmation');
        }

        else {
            $transaction=MemberCanvas::getDb()->beginTransaction();
            try {
                $record->status = 'Active';
                $record->update();

                $activity=new CanvasActivity();
                $activity->added_by = $record->member;
                $activity->added_by_type = 'Member';
                $activity->canvas = $record->project;
                $activity->activity_text = 'Invitation for project review accepted' ;
                $activity->action_type = 'Acceptance';
                $activity->save();

                $pending_member = MemberCanvas::find()->where(['project' => $record->project , 'status' => 'Pending'])->one();

                $members_on_project = MemberCanvas::find()->where(['project' => $record->project])->count();

                if(is_null($pending_member) && $members_on_project == 3) // all members accepted, need to change state of project
                {
                    $canvas_record=Canvas::find()->where(['id' => $record->project])->one(); // get canvas record
                    $canvas_record->status = 'Expert evaluation in progress';  
                    $update = $canvas_record->update();
                    if (!empty($canvas_record->errors)) {
                        throw new Exception("Could not update canvas");
                    }
                }

                $member_record = Member::find()->where(['id' => $record->member])->one();
                $member_record->active_projects++;
                $member_record->update();
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollback();
            }


            Yii::$app->getSession()->setFlash('success','Project accepted successfully.');
            Yii::$app->getSession()->setFlash('link',Html::a('Click to go to project' , Yii::$app->urlManager->createAbsoluteUrl(
                                        ['canvas/view','id'=>$record->project]) ));
            return $this->redirect('index.php?r=site/confirmation');
        }

    }
    
    public function actionList()
    { 
        if(Yii::$app->user->isGuest)
        {
            return $this->redirect('index.php?r=site/index');
        }
        
        $canvasfilter = new CanvasSearchFilter();
        $subsector = new SubSector();
        $subsectors = array();
        $subsectors[] = $subsector;
        
        if (isset($_REQUEST["CanvasSearchFilter"]))
        {
            $canvasfilter->sector = $_REQUEST["CanvasSearchFilter"]["sector"];
            $canvasfilter->subsector = $_REQUEST["CanvasSearchFilter"]["subsector"];
            $subsectors = SubSector::find()->where("sector = '" . $canvasfilter->sector . "'")->orderBy(['name' => SORT_ASC])->all();
        }
        
        $sectors = Sector::find()->orderBy(['name' => SORT_ASC])->all();
        $sectors = ArrayHelper::map($sectors, 'id', 'name');
        $subsectors = ArrayHelper::map($subsectors, 'id', 'name');
        
        $searchModel = new CanvasSearch();
        $dataProvider = $searchModel->searchByFilter($canvasfilter);
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'filtermodel' => $canvasfilter,
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
        
        $model = $this->findModel($id);
        
        $attachments = ProjectAttachment::find()->where(['project' => $model->id])->all();

        $activities = CanvasActivity::find()->where(['canvas' => $model->id]);
		
        $activities_pages = new Pagination(['totalCount' => $activities->count(), 'pageSize'=>20, 'pageParam' => 'activities']);

        $activities = $activities->offset($activities_pages->offset)
                ->orderBy(['created_on' => SORT_DESC])
                ->limit($activities_pages->limit)
                ->all();

        $scoreModel = new \app\models\ScoreForm();

        $noteModel = new \app\models\NoteForm();

        $member = Member::find()->where(['id' => $model->created_by])->one();

        $canvas_members = MemberCanvas::find()
                  ->where(['project' => $model->id])
                  ->andWhere([">", "expiry_date", new Expression('NOW()')])
                  ->andWhere(["<>", "status", "Cancelled"])
                  ->all();

        $sector = Sector::find()->where(['id' => $model->sector])->one()->name;

        $subsector = SubSector::find()->where(['id' => $model->subsector])->one()->name;       

        return $this->render('profile', [
            'model' => $model,
            'member' => $member,
            'members' => $canvas_members,
            'sector' => $sector,
            'subsector' => $subsector,
            'scoreModel' => $scoreModel,
            'noteModel' => $noteModel,
            'activities' => $activities,
            'activities_pages' => $activities_pages,
            'attachments' => $attachments,
        ]);
    }    
    
    private function SendInvitationEmail($member_canvas)
    {
        $name = $member_canvas->evaluator->given_name . " " . $member_canvas->evaluator->family_name;
        $id = openssl_encrypt($member_canvas->id, 'AES-128-ECB', 'n9vwoxd1mv1mf8ka');
        $url = Yii::$app->urlManager->createAbsoluteUrl(
                ['canvas/profile','id'=>$member_canvas->project->id]);

        $confirmation_url = Yii::$app->urlManager->createAbsoluteUrl(
                            ['canvas/confirm','id'=>$id]);

        Yii::$app->mailer->compose('invitation_mail' , ['title' => $member_canvas->project->title , 'summary' => $member_canvas->project->eng_summary, 'url' =>$url , 'confirmation_url' => $confirmation_url , 'name' => $name])
            ->setTo($member_canvas->evaluator->email)
            ->setFrom('cop@viscontiproject.eu')
            ->setSubject('An invitation to evaluate a project')
            ->send();
    }
}
