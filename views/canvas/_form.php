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
/* @var $model app\models\Canvas */
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
        Choose a title that helps reader immediately understand what your project is about.
    </div>
    <?= $form->field($model, 'title')->textarea(['rows' => 1])->textInput(['placeholder' => '5-50 Characters'])->label(false) ?>
    <?php 
    $items = ArrayHelper::map(Languages::find()->all(), 'name', 'name');
     echo $form->field($model, 'language')->dropDownList($items,['prompt'=>'Please select language.'  ]);
    ?>
    
    <label class="control-label" for="canvas-content">Summary (in English) <span class="requiredquestion">*</span></label>

    <div class="alert alert-info">
        Links to external resources (dropbox, google drive, etc.) should be posted here.
    </div>
    
    <div class="alert alert-info">
        Summary should be at most 500 characters.
        <br />
        This short summary is needed so that even users who cannot read your native language can have a good idea of what your project is about. They could be interested in your project idea so make sure that it gives a clear idea of what this project will achieve if it becomes a reality.
    </div>
    
    <?= $form->field($model, 'eng_summary')->textarea(['rows' => 6])->widget(letyii\tinymce\Tinymce::className() , ['options' => ['rows'=>20],
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

    <label class="control-label" for="canvas-content">Summary <span class="requiredquestion">*</span></label>

    <div class="alert alert-info">
        Links to external resources (dropbox, google drive, etc.) should be posted here.
    </div>
    
    <div class="alert alert-info">
        Summary should be at most 2000 characters.
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
    
    <hr class="colorgraph">
    <h3> Creativity Aspects </h3>
    <hr class="colorgraph">
    <ul style="list-style-type:lower-latin;">
        <li>
            <p> What creativity or innovation will the project bring about? <span class="requiredquestion">*</span> </p>
            
            <div class="alert alert-info">
                In this part it is useful to explain if your project is:
                <br />
                <ul>
                    <li>A totally new idea that does not exist anywhere else or it is not already being done (research well).</li>
                    <li>A development of an idea that is already being implemented like a step forward.</li>
                    <li>A new way of using something that is already being done.</li>
                    <li>A new way of producing something that already exists maybe making it more efficient, more eco friendly, more economic or</li>
                    <li>An improvement of something that is being done.</li>
                </ul>
            </div>            

            <?= $form->field($model, 'selling')->widget(RemainingCharacters::classname(), [
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
                        'maxlength' => 3000,
                        'placeholder' => Yii::t('app', '2-3000 Characters')
                    ]
                ])->label(false); ?>
        </li>
        <li>
            <p> Why is your project special? <span class="requiredquestion">*</span></p>
            
            <div class="alert alert-info">
                In this part it is useful to explain how your project idea either brings together elements in a special way for example makes big change with minimum cost or environmental impact. The evaluators looking at your project will be looking for the element of surprise in your project. It is that element which makes the reader remember your project.
            </div>

            <?= $form->field($model, 'outstanding')->widget(RemainingCharacters::classname(), [
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
                        'maxlength' => 3000,
                        'placeholder' => Yii::t('app', '2-3000 Characters')
                    ]
                ])->label(false); ?>
        </li>
        <li>
            <p> What are the benefits of your project? <span class="requiredquestion">*</span> </p>
            
            <div class="alert alert-info">
                Good projects ideas are those that bring about change to the better in the lives of people, the environments, the economy, society in general. It is important that in this part you explain how the change that the project idea would bring about if it became a reality would bring about change and how this change is of benefit. When writing this part of your project keep imagine that the people who could invest in your project idea would be convinced that their investment is money well spent because of the benefits.
            </div>            

            <?= $form->field($model, 'benefits')->widget(RemainingCharacters::classname(), [
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
                        'maxlength' => 3000,
                        'placeholder' => Yii::t('app', '2-3000 Characters')
                    ]
                ])->label(false); ?>
        </li>
        <li>
            <p> How can it be promoted? (facebook, web site…) <span class="requiredquestion">*</span>  </p>
            
            <div class="alert alert-info">
                Creativity is not only in the project idea but also in how you would promote this idea if it became reality and you wanted to sell or promote it. In this area one expects a link between how you promote the project or its products and the people who would buy or invest in the idea. It could be useful to identify to whom you would address the promotion or sales effort and then identify ways and tools for promotions so that the person reading your project will see that you are focused and know how to promote your idea and the products or change it would bring about.
            </div>            

            <?= $form->field($model, 'marketed')->widget(RemainingCharacters::classname(), [
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
                        'maxlength' => 3000,
                        'placeholder' => Yii::t('app', '2-3000 Characters')
                    ]
                ])->label(false); ?>
        </li>
    </ul>
    <hr class="colorgraph">
    <h3> Technical Aspects</h3>
    <hr class="colorgraph">
    <ul style="list-style-type:lower-latin;">
        <li>
            <p> What technical resources/key activities would be required? (e.g PC, Server, Robots, Internet Service) <span class="requiredquestion">*</span> </p>

            <div class="alert alert-info">
                When filling this part make sure that you include all those elements that will make your project whole. Keep in mind concrete things as well as licences or rights, permissions etc. to implement your project. Include the activities or steps to implement your project. 
                <br />
                From this part the person looking at your project will realise that you have a clear idea of what is needed and how you can implement the project. He will know that you have thought of everything and that you will be able to make good costings for your project because everything has a cost...
            </div>
            
            <?= $form->field($model, 'tech_resources')->widget(RemainingCharacters::classname(), [
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
                        'maxlength' => 3000,
                        'placeholder' => Yii::t('app', '2-3000 Characters')
                    ]
                ])->label(false); ?>
        </li>
        <li>
            <p> What external help do you need to bring your project technically possible? <span class="requiredquestion">*</span>  </p>

            <div class="alert alert-info">
                Many projects need more than one expertise and in many cases partnerships are the best way forward. You might identify the need for this for your project. In such cases identifying the need for expert partners is the mature way forward.<br />
                Other projects may not require expertise other than the one you would imagine putting into the project yourself but may require strategic partnerships. A strategic partner could be your most likely client especially when you are proposing infrastructural project ideas. It could be a partner that will secure a client base for your project because your project would produce a new or improved service for its clients.
            </div>
            
            <?= $form->field($model, 'partners')->widget(RemainingCharacters::classname(), [
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
                        'maxlength' => 3000,
                        'placeholder' => Yii::t('app', '2-3000 Characters')
                    ]
                ])->label(false); ?>
        </li>
        <li>
            <p> What technical challenges may put your projects at risk? <span class="requiredquestion">*</span> </p>

            <div class="alert alert-info">
                For this part it is important that you go through the previous two parts in this project presentation and check if there are materials or services that the project will definitely need to be implemented but they could be difficult to obtain or find.
                <br />
                These are technical challenges so do not get distracted with funding or cost of materials but about having them and making the project happen. Describe why these technical challenges can put project and risk and more importantly identify solutions if there are any.
            </div>
            
            <?= $form->field($model, 'risk')->widget(RemainingCharacters::classname(), [
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
                        'maxlength' => 3000,
                        'placeholder' => Yii::t('app', '2-3000 Characters')
                    ]
                ])->label(false); ?>
        </li>
        <li>
            <p> Does it have any social or environmental impact? <span class="requiredquestion">*</span> </p>
            
            <div class="alert alert-info">
                Project ideas need necessarily bring about change. It is good to make an assessment of which changes the project brings about be it negative and positive. It could be a positive change that comes at a price after all.<br />
                Social impact refers to how people are effected by the output or product of the project you are sharing. If it makes something easier to use or makes life easier in general think of how people's lives are better, how more people are given access to the service or to the product.<br />
                Environmental impact refers to how your project brings about change in the natural environment as well as in the spaces in which we live. It could be that your project does not really change anything in so far as green is concerned but makes living spaces better...and that could also bring about positive social impact.<br />
                It is good to show to the whoever looks at your project to see that you did not only think of the A to Z of your project but also of why it is necessary and useful.
            </div>

            <?= $form->field($model, 'impact')->widget(RemainingCharacters::classname(), [
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
                        'maxlength' => 3000,
                        'placeholder' => Yii::t('app', '2-3000 Characters')
                    ]
                ])->label(false); ?>
        </li>
    </ul>
    <hr class="colorgraph">
    <h3> Economics Aspects </h3>
    <hr class="colorgraph">
    <ul style="list-style-type:lower-latin;">
        <li>
            <p> Which are the costs of the project? <span class="requiredquestion">*</span></p>

            <div class="alert alert-info">
                Earlier in this form you wrote about technical resources/key activities and external help that you would need to make the project a reality.<br />
                What you have put in that part can help you identify the costs of your project.<br />
                Depending on what project idea you are sharing you can either make an estimate of cost of the project as a whole or of every unit if it is about a product that can be mass produced.<br />
                These are estimates but do make some research or ask for help should you be in contact with someone who can help you with this part.<br />
                Do not leave out the work that experts need to carry out or if there is maintenance that will be required.<br />
                No one expects you to be an economist but take time to think about all the costs so that the person looking at your project idea sees that you have looked into the possibility of making this idea a reality.
            </div>
            
            <?= $form->field($model, 'fin_resources')->widget(RemainingCharacters::classname(), [
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
                        'maxlength' => 3000,
                        'placeholder' => Yii::t('app', '2-3000 Characters')
                    ]
                ])->label(false); ?>
        </li>
        <li>
            <p> Who could pay for buying or using your product/service? <span class="requiredquestion">*</span></p>

            <div class="alert alert-info">
                This question is asking you who is your client if the result can be sold be it a tangible product or a service. You can also take it to mean who would finance the project especially if it is a project that is infrastructural and therefore one could possibly sell it only once.<br />
                In some cases you can take a dual approach. You can imagine selling the product or service directly to the ultimate client or you could image that a brand buys it and sells it to its client base.<br />
                In any case if a brand is buying your product or service to resell it you still need to convince the brand to buy it and you need to think of who is the individual or company that would be ready to pay and how much.<br />
                Think of things like whether your client or the ultimate client would pay once for a service, or for a subscription or periodically.
            </div>
            
            <?= $form->field($model, 'customers')->widget(RemainingCharacters::classname(), [
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
                        'maxlength' => 3000,
                        'placeholder' => Yii::t('app', '2-3000 Characters')
                    ]
                ])->label(false); ?>
        </li>
        <li>
            <p> Will the project create income? How? <span class="requiredquestion">*</span></p>
            
            <div class="alert alert-info">
                There are products or services that can be sold but there are others that pertain to a social service so the ultimate client would not be paying for them. A public authority could buy or pay for it instead.<br />
                There are products or services that are not a basic need or not a need at tall so no one would buy it out of need but they could make an improvement on something and could make an attractive or entertaining gadget that can be bought and given out as a public relations or marketing opportunity by your client.<br />
                If you are selling the product or service to your client you could identify the price and according to the price you can even calculate what kind of client would buy it. You could identify what income bracket your client would have based on the kind of product and the minimum price at which it would be sold.<br />
                This question is important for someone to look into the economic viability of your project idea.
            </div>

            <?= $form->field($model, 'generate')->widget(RemainingCharacters::classname(), [
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
                        'maxlength' => 3000,
                        'placeholder' => Yii::t('app', '2-3000 Characters')
                    ]
                ])->label(false); ?>
        </li>
    </ul>

    <?php 
                $items = ArrayHelper::map(Sector::find()->all(), 'id', 'name');
                 echo $form->field($model, 'sector')->dropDownList($items,['prompt'=>'Please select sector' , 'onchange' => '$.get ("index.php?r=site/subsectors&id=' . '"+$(this).val(), function(data) { $("select#canvas-subsector").html(data); });' ]);

                 $items = ArrayHelper::map(SubSector::find()->where(['id' => $model->sector])->all(), 'id', 'name');
                 echo $form->field($model, 'subsector')->dropDownList($items,['prompt'=>'Please select sub-sector'  ]);
            ?>

    <?php if($model->isNewRecord) { ?>

    <?= $form->field($model, 'files[]')->fileInput(['multiple' => true]) ?>

    <p> Maximum number of files : 5. Allowed formats: png, jpg, doc, docx , pdf , ppt, pptx ,xls ,xlsx . File name must be no more than 50 characters. Maximum accepted file size is 10MB. </p>

    <?php
        }
    ?>

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
