<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Country;
use yii\helpers\ArrayHelper;
use app\models\Sector;
use app\models\SubSector;
use app\models\Specialization;
use app\models\Interest;
use app\models\MemberSector;
use app\models\MemberSubSector;
use app\models\MemberSpecialization;
use app\models\MemberInterest;

/* @var $this yii\web\View */
/* @var $model app\models\StudentAccount */
/* @var $form yii\widgets\ActiveForm */
?>

<script src="../web/is.js"> </script>
<div class="student-account-form" onload="prepare();">
	<div class="status-bar">
		<section class="status-bar">
			<div class="container">
				<div class="row">
					<ul id="tabs" class="nav nav-pills" data-tabs="tabs">
						<li class="active"><a href="#tab-1" data-toggle="tab">Personal Info</a>
						</li>
						<li><a href="#tab-2" data-toggle="tab">Contact Info</a>
						</li>
						<li><a href="#tab-3" data-toggle="tab" onclick="refresh3();">Fluent Languages</a>
						</li>
						<li><a href="#tab-4" data-toggle="tab" onclick="refresh1();">Education</a>
						</li>
						<li><a href="#tab-5" data-toggle="tab" onclick="refresh2();">Work Experience</a>
						</li>
						<li><a href="#tab-6" data-toggle="tab">Specialisation</a>
						</li>
						<li><a href="#tab-7" data-toggle="tab">Role</a>
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

			<?= $form->field($model, 'title')->dropdownList(['Mr.' => 'Mr.' , 'Ms.' => 'Ms.' , 'Mrs.' => 'Mrs.'] , ['prompt' => 'Select a Title']) ?>

			<?= $form->field($model, 'given_name')->textInput(['maxlength' => true]) ?>

			<?= $form->field($model, 'family_name')->textInput(['maxlength' => true]) ?>

			<?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>

			<div class="alert alert-info" style="margin-top:-15px;"> Personal website / Personal profile on professional social media / Web page of your department in your organisation </div>

			<?= $form->field($model, 'bio')->textarea(['rows'=>'4']) ?>

			<div class="alert alert-info" style="margin-top:-15px;"> Information about your present profession and fields of activity that you feel would be interesting for other members of the Community who are mostly in science, technology, engineering, IT and related subjects. </div>

			<?php 
							$items=[];
							for($i = date("Y");$i >= date("Y") - 100 ; $i--)
								$items[$i]=$i;

							echo $form->field($model, 'birth_year')->dropDownList($items,['prompt'=>'Please select your birth year' ]);

						?>
			<div class="form-group">
    	
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update Profile and Continue', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success pull-right' , 'style' => 'margin-right:40%;']) ?>
		</div>
		</div>
			
		<div class="tab-pane" id="tab-2">
			<h3> Contact Info </h3>
			<hr class="colorgraph"> 
			<?= $form->field($model, 'mobile')->textInput(['maxlength' => true])->label("Mobile (Optional)") ?>

			<?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => '+123 456 1234567'])->label("Phone (Optional)") ?>
                            
			<?php 
				$items = ArrayHelper::map(Country::find()->all(), 'country_name', 'country_name');
				 echo $form->field($model, 'country')->dropDownList($items,['prompt'=>'Please select your country.'  ]);

			?>
			<div class="form-group">
    	
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update Profile and Continue', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success pull-right' , 'style' => 'margin-right:40%;']) ?>
		</div>
		</div>
		
		<div class="tab-pane" id="tab-4">
			<h3> Education </h3>
			<hr class="colorgraph"> 
                        <div class="alert alert-info">
                            If you have studied the same subject in different levels it is enough if you put in the highest qualification obtained.
                        </div>
			<iframe width="100%" onload="javascript:setIframeHeight(this.id);" id="membereducation" name="education" src="<?php echo Yii::$app->urlManager->createUrl('membereducation/index');?>" scrolling="no" frameBorder="0"></iframe>
                        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update Profile and Continue', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success pull-right' , 'style' => 'margin-right:40%;']) ?>
			
		</div>
		
		<div class="tab-pane" id="tab-5">
			<h3> Work Experience </h3>
			<hr class="colorgraph"> 
                        <div class="no_work_experience"><?= $form->field($model, 'no_work_experience')->checkbox() ?></div>
			<iframe width="100%" id="memberexperience" scrolling="no" onload="javascript:setIframeHeight(this.id);"  name="experience" src="<?php echo Yii::$app->urlManager->createUrl('memberexperience/index');?>" frameBorder="0"></iframe>
                        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update Profile and Continue', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success pull-right' , 'style' => 'margin-right:40%;']) ?>
			
		</div>
		
		<div class="tab-pane" id="tab-6">
			<h3> Specialisation </h3>
			<hr class="colorgraph"> 
			<div><?= $form->field($model, 'is_pro')->checkbox() ?></div>
                        <div><?= $form->field($model, 'is_student')->checkbox() ?></div>
			<div class="alert alert-info">
				<ul>
                 <li>In this last part of your profile you can choose sectors in which you operate. For each sector you choose menus of subsectors will appear, and then specialisation etc. </li>
				 <li>Please make sure you choose at least one sub-sector from every sector you choose, one specialisation from every sub-sector you choose etc. </li>
				 <li>This will give us a full picture of your areas of specialisation and the platform will assign your role.</li>
				<ul>
    		</div>
    		<div class="alert alert-info">
                 If sector, sub-sector, specialisation or interest is missing from the list and you feel these are important to be included please let us know at <a href="mailto:cop@viscontiproject.eu">cop@viscontiproject.eu</a> so that we will make contact with you.
    		</div>
			<div class="row">
			<?php
				//----------- sectors start ---------
				$sectors = Sector::find()->where(['status' => 'Active', 'trash' => NULL])->orderBy("name")->all(); // get sectors

				$user_sectors = MemberSector::find()->where(['member' => Yii::$app->user->id])->all(); // set checkbox based on db, and hidden one aswell

				$user_subsectors = MemberSubSector::find()->where(['member' => Yii::$app->user->id])->all(); 

				$user_specializations = MemberSpecialization::find()->where(['member' => Yii::$app->user->id])->all();

				$user_interests = MemberInterest::find()->where(['member' => Yii::$app->user->id])->all();

				echo '<div class="col-sm-3">';
				echo '<p style="font-size:150%;"> <b>Sectors</b> </p>';

				foreach($sectors as $s )
				{   
					$value=0;
					foreach($user_sectors as $u_s){
						if($u_s->sector_id == $s->id)
						{
							$value=1;
							break;
						}
					}

					echo '<input type="checkbox" class="sector controller'. $s->id .' controller" data-container=".checkbox_container'. $s->id . '" data-target=".controlled'. $s->id .'" name="sector_'.$s->id.'" '.(($value==1) ? 'checked' : '').'> 
								 <span class="checkboxtext">'.$s->name.'</span>
							</input>
							<br>'; //echo all sectors , along with controls for subsectors
					echo '<input type="hidden" value="'.$value.'" name="hidden_sector_'.$s->id.'"></input>';
				}

				echo '</div>';
				echo '<div class="col-sm-3">';
				//------- sectors end ----------
				//------- subsectors start -----
				 

				echo '<p style="font-size:150%;"> <b>Sub-Sectors</b> </p>';

				foreach($sectors as $s)
				{   
					$subsectors = Subsector::find()->where(['sector' => $s->id, 'status' => 'Active', 'trash' => NULL])->all(); // get all subsectors for $s sector
					echo '<div style="border-bottom : 1px dotted #ccc;overflow:hidden;">';
					foreach($subsectors as $item)
					{  
						$value=0;
						foreach($user_subsectors as $u_s){
							if($u_s->subsector == $item->id)
							{
								$value=1;
								break;
							}
						}
						echo '<div class="checkcont checkbox_container'.$s->id.'">';
						echo '<input type="checkbox" data-container=".checkbox_containerspec'. $item->id . '" data-target=".controlledspec'. $item->id .'"
							 class="controlled controller controlled'.$s->id.'" name="subsector_'.$item->id.'" '.(($value==1) ? 'checked' : '').' >'.$item->name.'</input>';
						echo '<input type="hidden" value="'.$value.'" name="hidden_subsector_'.$item->id.'"></input>';
						echo '<br></div>';
					}

					echo '</div>';
				}
				echo '</div>';
				//--------- subsectors end -----
				//-------- specializations start ----
				echo '<div class="col-sm-3">';

				echo '<p style="font-size:150%;"> <b>Specialisation</b> </p>';

				$subs = SubSector::find()->where(['status' => 'Active', 'trash' => NULL])->orderBy("name")->all(); // get sectors

				foreach($subs as $s)
				{   
					$specializations = Specialization::find()->where(['subsector' => $s->id,'status' => 'Active', 'trash' => NULL])->all(); // get all specializations for $s subsector
					echo '<div style="border-bottom : 1px dotted #ccc;overflow:hidden;">';
					foreach($specializations as $item)
					{   
						$value=0;
						foreach($user_specializations as $u_s){
							if($u_s->specialization == $item->id)
							{
								$value=1;
								break;
							}
						}

						echo '<div class="checkcont checkbox_containerspec'.$s->id.'">';
						echo '<input type="checkbox" class="controlled controller controlledspec'.$s->id.'" data-container=".checkbox_containerint'. $item->id . '" data-target=".controlledint'. $item->id .'" name="specialization_'.$item->id.'"'.(($value==1) ? 'checked' : '').' >'.$item->name.'</input>';
						echo '<input type="hidden" value="'.$value.'" name="hidden_specialization_'.$item->id.'"></input>';
						echo '<br></div>';
					}

					echo '</div>';
				}
				echo '</div>';
				//specializations end
				//interests start

				echo '<div class="col-sm-3">';

				 

				echo '<p style="font-size:150%;"> <b>Interests</b> </p>';

				$specs = Specialization::find()->where(['status' => 'Active', 'trash' => NULL])->orderBy("name")->all(); // get sectors

				foreach($specs as $s)
				{   
					$interests = Interest::find()->where(['specialization' => $s->id])->all(); // get all specializations for $s subsector
                                        
					echo '<div style="border-bottom : 1px dotted #ccc;overflow:hidden;">';
					foreach($interests as $item)
					{   
						$value=0;
						foreach($user_interests as $u_i)
                                                {
                                                    if($u_i->interest == $item->id)
                                                    {
                                                            $value=1;
                                                            break;
                                                    }
						}

						echo '<div class="checkcont checkbox_containerint'.$s->id.'">';
						echo '<input type="checkbox" class="controlled placeholder controlledint'.$s->id.'" name="interest_'.$item->id.'"'.(($value==1) ? 'checked' : '').' >'.$item->name.'</input>';
						echo '<input type="hidden" value="'.$value.'" name="hidden_interest_'.$item->id.'"></input>';
						echo '<br></div>';

					}

					echo '</div>';
				}
				echo '</div>';

				//interests end
			?>

			</div>
			<br>
			<div class="form-group">
    	
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update Profile and Continue', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success pull-right' , 'style' => 'margin-right:40%;']) ?>
		</div>
		</div>
	<div class="tab-pane" id="tab-7">
		</br>
		<!--
		<div class="row">
		<div class="col-sm-3 col-sm-offset-2">
		<p> The following minimal data should be filled in order for the role to be determined: </p>
		</div>
		<div class="col-sm-6">
		<ul class="list-group">
			<li class="list-group-item">At least one experience record.</li>
			<li class="list-group-item">At least one educational record.</li>
			<li class="list-group-item">At least one sector.</li>
			<li class="list-group-item">For each sector, at least one sub-sector.</li>
		</ul>
		</div>
		</div>	-->
		<div class="alert alert-info"> 
		<ul class="list-group">
			<li class="list-group-item"> The platform assigns roles to members of the Community of Practice according to the sectors, sub-sectors, specialisations and interests you may have chosen when building your profile.
