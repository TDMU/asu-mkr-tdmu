<?php

/**
 * This is the model class for table "stegn".
 *
 * The followings are the available columns in table 'stegn':
 * @property integer $stegn1
 * @property integer $stegn2
 * @property integer $stegn3
 * @property integer $stegn4
 * @property double $stegn5
 * @property double $stegn6
 * @property string $stegn7
 * @property integer $stegn8
 *
 * The followings are the available model relations:
 * @property St $stegn10
 * @property Us $stegn20
 * @property P $stegn80
 */
class Stegn extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stegn';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('stegn1, stegn2, stegn3, stegn4, stegn8', 'numerical', 'integerOnly'=>true),
			array('stegn5, stegn6', 'numerical'),
			array('stegn7', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('stegn1, stegn2, stegn3, stegn4, stegn5, stegn6, stegn7, stegn8', 'safe', 'on'=>'search'),
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
			'stegn10' => array(self::BELONGS_TO, 'St', 'stegn1'),
			'stegn20' => array(self::BELONGS_TO, 'Us', 'stegn2'),
			'stegn80' => array(self::BELONGS_TO, 'P', 'stegn8'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'stegn1' => 'Stegn1',
			'stegn2' => 'Stegn2',
			'stegn3' => 'Stegn3',
			'stegn4' => 'Stegn4',
			'stegn5' => 'Stegn5',
			'stegn6' => 'Stegn6',
			'stegn7' => 'Stegn7',
			'stegn8' => 'Stegn8',
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

		$criteria->compare('stegn1',$this->stegn1);
		$criteria->compare('stegn2',$this->stegn2);
		$criteria->compare('stegn3',$this->stegn3);
		$criteria->compare('stegn4',$this->stegn4);
		$criteria->compare('stegn5',$this->stegn5);
		$criteria->compare('stegn6',$this->stegn6);
		$criteria->compare('stegn7',$this->stegn7,true);
		$criteria->compare('stegn8',$this->stegn8);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stegn the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getMarksForStudent($st1, $us1)
        {
            $raws = Yii::app()->db->createCommand()
                ->select('*')
                ->from('stegn')
                ->where(array('in', 'stegn2', $us1))
                ->andWhere('stegn1 = :ST1', array(':ST1' => $st1))
                ->queryAll();

            $res = array();
            foreach($raws as $raw) {
                $key = $raw['stegn2'].'/'.$raw['stegn3'];
                $res[$key] = $raw;
            }

            return $res;
        }
        
        public function insertMark($stegn1,$stegn2,$stegn3,$field,$value){
            if ($field == 'stegn4')
            {
                $sql = <<<SQL
                    UPDATE or INSERT INTO stegn (stegn1,stegn2,stegn3,stegn4,stegn7,stegn8) VALUES (:st1,:us1,:nom,:value,current_timestamp,:p1) MATCHING (stegn1,stegn2,stegn3);
SQL;
                if($value==0)
                {
                    $value=1;
                }
                else {
                    $value=0;
                } 
            }
            elseif ($field == 'stegn5')
                $sql = <<<SQL
                    UPDATE or INSERT INTO stegn (stegn1,stegn2,stegn3,stegn5,stegn7,stegn8) VALUES (:st1,:us1,:nom,:value,current_timestamp,:p1) MATCHING (stegn1,stegn2,stegn3);
SQL;
            elseif ($field == 'stegn6')
                $sql = <<<SQL
                    UPDATE or INSERT INTO stegn (stegn1,stegn2,stegn3,stegn6,stegn7,stegn8) VALUES (:st1,:us1,:nom,:value,current_timestamp,:p1) MATCHING (stegn1,stegn2,stegn3);
SQL;
            $command = Yii::app()->db->createCommand($sql);
            $command->bindValue(':st1', $stegn1);
            $command->bindValue(':us1', $stegn2);
            $command->bindValue(':nom', $stegn3);
            $command->bindValue(':value', $value);
            $command->bindValue(':p1', Yii::app()->user->dbModel->p1);
            $command->execute();
        }
}
