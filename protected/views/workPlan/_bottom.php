<?php

function getHoursByUs6($us4, $us6){
    switch ($us4){
        case 5:
            return D::model()->getConvertByUs6('e', $us6);
            break;
        case 6:
            if($us6 == 1)
                return tt('Зач.');
            else if($us6 == 2)
                return tt('Диф.зач.');
            else
                return '';
            break;
        case 7:
            return D::model()->getConvertByUs6('w', $us6);
            break;
        case 8:
            return D::model()->getConvertByUs6('y', $us6);
            break;
        default:
            return '';
    }
}

/**
 * @var WorkPlanController $this
 * @var FilterForm $model
 */

$options = array('class'=>'chosen-select', 'autocomplete' => 'off', 'empty' => '&nbsp;', 'style' => 'width:200px');

$data = CHtml::listData(Sem::model()->getSemestersForWorkPlan($model->group, $type), 'us3', 'sem7', 'name');

$html  = '<div class="row-fluid" style="margin-bottom:2%">';
$html .= CHtml::label(tt('Семестр'), 'FilterForm_semester');
$html .= CHtml::dropDownList('FilterForm[semester]', $model->semester, $data, $options);
$html .= '</div>';

echo $html;

if (! empty($model->semester)) :

        $disciplines = D::model()->getDisciplinesForWorkPlan($model, $type);

        $getGroupUrl = Yii::app()->createUrl('workLoad/getGroups');

        $types = array(
            15 => 'ECTS',
            19 => tt('Нац. кр'),
            0 => tt('Всего'),
            1 => tt('Лк'),
            2 => tt('Пз'),
            3 => tt('Сем'),
            4 => tt('Лб'),
            16 => tt('Кнч'),
            5 => tt('Экз'),
            6 => tt('Зч'),
            8 => tt('КП'),
            7 => tt('Кр'),
        );
?>
<?php

    $text = tt('Для просмотра программы дисциплины нажмите значок рядом с ее названием');
    echo <<<HTML
    <h3 class="blue header lighter tooltip-info noprint">
        <i class="icon-info-sign show-info" style="cursor:pointer"></i>
        <small>
            <i class="icon-double-angle-right"></i> {$text}
        </small>
    </h3>
HTML;
    ?>
<table id="disciplines" class="table table-striped table-bordered table-hover small-rows"  data-getGroupUrl="<?=$getGroupUrl?>">
    <thead>
        <tr>
            <th rowspan="2">№</th>
            <th rowspan="2" style="width:25%"><?=tt('Дисциплина')?></th>
            <th rowspan="2">ECTS</th>
            <th rowspan="2"><?=tt('Нац. кр')?></th>
            <th colspan="6"><?=tt('Часов по плану')?></th>
            <th rowspan="2"><?=tt('Экз')?></th>
            <th rowspan="2"><?=tt('Зач')?></th>
            <th rowspan="2"><?=tt('КП')?></th>
            <th rowspan="2"><?=tt('Кр')?></th>
            <th rowspan="2"><?=tt('Кафедра')?></th>
            <th rowspan="2"></th>
        </tr>
        <tr>
            <th><?=tt('Всего')?></th>
            <th><?=tt('Лк')?></th>
            <th><?=tt('Пз')?></th>
            <th><?=tt('Сем')?></th>
            <th><?=tt('Лб')?></th>
            <th><?=tt('Кнч')?></th>
        </tr>
    </thead>
    <tbody>
    <?php
        $html = '';
        foreach ($disciplines as $key => $discipline) {
			
			$link = D::model()->getLinkDisciplinesForWorkPlan($model, $discipline['uo1']);
            $html .= <<<HTML
                <tr>
                    <td>{$key}</td>
                    <td>{$discipline['d2']}{$link}</td>
HTML;

            foreach ($types as $type => $name) {
                $hours = isset($discipline['hours'][$type]) ? round($discipline['hours'][$type],2) : null;
                if(is_null($hours)&&$type==0)
                {
                    $hours = Us::model()->getHoursByUo1Sem1( $discipline['uo1'],$model->semester,0);
                    if(!empty($hours))
                        $hours = round($hours);
                }
                if (in_array($type, array(5,6,7,8)) && !is_null($hours)) {
                    $hours = '<span class="label label-warning">'.getHoursByUs6($type, $discipline['hours'][$type]).'</span>';
                    //$hours = '<span class="label label-warning">+</span>';
                }

                $html .= '<td>'.$hours.'</td>';
            }
            $tip2  = tt('Распечатать в Excel');
            $url = Yii::app()->createUrl('workPlan/printGroups',array('type'=> $discipline['uo1'],'sem'=>$model->semester));
            $btnPrint  = <<<HTML
                        <a class="btn btn-minier btn-info tooltip-info btn-print"
                                href="{$url}"
                                data-rel="tooltip"
                                data-placement="bottom"
                                data-original-title="{$tip2}">
                            <i class="icon-print"></i>
                        </a>
HTML;
            $html .= <<<HTML
                    <td>{$discipline['k2']}</td>
                    <td>{$btnPrint}</td>
                </tr>
HTML;

        }
        echo $html;
    ?>
    </tbody>
</table>

<?php endif ?>
