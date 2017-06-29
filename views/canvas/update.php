<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Canvas */

$this->title = 'Update Project: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Canvas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="canvas-update">



    <h1><?= Html::encode($this->title) ?></h1>

    <?php
                    if($model->status == 'Draft'){
                        echo '<div class="alert alert-info">
                                <strong>Hey!</strong> Before your project gets submitted, you can update it. When you\'re ready , press \'Submit\' at the bottom of the page! 
                            </div>';
                    }
                ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>


</div>
<?php

if(!empty(Yii::$app->request->get('error')))
{
	echo '<script> alert("'. Yii::$app->request->get('error') .'"); </script>';
}

?>