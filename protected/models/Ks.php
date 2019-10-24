<?php

/**
 * This is the model class for table "ks".
 *
 * The followings are the available columns in table 'ks':
 * @property integer $ks1
 * @property string $ks2
 * @property string $ks3
 * @property string $ks4
 * @property string $ks5
 * @property string $ks6
 * @property integer $ks7
 * @property string $ks8
 */
class Ks extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ks1, ks7', 'numerical', 'integerOnly'=>true),
			array('ks2, ks8', 'length', 'max'=>400),
			array('ks3', 'length', 'max'=>120),
			array('ks4', 'length', 'max'=>4),
			array('ks5, ks6', 'length', 'max'=>8),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ks the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getFieldByLanguage($short){
		switch(Yii::app()->language){
			case 'uk':
			case 'ru':
				if(!$short)
					return 'ks2';
				else
					return 'ks3';
				break;
			case 'en': if(!$short)
					return 'ks10';
				else
					return 'ks11';
				break;
		}

		return 'ks2';
	}

	public static function getListDataForKsFilter(){
		$res = array();
		/*--------------госаудит-----------скрывать все филиалы кроме ихнего*/
		$univeristyCod = Yii::app()->core->universityCode;
		if($univeristyCod==U_URFAK){
			if($_SERVER['SERVER_NAME']=='tt.audit.msu.ru')
			{
				$filial = Ks::model()->findByPk(1);
				if(!empty($filial)){
					$name =  $filial[Ks::getFieldByLanguage(false)];
					//$filials[$key]['name'] =
					$res[0]['name'] = (isset($name)&&!empty($name)&&$name!="")?$name:$filial['ks2'];
					$res[0]['ks1'] = $filial->ks1;
					return CHtml::listData($res, 'ks1', 'name');
				}
			}
		}
		/*--------------госаудит-----------скрывать все филиалы кроме ихнего*/
		//$filials = CHtml::listData(Ks::model()->findAllByAttributes(array('ks12'=>null,'ks13'=>0)), 'ks1', 'ks2');
		$filials = Ks::model()->findAllByAttributes(array('ks12'=>null,'ks13'=>0));

		foreach($filials as $key => $filial){

		    if($univeristyCod==U_NULAU){
		        if($filial->ks1==7)
		            continue;
            }

			$name =  $filial[Ks::getFieldByLanguage(false)];
			//$filials[$key]['name'] =
			$res[$key]['name'] = (isset($name)&&!empty($name)&&$name!="")?$name:$filial['ks2'];
			$res[$key]['ks1'] = $filial->ks1;
		}
		return CHtml::listData($res, 'ks1', 'name');
	}
}
