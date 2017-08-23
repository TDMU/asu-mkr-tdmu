<style>
    .autocomplete-suggestions { border:1px solid #999; background:#FFF; cursor:pointer; text-align:left; max-height:350px; overflow:auto; margin:-6px 6px 6px -6px; /* IE6 specific: */ _height:350px;  _margin:0; _overflow-x:hidden; }
    .autocomplete-selected { background:#F0F0F0; }
    .autocomplete-suggestions div { padding:2px 5px; white-space:nowrap; overflow:hidden; }
    .autocomplete-suggestions strong { font-weight:normal; color:#3399FF; }
</style>
<?php
//.autocomplete-suggestions-w1 { background:url(img/shadow.png) no-repeat bottom right; position:absolute; top:0px; left:0px; margin:6px 0 0 6px; /* IE6 fix: */ _background:none; _margin:1px 0 0 0; }
/**
 * @var CActiveForm $form
 * @var TimeTableForm $model
 * @var stInfoForm $stInfoForm
 */
$params = array('class'=>'chosen-select', 'autocomplete' => 'off', 'empty' => '&nbsp;');

?>

<?php
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'fill-data-form',
    'htmlOptions' => array(
        'class' => 'form-horizontal',
    )
));
?>
    <?= $form->errorSummary($stInfoForm) ?>

    <div class="control-group">
        <?= $form->label($stInfoForm, 'st74', array('class' => 'control-label'))?>
        <div class="controls">
            <?= $form->textField($stInfoForm, 'st74', array('autocomplete'=>'off'))?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->label($stInfoForm, 'st75', array('class' => 'control-label'))?>
        <div class="controls">
            <?= $form->textField($stInfoForm, 'st75', array('autocomplete'=>'off'))?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->label($stInfoForm, 'st76', array('class' => 'control-label'))?>
        <div class="controls">
            <?= $form->textField($stInfoForm, 'st76', array('autocomplete'=>'off'))?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->label($stInfoForm, 'st107', array('class' => 'control-label'))?>
        <div class="controls">
            <?= $form->textField($stInfoForm, 'st107', array('autocomplete'=>'off'))?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->label($stInfoForm, 'st131', array('class' => 'control-label'))?>
        <div class="controls">
            <?= $form->textField($stInfoForm, 'st131', array('autocomplete'=>'off'))?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->label($stInfoForm, 'st132', array('class' => 'control-label'))?>
        <div class="controls">
            <?= $form->textField($stInfoForm, 'st132', array('autocomplete'=>'off'))?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->label($stInfoForm, 'st34', array('class' => 'control-label'))?>
        <div class="controls">
            <?php
                $specializations = Sp::model()->getSpecializations($stInfoForm->speciality);
                $options = CHtml::listData($specializations, 'spc1', 'spc4');
                echo $form->dropDownList($stInfoForm, 'st34', $options, $params);
            ?>
        </div>
    </div>

    <?php $url1 = $this->createUrl('/other/autocompleteTeachers') ?>
    <?php $url2 = $this->createUrl('/other/updateNkrs') ?>

    <?php
    /*$blockTheme = false;
    if(PortalSettings::model()->getSettingFor(72)==1)
        $blockTheme = true;

    $sg1 = St::model()->getSg1BySt1($model->student);
    if(Cwb::model()->findByPk($sg1)!==null)
        $blockTheme = true;*/

    $blockTheme = false;
    if(!$blockTheme):
        $cod = SH::getUniversityCod();
        if($cod==7){
            ?>
            <div class="alert alert-warning">
                <strong>Внимание!</strong></br>
                При занесении темы курсовой/дипломной работы придерживайтесь следующих правил:</br>
                1. Тема должна состоять из одного предложения (использование точек не допускается). Точка в конце предложения НЕ ставится;</br>
                2. Избегайте кавычек и лишних пробелов;</br>
                3. Использование аббревиатур не допускается;</br>
                4. При возникновении проблем с оформлением заявления пишите на почту edu@law.msu.ru c пометкой Выпуск-[год].</br>
            </div>
        <?php } ?>

        <div class="control-group" data-autocompleteUrl="<?= $url1?>" data-updateNkrs="<?= $url2?>">
            <?= CHtml::label(tt('Тема курсовой'), '', array('class' => 'control-label')) ?>
            <div class="controls">
                <?php
                    $discipline = D::model()->getDisciplineForCourseWork($model->student);
                    list($rus, $eng) = Spkr::model()->findAllInArray();
                    if ($discipline) {

                        echo '<strong>'.$discipline['d2'].'</strong>'.'<br>';

                        $courseWork = D::model()->getFirstCourseWork($model->student, $discipline['us1']);
                        if(!empty($courseWork))
                        {
                            $nkrs1 = $courseWork['nkrs1'];
                            $p1    = $courseWork['nkrs6'];
                            $nkrs6 = P::model()->getTeacherNameWithDol($courseWork['nkrs6']);
                            $nkrs7 = $courseWork['nkrs7'];
                        }
                        else
                        {
                            $nkrs1 = -1;
                            $nkrs6='';
                            $nkrs7=-1;

                        }
                            echo CHtml::tag('div', array('data-nkrs1' => $nkrs1,'data-st1' =>$model->student,'data-us1' =>$discipline['us1'])).
                                    CHtml::dropDownList('nkrs7', $nkrs7,  array('0' => '--Select--')+$rus, array('id' => false, 'class' => 'chosen-select', 'style'=>'width:50%')).
                                    CHtml::label(tt('Научный руководитель'), '', array()).
                                    CHtml::textField('nkrs6', $nkrs6, array('class' => 'autocomplete')).'<br>'.
                                 CHtml::closeTag('div');


                    }
                ?>
            </div>
        </div>

        <div class="control-group">
            <?= CHtml::label(tt('Тема курсовой (англ.)'), '', array('class' => 'control-label')) ?>
            <div class="controls">
                <?php
                    if ($discipline) {

                        echo '<strong>'.$discipline['d2'].'</strong>'.'<br>';
                            echo CHtml::tag('div').
                                    CHtml::dropDownList('nkrs7-eng', $nkrs7,  array('0' => '--Select--')+$eng, array('style'=>'width:72%', 'disabled' => true)).
                                 CHtml::closeTag('div');
                    }
                ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning"><?=tt('Редактирование тем закрыто.')?></div>
    <?php endif; ?>
    <div class="form-actions">
        <button type="submit" class="btn btn-info">
            <i class="icon-ok bigger-110"></i>
            <?=tt('Сохранить')?>
        </button>
        <?php
        if (!$canSelectSt):
        ?>
        <?php /*<a class="btn btn-primary" href="<?=Yii::app()->createUrl('/other/studentInfoExcel')?>">
            <i class="icon-print bigger-110"></i>
            <?=tt('Печать')?>
        </a>*/ ?>
        <a class="btn btn-success" href="<?=Yii::app()->createUrl('/other/studentInfoPdf')?>">
            <i class="icon-print bigger-110"></i>
            <?=tt('Печать Pdf')?>
        </a>

        <?php
            endif;
            if(PortalSettings::model()->findByPk(72)->ps2!=1):
        ?>
        <a type="submit" class="btn btn-warning btn-add-spkr" data-url="<?=$this->createUrl('/other/renderAddSpkr')?>">
            <i class="icon-plus bigger-110"></i>
            <?=tt('Добавить в справочник тему курсовой')?>
        </a>
    </div>
<?php
            endif;?>
    <div class="control-group">
        <?= $form->label($stInfoForm, 'internationalPassport', array('class' => 'control-label'))?>
        <div class="controls">
            <?php //$form->textField($stInfoForm, 'internationalPassport', array('autocomplete'=>'off'))
                echo CHtml::image(Yii::app()->createUrl('/other/studentPassport',array('psp1'=>$model->student,'type'=>2)),'',array("width"=>"200px"));//$stInfoForm->getPassport($model->student,2);
            ?>
            <div>
            <button data-url="<?=$this->createUrl('/other/changePassport')?>" type="button" class="change-passport btn btn-success btn-mini" data-psp1="<?=$model->student?>" data-type="2">
            <i class="icon-arrow-up bigger-110"></i>
            <?=tt('Загрузить')?>
            </button>
            <button data-url="<?=$this->createUrl('/other/showPassport')?>" type="button" class="show-passport btn btn-warning btn-mini" data-psp1="<?=$model->student?>" data-type="2">
            <i class="icon-fullscreen bigger-110"></i>
            <?=tt('Открыть в полном размере')?>
            </button>
            <button data-url="<?=$this->createUrl('/other/deletePassport')?>" type="button" class="delete-passport btn btn-danger btn-mini" data-psp1="<?=$model->student?>" data-type="2">
            <i class="icon-trash bigger-110"></i>
            <?=tt('Удалить')?>
            </button>
            </div>
        </div>
    </div>
    
    <div class="control-group">
        <?= $form->label($stInfoForm, 'passport', array('class' => 'control-label'))?>
        <div class="controls">
            <?php 
            echo CHtml::image(Yii::app()->createUrl('/other/studentPassport',array('psp1'=>$model->student,'type'=>1)),'',array("width"=>"200px"));
            ?>
            <div>
            <button data-url="<?=$this->createUrl('/other/changePassport')?>" type="button" class="change-passport btn btn-success btn-mini" data-psp1="<?=$model->student?>" data-type="1">
            <i class="icon-arrow-up bigger-110"></i>
            <?=tt('Загрузить')?>
            </button>
            <button data-url="<?=$this->createUrl('/other/showPassport')?>" type="button" class="show-passport btn btn-warning btn-mini" data-psp1="<?=$model->student?>" data-type="1">
            <i class="icon-fullscreen bigger-110"></i>
            <?=tt('Открыть в полном размере')?>
            </button>
            <button data-url="<?=$this->createUrl('/other/deletePassport')?>" type="button" class="delete-passport btn btn-danger btn-mini" data-psp1="<?=$model->student?>" data-type="1">
            <i class="icon-trash bigger-110"></i>
            <?=tt('Удалить')?>
            </button>
            </div>
        </div>
    </div>

    <div class="control-group">
        <?= $form->label($stInfoForm, 'inn', array('class' => 'control-label'))?>
        <div class="controls">
            <?php
            echo CHtml::image(Yii::app()->createUrl('/other/studentPassport',array('psp1'=>$model->student,'type'=>3)),'',array("width"=>"200px"));
            ?>
            <div>
                <button data-url="<?=$this->createUrl('/other/changePassport')?>" type="button" class="change-passport btn btn-success btn-mini" data-psp1="<?=$model->student?>" data-type="3">
                    <i class="icon-arrow-up bigger-110"></i>
                    <?=tt('Загрузить')?>
                </button>
                <button data-url="<?=$this->createUrl('/other/showPassport')?>" type="button" class="show-passport btn btn-warning btn-mini" data-psp1="<?=$model->student?>" data-type="3">
                    <i class="icon-fullscreen bigger-110"></i>
                    <?=tt('Открыть в полном размере')?>
                </button>
                <button data-url="<?=$this->createUrl('/other/deletePassport')?>" type="button" class="delete-passport btn btn-danger btn-mini" data-psp1="<?=$model->student?>" data-type="3">
                    <i class="icon-trash bigger-110"></i>
                    <?=tt('Удалить')?>
                </button>
            </div>
        </div>
    </div>

    <div class="control-group">
        <?= $form->label($stInfoForm, 'snils', array('class' => 'control-label'))?>
        <div class="controls">
            <?php
            echo CHtml::image(Yii::app()->createUrl('/other/studentPassport',array('psp1'=>$model->student,'type'=>4)),'',array("width"=>"200px"));
            ?>
            <div>
                <button data-url="<?=$this->createUrl('/other/changePassport')?>" type="button" class="change-passport btn btn-success btn-mini" data-psp1="<?=$model->student?>" data-type="4">
                    <i class="icon-arrow-up bigger-110"></i>
                    <?=tt('Загрузить')?>
                </button>
                <button data-url="<?=$this->createUrl('/other/showPassport')?>" type="button" class="show-passport btn btn-warning btn-mini" data-psp1="<?=$model->student?>" data-type="4">
                    <i class="icon-fullscreen bigger-110"></i>
                    <?=tt('Открыть в полном размере')?>
                </button>
                <button data-url="<?=$this->createUrl('/other/deletePassport')?>" type="button" class="delete-passport btn btn-danger btn-mini" data-psp1="<?=$model->student?>" data-type="4">
                    <i class="icon-trash bigger-110"></i>
                    <?=tt('Удалить')?>
                </button>
            </div>
        </div>
    </div>
<?php $this->endWidget(); ?>

<?php
$this->beginWidget(
    'bootstrap.widgets.TbModal',
    array(
        'id' => 'myModal',
    )
); ?>
 
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4><?=tt('Просмотр')?></h4>
    </div>
 
    <div class="modal-body">
        <div id="modal-content">
            <div class="block"></div>
        </div>
    </div>
 
    <div class="modal-footer">
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'label' => tt('Отмена'),
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal'),
            )
        ); ?>
    </div>
 
<?php $this->endWidget(); ?>

<div class="hr hr-18 dotted hr-double"></div>

<?php if (isset($nkrs1)) : ?>
<div class="span4">
        <div class="widget-box">
            <div class="widget-header">
                <h4><?=tt('Антиплагиат')?></h4>
            </div>

            <div class="widget-body">
                <div class="widget-main no-padding">
                    <?php
                        $form=$this->beginWidget('CActiveForm', array(
                            'id'=>'attach-file-form',
                            'htmlOptions' => array(
                                'enctype' => 'multipart/form-data',
                            )
                        ));
                    ?>

                    <fieldset>
                        <?= CHtml::fileField('document', ''/*, array('id' => 'id-input-file-3')*/) ?>
                        <?= CHtml::hiddenField('nkrs1', $nkrs1) ?>
                        <?= CHtml::hiddenField('nkrs6', isset($p1)?$p1:null) ?>
                    </fieldset>

                    <div class="form-actions center">
                        <button class="btn btn-small btn-success">
                            <?=tt('Отправить')?>
                            <i class="icon-arrow-right icon-on-right bigger-110"></i>
                        </button>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
</div>
<?php endif ?>