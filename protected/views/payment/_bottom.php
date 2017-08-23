<style>
    .table-striped tbody > tr:nth-child(2n+1) > td, .table-striped tbody > tr:nth-child(2n+1) > th {
        background-color: transparent;
    }
</style>

<?php
/**
 * @var PaymentController $this
 * @var array $payments
 * @var array $plan
 */

$wholeTotal = null;
foreach ($payments as $payment) {
    $wholeTotal += $payment['money'];
}


function tr($type, &$i, $payment, $plan = null, &$total)
{
    // payment
    if ($type == 1) {

        $money = round($payment['money'], 2);
        $total += $money;

        $td1 = $i;
        $td2 = tt('оплачено').': '.SH::formatDate('Y-m-d H:i:s', 'd.m.Y', $payment['dat']);
        $td3 = $money.' грн.';
        $td4 = totalMsg($total);

        $i++;

        return '<tr>
                    <td>'.$td1.'</td>
                    <td>'.$td2.'</td>
                    <td>'.$td3.'</td>
                    <td>'.$td4.'</td>
                </tr>';

        // plan
    } else {

        $money = round($plan['money'], 2);
        $total -= $money;

        $td2 = $plan['y'];
        if (Yii::app()->controller->action->id == 'hostel')
            $td2 .= ', '.SH::russianMonthName($plan['m1']);

        $td3 = $money. ' грн.';
        $td4 = totalMsg($total);

        return '<tr style="background:lightblue">
                    <td colspan="2" style="text-align:center">'.$td2.'</td>
                    <td>'.$td3.'</td>
                    <td>'.$td4.'</td>
                </tr>';

    }
}

function totalMsg($total)
{
    $amount = abs($total);

    if ($total < 0)
        $msg = '<span class="label label-important">'.tt('задолженность').': '.$amount.' грн.'.'</span>';
    else if ($total > 0)
        $msg = '<span class="label label-success">'.tt('переплата').': '.$amount.' грн.'.'</span>';
    else
        $msg =  $total;

    return $msg;
}

?>
<table id="payments" class="table table-striped table-bordered table-hover small-rows" style="width:60%">
    <thead>
    <tr>
        <th style="width:25%" colspan="2"><?=tt('План')?></th>
        <th style="width:10%"><?=tt('Сумма')?></th>
        <th><?=tt('Комментарий')?></th>
    </tr>
    </thead>
    <tbody>
    <?php

    if (! empty($plans)) :

        $firstDate = strtotime('01.'.$plans[0]['m1'].'.'.$plans[0]['y']);

        $total = 0;
        $i = 1;

        // before starting of pay plan
        foreach ($payments as $payment) {

            $date = strtotime($payment['dat']);

            if ($date < $firstDate) {

                echo tr(1, $i, $payment, null, $total);

                array_shift($payments);

            }

        }

        // during pay plan
        foreach ($plans as $plan) {

            echo tr(2, $i, null, $plan, $total);

            $lastDayOfMonth = strtotime(
                date('t.m.Y',
                    strtotime('01.'.$plan['m2'].'.'.$plan['y'])
                )
            );

            foreach ($payments as $payment) {

                $date = strtotime($payment['dat']);

                if ($lastDayOfMonth >= $date) {

                    echo tr(1, $i, $payment, null, $total);

                    array_shift($payments);

                }

            }
        }


        // after finishing of pay plan
        foreach ($payments as $payment) {
            echo tr(1, $i, $payment, null, $total);
        }

    endif
    ?>

    </tbody>
    <tfoot style="background-color: #abbac3;font-size: larger;font-weight: bold;">
    <tr>
        <td colspan="3" >
            <?=tt('Всего оплачено') . ': '?>
            <span class="label label-warning arrowed arrowed-right"><?=$wholeTotal?> грн.</span>
        </td>
        <td>
            <?=tt('Суммарная')?> <?=isset($total)?totalMsg($total):''?>
        </td>
    </tr>
    </tfoot>
</table>