<?php
use yii\helpers\Html;
?>

<h1><?= $model->title ?></h1>
<h2>Evaluators</h2>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer est felis, convallis a interdum laoreet, venenatis ac leo. Mauris pulvinar eget nunc quis cursus. Suspendisse non augue dui. Donec at rhoncus nunc. Curabitur ultricies vestibulum blandit. Aenean luctus, dui eu viverra imperdiet, nulla orci suscipit magna, sit amet vestibulum erat ipsum a tortor. Maecenas in dui at tellus rutrum tempus at ac metus. Praesent venenatis, ex ut hendrerit tincidunt, enim mauris luctus ante, quis scelerisque leo sapien et ligula. In molestie magna lectus.</p>

<p>Nulla neque magna, aliquet non purus quis, efficitur laoreet massa. Ut ac est a diam bibendum bibendum non fringilla libero. Donec non nisi erat. Etiam varius congue erat ut porttitor. Morbi hendrerit velit in diam tempus cursus. In vehicula felis purus, nec rutrum ipsum condimentum vitae. Nulla euismod, risus et pulvinar dapibus, nibh sapien hendrerit ligula, et luctus magna eros non ex. Sed cursus accumsan neque non ornare. Nam vehicula, augue at tempor consequat, odio leo vehicula tortor, quis faucibus elit nulla at nisl. Nullam ornare auctor massa ut tempus. Nunc convallis rutrum sem id auctor.</p>

<p>Duis at ornare enim. Nulla in lorem cursus, condimentum justo a, vulputate diam. Sed quis tortor sagittis, condimentum nisl sed, interdum leo. Fusce euismod, urna vitae tincidunt porttitor, augue libero tempus tortor, eu rhoncus felis est eu nunc. Morbi sed ornare ex. Donec pretium facilisis scelerisque. Cras molestie tortor magna, nec volutpat turpis congue et. Aenean lacinia tincidunt urna sagittis sagittis. Nam eget justo non felis ullamcorper congue. Duis pharetra vulputate sem, ac consectetur justo porttitor ac. Morbi efficitur dolor eget lacinia imperdiet. Donec eget metus ut risus vestibulum laoreet in ut massa.</p>

<?php
if((count($existing_members["Active"]) + count($existing_members["Finalized"])) < 3)
{
?>
<h3>Evaluators needed to evaluate this project</h3>
<ul>
    <?php
    $technical_needed = true;
    $economical_needed = true;
    $creative_needed = true;
    
    foreach($existing_members["Finalized"] as $item)
    {
        if($item->role == "Technical") { $technical_needed = false; }
        if($item->role == "Economical") { $economical_needed = false; }
        if($item->role == "Creative") { $creative_needed = false; }
    }
    
    foreach($existing_members["Active"] as $item)
    {
        if($item->role == "Technical") { $technical_needed = false; }
        if($item->role == "Economical") { $economical_needed = false; }
        if($item->role == "Creative") { $creative_needed = false; }
    }
    
    if($technical_needed) { echo "<li>Technical evaluator</li>"; }
    if($economical_needed) { echo "<li>Economics evaluator</li>"; }
    if($creative_needed) { echo "<li>Creativity evaluator</li>"; }
    ?>
</ul>
<?php
}

if(count($existing_members["Finalized"]) > 0)
{
?>
<h3>Evaluators who finished evaluating this project</h3>
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
<h3>Evaluators who accepted evaluating this project</h3>
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
<h3>Pending invitations</h3>
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
<h3>Expired and cancelled invitations</h3>
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

if((count($existing_members["Active"]) + count($existing_members["Finalized"])) < 3 )
{
?>       
<div class="form-group">
    <?= Html::a('Choose evaluators from a list', ['/canvas/invitationslist', 'id' => $model->id], ['class'=>'btn btn-primary']) ?>
    <?= Html::a('Let the system choose evaluators for me', ['/canvas/invitationsrandom', 'id' => $model->id], ['class'=>'btn btn-primary']) ?>
</div>
<?php
}
?>


