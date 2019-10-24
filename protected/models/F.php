<?php

/**
 * This is the model class for table "f".
 *
 * The followings are the available columns in table 'f':
 * @property integer $f1
 * @property string $f2
 * @property string $f3
 * @property string $f4
 * @property string $f5
 * @property string $f6
 * @property string $f7
 * @property string $f8
 * @property string $f9
 * @property string $f10
 * @property string $f11
 * @property string $f12
 * @property string $f13
 * @property integer $f14
 * @property integer $f15
 * @property integer $f16
 * @property string $f17
 * @property string $f18
 * @property string $f19
 * @property integer $f22
 * @property string $f23
 * @property integer $f24
 * @property integer $f25
 * @property string $f26
 * @property string $f27
 * @property string $f28
 * @property string $f29
 * @property string $f30
 * @property string $f31
 * @property string $f20
 * @property string $f21
 * @property integer $f32
 * @property integer $f37
 */
class F extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'f';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('f1', 'required'),
			array('f1, f14, f15, f16, f22, f24, f25, f32, f37', 'numerical', 'integerOnly'=>true),
			array('f2, f31', 'length', 'max'=>100),
			array('f3, f4, f5', 'length', 'max'=>800),
			array('f6, f7, f8, f9, f10, f11, f12, f17, f18, f29', 'length', 'max'=>4),
			array('f13', 'length', 'max'=>12),
			array('f19, f23', 'length', 'max'=>8),
			array('f26, f27, f28, f30, f21', 'length', 'max'=>400),
			array('f20', 'length', 'max'=>180),
        );
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return F the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getFieldByLanguage($short){
		switch(Yii::app()->language){
			case 'uk':
				if(!$short)
					return 'f3';
				else
					return 'f2';
				break;
			case 'ru':
				if(!$short)
					return 'f30';
				else
					return 'f2';
				break;
			case 'en': if(!$short)
				return 'f26';
			else
				return 'f2';
				break;
		}

		return 'f3';
	}
    /**
     * @param int $type тип если 1 то нужно блокировать для юрки факультет номер 5
     * @return array
     */
    public function getFacultiesFor($filial, $type=0)
    {
        $sql=<<<SQL
            SELECT f1, f3, f26, f27, f28, f30
            FROM f
            WHERE f1>0 and f12<>0 and f17=0 and (f19 is null) and f14=:FILIAL and f32 = 0
            ORDER BY f15,f3 collate UNICODE
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':FILIAL', $filial);
        $faculties = $command->queryAll();

		$res = array();
		foreach($faculties as $key => $faculty){
            if($type==1&&Yii::app()->core->universityCode==U_NULAU&&$faculty['f1']==5)
                continue;
			$name =  $faculty[F::getFieldByLanguage(false)];
			$res[$key] = $faculty;
			$res[$key]['name'] = (isset($name)&&!empty($name)&&$name!="")?$name:$faculty['f3'];
		}
		return CHtml::listData($res, 'f1', 'name');
        //return $faculties;
    }

    /**
     * Все факультеты
     * @return array
     */
    public function getAllFaculties()
    {
        $sql=<<<SQL
            SELECT f1, f3
            FROM f
            WHERE f1>0 and f12<>0 and f17=0 and (f19 is null) and f32 = 0
            ORDER BY f15,f3 collate UNICODE
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $faculties = $command->queryAll();

        return $faculties;
    }
}
