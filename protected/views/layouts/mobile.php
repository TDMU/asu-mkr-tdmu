<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 02.02.2016
 * Time: 17:58
 */

Yii::app()->clientScript->registerCoreScript('jquery');
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="<?=Yii::app()->language?>" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

        <?php if(!empty(Yii::app()->params['analytics']))
            echo '<script>'.Yii::app()->params['analytics'].'</script>';
        ?>
        <style>
            #page-preloader {
                position: fixed;
                left: 0;
                top: 0;
                right: 0;
                bottom: 0;
                background: #fff;
                z-index: 100500;
            }

            #page-preloader .spinner div {
                width: 20px;
                height: 20px;
                position: absolute;
                left: -20px;
                top: 40%;
                background-color: rgb(63,93,143);
                border-radius: 50%;
                animation: move 4s infinite cubic-bezier(.2,.64,.81,.23);
            }
            #page-preloader .spinner div:nth-child(2) {
                animation-delay: 150ms;
                background-color: #0296db;
            }
            #page-preloader .spinner div:nth-child(3) {
                animation-delay: 300ms;
                background-color: #6a9cc4;
            }
            #page-preloader .spinner div:nth-child(4) {
                animation-delay: 450ms;
                background-color: #ddd;
            }
            @keyframes move {
                0% {left: 0%;}
                75% {left:100%;}
                100% {left:100%;}
            }
            </style>
    </head>
    <body>
        <?php if (! empty($this->pageHeader)) :?>
            <header class="header">
                <h1><?php echo $this->pageHeader;?></h1>
            </header>
        <?php endif;?>
        <div id="page-preloader">
            <div class="spinner">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
        <div class="spinner-block">
            <div id="spinner1" class="spinner" style="display: none">
                <div class="bounce1"></div>
                <div class="bounce2"></div>
                <div class="bounce3"></div>
            </div>
        </div>
        <main class="container">
            <?php echo $content; ?>
        </main>

        <footer class="footer">
            <?php require_once('_footer.php');?>
        </footer>
        <link rel="stylesheet" type="text/css" href="<?=Yii::app()->baseUrl?>/css/mobile/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?=Yii::app()->baseUrl?>/css/mobile/full_modal.css">
        <link rel="stylesheet/less" type="text/css" href="<?=Yii::app()->baseUrl?>/css/mobile/style.less">
        <script src="/js/mobile/less.min.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="<?=Yii::app()->baseUrl?>/css/mobile/select/cs-select.css" />
        <link rel="stylesheet" type="text/css" href="<?=Yii::app()->baseUrl?>/css/mobile/select/cs-skin-elastic.css" />

        <script type="text/javascript" src="<?=Yii::app()->baseUrl?>/js/mobile/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?=Yii::app()->baseUrl?>/js/mobile/moment.min.js"></script>
        <script src="<?=Yii::app()->baseUrl?>/js/mobile/main.js"></script>
        <script type="text/javascript" src="<?=Yii::app()->baseUrl?>/js/mobile/select/classie.js"></script>
        <script type="text/javascript" src="<?=Yii::app()->baseUrl?>/js/mobile/select/selectFx.js"></script></body>
</html>
