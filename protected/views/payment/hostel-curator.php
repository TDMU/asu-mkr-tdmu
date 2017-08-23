<?php
/**
 *
 * @var TimeTableController $this
 * @var TimeTableForm $model
 */

$this->pageHeader=tt('Оплата за общежитие');
$this->breadcrumbs=array(
		tt('Оплата'),
);

$this->renderPartial('/filter_form/payment/student', array(
    'model' => $model,
	'type'=>0
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
	$plans    = Spob::model()->getPlan($model->student);
	$payments = Sob::model()->getPayments($model->student);

	$this->renderPartial('_bottom', array(
			'plans' => $plans,
			'payments' => $payments,
	));
}
