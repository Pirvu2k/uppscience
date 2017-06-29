<?php
use yii\helpers\Html;
?>

<h1><?= $model->title ?></h1>
<h2>Evaluators</h2>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer est felis, convallis a interdum laoreet, venenatis ac leo. Mauris pulvinar eget nunc quis cursus. Suspendisse non augue dui. Donec at rhoncus nunc. Curabitur ultricies vestibulum blandit. Aenean luctus, dui eu viverra imperdiet, nulla orci suscipit magna, sit amet vestibulum erat ipsum a tortor. Maecenas in dui at tellus rutrum tempus at ac metus. Praesent venenatis, ex ut hendrerit tincidunt, enim mauris luctus ante, quis scelerisque leo sapien et ligula. In molestie magna lectus.</p>

<p>Nulla neque magna, aliquet non purus quis, efficitur laoreet massa. Ut ac est a diam bibendum bibendum non fringilla libero. Donec non nisi erat. Etiam varius congue erat ut porttitor. Morbi hendrerit velit in diam tempus cursus. In vehicula felis purus, nec rutrum ipsum condimentum vitae. Nulla euismod, risus et pulvinar dapibus, nibh sapien hendrerit ligula, et luctus magna eros non ex. Sed cursus accumsan neque non ornare. Nam vehicula, augue at tempor consequat, odio leo vehicula tortor, quis faucibus elit nulla at nisl. Nullam ornare auctor massa ut tempus. Nunc convallis rutrum sem id auctor.</p>

<p>Duis at ornare enim. Nulla in lorem cursus, condimentum justo a, vulputate diam. Sed quis tortor sagittis, condimentum nisl sed, interdum leo. Fusce euismod, urna vitae tincidunt porttitor, augue libero tempus tortor, eu rhoncus felis est eu nunc. Morbi sed ornare ex. Donec pretium facilisis scelerisque. Cras molestie tortor magna, nec volutpat turpis congue et. Aenean lacinia tincidunt urna sagittis sagittis. Nam eget justo non felis ullamcorper congue. Duis pharetra vulputate sem, ac consectetur justo porttitor ac. Morbi efficitur dolor eget lacinia imperdiet. Donec eget metus ut risus vestibulum laoreet in ut massa.</p>

   
<div class="form-group">
    <?= Html::a('Choose evaluators from a list', ['/paper/invitationslist', 'id' => $model->id], ['class'=>'btn btn-primary']) ?>
    <?= Html::a('Let the system choose evaluators for me', ['/paper/invitationsrandom', 'id' => $model->id], ['class'=>'btn btn-primary']) ?>
</div>



