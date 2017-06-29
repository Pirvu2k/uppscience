<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Jobs;
/* @var $this yii\web\View */
/* @var $model app\models\ExpertExperience */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="expert-experience-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php 
                $items = ArrayHelper::map(Jobs::find()->all(), 'code', 'code');
                 echo $form->field($model, 'job_title')->dropDownList($items,['prompt'=>'Please select job title.'  ]);

            ?>
    <div class="alert alert-info">
                 <strong>Note: </strong>If the job title is missing from the list, please contact us at 
                 <a href="mailto:cop@viscontiproject.eu">cop@viscontiproject.eu </a>
    </div>
    <?= $form->field($model, 'job_description')->textInput(['maxlength' => true]) ?>

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