When we add sectors etc. we feed the system with how much of the sector is economic, technical and creative. </li>

			<li class="list-group-item"> When a member of the Community of Practice will share a project idea and requests an opinion from other members the system will chose three members to look at that project. It will choose three members having technical, economic and creative roles so that each one can offer a view of the project idea shared from a different perspective. </li>
			
			<li class="list-group-item">The platform also filters who to invite to look a project by languages. It also distributes invitations so that it engages everyone in an equal manner. </li>
		</ul>
		</div>
		<?php
		 	if(!empty($model->role))
		 		{
		 		echo '<div class="status-bar text-center">
  						<strong>The role assigned to you is :</strong> '.$model->role.'</div></br>';}  
			echo Html::a('Find my role', ['/member/role','id'=>Yii::$app->user->id], ['class'=>'btn orange pull-right' , 'style' => 'margin-right:50%']);

		 ?>
	</div>

	<div class="tab-pane" id="tab-3">
			<h3> Fluent Languages </h3>
			<hr class="colorgraph"> 

			<div class="alert alert-info"> Languages in which you can communicate easily </div>

			<iframe width="100%" id="memberlanguage" scrolling="no" onload="javascript:setIframeHeight(this.id);"  name="language" src="<?php echo Yii::$app->urlManager->createUrl('memberlanguage/index');?>" frameBorder="0"></iframe>
                        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update Profile and Continue', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success pull-right' , 'style' => 'margin-right:40%;']) ?>
	</div>

	</div>

    
