<?php
function generateTh2($moduleInfo, $i, $isClosed)
{
    $min = $moduleInfo['min_mod_'.$i];
    $max = $moduleInfo['max_mod_'.$i];

    $field1 = 8+2*$i;
    $field2 = 9+2*$i;

    $pattern = <<<HTML
<th><input value="{$min}" maxlength="3" placeholder="min" data-name="vvmp{$field1}" %s></th>
<th><input value="{$max}" maxlength="3" placeholder="max" data-name="vvmp{$field2}" %s></th>
HTML;

    $disabled = $isClosed
                    ? 'disabled="disabled"'
                    : '';

    return sprintf($pattern, $disabled, $disabled);
}

function table2Tr($marks, $module, $isClosed, $areModulesExtended)
{
    $pattern= <<<HTML
    <td colspan="2">
        <input value="%s" maxlength="3" data-name="vmp4" data-module="%s" %s>
    </td>
HTML;

    $vmp4 = isset($marks[$module]) && $marks[$module]['vmp4'] != 0
                ? round($marks[$module]['vmp4'], 1)
                : '';

    $disabled = '';
    if ($isClosed)
        $disabled = 'disabled="disabled"';
    elseif ($areModulesExtended[$module])
        $disabled = 'disabled="disabled"';


    return sprintf($pattern, $vmp4, $module, $disabled);
}

function countVmpTotal($marks)
{
    $total = 0;
    foreach ($marks as $key => $mark) {

        if ($key == -1 || $key == 0) // this marks are from total 2
            continue;

        $total += $mark['vmp4'];
    }

    return $total;
}

function generateModuleHeader($i, $name, $isClosed)
{
    if (! $isClosed)
        $pattern = <<<HTML
<th colspan="2" data-module_num="%s">
    <i class="icon-hand-right icon-animated-hand-pointer blue"></i>
    <a class="green show-extended-module" role="button" href="#modal-table">%s</a>
</th>
HTML;
    else
        $pattern = <<<HTML
<th colspan="2" data-module_num="%s">
    %s
</th>
HTML;


    return sprintf($pattern, $i, $name);
}

$url       = Yii::app()->createUrl('/progress/insertVmpMark');
$minMaxUrl = Yii::app()->createUrl('/progress/updateVvmp');
$extendedModuleUrl = Yii::app()->createUrl('/progress/renderExtendedModule');
$table = <<<HTML
<div class="journal_div_table2 module_div_table2" >
    <table class="table table-striped table-bordered table-hover journal_table modules_table_2">
        <thead data-url="{$minMaxUrl}" data-extended_module_url="{$extendedModuleUrl}">
            <tr>
                %s
            </tr>
            <tr class="min-max">
                %s
            </tr>
        </thead>
        <tbody data-url="{$url}">
            %s
        </tbody>
    </table>
</div>
HTML;

$modules = $moduleInfo['kol_modul'];
$vvmp1   = $moduleInfo['vvmp1'];

/*** 2 table ***/
$th = $th2 = '';

for($i = 1; $i <= $modules; $i++) {
    $name = $moduleInfo['name_modul_'.$i];
    $th  .= generateModuleHeader($i, $name, $isClosed);
    $th2 .= generateTh2($moduleInfo, $i, $isClosed);
}

$areModulesExtended = array();
for($i = 1; $i <= $modules; $i++) {
    $areModulesExtended[$i] = Vmp::model()->isModuleExtended($vvmp1, $i);
}


global $total_1;
$tr = '';
foreach($students as $st) {

    $st1 = $st['st1'];

    $marks = Vmp::model()->getMarksForStudent($st1, $moduleInfo['vvmp1']);
    $total_1[$st1] = countVmpTotal($marks);

    $tr .= '<tr data-st1="'.$st1.'">';
    for($i = 1; $i <= $modules; $i++) {
        $tr .= table2Tr($marks, $i, $isClosed, $areModulesExtended);
    }
    $tr .= '</tr>';
}
echo sprintf($table, $th, $th2, $tr); // 2 table





