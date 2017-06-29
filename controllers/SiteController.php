<?php

namespace app\controllers;

use Yii;
use app\models\Paper;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\ForgotForm;
use app\models\RecoveryForm;
use app\models\LoginFormAll;
use app\models\ContactForm;
use yii\data\Pagination;
use app\models\Canvas;
use app\models\RegistrationForm;
use app\models\Member;
use yii\db\Expression;
use app\models\SubSector;
use app\models\MemberCanvas;
use app\models\CanvasActivity;
use app\models\MemberSearch;

class SiteController extends Controller
{   
    public $defaultAction = 'welcome';

    public function behaviors()
    {
        return [
             'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout','index'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ], 
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => null,
            ],
        ];
    }

    public function actionIndex()
    {   
        //recent activities

        $activities = CanvasActivity::find()->where(['added_by' => Yii::$app->user->id , 'added_by_type' => 'Member'])->orderBy(['created_on' => SORT_DESC])->limit(5)->all();

        //ownProjects
        $ownProjectsDraft = Canvas::find()->where(['created_by'=>Yii::$app->user->id ,'status'=>'Draft'])->orWhere(['status' => 'Member evaluation requested', 'created_by'=>Yii::$app->user->id])
                                     ->orderBy(['date_added' => SORT_DESC])
                                     ->all();
        
        $ownProjects = Canvas::find()->where(['created_by'=>Yii::$app->user->id ,'status'=>'Submitted'])->orWhere(['status' => 'Member evaluation requested', 'created_by'=>Yii::$app->user->id])
                             ->orderBy(['date_added' => SORT_DESC])
                             ->all();

        //memberAcceptedProjects
        $memberAcceptedProjects =  Canvas::find()->where(['status' => 'Expert evaluation in progress'])->andWhere(['created_by' => Yii::$app->user->id])
                                                 ->orderBy(['date_added' => SORT_DESC])
                                                 ->all();

        $activities = CanvasActivity::find()->where(['added_by' => Yii::$app->user->id , 'added_by_type' => 'Member'])->orderBy(['created_on' => SORT_DESC])->limit(5)->all();

        //invitations
        $invitations=MemberCanvas::find()->with("canvas")->with("evaluator")
                                         ->where(['member' => Yii::$app->user->id , 'status' => 'Pending'])
                                         ->orderBy(['created_on' => SORT_DESC])
                                         ->all();
                            
        //acceptedProjects
        $acceptedProjects=MemberCanvas::find()->with("canvas")->with("evaluator")
                                              ->where(['member' => Yii::$app->user->id])
                                              ->andWhere(['or',['status'=>'Active'],['status'=>'Finalized']])
                                              ->orderBy(['created_on' => SORT_DESC])
                                              ->all();
            
            return $this->render('index', [
            'ownProjects' => $ownProjects,
            'ownProjectsDraft' => $ownProjectsDraft,
            'activities' => $activities,
            'memberAcceptedProjects' => $memberAcceptedProjects,
            'invitations' => $invitations,
            'activities' => $activities,
            'acceptedProjects' => $acceptedProjects,
        ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginFormAll();
        if ($model->load(Yii::$app->request->post()) && $model->login()) 
        {
            return $this->goBack();
        }
        return $this->render('loginall', [
            'model' => $model,
        ]);
    }
	
    public function actionRecovery()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RecoveryForm();
        
        return $this->render('recovery', [
            'model' => $model,
        ]);
    }
	
    public function actionForgot()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new ForgotForm();
        if ($model->load(Yii::$app->request->post()) && $model->forgot()) {
			
		   $time = date('Y-m-d H:i:s');
		   $key = openssl_encrypt(strtotime($time), 'AES-128-ECB', 'n9vwoxd1mv1mf8ka');
				
                            $check = Yii::$app->db->createCommand('SELECT reset_pass_exp_date FROM member WHERE email=:email')
                            ->bindValue(':email', $model->email)
                            ->queryColumn();

                            $datetime1 = strtotime($check[0]);
                            $datetime2 = strtotime($time);
                            $interval  = abs($datetime2 - $datetime1);
                            $minutes = round($interval / 60);

                            if($minutes>30)
                            {
                                    \Yii::$app->db->createCommand("UPDATE member SET reset_pass_exp_date=:time WHERE email=:email")
                                    ->bindValue(':time', $time)
                                    ->bindValue(':email', $model->email)
                                    ->execute();


                                    $email = \Yii::$app->mailer->compose('forgot_mail' , ['mail' => $model->email , 'key'=>$key])
                                                    ->setTo($model->email)
                                                    ->setFrom(['cop@viscontiproject.eu'])
                                                    ->setSubject('VISConti Password Reset Confirmation')
                                                    ->send();
                if($email){
                Yii::$app->getSession()->setFlash('success',"We've sent you an email describing how to reset your password.");
                }
                else{
                Yii::$app->getSession()->setFlash('warning','Registration failed , please contact admin.');
                }
                return $this->redirect('index.php?r=site/forgot');

                            }
                            else
                            {
                Yii::$app->getSession()->setFlash('error', 'E-mail already sended. You can send a new request in '.(30-$minutes).' minutes.');
                return $this->redirect('index.php?r=site/forgot');
                            }
		   
		}
		//else print 'no';

        return $this->render('forgot', [
            'model' => $model,
        ]);
    }

     public function actionRegister()
    {
        $model = new RegistrationForm();
        
        if ($model->load(Yii::$app->request->post())) 
        {
            $member = new Member();
            $member->email = $model->email;
            $member->given_name = $model->given_name;
            $member->family_name = $model->family_name;
            $member->password = Yii::$app->getSecurity()->generatePasswordHash($model->password);
            $member->created_on = $member->last_modified_on = $member->last_login_activity = new Expression('NOW()');
            $member->generateAuthKey();
            if(!Member::findOne(['email'=>$model->email]))
            {   
                if($member->save())
                {
                                    //Ion - 17.08.2016 changed $member->id to $member->id :)
                $email = \Yii::$app->mailer->compose('confirmation_mail' , ['id' => $member->id , 'key'=>$member->auth_key])
                    ->setTo($member->email)
                    ->setFrom(['cop@viscontiproject.eu'])
                    ->setSubject('VISConti Account Confirmation')
                    /*->setTextBody("
                        In order to confirm your e-mail , please click the following link : ".
                            Yii::$app->urlManager->createAbsoluteUrl(
                                ['site/confirm','id'=>$member->id,'key'=>$member->auth_key,'type'=>'s']
                            )
                        )*/
                    ->send();
                    Yii::$app->getSession()->setFlash('success','A confirmation e-mail has been to the address you provided.');
                }
                else
                {
                    Yii::$app->getSession()->setFlash('warning','Registration failed, please try again or contact system administrator');
                }

                return $this->redirect('index.php?r=site/confirmation');
            }
            else {
                Yii::$app->getSession()->setFlash('error', 'E-mail already in use.');
                return $this->redirect('index.php?r=site/register');
            }
        } 
        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionCanvas()
    {
        if(!Yii::$app->user->can('member'))
            return $this->render('canvas1');
        else return $this->render('hello');
    }
    public function actionFaq()
    {
        return $this->render('faq');
    }
    public function actionWelcome()
    {
	if(!Yii::$app->user->isGuest)
		return $this->redirect('index.php?r=site/index');
        return $this->render('welcome');
    }

    public function actionSubsectors($id)
    {

        $subsectors = SubSector::find()->where(['sector' => $id])->all();

        if(!empty($subsectors)) {
            foreach($subsectors as $subsector)
            {
                echo "<option value='". $subsector->sector . "'>" . $subsector->name . "</option>";
            }
        }
        else {
            echo "<option>.</option>";
        }
    }

    public function actionConfirmation(){
        return $this->render('confirmation'); //page user sees after signup
    }

    public function actionConfirm($id, $key)
    {
        $user = \app\models\Member::find()->where([
            'id'=>$id,
            'auth_key'=>$key,
            'confirmed'=>'No',
            ])->one();
        
        if(!empty($user)){
        $user->confirmed='Yes';
        $user->save();
        Yii::$app->getSession()->setFlash('success','Account confirmed successfully.');
        }
        else{
        Yii::$app->getSession()->setFlash('warning','Email address confirmation failed. Please contact system administrator.');
        }
        return $this->render('confirmation');
    }
    
    public function actionMembersmembers()
    { 
        $searchModel = new MemberSearch();
        $dataProvider = $searchModel->searchAll();
        $dataProvider->pagination = new Pagination(['pageSize' => 1000]);
        return $this->renderPartial('members-members', [
            'dataProvider' => $dataProvider,
        ]);
    }
	
    protected function findPaperModel($id)
    {
        if (($model = Paper::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException("The requested page does not exist.");
        }
    }
	
    public function actionPaper($id)
    {      
        $model = $this->findPaperModel($id);

        return $this->render('paper', [
            'model' => $model
        ]);
    }
}
