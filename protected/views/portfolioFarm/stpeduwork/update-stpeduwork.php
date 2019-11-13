<?php
/**
 * Created by PhpStorm.
 * User: Lesha
 * Date: 11.09.2019
 * Time: 13:05
 */

/**
 * @var PortfolioFarmController $this
 * @var Stpeduwork $model
 */

$this->pageHeader=tt('Редактирование в "Портфолио работ"');
$this->breadcrumbs=array(
    tt('Портфолио')=> array('/portfolioFarm/index'),
    tt('Редактирование в "Портфолио работ"'),
);

echo $this->renderPartial('stpeduwork/_form-stpeduwork', array(
    'model' => $model
));