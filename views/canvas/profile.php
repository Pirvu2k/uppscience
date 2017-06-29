<?php

use yii\helpers\Html;
use app\models\MemberCanvas;
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
                        ?>
                            
                            </div>
                    </h3>
                <br />
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
                                    
                                echo '<a href="index.php?r=member/profile&id='. $e->id . '"><p>' . $ename . '</p></a>';
                            } if(empty($members)) echo 'No evaluators available';
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
                            } if (empty($attachments)) echo '<p> No attachments available</p>';
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
                        <h4> Overall Technical Score: <b><?= ($model->overall_technical == -1 ? 'Not set' : $model->overall_technical) ?></b></h4> 
                </div>

                <div class="col-xs-6 col-md-4">
                        <h4> Overall Economics Score: <b><?= ($model->overall_economical == -1 ? 'Not set' : $model->overall_economical) ?></b></h4> 
                </div>

                <div class="col-xs-6 col-md-4">
                        <h4> Overall Creative Score: <b><?= ($model->overall_creative == -1 ? 'Not set' : $model->overall_creative) ?></b></h4> 
                </div>
            
                </div>

                <div class="row">
                    <hr class="colorgraph">
                </div>


                <div class="row">
                    <h3> Creativity Aspects </h3>
                    <br>
                    <ul style="list-style-type:none;">
                        <?php if(!empty($model->selling)) { $creative_check=true; ?>
                        <li>
                            <h4><b>What creativity or innovation will the project bring about?</b></h4>

                            <p style="word-break:break-all"> <?= $model->selling ?> </p>
                            <?php PlotScoreForm($model, "selling"); ?>
                        </li>
                        <?php
                            }
                        ?>
                        <?php if(!empty($model->outstanding)) { $creative_check=true; ?>
                        <li>
                            <h4><b> Why is your project special? </b></h4>

                            <p style="word-break:break-all"> <?= $model->outstanding ?> </p>
                            <?php PlotScoreForm($model, "outstanding"); ?>
                        </li>
                        <?php
                            }
                        ?>
                        <?php if(!empty($model->benefits)) { $creative_check=true; ?>
                        <li>
                            <h4><b>  What are the benefits of your project?</b> </h4>

                            <p style="word-break:break-all"> <?= $model->benefits ?> </p>
                            <?php PlotScoreForm($model, "benefits"); ?>
                        </li>
                        <?php
                            }
                        ?>
                        <?php if(!empty($model->marketed)) { $creative_check=true; ?>
                        <li>
                            <h4><b>  How can it be promoted? (facebook, web site…) </b></h4>

                            <p style="word-break:break-all"> <?= $model->marketed ?> </p>
                            <?php PlotScoreForm($model, "marketed"); ?>
                        </li>
                        <?php
                            } if (!$creative_check) echo '<h4> Not completed by student. </h4>';
                        ?>
                    </ul>
                </div>
                <div class="row">
                    <h3> Technical Aspects </h3>
                    <br>
                    <ul style="list-style-type:none;">
                        <?php if(!empty($model->partners)) { $tech_check=true; ?>
                        <li>
                            <h4><b>What technical resources/key activities would be required? (e.g PC, Server, Robots, Internet Service)</b></h4>

                            <p style="word-break:break-all"> <?= $model->partners ?> </p>
                            <?php PlotScoreForm($model, "partners"); ?>
                        </li>
                        <?php
                            }
                        ?>
                        <?php if(!empty($model->tech_resources)) { $tech_check=true; ?>
                        <li>
                            <h4><b> What external help do you need to bring your project technically possible? </b></h4>
                            <p style="word-break:break-all"> <?= $model->tech_resources?> </p>
                            <?php PlotScoreForm($model, "tech_resources"); ?>
                        </li>
                        <?php
                            }
                        ?>
                        <?php if(!empty($model->risk)) { $tech_check=true; ?>
                        <li>
                            <h4><b> What technical challanges may put your projects at risk? </b> </h4>

                            <p style="word-break:break-all"> <?= $model->risk ?> </p>
                            <?php PlotScoreForm($model, "risk"); ?>
                        </li>
                        <?php
                            }
                        ?>
                        <?php if(!empty($model->impact)) { $tech_check=true; ?>
                        <li>
                            <h4><b>  Does it have any social or environmntal impact? </b></h4>

                            <p style="word-break:break-all"> <?= $model->impact ?> </p>
                            <?php PlotScoreForm($model, "impact"); ?>
                        </li>
                        <?php
                            } if (!$tech_check) echo '<h4> Not completed by student. </h4>';
                        ?>
                    </ul>
                </div>
                <div class="row">
                    <h3> Economics Aspects </h3>
                    <br>
                    <ul style="list-style-type:none;">
                        <?php if(!empty($model->fin_resources)) { $economical_check=true; ?>
                        <li>
                            <h4><b>Which are the costs of the project?</b></h4>

                            <p style="word-break:break-all"> <?= $model->fin_resources ?> </p>
                            <?php PlotScoreForm($model, "fin_resources"); ?>
                        </li>
                        <?php
                            }
                        ?>
                        <?php if(!empty($model->customers)) { $economical_check=true; ?>
                        <li>
                            <h4><b> Who could pay for buying or using your product/service? </b></h4>

                            <p style="word-break:break-all"> <?= $model->customers ?> </p>
                            <?php PlotScoreForm($model, "customers"); ?>
                        </li>
                        <?php
                            }
                        ?>
                        <?php if(!empty($model->generate)) { $economical_check=true; ?>
                        <li>
                            <h4><b> Will the project create income? How? </b> </h4>

                            <p style="word-break:break-all"> <?= $model->generate ?> </p>
                            <?php PlotScoreForm($model, "generate"); ?>
                        </li>
                        <?php
                            } if (!$economical_check) echo '<h4> Not completed by student. </h4>';
                        ?>
                    </ul>
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
					if (empty($activities)) echo 'No activities/actions available';
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
</div>
<?php
function PlotScoreForm($model, $field)
{     
//    if($memberCanvasRecord->role == $role &&
//       !empty($memberCanvasRecord))
//    {
//        if($model->status != "Evaluation complete")
//        {
//            echo '<div class="row"><h4>The score for this question:<b> '. $model->attributes[$field . "_score"] .'</b></h4></div>'; 
//        }
//    }
}