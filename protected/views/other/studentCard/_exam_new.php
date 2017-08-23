<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 30.12.2015
 * Time: 10:04
 */

$id = SH::getUniversityCod();

$disciplines  = Stusv::model()->getDisciplineExam($st->st1,$gr1);

if(empty($disciplines))
    echo '<div class="">'.tt('Нет дисциплин').'</div>';
else{

    $pattern = <<<HTML
        <table class = "table table-bordered table-hover table-condensed">
            <thead>
                %s
            </thead>
            <tbody>
                %s
            </tbody>
</table>
HTML;

    $tr = $tr2 = "";
    $tr.='<tr>';
    $tr.='<th>'.tt('№ пп').'</th>';
    $tr.='<th>'.tt('Дисциплина').'</th>';
    $tr.='<th>'.tt('Тип').'</th>';

    if($id==38) {
        $tr .= '<th>' . tt('№ протокола') . '</th>';
        $tr .= '<th>' . tt('Дата протокола') . '</th>';
    }else {
        $tr .= '<th>' . tt('№ ведомости') . '</th>';
        $tr .= '<th>' . tt('Дата ведомости') . '</th>';
    }
    $tr.='<th>'.tt('5-ная').'</th>';
    $tr.='<th>'.tt('ECTS').'</th>';
    $tr.='<th>'.tt('Др. бальность').'</th>';
    $tr.='</tr>';

    $i=1;
    foreach($disciplines as $discipline)
    {
        $tr2.='<tr>';
        $tr2.='<td>'.$i.'</td>';
        $tr2.='<td>'.$discipline['d2'].'</td>';

        $type=SH::convertUsByStudentCard($discipline['us4'],$discipline['us6']);
        if(($id==32||$id==38)&&$discipline['us4']==5)
            $type = tt('ПМК');

        $tr2.='<td>'.$type.'</td>';
        $tr2.='<td>'.$discipline['stusv4'].'</td>';
        $date=!empty($discipline['stusv3'])?date('Y-m-d',strtotime($discipline['stusv3'])):"";
        $tr2.='<td>'.$date.'</td>';
        if($discipline['us4']!=6) {
            if ($discipline['stusvst6'] == -1)
                $val = tt('Зачтено');
            else
                $val = $discipline['stusvst6'];
            $tr2 .= '<td>' . $val . '</td>';
        }
        else
        {
            if($discipline['us6']==2) {
                if ($discipline['stusvst6'] == -1)
                    $val = tt('Зачтено');
                else
                    $val = $discipline['stusvst6'];
            }else {
                if ($discipline['stusvst6'] == -1)
                    $val = tt('зачет.');
                else
                    $val = tt('незач.');
            }
            $tr2.='<td>'.$val.'</td>';
        }
        $tr2.='<td>'.$discipline['stusvst5'].'</td>';
        $tr2.='<td>'.$discipline['stusvst4'].'</td>';
        $tr2.='</tr>';
        $i++;
    }

    echo sprintf($pattern,$tr,$tr2);
}