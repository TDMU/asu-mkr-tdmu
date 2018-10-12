<?php

/**
 * This is the model class for table "rz".
 *
 * The followings are the available columns in table 'rz':
 * @property integer $rz1
 * @property string $rz2
 * @property string $rz3
 * @property string $rz4
 * @property string $rz5
 */
class Rz extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rz';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rz1', 'numerical', 'integerOnly'=>true),
			array('rz2, rz3, rz4, rz5', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rz1, rz2, rz3, rz4, rz5', 'safe', 'on'=>'search'),
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
			'rz1' => 'Rz1',
			'rz2' => 'Rz2',
			'rz3' => 'Rz3',
			'rz4' => 'Rz4',
			'rz5' => 'Rz5',
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

		$criteria->compare('rz1',$this->rz1);
		$criteria->compare('rz2',$this->rz2,true);
		$criteria->compare('rz3',$this->rz3,true);
		$criteria->compare('rz4',$this->rz4,true);
		$criteria->compare('rz5',$this->rz5,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Rz the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getRzArray($ks1)
    {
        if(empty($ks1))
            $ks1=0;
            $rows = Yii::app()->db->createCommand()
                ->select('*')
                ->from($this->tableName())
                ->where('rz7=0 AND rz16=:id', array(':id'=>$ks1))
                ->queryAll();

        $res = array();
        foreach($rows as $row) {
            $key = $row['rz6'];
            $res[$key] = $row;
        }

        return $res;
    }

    public function getRzForDropdown($filial = null)
    {
        $query = Yii::app()->db->createCommand()
            ->select('*')
            ->from($this->tableName());
        if($filial !== null)
            $query = $query->where(" rz16 = :FILIAL", array(
                ':FILIAL' => $filial
            ));
        $rows = $query->queryAll();

        foreach($rows as $key => $row) {
            $rows[$key]['name'] = $row['rz1'].' '.tt('пара').' ('.$row['rz2'].'-'.$row['rz3'].')';
        }

        return $rows;
    }

}
