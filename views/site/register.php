<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use yii\captcha\CaptchaValidator;
use yii\captcha\CaptchaAction;

/**
 * @var yii\web\View              $this
 * @var dektrium\user\models\User $user
 * @var dektrium\user\Module      $module
 */

$this->title = 'Register';
$this->params['breadcrumbs'][] = $this->title;


?>


<div class="row">
        <div class="row" style="margin-top:20px">
			<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
				<h2>Please Sign Up</h2>
				<hr class="colorgraph">

				<div class="panel-body">
					<?php $form = ActiveForm::begin([
						'fieldConfig' => [
							'template' => "<div class=\"col-lg-13\">{input}</div></br><div class=\"col-lg-12\">{error}</div>",
						],
						//'enableAjaxValidation'   => true,
						//'enableClientValidation' => true,
					]); ?>
                                    
                                        <p style="color:red;"><?= Yii::$app->session->getFlash('error'); ?> </p>
                                    
                                        <?= $form->field($model, 'given_name', ['inputOptions' => ['placeholder' => 'Given Name', 'autofocus' => 'autofocus', 'class' => 'form-control input-lg', 'tabindex' => '1']])->textInput(['maxlength' => true]) ?>

                                        <?= $form->field($model, 'family_name', ['inputOptions' => ['placeholder' => 'Family Name', 'autofocus' => 'autofocus', 'class' => 'form-control input-lg', 'tabindex' => '2']])->textInput(['maxlength' => true]) ?>

					<?= $form->field($model, 'email', ['inputOptions' => ['placeholder' => 'E-mail', 'autofocus' => 'autofocus', 'class' => 'form-control input-lg', 'tabindex' => '3']]) ?> 

					<?php // $form->field($model, 'username') ?>
					
					<?= $form->field($model, 'password', ['inputOptions' => ['placeholder' => 'Password', 'autofocus' => 'autofocus', 'class' => 'form-control input-lg', 'tabindex' => '4']])->passwordInput() ?>

					<?= $form->field($model, 'captcha')->widget(Captcha::className(),['captchaAction' => 'site/captcha', 'options' => ['placeholder' => 'Captcha', 'autofocus' => 'autofocus', 'class' => 'form-control input-lg', 'tabindex' => '5'],
						'template' => '<div class="row">
                            <div class="col-xs-12 col-sm-3 col-md-3">
                                <div class="form-group">
									{image}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-9 col-md-9">
                                <div class="form-group">
                                    {input}
                                </div>
                            </div>
                        </div>'
					]) ?>


					<?= Html::submitButton('Create Account', ['class' => 'btn btn-success btn-block']) ?>

					<?php ActiveForm::end(); ?>
				</div>
			
				<hr class="colorgraph">
            </div>
        </div>

        <p class="text-center">
            <?= Html::a('Already registered? Sign in!', ['/site/login']) ?>
        </p>
    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

<?php
$js = <<<JS
       $('#registrationform-captcha-image').trigger('click');
JS;
$this->registerJs($js, $this::POS_READY);
?>
