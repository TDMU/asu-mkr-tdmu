<?php
/**
 * @var $model St
 * @var $this OtherController
 */
$this->pageHeader=tt('Запись на выборочные дисциплины');
$this->breadcrumbs=array(
    tt('Запись на выборочные дисциплины'),
);

Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/other/subscription.js', CClientScript::POS_HEAD);

$url1 = Yii::app()->createUrl('other/saveCiklVBloke');
$url2 = Yii::app()->createUrl('other/saveDisciplines');
$url3 = Yii::app()->createUrl('other/cancelSubscription');
$msg1 = tt('Произошла ошибка!');
$msg2 = tt('Необходимо выбрать один из вариантов!');
$msg3 = tt('Выберите нужное количество дисциплин!');

Yii::app()->clientScript->registerScript('orderLesson-messages', <<<JS
    var url1 = '{$url1}';
    var url2 = '{$url2}';
    var url3 = '{$url3}';
    var msg1 = '{$msg1}';
    var msg2 = '{$msg2}';
    var msg3 = '{$msg3}';

JS
    , CClientScript::POS_HEAD);

$params = $model->subscriptionParams;
if(empty($params)) {
    $message = tt('Ошибка, возможно запись на дисциплины закрыта! Обратитесь к администратору!');

    $errorMessage = PortalSettings::model()->getSettingFor(PortalSettings::ERROR_SUBCRIPTION_MESSAGE);
    if(!empty($errorMessage))
        $message = $errorMessage;

    throw new CHttpException(400, $errorMessage);
}
$params['st1'] = $model->st1;
$params['semester'] =0;

$_SESSION['u1_vib']       = isset($_SESSION['u1_vib']) ? $_SESSION['u1_vib'] : '';
$_SESSION['u1_vib_disc']  = isset($_SESSION['u1_vib_disc']) ? $_SESSION['u1_vib_disc'] : '';
$_SESSION['uch_god']      = $params['uch_god'];
$_SESSION['semester']     = isset($_SESSION['semester']) ? $_SESSION['semester'] :$params['semester'];
//print_r($_SESSION['semester'].'-------------------');
$_SESSION['st1']          = $params['st1'];
$_SESSION['gr1_kod']      = $params['gr1_kod'];
$_SESSION['data_nachala'] = $params['data_nachala'];

//print_r($_SESSION['semester']);

if (isset($_SESSION['func'])) {
    //print_r('2---------------------');
    $_SESSION['func']($params);
} else {
    //$params['semester'] =0;
    //$_SESSION['semester']     = $params['semester'];
    PROCEDURA_CIKL_PO_BLOKAM($params);
}


