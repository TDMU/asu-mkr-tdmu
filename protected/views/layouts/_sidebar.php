<?php /*<div id="sidebar" class="sidebar sidebar-fixed noprint">*/?>
<div id="sidebar" class="sidebar h-sidebar navbar-collapse collapse noprint"  data-sidebar="true" data-sidebar-scroll="true" data-sidebar-hover="true">
    <script type="text/javascript">
        try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
    </script>
    <?php
        //$ps101= PortalSettings::model()->findByPk(101)->ps2;
        $ps101=0;
        if($ps101==0) {
    ?>
        <div id="sidebar-shortcuts" class="sidebar-shortcuts">
            <div id="sidebar-shortcuts-large" class="sidebar-shortcuts-large">
                <?php
                    $this->widget('ext.LangPick.ELangPick', array(
                        'excludeFromList' => array(),           // list of languages to exclude from list
                        'pickerType' => 'buttons',              // buttons, links, dropdown
                        'linksSeparator' => '<b> | </b>',       // if picker type is set to 'links'
                        'buttonsSize' => 'mini',                // mini, small, large
                        'buttonsColor' => 'info',             // primary, info, success, warning, danger, inverse
                        'htmlOptions' => array('id'=>'languages-list')
                    ));
                ?>
            </div>

            <div id="sidebar-shortcuts-mini" class="sidebar-shortcuts-mini">
                <span class="btn btn-info"></span>
            </div>
        </div>
    <?php } ?>

    <?php require_once('_menu.php')?>

    <div id="sidebar-collapse" class="sidebar-collapse">
        <i data-icon2="icon-double-angle-right" data-icon1="icon-double-angle-left" class="icon-double-angle-left"></i>
    </div>

    <?php if(PortalSettings::model()->findByPk(80)->ps2!=1):?>
        <label id="app-title"><?=tt('Расписание занятий для мобильных устройств')?></label>
        <a href="https://play.google.com/store/apps/details?id=scheduleMKP.scheduleMKP" target="_blank">
            <img src="<?php echo Yii::app()->request->baseUrl ?>/images/googleplay.png" style="max-width:90%;padding:5%"/>
        </a>
    <?php endif; ?>

    <?php
       if(!empty(Yii::app()->params['banner'])):
    ?>
    <div class="banner-block" id="banner">
        <?= Yii::app()->params['banner']?>
    </div>
    <?php endif; ?>

    <script type="text/javascript">
        try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
    </script>
</div>