<input type="hidden" name="tabid" id="tabid" value="<?php echo $tabid ?>" />
    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
$(document).ready(function()
{
    $('#memberaccount-given_name, #memberaccount-family_name').change(function () {
        var txtval = $(this).val();
        $(this).val(txtval.toLowerCase().replace(/\b[a-z]/g, function (letter) { return letter.toUpperCase(); }));
    });
    
     var tabid = $("#tabid").val();
     $('#tabs a[href="#' + tabid + '"]').tab('show');
     
     RefreshWorkExperience();
     
     $(".no_work_experience input[type=checkbox]").click(function() 
     {
         RefreshWorkExperience();
     });

  $('.nav-pills a').click(function (e) {
    $(this).tab('show');
    var scrollmem = $('body').scrollTop();
    $("#tabid").val(this.hash.replace("#", ""));
    $('html,body').scrollTop(scrollmem);
  });
  });
  
function RefreshWorkExperience()
{
    if($(".no_work_experience input").is(':checked'))
    {
       $("#memberexperience").hide();
    }
    else
    {
        $("#memberexperience").show();
    }
}

function refresh3() {
	if(is.firefox())
		document.getElementById('memberlanguage').contentWindow.location.reload();
};

function refresh1() {
	if(is.firefox())
		document.getElementById('membereducation').contentWindow.location.reload();
};

