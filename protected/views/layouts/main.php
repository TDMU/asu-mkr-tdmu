<?php /* @var $this Controller */ 
	function getStyleName($name)
	{
		if(!file_exists('/css/user/'.$name))
			return 'css/'.$name;
		else
			return 'css/user/'.$name;
	}
	
	function getAceStyleName($name)
	{
		if(!file_exists('/css/user/ace/'.$name))
			return 'theme/ace/assets/css/'.$name;
		else
			return 'css/user/ace/'.$name;
	}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="ru" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" type="text/css" href="<?=Yii::app()->baseUrl?>/<?=getStyleName('styles.css')?>" />

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <?php Yii::app()->bootstrap->register(); ?>

    <link rel="stylesheet" href="<?=Yii::app()->baseUrl?>/<?=getAceStyleName('font-awesome.min.css')?>" />
    <!--[if IE 7]>
    <link rel="stylesheet" href="<?=Yii::app()->baseUrl?>/theme/ace/assets/css/font-awesome-ie7.min.css" />
    <![endif]-->

    <!-- fonts -->
    <link rel="stylesheet" href="<?=Yii::app()->baseUrl?>/<?=getAceStyleName('ace-fonts.css')?>" />

    <!-- ace styles -->
    <link rel="stylesheet" href="<?=Yii::app()->baseUrl?>/<?=getAceStyleName('ace.min.css')?>" />
    <link rel="stylesheet" href="<?=Yii::app()->baseUrl?>/<?=getAceStyleName('ace-responsive.min.css')?>" />
    <link rel="stylesheet" href="<?=Yii::app()->baseUrl?>/<?=getAceStyleName('ace-skins.min.css')?>" />

    <!--[if lte IE 8]>
    <link rel="stylesheet" href="<?=Yii::app()->baseUrl?>/<?=getAceStyleName('ace-ie.min.css')?>" />
    <![endif]-->

    <!-- ace settings handler -->
    <script src="<?=Yii::app()->baseUrl?>/theme/ace/assets/js/ace-extra.min.js"></script>
    <script src="<?=Yii::app()->baseUrl?>/js/main.js"></script>
    <script>
        tt = {} // object containing translations
        tt.registerConfirm = '<?=tt('Регистрация прошла успешно! Пожалуйста, авторизируйтесь!')?>'
        tt.sendingConfirm  = '<?=tt('Ваш логин и пароль были отправлены Вам на почту!')?>'
    </script>
    <?php
        Yii::app()->clientScript->registerPackage('chosen');
        Yii::app()->clientScript->registerPackage('spin');
    ?>
</head>

<body class="navbar-fixed breadcrumbs-fixed">

    <?php require_once('_header.php')?>

    <div class="main-container container-fluid">
        <a href="#" id="menu-toggler" class="menu-toggler">
            <span class="menu-text"></span>
        </a>

        <?php require_once('_sidebar.php')?>

        <div class="main-content">
		
            <?php require_once('_breadcrumbs.php')?>

            <div class="page-content">

                <?php require_once('_flashes.php')?>

                <!-- PAGE CONTENT BEGINS -->
                <?php echo $content; ?>
                <!-- PAGE CONTENT ENDS -->
            </div>

            </div>

        </div><!-- /.main-content -->
    </div>

    <!-- basic scripts -->

    <!--[if !IE]> -->

    <script type="text/javascript">
        window.jQuery || document.write("<script src='<?=Yii::app()->baseUrl?>/theme/ace/assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
    </script>

    <!-- <![endif]-->

    <!--[if IE]>
    <script type="text/javascript">
        window.jQuery || document.write("<script src='<?=Yii::app()->baseUrl?>/theme/ace/assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
    </script>
    <![endif]-->

    <script type="text/javascript">
        if("ontouchend" in document) document.write("<script src='<?=Yii::app()->baseUrl?>/theme/ace/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
    </script>

    <!-- ace scripts -->
    <script src="<?=Yii::app()->baseUrl?>/theme/ace/assets/js/uncompressed/ace-elements.js"></script>
    <script src="<?=Yii::app()->baseUrl?>/theme/ace/assets/js/ace.min.js"></script>
    <script src="<?=Yii::app()->baseUrl?>/theme/ace/assets/js/uncompressed/bootbox.js"></script>
	<?php if(!empty(Yii::app()->params['analytics']))
			echo '<script>'.Yii::app()->params['analytics'].'</script>';
	?>
	<script>
		$(function () {
		  $('[data-toggle="tooltip"]').tooltip()
		});
	</script>
</body>
</html>
