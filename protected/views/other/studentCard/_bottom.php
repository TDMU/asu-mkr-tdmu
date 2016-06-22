<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 27.11.2015
 * Time: 9:51
 */

/* @var $st St */


if(Yii::app()->user->isStd)
{
    $ps73 = PortalSettings::model()->findByPk(73)->ps2;
    if($ps73==0)
        throw new CHttpException(403, tt('Доступ закрыт для студентов.'));
}

if(Yii::app()->user->isPrnt)
{
    $ps74 = PortalSettings::model()->findByPk(74)->ps2;
    if($ps74==0)
        throw new CHttpException(403, tt('Доступ закрыт для родителей.'));
}

$studentInfo = $st->getStudentInfoForCard();

if(empty($studentInfo))
    echo tt('Error');
else {

echo '<div id="studentCard">';
    echo '<div class="top-block"></div>';

    $id   = $st->st1;
    $type = Users::FOTO_ST1;
    $url = $this->createUrl('/site/userPhoto', array('_id' => $id, 'type' => $type));

    $class='img-circle';
    //img-polaroid
    $infoHtml = <<<HTML
    <div class="student-info row">
        <div class="student-photo span3">
             <img alt="photo" src="{$url}" class="{$class}">
        </div>
        <table class="table span9">
            <tbody>
                <tr>
                    <th>%s</th>
                    <td>%s</td>
                </tr>
                <tr>
                    <th>%s</th>
                    <td>%s</td>
                </tr>
                <tr>
                    <th>%s</th>
                    <td>%s</td>
                </tr>
                <tr>
                    <th>%s</th>
                    <td>%s</td>
                </tr>
                <tr>
                    <th>%s</th>
                    <td>%s</td>
                </tr>
                <tr>
                    <th>%s</th>
                    <td>%s</td>
                </tr>
                <tr>
                    <th>%s</th>
                    <td>%s</td>
                </tr>
                <tr>
                    <th>%s</th>
                    <td>%s</td>
                </tr>
            </tbody>
        </table>
    </div>
HTML;

    echo sprintf($infoHtml,
        tt('ФИО'),$st->st2.' '.$st->st3.' '.$st->st4,
        tt('Гражданство'),$studentInfo['sgr2'],
        tt('Дата рождения'),date("m.d.y",strtotime($st->st7)),
        tt('Факультет'), $studentInfo['f3'],
        tt('Специальность'), $studentInfo['sp2'],
        tt('Форма обучения'),SH::convertEducationType($studentInfo['sg4']),
        tt('Курс'), $studentInfo['sem4'],
        tt('Группа'), Gr::model()->getGroupName($studentInfo['sem4'], $studentInfo)

    );

    echo '</div>';//.top-block

    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'link',
        'type'=>'primary',
        'icon'=>'print',
        'url'=>Yii::app()->createUrl('/other/studentCardExcel'),
        'label'=>tt('Печать'),
        'htmlOptions'=>array(
            'class'=>'btn-mini',
            'id'=>'studentCard-print',
        )
    ));

    echo '<div class="bottom-block">';

    $disciplines = Elg::model()->getDispBySt($st->st1);

    $params = array('gr1'=>$studentInfo['gr1'],'st'=>$st);
    $ps50 = PortalSettings::model()->findByPk(50)->ps2;
    $tabs = array();
    if(PortalSettings::model()->findByPk(47)->ps2==1)
        array_push($tabs,array('label'=>tt('Успеваемость'), 'content'=>$this->renderPartial('studentCard/_journal', $params+array('disciplines'=>$disciplines),true), 'active'=>$ps50==0));
    if(PortalSettings::model()->findByPk(48)->ps2==1)
        array_push($tabs,array('label'=>tt('Текущая задолженость'), 'content'=>$this->renderPartial('studentCard/_retake', $params+array('disciplines'=>$disciplines),true), 'active'=>$ps50==1));
    if(PortalSettings::model()->findByPk(49)->ps2==1)
        array_push($tabs,array('label'=>tt('Модульный контроль'), 'content'=>$this->renderPartial((PortalSettings::model()->findByPk(76)->ps2==1)?'studentCard/_module':'studentCard/_module_pmk', $params,true), 'active'=>$ps50==2));
    if(PortalSettings::model()->findByPk(51)->ps2==1)
        array_push($tabs,array('label'=>tt('Екзаменационная сессия'), 'content'=>$this->renderPartial('studentCard/_exam', $params,true), 'active'=>$ps50==3));
    if(PortalSettings::model()->findByPk(52)->ps2==1)
        array_push($tabs,array('label'=>tt('Общая успеваемость'), 'content'=>$this->renderPartial('studentCard/_progress', $params,true), 'active'=>$ps50==4));

    /*array('label'=>Yii::t('main', 'Текущая задолженость'), 'content'=>$this->renderPartial('studentCard/_retake',$params,true), 'active'=>$ps50==1,'visible'=>PortalSettings::model()->findByPk(48)->ps2==1);
    array('label'=>Yii::t('main', 'Модульный контроль'), 'content'=>$this->renderPartial('studentCard/_module', $params,true), 'active'=>$ps50==2,'visible'=>PortalSettings::model()->findByPk(49)->ps2==1);
    array('label'=>Yii::t('main', 'Екзаменационная сессия'), 'content'=>$this->renderPartial('studentCard/_exam', $params,true), 'active'=>$ps50==3,'visible'=>PortalSettings::model()->findByPk(51)->ps2==1);
    array('label'=>Yii::t('main', 'Диплом'), 'content'=>$this->renderPartial('studentCard/_diplom', $params,true), 'active'=>$ps50==4,'visible'=>PortalSettings::model()->findByPk(52)->ps2==1);
    */

     $this->widget('bootstrap.widgets.TbTabs', array(
        'type'=>'tabs',
        'placement'=>'top',
        'tabs'=>$tabs,
    ));

    echo '</div>';//.bottom-block
echo'</div>';//#studentCard

}

$this->beginWidget(
    'bootstrap.widgets.TbModal',
    array(
        'id' => 'modalBlock',
        'htmlOptions'=>array(
            'class'=>'full-modal'
        )
    )
); ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4></h4>
    </div>

    <div class="modal-body">
        <div id="modal-content">

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

<?php $this->endWidget();