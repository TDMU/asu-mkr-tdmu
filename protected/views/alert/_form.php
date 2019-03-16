<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 11.03.2019
 * Time: 10:34
 */

Yii::app()->clientScript->registerPackage('autocomplete');

$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType'=>'button',
    'type'=>'success',

    'icon'=>'plus',
    'label'=>tt('Написать сообщение'),
    'htmlOptions'=>array(
        'class'=>'btn-small',
        'id'=>'btn-new-message',
        'data-toggle' => 'modal',
        'data-target' => '#myModal',
    )
));

$this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'myModal')
); ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4><?=tt('Написать новое сообщение')?></h4>
    </div>

    <div class="modal-body">
        <?php
        $formModel = new CreateMessageForm();
        /**
         * @var $form TbActiveForm
         */
        $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id'=>'create-message-form',
            'htmlOptions' => array('class' => ''),
            'method'=>'post',
            'action'=> array('alert/send'),
            'enableAjaxValidation'=>true,
            'clientOptions'=>array(
                //'validateOnSubmit'=>true,
                'validationUrl' => array('alert/validateSend')
            ),
        ));

        echo $form->errorSummary($formModel);
        echo '<div class="row-fluid">';
        echo '<div class="span6">';
        echo $form->dropDownListRow($formModel,'type', $formModel->getTypes());
        echo '</div>';

        echo '<div class="span6" id="faculty-block">';
        echo $form->dropDownListRow($formModel,'faculty', CHtml::listData(F::model()->getAllFaculties(), 'f1', 'f3'));
        echo '</div>';

        echo '<div class="span12">';
        echo '<div class="control-group">';
        echo '<div class="controls">';
        echo $form->hiddenField($formModel,'to', array());
        echo $form->textFieldRow($formModel,'searchField',array('class' => 'autocomplete'));
        /*echo CHtml::activeLabel($formModel, 'searchField',  array()).
            CHtml::activeTextField($formModel,'searchField', array('class' => 'autocomplete'));*/
        echo '</div>';
        echo '</div>';
        echo '</div>';


        echo '<div class="span12">';
        echo $form->textAreaRow($formModel,'body',
            array(
                'rows'=>6,
                'cols'=>100,
                'style'=> 'width:90%'
            )
        );

        echo '<div id="notification-block">';
        echo $form->checkBoxRow($formModel, 'notification');
        echo '</div>';
        echo $form->checkBoxRow($formModel, 'sendMail');
        echo '</div>';
        echo '</div>';

        $this->endWidget();
        ?>
    </div>

    <div class="modal-footer">
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'buttonType' => 'button',
                'type' => 'primary',
                'label' => tt('Отправить'),
                'url' => '#',
                'htmlOptions' => array(
                    'id'=>'btn-send-new-message',
                    'data-loading-text' => "Loading..."
                ),
            )
        ); ?>
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'label' => tt('Отмена'),
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal'),
            )
        ); ?>
    </div>

<?php $this->endWidget();

$url = Yii::app()->createUrl('alert/autocomplete');

Yii::app()->clientScript->registerScript('alert-autocomplite', <<<JS
    $('#btn-new-message').click(function(e) {
        $("#CreateMessageForm_type").val('1');
        typeChanged($("#CreateMessageForm_type"));
    });

    $('#btn-send-new-message').click(function(e) {
        $(this).attr("disabled", "disabled");
        $('#create-message-form').submit();
    });
    
    $('.btn-response').click(function(e) {
        
        $("#CreateMessageForm_type").val($(this).data('type'));
        typeChanged($("#CreateMessageForm_type"));
        
        $('#CreateMessageForm_to').val($(this).data('id'));
        $('#CreateMessageForm_searchField').val($(this).data('name'));  
        
        
        $('#myModal').modal('show');
        e.preventDefault();
    });

    $("#CreateMessageForm_type").change(function() {
        typeChanged($(this));
    });

    function typeChanged(elem){
        $(this).attr("disabled", false);
        $('#CreateMessageForm_to').val('');  
        $('#CreateMessageForm_searchField').val('');  
        var current = elem.val();
        
        if(current == '2' || current == '3'){
            $('#notification-block').hide();
            $('#faculty-block').show();
        }else {
            $('#notification-block').show();
            if(current == '4')
                $('#faculty-block').hide();
            else 
                $('#faculty-block').show();
        }
    }
    
    $('.autocomplete').autocomplete({
        serviceUrl: 
            function(obj){
                return "{$url}?type=" +  $("#CreateMessageForm_type").val() + "&faculty=" + $("#CreateMessageForm_faculty").val();
            },
        
        minChars:2,
        delimiter: /(,|;)\s*/, // regex or character
        maxHeight:300,
        width:'auto',
        zIndex: 9999,
        deferRequestBy: 0, //miliseconds
        params: { }, //aditional parameters
        noCache: true, //default is false, set to true to disable caching
        // callback function:
        onSelect: function(obj){
            $('#CreateMessageForm_to').val(obj.id); 
        }
    });
JS
);

Yii::app()->clientScript->registerCss('autocomplete-style', <<<CSS
    .autocomplete-suggestions { border:1px solid #999; background:#FFF; cursor:pointer; text-align:left; max-height:350px; overflow:auto; margin:-6px 6px 6px -6px; /* IE6 specific: */ _height:350px;  _margin:0; _overflow-x:hidden; }
    .autocomplete-selected { background:#F0F0F0; }
    .autocomplete-suggestions div { padding:2px 5px; white-space:nowrap; overflow:hidden; }
    .autocomplete-suggestions strong { font-weight:normal; color:#3399FF; }
CSS
);