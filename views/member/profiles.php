<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ExpertExperienceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Member Profiles';
$this->params['breadcrumbs'][] = $this->title;

$filterform = ActiveForm::begin(['id' => 'members-filter-form', 'method' => 'get', 'action' => Url::to(['member/profiles'])]);

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
                'given_name',
                'family_name',            
                'country',
                [
                    'label' => 'Is Professional',
                    'format' => 'raw',
                    'value' => function ($data) 
                    {
                        return $data["is_pro"] == 1? "Yes" : "No";
                    },
                ],
                [
                    'label' => 'Is Student',
                    'format' => 'raw',
                    'value' => function ($data) 
                    { 
                        return $data["is_student"] == 1? "Yes" : "No";
                    },
                ],
                'role',
                [
                    'label' => '',
                    'format' => 'raw',
                    'value' => function ($data) 
                    { 
                    return Html::a("View Profile", ['profile', 'id' => $data["id"]]);
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

        $grid = GridView::widget($widgets);

        echo $grid;

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