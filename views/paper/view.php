<?php

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
			
				<div class="dropdown">
					<button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						<i class="glyphicon glyphicon-share-alt" style="margin-right:10px;"></i> Share
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						<li style="padding-left: 10px; padding-right: 10px;"><input id="share" class="form-control" value="<?php print 'http://'.$_SERVER['HTTP_HOST'].Yii::$app->getUrlManager()->getBaseUrl().'/index.php?r=site/paper&id='.$model->id; ?>" readonly="readonly"></li>
						<li><center><button id="copyButton" class="btn btn-info">Copy to clipboard</button></center></li>
					</ul>
				</div>
		
                <h3><?= Html::encode($this->title) ?></h3>
    <?php
        $created_by = $model->created_by;
        $get_author_details = Yii::$app->db->createCommand("SELECT given_name, family_name FROM member WHERE id = ".$model->created_by)->queryAll(); 
        $get_last_modified_details = Yii::$app->db->createCommand("SELECT given_name, family_name FROM member WHERE id = ".$model->last_modified_by)->queryAll(); 
            
        $model['created_by'] = Html::a($get_author_details[0]['given_name'] . " " . $get_author_details[0]['family_name'], ['member/profile', 'id' => $model['created_by']]);
        $model['last_modified_by'] = Html::a($get_last_modified_details[0]['given_name'] . " " . $get_last_modified_details[0]['family_name'], ['member/profile', 'id' => $model['last_modified_by']]);
    ?>
<div class="pull-right">
        <?php if(!Yii::$app->user->isGuest && (Yii::$app->user->identity->getAdminStatus() == "yes" || Yii::$app->user->id == $created_by))
            print Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'onclick' => "return confirm('Are you sure you want to delete this item?');",
            'data' => [
                'method' => 'post',
            ],
        ]) ?>
                            </div>
                    </h3>
                 
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

                <?php
                    if(Yii::$app->user->id == $created_by)
                        {
                ?>

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">
                  Report Abuse
                </button>

                <!-- Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Report <?= $model->title ?> </h4>
                      </div>
                      <div class="modal-body">
                        <?php $form=ActiveForm::begin(['enableClientValidation' => true]); ?>

                        <?= $form->field($abuseModel, 'info')->textArea(['placeholder' => '10-1024 Characters' , 'rows' => 3]); ?>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <?= Html::submitButton('Report', ['class' => 'btn btn-info' , 'data-confirm' => 'Are you sure you want to report this paper?It can\'t be undone afterwards.']); ?>
                      </div>

                      <?php ActiveForm::end(); ?>

                    </div>
                  </div>
                </div>

                <?php
                }
                ?>

                <hr class="colorgraph">
                <h3>Title (in English)</h3>
                <?= $model->title_in_english ?>
                <hr class="colorgraph">
                <h3>Abstract</h3>
                <?= $model->abstract ?>
                <hr class="colorgraph">
                <h3>Abstract (in English)</h3>
                <?= $model->abstract_in_english ?>
                <hr class="colorgraph">
                <h3>Project status</h3>
                <div class="row">
                    <div class="col-xs-6 col-md-2">
                        <b>Submitted by</b>
                        </br>
                        <?= $model->created_by ?>
                        </br>
                        <b>Last modified by</b>
                        </br>
                        <?= $model->last_modified_by ?>
                    </div>
                    <div class="col-xs-6 col-md-2">
                        <b>Language</b>
                        </br>
                        <?= $model->language ?>
                    </div>
                    <div class="col-xs-6 col-md-2">
                        <b>Created on</b>
                        </br>
                        <?= $model->created_on ?>
                        </br>
                        <b>Last modified on</b>
                        </br>
                        <?= $model->last_modified_on ?>
                        </br>
                        <b>Submission date</b>
                        </br>
                        <?= $model->submission_date ?>
                    </div>
                    <div class="col-xs-6 col-md-3">
                        <b>Status</b>
                        </br>
                        <div class="pull-left">
                            <?= $model->status ?>
                        </div>
                    </div>

                    <div class="col-xs-6 col-md-3">
                        <b>Trash</b>
                        </br>
                        <?= $model->trash ?>
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
                        
                </div>

                <div class="col-xs-6 col-md-4">
                        <h4> Rating: <b><?= $model->rating ?></b></h4> 
                </div>

                <div class="col-xs-6 col-md-4">
                        
                </div>
            
                </div>

                <div class="row">
                    <hr class="colorgraph">
                </div>

                <div class="row">
                    <h3> Introduction </h3>
                    <br>
                    <div class="bs-callout bs-callout-info" id="callout-type-b-i-elems">
                        <?= $model->introduction ?>
                    </div>
                    
                </div>

                <div class="row">
                    <h3> Content </h3>
                    <br>
                    <div class="bs-callout bs-callout-info" id="callout-type-b-i-elems">
                        <?= $model->content ?>
                    </div>
                </div>

                <div class="row">
                    <h3> References </h3>
                    <br>
                    <div class="bs-callout bs-callout-info" id="callout-type-b-i-elems">
                        <?= $model->references ?>
                    </div>
                </div>


            <div class="row">
                    <hr class="colorgraph">
                    <h2> Keywords </h2>

                        <?= $model->keywords ?>

            </div>

        </main>
    </div>

</div>
<script>
document.getElementById("copyButton").addEventListener("click", function() {
    copyToClipboard(document.getElementById("share"));
});

function copyToClipboard(elem) {
    var targetId = "_hiddenCopyText_";
    var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
    var origSelectionStart, origSelectionEnd;
    if (isInput) {
        target = elem;
        origSelectionStart = elem.selectionStart;
        origSelectionEnd = elem.selectionEnd;
    } else {
        target = document.getElementById(targetId);
        if (!target) {
            var target = document.createElement("textarea");
            target.style.position = "absolute";
            target.style.left = "-9999px";
            target.style.top = "0";
            target.id = targetId;
            document.body.appendChild(target);
        }
        target.textContent = elem.textContent;
    }
    var currentFocus = document.activeElement;
    target.focus();
    target.setSelectionRange(0, target.value.length);
    
    var succeed;
    try {
    	  succeed = document.execCommand("copy");
    } catch(e) {
        succeed = false;
    }
    if (currentFocus && typeof currentFocus.focus === "function") {
        currentFocus.focus();
    }
    
    if (isInput) {
        elem.setSelectionRange(origSelectionStart, origSelectionEnd);
    } else {
        target.textContent = "";
    }
    return succeed;
}
</script>