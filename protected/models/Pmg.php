<?php

/**
 * This is the model class for table "pmg".
 *
 * The followings are the available columns in table 'pmg':
 * @property integer $pmg1
 * @property string $pmg2
 * @property string $pmg3
 * @property string $pmg4
 * @property string $pmg5
 * @property integer $pmg6
 * @property integer $pmg7
 * @property integer $pmg8
 * @property string $pmg9
 */
class Pmg extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pmg';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('pmg1', 'required'),
			array('pmg6,pmg7, pmg8', 'numerical', 'integerOnly'=>true),
			array('pmg2, pmg3, pmg4, pmg5, pmg9', 'length', 'max'=>400),
			// The following rule is used by search().
			array('pmg1, pmg2, pmg3, pmg4, pmg5, pmg6, pmg7, pmg8, pmg9', 'safe', 'on'=>'search'),
		);
	}



	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'pmg1' => 'Pmg1',
            'pmg2' => tt('Заголовок укр.'),
            'pmg3' => tt('Заголовок рус.'),
            'pmg4' => tt('Заголовок анг.'),
            'pmg5' => tt('Заголовок др.'),
            'pmg6' => tt('Видимость(авторизация)'),
			'pmg7' => tt('Видимость'),
			'pmg8' => tt('Приоритет'),
			'pmg9' => 'bootstap icon',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('pmg1',$this->pmg1);
		$criteria->compare('pmg2',$this->pmg2,true);
		$criteria->compare('pmg3',$this->pmg3,true);
		$criteria->compare('pmg4',$this->pmg4,true);
		$criteria->compare('pmg5',$this->pmg5,true);
        $criteria->compare('pmg6',$this->pmg6);
		$criteria->compare('pmg7',$this->pmg7);
		$criteria->compare('pmg8',$this->pmg8);
		$criteria->compare('pmg9',$this->pmg9,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=> Yii::app()->user->getState('pageSize',10),
            ),
        ));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Pmg the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function getPmg7Array()
    {
        return array(
            0=>tt('Неопубликовано'),
            1=>tt('Опубликовано'),
        );
    }

    public function getPmg7()
    {
        switch ($this->pmg7) {
            case 0:
                return tt('Неопубликовано');
                break;
            case 1:
                return tt('Опубликовано');
                break;
            default:
                return '-';
        }
    }

    public static function getPmg6Array()
    {
        return array(
            0=>tt('Доступен для всех пользователей'),
            1=>tt('Доступен для авторизированых пользователей'),
        );
    }

    public function getPmg6()
    {
        switch ($this->pmg6) {
            case 0:
                return tt('Доступен для всех пользователей');
                break;
            case 1:
                return tt('Доступен для авторизированых пользователей');
                break;
            default:
                return '-';
        }
    }
}
