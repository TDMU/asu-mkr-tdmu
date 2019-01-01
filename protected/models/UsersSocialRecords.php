<?php

/**
 * This is the model class for table "users_socialrecords".
 *
 * The followings are the available columns in table 'users_socialrecords':
 * @property integer $id
 * @property integer $userid
 * @property integer $usertype
 * @property integer $personid
 * @property string $service
 * @property string $serviceid
 * @property string $created
 * @property string $updated
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class UsersSocialRecords extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users_socialrecords';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, userid, usertype, personid', 'numerical', 'integerOnly'=>true),
			array('service', 'length', 'max'=>80),
			array('serviceid', 'length', 'max'=>200),
			array('created, updated', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, userid, usertype, personid, service, serviceid, created, updated', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Users', 'userid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'userid' => 'Userid',
			'usertype' => 'Usertype',
			'personid' => 'Personid',
			'service' => 'Service',
			'serviceid' => 'Serviceid',
			'created' => 'Created',
			'updated' => 'Updated',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('usertype',$this->usertype);
		$criteria->compare('personid',$this->personid);
		$criteria->compare('service',$this->service,true);
		$criteria->compare('serviceid',$this->serviceid,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UsersSocialrecords the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
