<?php

/**
 * This is the model class for table "elgr".
 *
 * The followings are the available columns in table 'elgr':
 * @property integer $elgr1
 * @property integer $elgr2
 * @property string $elgr3
 * @property string $elgr4
 * @property string $elgr5
 * @property integer $elgr6
 */
class Elgr extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'elgr';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('elgr1, elgr2, elgr6', 'numerical', 'integerOnly'=>true),
			array('elgr3, elgr5', 'length', 'max'=>8),
			array('elgr4', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('elgr1, elgr2, elgr3, elgr4, elgr5, elgr6', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Elgr the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getList($gr1,$elgz1)
    {

        $res = Yii::app()->db->createCommand()
            ->select('elgr2,elgr3')
            ->from('elgr')
            ->where(array('in', 'elgr2', $elgz1))
            ->andWhere('elgr1 = :GR1', array(':GR1' => $gr1))
            ->queryAll();

        if(!empty($res))
        {
            $arr = array();
            foreach($res as $val)
            {
                $arr[$val['elgr2']]=$val['elgr3'];
            }
            return $arr;
        }else
            return array();
    }
}
