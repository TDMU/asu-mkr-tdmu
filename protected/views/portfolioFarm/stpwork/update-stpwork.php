<?php
/**
 * Created by PhpStorm.
 * User: Lesha
 * Date: 11.09.2019
 * Time: 13:05
 */

/**
 * @var PortfolioFarmController $this
 * @var Stpwork $model
 */

$this->pageHeader=tt('Редактирование в "Учебно-профессиональная деятельность"');
$this->breadcrumbs=array(
    tt('Портфолио')=> array('/portfolioFarm/index'),
    tt('Редактирование в "Учебно-профессиональная деятельность"'),
);

echo $this->renderPartial('stpwork/_form-stpwork', array(
    'model' => $model
));