function PROCEDURA_CIKL_PO_BLOKAM($params)
{
    //print_r('Начало:<br>');
    //$params['semester'] =0;
    $_SESSION['semester'] = $params['semester'];

    //print_r('Тек семест:'. $params['semester'].'<br>');

    unset($_SESSION['func']);
    extract($params);// $sg1_kod, $gr1_kod, $uch_god, $semestr, $data_nachala

    if (! $sg1_kod) {
        Yii::app()->user->setFlash('error', tt('Студент не активен'));
        return;
    }

    list($min_block_, $max_block) = U::model()->getMinMAxBlocks($sg1_kod);
    //print_r('Тек min_block 1:'. $min_block_.'<br>');
    $min_block = isset($_SESSION['min_block']) ? $_SESSION['min_block'] : (int)$min_block_;
    //print_r('Тек min_block 2:'. $min_block.'<br>');
    $enable= false;
    for ($block = $min_block; $block <= $max_block+1; $block ++) {

        //print_r($block.'///---<br>');
        //print_r($semester.'///<br>');
        // Определяю является ли родитель блоком по выбору
        $u9_root = U::model()->getU9($sg1_kod, $block);

        // это корень блока
        if ($u9_root == 0) {

            $u1_root = U::model()->getU17($sg1_kod, $block);

            if (empty($_SESSION['u1_vib']))
                $_SESSION['u1_vib'] = $u1_root;
            else
                if($u1_root!=null && !empty($u1_root))
                    $_SESSION['u1_vib'] .= ','.$u1_root;
        }

        // Проверяю анализировать ли блок
        // (нужно, когда повторно буду заходить внутрь и там нет дисциплин, то понять,
        // нужно ли в него дальше в глубь идти т.е. выбрал студент его или нет ранее)
        $u1 = U::model()->getU1($sg1_kod, $block, $_SESSION['u1_vib']);
        //print_r('Тек u1:'. $u1.'<br>');
        if ($u1 > 0) {

            // Проверяю попадает ли блок в анализируемый учебный год и семестр
            // (анализирую максимум 2 вложения циклов, если больше будет, то нужно дописать условие по аналогии с ниже на u17)
            $analyze = U::model()->getAnalyze($uch_god, $semester, $block, $sg1_kod);
            //print_r('Тек analyze:'. $analyze.'<br>');
            if ($analyze > 0) {

                // Проверяю, выбрал ли цикл в этом блоке студент
                $u1_d = 0;
                $u1_kods = U::model()->getU1_KOD($block, $sg1_kod);
                //print_r('Тек u1_kods:<br>');
                //print_r($u1_kods);
                //print_r('<br>');
                foreach ($u1_kods as $u1_kod) {

                    $u1_vibral = U::model()->getU1_VIBRAL($st1, $u1_kod, $block, $sg1_kod);
                    //print_r('Тек '.$u1_kod.' - $u1_vibral:'.$u1_vibral.'<br>');
                    if ($u1_vibral) {
                        $u1_d = $u1_kod;
                        break;
                    }
                }

                if ($u1_d == 0) {
                    $kol = U::model()->getKOL($block, $sg1_kod);

                    // если в блоке только 1 строка цикла, в случае просто выбора дисциплин
                    if ($kol == 1){
                        $_ul = U::model()->get_U1($block, $sg1_kod);
                        $_SESSION['u1_vib'] .= ','.$_ul;
                        $_SESSION['u1_vib_disc'] = $_ul;

                        $_SESSION['min_block'] = $block;
                        PROCEDURA_VIBOR_DISCIPLIN($params);
                        break;

                    } else {

                        $_SESSION['min_block'] = $block;
                        PROCEDURA_VIBOR_CIKLA_V_BLOKE($params);
                        break;
                    }

                } else {
                    $_SESSION['u1_vib'] .= ','.$u1_d;
                    $_SESSION['u1_vib_disc'] = $u1_d;

                    $_SESSION['min_block'] = $block;
                    PROCEDURA_VIBOR_DISCIPLIN($params);
                    break;
                }


            } else
                continue;
        }
    }
    /*var_dump($min_block);
    var_dump($block);
    var_dump($max_block);
    var_dump($_SESSION['u1_vib']);*/
    if($block>$max_block){
        //print_r('Переход:<br>');
        $params['semester']++;
        //print_r('Тек семест:'. $params['semester'].'<br>');
        $_SESSION['min_block'] = $min_block_;
        //print_r('Тек min_block:'. $_SESSION['min_block'].'<br>');
        if ($params['semester'] <= 1)
            PROCEDURA_CIKL_PO_BLOKAM($params);
    }
}

function PROCEDURA_VIBOR_CIKLA_V_BLOKE($params)
{
    $widget = <<<HTML
<div class="widget-box" style="margin:0 0 10px 0">
    <div class="widget-header">
        <h4>%s</h4>
    </div>
    <div class="widget-body">
        <div class="widget-main no-padding">
            %s
        </div>
    </div>
</div>
HTML;

    unset($_SESSION['func']);
    extract($params);// $sg1_kod, $gr1_kod, $uch_god, $semestr, $data_nachala

    $block = $_SESSION['min_block'];

    $blocks = U::model()->getCiklList($block, $sg1_kod);

    $options = array(
        'labelOptions' => array('class' => 'lbl'),
        'class' => 'ace',
        'template' => '<div class="cikl">{input}{label}</div>',
        'separator' => ''
    );
    $controls =  CHtml::radioButtonList('cikl_v_bloke', false, $blocks, $options);

    echo sprintf($widget, tt('Выберите один из блоков'), $controls);
    echo CHtml::button(tt('Сохранить'), array('name' => 'cikl_v_bloke', 'class' => 'btn btn-small btn-success', 'style'=>'margin:0 1% 0 0'));
}

