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

$this->pageHeader=tt('Додавання в "Портфоліо робіт"');
$this->breadcrumbs=array(
    tt('Портфолио')=> array('/portfolioFarm/index'),
    tt('Додавання в "Портфоліо робіт"'),
);


echo $this->renderPartial('stpeduwork/_form-stpeduwork', array(
    'model' => $model
));