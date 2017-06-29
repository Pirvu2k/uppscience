<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;
use yii\helpers\Url;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:title" content="Visconti" />
    <meta property="og:type" content="Community of Practice" />
    <meta property="og:url" content="http://cop.viscontiproject.eu/" />
    <meta property="og:image" content="../web/images/avatar_big.png" />
    <meta property="og:description"  content="VISConti Project, a Community of Practice where students can have their ideas and projects evaluated by true experts." />
    <meta name="theme-color" content="#5b3777" />
    <meta name="msapplication-navbutton-color" content="#5b3777" />
    <meta name="apple-mobile-web-app-status-bar-style" content="#5b3777" />
	
    <?= Html::csrfMetaTags() ?>
	<link rel="icon" type="image/png" href="../images/icon.png?v=1" />
    <title><?= Html::encode($this->title) ?></title>
    <?php //$this->head() ?>
    <?php
		$controller = Yii::$app->controller;
		$is = (($controller->action->id === "create")) ? true : false;
		if($is)
			echo '<link href="/web/assets/e473f026/dropzone/dist/min/dropzone2.min.css" rel="stylesheet">';
        if (YII_ENV_DEV)
            echo '<link href="/web/assets/ba2b43d0/toolbar.css" rel="stylesheet">	';
    ?>
	<link href='http://fonts.googleapis.com/css?family=Oxygen:400,700' rel='stylesheet'>
	<link href="../web/css/site.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="./scripts/jquery-ui.min.js"></script>
</head>
<body>
<?php $this->beginBody() ?>
<header id="primary" data-view="header_view" data-nosearch="true" data-model_name="user" data-model_id="5598ec54ad44d93f30b9f805">
    <div class="container">
        <nav class="row">
            <div class="col-sm-4">
                <ul class="nav-pills pull-left nav">
					<?php $isfaq = ($controller->action->id === "faq" || $controller->action->id === "welcome") ? true : false; ?>
					<li<?php if(!$isfaq) echo' class="active"'; ?>><a href="index.php?r=site/index">
					<?php if($isfaq && Yii::$app->user->isGuest) echo 'Login / Register'; else echo 'Home';?>
					</a></li>
					<li<?php if($isfaq) echo' class="active"'; ?>><a href="index.php?r=site/faq">FAQ</a></li>
                </ul>
            </div>

            <?php
               if(!Yii::$app->user->isGuest && Yii::$app->user->identity->getAdminStatus() == "yes")
                {
            ?>

            <div class="col-sm-4  center-block admin-bar" >
                  <ul class="nav-pills nav" style="margin-left:15px;" >
                       <li><a href="#">Members</a></li>
                       <li><a href="#">Papers</a></li>
                       <li><a href="#">Projects</a></li>
                       <li><a href="#">Abuses</a></li>
                  </ul>
            </div>

            <?php
            }
            ?>

            <div id="header-actions" class="col-sm-4" data-view="header_actions" data-model_name="user" data-model_id="5598ec54ad44d93f30b9f805">
				<?php
					if(!Yii::$app->user->isGuest){
                        echo '<a class="btn purple" href="index.php?r=member/profile&id=' . Yii::$app->user->id . '"><i class="glyphicon glyphicon-user" style="margin-right:10px;"></i>'. Yii::$app->user->identity->getName() .'</a>';
                    }
				?>
            </div>
        </nav>
    </div>
</header>

<section id="home-header" class="hero">
	<div class="container">
		<div class="row">
			</br></br>
			<img src="../images/newcovervisconti.png"> 
		</div>
	</div>
</section>


<div class="content">
	</br></br>
	<?php
        echo '<ul id="sidebar" class="col-sm-12 col-md-1"></ul>';
	?>

<?php
    NavBar::begin([
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
			'id' => 'menu1',
            'class' => 'col-md-2 col-sm-3',
        ],
    ]);
    $navItems=[
    ['label' => 'Home', 'url' => ['/site/index']]
  ];
    $navItemsRight=[];
    $createItems=[];
  if (Yii::$app->user->isGuest) {
    array_push($navItemsRight,['label' => 'Sign In', 'url' => ['/site/login']],['label' => 'Sign Up', 'url' => ['/site/register']]);
  }
  else 
    {
      array_push($navItems,
        [
            'label' => 'Create',
            'items' => [
                 ['label' => 'Create Project', 'url' => ['canvas/precreate']],
                 ['label' => 'Submit Paper', 'url' => ['paper/precreate']],
            ],
        ]);

      array_push($navItems,
        ['label'=>'Members', 'url'=>['member/profiles']]);
      array_push($navItems,
        ['label'=>'Projects', 'url'=>['canvas/list']]);
      array_push($navItems,
        ['label'=>'Papers', 'url'=>['paper/list']]);
        array_push($navItemsRight, ['label' => 'Update Profile', 'url' => Url::to(['member/update', 'id' => Yii::$app->user->id])]);
        array_push($navItemsRight,
		['label' => 'Logout',
        'url' => ['/site/logout'],
        'linkOptions' => ['data-method' => 'post']]
    );
  }

echo '<h3>Site Menu</h3>';
echo Nav::widget([
    'items' => $navItems,
]);
echo '<h3>Account Menu</h3>';
echo Nav::widget([
    'items' => $navItemsRight,
]);

if(!Yii::$app->user->isGuest &&
   Yii::$app->user->identity->getAdminStatus() == "yes")
{
    $navItemsAdmin = [];
    array_push($navItemsAdmin, ['label' => 'Member Mailing List', 'url' => Url::to(['member/mailinglist'])]);
    
    echo '<h3>Admin Tasks</h3>';
    echo Nav::widget([
        'items' => $navItemsAdmin,
    ]);    
}

    NavBar::end();
?>
    <main id="new" class="container">
        <?= $content ?>
    </main>
	<div class="clearfix visible-*"></div>
	<footer>
		<div class="container">
			<small class="row">&copy; VISConti <?= date('Y') ?></small>
		</div>
	</footer>
</div>

<script>
    $('#create-trigger').click(function(){
        $('#create-menu').toggle();
    });
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
