<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Languages;
/* @var $this yii\web\View */
/* @var $model app\models\ExpertLanguage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="expert-language-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php 
                $items = ArrayHelper::map(Languages::find()->all(), 'name', 'name');
                 echo $form->field($model, 'language')->dropDownList($items,['prompt'=>'Please select language.'  ]);

            ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Add' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
