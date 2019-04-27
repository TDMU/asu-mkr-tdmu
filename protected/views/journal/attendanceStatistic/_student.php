<?php
	$this->pageHeader=tt('Статистика посещаемости студента').' '.ShortCodes::getShortName($st[0]['st2'], $st[0]['st3'], $st[0]['st4']);
	$this->breadcrumbs=array(
		tt('Электронный журнал'),
	);
	if($statistic!=null)
	{
			$table='<table id="student-statistic" class="table table-striped table-hover">
				<thread>
					<tr>
						<th>№</th>
                        <th>'.tt('Тип').'</th>
						<th>'.tt('Дата').'</th>
						<th>'.tt('Кол-во часов').'</th>
						<th>'.tt('Дисциплина').'</th>
						
					</tr>
				</thread>
				<tbody>';
		$i=1;

        $now = strtotime('now');
		foreach($statistic as $key)
		{
            $r2 = $key['r2'];
            $timeR2 = strtotime($r2);
		    if($timeR2>$now)
		        continue;

			if($key['propusk']==2){
				$table.= '<tr class="success">';
			}
			else
			{
				$table.= '<tr>';
			}
            $table.= '<td>'.$i.'</td>
                <td>'.SH::convertUS4($key['us4']).'</td>
                <td>'.date('d.m.Y',strtotime($key['r2'])).'</td>
                <td>'.round($key['rz8'], 2).'</td>
                <td>'.$key['d2'].'</td>
                </tr>';
			$i++;
		}
		$table.='</tbody>
		</table>';
		echo $table;
	}else
	{
		echo '<span class="label label-success" style="font-size:16px">'.tt('Зарегистрированных пропусков нет').'</span>';
	}
        
        
?>