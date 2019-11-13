<?php
/**
 * Created by PhpStorm.
 * User: Lesha
 * Date: 11.09.2019
 * Time: 13:05
 */

/**
 * @var PortfolioFarmController $this
 * @var Stppart $model
 */

$this->pageHeader=tt('Редактирование в "Данные об участии в мероприятиях"');
$this->breadcrumbs=array(
    tt('Портфолио')=> array('/portfolioFarm/index'),
    tt('Редактирование в "Учебно-профессиональная деятельность"'),
);

echo $this->renderPartial('stppart/_form-stppart', array(
    'model' => $model
));