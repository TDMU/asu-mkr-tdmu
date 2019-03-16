<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 19.02.2019
 * Time: 16:42
 */

/**
 * @var $model Users
 * @var $this AlertController
 * @var $message Um
 * @var $isOutputEnabled bool
 */


$user = Users::model()->findByPk($message->um2);
if(empty($user)){
    $url= '#';
    $name = '-';
}else{
    $url = Yii::app()->createUrl('/site/userPhoto', array(
        '_id' => $user->u6,
        'type' => $user->u5 == 1 ? 0 : 1
    ));
    $name = $user->getNameWithDept();
}

$extra = '';
/*if($message->um10 > 0)
    if(!empty($message->um100)){
        $extra = $this->renderPartial('_outputMessage', array(
            'model' => $model,
            'message'=> $message->um100
        ), true);
    }*/
$extraTitle = $isOutputEnabled ? CHtml::tag(
    'div',
    array(
        'class' => 'pull-right'
    ),
    CHtml::button(
        tt('Ответить'),
        array(
            'class' => 'btn btn-mini btn-success btn-response',
            'data-id' => $message->um2,
            'data-name' => $name,
            'data-type' => ! $user ? '0' : ($user->u5 == 0 ? 1 : 4)
        )
    )
) : '';

echo $this->renderPartial('_message', array(
    'date' => $message->um3,
    'text' => $message->um5,
    'url'=> $url,
    'name' => $name,
    'extra' => $extra,
    'model' =>$message,
    'extraTitle' => $extraTitle
));