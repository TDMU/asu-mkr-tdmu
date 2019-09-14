<?php

/**
 * This is the model class for table "p".
 *
 * The followings are the available columns in table 'p':
 * @property integer $p1
 * @property string $p3
 * @property string $p4
 * @property string $p5
 * @property string $p6
 * @property string $p7
 * @property string $p8
 * @property string $p9
 * @property string $p10
 * @property string $p11
 * @property string $p12
 * @property string $p13
 * @property string $p14
 * @property string $p15
 * @property string $p16
 * @property string $p17
 * @property string $p18
 * @property string $p19
 * @property string $p20
 * @property string $p21
 * @property string $p22
 * @property string $p23
 * @property string $p24
 * @property string $p27
 * @property string $p33
 * @property string $p34
 * @property string $p35
 * @property string $p36
 * @property string $p37
 * @property string $p38
 * @property string $p39
 * @property string $p40
 * @property string $p41
 * @property string $p42
 * @property string $p43
 * @property string $p44
 * @property string $p45
 * @property string $p46
 * @property string $p47
 * @property integer $p48
 * @property string $p49
 * @property string $p50
 * @property string $p51
 * @property string $p52
 * @property string $p53
 * @property string $p54
 * @property integer $p55
 * @property string $p56
 * @property string $p57
 * @property string $p58
 * @property string $p59
 * @property string $p60
 * @property integer $p61
 * @property string $p62
 * @property string $p63
 * @property string $p64
 * @property string $p65
 * @property string $p66
 * @property string $p67
 * @property string $p68
 * @property string $p74
 * @property integer $p75
 * @property string $p76
 * @property string $p77
 * @property string $p78
 * @property string $p79
 * @property string $p80
 * @property string $p81
 * @property integer $p82
 * @property integer $p83
 * @property integer $p84
 * @property integer $p85
 * @property integer $p86
 * @property integer $p87
 * @property string $p88
 * @property string $p89
 * @property integer $p90
 * @property integer $p91
 * @property string $p92
 * @property string $p93
 * @property string $p94
 * @property string $p95
 * @property string $p96
 * @property string $p97
 * @property string $p98
 * @property string $p99
 * @property integer $p100
 * @property string $p102
 * @property string $p103
 * @property string $p104
 * @property string $p105
 * @property string $p106
 * @property string $p107
 * @property string $p108
 * @property string $p109
 * @property string $p110
 *
 * @property K $chair
 *
 * @property Grants $grants
 *
 * From ShortNameBehaviour:
 * @method string getShortName() Returns default truncated name.
 *
 */