function PROCEDURA_VIBOR_DISCIPLIN($params)
{
    $widget = <<<HTML
<div class="widget-box" style="margin:0 0 10px 0">
    <div class="widget-header">
        <h4>%s</h4>
    </div>
    <div class="widget-body">
        <div class="widget-main no-padding">
            %s
        </div>
    </div>
</div>
HTML;

    unset($_SESSION['func']);
    extract($params);// $sg1_kod, $gr1_kod, $uch_god, $semestr, $data_nachala
    // Проверяю, если ли дисциплины в выбранном цикле для выбора

    //print_r('u1_vib_disc: '.$_SESSION['u1_vib_disc'].'<br>');
    //print_r('$uch_god: '.$uch_god.'<br>');
    //print_r('$semester: '.$semester.'<br>');
    $semester=isset($_SESSION['semester']) ? $_SESSION['semester']:$semester;
    //print_r('$semester: '.$semester.'<br>');
    $nado_vibrat = U::model()->getNADO_VIBRAT($_SESSION['u1_vib_disc'], $uch_god, $semester);

    //print_r('nado-vibrat:'.$nado_vibrat.'<br>');

    if ($nado_vibrat > 0) {

        // Проверяю, выбрал ли студент необходимое количество дисциплин
        $kol = U::model()->getKOL2($_SESSION['u1_vib_disc'], $uch_god, $semester, $st1);

        // не выбрал нужное количество дисциплин
        if ($nado_vibrat != $kol) {

            $disciplines = U::model()->getDisciplines($_SESSION['u1_vib_disc'], $uch_god, $semester, $gr1_kod);

            $c2 = U::model()->getC2($_SESSION['u1_vib_disc'], $uch_god, $semester, $gr1_kod);

            // ставиш точку напротив дисциплины, которую студент ранее выбрал (если изменили количество дисциплин для выбора)
            $alreadyCheckedDisc = U::model()->getAlreadyChecked($_SESSION['u1_vib_disc'], $uch_god, $semester, $st1);

            $controls = '';
            $controls .= '<h5 class="name-c2">'.$c2.' '.tt('для изучения в {sem} семестре {year}/{year1} уч.г.',array(
                    '{year}' => $uch_god,
                    '{year1}' => $uch_god+1,
                    '{sem}' => SH::convertSem5($semester)
                )).'</h5>';
            foreach ($disciplines as $discipline) {
                $isChecked = in_array($discipline['d1'], $alreadyCheckedDisc);
                $value     = $discipline['ucgn1_kod'];

                $maxCount = $discipline['ucx6'];
                $maxCountStr = '';
                $maxCountStr2 = '';
                $disabled = array();

                if($maxCount>0) {
                    $maxCountStr = '/' . tt('Максимальное количество');
                    $maxCountStr2 = '/' . $maxCount;
                    if($maxCount<=$discipline['count_st']){
                        $disabled = array('disabled'=>'disbaled');
                    }
                }

                $tooltip='<a href="#" data-toggle="tooltip" data-placement="right" title="" data-original-title="'.tt('Количество студентов записавшихся на дисциплину').$maxCountStr.'">'.$discipline['count_st'].$maxCountStr2.'</a>';
                $controls .= '<div class="subscription-disc">';
                if($maxCount>$discipline['count_st']||$maxCount==0) {
                    $controls .= CHtml::checkBox('disciplines[]', $isChecked, array('value' => $value) + $disabled);
                }
                $controls .= '<span>'.$discipline['d2'].' '.D::model()->getAd($discipline['d1']).' ('.$tooltip.')</span>'.
                             '</div>';
            }

            echo sprintf($widget, tt('Количество дисциплин, которые необходимо выбрать').': '.$nado_vibrat, $controls).
                 CHtml::button(tt('Сохранить'), array('name' => 'vibor_discipline', 'data-min' => $nado_vibrat, 'class' => 'btn btn-small btn-success', 'style'=>'margin:0 1% 0 0'));

        } else {
            $_SESSION['min_block']++;
            PROCEDURA_CIKL_PO_BLOKAM($params);
        }

    } else {
        $_SESSION['min_block']++;
        PROCEDURA_CIKL_PO_BLOKAM($params);
    }
}


$html = <<<HTML
	<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3></h3>
	  </div>
	  <div class="modal-body">
		<p></p>
	  </div>
	</div>
HTML;

echo $html;


if(St::model()->enableSubcription($model->st1))
    echo CHtml::button(tt('Отмена'), array('id' => 'cancelSubscription', 'class' => 'btn btn-small btn-danger'));

$st1          = $_SESSION['st1'];
$data_nachala = $_SESSION['data_nachala'];

$disciplines = U::model()->getSubscribedDisciplines($st1, $data_nachala);
if (! empty($disciplines)) : ?>
    <table class="table table-striped table-bordered subscription">
        <caption><?=tt('Дисциплины, на которые Вы уже записались')?></caption>
        <tbody>
         <?php
            foreach ($disciplines as $discipline) {
                echo '<tr><td>'.$discipline.'</td></tr>';
            }
         ?>
        </tbody>
    </table>
<?php endif;