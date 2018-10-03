<?php
/**
 *
 * @var DefaultController $this
 * @var Users $user
 *
 */

//Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/admin/teachers.js', CClientScript::POS_HEAD);

$this->pageHeader=tt('Настройки');
$this->breadcrumbs=array(
    tt('Студенты') => array('/admin/default/students'),
    tt('Настройки'),
);
?>

<?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'grants',
        'type' => 'horizontal',
        'action' => ''//$this->createUrl('/admin/prospect/scheduleProspect')
    ));
    echo $form->errorSummary($user);
?>
<?php
    $flashMessages = Yii::app()->user->getFlashes();
    if ($flashMessages) {
        echo '<ul class="flashes">';
        foreach($flashMessages as $key => $message) {
            echo '<li><div class="flash-' . $key . '">' . $message . "</div></li>\n";
        }
        echo '</ul>';
    }
?>
    <div class="control-group">
        <label for="Users_u2" class="control-label"><?=tt('Логин')?></label>
        <div class="controls">
            <label>
                <?=CHtml::textField('Users[u2]', $user->u2)?>
                <span class="lbl"></span>
            </label>
        </div>
    </div>

    <div class="control-group">
        <label for="Users_u3" class="control-label"><?=tt('Пароль')?></label>
        <div class="controls">
            <label>
                <?=CHtml::passwordField('Users[u3]', $user->u3)?>
                <span class="lbl"></span>
            </label>
        </div>
    </div>

    <div class="control-group">
        <label for="Users_u3" class="control-label"><?=tt('Повторите пароль')?></label>
        <div class="controls">
            <label>
                <?=CHtml::passwordField('Users[password]', $user->password)?>
                <span class="lbl"></span>
            </label>
        </div>
    </div>

    <div class="control-group">
        <label for="Users_u4" class="control-label">Email</label>
        <div class="controls">
            <label>
                <?=CHtml::textField('Users[u4]', $user->u4)?>
                <span class="lbl"></span>
            </label>
        </div>
    </div>

    <div class="control-group">
        <label for="Users_u8" class="control-label"><?=tt('Заблокирован')?></label>
        <div class="controls">
            <label>
                <?php
                echo CHtml::checkBox('Users[u8]', $user->u8,
                    array(
                        'class' => 'ace ace-switch',
                        'uncheckValue' => '0'
                    )
                )
                ?>
                <span class="lbl"></span>
            </label>
        </div>
    </div>

    <div class="control-group">
        <label for="Users_updategoogle" class="control-label"><?=tt('Update Google Directory')?></label>
        <div class="controls">
            <label>
                <?php
                echo CHtml::checkBox('Users[updategoogle]', 1,
                    array(
                        'class' => 'ace ace-switch',
                        'uncheckValue' => '0'
                    )
                )
                ?>
                <span class="lbl"></span>
            </label>
        </div>
    </div>

    <div class="control-group">

    <?php 
    //   echo CHtml::ajaxLink(
    //       'Get GSuite Info', 
    //       array('/admin/default/GsuiteInfo/uname/'.$user->u2),
    //       array('success' => 'js:function(data){$("#gsuiteinfo").html(data);}')
    //   );
    ?>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-info">
            <i class="icon-ok bigger-110"></i>
            <?=tt('Сохранить')?>
        </button>
    <?php
        echo CHtml::ajaxButton(
            'Get GSuite Info',
            array('/admin/default/GsuiteInfo/uname/'.$user->u2),
            array(
                'data'=>array('uname'=>$user->u2),
                'type'=>'GET',
//                'update'=>'#gsuiteinfo',
                'success' => 'js:function(data){$("#gsuiteinfo").html(data);}'
            ), 
            array('class'=>'btn btn-info')
        );
?>        
    </div>
<div id="gsuiteinfo">
No GoogleSuite Directory Info...
</div>
<script>
$(document).ready(function(){

   // $('.gsuite').click(function(){
       alert('gsuite');
     //  return false;
    //});
    
});
</script>
<?php $this->endWidget();