<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ExpertLanguage */

$this->title = 'Update Expert Language: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Expert Languages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<link href="../web/css/site.css" rel="stylesheet" />
<div class="expert-language-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
