<?php

/**
 * This is the model class for table "passport".
 *
 * The followings are the available columns in table 'passport':
 * @property integer $passport1
 * @property integer $passport2
 * @property integer $passport3
 * @property string $passport4
 */
class Passport extends CGraficActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'passport';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('passport2', 'required'),
			array('passport1, passport2, passport3', 'numerical', 'integerOnly'=>true),
			array('passport4', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('passport1, passport2, passport3, passport4', 'safe', 'on'=>'search'),
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
			'passport1' => 'Passport1',
			'passport2' => 'Passport2',
			'passport3' => 'Passport3',
			'passport4' => 'Passport4',
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

		$criteria->compare('passport1',$this->passport1);
		$criteria->compare('passport2',$this->passport2);
		$criteria->compare('passport3',$this->passport3);
		$criteria->compare('passport4',$this->passport4,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Passport the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * Рендер фото пользователя
     * @param $id
     * @param $type
     * @throws CHttpException
     */
    public static function renderPassport($id, $type){
        $model = Passport::model()->findByAttributes(array(
            'passport2' => $id,
            'passport3' => $type
        ));

        if($model==null)
        {
            throw new CHttpException(500, 'Невозможно отобразить');
        }else{
            if(!empty($model->passport4)) {
                $im = imagecreatefromstring($model->passport4);
                if ($im !== false) {
                    header('Content-Type: image/jpeg');
                    imagejpeg($im);
                    imagedestroy($im);
                }else
                    throw new CHttpException(500, 'Невозможно отобразить');
            }else {
                throw new CHttpException(500, 'Невозможно отобразить');
            }
        }

    }
}
