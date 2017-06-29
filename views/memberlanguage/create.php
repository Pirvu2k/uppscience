<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ExpertLanguage */

$this->title = 'Create Expert Language';
$this->params['breadcrumbs'][] = ['label' => 'Expert Languages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<link href="../web/css/site.css" rel="stylesheet" />
<div class="expert-language-create">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
