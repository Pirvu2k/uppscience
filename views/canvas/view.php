<?php

use yii\helpers\Html;
use app\models\MemberCanvas;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */
/* @var $model app\models\Canvas */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Canvas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$tech_check=false;
$creative_check=false;
$economical_check=false;

?>
<div class="canvas-view">
    
<div id="content">
        <main id="new" class="container">
            <div class="row">
                <h3><?= $model->title ?> 
                         <div class="pull-right">

                         <?php
                            
                            $record = MemberCanvas::find()->where(['member'=>Yii::$app->user->id , 'project' => $model->id , 'status' => 'Pending'])->one(); 

                            if(Yii::$app->user->identity->type == 'e' && !is_null($record))
                            {
                                
                                echo Html::a('Accept Project', ['confirm', 'id' => openssl_encrypt($record->id, 'AES-128-ECB', 'n9vwoxd1mv1mf8ka')], ['class' => 'btn btn-info']);
                            }

                             if($model->status == 'Submitted' && Yii::$app->user->identity->type == 's' && $model->created_by == Yii::$app->user->id)
                            {

                                echo Html::a('Find Evaluators', ['update', 'id' => $model->id], ['class' => 'btn btn-success']);
    
                            }

                            
                        ?>
                            
                            </div>
                    </h3>
                 
                <?php
                    if($model->status == 'Submitted' && Yii::$app->user->identity->type == 's' && $model->created_by == Yii::$app->user->id){
                        echo '<div class="alert alert-danger">
                                <strong>Attention!</strong> Your project is not evaluated on all domains! Press the \'Find Evaluators \' button to search for available members and do your last-minute changes on the project.
                            </div>';
                    }
                ?>
                <hr class="colorgraph">
                <h3>Summary (in English)</h3>
                <?= $model->eng_summary ?>
                <hr class="colorgraph">
                <h3>Summary</h3>
                <?= $model->content ?>
                <hr class="colorgraph">
                <h3>Project status</h3>
                <div class="row">
                    <div class="col-xs-6 col-md-2">
                        <b>Submitted by</b>
                        </br>
                        <?php
                            echo '<a href="index.php?r=member/profile&id='. $model->created_by . '">' . $model->owner->given_name . ' ' . $model->owner->family_name . '</a>';
                        ?>
                        </br>
                        <b>Language</b>
                        </br>
                        <?php
                            echo $model->language;
                        ?>
                    </div>
                    <div class="col-xs-6 col-md-2">
                        <b>Evaluators</b>
                        </br>
                        <?php
                            foreach($members as $e)
                            {
                                if(!empty($e->given_name) && !empty($e->family_name))
                                    {
                                        $ename = $e->given_name . ' ' . $e->family_name;
                                    }
                                else $ename = $e->email;
                                echo '<a href="index.php?r=member/profile&id='. $e->id . '"><p>' . $ename . '</p></a>';
                            } 
                            
                            if(empty($members)) 
                            {
                                echo 'No evaluators available';
                            }
                            
                            if($model->created_by == Yii::$app->user->id)
                            {
                                echo '<br /><a href="index.php?r=canvas/invitations1&id='. $model->id . '"><b>Manage Evaluators</b></a>';
                            }
                         ?>
                    </div>
                    <div class="col-xs-6 col-md-2">
                        <b>Attachments</b>
                        </br>
                        <?php
                            foreach($attachments as $attachment)
                            {
                        ?>
                                <a href="uploads/<?= $attachment->attachment_name ?>" target="_blank"> <p style="word-break:break-all;"> <?= $attachment->attachment_name ?></p> </a>
                        <?php
                            } if (empty($attachments)) echo '<p> No attachments. </p>';
                        ?>
                    </div>
                    <div class="col-xs-6 col-md-3">
                        <b>Stage</b>
                        </br>
                        <div class="pull-left">
                            <?= $model->status ?>
                        </div>
                    </div>

                    <div class="col-xs-6 col-md-3">
                        <b>Classification</b>
                           <p> <b>Sector:</b> <?= $sector ?>
                           <br />
                           <b>Sub-sector:</b> <?= $subsector ?> </p>
                        </div>
                    </div>
                </div>
                </br>

            </div>                
                <div class="row">
                    <hr class="colorgraph">
                </div>

                <div class="row">

                <div class="col-xs-6 col-md-4">
                        <h4> Overall Technical Score: <b><?= ($model->overall_technical < 0 ? 'Not set' : $model->overall_technical) ?></b></h4> 
                </div>

                <div class="col-xs-6 col-md-4">
                        <h4> Overall Economics Score: <b><?= ($model->overall_economical < 0 ? 'Not set' : $model->overall_economical) ?></b></h4> 
                </div>

                <div class="col-xs-6 col-md-4">
                        <h4> Overall Creative Score: <b><?= ($model->overall_creative < 0 ? 'Not set' : $model->overall_creative) ?></b></h4> 
                </div>
            
                </div>

                <div class="row">
                    <hr class="colorgraph">
                </div>

                <?php $form = ActiveForm::begin(['enableClientValidation' => true]); ?>
                <div class="row">
                    <h3> Creativity Aspects </h3>
                    <br>
                    <ul style="list-style-type:none;">
                        <?php if(!empty($model->selling)) { $creative_check=true; ?>
                        <li>
                            <h4><b>What creativity or innovation will the project bring about?</b></h4>

                            <p style="word-break:break-all"> <?= $model->selling ?> </p>
                            <?php PlotScoreForm($model, $memberCanvasRecord, "selling", "Creative", $form); ?>
                        </li>
                        <?php
                            }
                        ?>
                        <?php if(!empty($model->outstanding)) { $creative_check=true; ?>
                        <li>
                            <h4><b> Why is your project special? </b></h4>

                            <p style="word-break:break-all"> <?= $model->outstanding ?> </p>
                            <?php PlotScoreForm($model, $memberCanvasRecord, "outstanding", "Creative", $form); ?>
                        </li>
                        <?php
                            }
                        ?>
                        <?php if(!empty($model->benefits)) { $creative_check=true; ?>
                        <li>
                            <h4><b>  What are the benefits of your project?</b> </h4>

                            <p style="word-break:break-all"> <?= $model->benefits ?> </p>
                            <?php PlotScoreForm($model, $memberCanvasRecord, "benefits", "Creative", $form); ?>
                        </li>
                        <?php
                            }
                        ?>
                        <?php if(!empty($model->marketed)) { $creative_check=true; ?>
                        <li>
                            <h4><b>  How can it be promoted? (facebook, web site…) </b></h4>

                            <p style="word-break:break-all"> <?= $model->marketed ?> </p>
                            <?php PlotScoreForm($model, $memberCanvasRecord, "marketed", "Creative", $form); ?>
                        </li>
                        <?php
                            } if (!$creative_check) echo '<h4> Not completed by student. </h4>';
                        ?>
                    </ul>
                </div>
                <?php PlotScoreSubmissionButton($model, $memberCanvasRecord, "Creative") ?>
                <div class="row">
                    <h3> Technical Aspects </h3>
                    <br>
                    <ul style="list-style-type:none;">
                        <?php if(!empty($model->partners)) { $tech_check=true; ?>
                        <li>
                            <h4><b>What technical resources/key activities would be required? (e.g PC, Server, Robots, Internet Service)</b></h4>

                            <p style="word-break:break-all"> <?= $model->partners ?> </p>
                            <?php PlotScoreForm($model, $memberCanvasRecord, "partners", "Technical", $form); ?>
                        </li>
                        <?php
                            }
                        ?>
                        <?php if(!empty($model->tech_resources)) { $tech_check=true; ?>
                        <li>
                            <h4><b> What external help do you need to bring your project technically possible? </b></h4>
                            <p style="word-break:break-all"> <?= $model->tech_resources?> </p>
                            <?php PlotScoreForm($model, $memberCanvasRecord, "tech_resources", "Technical", $form); ?>
                        </li>
                        <?php
                            }
                        ?>
                        <?php if(!empty($model->risk)) { $tech_check=true; ?>
                        <li>
                            <h4><b> What technical challanges may put your projects at risk? </b> </h4>

                            <p style="word-break:break-all"> <?= $model->risk ?> </p>
                            <?php PlotScoreForm($model, $memberCanvasRecord, "risk", "Technical", $form); ?>
                        </li>
                        <?php
                            }
                        ?>
                        <?php if(!empty($model->impact)) { $tech_check=true; ?>
                        <li>
                            <h4><b>  Does it have any social or environmntal impact? </b></h4>

                            <p style="word-break:break-all"> <?= $model->impact ?> </p>
                            <?php PlotScoreForm($model, $memberCanvasRecord, "impact", "Technical", $form); ?>
                        </li>
                        <?php
                            } if (!$tech_check) echo '<h4> Not completed by student. </h4>';
                        ?>
                    </ul>
                </div>
                <?php PlotScoreSubmissionButton($model, $memberCanvasRecord, "Technical") ?>
                <div class="row">
                    <h3> Economics Aspects </h3>
                    <br>
                    <ul style="list-style-type:none;">
                        <?php if(!empty($model->fin_resources)) { $economical_check=true; ?>
                        <li>
                            <h4><b>Which are the costs of the project?</b></h4>

                            <p style="word-break:break-all"> <?= $model->fin_resources ?> </p>
                            <?php PlotScoreForm($model, $memberCanvasRecord, "fin_resources", "Economical", $form); ?>
                        </li>
                        <?php
                            }
                        ?>
                        <?php if(!empty($model->customers)) { $economical_check=true; ?>
                        <li>
                            <h4><b> Who could pay for buying or using your product/service? </b></h4>

                            <p style="word-break:break-all"> <?= $model->customers ?> </p>
                            <?php PlotScoreForm($model, $memberCanvasRecord, "customers", "Economical", $form); ?>
                        </li>
                        <?php
                            }
                        ?>
                        <?php if(!empty($model->generate)) { $economical_check=true; ?>
                        <li>
                            <h4><b> Will the project create income? How? </b> </h4>

                            <p style="word-break:break-all"> <?= $model->generate ?> </p>
                            <?php PlotScoreForm($model, $memberCanvasRecord, "generate", "Economical", $form); ?>
                        </li>
                        <?php
                            } if (!$economical_check) echo '<h4> Not completed by student. </h4>';
                        ?>
                    </ul>
                </div>
                <?php PlotScoreSubmissionButton($model, $memberCanvasRecord, "Economical") ?>
    
    <?php ActiveForm::end(); ?>
    
    <div class='row'>
        <?php PlotFinalizeEvaluation($model, $memberCanvasRecord) ?>
    </div>

            <div class="row">
                    <hr class="colorgraph">
                    <h2> Add note </h2>

                    <?php $form=ActiveForm::begin(['enableClientValidation' => true]); ?>

                    <?= $form->field($noteModel, 'note')->textArea(['placeholder' => '10-255 Characters' , 'rows' => 3]); ?>

                    <div class="form-group">
                         <?= Html::submitButton('Add Note', ['class' => 'btn btn-info' , 'data-confirm' => 'Are you sure you want to add this note?It can\'t be removed afterwards.']); ?>
                    </div>

                    <?php ActiveForm::end(); ?>
            </div>

            <div class="row">

                <h2>Activities</h2>

                <ul class="col-md-10 col-sm-9 feed">
                    <?php
                        foreach($activities as $activity) {
                    ?>
                    <li>
                        <h2 class="col-sm-12"><a href="#"><?= $activity->action_type ?></a></h2>	
		
			<span class="pull-right"><time>At <?= $activity->created_on ?> by <a href=""><?= $activity->getName() ?> </a></time></span>

			<div class="clearfix visible-*"></div>

                        <div class="col-sm-12">
                            <data style="word-break:break-all;"><b><?= $activity->activity_text ?></b>
                            </data>
                        </div>
						<div class="clearfix visible-*"></div>
                    </li>
                    <?php
                    }
					echo LinkPager::widget([
						'pagination' => $activities_pages,
					]);
					if (empty($activities)) echo 'No activities/actions to show.';
                    ?>
                    
                    <!-- <button class="btn orange">Log activity/action</button> -->

                </ul>

            </div>
            <div class="row">
            <?php
            echo \spanjeta\comments\CommentsWidget::widget(['model'=>$model]); 
            ?>
            </div>
        </main>
    </div>

