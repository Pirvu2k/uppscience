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

$filterform = ActiveForm::begin(['id' => 'members-filter-form', 'method' => 'get', 'action' => Url::to(['member/mailinglist'])]);

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
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'given_name',
            'family_name',            
            'country',
            'role',
            'email'
        ],
    ]); ?>
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