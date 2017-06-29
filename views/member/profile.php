<?php
use yii\helpers\Html;

$ed_check=false;
$contact_check=false;
$experience_check=false;
$personal_check=false;
$bio_check=false;
$specialization_check=false;

function getSpec($id, $type) // getter function for specialization
{   
    if($type=='sector')
    {
        $sector = app\models\Sector::find()->where(['id' => $id])->one();
        return $sector->name;
    }

    if($type=='subsector')
    {
        $subsector = app\models\SubSector::find()->where(['id' => $id])->one();
        return $subsector->name;
    }

    if($type=='specialization')
    {
        $spec = app\models\Specialization::find()->where(['id' => $id])->one();
        return $spec->name;
    }

    if($type=='interest')
    {
        $interest = app\models\Interest::find()->where(['id' => $id])->one();
        return $interest->name;
    }
}

$this->title = 'View Profile';
$this->params['breadcrumbs'][] = ['label' => 'Expert Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expert-account-view">

    <div class="row">
        <div class="col-md-12 col-xs-12">
                <div class="well panel panel-default">
                        <div class="panel-body">
                                <div class="col-xs-12 col-sm-8">
                                        <h2><i class="glyphicon glyphicon-user text-muted"></i> <?= Html::encode($model->title) . " " . Html::encode($model->given_name) . " " . Html::encode($model->family_name)  ?></h2>
                                        <?php if (!empty($model->role)) { ?>
                                                <p><strong>Role : </strong> <?= $model->role ?></p>
                                        <?php } ?>
                                        <?php if (!empty($model->country)) { $contact_check=true; ?>
                                                <p><i class="glyphicon glyphicon-globe text-muted"></i> Country: <?= Html::a(Html::encode($model->country)) ?> <?php if (!empty($model->state)) { ?> , <?= Html::a(Html::encode($model->state)) ?> <?php  } ?></p>
                                        <?php  } ?>
                                        <?php if (!empty($model->website)) { ?>
                                                <p><strong><i class="glyphicon glyphicon-globe text-muted"></i> Website:</strong> <?= Html::a(Html::encode($model->website), Html::encode($model->website)) ?></p>
                                        <?php }    ?>
                                        <?php if (!empty($model->birth_year)) { ?>
                                                <p><strong><i class="glyphicon glyphicon-calendar text-muted"></i> Year of Birth :</strong> <?= Html::a(Html::encode($model->birth_year), Html::encode($model->birth_year)) ?></p>
                                        <?php } ?>
                                        <p><strong><i class="glyphicon glyphicon-time text-muted"></i> Joined on: </strong> <?= $model->created_on ?>
                                        <?php if (!empty($model->address)) { $contact_check=true; ?>
                                                <p><i class="glyphicon glyphicon-map-marker text-muted"></i> Address: <?php if (!empty($model->city)) { ?> <?= Html::a(Html::encode($model->city)) ?> , <?php  } ?> <?= Html::a(Html::encode($model->address)) ?></p>
                                        <?php  } ?>
                                        <?php if (!empty($model->zip)) { $contact_check=true; ?>
                                                <p><i class="glyphicon glyphicon-home text-muted"></i> Zip/Postal Cod: <?= Html::a(Html::encode($model->zip)) ?></p>
                                        <?php  } if($contact_check == false) echo "<p>Nothing here.</p>";?>

                                </div>        
                        </div>
                </div>
                </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <?php if (!empty($model->bio)) { ?>
            <div class="col-xs-12">
                <blockquote>
                        <p style="word-break: break-all;width:600px;"><?= Html::encode($model->bio) ?></p>
                </blockquote>
            </div>
            <?php  } ?>
        </div>
    </div>
    <?php
    if(count($projects_submitted) > 0 ||
       count($projects_evaluated) > 0)
    {
    ?>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
        <h3>Activities in Community:</h3>

        <?php if (!empty($papers)) { ?>
                <h4>Papers Submitted:</h4> 
                            <ul>
                                    <?php foreach($papers as $item) : ?>
                                <li style="margin-top:5px;"><i class="glyphicon glyphicon-check text-muted"></i><?php echo Html::a($item->title, ['paper/view', 'id' => $item->id]) ?></li>
                                    <?php endforeach; ?>
                            </ul>
        <?php  } ?> 


        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
                <?php if (!empty($projects_submitted)) { ?>
                <h4>Projects Submitted:</h4> 
                            <ul>
                                    <?php foreach($projects_submitted as $item) : ?>
                                <li style="margin-top:5px;"><i class="glyphicon glyphicon-check text-muted"></i><?php echo Html::a($item->title, ['canvas/profile', 'id' => $item->id]) ?></li>
                                    <?php endforeach; ?>
                            </ul>
                <?php  } ?> 
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
                <?php if (!empty($projects_evaluated)) { ?>
                <h4>Evaluated Projects:</h4> 
                            <ul>
                                    <?php foreach($projects_evaluated as $item) : ?>
                                            <li style="margin-top:5px;"><i class="glyphicon glyphicon-check text-muted"></i><?php echo Html::a($item->canvas->title, ['canvas/profile', 'id' => $item->canvas->id]) ?></li>
                                    <?php endforeach; ?>
                            </ul>
                <?php  } ?> 
        </div>
    </div>    
    <?php
    }
    ?>
    <div class="row">
        <?php if (!empty($languages)) { ?>
        <div class="col-xs-12 col-sm-6">
                <h3>Languages:</h3>
                <ul>
                <?php foreach($languages as $item) : ?>
                    <li style="margin-top:5px;"><i class="glyphicon glyphicon-globe text-muted"></i> <?= $item->language ?></li>
                <?php endforeach; ?>
                </ul>
        </div>
        <?php } ?>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
                <h3>Education:</h3>
                <?php if (!empty($education)) { $ed_check=true; ?>
                                        <ul>
                                                <?php foreach($education as $item) : ?>
                                                        <li><i class="glyphicon glyphicon-check text-muted"></i> <?= $item->degree . " at " . $item->institution . " , from " . FormatMonthYear($item->from_m, $item->from_y) . " to " . FormatMonthYear($item->to_m, $item->to_y) ?></li>
                                                        <li style="margin-left:10px;"><i class="glyphicon glyphicon-cog text-muted"></i> Degree Details: <?= $item->degree_details ?></li>
                                                <?php endforeach; ?>
                                        </ul>
                        </p>
                <?php  } if($ed_check == false) echo "<p>No education records</p>";  ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
        <h3>Experience:</h3>
                <?php if (!empty($experience)) { $experience_check=true; ?>
                                        <ul>
                                                <?php foreach($experience as $item) : ?>
                                                        <li style="margin-top:5px;"><i class="glyphicon glyphicon-check text-muted"></i> <?= $item->job_title . " at " . $item->institution . " , from " . FormatMonthYear($item->from_m, $item->from_y) . " to " . FormatMonthYear($item->to_m, $item->to_y) ?></li>
                                                        <li style="margin-left:10px;"><i class="glyphicon glyphicon-cog text-muted"></i> Job Details: <?= $item->job_description ?></li>
                                                <?php endforeach; ?>
                                        </ul>
                <?php  }  if($experience_check == false) echo '<p>No experience records</p>'; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
        <h3>Specialisation:</h3>
                <?php if (!empty($sectors)) { $specialization_check=true; ?>
                <h4>Sectors:</h4> 
                            <ul>
                                    <?php foreach($sectors as $item) : ?>
                                            <li style="margin-top:5px;"><i class="glyphicon glyphicon-check text-muted"></i> <?= getSpec($item->sector_id,'sector'); ?></li>
                                    <?php endforeach; ?>
                            </ul>
                <?php  } ?> 
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
        <?php if (!empty($subsectors)) { $specialization_check=true; ?>
        <h4>Sub-Sectors:</h4> 
                                <ul>
                                        <?php foreach($subsectors as $item) : ?>
                                                <li style="margin-top:5px;"><i class="glyphicon glyphicon-check text-muted"></i> <?= getSpec($item->subsector,'subsector'); ?></li>
                                        <?php endforeach; ?>
                                </ul>
        <?php  } ?> 
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
        <?php if (!empty($specializations)) { $specialization_check=true; ?>
        <h4>Specialisations:</h4> 
            <ul>
                    <?php foreach($specializations as $item) : ?>
                            <li style="margin-top:5px;"><i class="glyphicon glyphicon-check text-muted"></i> <?= getSpec($item->specialization,'specialization'); ?></li>
                    <?php endforeach; ?>
            </ul>
        <?php  } ?> 
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
        <?php if (!empty($interests)) { $specialization_check=true; ?>
        <h4>Interests:</h4> 
            <ul>
                    <?php foreach($interests as $item) : ?>
                            <li style="margin-top:5px;"><i class="glyphicon glyphicon-check text-muted"></i> <?= getSpec($item->interest,'interest'); ?></li>
                    <?php endforeach; ?>
            </ul>
        <?php  } ?> 
        </div>
    </div>
</div>
<?php
function FormatMonthYear($m, $y)
{
    if($m != null && $m != "")
    {
        if($y == "Ongoing")
        {
            return "Ongoing";
        }
        else
        {
            return "$m-$y";
        }
    }
    else
    {
        return $y;
    }
}
