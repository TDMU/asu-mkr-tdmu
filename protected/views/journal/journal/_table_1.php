<?php
$pattern = <<<HTML
    <tr data-st1="%s" class="%s"><td class="center">%s</td><td data-toggle="tooltip" data-placement="right" title="" data-original-title="%s">%s</td></tr>
HTML;

    $columnName = tt('ФИО');
    $table = <<<HTML
<table class="table table-striped table-bordered table-hover journal_table journal_table_1 small-table-1" >
    <thead>
        <tr>
            <th class="center">№</th>
            <th>{$columnName}</th>
        </tr>
    </thead>
    <tbody>
        %s
    </tbody>
</table>
HTML;

    /*** 1 table ***/
    $tr = '';
    foreach($students as $key => $st) {
        $name = ShortCodes::getShortName($st['st2'], $st['st3'], $st['st4']);
        $num  = $key+1;

        if($st['st45']==1)
            $name.=' ('.tt('з.').')';

        $class = '';
        $button = '';
        if($st['st167']==1) {
            $name .= ' (' . tt('неоп.') . ')';
            $class = 'error-tr';
            $stbl = Stbl::model()->findByAttributes(array('stbl2'=>$st['st1'], 'stbl5'=>null),array('order'=>'stbl3 DESC'));
            if(!empty($stbl)) {
                $button = CHtml::link('<i class="icon-info-sign"></i>', '#', array('class' => 'btn-fin-block btn btn-mini btn-warning'));
            }
        }
        $tr .= sprintf($pattern, $st['st1'], $class, $num,$st['st2'].' '.$st['st3'].' '.$st['st4'] ,$name.$button);
    }
    echo sprintf($table, $tr); // 1 table