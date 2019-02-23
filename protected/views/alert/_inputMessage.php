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
 */


$user = Users::model()->findByPk($message->um2);
if(empty($user)){
    $url= '#';
    $name = '-';
}else{
    $url = Yii::app()->createUrl('/site/userPhoto', array(
        '_id' => $user->u5,
        'type' => $user->u6
    ));
    $name = $user->getNameWithDept();
}

$extra = '';
$extra = '';
/*if($message->um10 > 0)
    if(!empty($message->um100)){
        $extra = $this->renderPartial('_outputMessage', array(
            'model' => $model,
            'message'=> $message->um100
        ), true);
    }*/

echo $this->renderPartial('_message', array(
    'date' => $message->um3,
    'text' => $message->um5,
    'url'=> $url,
    'name' => $name,
    'extra' => $extra
));