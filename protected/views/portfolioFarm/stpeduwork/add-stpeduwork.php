<?php
/**
 * Created by PhpStorm.
 * User: Lesha
 * Date: 11.09.2019
 * Time: 11:21
 */

/**
 * @var PortfolioFarmController $this
 * @var Stpeduwork $model
 */

$this->pageHeader=tt('Добавление в "Портфолио работ"');
$this->breadcrumbs=array(
    tt('Портфолио')=> array('/portfolioFarm/index'),
    tt('Добавление в "Портфолио работ"'),
);


echo $this->renderPartial('stpeduwork/_form-stpeduwork', array(
    'model' => $model
));