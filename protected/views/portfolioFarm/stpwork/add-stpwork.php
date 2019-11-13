<?php
/**
 * Created by PhpStorm.
 * User: Lesha
 * Date: 11.09.2019
 * Time: 11:21
 */

/**
 * @var PortfolioFarmController $this
 * @var Stpwork $model
 */

$this->pageHeader=tt('Добавление в "Учебно-профессиональная деятельность"');
$this->breadcrumbs=array(
    tt('Портфолио')=> array('/portfolioFarm/index'),
    tt('Добавление в "Учебно-профессиональная деятельность"'),
);


echo $this->renderPartial('stpwork/_form-stpwork', array(
    'model' => $model
));