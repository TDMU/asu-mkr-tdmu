<?php

/**
 * This is the model class for table "pnsp".
 *
 * The followings are the available columns in table 'pnsp':
 * @property integer $pnsp1
 * @property string $pnsp2
 * @property string $pnsp3
 * @property integer $pnsp4
 * @property integer $pnsp5
 * @property string $pnsp7
 * @property integer $pnsp8
 * @property string $pnsp9
 * @property string $pnsp10
 * @property string $pnsp11
 * @property string $pnsp12
 * @property string $pnsp13
 * @property integer $pnsp14
 * @property integer $pnsp15
 * @property integer $pnsp16
 * @property string $pnsp17
 * @property string $pnsp18
 * @property string $pnsp19
 * @property string $pnsp20
 */
class Pnsp extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pnsp';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pnsp1, pnsp4, pnsp5, pnsp8, pnsp14, pnsp15, pnsp16', 'numerical', 'integerOnly'=>true),
			array('pnsp2, pnsp20', 'length', 'max'=>600),
			array('pnsp3, pnsp10, pnsp11, pnsp12', 'length', 'max'=>100),
			array('pnsp7, pnsp9', 'length', 'max'=>8),
			array('pnsp13', 'length', 'max'=>1000),
			array('pnsp17, pnsp18, pnsp19', 'length', 'max'=>400),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pnsp1, pnsp2, pnsp3, pnsp4, pnsp5, pnsp7, pnsp8, pnsp9, pnsp10, pnsp11, pnsp12, pnsp13, pnsp14, pnsp15, pnsp16, pnsp17, pnsp18, pnsp19, pnsp20', 'safe', 'on'=>'search'),
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
			'pnsp1' => 'Pnsp1',
			'pnsp2' => 'Pnsp2',
			'pnsp3' => 'Pnsp3',
			'pnsp4' => 'Pnsp4',
			'pnsp5' => 'Pnsp5',
			'pnsp7' => 'Pnsp7',
			'pnsp8' => 'Pnsp8',
			'pnsp9' => 'Pnsp9',
			'pnsp10' => 'Pnsp10',
			'pnsp11' => 'Pnsp11',
			'pnsp12' => 'Pnsp12',
			'pnsp13' => 'Pnsp13',
			'pnsp14' => 'Pnsp14',
			'pnsp15' => 'Pnsp15',
			'pnsp16' => 'Pnsp16',
			'pnsp17' => 'Pnsp17',
			'pnsp18' => 'Pnsp18',
			'pnsp19' => 'Pnsp19',
			'pnsp20' => 'Pnsp20',
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

		$criteria->compare('pnsp1',$this->pnsp1);
		$criteria->compare('pnsp2',$this->pnsp2,true);
		$criteria->compare('pnsp3',$this->pnsp3,true);
		$criteria->compare('pnsp4',$this->pnsp4);
		$criteria->compare('pnsp5',$this->pnsp5);
		$criteria->compare('pnsp7',$this->pnsp7,true);
		$criteria->compare('pnsp8',$this->pnsp8);
		$criteria->compare('pnsp9',$this->pnsp9,true);
		$criteria->compare('pnsp10',$this->pnsp10,true);
		$criteria->compare('pnsp11',$this->pnsp11,true);
		$criteria->compare('pnsp12',$this->pnsp12,true);
		$criteria->compare('pnsp13',$this->pnsp13,true);
		$criteria->compare('pnsp14',$this->pnsp14);
		$criteria->compare('pnsp15',$this->pnsp15);
		$criteria->compare('pnsp16',$this->pnsp16);
		$criteria->compare('pnsp17',$this->pnsp17,true);
		$criteria->compare('pnsp18',$this->pnsp18,true);
		$criteria->compare('pnsp19',$this->pnsp19,true);
		$criteria->compare('pnsp20',$this->pnsp20,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Pnsp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getFieldByLanguage(){
		switch(Yii::app()->language){
			case 'uk':
				return 'pnsp2';
				break;
			case 'ru':
				return 'pnsp20';
				break;
			case 'en':
				return 'pnsp17';
				break;
		}

		return 'f3';
	}

    /**
     * статистика посещаемости по потокам (получит ьспецаильности по факультетту)
     * @param $faculty
     * @return array
     */
    public function getSpFor($faculty)
    {
        if (empty($faculty))
            return array();

        $sql=<<<SQL
            SELECT pnsp1, pnsp2, pnsp17, pnsp18, pnsp19, pnsp20, sp1, sp2
            FROM sg
            INNER JOIN sem on (sg.sg1 = sem.sem2)
            INNER JOIN sp on (sg.sg2 = sp.sp1)
            INNER JOIN pnsp on (sp.sp11 = pnsp.pnsp1)
            WHERE sp5=:FACULTY and sp7 is null and sem3=:YEAR
            GROUP BY pnsp1, pnsp2, pnsp17, pnsp18, pnsp19, pnsp20, sp1, sp2
            ORDER BY pnsp2
SQL;

        list($year, ) = SH::getCurrentYearAndSem();

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':FACULTY', $faculty);
        $command->bindValue(':YEAR', $year);
        $specialities = $command->queryAll();

        foreach ($specialities as $key => $speciality) {
            $name =  $speciality[self::getFieldByLanguage()];
            $specialities[$key]['name'] = (isset($name)&&!empty($name)&&$name!="")?$name:$speciality['pnsp2'];
            $specialities[$key]['name'].=' ('.$speciality['sp2'].')';
        }
        //return CHtml::listData($specialities, 'pnsp1', 'name');
        return $specialities;
    }

    public function getSpecialitiesFor($faculty)
    {
        if (empty($faculty))
            return array();

        $sql=<<<SQL
            SELECT pnsp1, pnsp2, pnsp17, pnsp18, pnsp19, pnsp20
            FROM sg
            INNER JOIN sem on (sg.sg1 = sem.sem2)
            INNER JOIN sp on (sg.sg2 = sp.sp1)
            INNER JOIN pnsp on (sp.sp11 = pnsp.pnsp1)
            WHERE sp5=:FACULTY and sp7 is null and sem3=:YEAR
            GROUP BY pnsp1, pnsp2, pnsp17, pnsp18, pnsp19, pnsp20
            ORDER BY pnsp2
SQL;

        list($year, ) = SH::getCurrentYearAndSem();

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':FACULTY', $faculty);
        $command->bindValue(':YEAR', $year);
        $specialities = $command->queryAll();

        foreach ($specialities as $key => $speciality) {
			$name =  $speciality[self::getFieldByLanguage()];
            $specialities[$key]['name'] = (isset($name)&&!empty($name)&&$name!="")?$name:$speciality['pnsp2'];
		}
		//return CHtml::listData($specialities, 'pnsp1', 'name');
        return $specialities;
    }

    public function getSpecialityFor($st1)
    {
        if (empty($st1))
            return array();

        $sql=<<<SQL
            select pnsp1
			from sdp
			   inner join st on (sdp.sdp1 = st.st65)
			   inner join std on (st.st1 = std.std2)
			   inner join gr on (std.std3 = gr.gr1)
			   inner join sg on (gr.gr2 = sg.sg1)
			   inner join sp on (sg.sg2 = sp.sp1)
			   inner join pnsp on (sp.sp11 = pnsp.pnsp1)
			where st1 = :ST1
			group by pnsp1
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ST1', $st1);
        $pnsp1 = $command->queryScalar();

        return $pnsp1;
    }
}
