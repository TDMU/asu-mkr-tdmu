<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 30.09.14
 * Time: 9:12
 */
class ConfigEAuthForm extends CFormModel
{
    public $enable;
	public $popup = 0;

    // register your app here: https://dev.twitter.com/apps/new
    public $enableTwitter;
    public $keyTwitter;
    public $secretTwitter;
    //https://github.com/settings/applications
    public $enableGithub;
    public $clientIdGithub;
    public $clientSecretGithub;
    // register your app here: https://vk.com/editapp?act=create&site=1
    public $enableVkontakte;
    public $clientIdVkontakte;
    public $clientSecretVkontakte;
    // register your app here: http://api.mail.ru/sites/my/add
    public $enableMailru;
    public $clientIdMailru;
    public $clientSecretMailru;
    // register your app here: https://www.dropbox.com/developers/apps/create
    public $enableDropbox;
    public $clientIdDropbox;
    public $clientSecretDropbox;
    // register your app here: https://developers.facebook.com/apps/
    public $enableFacebook;
    public $clientIdFacebook;
    public $clientSecretFacebook;
    // register your app here: https://code.google.com/apis/console/
    public $enableGoogle;
    public $clientIdGoogle;
    public $clientSecretGoogle;
    // register your app here: https://oauth.yandex.ru/client/my
    public $enableYandex;
    public $clientIdYandex;
    public $clientSecretYandex;
    // register your app here: https://www.linkedin.com/secure/developer
    public $enableLinkedin;
    public $keyLinkedin;
    public $secretLinkedin;

    public function rules()
    {
        $boolProps = 'enable, enableGithub, enableTwitter, enableVkontakte, '.
            'enableMailru, enableDropbox, enableFacebook, enableGoogle, enableYandex, enableLinkedin';

        $keys = 'keyTwitter, secretTwitter, clientIdFacebook, clientSecretFacebook, clientIdGithub, clientSecretGithub, '.
            'clientIdGoogle, clientSecretGoogle, clientIdYandex, clientSecretYandex, keyLinkedin, secretLinkedin, '.
            'clientIdVkontakte, clientSecretVkontakte, clientIdDropbox, clientSecretDropbox, clientIdMailru, clientSecretMailru';

        return array(
			array($boolProps, 'numerical', 'integerOnly'=>true),
            array($boolProps, 'default', 'value'=>0, 'setOnEmpty'=>true),
            array($boolProps, 'in', 'range' => array(0, 1)),
            array($keys, 'length', 'max'=>100)
		);
    }

    public function attributeLabels()
    {
        return array(
            'enable' => tt('Разрешить авторизацию через соц. сети'),
            'popup' => tt('Авторизация:'),
            'enableGithub' => tt('Авторизация через GitHub'),
            'enableTwitter' => tt('Авторизация через Twitter'),
            'enableWargaming' => tt('Авторизация через Wargaming'),
            'enableYahoo' => tt('Авторизация через Yahoo'),
            'enableVkontakte' => tt('Авторизация через Vkontakte'),
            'enableMailru' => tt('Авторизация через Mailru'),
            'enableDropbox' => tt('Авторизация через Dropbox'),
            'enableFacebook' => tt('Авторизация через Facebook'),
            'enableGoogle' => tt('Авторизация через Google'),
            'enableYandex' => tt('Авторизация через Yandex'),
            'enableLinkedin' => tt('Авторизация через Linkedin')
        );
    }

    public function getAuthPopupType(){
        return array(
            0 => tt('Редирект'),
            1 => tt('Окно')
        );
    }

