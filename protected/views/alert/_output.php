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
 */

$messages = Um::model()->findAllByAttributes(array('um2'=> $model->u1));

if(empty($messages)): ?>
   <div class="alert alert-warning">
        <?=tt('Нет отправленных сообщений')?>
    </div>
<?php
    endif;


foreach ($messages as $message){
    echo $this->renderPartial('_outputMessage', array(
        'model' => $model,
        'message'=> $message
    ));
}


