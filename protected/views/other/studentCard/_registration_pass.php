<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 24.10.2018
 * Time: 16:01
 */

/**
 * @var $st St
 */

$ps137 = PortalSettings::model()->getSettingFor(PortalSettings::ENABLE_REGISTRATION_PASS);

if($ps137!=0):


    $tabs = array(
        array(
            'label'=>tt('Пропуски'),
            'content'=>$this->renderPartial(
                'studentCard/_passes',
                array(
                    //'passList'=>$passList,
                    'st' => $st
                ),
                true
            ),
            'active'=>true
        ),
        array(
            'label'=>tt('Заявки на оплату'),
            'content'=>$this->renderPartial(
                'studentCard/_requestPayments',
                array(
                    'st' => $st
                ),
                true
            ),
        ),
        array(
            'label'=>tt('Справки'),
            'content'=>$this->renderPartial(
                'studentCard/_references',
                array(
                    'st' => $st
                ),
                true
            ),
        )
    );

    $this->widget('bootstrap.widgets.TbTabs', array(
        'type'=>'tabs',
        'placement'=>'top',
        'tabs'=>$tabs,
    ));

else:
    echo CHtml::tag('div', array('class'=> 'alert alert-error'), tt('Регистрация пропусков не активна'));
endif;