class P extends CActiveRecord implements IPerson
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'p';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('p1', 'required'),
			array('p1, p48, p55, p61, p75, p82, p83, p84, p85, p86, p87, p90, p91, p100', 'numerical', 'integerOnly'=>true),
			array('p3, p27, p54, p76, p102, p105, p108', 'length', 'max'=>140),
			array('p4, p5, p6, p7, p13, p14, p77, p78, p103, p104, p106, p107, p109, p110', 'length', 'max'=>80),
			array('p8, p19, p20, p58', 'length', 'max'=>4),
			array('p49, p62, p63', 'length', 'max'=>8),
            array('p9', 'length', 'max'=>20),
			array('p10, p11, p17, p21, p22, p53', 'length', 'max'=>400),
			array('p12, p16, p57, p65', 'length', 'max'=>60),
			array('p15, p67, p68', 'length', 'max'=>20),
			array('p18, p56, p66', 'length', 'max'=>40),
			array('p23, p50', 'length', 'max'=>120),
			array('p24', 'length', 'max'=>1200),
			array('p33, p34, p35, p36, p37, p38, p40, p60, p64, p79', 'length', 'max'=>100),
			array('p39, p89', 'length', 'max'=>300),
			array('p41, p42, p43, p44, p45, p46, p47', 'length', 'max'=>4000),
			array('p51, p52, p88, p92, p93, p94, p95, p96, p97, p98, p99', 'length', 'max'=>600),
			array('p59', 'length', 'max'=>224),
			array('p74', 'length', 'max'=>24),
			array('p80', 'length', 'max'=>180),
			array('p81', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('p1, p3, p4, p5, p6, p7, p8, p9, p10, p11, p12, p13, p14, p15, p16, p17, p18, p19, p20, p21, p22, p23, p24, p27, p33, p34, p35, p36, p37, p38, p39, p40, p41, p42, p43, p44, p45, p46, p47, p48, p49, p50, p51, p52, p53, p54, p55, p56, p57, p58, p59, p60, p61, p62, p63, p64, p65, p66, p67, p68, p74, p75, p76, p77, p78, p79, p80, p81, p82, p83, p84, p85, p86, p87, p88, p89, p90, p91, p92, p93, p94, p95, p96, p97, p98, p99, p100, p102, p103, p104, p105, p106, p107, p108, p109, p110', 'safe', 'on'=>'search'),
		);
	}

    public function behaviors()
    {
        return array(
            'ShortNameBehaviour' => array(
                'class'      => 'ShortNameBehaviour',
                'surname'    => 'p3',
                'name'       => 'p4',
                'patronymic' => 'p5',
            )
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
            'account' => array(self::HAS_ONE, 'Users', 'u6', 'on' => 'u5=1'),
            'accountDoctor' => array(self::HAS_ONE, 'Users', 'u6', 'on' => 'u5=3'),
            'pd' => array(self::HAS_MANY, 'Pd', 'pd2'),
            'grants' => array(self::HAS_ONE, 'Grants', 'grants2'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'p1' => 'P1',
			'p3' => tt('Фамилия'),
			'p4' => tt('Имя'),
			'p5' => tt('Отчество'),
			'p6' => 'P6',
			'p7' => 'P7',
			'p8' => 'P8',
			'p9' => tt('Дата рождения'),
			'p10' => 'P10',
			'p11' => 'P11',
			'p12' => 'P12',
			'p13' => tt('ИНН'),
			'p14' => 'P14',
			'p15' => 'P15',
			'p16' => 'P16',
			'p17' => 'P17',
			'p18' => 'P18',
			'p19' => 'P19',
			'p20' => 'P20',
			'p21' => 'P21',
			'p22' => 'P22',
			'p23' => 'P23',
			'p24' => 'P24',
			'p27' => 'P27',
			'p33' => 'P33',
			'p34' => 'P34',
			'p35' => 'P35',
			'p36' => 'P36',
			'p37' => 'P37',
			'p38' => 'P38',
			'p39' => 'P39',
			'p40' => 'P40',
			'p41' => 'P41',
			'p42' => 'P42',
			'p43' => 'P43',
			'p44' => 'P44',
			'p45' => 'P45',
			'p46' => 'P46',
			'p47' => 'P47',
			'p48' => 'P48',
			'p49' => 'P49',
			'p50' => 'P50',
			'p51' => 'P51',
			'p52' => 'P52',
			'p53' => 'P53',
			'p54' => 'P54',
			'p55' => 'P55',
			'p56' => 'P56',
			'p57' => 'P57',
			'p58' => 'P58',
			'p59' => 'P59',
			'p60' => 'P60',
			'p61' => 'P61',
			'p62' => 'P62',
			'p63' => 'P63',
			'p64' => 'P64',
			'p65' => 'P65',
			'p66' => 'P66',
			'p67' => 'P67',
			'p68' => 'P68',
			'p74' => 'P74',
			'p75' => 'P75',
			'p76' => 'P76',
			'p77' => 'P77',
			'p78' => 'P78',
			'p79' => 'P79',
			'p80' => 'P80',
			'p81' => 'P81',
			'p82' => 'P82',
			'p83' => 'P83',
			'p84' => 'P84',
			'p85' => 'P85',
			'p86' => 'P86',
			'p87' => 'P87',
			'p88' => 'P88',
			'p89' => 'P89',
			'p90' => 'P90',
			'p91' => 'P91',
			'p92' => 'P92',
			'p93' => 'P93',
			'p94' => 'P94',
			'p95' => 'P95',
			'p96' => 'P96',
			'p97' => 'P97',
			'p98' => 'P98',
			'p99' => 'P99',
			'p100' => 'P100',
			'p102' => 'P102',
			'p103' => 'P103',
			'p104' => 'P104',
			'p105' => 'P105',
			'p106' => 'P106',
			'p107' => 'P107',
			'p108' => 'P108',
			'p109' => 'P109',
			'p110' => 'P110',
            'shortName' => tt('Ф.И.О.'),
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

		$criteria->compare('p1',$this->p1);
		$criteria->compare('p3',$this->p3,true);
		$criteria->compare('p4',$this->p4,true);
		$criteria->compare('p5',$this->p5,true);
		$criteria->compare('p6',$this->p6,true);
		$criteria->compare('p7',$this->p7,true);
		$criteria->compare('p8',$this->p8,true);
		$criteria->compare('p9',$this->p9,true);
		$criteria->compare('p10',$this->p10,true);
		$criteria->compare('p11',$this->p11,true);
		$criteria->compare('p12',$this->p12,true);
		$criteria->compare('p13',$this->p13,true);
		$criteria->compare('p14',$this->p14,true);
		$criteria->compare('p15',$this->p15,true);
		$criteria->compare('p16',$this->p16,true);
		$criteria->compare('p17',$this->p17,true);
		$criteria->compare('p18',$this->p18,true);
		$criteria->compare('p19',$this->p19,true);
		$criteria->compare('p20',$this->p20,true);
		$criteria->compare('p21',$this->p21,true);
		$criteria->compare('p22',$this->p22,true);
		$criteria->compare('p23',$this->p23,true);
		$criteria->compare('p24',$this->p24,true);
		$criteria->compare('p27',$this->p27,true);
		$criteria->compare('p33',$this->p33,true);
		$criteria->compare('p34',$this->p34,true);
		$criteria->compare('p35',$this->p35,true);
		$criteria->compare('p36',$this->p36,true);
		$criteria->compare('p37',$this->p37,true);
		$criteria->compare('p38',$this->p38,true);
		$criteria->compare('p39',$this->p39,true);
		$criteria->compare('p40',$this->p40,true);
		$criteria->compare('p41',$this->p41,true);
		$criteria->compare('p42',$this->p42,true);
		$criteria->compare('p43',$this->p43,true);
		$criteria->compare('p44',$this->p44,true);
		$criteria->compare('p45',$this->p45,true);
		$criteria->compare('p46',$this->p46,true);
		$criteria->compare('p47',$this->p47,true);
		$criteria->compare('p48',$this->p48);
		$criteria->compare('p49',$this->p49,true);
		$criteria->compare('p50',$this->p50,true);
		$criteria->compare('p51',$this->p51,true);
		$criteria->compare('p52',$this->p52,true);
		$criteria->compare('p53',$this->p53,true);
		$criteria->compare('p54',$this->p54,true);
		$criteria->compare('p55',$this->p55);
		$criteria->compare('p56',$this->p56,true);
		$criteria->compare('p57',$this->p57,true);
		$criteria->compare('p58',$this->p58,true);
		$criteria->compare('p59',$this->p59,true);
		$criteria->compare('p60',$this->p60,true);
		$criteria->compare('p61',$this->p61);
		$criteria->compare('p62',$this->p62,true);
		$criteria->compare('p63',$this->p63,true);
		$criteria->compare('p64',$this->p64,true);
		$criteria->compare('p65',$this->p65,true);
		$criteria->compare('p66',$this->p66,true);
		$criteria->compare('p67',$this->p67,true);
		$criteria->compare('p68',$this->p68,true);
		$criteria->compare('p74',$this->p74,true);
		$criteria->compare('p75',$this->p75);
		$criteria->compare('p76',$this->p76,true);
		$criteria->compare('p77',$this->p77,true);
		$criteria->compare('p78',$this->p78,true);
		$criteria->compare('p79',$this->p79,true);
		$criteria->compare('p80',$this->p80,true);
		$criteria->compare('p81',$this->p81,true);
		$criteria->compare('p82',$this->p82);
		$criteria->compare('p83',$this->p83);
		$criteria->compare('p84',$this->p84);
		$criteria->compare('p85',$this->p85);
		$criteria->compare('p86',$this->p86);
		$criteria->compare('p87',$this->p87);
		$criteria->compare('p88',$this->p88,true);
		$criteria->compare('p89',$this->p89,true);
		$criteria->compare('p90',$this->p90);
		$criteria->compare('p91',$this->p91);
		$criteria->compare('p92',$this->p92,true);
		$criteria->compare('p93',$this->p93,true);
		$criteria->compare('p94',$this->p94,true);
		$criteria->compare('p95',$this->p95,true);
		$criteria->compare('p96',$this->p96,true);
		$criteria->compare('p97',$this->p97,true);
		$criteria->compare('p98',$this->p98,true);
		$criteria->compare('p99',$this->p99,true);
		$criteria->compare('p100',$this->p100);
		$criteria->compare('p102',$this->p102,true);
		$criteria->compare('p103',$this->p103,true);
		$criteria->compare('p104',$this->p104,true);
		$criteria->compare('p105',$this->p105,true);
		$criteria->compare('p106',$this->p106,true);
		$criteria->compare('p107',$this->p107,true);
		$criteria->compare('p108',$this->p108,true);
		$criteria->compare('p109',$this->p109,true);
		$criteria->compare('p110',$this->p110,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return P the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getTeacherByUo($uo1)
	{
		$sql = <<<SQL
        SELECT pd2,p3,p4,p5 FROM sem
        	INNER JOIN us ON (sem.sem1 = us.us12)
		    INNER JOIN nr ON (us.us1 = nr.nr2)
		    INNER JOIN pd ON (nr.nr6 = pd.pd1) /*or (nr.nr7 = pd.pd1) or (nr.nr8 = pd.pd1) or (nr.nr9 = pd.pd1)*/
            INNER JOIN p on (pd.pd2 = p.p1)
      	WHERE us2=:UO1 and sem3=:YEAR AND sem5=:SEM
SQL;
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':UO1', $uo1);
		$command->bindValue(':YEAR', Yii::app()->session['year']);
		$command->bindValue(':SEM', Yii::app()->session['sem']);
		$res = $command->queryRow();
		return $res;
	}

    public function getZavKavByTeacher($teacher)
    {
        if (empty($teacher))
            return array(null,null);
        $sql = <<<SQL
        SELECT k1,k2,k3 FROM p
			INNER JOIN PD ON (P1=PD2)
			INNER JOIN K ON (PD4=K1)
		WHERE pd2>0 and pd3='0' and pd28 in (0,2,5,9) and pd13 is null and p1=:p1
		GROUP BY k1,k2,k3
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':p1', $teacher);
        $k = $command->queryRow();
        if(empty($k))
        {
            return array(null,null);
        }

        $sql = <<<SQL
        SELECT pd2,p3,p4,p5 FROM pd
			INNER JOIN B ON (PD5=B1)
			INNER JOIN P ON (PD2=P1)
		WHERE pd2>0 and pd3='0' and pd28 in (0,2,5,9) and pd13 is null and b11=1 and pd4=:k1
		GROUP BY pd2,p3,p4,p5
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':k1', $k['k1']);
        $res = $command->queryRow();
        return array($res,$k);
    }

	public function getSearchTeachers($name)
    {
        if (empty($name))
            return array();
		$sql = <<<SQL
        SELECT p1,p3,p4,p5,k1,k2,k3,ks1,ks3 FROM p
			INNER JOIN PD ON (P1=PD2)
			INNER JOIN K ON (PD4=K1)
			INNER JOIN KS ON (K10=KS1)
		WHERE pd2>0 and pd3='0' and pd28 in (0,2,5,9) and pd13 is null and p3 CONTAINING :name and k20=0
		GROUP BY p1,p3,p4,p5,k1,k2,k3,ks1,ks3
        ORDER BY p3 collate UNICODE,p4,p5,k2
SQL;
		$command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':name', $name);
        $teachers = $command->queryAll();
        return $teachers;
    }

	/**
	 * для прямой ссылки в расписании преподавателя
	 * @param $p1 int
	 * @return array
	 */
	public function getTeacherParamsByP1($p1)
	{
		if (empty($p1))
			return array();

		$sql = <<<SQL
        SELECT first 1 p1,k1,K10 as ks1, K7 as f1 FROM p
			INNER JOIN PD ON (P1=PD2)
			INNER JOIN K ON (PD4=K1)
		WHERE pd2>0 and pd3='0' and pd28 in (0,2,5,9) and pd13 is null and p1=:P1 and k20=0
SQL;
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':P1', $p1);
		$teacher = $command->queryRow();
		return $teacher;
	}
	
    public function getTeachersFor($chairId)
    {
        $criteria=new CDbCriteria;

        $criteria->select = 'p3, p4, p5,p9,p13';

        $with = array(
            'account' => array(
                'select' => 'u2, u3, u4, u7'
            )
        );
        if (! empty($chairId)) {
            $with['pd'] = array(
                'select' => false,
                'together' => true
            );
			$today = date('d.m.Y 00:00');
            $criteria->compare('pd4', $chairId);
			$criteria->addCondition("PD28 in (0,2,5,9) and PD3=0 and (PD13 IS NULL or PD13>'{$today}')");
        }

        $criteria->addCondition("p3 <> ''");
		$criteria->addCondition("p3 CONTAINING '".$this->p3."'");
		$criteria->addCondition("p4 CONTAINING '".$this->p4."'");
		$criteria->addCondition("p5 CONTAINING '".$this->p5."'");
        $criteria->addSearchCondition('p13', $this->p13);
        if(!empty($this->p9))
            $criteria->addCondition("p9 CONTAINING '".date_format(date_create_from_format('d-m-Y', trim($this->p9)), 'Y-m-d')."'");
            //$criteria->addSearchCondition('p9', date_format(date_create_from_format('d-m-Y', $this->p9), 'Y-m-d H:i:s'));

        $criteria->addSearchCondition('account.u2', Yii::app()->request->getParam('login'));
        //$criteria->addCondition('account.u5 = 1');
        $criteria->addSearchCondition('account.u3', Yii::app()->request->getParam('password'));
        $criteria->addSearchCondition('account.u4', Yii::app()->request->getParam('email'));
        $criteria->addSearchCondition('account.u7', Yii::app()->request->getParam('status'));

        $criteria->with = $with;

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
			'pagination'=>array(
                'pageSize'=> Yii::app()->user->getState('pageSize',10),
				'currentPage'=> Yii::app()->user->getState('CurrentPageP',null),
            ),
            'sort' => array(
                'defaultOrder' => 'p3 collate UNICODE',
                'attributes' => array(
                    'p3',
                    'p4',
                    'p5',
					'p9',
					'p13',
                    'account.u2',
                    'account.u3',
                    'account.u4',
                    'account.u7',
                ),
            )
        ));
    }

    /**
     * Провайдер данных для получения и фильтрации списка врачей
     * @return CActiveDataProvider
     */
    public function getDoctors()
    {
        $criteria=new CDbCriteria;

        $criteria->select = 'p3, p4, p5,p9,p13';

        $with = array(
            'accountDoctor' => array(
                'select' => 'u2, u3, u4, u7, u5'
            )
        );
        if (! empty($chairId)) {
            $with['pd'] = array(
                'select' => false,
                'together' => true
            );
            $today = date('d.m.Y 00:00');
            $criteria->compare('pd4', $chairId);
            $criteria->addCondition("PD28 in (0,2,5,9) and PD3=0 and (PD13 IS NULL or PD13>'{$today}')");
        }

        $criteria->addCondition("p3 <> ''");
        $criteria->addCondition("p3 CONTAINING '".$this->p3."'");
        $criteria->addCondition("p4 CONTAINING '".$this->p4."'");
        $criteria->addCondition("p5 CONTAINING '".$this->p5."'");
        $criteria->addSearchCondition('p13', $this->p13);
        if(!empty($this->p9))
            $criteria->addCondition("p9 CONTAINING '".date_format(date_create_from_format('d-m-Y', trim($this->p9)), 'Y-m-d')."'");
        //$criteria->addSearchCondition('p9', date_format(date_create_from_format('d-m-Y', $this->p9), 'Y-m-d H:i:s'));

        $criteria->addSearchCondition('accountDoctor.u2', Yii::app()->request->getParam('login'));
        //$criteria->addCondition('accountDoctor.u5 = 3');
        $criteria->addSearchCondition('accountDoctor.u3', Yii::app()->request->getParam('password'));
        $criteria->addSearchCondition('accountDoctor.u4', Yii::app()->request->getParam('email'));
        $criteria->addSearchCondition('accountDoctor.u7', Yii::app()->request->getParam('status'));

        $criteria->with = $with;

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=> Yii::app()->user->getState('pageSize',10),
                'currentPage'=> Yii::app()->user->getState('CurrentPageD',null),
            ),
            'sort' => array(
                'defaultOrder' => 'p3 collate UNICODE',
                'attributes' => array(
                    'p3',
                    'p4',
                    'p5',
                    'p9',
                    'p13',
                    'accountDoctor.u2',
                    'accountDoctor.u3',
                    'accountDoctor.u4',
                    'accountDoctor.u7',
                ),
            )
        ));
    }
	
	public function getP9String()
	{
		//return date("d-m-Y", strtotime($this->p9));
		return date_format(date_create_from_format('Y-m-d H:i:s', $this->p9), 'd-m-Y');
	}
	
    public function getTeachersForTimeTable($chairId, $keyFieldName = 'p1')
    {
        if (empty($chairId))
            return array();

        $today = date('d.m.Y 00:00');
        $sql = <<<SQL
            SELECT P1,P3,P4,P5,pd7,DOL2,PD1, p76, p77, p78, dol13
            FROM P
                INNER JOIN PD ON (P1=PD2)
                INNER JOIN DOL ON (PD45 = DOL1)
                INNER JOIN K on (PD4=K1)
            WHERE PD4 = {$chairId} and PD28 in (0,2,5,9) and PD3=0 and pd11<='{$today}'and (PD13 IS NULL or PD13>'{$today}') and k20=0
            group by P1,P3,P4,P5,pd7,DOL2,PD1, p76, p77, p78, dol13
            ORDER BY P3 collate UNICODE, pd7 desc
SQL;

        $teachers = Yii::app()->db->createCommand($sql)->queryAll();
        $res = array();
        foreach ($teachers as $tch) {
            if(Yii::app()->language=='en'&&!empty($tch['p76']))
                $res[ $tch[$keyFieldName] ] = sprintf('%s %s %s, %s',$tch['p76'], $tch['p77'], $tch['p78'], $tch['dol13']);
            else
                $res[ $tch[$keyFieldName] ] = sprintf('%s %s %s, %s',$tch['p3'], $tch['p4'], $tch['p5'], $tch['dol2']);
        }
        return $res;
    }
    
    public function getTeachersForListChair($chairId)
    {
        if (empty($chairId))
            return array();

        $today = date('d.m.Y 00:00');
        $sql = <<<SQL
            SELECT P1,P3,P4,P5,pd6,dol2,p81
            FROM P
                INNER JOIN PD ON (P1=PD2)
                INNER JOIN DOL ON (PD45 = DOL1)
                INNER JOIN K on (PD4=K1)
            WHERE PD4 = {$chairId} and PD28 in (0,2,5,9) and PD3=0 and (PD13 IS NULL or PD13>'{$today}') and k20=0
            group by P1,P3,P4,P5,pd6,dol2,p81
            ORDER BY P3 collate UNICODE
SQL;

        $teachers = Yii::app()->db->createCommand($sql)->queryAll();

        $res = array();

        foreach($teachers as $teacher)
        {
            if(isset($res[$teacher['p1']]))
            {
                $res[$teacher['p1']]['pd6']+=$teacher['pd6'];
                $res[$teacher['p1']]['dol2'].=','.$teacher['dol2'];
            }else
            {
                $res[$teacher['p1']]['p3']=$teacher['p3'];
                $res[$teacher['p1']]['p4']=$teacher['p4'];
                $res[$teacher['p1']]['p5']=$teacher['p5'];
                $res[$teacher['p1']]['pd6']=$teacher['pd6'];
                $res[$teacher['p1']]['dol2']=$teacher['dol2'];
            }
        }

        return $res;
    }

    /**
     * @param $chairId
     * @return array
     * @throws CException
     */
    public function getTeachersForContactTeachers($chairId)
    {
        if (empty($chairId))
            return array();

        $today = date('d.m.Y 00:00');
        $sql = <<<SQL
            SELECT P1,P3,P4,P5,p81
            FROM P
                INNER JOIN PD ON (P1=PD2)
                INNER JOIN K on (PD4=K1)
            WHERE PD4 = {$chairId} and PD28 in (0,2,5,9) and PD3=0 and (PD13 IS NULL or PD13>'{$today}') and k20=0
            group by P1,P3,P4,P5,p81
            ORDER BY P3 collate UNICODE
SQL;

        return Yii::app()->db->createCommand($sql)->queryAll();
    }
    
     public function getTeachersForTimeTableChair($chairId)
    {
        if (empty($chairId))
            return array();

        $today = date('d.m.Y 00:00');
        $sql = <<<SQL
            SELECT P1,P3,P4,P5
            FROM P
                INNER JOIN PD ON (P1=PD2)
                INNER JOIN K on (PD4=K1)
            WHERE PD4 = {$chairId} and PD28 in (0,2,5,9) and PD3=0 and (PD13 IS NULL or PD13>'{$today}') and k20=0
            group by P1,P3,P4,P5
            ORDER BY P3 collate UNICODE
SQL;

        $teachers = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($teachers as $key => $teacher) {
            $name = SH::getShortName($teacher['p3'], $teacher['p4'], $teacher['p5']);
            $teachers[$key]['name'] = $name;
        }
        return $teachers;
    }

    public static function getTimeTable($p1, $date1, $date2)
    {
        if (empty($p1))
            return array();

        $sql = <<<SQL
        SELECT * FROM RASP(:LANG, 0, 0, 0, :P1, 0, :DATE_1, :DATE_2, 1)
        ORDER BY r2,r3,rz2, d3
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':P1', $p1);
        $command->bindValue(':DATE_1', $date1);
        $command->bindValue(':DATE_2', $date2);
        $command->bindValue(':LANG', TimeTableForm::getLangCode());
        $timeTable = $command->queryAll();

        if (empty($timeTable))
            return array();

        return $timeTable;
    }

    public function getTeacherNameBy($p1, $short = true)
    {
        if (empty($p1))
            return '';

        $model = P::model()->findByPk($p1);

        if ($short)
            $name = $model->getShortName();
        else
            $name = implode(' ', array($model->p3, $model->p4, $model->p5));

        return $name;
    }

    public function getTeacherNameByPd1($pd1)
    {
        if (empty($pd1))
            return '';

        $pd = Pd::model()->findByAttributes(array('pd1' => $pd1));
        if (empty($pd))
            return '';

        return $this->getTeacherNameBy($pd->pd2);
    }

    public function getTeacherNameForPhones($b1, $k1)
    {
        $sql = <<<SQL
            SELECT p3,p4,p5
            FROM PD
               INNER JOIN P ON (PD.PD2 = P.P1)
            WHERE pd4=:K1 and pd5=:B1 and pd28 in (0,2,5) and (pd13 is null or pd13>:DATE)
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':K1', $k1);
        $command->bindValue(':B1', $b1);
        $command->bindValue(':DATE', date('d.m.Y'));
        $teachers = $command->queryAll();

        $names = array();
        foreach ($teachers as $teacher) {
            $names[] = implode(' ', array($teacher['p3'], $teacher['p4'], $teacher['p5']));
        }

        $name = implode('<br/>', $names);

        return $name;
    }


    public function findUsersByNameTeacher($query)
    {
        $sql = <<<SQL
            select u1,P1,P3,P4,P5,DOL2,K3,K1,K2, pd1,pd7
			FROM P
			    INNER JOIN users on (u6 = p1 and u5 =1)
				INNER JOIN PD ON(P1=PD2)
				INNER JOIN DOL ON (PD45 = DOL1)
				INNER JOIN K ON (PD4 = K1)
				where PD2>0  and (PD28 in (0,2,5,9)) and PD11<=:DATE1
				and (PD13 is null or PD13>:DATE2)
				and k20=0
				and
				(
					P3 CONTAINING :QUERY1
					or P76 CONTAINING :QUERY2
					or P102 CONTAINING :QUERY3
					or P105 CONTAINING :QUERY4
					or P108 CONTAINING :QUERY5
				)
				and p1 not in
					(
						Select ZPD3
							from ZPD
						where ZPD2 = 2
					)
			order by P3 collate UNICODE,P4,P5,PD7
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':DATE1', date('d.m.Y 00:00', strtotime('+20 days')));
        $command->bindValue(':DATE2', date('d.m.Y 00:00'));
        $command->bindValue(':QUERY1', $query);
        $command->bindValue(':QUERY2', $query);
        $command->bindValue(':QUERY3', $query);
        $command->bindValue(':QUERY4', $query);
        $command->bindValue(':QUERY5', $query);
        $teachers = $command->queryAll();

        foreach ($teachers as $key => $teacher) {

            $teachers[$key]['id'] = $teacher['pd1'];

            $name = '';
            if (! empty($teacher['dol2']))
                $name .= $teacher['dol2'].' ';
            $name .= SH::getShortName($teacher['p3'], $teacher['p4'], $teacher['p5']);
            if (! empty($teacher['k2']))
                $name .= '('.$teacher['k2'].')';

            $teachers[$key]['name'] = $name;
        }

        return $teachers;
    }

    public function findTeacherByName($query)
    {
        $sql = <<<SQL
            select P1,P3,P4,P5,DOL2,K3,K1,K2, P76, P77, P78, P102,
			P103, P104, P105, P106, P107, K15, K16, K17, K18, P108, P109, P110, K10,
			PD1,PD7
			FROM P
				INNER JOIN PD ON(P1=PD2)
				INNER JOIN DOL ON (PD45 = DOL1)
				INNER JOIN K ON (PD4 = K1)
				where PD2>0  and (PD28 in (0,2,5,9)) and PD11<=:DATE1
				and (PD13 is null or PD13>:DATE2)
				and k20=0
				and
				(
					P3 CONTAINING :QUERY1
					or P76 CONTAINING :QUERY2
					or P102 CONTAINING :QUERY3
					or P105 CONTAINING :QUERY4
					or P108 CONTAINING :QUERY5
				)
				and p1 not in
					(
						Select ZPD3
							from ZPD
						where ZPD2 = 2
					)
			order by P3 collate UNICODE,P4,P5,PD7
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':DATE1', date('d.m.Y 00:00', strtotime('+20 days')));
        $command->bindValue(':DATE2', date('d.m.Y 00:00'));
        $command->bindValue(':QUERY1', $query);
        $command->bindValue(':QUERY2', $query);
        $command->bindValue(':QUERY3', $query);
        $command->bindValue(':QUERY4', $query);
        $command->bindValue(':QUERY5', $query);
        $teachers = $command->queryAll();

        foreach ($teachers as $key => $teacher) {

            $teachers[$key]['id'] = $teacher['pd1'];

            $name = '';
            if (! empty($teacher['dol2']))
                $name .= $teacher['dol2'].' ';
            $name .= SH::getShortName($teacher['p3'], $teacher['p4'], $teacher['p5']);
            if (! empty($teacher['k2']))
                $name .= '('.$teacher['k2'].')';

            $teachers[$key]['name'] = $name;
        }

        return $teachers;
    }

    public function getTeachersWorkLoad($chairId, $keyFieldName = 'pd1')
    {
        if (empty($chairId))
            return array();

        $today = date('d.m.Y 00:00');
        $sql = <<<SQL
            SELECT P1,P3,P4,P5,DOL2,PD1,PD7
            FROM P
                INNER JOIN PD ON (P1=PD2)
                INNER JOIN DOL ON (PD45 = DOL1)
                INNER JOIN k on (pd4=k1)
            WHERE PD4 = {$chairId} and PD28 in (0,2,5,9) and PD3=0 and (PD13 IS NULL or PD13>'{$today}') and k20=0
            ORDER BY P3 collate UNICODE
SQL;

        $teachers = Yii::app()->db->createCommand($sql)->queryAll();
		$type=array(
			0=>tt('штатный'),
			1=>tt('внешн. совмест. '),
			2=>tt('внутр. совмест.'),
			3=>tt('почасовик внутр.'),
			4=>tt('совмещение'),
			5=>tt('почасовик внешн.'),
			6=>tt('специалист'),
		);
        $res = array();
        foreach ($teachers as $tch) {
            $res[ $tch[$keyFieldName] ] = SH::getShortName($tch['p3'], $tch['p4'], $tch['p5']).' ('.$type[$tch['pd7']].')'.$tch['dol2'];
        }
        return $res;
    }

    public function getPd1ByK1($k1)
    {
        $today = date('Y-m-d 00:00');
        $sql = <<<SQL
        SELECT FIRST 1 pd1
        FROM PD
        WHERE pd2=:P1 and pd4=:K1 and pd3=0 and pd28 in (0,2,5,9) and pd11<='{$today}' and (pd13 is null or pd13>'{$today}')
        ORDER BY pd7
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':K1', $k1);
        $command->bindValue(':P1', $this->p1);
        $pd1 = $command->queryScalar();

        return $pd1;
    }

    public function getTeacherNameWithDol($p1)
    {
        $sql = <<<SQL
        select P3,P4,P5,DOL2
        from dol
        inner join pd on (dol.dol1 = pd.pd45)
        inner join p on (pd.pd2 = p.p1)
        where pd3=0 and pd28 in (0,2,5,9) and (pd13 is null or pd13>current_timestamp) and P1=:P1
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':P1', $p1);
        $tch = $command->queryRow();

        return implode(' ', array($tch['dol2'], SH::getShortName($tch['p3'], $tch['p4'], $tch['p5'])));
    }
	
	public static function getPermition($p1)
	{
		$sql = <<<SQL
		SELECT p120 FROM users JOIN p ON u6=:p1 WHERE u5=1 AND u6>0
SQL;
		$command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':p1', $p1);
        $permition = $command->queryRow();
		return $permition['p120'];
	}
        
    public function getArrayPd($p1)
    {
        $sql = <<<SQL
            SELECT pd1,pd6,pd7,pd5 FROM pd WHERE pd2=:p1 AND (pd13 is null or pd13>=current_timestamp) AND pd28 in (0,2,5,9) ORDER by pd7
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':p1', $p1);
        $teachers = $command->queryAll();
        $types=array(
        0=>tt('штатный'),
        1=>tt('внешн. совмест. '),
        2=>tt('внутр. совмест.'),
        3=>tt('почасовик внутр.'),
        4=>tt('совмещение'),
        5=>tt('почасовик внешн.'),
        6=>tt('специалист'),
    );
        foreach ($teachers as $key => $teacher) {
            $type=$types[$teacher['pd7']];
            $teachers[$key]['title'] = $type.' '.(float)$teacher['pd6'];
        }
        return $teachers;
    }

	//$type = 0 -факультет 1 - кафедра
	public function isJournalAdmin($id,$type,$stegnd9)
	{
		$result = false;
		if($type==1) {
			$sql = <<<SQL
			SELECT * FROM stegnd WHERE stegnd1=:P1 AND stegnd2=:K1 AND stegnd9=:STEGND9 AND stegnd6=1
SQL;
			$command = Yii::app()->db->createCommand($sql);
			$command->bindValue(':P1', $this->p1);
			$command->bindValue(':K1', $id);
			$command->bindValue(':STEGND9', $stegnd9);
			$res = $command->queryRow();
			if(!empty($res))
				$result = true;
		}

		return $result;
	}

    /**
     * Получить кафедру
     * @return K
     */
	public function getChair()
    {
        if (empty($this->p1))
            return null;

        $today = date('Y-m-d 00:00');

        return K::model()->findBySql(<<<SQL
            SELECT k.* FROM P 
              INNER JOIN PD ON (P1=PD2)
              INNER JOIN K ON (PD4 = k1)
            WHERE pd3=0 and pd28 in (0,2,5,9) and pd11<='{$today}' and (pd13 is null or pd13>'{$today}') AND p1=:P1 and k20=0
SQL
            , array(
                ':P1' => $this->p1
            )
        );
    }
    /*
     * Проверка заблокирован ли преподаватель
     * @return bool
     */
	public function checkBlocked()
    {
        if($this->isNewRecord)
            return true;

        $today = date('Y-m-d 00:00');

        $sql = <<<SQL
              SELECT COUNT(*) FROM PD 
              WHERE pd28 in (0,2,5,9) and pd11<='{$today}' and (pd13 is null or pd13>='{$today}') and pd2=:P1
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':P1', $this->p1);
        $count = $command->queryScalar();

        return empty($count) || $count==0;
    }
}
