<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<h1><?= $model->title ?></h1>
<h2>Finding Evaluators</h2>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer est felis, convallis a interdum laoreet, venenatis ac leo. Mauris pulvinar eget nunc quis cursus. Suspendisse non augue dui. Donec at rhoncus nunc. Curabitur ultricies vestibulum blandit. Aenean luctus, dui eu viverra imperdiet, nulla orci suscipit magna, sit amet vestibulum erat ipsum a tortor. Maecenas in dui at tellus rutrum tempus at ac metus. Praesent venenatis, ex ut hendrerit tincidunt, enim mauris luctus ante, quis scelerisque leo sapien et ligula. In molestie magna lectus.</p>

<p>Nulla neque magna, aliquet non purus quis, efficitur laoreet massa. Ut ac est a diam bibendum bibendum non fringilla libero. Donec non nisi erat. Etiam varius congue erat ut porttitor. Morbi hendrerit velit in diam tempus cursus. In vehicula felis purus, nec rutrum ipsum condimentum vitae. Nulla euismod, risus et pulvinar dapibus, nibh sapien hendrerit ligula, et luctus magna eros non ex. Sed cursus accumsan neque non ornare. Nam vehicula, augue at tempor consequat, odio leo vehicula tortor, quis faucibus elit nulla at nisl. Nullam ornare auctor massa ut tempus. Nunc convallis rutrum sem id auctor.</p>

<p>Duis at ornare enim. Nulla in lorem cursus, condimentum justo a, vulputate diam. Sed quis tortor sagittis, condimentum nisl sed, interdum leo. Fusce euismod, urna vitae tincidunt porttitor, augue libero tempus tortor, eu rhoncus felis est eu nunc. Morbi sed ornare ex. Donec pretium facilisis scelerisque. Cras molestie tortor magna, nec volutpat turpis congue et. Aenean lacinia tincidunt urna sagittis sagittis. Nam eget justo non felis ullamcorper congue. Duis pharetra vulputate sem, ac consectetur justo porttitor ac. Morbi efficitur dolor eget lacinia imperdiet. Donec eget metus ut risus vestibulum laoreet in ut massa.</p>

<?php
if((count($existing_members["Active"]) + count($existing_members["Finalized"])) < 3)
{
?>
<h3>Experts needed to evaluate this project</h3>
<ul>
    <?php
    $technical_needed = true;
    $economical_needed = true;
    $creative_needed = true;
    $technical_needed_id = "";
    $economical_needed_id = "";
    $creative_needed_id = "";
    
    foreach($existing_members["Finalized"] as $item)
    {
        if($item->role == "Technical") { $technical_needed_id = $item->member; $technical_needed = false; }
        if($item->role == "Economical") { $economical_needed_id = $item->member; $economical_needed = false; }
        if($item->role == "Creative") { $creative_needed_id = $item->member; $creative_needed = false; }
    }
    
    foreach($existing_members["Active"] as $item)
    {
        if($item->role == "Technical") { $technical_needed_id = $item->member; $technical_needed = false; }
        if($item->role == "Economical") { $economical_needed_id = $item->member; $economical_needed = false; }
        if($item->role == "Creative") { $creative_needed_id = $item->member; $creative_needed = false; }
    }
    
    foreach($existing_members["Pending"] as $item)
    {
        if($item->role == "Technical") { $technical_needed_id = $item->member; }
        if($item->role == "Economical") { $economical_needed_id = $item->member; }
        if($item->role == "Creative") { $creative_needed_id = $item->member; }
    }
    
    if($technical_needed) { echo "<li>Technical expert</li>"; }
    if($economical_needed) { echo "<li>Economics expert</li>"; }
    if($creative_needed) { echo "<li>Creativity expert</li>"; }
    ?>
</ul>
<?php
}

