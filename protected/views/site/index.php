<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

switch (Yii::app()->language){
        case 'uk': $id=61; break;
        case 'en': $id=63; break;
        case 'ru':
        default: $id=62; break;
}
echo '<div style="margin-top: 50px">';
    echo PortalSettings::model()->findByPk($id)->ps2;
echo '</div>';
