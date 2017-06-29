<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ExpertExperienceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Project List';
$this->params['breadcrumbs'][] = $this->title;

$filterform = ActiveForm::begin(['id' => 'canvas-filter-form', 'method' => 'get', 'action' => Url::to(['canvas/list'])]);

?>
<div class="row">
    <div class="col-md-6">
        <?php
        echo $filterform->field($filtermodel, 'sector')->dropDownList($sectors,['prompt'=>'' ]);
        ?>
    </div>
    <div class="col-md-6">
        <?php
        echo $filterform->field($filtermodel, 'subsector')->dropDownList($subsectors,['prompt'=>''  ]);
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
            'title',     
            [
                'label' => 'English Summry',
                'format' => 'html',
                'value' => function ($data) 
                { 
                    return $data["eng_summary"];
                },
            ],
            [
                'label' => 'Project Author',
                'format' => 'raw',
                'value' => function ($data) 
                { 
                    return $data["given_name"] . " " . $data["family_name"];
                },
            ],
            [
                'label' => '',
                'format' => 'raw',
                'value' => function ($data) 
                { 
                    return Html::a("View", ['profile', 'id' => $data["id"]]);
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
    $('#membersearchfilter-sector, #membersearchfilter-subsector').change(function () 
    {
        $("#members-filter-form").submit();
    });
    
    $('#canvassearchfilter-sector, #canvassearchfilter-subsector').change(function () 
    {
        $("#canvas-filter-form").submit();
    });
});
</script>