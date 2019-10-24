<?php

/**
 * This is the model class for table "elgotr".
 *
 * The followings are the available columns in table 'elgotr':
 * @property integer $elgotr0
 * @property integer $elgotr1
 * @property double $elgotr2
 * @property string $elgotr3
 * @property integer $elgotr4
 * @property string $elgotr5
 *
 * The followings are the available model relations:
 * @property Elgzst $elgotr10
 * @property P $elgotr40
 */
class Elgotr extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'elgotr';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('elgotr0', 'required'),
			array('elgotr1, elgotr4', 'numerical', 'integerOnly'=>true),
			array('elgotr2', 'numerical'),
            //array('elgotr3', 'validateDate'),
			array('elgotr3, elgotr5', 'length', 'max'=>30),
			array('elgotr0, elgotr1, elgotr2, elgotr3, elgotr4, elgotr5', 'safe', 'on'=>'search'),
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
			'elgotr10' => array(self::BELONGS_TO, 'Elgzst', 'elgotr1'),
			'elgotr40' => array(self::BELONGS_TO, 'P', 'elgotr4'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'elgotr2' => tt('Оценка'),
            'elgotr3' => tt('Дата'),
            'elgotr4' => tt('Преподаватель'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Elgotr the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getElgotr2ArrByLk()
    {
        return array('0'=>tt('Не отработано'),'-1'=>tt('Отработано'));
    }

    public function getElgotr2ByLk()
    {
        switch ($this->elgotr2) {
            case 0:
                return tt('Не отработано');
                break;
            case -1:
                return tt('Отработано');
                break;
            default:
                return '-';
        }
    }

	public function getElgotrForExcel($model)
	{
		list($uo1,$gr1)=explode('/',$model->group);
		if(empty($uo1)||empty($gr1))
			return array();
		$sql = <<<SQL
			SELECT elgotr0,elgotr2,elgotr4,st2,st3,st4,p3,p4,p5,elgzst3,elgz3,elgp2,elgp3,elgp4 fROM elgotr
				INNER JOIN p on (elgotr.elgotr4 = p.p1)
				INNER JOIN elgzst on (elgotr.elgotr1 = elgzst.elgzst0)
				INNER JOIN st on (elgzst.elgzst1 = st.st1)
				LEFT JOIN elgp on (elgzst.elgzst0 = elgp.elgp1)
				INNER JOIN std ON (st.st1=std.std2)
				INNER JOIN gr ON (std.std3=gr.gr1)
				INNER JOIN elgz on (elgzst.elgzst2 = elgz.elgz1)
				INNER JOIN elg on (elgz.elgz2 = elg.elg1)
				INNER JOIN sem on (elg.elg3 = sem.sem1)
			WHERE sem.sem3=:YEAR AND sem.sem5=:SEM AND elg.elg2=:UO1 AND gr.gr1=:GR1 AND elg.elg4=:TYPE
			GROUP BY elgotr0,elgotr2,elgotr4,st2,st3,st4,p3,p4,p5,elgzst3,elgz3,elgp2,elgp3,elgp4
			ORDER BY elgotr.elgotr0

SQL;
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':UO1', $uo1);
		$command->bindValue(':GR1', $gr1);
		$command->bindValue(':TYPE', $model->type_lesson);
		$command->bindValue(':YEAR', Yii::app()->session['year']);
		$command->bindValue(':SEM', Yii::app()->session['sem']);
		$rows = $command->queryAll();
		return $rows;
	}
}
