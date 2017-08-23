<?php
/* @var $this Controller */
    /*function getStyleName($name)
	{
		return 'css/'.$name;
	}*/
	
	/*function getAceStyleName($name)
	{
		return 'theme/ace/assets/css/'.$name;
	}*/
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="ru" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" type="text/css" href="<?=Yii::app()->baseUrl?>/css/styles.css" />


    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <?php Yii::app()->bootstrap->register(); ?>
    <?php
        if(file_exists(Yii::getPathOfAlias('webroot').'/css/user.css')):
        ?>
            <link rel="stylesheet" href="<?=Yii::app()->baseUrl?>/css/user.css" />
        <?php
        endif;
    ?>
    <link rel="stylesheet" href="<?=Yii::app()->baseUrl?>/theme/ace/assets/css/font-awesome.min.css" />
    <!--[if IE 7]>
    <link rel="stylesheet" href="<?=Yii::app()->baseUrl?>/theme/ace/assets/css/font-awesome-ie7.min.css"/>
    <![endif]-->

    <!-- fonts -->
    <link rel="stylesheet" href="<?=Yii::app()->baseUrl?>/theme/ace/assets/css/ace-fonts.css" />

    <!-- ace styles -->
    <link rel="stylesheet" href="<?=Yii::app()->baseUrl?>/theme/ace/assets/css/ace.min.css" />
    <link rel="stylesheet" href="<?=Yii::app()->baseUrl?>/theme/ace/assets/css/ace-responsive.min.css" />
    <link rel="stylesheet" href="<?=Yii::app()->baseUrl?>/theme/ace/assets/css/ace-skins.min.css" />

    <!--[if lte IE 8]>
    <link rel="stylesheet" href="<?=Yii::app()->baseUrl?>/theme/ace/assets/css/ace-ie.min.css" />
    <![endif]-->

    <!-- ace settings handler -->
    <script src="<?=Yii::app()->baseUrl?>/theme/ace/assets/js/ace-extra.min.js"></script>
    <script src="<?=Yii::app()->baseUrl?>/js/main.js"></script>
    <script>
        tt = {} // object containing translations
        tt.registerConfirm = '<?=tt('Регистрация прошла успешно! Пожалуйста, авторизируйтесь!')?>'
        tt.sendingConfirm  = '<?=tt('Интсрукция была отправлены Вам на почту!')?>'
    </script>
    <?php
        Yii::app()->clientScript->registerPackage('chosen');
        Yii::app()->clientScript->registerPackage('spin');
    ?>
    <?php if(!empty(Yii::app()->params['analytics']))
        echo '<script>'.Yii::app()->params['analytics'].'</script>';
    ?>
</head>

<body class="navbar-fixed breadcrumbs-fixed">

    <?php require_once('_header.php')?>

    <div class="main-container">
        <a href="#" id="menu-toggler" class="menu-toggler">
            <span class="menu-text"></span>
        </a>
        <script type="text/javascript">
                try{ace.settings.check('main-container' , 'fixed')}catch(e){}
        </script>

        <?php require_once('_sidebar.php')?>

        <div class="main-content">

            <?php require_once('_breadcrumbs.php')?>

            <div class="page-content">

                <?php //require_once('_flashes.php')?>
                <!-- PAGE CONTENT BEGINS -->
                <?php echo $content; ?>
                <!-- PAGE CONTENT ENDS -->
            </div>

            </div>

        </div><!-- /.main-content -->
    </div>
    <footer>
        <?php require_once('_footer.php');?>
    </footer>
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

	<script>
		$(function () {
		  $('[data-toggle="tooltip"]').tooltip();
		  $('[data-toggle="popover"]').popover()
		});
	</script>

    <?php
    $message = $class = '';
    if(Yii::app()->user->hasFlash('user')):?>
            <?php
                $message = Yii::app()->user->getFlash('user');
                $class = 'success';
            ?>
    <?php endif; ?>

    <?php if(Yii::app()->user->hasFlash('user_error')):?>
            <?php
                $message = Yii::app()->user->getFlash('user_error');
                $class = 'error';
            ?>
    <?php endif; ?>
    <?php
        if(!empty($message)&&!empty($class)){
            Yii::app()->clientScript->registerPackage('gritter');
            $js = <<<JS
                addGritter('','{$message}','{$class}')
JS;
            Yii::app()->clientScript->registerScript('flash',$js,CClientScript::POS_END);
        }
    ?>

    <?php if(Yii::app()->user->hasState('info_message')):?>
    <?php
        $message = Yii::app()->user->getState('info_message');
        Yii::app()->user->setState('info_message',null);
        $this->beginWidget(
            'bootstrap.widgets.TbModal',
            array(
                'id' => 'modalInfo',
            )
        ); ?>

        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>
            <h4></h4>
        </div>

        <div class="modal-body">
            <div id="modal-content">
                <?=$message?>
            </div>
        </div>

        <div class="modal-footer">
            <?php $this->widget(
                'bootstrap.widgets.TbButton',
                array(
                    'label' => tt('Закрыть'),
                    'url' => '#',
                    'htmlOptions' => array('data-dismiss' => 'modal'),
                )
            ); ?>
        </div>

        <?php $this->endWidget();?>

        <script type="text/javascript">
            $(window).load(function() {
                $('#modalInfo').modal('show');
            });
        </script>
    <?php endif; ?>

    <?php
    /*Запорожье авторизация на поддержке*/
    if(Yii::app()->user->hasState('api-func-login')) {
        $image = Yii::app()->user->getState('api-func-login');
        Yii::app()->user->setState('api-func-login', null);
        echo $image;
    }
    ?>

    <?php
    /*Запорожье выход с поддержки*/
    if(Yii::app()->user->hasState('api-func-logout')) {
        $image = Yii::app()->user->getState('api-func-logout');
        Yii::app()->user->setState('api-func-logout', null);
        echo $image;
        Yii::app()->user->logout(true);//костыль для показа сообщения что бы при лог ауте не дестроилась сессия
    }
    ?>

    <?php if(!empty(Yii::app()->params['analyticsYandex']))
        echo Yii::app()->params['analyticsYandex'];
    ?>
</body>
</html>
