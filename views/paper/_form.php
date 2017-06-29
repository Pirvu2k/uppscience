<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use yii\captcha\CaptchaValidator;
use yii\captcha\CaptchaAction;
use app\models\Languages;
use yii\helpers\ArrayHelper;
use app\models\Sector;
use app\models\SubSector;
use jlorente\remainingcharacters\RemainingCharacters;


/* @var $this yii\web\View */
/* @var $model app\models\Paper */
/* @var $form yii\widgets\ActiveForm */
?>

<style>

div.required label:after {
    content: " *";
    color: red;
}

.requiredquestion {
    color:red;
}

</style>

<div class="canvas-form">

    <?php $form = ActiveForm::begin(['enableClientValidation' => false , 'enableAjaxValidation' => false, 'options' => ['enctype'=>'multipart/form-data']]); ?>
    <label class="control-label" for="canvas-content">Title <span class="requiredquestion">*</span></label>
    <div class="alert alert-info">
        Choose a title that helps reader immediately understand what your paper is about.
    </div>
    <?= $form->field($model, 'title')->textarea(['rows' => 1])->textInput(['placeholder' => '5-255 Characters'])->label(false) ?>
     <label class="control-label" for="canvas-content">Title In English <span class="requiredquestion">*</span></label>
    <?= $form->field($model, 'title_in_english')->textarea(['rows' => 1])->textInput(['placeholder' => '5-255 Characters'])->label(false) ?>
    <?php 
    $items = ArrayHelper::map(Languages::find()->all(), 'id', 'name');
     echo $form->field($model, 'language')->dropDownList($items,['prompt'=>'Please select language.'  ]);
    ?>
    
    <?php
     $items = ArrayHelper::map(Sector::find()->all(), 'id', 'name');
    echo $form->field($model, 'discipline')->dropDownList($items,['prompt'=>'Please select sector'  ]);
    ?>
   
    <label class="control-label" for="canvas-content">Abstract <span class="requiredquestion">*</span></label>

    <div class="alert alert-info">
        Links to external resources (dropbox, google drive, etc.) should be posted here.
    </div>
    
    <div class="alert alert-info">
        Abstract should be at most 2000 characters.
        <br />
        This short summary is needed so that even users who cannot read your native language can have a good idea of what your project is about. They could be interested in your project idea so make sure that it gives a clear idea of what this project will achieve if it becomes a reality.
    </div>
    
    <?= $form->field($model, 'abstract')->widget(RemainingCharacters::classname(), [
                    'type' => RemainingCharacters::INPUT_TEXTAREA,
                    'text' => Yii::t('app', '{n} characters remaining'),
                    'label' => [
                        'tag' => 'p',
                        'id' => 'my-counter',
                        'class' => 'counter',
                        'invalidClass' => 'error'
                    ],
                    'options' => [
                        'rows' => '3',
                        'class' => 'form-control',
                        'maxlength' => 2000,
                        'placeholder' => Yii::t('app', '2-2000 Characters')
                    ]
                ])->label(false); ?>

    <label class="control-label" for="canvas-content">Abstract In English <span class="requiredquestion">*</span></label>

    <div class="alert alert-info">
        Links to external resources (dropbox, google drive, etc.) should be posted here.
    </div>
    
    <div class="alert alert-info">
        Abstract should be at most 2000 characters.
        <br />
        This short summary is needed so that even users who cannot read your native language can have a good idea of what your project is about. They could be interested in your project idea so make sure that it gives a clear idea of what this project will achieve if it becomes a reality.
    </div>
    
     <?= $form->field($model, 'abstract_in_english')->widget(RemainingCharacters::classname(), [
                    'type' => RemainingCharacters::INPUT_TEXTAREA,
                    'text' => Yii::t('app', '{n} characters remaining'),
                    'label' => [
                        'tag' => 'p',
                        'id' => 'my-counter',
                        'class' => 'counter',
                        'invalidClass' => 'error'
                    ],
                    'options' => [
                        'rows' => '3',
                        'class' => 'form-control',
                        'maxlength' => 2000,
                        'placeholder' => Yii::t('app', '2-2000 Characters')
                    ]
                ])->label(false); ?>

    <label class="control-label" for="canvas-content">Keywords <span class="requiredquestion">*</span></label>

    <div class="alert alert-info">
        Links to external resources (dropbox, google drive, etc.) should be posted here.
    </div>
    
    <div class="alert alert-info">
        Keywords should be at most 255 characters.
        <br />
        This short summary is needed so that even users who cannot read your native language can have a good idea of what your project is about. They could be interested in your project idea so make sure that it gives a clear idea of what this project will achieve if it becomes a reality.
    </div>
    
     <?= $form->field($model, 'keywords')->widget(RemainingCharacters::classname(), [
                    'type' => RemainingCharacters::INPUT_TEXTAREA,
                    'text' => Yii::t('app', '{n} characters remaining'),
                    'label' => [
                        'tag' => 'p',
                        'id' => 'my-counter',
                        'class' => 'counter',
                        'invalidClass' => 'error'
                    ],
                    'options' => [
                        'rows' => '3',
                        'class' => 'form-control',
                        'maxlength' => 255,
                        'placeholder' => Yii::t('app', '2-255 Characters')
                    ]
                ])->label(false); ?>

    <label class="control-label" for="canvas-content">Introduction <span class="requiredquestion">*</span></label>

    <div class="alert alert-info">
        Links to external resources (dropbox, google drive, etc.) should be posted here.
    </div>
    
    <div class="alert alert-info">
        
        <br />
        The Summary is a short description of your project including how the idea came to your mind, the key elements and what benefit or change it will bring about.
        <br />
        The Summary is the first thing that anyone looking at your project will read so it needs to be written with care.
        <br />
        The panel makes it possible for you to insert video, links, special characters and images. Links to external resources (dropbox, google drive, etc.) should be posted here.
    </div>    

    <?= $form->field($model, 'introduction')->textarea(['rows' => 6])->widget(letyii\tinymce\Tinymce::className() , ['options' => ['rows'=>20],
        'configs' => [
        'plugins' => [
            "advlist autolink lists link charmap print preview anchor ",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste imagetools",
            "textcolor",
        ],
        'toolbar' => "undo redo | styleselect | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link ",
        'statusbar' => 'false',
        ],
        

    ])->label(false) ?>

    <label class="control-label" for="canvas-content">Content <span class="requiredquestion">*</span></label>

    <div class="alert alert-info">
        Links to external resources (dropbox, google drive, etc.) should be posted here.
    </div>
    
    <div class="alert alert-info">
        
        <br />
        The Summary is a short description of your project including how the idea came to your mind, the key elements and what benefit or change it will bring about.
        <br />
        The Summary is the first thing that anyone looking at your project will read so it needs to be written with care.
        <br />
        The panel makes it possible for you to insert video, links, special characters and images. Links to external resources (dropbox, google drive, etc.) should be posted here.
    </div>    

    <?= $form->field($model, 'content')->textarea(['rows' => 6])->widget(letyii\tinymce\Tinymce::className() , ['options' => ['rows'=>20],
        'configs' => [
        'plugins' => [
            "advlist autolink lists link charmap print preview anchor ",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste imagetools",
            "textcolor",
        ],
        'toolbar' => "undo redo | styleselect | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link ",
        'statusbar' => 'false',
        ],
        

    ])->label(false) ?>

    <label class="control-label" for="canvas-content">Conclusion <span class="requiredquestion">*</span></label>

    <div class="alert alert-info">
        Links to external resources (dropbox, google drive, etc.) should be posted here.
    </div>
    
    <div class="alert alert-info">
        
        <br />
        The Summary is a short description of your project including how the idea came to your mind, the key elements and what benefit or change it will bring about.
        <br />
        The Summary is the first thing that anyone looking at your project will read so it needs to be written with care.
        <br />
        The panel makes it possible for you to insert video, links, special characters and images. Links to external resources (dropbox, google drive, etc.) should be posted here.
    </div>    

    <?= $form->field($model, 'conclusion')->textarea(['rows' => 6])->widget(letyii\tinymce\Tinymce::className() , ['options' => ['rows'=>20],
        'configs' => [
        'plugins' => [
            "advlist autolink lists link charmap print preview anchor ",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste imagetools",
            "textcolor",
        ],
        'toolbar' => "undo redo | styleselect | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link ",
        'statusbar' => 'false',
        ],
        

    ])->label(false) ?>

    <label class="control-label" for="canvas-content">References <span class="requiredquestion">*</span></label>

    <div class="alert alert-info">
        Links to external resources (dropbox, google drive, etc.) should be posted here.
    </div>
    
    <div class="alert alert-info">
        
        <br />
        The Summary is a short description of your project including how the idea came to your mind, the key elements and what benefit or change it will bring about.
        <br />
        The Summary is the first thing that anyone looking at your project will read so it needs to be written with care.
        <br />
        The panel makes it possible for you to insert video, links, special characters and images. Links to external resources (dropbox, google drive, etc.) should be posted here.
    </div>    

    <?= $form->field($model, 'references')->textarea(['rows' => 6])->widget(letyii\tinymce\Tinymce::className() , ['options' => ['rows'=>20],
        'configs' => [
        'plugins' => [
            "advlist autolink lists link charmap print preview anchor ",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste imagetools",
            "textcolor",
        ],
        'toolbar' => "undo redo | styleselect | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link ",
        'statusbar' => 'false',
        ],
        

    ])->label(false) ?>


    <div class="form-group">
        <div class="row">
            <div class="col-md-2">
                <?= Html::a('Save', null , ['class'=>'btn btn-primary btn-primary-save']) ?>
            </div>
            <?php
            if($model->status == "Draft" || $model->isNewRecord)
            {
            ?>
            <div class="col-md-2">
                <?= Html::a('Submit', null, ['class'=>'btn btn-primary btn-primary-submit']) ?>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
    <input type="hidden" name="mode" id="mode" value="" />
    <?php ActiveForm::end(); ?>

    <div class="col-xs-6 col-md-2 pull-right">
        <div class="alert alert-danger">
            <center><strong class="requiredquestion">*</stong> is required.</center>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() 
    {
        $(".btn-primary-save").click(function() 
        {
            $("#mode").val("save");
            $("#w0").submit();
            $(this).preventDefault();
        });
        
        $(".btn-primary-submit").click(function() 
        {
            $("#mode").val("submit");
            $("#w0").submit();
            $(this).preventDefault();
        });
    });
</script>
