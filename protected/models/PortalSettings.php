<?php

/**
 * This is the model class for table "portal_settings".
 *
 * The followings are the available columns in table 'portal_settings':
 * @property integer $ps1
 * @property string $ps2
 */
class PortalSettings extends CActiveRecord
{
    const ENABLE_DIST_EDUCATION = 122;
    const HOST_DIST_EDUCATION = 123;
    const APIKEY_DIST_EDUCATION = 124;
    /**
     * Записівать накурсі при записи на дисциплину
     */
    const ENABLE_IN_SUBSCRIPTION_DIST_EDUCATION = 129;

    /**
     * роль для студиков мудла
     */
    const ROLE_ID_FOR_MOODLE_STUDENTS = 130;

    const ZAP_SUPPORT_API_KEY_ID = 38999999;//запорожье
	//const ZAP_SUPPORT_SECRET_KEY_ID = 38999998;//запорожье
    /**
     * @var int ошибка записи на дисциплины сообщение
     */
    const ERROR_SUBCRIPTION_MESSAGE = 126;
    /**
     * @var int мобильное приложение через авторизацию
     * Не используется
     */
    //const MOBILE_APP_NEED_AUTH = 128;
    /**
     * @var int Письмо о ригитсрации в дист. образовании
     */
    const REGISTRATION_EMAIL_DIST_EDUCATION = 127;
    /**
     * @var int Письмо о ригитсрации на курс
     */
    const SUBSCRIPTION_EMAIL_DIST_EDUCATION = 131;
    /**
     * @var int Письмо о выпеске с курса
     */
    const UNSUBSCRIPTION_EMAIL_DIST_EDUCATION = 132;
    /**
     * @var int Почта администротора дсит.образования
     */
    const ADMIN_EMAIL_DIST_EDUCATION = 133;

    /**
     * @var int Письмо для подтвреднеия почты в дист образовании
     */
    const ACCEPT_EMAIL_DIST_EDUCATION = 134;

	private  $settings = array();
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'portal_settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ps1', 'required'),
			array('ps1', 'numerical', 'integerOnly'=>true),
			array('ps2', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ps1, ps2', 'safe', 'on'=>'search'),
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
			'ps1' => 'Ps1',
			'ps2' => 'Ps2',
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

		$criteria->compare('ps1',$this->ps1);
		$criteria->compare('ps2',$this->ps2,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PortalSettings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getJournalExtraColumns()
    {
        $sql = <<<SQL
        SELECT ps1, ps2
        FROM PORTAL_SETTINGS
        WHERE ps1 between 0 and 7
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $columns = $command->queryAll();

        $res = array();
        foreach ($columns as $column) {
            if ($column['ps1'] == 0 && $column['ps2'] == 1)
                $res[] = array('dsej4', $columns[1]['ps2']);

            if ($column['ps1'] == 2 && $column['ps2'] == 1)
                $res[] = array('dsej5', $columns[3]['ps2']);

            if ($column['ps1'] == 4 && $column['ps2'] == 1)
                $res[] = array('dsej6', $columns[5]['ps2']);

            if ($column['ps1'] == 6 && $column['ps2'] == 1)
                $res[] = array('dsej7', $columns[7]['ps2']);
        }

        return $res;
    }

    public function getModuleExtraColumns()
    {
        $sql = <<<SQL
        SELECT ps1, ps2
        FROM PORTAL_SETTINGS
        WHERE ps1 between 10 and 15
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $columns = $command->queryAll();

        $res = array();
        $res['total1'] = array();
        $res['total2'] = array();

        foreach ($columns as $key => $column) {
            if ($column['ps1'] == 10 && $column['ps2'] == 1)
                $res['total1'][] = array('0', $columns[$key+1]['ps2']);

            if ($column['ps1'] == 12 && $column['ps2'] == 1)
                $res['total1'][] = array('stus3', $columns[$key+1]['ps2']);

            if ($column['ps1'] == 14 && $column['ps2'] == 1)
                $res['total2'][] = array('-1', $columns[$key+1]['ps2']);
        }

        return $res;
    }

    public function getSettingFor($key)
    {
        if (! isset($this->settings[$key])) {

            $setting = PortalSettings::model()->findByPk($key)->getAttribute('ps2');

            $this->settings[$key] = $setting;
        }

        return $this->settings[$key];
    }
}
