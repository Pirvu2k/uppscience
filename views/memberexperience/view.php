<?php



use yii\helpers\Html;

use yii\widgets\DetailView;



/* @var $this yii\web\View */

/* @var $model app\models\ExpertExperience */



$this->title = $model->id;

$this->params['breadcrumbs'][] = ['label' => 'Expert Experiences', 'url' => ['index']];

$this->params['breadcrumbs'][] = $this->title;

?>



<link href="../web/css/site.css" rel="stylesheet" />



<div class="expert-experience-view">





    <p>

        <?= Html::a('Back to List', ['index'], ['class' => 'btn btn-success']) ?>

        <?= Html::a('Add Work Experience', ['create'], ['class' => 'btn btn-success']) ?>
        
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

            'job_title',

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

            'job_description',

        ],

    ]) ?>



</div>