<?php
function PlotScoreForm($model, $memberCanvasRecord, $field, $role, $form)
{ 
    if($memberCanvasRecord->role == $role &&
       !empty($memberCanvasRecord))
    {
        if($memberCanvasRecord->status == 'Active' && $model->status != "Evaluation complete")
        {
            echo '<div class="row">';
            echo '<div class="col-md-2">';
            echo "<input type='hidden' name='score_submission' value='" . $field . "_score' />";
            echo $form->field($model , $field . "_score")->textInput(['placeholder' => '0-100']);
            echo "</div>"; 
            echo "</div>"; 
        } 
        else
        {
            echo '<div class="row"><h4>The score for this question:<b> '. $model->attributes[$field . "_score"] .'</b></h4></div>'; 
        }
    }
}

function PlotScoreSubmissionButton($model, $memberCanvasRecord, $role)
{
    if($memberCanvasRecord->role == $role &&
       !empty($memberCanvasRecord))
    {
        if($memberCanvasRecord->status == 'Active' && $model->status != "Evaluation complete")
        {
            $form = ActiveForm::begin(['enableClientValidation' => true]);
            echo '<div class="row">';
            echo '<div class="col-md-2">';
            echo Html::submitButton('Submit Score', ['class' => 'btn btn-info' , 'data-confirm' => 'Please confirm your score before submission']);
            echo "</div>"; 
            echo "</div><br />"; 
        } 
    }    
}

function PlotFinalizeEvaluation($model, $memberCanvasRecord)
{ 
    $type = "overall_" . strtolower($memberCanvasRecord->role);
    $score = $model->attributes[$type];
    
    if(!empty($memberCanvasRecord) && 
       $memberCanvasRecord->status == 'Active' &&
       $score == -1)
    {
        if($model->status != "Evaluation complete")
        {
            $form = ActiveForm::begin(['enableClientValidation' => true]);
            echo '<div class="row">';
            echo "<input type='hidden' name='evaluation_submission' value='1' />";
            echo '<div class="col-md-12">';
            echo Html::submitButton('Submit Evaluation', ['class' => 'btn btn-info' , 'data-confirm' => 'Please confirm before submitting your evaluation, you will not be able to change scores after submission']);
            echo "</div>"; 
            echo "</div>"; 
            ActiveForm::end();
        }
    }
}
