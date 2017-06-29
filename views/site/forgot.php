<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ForgotForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset Password';
$this->params['breadcrumbs'][] = $this->title;
?>

    <div class="row">
        <h1>Reset your password</h1>
    </div>
	
    <div class="row" style="margin-top:20px">
            <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
				<fieldset>
					<h3>If you've forgotten your password, we'll send you an email to reset your password.</h3>
					<hr class="colorgraph">										<?php if(Yii::$app->session->getFlash('success')) { ?>					<div class='alert alert-success' role='alert'><font color='green'><?= Yii::$app->session->getFlash('success'); ?></font></div>										<?php } if(Yii::$app->session->getFlash('error')) { ?>					<div class='alert alert-danger' role='alert'><font color='red'><?= Yii::$app->session->getFlash('error'); ?></font></div>					
					<?php } $form = ActiveForm::begin([
						'id' => 'forgot-form',
						'options' => ['class' => 'form-horizontal'],																		
						'fieldConfig' => [
							'template' => "<div class=\"col-lg-12\">{input}</div></br><div class=\"col-lg-12\">{error}</div>",
						],
					]); ?>																<?= $form->field($model, "info", ['errorOptions' => ['encode' => false]])->hiddenInput()->label(false) ?>																<?= $form->field($model, 'email', ['inputOptions' => ['placeholder' => 'E-mail', 'autofocus' => 'autofocus', 'class' => 'form-control input-lg', 'tabindex' => '1']]) ?>
						
                                        <div class="row">							<div class="col-xs-3 col-sm-3 col-md-3"></div>
                                                <div class="col-xs-6 col-sm-6 col-md-6">							
                                                        <?= Html::submitButton('Continue', ['class' => 'orange col-sm-12', 'tabindex' => '3', 'name' => 'forgot-button']) ?>
                                                </div>
                                        </div>
						
						<hr class="colorgraph">
				</fieldset>
			</div>


    <?php ActiveForm::end(); ?>
			</div>