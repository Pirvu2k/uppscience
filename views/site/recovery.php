<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ForgotForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset Password';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$request = Yii::$app->request;
$email = $request->get('mail'); 
$key = $request->get('key'); 
$type = $request->get('type'); 

$time = Yii::$app->db->createCommand('SELECT reset_pass_exp_date FROM member WHERE email=:email')
        ->bindValue(':email', $email)
        ->queryColumn();

$key_check = openssl_encrypt(strtotime($time[0]), 'AES-128-ECB', 'n9vwoxd1mv1mf8ka');
?>

    <div class="row">
        <h1>Reset Password</h1>
    </div>
	
    <div class="row" style="margin-top:20px">
            <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
				<fieldset>
					<h2><?php if($key == $key_check) print 'Enter your new password'; else print 'Error'; ?></h2>
					<hr class="colorgraph">										
					
					
					<?php if($key == $key_check) { ?>
					<?php
						if ($model->load(Yii::$app->request->post()))
						{
							$new_password = Yii::$app->getSecurity()->generatePasswordHash($model->password);
							$time = 0;
                                                        Yii::$app->db->createCommand("UPDATE member SET reset_pass_exp_date=:time, password=:password WHERE email=:email")
                                                        ->bindValue(':time', $time)
                                                        ->bindValue(':password', $new_password)
                                                        ->bindValue(':email', $email)
                                                        ->execute();
							print "<div class='alert alert-success' role='alert'>Your password was successfully changed.</div>";
							?>
							<?= Html::a('<button type="button" class="purple col-sm-12">Go to Login page</button>', ['site/login']) ?>
						<?php
						}
						else {
					?>
					<?php $form = ActiveForm::begin([
						'id' => 'recovery-form',
						'options' => ['class' => 'form-horizontal'],
						'fieldConfig' => [
							'template' => "<div class=\"col-lg-12\">{input}</div></br><div class=\"col-lg-12\">{error}</div>",
						],
					]); ?>																
						<?php echo Html::hiddenInput('email', $email); ?>																
						<?= $form->field($model, 'password', ['inputOptions' => ['placeholder' => 'New password', 'autofocus' => 'autofocus', 'class' => 'form-control input-lg', 'tabindex' => '1']])->passwordInput() ?>						
						
						<div class="row">							
							<div class="col-xs-3 col-sm-3 col-md-3"></div>
							<div class="col-xs-6 col-sm-6 col-md-6">							
								<?= Html::submitButton('Save changes', ['class' => 'purple col-sm-12', 'tabindex' => '3', 'name' => 'recovery-button']) ?>
							</div>
						</div>
						<?php ActiveForm::end(); }} else { print "<div class='alert alert-danger' role='alert'>The reset password link you've followed was invalid. If you believe this was an error, please try the link again or request a new confirmation link below.</div>";?>
						<?= Html::a('<button type="button" class="purple col-sm-12">Go to Forgot Password page</button>', ['site/forgot']) ?>
						<?php }?>
						<hr class="colorgraph">
				</fieldset>
			</div>



			</div>