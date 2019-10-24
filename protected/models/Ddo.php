<?php

/**
 * This is the model class for table "ddo".
 *
 * The followings are the available columns in table 'ddo':
 * @property integer $ddo1
 * @property string $ddo2
 * @property integer $ddo3
 * @property integer $ddo4
 * @property integer $ddo5
 * @property integer $ddo6
 * @property integer $ddo7
 * @property integer $ddo8
 * @property integer $ddo9
 * @property integer $ddo10
 * @property integer $ddo11
 * @property integer $ddo12
 * @property integer $ddo13
 * @property integer $ddo14
 * @property integer $ddo15
 *
 * The followings are the available model relations:
 * @property Ddoi[] $ddois
 * @property Ddon[] $ddons
 * @property Tddo[] $tddos
 */
class Ddo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ddo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ddo1, ddo3, ddo4, ddo5, ddo6, ddo7, ddo8, ddo9, ddo10, ddo11, ddo12, ddo13, ddo14, ddo15', 'numerical', 'integerOnly'=>true),
			array('ddo2', 'length', 'max'=>180),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'ddois' => array(self::HAS_MANY, 'Ddoi', 'ddoi2'),
			'ddons' => array(self::HAS_MANY, 'Ddon', 'ddon2'),
			'tddos' => array(self::HAS_MANY, 'Tddo', 'tddo2'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ddo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/*
	 * Отношение полей tddo к полям ddo
	 *
	 */
	public function getTddoFiledByDdo($ddoField){
		$field = '';
		switch($ddoField){
			case 'ddo3':
				$field = 'tddo7';
				break;
			case 'ddo4':
				$field = 'tddo4';
				break;
			case 'ddo5':
				$field = 'tddo8';
				break;
			case 'ddo6':
				$field = 'tddo9';
				break;
			case 'ddo7':
				$field = 'tddo18';
				break;
			case 'ddo8':
				$field = 'tddo6';
				break;
			case 'ddo9':
				$field = 'ddo9';
				break;
			case 'ddo10':
				$field = 'tddo19';
				break;
			case 'ddo11':
				$field = 'tddo11';
				break;
			case 'ddo12':
				$field = 'tddo10';
				break;
			case 'ddo13':
				$field = 'tddo17';
				break;
			case 'ddo14':
				$field = 'tddo16';
				break;
			case 'ddo15':
				$field = 'tddo20';
				break;
			default:
				$field = '';

		}
		return $field;
	}
	/*Колонки для грида для типа докумнта*/
	/* $controller контроллеря для фильтров дат для грида*/
	/* $model model tddo*/
	public function generateColumnsGrid($controller, $model){
		$docTypeIndexModel = Ddoi::model()->findByAttributes(array('ddoi2'=>$this->ddo1));
		if(empty($docTypeIndexModel))
			$docTypeIndexModel = new Ddoi();
		$docTypeNameModel = Ddon::model()->findByAttributes(array('ddon2'=>$this->ddo1));
		if(empty($docTypeNameModel))
			$docTypeNameModel = new Ddon();

		for($i=3;$i<=15; $i++){

			$nameAttr = 'ddon'.$i;
			$indexAttr = 'ddoi'.$i;
			$ddoAttr = 'ddo'.$i;

			$tddoFiled = Ddo::model()->getTddoFiledByDdo($ddoAttr);

			if($tddoFiled=='')
				continue;

			if($tddoFiled=='ddo9')
				continue;

			$items[$docTypeIndexModel->$indexAttr] = array(
					'name' => $tddoFiled,
					'header'=>$docTypeNameModel->$nameAttr,
					'value' => '$data->'.$tddoFiled,
					'visible'=>$this->$ddoAttr==1
			);

			switch ($tddoFiled){
				case 'tddo9':
					$items[$docTypeIndexModel->$indexAttr]['value'] = function($data) {
						if(empty($data['tddo9']))
							return '';
						return date_format(date_create_from_format('Y-m-d H:i:s', $data['tddo9']), 'Y-m-d');
					};
					$items[$docTypeIndexModel->$indexAttr]['filter']=$controller->widget('zii.widgets.jui.CJuiDatePicker', array(
							'model'=>$model,
							'attribute'=>'tddo9',
							'language' => Yii::app()->language,
						// 'i18nScriptFile' => 'jquery.ui.datepicker-ja.js', (#2)
							'htmlOptions' => array(
									'id' => 'datepicker-for-tddo9',
									'size' => '10',
							),
							'options' => array(
									'dateFormat' => 'yy-mm-dd'
							),
							'defaultOptions' => array(  // (#3)
									'showOn' => 'focus',
									'dateFormat' => 'yy-mm-dd',
									'showOtherMonths' => true,
									'selectOtherMonths' => true,
									'changeMonth' => true,
									'changeYear' => true,
									'showButtonPanel' => true,
							)
							),
					true);
				break;
				case 'tddo4':
					$items[$docTypeIndexModel->$indexAttr]['value'] = function($data) {
						if(empty($data['tddo4']))
							return '';
						return date_format(date_create_from_format('Y-m-d H:i:s', $data['tddo4']), 'Y-m-d');
					};
					$items[$docTypeIndexModel->$indexAttr]['filter']=$controller->widget('zii.widgets.jui.CJuiDatePicker', array(
							'model'=>$model,
									'attribute'=>'tddo4',
									'language' => Yii::app()->language,
								// 'i18nScriptFile' => 'jquery.ui.datepicker-ja.js', (#2)
									'htmlOptions' => array(
											'id' => 'datepicker-for-tddo4',
											'size' => '10',
									),
									'options' => array(
											'dateFormat' => 'yy-mm-dd'
									),
									'defaultOptions' => array(  // (#3)
											'showOn' => 'focus',
											'dateFormat' => 'yy-mm-dd',
											'showOtherMonths' => true,
											'selectOtherMonths' => true,
											'changeMonth' => true,
											'changeYear' => true,
											'showButtonPanel' => true,
									)
							),
					true);
				break;
				case 'tddo18':
					$items[$docTypeIndexModel->$indexAttr]['value'] = function($data) {
						if($data->tddo18==0)
							return '';

						$org = $data->tddo180;

						return sprintf('%s / %s / %s',$org->org2,$org->org3,$org->org4);
					};
					$items[$docTypeIndexModel->$indexAttr]['filter'] = Org::getAll();
				break;
				case 'tddo20':
					$items[$docTypeIndexModel->$indexAttr]['value'] = function($data) {
						if($data->tddo20==0)
							return '';
						$tdo = $data->tddo200;

						return $tdo->tdo2;
					};
					$items[$docTypeIndexModel->$indexAttr]['filter'] = Tdo::getAll();
				break;
				case 'tddo17':
					$items[$docTypeIndexModel->$indexAttr]['value'] ='$data->getTddo17Type()';
					$items[$docTypeIndexModel->$indexAttr]['filter'] = Tddo::model()->getTddo17Types();
					break;
				case 'tddo16':
					$items[$docTypeIndexModel->$indexAttr]['type']='raw';
					$items[$docTypeIndexModel->$indexAttr]['value'] =function($data) {
						if($data->tddo16==0)
							return '';
						$tddoDoc = $data->tddo160;

						return CHtml::link(sprintf('%s (%s)',$tddoDoc->tddo3, $tddoDoc->tddo24->ddo2),'/doc/'.$tddoDoc->tddo1);
					};
					$items[$docTypeIndexModel->$indexAttr]['filter'] = false;
					break;
				case 'tddo10':
					$items[$docTypeIndexModel->$indexAttr]['value'] ='$data->getTddo10Type()';
					$items[$docTypeIndexModel->$indexAttr]['filter'] = Tddo::model()->getTddo10Types();
					break;
				case 'tddo11':
					$items[$docTypeIndexModel->$indexAttr]['value'] ='$data->getTddo11Type()';
					$items[$docTypeIndexModel->$indexAttr]['filter'] = Tddo::model()->getTddo11Types();
					break;
			}
		}

		ksort($items);

		return $items;
	}

    /**
     * @param $controller Controller
     * @param $model Tddo
     * @return array
     */
	public function generateAttributesView($controller,$model){
		$docTypeIndexModel = Ddoi::model()->findByAttributes(array('ddoi2'=>$this->ddo1));
		if(empty($docTypeIndexModel))
			$docTypeIndexModel = new Ddoi();
		$docTypeNameModel = Ddon::model()->findByAttributes(array('ddon2'=>$this->ddo1));
		if(empty($docTypeNameModel))
			$docTypeNameModel = new Ddon();

		for($i=3;$i<=15; $i++){

			$nameAttr = 'ddon'.$i;
			$indexAttr = 'ddoi'.$i;
			$ddoAttr = 'ddo'.$i;

			$tddoFiled = Ddo::model()->getTddoFiledByDdo($ddoAttr);

			if($tddoFiled=='')
				continue;

			if($tddoFiled!='ddo9')
				$items[$docTypeIndexModel->$indexAttr] = array(
					'name' => $tddoFiled,
					'label'=>$docTypeNameModel->$nameAttr,
					'value' => $model->$tddoFiled,
					'visible'=>$this->$ddoAttr==1
				);
			else
				$items[$docTypeIndexModel->$indexAttr] = array(
						'name' => $tddoFiled,
						'label'=> $docTypeNameModel->$nameAttr,
						'value' => '',
						'visible'=> $this->$ddoAttr==1
				);

			switch ($tddoFiled){
				case 'ddo9':
					$items[$docTypeIndexModel->$indexAttr]['type']='raw';
					$items[$docTypeIndexModel->$indexAttr]['value'] =function($data) use ($controller) {
						/**
						 *
						 * @var $data Tddo
						 * @var $controller DocController
						 */
						$html = '';
						$html.=$controller->widget('CTreeView', array('data' =>$data->getPerformans(),'persist'=>'cookie'), TRUE);

						return $html;
					};
					break;
				case 'tddo9':
					$items[$docTypeIndexModel->$indexAttr]['value'] = function($data) {
						if(empty($data['tddo9']))
							return '';
						return date_format(date_create_from_format('Y-m-d H:i:s', $data['tddo9']), 'Y-m-d');
					};
					break;
				case 'tddo4':
					$items[$docTypeIndexModel->$indexAttr]['value'] = function($data) {
						if(empty($data['tddo4']))
							return '';
						return date_format(date_create_from_format('Y-m-d H:i:s', $data['tddo4']), 'Y-m-d');
					};
					break;
				case 'tddo18':
					$items[$docTypeIndexModel->$indexAttr]['value'] = function($data) {
						if($data->tddo18==0)
							return '';

						$org = $data->tddo180;

						return sprintf('%s / %s / %s',$org->org2,$org->org3,$org->org4);
					};
					break;
				case 'tddo20':
					$items[$docTypeIndexModel->$indexAttr]['value'] = function($data) {
						if($data->tddo20==0)
							return '';
						$tdo = $data->tddo200;

						return $tdo->tdo2;
					};
					break;
				case 'tddo17':
					$items[$docTypeIndexModel->$indexAttr]['value'] =$model->getTddo17Type();
					break;
				case 'tddo16':
					$items[$docTypeIndexModel->$indexAttr]['type']='raw';
					$items[$docTypeIndexModel->$indexAttr]['value'] =function($data) {
						if($data->tddo16==0)
							return '';
						$tddoDoc = $data->tddo160;

						return CHtml::link(sprintf('%s (%s)',$tddoDoc->tddo3, $tddoDoc->tddo24->ddo2),'/doc/'.$tddoDoc->tddo1);
					};
					break;
				case 'tddo10':
					$items[$docTypeIndexModel->$indexAttr]['value'] =$model->getTddo10Type();
					break;
				case 'tddo11':
					$items[$docTypeIndexModel->$indexAttr]['type']='raw';
					$items[$docTypeIndexModel->$indexAttr]['value'] =function($data) {
						/**
						 * @var $data Tddo
						 */
						$html = '';

						$html.= '<div>';
						$html.= $data->getTddo11Type();
						$html.= '</div>';

						if($data->tddo11!=1)
							return $html;

						$dkids = $data->getDkid();
						if(!empty($dkids)) {
							$html.= '
								<table class="table table-condensed table-hover">
									<thead>
										<tr>
											<th>'.tt('Необходимая дата').'</th>
											<th>'.tt('Фактическая дата').'</th>
											<th>'.tt('Исполнитель').'</th>
										</tr>
									</thead>
									<tbody>
							';
							foreach ($dkids as $dkid) {
                                /**
                                 * @var $dkid Dkid
                                 */
								if(!empty($dkid->dkid3))
									$html.= '<tr class="success">';
								else
								{
									if(strtotime($dkid->dkid2)<=strtotime('NOW'))
										$html.= '<tr class="error">';
									else
										$html.= '<tr class="warning">';
								}
								$html.= '<td>'.Dkid::model()->getDateString(date_create_from_format('Y-m-d H:i:s', $dkid->dkid2)).'</td>';
								if(!empty($dkid->dkid3))
									$html.= '<td>'.Dkid::model()->getDateString(date_create_from_format('Y-m-d H:i:s', $data['tddo4'])).'</td>';
								else
									$html.= '<td/>';

                                $html.='<td/>';

								$html.= '</tr>';
                                foreach ($dkid->performens as $performen){
                                    /**
                                     * @var $performen Ido
                                     */
                                    $html.='<tr><td/><td/><td>'.$performen->getFullText().'</td></tr>';
                                }

                            }

							$html.='
									</tbody>
								</table>
							';
						}


						return $html;
					};
					break;
			}
		}

		ksort($items);

		return $items;
	}
}
