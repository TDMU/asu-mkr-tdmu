<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 10.02.2016
 * Time: 9:38
 */

if(!empty($model->group)):

    list($uo1,$gr1) = explode("/", $model->group);

    $sem1List =  Sem::model()->getSem1List($uo1);
    if(count($sem1List)!=1) {
        $options =  array('class'=>'cs-select cs-skin-elastic', 'autocomplete' => 'off', 'empty' => '&nbsp;');

        $html  = '<div class="select-group col-xs-12" style="width:100%;margin-left: 0px;margin-bottom: 5px;">';
        $html .= CHtml::label($model->getAttributeLabel('sem1'), 'FilterForm_sem1');
        $html .= CHtml::dropDownList('FilterForm[sem1]', $model->sem1, CHtml::listData($sem1List, 'sem1', 'name'), $options);
        $html .= '</div>';

        echo $html;
    }else
    {
        $elem =  current($sem1List);
        $model->sem1 = $elem['sem1'];
    }

    if (!empty($model->sem1)):

    $dates = R::model()->getDatesForJournal(
        $uo1,
        $gr1,
        $model->type_lesson,
        $model->sem1
    );

    $sem1 = Sem::model()->getSem1ByUo1($uo1);
    $sem = Sem::model()->findByPk($sem1);

    $gr = Gr::model()->findByPk($gr1);
    $grName = Gr::model()->getGroupName($sem->sem4,$gr);
    $this->pageHeader=tt($grName);
    $this->breadcrumbs=array(
        tt($grName),
    );

    $ps9  = PortalSettings::model()->getSettingFor(9);
    $ps20 = PortalSettings::model()->getSettingFor(20);// use sub modules
    $ps56 = PortalSettings::model()->getSettingFor(56);//
    $ps57 = PortalSettings::model()->getSettingFor(57);//
    $ps33=PortalSettings::model()->getSettingFor(33);

$elg1=Elg::getElg1($uo1,$model->type_lesson,$model->sem1);
$elg = Elg::model()->findByPk($elg1);
if(empty($elg))
    throw new CHttpException(404, tt('Не задана структура журнала. Обратитесь к Администратору системы').'.');

$students = St::model()->getStudentsForJournal($gr1, $uo1);

$modules = null;
$ps57 = PortalSettings::model()->getSettingFor(57);
if($ps57==1)
    $modules = Vvmp::model()->getModule($uo1,$gr1);

$elgz3Nom = 1;
$dateDiff = -1;
$date1 = new DateTime(date('Y-m-d H:i:s'));
foreach($dates as $date){
    $date2  = new DateTime($date['r2']);
    $diff = $date1->diff($date2)->days;
    if ($diff <= $dateDiff || $dateDiff==-1)
    {
        $dateDiff = $diff;
        $elgz3Nom = $date['elgz3'];
    }
}
?>
<div class="panel-actions row">
    <div class="form-actions col-xs-12">
        <button id="lesson-left" class="btn col-xs-2" type="button"><i class="arrow glyphicon glyphicon-triangle-left"></i></button>
        <div class="form-group col-xs-8">
            <?= CHtml::dropDownList('lesson-list',$elgz3Nom, CHtml::listData($dates,'elgz3','text'), array('class'=>'form-control','data-count'=>count($dates)));?>
        </div>
        <button id="lesson-right" class="btn col-xs-2 disabled" type="button" disabled="disabled"><i class="arrow glyphicon glyphicon-triangle-right"></i></button>
    </div>
</div>
<div class="col-xs-12 table-journal">
    <div class="journal-bottom table-responsive">
        <?php
        $this->renderPartial('journal/_table', array(
            'students' => $students,
            'dates'=>$dates,
            'elg'=>$elg,
            'uo1'=>$uo1,
            'gr1'=>$gr1,
            'ps20'  => $ps20,
            'ps33'  => $ps33,
            'ps55'  => $ps55,
            'modules'=>$modules,
            'read_only'=>$read_only,
            'ps56'  => $ps56,
            'ps57'  => $ps57,
            'ps9'  => $ps9,
            'model' => $model,
        ));
        ?>
    </div>
</div>
<?php
endif;
endif;

