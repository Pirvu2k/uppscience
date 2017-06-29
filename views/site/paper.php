<?php
use app\models\Paper;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Paper */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Papers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="paper-view">

<div id="content">
        <main id="new" class="container">
            <div class="row">
                <h3><?= Html::encode($model->title_in_english) ?></h3>

			
				<div class="dropdown">
					<button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						<i class="glyphicon glyphicon-share-alt" style="margin-right:10px;"></i> Share
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						<li style="padding-left: 10px; padding-right: 10px;"><input id="share" class="form-control" value="<?php print 'http://'.$_SERVER['HTTP_HOST'].Yii::$app->getUrlManager()->getBaseUrl().'index.php?r=site/paper&id='.$model->id; ?>" readonly="readonly"></li>
						<li><center><button id="copyButton" class="btn btn-info">Copy to clipboard</button></center></li>
					</ul>
				</div>
				
    <?php
        $created_by = $model->created_by;
        $get_author_details = Yii::$app->db->createCommand("SELECT given_name, family_name FROM member WHERE id = ".$model->created_by)->queryAll(); 
        $get_last_modified_details = Yii::$app->db->createCommand("SELECT given_name, family_name FROM member WHERE id = ".$model->last_modified_by)->queryAll(); 
            
        $model['created_by'] = Html::a($get_author_details[0]['given_name'] . " " . $get_author_details[0]['family_name'], ['member/profile', 'id' => $model['created_by']]);
        $model['last_modified_by'] = Html::a($get_last_modified_details[0]['given_name'] . " " . $get_last_modified_details[0]['family_name'], ['member/profile', 'id' => $model['last_modified_by']]);
    ?>
			
                <hr class="colorgraph">
                <h3>Keywords</h3>
                <?= $model->keywords ?>
			
                <hr class="colorgraph">
				

                <div class="row">

					<div class="col-xs-6 col-md-4">
					</div>

					<div class="col-xs-6 col-md-4">
						<h4> Rating: <b><?= $model->rating ?></b></h4> 
					</div>

					<div class="col-xs-6 col-md-4">
					</div>
            
                </div>
				
                <hr class="colorgraph">
                <h3>Submitted by</h3>
                <h4><?= $model->created_by ?></h4>
				
                <hr class="colorgraph">
                <h3>Abstract</h3>
                <?= $model->abstract_in_english ?>
                <hr class="colorgraph">

                </div>
                </br>

            </div>        
                <div class="row">
                    <h3> Introduction </h3>
                    <br>
                    <div class="bs-callout bs-callout-info" id="callout-type-b-i-elems">
                        <?= $model->introduction ?>
                    </div>
                    
                </div>
<div class="pull-right"><a href="<?php print 'http://'.$_SERVER['HTTP_HOST'].Yii::$app->getUrlManager()->getBaseUrl().'/index.php?r=paper/view&id='.$model->id; ?>" class="btn btn-info">Read more</a></div>

        </main>
    </div>

</div>
