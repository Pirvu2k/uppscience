<?php



use yii\helpers\Html;

use yii\widgets\DetailView;



/* @var $this yii\web\View */

/* @var $model app\models\ExpertLanguage */



$this->title = $model->language;

$this->params['breadcrumbs'][] = ['label' => 'Expert Languages', 'url' => ['index']];

$this->params['breadcrumbs'][] = $this->title;

?>



<link href="../web/css/site.css" rel="stylesheet" />

<div class="expert-language-view">



    <h1><?= Html::encode($this->title) ?></h1>



    <p>

        <?= Html::a('Back to List', ['index'], ['class' => 'btn btn-success']) ?>
        
        <?= Html::a('Add Language', ['create'], ['class' => 'btn btn-success']) ?>

        <?= Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        <?= Html::a('Delete', ['delete', 'id' => $model->id], [

            'class' => 'btn btn-danger',

            'data' => [

                'confirm' => 'Are you sure you want to delete this item?',

                'method' => 'post',

            ],

        ]) ?>

    </p>



    <?= DetailView::widget([

        'model' => $model,

        'attributes' => [

            'language',

        ],

    ]) ?>



</div>

