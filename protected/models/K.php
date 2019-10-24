<?php

/**
 * This is the model class for table "k".
 *
 * The followings are the available columns in table 'k':
 * @property integer $k1
 * @property string $k2
 * @property string $k3
 * @property string $k4
 * @property string $k5
 * @property string $k6
 * @property integer $k7
 * @property integer $k8
 * @property string $k9
 * @property integer $k10
 * @property string $k11
 * @property integer $k12
 * @property string $k13
 * @property integer $k14
 * @property string $k15
 * @property string $k16
 * @property string $k17
 * @property string $k18
 * @property string $k20
 */
class K extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'k';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('k1', 'required'),
			array('k1, k7, k8, k10, k12, k14, k20', 'numerical', 'integerOnly'=>true),
			array('k2', 'length', 'max'=>200),
			array('k3, k4, k5, k15, k16, k17, k18', 'length', 'max'=>600),
			array('k11', 'length', 'max'=>4),
			array('k9, k13', 'length', 'max'=>8),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return K the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getFieldByLanguage($short){
		switch(Yii::app()->language){
			case 'uk':
				if(!$short)
					return 'k3';
				else
					return 'k2';
				break;
			case 'ru':
				if(!$short)
					return 'k18';
				else
					return 'k2';
				break;
			case 'en': if(!$short)
				return 'k15';
			else
				return 'k2';
				break;
		}

		return 'k3';
	}

    public function getAllChairs()
    {
        $today = date('Y-m-d');
        $sql = <<<SQL
            SELECT k1,k2,k3
            FROM f
              INNER JOIN k on (f.f1 = k.k7)
            WHERE f1>0 and f12<>0 and f17<>2 and (f19 is null or f19>'{$today}') and k20=0 and
                  k11<>0 and k1>0 and (k9 is null or k9>'{$today}')
            ORDER BY k8,f15,f3 collate UNICODE, k3 collate UNICODE
SQL;
        $chairs = Yii::app()->db->createCommand($sql)->queryAll();

        return $chairs;
    }

    public function getOnlyChairsFor($filial)
    {
        $sql=<<<SQL
            SELECT K1,K2,K3,K15,K16,K17,K10, K18
				FROM F
				inner join k on (f.f1 = k.k7)
			WHERE f12='1' and f17='0' and k11='1' and k10=:FILIAL and (k9 is null) and K1>0 and k20=0
			ORDER BY K3 collate UNICODE
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':FILIAL', $filial);
        $chairs = $command->queryAll();

		$res = array();
		foreach($chairs as $key => $chair){
			$name =  $chair[K::getFieldByLanguage(false)];
			$res[$key] = $chair;
			$res[$key]['name'] = (isset($name)&&!empty($name)&&$name!="")?$name:$chair['k3'];
		}
		return CHtml::listData($res, 'k1', 'name');
    }

    public function getChairByUo1($uo1)
    {
        if (empty($uo1))
            return 0;

        $sql=<<<SQL
            SELECT uo4
				FROM uo
			WHERE uo1=:UO1
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':UO1', $uo1);
        $chair = $command->queryScalar();

        return $chair;
    }

}