if((count($technical_members) > 0 || 
   count($economical_members) > 0 ||
   count($creative_members) > 0) &&
   ($technical_needed ||
    $economical_needed ||
    $creative_needed))
{
?>
<h3>Experts available to invite</h3>
<?php
$form = ActiveForm::begin(['enableClientValidation' => false , 'enableAjaxValidation' => false, 'options' => ['enctype'=>'multipart/form-data']]);
?>
<input type='hidden' name='evaluation_request_submission' value='1' />
<?php        
if(count($technical_members) > 0 && $technical_needed)
{
?>
<h4>Available technical experts</h4>
<ul>
    <?php 
    foreach($technical_members as $item)
    {
?>
    <li style="list-style-type: none;"> <input <?php if($item->id == $technical_needed_id ) { echo "checked='checked'"; } ?> type="radio" name="Technical" value="<?php echo $item->id ?>"> <?php echo $item->given_name . " " . $item->family_name . ($item->country != ""? " - " . $item->country : ""); ?></li>
<?php    
    }
?>
</ul>
<?php
}
?>

<?php
if(count($economical_members) > 0 && $economical_needed)
{
?>
<h4>Available economics experts</h4>
<ul>
    <?php 
    foreach($economical_members as $item)
    {
?>
    <li style="list-style-type: none;"><input <?php if($item->id == $economical_needed_id ) { echo "checked='checked'"; } ?> type="radio" name="Economical" value="<?php echo $item->id ?>"> <?php echo $item->given_name . " " . $item->family_name . ($item->country != ""? " - " . $item->country : ""); ?></li>
<?php    
    }
?>
</ul>
<?php
}
?>

<?php
if(count($creative_members) > 0 && $creative_needed)
{
?>
<h4>Available creativity experts</h4>
<ul>
    <?php 
    foreach($creative_members as $item)
    {
?>
    <li style="list-style-type: none;"><input <?php if($item->id == $creative_needed_id ) { echo "checked='checked'"; } ?> type="radio" name="Creative" value="<?php echo $item->id ?>"> <?php echo $item->given_name . " " . $item->family_name . ($item->country != ""? " - " . $item->country : ""); ?></li>
<?php    
    }
?>
</ul>
<?php
}
?>
<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Invite Experts', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
<?php
ActiveForm::end();
}

if(count($existing_members["Finalized"]) > 0 ||
   count($existing_members["Active"]) > 0 ||
   count($existing_members["Pending"]) > 0 ||
   count($existing_members["Expired"]) > 0)
{
?>
<h3>Project evaluation status</h3>
<?php    
if(count($existing_members["Finalized"]) > 0)
{
?>
<h4>Experts who finished evaluating this project</h4>
<ul>
    <?php 
    foreach($existing_members["Finalized"] as $item)
    {
?>
    <li><?php echo $item->role . ": " . $item->evaluator->given_name . " " . $item->evaluator->family_name; ?></li>
<?php    
    }
?>
</ul>
<?php
}
?>

<?php
if(count($existing_members["Active"]) > 0)
{
?>
<h4>Experts who accepted evaluating this project</h4>
<ul>
    <?php 
    foreach($existing_members["Active"] as $item)
    {
?>
    <li><?php echo $item->role . ": " . $item->evaluator->given_name . " " . $item->evaluator->family_name; ?></li>
<?php    
    }
?>
</ul>
<?php
}
?>

<?php
if(count($existing_members["Pending"]) > 0)
{
?>
<h4>Pending invitations</h4>
<ul>
    <?php 
    foreach($existing_members["Pending"] as $item)
    {
?>
    <li><?php echo $item->role . ": " . $item->evaluator->given_name . " " . $item->evaluator->family_name; ?></li>
<?php    
    }
?>
</ul>
<?php
}
?>

<?php
if(count($existing_members["Expired"]) > 0)
{
?>
<h4>Expired and cancelled invitations</h4>
<ul>
    <?php 
    foreach($existing_members["Expired"] as $item)
    {
?>
    <li><?php echo $item->role . ": " . $item->evaluator->given_name . " " . $item->evaluator->family_name; ?></li>
<?php    
    }
?>
</ul>
<?php
}
}
?>


