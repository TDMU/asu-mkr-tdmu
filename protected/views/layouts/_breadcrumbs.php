<div class="noprint">
<?php
	$ps105 = (isset(PortalSettings::model()->findByPk(105)->ps2)?PortalSettings::model()->findByPk(105)->ps2:0);
    if($ps105!=1):
?>
<div id="breadcrumbs" class="breadcrumbs breadcrumbs-fixed">
    <script type="text/javascript">
        try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
    </script>

    <?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
        'links'=>$this->breadcrumbs,
        'separator' => '<i class="icon-angle-right arrow-icon"></i>',
        'htmlOptions' => array('class' => ''),
        'homeLink' => '<i class="icon-home home-icon"></i>'.CHtml::link(Yii::t('zii','Home'), Yii::app()->homeUrl),
    )); ?>

</div>
    <?php else:
        Yii::app()->clientScript->registerCss('breadcrumbs', <<<CSS
                .main-container .main-content .page-header {
                    margin-top: 15px!important;
                }
CSS
        );
    endif;?>
</div>
