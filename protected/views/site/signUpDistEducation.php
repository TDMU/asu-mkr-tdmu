<?php
/**
 * Created by PhpStorm.
 * User: NEFF
 * Date: 16.11.2017
 * Time: 13:33
 */

$this->pageTitle=tt('Регистрация в системе дистанционного обучения');
$this->pageHeader=tt('Регистрация в системе дистанционного обучения');

echo '<h3>'.tt('Выберите один и предложеных вариантов:').'</h3>';
$this->widget('bootstrap.widgets.TbButtonGroup', array(
    'size' => 'large',
    'buttons'=>array(
        array(
            'label'=>tt('Новая учетная запись'),
            'url'=>array('/site/signUpNewDistEducation'),
            'type'=>'success'
        ),
        array(
            'label'=>tt('Существующая учетная запись'),
            'url'=>array('/site/signUpOldDistEducation'),
            'type'=>'primary'
        ),
        array(
            'label'=>tt('Отмена'),
            'url'=>array('/site/index'),
            'type'=>'warning'
        ),
    ),
));
