<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ExpertExperienceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Expert Experiences';
$this->params['breadcrumbs'][] = $this->title;
?>

<link href="../web/css/site.css" rel="stylesheet" />

<div class="expert-experience-index">
    <h1>Experts</h1>
    <?= GridView::widget([
        'dataProvider' => $dataProviderExperts,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'email',
            'role',
            // 'job_description',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <h1>Students</h1>
        <?= GridView::widget([
        'dataProvider' => $dataProviderStudents,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'email',
            // 'job_description',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
