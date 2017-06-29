<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ExpertExperienceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Papers List';
$this->params['breadcrumbs'][] = $this->title;

$filterform = ActiveForm::begin(['id' => 'paper-filter-form', 'method' => 'get', 'action' => Url::to(['paper/list'])]);

?>
<div class="row">
    <div class="col-md-6">
        <?php
        echo $filterform->field($filtermodel, 'discipline')->dropDownList($sectors,['prompt'=>'' ]);
        ?>
    </div>
</div>
<?php
ActiveForm::end();
?>


<div class="expert-experience-index">
    <?php
    
    $widgets = [
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Title',
                'format' => 'html',
                'value' => function ($data) 
                {
                    return Html::a($data['title'], ['view', 'id' => $data["id"]]);
                },
            ],
            [
                'label' => 'Content',
                'format' => 'html',
                'value' => function ($data) 
                {
					if(strlen($data["content"])==50)
						$data["content"].='...';
                    return $data["content"];
                },
            ],
            [
                'label' => 'Project Author',
                'format' => 'raw',
                'value' => function ($data) 
                { 
                    return Html::a($data["given_name"] . " " . $data["family_name"], ['member/profile', 'id' => $data["author"]]);
                },
            ],            
        ],
    ]; 

    if(Yii::$app->user->identity->getAdminStatus() == "yes")
        {
            array_push($widgets['columns'], ['class' => 'yii\grid\ActionColumn' , 
                                            'visibleButtons' => ['view' => false, 'update'=>false] ,
                                             'buttons' => [
                                                'delete' => function($url, $data){
                                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $data['id']], [
                                                        'class' => '',
                                                        'data' => [
                                                            'confirm' => 'Are you absolutely sure ? You will lose all the information about this user with this action.',
                                                            'method' => 'post',
                                                        ],
                                                    ]);
                                                }
                                            ]
            ]);          
        } 

    echo GridView::widget($widgets); 

    ?>
</div>

<script type="text/javascript">
$(document).ready(function()
{
    $('#papersearchfilter-discipline').change(function () 
    {
        $("#paper-filter-form").submit();
    });
});
</script>