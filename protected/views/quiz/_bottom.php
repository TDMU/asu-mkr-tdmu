<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 17.11.2018
 * Time: 13:10
 */

/**
 * @var QuizController $this
 * @var TimeTableForm $model
 */

$gr = Gr::model()->findByPk($model->group);

if(empty($gr)):
    echo CHtml::tag('div', array('class' => 'alert alert-warning'), tt('Группа не выбрана'));
else:
?>
    <div class="row-fluid">
        <div class="span12">
            <?=$this->renderPartial('_list', array(
                'model' => $model,
                'group' => $gr
            ));?>
        </div>
    </div>
<?php
    endif;