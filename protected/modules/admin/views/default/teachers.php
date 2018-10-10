<?php
/**
 *
 * @var DefaultController $this
 * @var P $model
 *
 */

    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/admin/teachers.js', CClientScript::POS_HEAD);
    
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/admin/gsuite.js');
    
    $this->pageHeader=tt('Преподаватели');
    $this->breadcrumbs=array(
        tt('Админ. панель'),
    );
?>
<?php
    $pageSize=Yii::app()->user->getState('pageSize',10);
    $chairs = CHtml::listData(K::model()->getAllChairs(), 'k1', 'k3');
    //echo CHtml::label(tt('Кафедра'), 'chairs');
    echo CHtml::dropDownList('chairs', '', $chairs, array('class'=>'chosen-select', 'autocomplete' => 'off', 'empty' => ''));

    $provider = $model->getTeachersFor($chairId);

    $template = <<<HTML
            <div>
                <div class="pull-left">{summary}</div>
                <div class="pull-right">{pager}</div>
            </div>
            {items}
            <div>
                <div class="pull-right">{pager}</div>
            </div>
HTML;

    $this->widget('bootstrap.widgets.TbGridView', array(
        'id' => 'teachers',
        'dataProvider' => $provider,
        'filter' => $model,
        'pager' => array(
            'firstPageLabel'=>'<<',
            'prevPageLabel'=>'<',
            'nextPageLabel'=>'>',
            'lastPageLabel'=>'>>',
            'class'=>'bootstrap.widgets.TbPager',
            'displayFirstAndLast'=>true
            //'maxButtonCount'=>'10',
            //'header'=>'<span>Листалка страниц:</span>',
        ),
        //'enableHistory'=>true,
        'template' => $template,
        'type' => 'striped bordered',
        'ajaxUrl' => Yii::app()->createAbsoluteUrl('/admin/default/teachers'),
        'columns' => array(
            'p3',
            'p4',
            'p5',
			'p9'=>array(
				'name' => 'p9',
				'value'=>'$data->getP9String()',
				//'filter' => '',
				'htmlOptions' => array(
					'style' => 'min-width:130px;',
				),
			),
			/*'p13'=>array(
				'name' => 'p13',
				'filter' => '',
			),*/
            'p13',
            array(
                'header' => 'Login',
                'filter' => CHtml::textField('login', Yii::app()->request->getParam('login')),
                'name'   => 'account.u2',
                'value'  => '! empty($data->account)
                                ? $data->account->u2
                                : ""',
            ),
            /*array(
                'header' => 'Password',
                'filter' => CHtml::textField('password', Yii::app()->request->getParam('password')),
                'name'   => 'account.u3',
                'value'  => '! empty($data->account)
                                ? $data->account->u3
                                : ""',
            ),*/
            array(
                'header' => 'Email',
                'filter' => CHtml::textField('email', Yii::app()->request->getParam('email')),
                'name'   => 'account.u4',
                'value'  => '! empty($data->account)
                                ? $data->account->u4
                                : ""',
            ),
            array(
                'header' => tt('Статус'),
                'filter' => CHtml::dropDownList('status', Yii::app()->request->getParam('status'), array(''=>'',1=>tt('Администратор'))),
                'name'   => 'account.u7',
                'value'  => '! empty($data->account)
                                ? $data->account->u7 == 1 ? "<span class=\"label label-warning\">'.tt("Администратор").'</span>" : ""
                                : ""',
                'type'   => 'raw'
            ),
            array(
                'class'=>'CButtonColumn',
                'template'=>'{grants} {gsuite} {enter} {delete}',
                //'header' => tt('Права доступа'),
                'header'=>CHtml::dropDownList(
                        'pageSize',
                        $pageSize,
                        $this->getPageSizeArray(),
                        array('class'=>'change-pageSize')
                    ),
                'buttons'=>array
                (
                    'grants' => array(
                        'label'=>'<i class="icon-check bigger-120"></i>',
                        'imageUrl'=>false,
                        'url'=>'Yii::app()->createAbsoluteUrl("/admin/default/PGrants", array("id" => $data->p1))',
                        'options' => array(
                            'class' => 'btn btn-mini btn-success',
                            'title'=>tt('Редактировать'),
                        ),
                    ),
                    'gsuite' => array(
                        'label'=>'<i class="icon-info-sign bigger-120"></i>',
                        'imageUrl'=>false,
                        'url'=>'Yii::app()->createAbsoluteUrl("/admin/default/GsuiteInfo", array("uname" => !empty($data->account)? $data->account->u2: "-1"))',
                        'options' => array(
                            'class' => 'btn btn-mini btn-primary',
                            //'class' => 'btn btn-mini btn-primary gsuite',
                            'title'=>tt('GoogleSuite User info'),
                        ),
                        'click'=>"function( e ){ e.preventDefault(); getGoogleInfoClick((this).href); return false;}",
                        'visible'=>'!empty($data->account)'
                    ),
                    'enter' => array(
                        'label'=>'<i class="icon-share bigger-120"></i>',
                        'imageUrl'=>false,
                        'url'=>'Yii::app()->createAbsoluteUrl("/admin/default/enter", array("id" => !empty($data->account)? $data->account->u1: "-1"))',
                        'options' => array(
                            'class' => 'btn btn-mini btn-primary',
                            'title'=>tt('Авторизироваться'),
                        ),
                        'visible'=>'!empty($data->account)'
                    ),
                    'delete' => array(
                        'label'=>'<i class="icon-trash bigger-120"></i>',
                        'imageUrl'=>false,
                        'url'=>'Yii::app()->createAbsoluteUrl("/admin/default/deleteUser", array("id" => !empty($data->account)? $data->account->u1: "-1"))',
                        'options' => array(
                            'class' => 'btn btn-mini btn-danger',
                            'title'=>tt('Удалить'),
                        ),
                        'visible'=>'!empty($data->account)'
                    ),
                ),
            ),
        ),
    ));

Yii::app()->clientScript->registerScript('initPageSize',"
	   $(document).on('change','.change-pageSize', function() {
	        $.fn.yiiGridView.update('teachers',{ data:{ pageSize: $(this).val(), chairId: $('#chairs').val() }})
	    });",CClientScript::POS_READY);
?>
  <div class="modal fade" id="gSuiteInfoModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">GoogleSuite Directory User Info</h4>
        </div>
        <div class="modal-body" id="gsuiteinfo">
          <p>No Google user data...</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>