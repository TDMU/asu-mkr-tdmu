<?php

/**
 * This is the model class for table "ustem".
 *
 * The followings are the available columns in table 'ustem':
 * @property integer $ustem1
 * @property integer $ustem2
 * @property integer $ustem3
 * @property integer $ustem4
 * @property string $ustem5
 * @property integer $ustem6
 * @property integer $ustem7
 * @property string $ustem8
 * @property integer $ustem9
 * @property integer $ustem11
 */
class Ustem extends CActiveRecord
{
    public $nr18;
    const USTEM5_LENGHT= 700;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ustem';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ustem1', 'required'),
            array('ustem7', 'numerical'),
			array('ustem2, ustem3, ustem4, ustem6,ustem11', 'numerical', 'integerOnly'=>true),
			array('ustem5', 'length', 'max'=>self::USTEM5_LENGHT),
            array('nr18', 'safe'),
            array('ustem4', 'unsafe','on'=>'update'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'ustem3' => tt('№ темы'),
            'ustem4' => tt('№ занятия'),
            'ustem5' => tt('Тема'),
            'ustem6' => tt('Тип'),
            'ustem7' => tt('Длительность занятия'),
            'ustem11' => tt('Кафедра'),
            'groups' => tt('Группы'),
            'nr18'   => tt('Длительность')
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ustem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getTheme($us1)
    {
        $sql=<<<SQL
            select ustem.*,k2,k3 from ustem
            LEFT JOIN k on (ustem11 = k1)
            where ustem2=:us1 order by ustem4
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':us1', $us1);
        $themes = $command->queryAll();
        return $themes;
    }
	
    public function deleteThematicPlan($model)
    {
        if(!empty($model->group))
        {
            list($us1,$us6)=explode('/',$model->group);
            $sql=<<<SQL
                SELECT * FROM ustem WHERE ustem2=:us1;
SQL;
            $command = Yii::app()->db->createCommand($sql);
            $command->bindValue(':us1', $us1);
            $res=$command->queryAll();

            $name=P::model()->getTeacherNameBy(Yii::app()->user->dbModel->p1,false);
            foreach($res as $val)
            {
                $ustem1=$val['ustem1'];
                $ustem2=$val['ustem2'];
                $ustem3=$val['ustem3'];
                $ustem4=$val['ustem4'];
                $ustem5=$val['ustem5'];
                $ustem6=$val['ustem6'];

                $text=$name.' Ustem1-'.$ustem1.' Ustem2-'.$ustem2.' Ustem3-'.$ustem3.' Ustem4-'.$ustem4.' Ustem5-'.$ustem5.' Ustem6-'.$ustem6;
                $sql = <<<SQL
              INSERT into adz (adz1,adz2,adz3,adz4,adz5,adz6) VALUES (:adz1,:adz2,:adz3,:adz4,:adz5,:adz6);
SQL;
                $command=Yii::app()->db->createCommand($sql);
                $command->bindValue(':adz1', 0);
                $command->bindValue(':adz2', 999);
                $command->bindValue(':adz3', 1);
                $command->bindValue(':adz4', date('Y-m-d H:i:s'));
                $command->bindValue(':adz5', 0);
                $command->bindValue(':adz6', $text);
                $command->execute();
            }

            $sql=<<<SQL
                DELETE  FROM ustem WHERE ustem2=:us1;
SQL;
            $command = Yii::app()->db->createCommand($sql);
            $command->bindValue(':us1', $us1);
            $command->queryAll();
            $this->noAcceptThematicPlan($us1);
        }
    }

    public function selectAcceptThematicPlan($us1)
    {
        if(empty($us1))
        return false;

        $sql=<<<SQL
            SELECT * FROM ustemp WHERE ustemp1=:us1;
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':us1', $us1);
        $res=$command->queryRow();

        if(empty($res))
            return false;
        else
            return true;

    }

    public function noAcceptThematicPlan($us1)
    {
        if(!empty($us1))
        {
            $sql=<<<SQL
                DELETE FROM ustemp WHERE ustemp1=:us1;
SQL;
            $command = Yii::app()->db->createCommand($sql);
            $command->bindValue(':us1', $us1);
            $command->execute();
        }
    }

    public function acceptThematicPlan($model)
    {
        if(!empty($model->group))
        {
            list($us1,$us6)=explode('/',$model->group);
            $this->noAcceptThematicPlan($us1);
            $sql=<<<SQL
                INSERT INTO ustemp(ustemp1) VALUES (:us1);
SQL;
            $command = Yii::app()->db->createCommand($sql);
            $command->bindValue(':us1', $us1);
            $res=$command->execute();
        }
    }

    public function getDefaultChair($us1)
    {
        $sql=<<<SQL
            SELECT uo4 FROM us
            INNER JOIN uo ON (us.us2 = uo.uo1)
            WHERE us1=:US1
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':US1', $us1);
        $uo4=$command->queryScalar();
        return $uo4;
    }

    public function addChair($us1)
    {
        $uo4 = self::model()->getDefaultChair($us1);

        Ustem::model()->updateAll(array('ustem11'=>$uo4),'ustem2=:US1 AND ustem11=0',array(':US1'=>$us1));
        /*$sql=<<<SQL
                UPDATE ustem SET ustem11=:UO4 WHERE ustem2=:US1;
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':US1', $us1);
        $command->bindValue(':U04', $uo4);
        $command->execute();*/
    }

    public function recalculation($us1)
    {
        $themes=Ustem::model()->findAllByAttributes(array('ustem2'=>$us1),array('order'=>'ustem4 ASC,ustem1 ASC'));
        foreach($themes as $key => $theme) {
            if($theme->ustem4!=((int)$key+1))
            {
                $theme->ustem4=(int)$key+1;
                $theme->save();
            }
        }
    }

    public function getUstem6Arr($us1 = null)
    {
        /**
         * @var $uo Uo
         */
        $uo = null;

        $us = Us::model()->findByPk($us1);

        if(!empty($us))
            $uo = Uo::model()->findByPk($us->us2);

        if(!empty($uo)&&$uo->uo6==3){
            return array(
                '0'=>tt('Занятие'),
                '1'=>tt('Субмодуль'),
                //'2'=>tt('ПМК'),
                '3'=>tt('Диф. Зачет')
                //'4'=>tt('Зачет'),
                //'5'=>tt('Экзамен'),
            );
        }else
            return array(
                '0'=>tt('Занятие'),
                '1'=>tt('Субмодуль'),
                '2'=>tt('ПМК'),
                '3'=>tt('Диф. Зачет'),
                '4'=>tt('Зачет'),
                '5'=>tt('Экзамен'),
            );
    }

    public function getUstem6Value($key)
    {
        switch($key)
        {
            case '0':
                $type='';
                break;
            case '1':
                $type=tt('Субмодуль');
                break;
            case '2':
                $type=tt('ПМК');
                break;
            case '3':
                $type=tt('Диф.зач');
                break;
            case '4':
                $type=tt('Зач.');
                break;
            case '5':
                $type=tt('Экз.');
                break;
            default:
                $type='';
        }
        return $type;
    }

    public function getUstem11Arr($us1)
    {
        $sql = <<<SQL
             select nr30,k2 from sem
              inner join us on (sem.sem1 = us.us3)
              inner join nr on (us.us1 = nr.nr2)
              inner join ug on (nr.nr1 = ug.ug1)
              INNER JOIN k on (nr30 = k1)
            WHERE sem3=:YEAR AND sem5=:SEM AND us1=:US1
            GROUP BY nr30,k2
SQL;
        $command=Yii::app()->db->createCommand($sql);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
        $command->bindValue(':US1', $us1);
        $res = $command->queryAll();
        return $res;
    }

    public function getUstem7Arr()
    {
        /*select rz8 from rz group by rz8*/
        $sql = <<<SQL
              select rz8
                from sem
                   inner join us on (sem.sem1 = us.us12)
                   inner join nr on (us.us1 = nr.nr2)
                   inner join r on (nr.nr1 = r.r1)
                   inner join rz on (r.r4 = rz.rz1)
                where sem3=:YEAR AND sem5=:SEM
                group by rz8
SQL;
        $command=Yii::app()->db->createCommand($sql);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
        $res = $command->queryAll();
        foreach($res as $key => $val)
        {
            $res[$key]['rz8_']=round($val['rz8'], 2);
        }
        return $res;
    }

    public function getUstem6($type)
    {
        $arr=$this->getUstem6Arr();
        if(isset($arr[$type]))
            return $arr[$type];
        else
            return '-';
    }

    //фактичесое количетво часов по тем плану
    public function getHours($us1,$ustem1)
    {
        $res = Ustem::model()->findAllByAttributes(
            array('ustem2'=>$us1),
            array(
                'select'=>'ustem7',
                'condition'=>'ustem1 != :ustem1',
                'params'=>array(':ustem1'=>$ustem1)
            ));

        $hours=0;
        foreach($res as $val)
        {
            $hours+=$val['ustem7'];
        }
        return $hours;
    }

    public function getLessonByRasp()
    {
        $sql = <<<SQL
             select r2,rz8 as dlitelnost
            from rz
               inner join r on (rz.rz1 = r.r4)
               inner join nr on (r.r1 = nr.nr1)
               inner join us on (nr.nr2 = us.us1)
               inner join ug on (nr.nr1 = ug.ug3)
            where us1=:us1 and ug2=:gr1
            order by r2,rz2
SQL;

    }

}
