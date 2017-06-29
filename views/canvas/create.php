<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Canvas */

	$this->title ="Create Project";
$this->params['breadcrumbs'][] = ['label' => 'Canvas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="canvas-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <hr class="colorgraph">
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


