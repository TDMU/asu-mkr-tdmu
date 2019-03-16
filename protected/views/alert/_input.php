<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 15.02.2019
 * Time: 13:50
 */


/**
 * @var $this AlertController
 * @var $model Users
 * @var $period string
 */


$messages = $model->getInputMessages($period);

if(empty($messages)): ?>
    <div class="alert alert-warning">
        <?=tt('Нет входящих сообщений')?>
    </div>
<?php
endif;


foreach ($messages as $message){
    echo $this->renderPartial('_inputMessage', array(
        'model' => $model,
        'message'=> $message,
        'isOutputEnabled' => $isOutputEnabled
    ));
}