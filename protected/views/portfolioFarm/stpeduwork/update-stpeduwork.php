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

$this->pageHeader=tt('Редагування в "Портфоліо робіт"');
$this->breadcrumbs=array(
    tt('Портфолио')=> array('/portfolioFarm/index'),
    tt('Редагування в "Портфоліо робіт"'),
);

echo $this->renderPartial('stpeduwork/_form-stpeduwork', array(
    'model' => $model
));