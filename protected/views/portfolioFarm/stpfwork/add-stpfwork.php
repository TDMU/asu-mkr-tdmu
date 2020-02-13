<?php
/**
 * Created by PhpStorm.
 * User: Lesha
 * Date: 11.09.2019
 * Time: 11:21
 */

/**
 * @var PortfolioFarmController $this
 * @var Stpfwork $model
 */

$this->pageHeader=tt('Добавление в "Портфолио профессиональной реализации"');
$this->breadcrumbs=array(
    tt('Портфолио')=> array('/portfolioFarm/index'),
    tt('Добавление в "Портфолио работ"'),
);


echo $this->renderPartial('stpfwork/_form-stpfwork', array(
    'model' => $model
));