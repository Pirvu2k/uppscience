<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */

$this->title = 'Visconti';

?><div class="row">	<?php		
    if(empty(Yii::$app->user->identity->role))		
    {			
        echo'<div class="alert alert-info">					<strong>Thank you for joining the VISConti Community of Practice</strong>					<p>Your next step is to complete your profile so that you will be assigned a role in the Community of Practice.</p> <p>Having a role assigned to you makes you an active member of the Community.</p>				 </div>';		}	?>
    
    <?php if(!Yii::$app->user->isGuest) {?>
    <div style="margin-left: 34px;">
        <div class="row">
        <h2>Draft projects</h2>
    <ul class="col-md-10 col-sm-9 feed">
        <?php foreach($ownProjectsDraft as $project): ?>
        <li>
            <h2 class="col-sm-12"><a href="?r=canvas/update&id=<?= $project->id ?> "><?= $project->title ?></a></h2>
            <span class="pull-right"><time><?= $project->date_added ?></a></time></span>
            <div class="col-sm-12 clearfix">
                <data><b><?= $project->eng_summary ?></b>
                </data>
            </div>
            <div class="clearfix visible-*"></div>
        </li>
        <?php endforeach; 
        if(empty($ownProjectsDraft))
            echo "<p>No projects available</p>";
        ?>
    </ul>
    </div>
    <div class="row">
        <h2>Submitted projects</h2>
    <ul class="col-md-10 col-sm-9 feed">
        <?php foreach($ownProjects as $project): ?>
        <li>
            <h2 class="col-sm-12"><a href="?r=canvas/view&id=<?= $project->id ?> "><?= $project->title ?></a></h2>
            <span class="pull-right"><time><?= $project->date_added ?></a></time></span>
            <div class="col-sm-12 clearfix">
                <data><b><?= $project->eng_summary ?></b>
                </data>
            </div>
            <div class="clearfix visible-*"></div>
        </li>
        <?php endforeach; 
        if(empty($ownProjects))
            echo "<p>No projects available</p>";
        ?>
    </ul>
    </div>     
    <div class="row">
        <h2>In review projects </h2>
    <ul class="col-md-10 col-sm-9 feed">
        <?php foreach($memberAcceptedProjects as $project): ?>
        <li>
            <h2 class="col-sm-12"><a href="?r=canvas/view&id=<?= $project->id ?> "><?= $project->title ?></a></h2>
            <span class="pull-right"><time><?= $project->date_added ?></a></time></span>
            <div class="col-sm-12 clearfix">
                <data><b><?= $project->eng_summary ?></b>
                </data>
            </div>
            <div class="clearfix visible-*"></div>
        </li>
        <?php endforeach; 
        if(empty($memberAcceptedProjects))
            echo "<p>No projects available</p>";
        ?>
    </ul>
    </div>        

<div class="row">
    <h2>Accepted projects</h2>
    <ul class="col-md-10 col-sm-9 feed">
        <?php foreach($acceptedProjects as $member_project): ?>
        <li>
            <h2 class="col-sm-12"><a href="?r=canvas/view&id=<?= $member_project->project ?> "><?= $member_project->canvas->title ?></a></h2>
            <span class="pull-right"><time><?= $member_project->canvas->date_added ?></a></time></span>
            <div class="col-sm-12 clearfix">
                <data><b><?= $member_project->canvas->eng_summary ?></b>
                </data>
            </div>
            <div class="clearfix visible-*"></div>
        </li>
        <?php endforeach; 
        if(empty($acceptedProjects))
            echo "<p>No projects available</p>";
        ?>
    </ul>
</div>

<div class="row">
    <h2>Invitations</h2>
    <ul class="col-md-10 col-sm-9 feed">
        <?php foreach($invitations as $invitation): ?>
        <li>
            <h2 class="col-sm-12"><a href="?r=canvas/view&id=<?= $invitation->project->id ?> "><?= $invitation->project->title ?></a></h2>
            <span class="pull-right"><time><?= $invitation->project->date_added ?></a></time></span>
            <div class="col-sm-12 clearfix">
                <data><b><?= $invitation->project->eng_summary ?></b>
                </data>
            </div>
            <div class="clearfix visible-*"></div>
        </li>
        <?php endforeach; 
        if(empty($invitations))
            echo "<p>No invitations available</p>";
        ?>
    </ul></div>

    <div class="row">
    <h2> Recent activities </h2>
    <ul class="col-md-10 col-sm-9 feed">
        <?php foreach($activities as $activity): ?>
        <li>
            <h2 class="col-sm-12"><a href="?r=canvas/view&id=<?= $activity->canvas ?> "><?= $activity->action_type ?></a></h2>
            <span class="pull-right"><time><?= $activity->created_on ?></a></time></span>
            <div class="col-sm-12 clearfix">
                <data><b><?= $activity->activity_text ?></b>
                </data>
            </div>
            <div class="clearfix visible-*"></div>
        </li>
        <?php endforeach; 
        if(empty($activities))
            echo "<p>No activities available</p>";
        ?>
    </ul>
</div>    
            </div>
    <?php } ?>
