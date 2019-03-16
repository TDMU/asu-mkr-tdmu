<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 19.02.2019
 * Time: 16:49
 */

/**
 * @var $model Um
 * @var $this AlertController
 * @var $text string
 * @var $name string
 * @var $url string
 * @var $date string
 * @var $extra string
 * @var $extraTitle string
 */

$classNotification = '';
$strNotification = '';
if($model->um4 == 1 && $model->um2 == Yii::app()->user->id && $model->um7 > 0) {
    $to = Users::model()->findByPk($model->um7);
    if($to != null){
        $classNotification = $to->u15 > $model->um3 ? 'badge badge-success' : 'badge badge-important';
        /*$strNotification =
            $to->u15 > $model->um3 ?
                '<span class="badge badge-success"><i class="icon-plus"></i></span>' :
                '<span class="badge badge-important"><i class="icon-minus"></i></span>';

        $strNotification = CHtml::tag('div',  array(
            'class' => 'pull-right'
        ), $strNotification);*/
    }
}

$pattern = <<<HTML
            <div class="media">
              <a class="pull-left" href="#">
                %s
              </a>
              <div class="media-body">
                <h5 class="media-heading {$classNotification}">%s</h5>
                <div class="well well-small">%s</div> 
                %s
              </div>
            </div>
HTML;


echo sprintf($pattern,
    empty($url) ? '' : '<img class="media-object" src="'.$url.'">',
    tt('{username} <small>{date}</small> {read}', array(
        '{username}' => $name,
        '{date}' => date('d.m.Y H:i',strtotime($date)),
        '{read}' => $strNotification
    )). $extraTitle,
    CHtml::encode($text),
    $extra);