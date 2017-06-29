<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Country;
use app\models\Sector;
use app\models\Degrees;
use app\models\SubSector;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\StudentAccount */
/* @var $form yii\widgets\ActiveForm */
?>
<script src="../web/is.js"> </script>

<div class="student-account-form">
	<div class="status-bar">
		<section class="status-bar">
			<div class="container">
				<div class="row">
					<ul id="tabs" class="nav nav-pills" data-tabs="tabs">
						<li class="active"><a href="#tab-1" data-toggle="tab">Personal Info</a>
						</li>
						<li><a href="#tab-2" data-toggle="tab">Contact Info</a>
						</li>
						<li><a href="#tab-3" data-toggle="tab" onclick="refresh1();">Education</a>
						</li>
						<li><a href="#tab-4" data-toggle="tab" onclick="refresh2();">Experience</a>
						</li>
					</ul>
				</div>
			</div>
		</section>
	</div>
    <?php $form = ActiveForm::begin(); ?>
	<div id="profile-tab-content" class="tab-content">
		<div class="tab-pane active" id="tab-1">
			<h3> Personal Info </h3>
			<hr class="colorgraph">
			<?= $form->field($model, 'given_name')->textInput(['maxlength' => true]) ?>

			<?= $form->field($model, 'family_name')->textInput(['maxlength' => true]) ?>

			<?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>

			<div class="alert alert-info" style="margin-top:-15px;"> Personal website / Personal profile on professional social media / Web page of your department in your organisation </div>
			
			<?= $form->field($model, 'bio')->textarea(['rows'=>'4']) ?>

			<?php 
							$items=[];
							for($i = date("Y");$i >= date("Y") - 100 ; $i--)
								$items[$i]=$i;

							echo $form->field($model, 'birth_year')->dropDownList($items,['prompt'=>'Please select your birth year.' ]);

						?>
			<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update Profile and Continue', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success pull-right' , 'style' => 'margin-right:40%']) ?>
    </div>
		</div>
		<div class="tab-pane" id="tab-2">
			<h3> Contact Info </h3>
			<hr class="colorgraph"> 
			<?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

			<?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

			<?php 
				$items = ArrayHelper::map(Country::find()->all(), 'country_name', 'country_name');
				 echo $form->field($model, 'country')->dropDownList($items,['prompt'=>'Please select your country.'  ]);

			?>

			<div class="form-group">
        	<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update Profile and Continue', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success pull-right' , 'style' => 'margin-right:40%']) ?>
    		</div>

		</div>
		<div class="tab-pane" id="tab-3">
			<h3> Education </h3>
			<hr class="colorgraph"> 

			<?php 
				$items = ArrayHelper::map(Sector::find()->all(), 'id', 'name');
				 echo $form->field($model, 'sector')->dropDownList($items,['prompt'=>'Please select your field of study.' ]);

				  
                $items = ArrayHelper::map(Degrees::find()->all(), 'id', 'code');
                 echo $form->field($model, 'sub_sector')->dropDownList($items,['prompt'=>'Please select level of study.'  ]);

            
			?>
			<hr class="colorgraph">
			<iframe width="100%" id="studenteducation" scrolling="no" onload="javascript:setIframeHeight(this.id);" name="education" src="<?php echo Yii::$app->urlManager->createUrl('studenteducation/index');?>" frameBorder="0"></iframe>
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update Profile and Continue', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success pull-right' , 'style' => 'margin-right:40%']) ?>
		</div>
		<div class="tab-pane" id="tab-4">
			<h3> Experience </h3>
			<hr class="colorgraph"> 
			<iframe width="100%" id="studentexperience" scrolling="no" onload="javascript:setIframeHeight(this.id);" name="experience" src="<?php echo Yii::$app->urlManager->createUrl('studentexperience/index');?>" frameBorder="0"></iframe>

		</div>

    
<input type="hidden" name="tabid" id="tabid" value="<?php echo $tabid ?>" />
    <?php ActiveForm::end(); ?>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script>
$(document).ready(function()
{
    $('#studentaccount-given_name, #studentaccount-family_name').change(function () {
        var txtval = $(this).val();
        $(this).val(txtval.toLowerCase().replace(/\b[a-z]/g, function (letter) { return letter.toUpperCase(); }));
    });
     var tabid = $("#tabid").val();
     $('#tabs a[href="#' + tabid + '"]').tab('show');

  $('.nav-pills a').click(function (e) {
    $(this).tab('show');
    var scrollmem = $('body').scrollTop();
    $("#tabid").val(this.hash.replace("#", ""));
    $('html,body').scrollTop(scrollmem);
  });
  });
	function getDocHeight(doc) {
		doc = doc || document;
		var body = doc.body, html = doc.documentElement;
		var height = Math.max( body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight );
		return height;
	}
	function setIframeHeight(myiframeid) {
		var ifrm = document.getElementById(myiframeid);
		var doc = ifrm.contentDocument? ifrm.contentDocument: ifrm.contentWindow.document;
		ifrm.style.visibility = 'hidden';
		ifrm.style.height = "10px";
		ifrm.style.height = getDocHeight( doc ) + 5+"px";
		ifrm.style.visibility = 'visible';
	}

	function refresh1() {
	if(is.firefox())
		document.getElementById('studenteducation').contentWindow.location.reload();
	};

	function refresh2() {
		if(is.firefox())
			document.getElementById('studentexperience').contentWindow.location.reload();
	}
</script>

<script>
	(function(run){
		for (i=0;i<frames.length; i++) {
		var f1 = document.getElementsByTagName('myiframename')[i];
		if(!f1 && window.addEventListener && !run){
		document.addEventListener('DOMContentLoaded', arguments.callee, false);
		return;
		}
		if(!f1){setTimeout(arguments.callee, 300); return;}
		f2 = f1.cloneNode(false);
		f1.src = 'about: blank';
		f2.frameBorder = '0';
		f2.allowTransparency = 'yes';
		f2.scrolling = 'no';
		f1.parentNode.replaceChild(f2, f1);
		}
	})();
</script>