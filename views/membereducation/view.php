<?php



use yii\helpers\Html;

use yii\widgets\DetailView;



/* @var $this yii\web\View */

/* @var $model app\models\ExpertEducation */



$this->title = $model->id;

$this->params['breadcrumbs'][] = ['label' => 'Expert Educations', 'url' => ['index']];

$this->params['breadcrumbs'][] = $this->title;

?>



<link href="../web/css/site.css" rel="stylesheet" />



<div class="expert-education-view">



    <p>

        <?= Html::a('Back to List', ['index'], ['class' => 'btn btn-success']) ?>
        
        <?= Html::a('Add Qualification', ['create'], ['class' => 'btn btn-success']) ?>

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

            'degree',

            'institution',

            array(
            'label'=>'From',
            'value'=>$model->from_m . "-" . $model->from_y,
            ),
            // 'degree_details',
            array(
            'label'=>'To',
            'value'=>$model->to_y != "Ongoing"? $model->to_m . "-" . $model->to_y : "Ongoing",
            ),

            'degree_details',

        ],

    ]) ?>



</div>

