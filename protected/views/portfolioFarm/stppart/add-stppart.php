<?php
/**
 * Created by PhpStorm.
 * User: Lesha
 * Date: 11.09.2019
 * Time: 11:21
 */

/**
 * @var PortfolioFarmController $this
 * @var Stppart $model
 */

$this->pageHeader=tt('Додавання в "Дані щодо участі у заходах"');
$this->breadcrumbs=array(
    tt('Портфолио')=> array('/portfolioFarm/index'),
    tt('Додавання в "Навчально-професійна діяльність"'),
);


echo $this->renderPartial('stppart/_form-stppart', array(
    'model' => $model
));