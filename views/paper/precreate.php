<?php
use yii\helpers\Html;
?>
<p>As a member of the VISConti Community of Practice you can share ideas for projects that you wish to have reviewed by other members in the Community.</p>

<p>Having your project reviewed by other members in the Community can possibly help you improve on your project, network and grow.</p>

<p>The platform will help you create the project with several fields where to input the various aspects of the project including the technical aspects, economic as well as an explanation about the creative / innovative aspects of your project idea. The form has hints and tips that help you think while preparing the presentation of your project idea. These hints help you cover all aspects to make sure you put in the best possible effort in your presentation.</p>

<p>When you submit a project for review you can either have the platform choose other members of the Community who will review your projects or you can choose ones from a list and they will look at your project from the technical angle, the economic and the creative one. The reviewers will give you a score on each of the three aspects.</p>

<p>The reviewers who will look at your project will have a profile that matches the language and the sector to which your project belongs.</p>

<p>Upon submission of your project the reviewers will receive an invitation to look at your project as well as a note of understanding that when they look at your project they are doing so under strict confidentiality arrangement. They cannot share or use your presentation or its content in any way without your involvement and permission.</p>

<p>We suggest that you look at the form for submission of your project idea before you start so that you can prepare materials etc. however you can always edit your project before submitting it for review.</p>

<p>Good luck with your projects and thank you for being an active member in the VISConti Community of Practice.</p>
<p>
    <div class="form-group">
    <?php
    if($user->given_name == '' || $user->family_name == '')
    {
        echo "Please complete at least personal inforamtion section in your profile before you create your project";
    }
    else
    {
        echo Html::a('Create Paper', ['/paper/create'], ['class'=>'btn btn-primary']);
    }
    ?>
    </div>
</p> 
<br />
<div style="position:relative;height:0;padding-bottom:56.25%"><iframe src="https://www.youtube.com/embed/jO1wOJ1Um4s?ecver=2" width="640" height="360" frameborder="0" style="position:absolute;width:100%;height:100%;left:0" allowfullscreen></iframe></div>       

