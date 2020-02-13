<?php
/**
 * Created by PhpStorm.
 * User: Lesha
 * Date: 11.09.2019
 * Time: 13:05
 */

/**
 * @var PortfolioFarmController $this
 * @var Stpfwork $model
 */

$this->pageHeader=tt('Редактирование в "Портфолио профессиональной реализации"');
$this->breadcrumbs=array(
    tt('Портфолио')=> array('/portfolioFarm/index'),
    tt('Редактирование в "Портфолио работ"'),
);

echo $this->renderPartial('stpfwork/_form-stpfwork', array(
    'model' => $model
));