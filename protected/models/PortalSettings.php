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

    /**
     *
     */
    const USE_PORTFOLIO = 138;

    /**
     *
     */
    const PORTFOLIO_PATH = 139;

    /**
     * Разрешено ли стущдикам отправлять сообщения в сервисе оповещения
     */
    const STUDENT_SEND_IN_ALERT = 140;

    /**
     * Для хранения настро
     * @var array
     */
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
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PortalSettings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * @param $key
     * @return mixed
     */
    public function getSettingFor($key)
    {
        if (! isset($this->settings[$key])) {

            $setting = PortalSettings::model()->findByPk($key)->getAttribute('ps2');

            $this->settings[$key] = $setting;
        }

        return $this->settings[$key];
    }
}
