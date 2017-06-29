<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Education */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="education-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Back to List', ['index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?= $form->field($model, 'degree')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'institution')->textInput(['maxlength' => true]) ?>

    <?php 
                    $items=[];
                    for($i = 1;$i <=12 ; $i++)
                        $items[$i]=$i;

                    echo $form->field($model, 'from_m')->dropDownList($items,['value'=> $model->from_m  ,'prompt'=>'Please select a start month']);
    
                    $items=[];
                    for($i = date("Y");$i >= date("Y") - 100 ; $i--)
                        $items[$i]=$i;

                    echo $form->field($model, 'from_y')->dropDownList($items,['value'=> $model->from_y  ,'prompt'=>'Please select start year.']);

                ?>

                    <?php 
                    $items=[];
                    for($i = 1;$i <=12 ; $i++)
                        $items[$i]=$i;

                    echo $form->field($model, 'to_m')->dropDownList($items,['value'=> $model->to_m  ,'prompt'=>'Please select end month']);
                    
                    $items=[];
                    for($i = date("Y");$i >= date("Y") - 100 ; $i--)
                        $items[$i]=$i;

                    echo $form->field($model, 'to_y')->dropDownList($items,['value'=> $model->to_y , 'prompt'=>'Please select end year.' ]);

                ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