function refresh2() {
	if(is.firefox())
		document.getElementById('memberexperience').contentWindow.location.reload();
}

$('.controlled').each(function(){
    var $this=$(this);
    if($this.prop('checked') == false) {
        $this.parent().parent().hide(); 
    }
}); // disable and hide all unchecked items

$('.controlled').each(function(){
    var $this=$(this);
    if($this.prop('checked') == true) { 
            $('.'+$this.attr('class').split(' ')[2]).each(function(){
                $(this).show();
                $(this).parent('.checkcont').show();
                $(this).parent().parent().show(); 
                //show child elements
                $($(this).data('container')).parent().show();
                $($(this).data('target')).show();
       
        });
    } else {
    	$($(this).data('container')).parent().hide();
        $($(this).data('target')).hide();
    }
}); //show childs of checked items

$('.sector').each(function(){
    var $this=$(this);
    if($this.prop('checked') == true) { 
                $($(this).data('container')).parent().show();
                $($(this).data('target')).show();
        };
});

$('.controller').click(function () { 
    var $this = $(this),
        $inputs = $($this.data('target')), //get inputs
        $containers=$($this.data('container')); // get container
    if(this.checked) {
    	$inputs.show('slow');
        $containers.parent().show("slow");
    } else 
    {
        $inputs.prop('checked', false); // uncheck all child elements if parent is unchecked
        $inputs.hide("slow");
        //alert(JSON.stringify($inputs.data('target')));
        $inputs.each(function(){
            var $childcontainers= $($(this).data('container')); //hide all 2nd+ level children
            var $childinputs= $($(this).data('target')); //uncheck all 2nd+ level children
            $childcontainers.parent().hide("slow");
            $childinputs.prop("checked",false);
            $childinputs.each(function(){
                    $($(this).data('container')).parent().hide("slow");
                    $($(this).data('target')).prop("checked" , false);
            });
        });
        $containers.parent().hide("slow");
        
    }
})


</script>

<script>

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
		console.log('pa');
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