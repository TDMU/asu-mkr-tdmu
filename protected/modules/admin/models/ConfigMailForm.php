<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 30.09.14
 * Time: 9:12
 */
class ConfigMailForm extends CFormModel
{
    public $Host;
    public $Username;
    public $Password;
	public $Port;
    public $SMTPSecure;

    public function rules()
    {
        return array(
            array('Host, Username, Password, Port','required'),
            array('SMTPSecure', 'length', 'max'=>10),
            array('Port', 'numerical', 'integerOnly'=>true),
        );
    }

    public function attributeLabels()
    {
        return array(
            //'Class' => 'Class',
            'Host' => tt('Host'),
            'Username' => tt('Логин'),
            'Password' => tt('Пароль'),
            'Port' => tt('Порт'),
            'SMTPSecure'=>'SMTPSecure',
        );
    }
}
?>