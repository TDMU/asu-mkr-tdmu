<?php
/**
 *
 * @var TimeTableController $this
 * @var TimeTableForm $model
 */

$this->pageHeader=tt('Оплата за обучение');
$this->breadcrumbs=array(
		tt('Оплата'),
);

$this->renderPartial('/filter_form/payment/student', array(
    'model' => $model,
	'type'=>1
));

$js = '
	initSpinner("spinner1");
    $spinner1 = $("#spinner1");
    initFilterForm($spinner1);
';

Yii::app()->clientScript->registerScript('initFilterForm',$js, CClientScript::POS_END);

echo <<<HTML
    <span id="spinner1"></span>
HTML;


if (! empty($model->student)) {
	$plans    = Spo::model()->getPlan($model->student);
	$payments = So::model()->getPayments($model->student);

	$this->renderPartial('_bottom', array(
			'plans' => $plans,
			'payments' => $payments,
	));
}
