<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StudentExperience */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="student-experience-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'job_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'job_description')->textarea(['rows'=>'4']) ?>

    <?= $form->field($model, 'institution')->textInput(['maxlength' => true]) ?>

    <?php 
                    $items=[];
                    for($i = 1;$i <=12 ; $i++)
                        $items[$i]=$i;

                    echo $form->field($model, 'from_m')->dropDownList($items,['value'=> $model->from_m  ,'prompt'=>'Please select a start month']);
    
                    $items=[];
                    for($i = date("Y");$i >= date("Y") - 100 ; $i--)
                        $items[$i]=$i;

                    echo $form->field($model, 'from_y')->dropDownList($items,['value'=> $model->from_y  ,'prompt'=>'Please select start year'])->label(false);

                ?>

                    <?php 
                    $items=[];
                    for($i = 1;$i <=12 ; $i++)
                        $items[$i]=$i;

                    echo $form->field($model, 'to_m')->dropDownList($items,['value'=> $model->to_m  ,'prompt'=>'Please select end month']);
                    
                    $items=[];
                    $items["Ongoing"] = "Ongoing";
                    for($i = date("Y");$i >= date("Y") - 100 ; $i--)
                        $items[$i]=$i;

                    echo $form->field($model, 'to_y')->dropDownList($items,['value'=> $model->to_y , 'prompt'=>'Please select end year' ])->label(false);

                ?>

    <div class="form-group">
        <p><?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right' , 'style' => 'margin-right:50%']) ?>
            <?= Html::a('Back to List', ['index'], ['class' => 'btn btn-danger']) ?>
        </p>
    </div>

    <?php ActiveForm::end(); ?>

</div>
