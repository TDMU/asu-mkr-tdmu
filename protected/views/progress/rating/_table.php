<?php
/**
 * Created by PhpStorm.
 * User: NEFF
 * Date: 18.01.2018
 * Time: 19:41
 */

/**
 * @var $model RatingForm
 */
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType'=>'link',
    'type'=>'primary',
    'icon'=>'print',
    'label'=>tt('Печать'),
    'url'=>Yii::app()->createUrl('/progress/ratingExcel',
        array(
            'group'=>$model->group,
            'semStart'=>$model->semStart,
            'semEnd'=>$model->semEnd,
            'ratingType'=>$model->ratingType,
            'stType'=>$model->stType,
            'course'=>$model->course
        )
    ),
    'htmlOptions'=>array(
        'class'=>'btn-mini',
        'id'=>'rating-print',
    )
));


$rating = $model->getRating($model->ratingType==1 ? RatingForm::COURSE : RatingForm::GROUP);

$urlSt = Yii::app()->createUrl('/progress/ratingStudent');
if(!empty($rating))
{
    ?>
    <table id="rating" class="table table-striped table-hover table-condensed" data-url-st="<?=$urlSt?>">
        <thead>
        <tr>
            <th style="width:40px">№</th>
            <th style="width:40px"><?=tt('Рейтинг')?></th>
            <th><?=tt('Ф.И.О.')?></th>
            <th><?=$model->getAttributeLabel('group')?></th>
            <th><?=tt('Балл')?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $i=0;
        $num = 1;
        $val='';

        foreach($rating as $key)
        {
            $_bal = round($key['value'], 2);
            if($_bal!=$val)
            {
                $val=$_bal;
                $i++;
            }

            $name = ShortCodes::getShortName($key['stInfo']['st2'], $key['stInfo']['st3'], $key['stInfo']['st4']);
            $a = CHtml::link($name, '#', array(
                'data-st1'=>$key['st1'],
                'class'=>'a-show-st-disp'
            ));

            echo '<tr>'.
                '<td>'.$num.'</td>'.
                '<td>'.$i.'</td>'.
                '<td>'.$a.'</td>'.
                '<td>'.$key['stInfo']['group'].'</td>'.
                '<td>'.$_bal.'</td>'.
                '</tr>';

            $num++;
        }
        ?>
        </tbody>
    </table>
    <?php

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

    <div class="modal-body" style="overflow-y: unset">
        <div id="modal-content" >

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

}else
{
    ?>
    <div class="alert alert-danger" role="alert">
        <?=tt('Нет данных')?>
    </div>
    <?php
}