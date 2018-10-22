<?php

/**
 * This is the model class for table "pm".
 *
 * The followings are the available columns in table 'pm':
 * @property integer $pm1
 * @property string $pm2
 * @property string $pm3
 * @property string $pm4
 * @property string $pm5
 * @property string $pm6
 * @property integer $pm7
 * @property integer $pm8
 * @property integer $pm9
 * @property string $pm10
 * @property integer $pm11
 * @property integer $pm12
 * @property integer $pm13
 * @property integer $pm14
 * @property integer $pm15
 */
class Pm extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pm';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pm2, pm3, pm4, pm5, pm6, pm7, pm8, pm9,pm10,pm11', 'required'),
			array('pm7, pm8, pm9,pm11,pm12,pm13,pm14,pm15', 'numerical', 'integerOnly'=>true),
			array('pm2, pm3, pm4, pm5', 'length', 'max'=>400),
			array('pm6', 'length', 'max'=>1020),
            array('pm6', 'url'),
			array('pm10,pm1', 'length', 'max'=>80),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pm1,pm2, pm3, pm4, pm5, pm6, pm7, pm8, pm9,pm10,pm11,pm12,pm13,pm14,pm15', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'pm1' => 'Pm1',
			'pm2' => tt('Заголовок укр.'),
			'pm3' => tt('Заголовок рус.'),
			'pm4' => tt('Заголовок анг.'),
			'pm5' => tt('Заголовок др.'),
			'pm6' => 'url',
			'pm7' => tt('Видимость'),
			'pm8' => tt('Открывать'),
            'pm9' => tt('Приоритет'),
            'pm10' => tt('Группа'),
            'pm11' => tt('Тип'),
            'pm12' => tt('Только для авторизированных пользователей'),
            'pm13' => tt('Запретить показывать студентам'),
            'pm14' => tt('Запретить показывать преподавателям'),
            'pm15' => tt('Запретить показывать родителям'),
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
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('pm1',$this->pm1);
		$criteria->compare('pm2',$this->pm2,true);
		$criteria->compare('pm3',$this->pm3,true);
		$criteria->compare('pm4',$this->pm4,true);
		$criteria->compare('pm5',$this->pm5,true);
        $criteria->compare('pm6',$this->pm6,true);
		$criteria->compare('pm7',$this->pm7);
		$criteria->compare('pm8',$this->pm8);
		$criteria->compare('pm9',$this->pm9);
		$criteria->compare('pm10',$this->pm10,true);
        $criteria->compare('pm11',$this->pm11);
        $criteria->compare('pm12',$this->pm12);
        $criteria->compare('pm13',$this->pm13);
        $criteria->compare('pm14',$this->pm14);
        $criteria->compare('pm15',$this->pm15);

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
	 * @return Pm the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public static function getPm10Array()
        {
           $arr=array(
               'self'=>tt('Личное'),
               'timeTable'=>tt('Расписание'),
               'workPlan'=>tt('Рабочий план'),
               'list'=>tt('Список'),
               'journal'=>tt('Эл. журнал'),
               'progress'=>tt('Успеваемость'),
               'entrance'=>tt('Абитуриент'),
               'workLoad'=>tt('Нагрузка'),
               'payment'=>tt('Оплата'),
               'other'=>tt('Другое'),
           );
            $pmg=CHtml::listData(Pmg::model()->findAll(),'pmg1','pmg2');
            return $arr+$pmg;
        }

        public function getPm10()
        {
            $arr = self::getPm10Array();
            if(isset($arr[$this->pm10]))
                return $arr[$this->pm10];
            else
                return '-';
        }
        
        public static function getPm8Array()
        {
           return array(
               0=>tt('Открывать в текущей вкладке'),
               1=>tt('Открывать в новой вкладке'),
               2=>tt('Открывать в iframe'),
           );
        }

        public function getPm8()
        {
            switch ($this->pm8) {
                case 0:
                    return tt('Открывать в текущей вкладке');
                    break;
                case 1:
                    return tt('Открывать в новой вкладке');
                    break;
                case 2:
                    return tt('Открывать в iframe');
                    break;
                default:
                    return '-';
            }
        }

        public function getParents()
        {
            $table=$this->tableName();
            if(!empty($this->pm1))
                $parents=  $this->findAllBySql('SELECT * FROM '.$table.' WHERE pm11=0 AND pm1!='.$this->pm1);
            else {
                $parents=  $this->findAllBySql('SELECT * FROM '.$table.' WHERE pm11=0');
            }
            return $parents;
        }

        public static function getPm11Array()
        {
            return array(
                0=>tt('Главный пункт'),
                1=>tt('Дочерний пункт'),
            );
        }

        public function getPm11()
        {
            switch ($this->pm11) {
                case 0:
                    return tt('Главный пункт');
                    break;
                case 1:
                    return tt('Дочерний пункт');
                    break;
                default:
                    return '-';
            }
        }
        
        public static function getPm7Array()
        {
           return array(
               0=>tt('Неопубликовано'),
               1=>tt('Опубликовано'),
           );
        }

        public function getPm7()
        {
            switch ($this->pm7) {
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

    public function getParent()
    {
        if($this->pm11!=0)
        {
            $parent_=  Pmc::model()->findByAttributes(array('pmc2'=>$this->pm1));
            $parent=null;
            if( $parent_!=null)
            {
                $parent=$this->model()->findByPk($parent_->pmc1);
            }
            return $parent;
        }else
        {
            return null;
        }
    }

    public function getParentTitle()
    {
        $parent=$this->getParent();
        if($parent==null)
        {
            return '-';
        }else
        {
            return $parent->pm2;
        }
    }

    protected function beforeSave()
    {
        if(!$this->isNewRecord&&$this->pm11==0)
            Pmc::model ()->deleteAllByAttributes (array('pmc2'=>  $this->pm1));
        return parent::beforeSave();
    }

    protected function afterDelete()
    {
        $res = Pmc::model()->findAllByAttributes(array('pmc1'=>$this->pm1));
        foreach($res as $val)
        {
            Pm::model ()->deleteAllByAttributes (array('pm1'=>  $val->pmc2));
        }
        parent::afterDelete();

    }
}