    /**
     * формирования массива сервиса для расширения EAuth
     * @return array
     */
    public function getServicesArr(){
        $services = array();

        if($this->enableLinkedin == 1)
            $services['linkedin'] = array(
                'class' => 'LinkedinOAuthService',
                'key' => $this->keyLinkedin,
                'secret' => $this->secretLinkedin,
            );

        if($this->enableTwitter == 1)
            $services['twitter'] = array(
                'class' => 'TwitterOAuthService',
                'key' => $this->keyTwitter,
                'secret' => $this->secretTwitter,
            );

        if($this->enableFacebook == 1)
            $services['facebook'] = array(
                'class' => 'AsuFacebookOAuthService',
                'client_id' => $this->clientIdFacebook,
                'client_secret' => $this->clientSecretFacebook,
            );

        if($this->enableGithub== 1)
            $services['github'] = array(
                'class' => 'AsuGitHubOAuthService',
                'client_id' => $this->clientIdGithub,
                'client_secret' => $this->clientSecretGithub,
            );

        if($this->enableGoogle== 1)
            $services['google_oauth'] = array(
                'class' => 'AsuGoogleOAuthService',
                'client_id' => $this->clientIdGoogle,
                'client_secret' => $this->clientSecretGoogle,
                'title' => 'Google (OAuth)',
            );

        if($this->enableYandex== 1)
            $services['yandex_oauth'] = array(
                'class' => 'YandexOAuthService',
                'client_id' => $this->clientIdYandex,
                'client_secret' => $this->clientSecretYandex,
                'title' => 'Yandex  (OAuth)',
            );

        if($this->enableVkontakte == 1)
            $services['vkontakte'] = array(
                'class' => 'VKontakteOAuthService',
                'client_id' => $this->clientIdVkontakte,
                'client_secret' => $this->clientSecretVkontakte,
            );

        if($this->enableMailru == 1)
            $services['mailru'] = array(
                'class' => 'MailruOAuthService',
                'client_id' => $this->clientIdMailru,
                'client_secret' => $this->clientSecretMailru,
            );

        if($this->enableDropbox == 1)
            $services['dropbox'] = array(
                'class' => 'DropboxOAuthService',
                'client_id' => $this->clientIdDropbox,
                'client_secret' => $this->clientSecretDropbox,
            );

        return $services;
    }

    public function setAttributesByServicesArr($services){

        if(empty($services))
            return;

        if(isset($services['linkedin'])) {
            $this->enableLinkedin = true;
            $this->keyLinkedin = $this->getValueByArrayKey($services['linkedin'],'key','');
            $this->secretLinkedin = $this->getValueByArrayKey($services['linkedin'],'secret','');
        }else {
            $this->enableLinkedin = false;
        }

        if(isset($services['twitter'])) {
            $this->enableTwitter = true;
            $this->keyTwitter = $this->getValueByArrayKey($services['twitter'],'key','');
            $this->secretTwitter = $this->getValueByArrayKey($services['twitter'],'secret','');
        }else {
            $this->enableTwitter = false;
        }

        if(isset($services['facebook'])) {
           $this->enableFacebook = true;
           $this->clientIdFacebook = $this->getValueByArrayKey($services['facebook'],'client_id','');
           $this->clientSecretFacebook = $this->getValueByArrayKey($services['facebook'],'client_secret','');
        }else {
            $this->enableFacebook = false;
        }

        if(isset($services['github'])) {
            $this->enableGithub= true;
            $this->clientIdGithub  = $this->getValueByArrayKey($services['github'],'client_id','');
            $this->clientSecretGithub = $this->getValueByArrayKey($services['github'],'client_secret','');
        }else {
            $this->enableGithub = false;
        }

        if(isset($services['google_oauth'])) {
            $this->enableGoogle= true;
            $this->clientIdGoogle  = $this->getValueByArrayKey($services['google_oauth'],'client_id','');
            $this->clientSecretGoogle = $this->getValueByArrayKey($services['google_oauth'],'client_secret','');
        }else {
            $this->enableGoogle = false;
        }

        if(isset($services['yandex_oauth'])) {
            $this->enableYandex= true;
            $this->clientIdYandex  = $this->getValueByArrayKey($services['yandex_oauth'],'client_id','');
            $this->clientSecretYandex = $this->getValueByArrayKey($services['yandex_oauth'],'client_secret','');
        }else {
            $this->enableYandex = false;
        }

        if(isset($services['vkontakte'])) {
            $this->enableVkontakte= true;
            $this->clientIdVkontakte  = $this->getValueByArrayKey($services['vkontakte'],'client_id','');
            $this->clientSecretVkontakte = $this->getValueByArrayKey($services['vkontakte'],'client_secret','');
        }else {
            $this->enableVkontakte = false;
        }

        if(isset($services['mailru'])) {
            $this->enableMailru= true;
            $this->clientIdMailru  = $this->getValueByArrayKey($services['mailru'],'client_id','');
            $this->clientSecretMailru = $this->getValueByArrayKey($services['mailru'],'client_secret','');
        }else {
            $this->enableMailru = false;
        }

        if(isset($services['dropbox'])) {
            $this->enableDropbox= true;
            $this->clientIdDropbox  = $this->getValueByArrayKey($services['dropbox'],'client_id','');
            $this->clientSecretDropbox = $this->getValueByArrayKey($services['dropbox'],'client_secret','');
        }else {
            $this->enableDropbox = false;
        }
    }

    private function getValueByArrayKey($array, $key, $default){
        if(!isset($array[$key]))
            return $default;

        return $array[$key];
    }

}
?>