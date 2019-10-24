<?php

/**
 * This is the model class for table "um".
 *
 * The followings are the available columns in table 'um':
 * @property integer $um1
 * @property integer $um2
 * @property string $um3
 * @property integer $um4
 * @property string $um5
 * @property integer $um7
 * @property integer $um8
 * @property integer $um9
 * @property integer $um10
 *
 * The followings are the available model relations:
 * @property Users $um20
 * @property Um $um100
 * @property Um[] $ums
 */
class Um extends CActiveRecord
{
    const TIME_PERIOD_MONTH = 'month';
    const TIME_PERIOD_YEAR = 'year';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'um';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('um1, um2, um4, um7, um8, um9, um10', 'numerical', 'integerOnly'=>true),
			array('um3', 'length', 'max'=>20),
            array('um5', 'length', 'max'=>500),
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
			'um20' => array(self::BELONGS_TO, 'Users', 'um2'),
			'um100' => array(self::BELONGS_TO, 'Um', 'um10'),
			'ums' => array(self::HAS_MANY, 'Um', 'um10'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'um1' => '#',
			'um2' => tt('Отправитель'),
			'um3' => tt('Дата'),
			'um4' => tt('Уведомление'),
			'um5' => tt('Текст'),
			'um7' => tt('Получатель (пользователь)'),
			'um8' => tt('Получатель (группа)'),
			'um9' => tt('Получатель (поток)'),
			'um10' => tt('Начальное сообщение'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Um the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * ИМя и фото пользователя
     * @return array
     */
    public function getUserToFotoAndName(){
        $url = '';
        $name = '';
	    if($this->um7 > 0){
	        $user = Users::model()->findByPk($this->um7);
	        if(empty($user)){
                $url= '#';
                $name = '-';
            }else{
                $url = Yii::app()->createUrl('/site/userPhoto', array(
                    '_id' => $user->u6,
                    'type' => $user->u5 == 1 ? 0 : 1
                ));
                $name = $user->getNameWithDept();
            }
        }

        if($this->um8 > 0){
            $gr = Gr::model()->findByPk($this->um8);
            if(empty($gr)){
                $url= '';
                $name = '-';
            }else{
                $url = '';
                $name = $gr->getNameByDate($gr->gr1, date('d.m.Y'));
            }
        }

        if($this->um9 > 0){
            $sg = Gr::model()->getInfoBySg($this->um9);
            if(empty($sg)){
                $url= '';
                $name = '-';
            }else{
                $url = '';
                $name = $sg['pnsp2'] . '('.$sg['f2'].') '. $sg['sg3']. ' '. SH::convertEducationType($sg['sg4']);
            }
        }

        return array($url, $name);
    }


}
