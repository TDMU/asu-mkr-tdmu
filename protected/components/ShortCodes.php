<?php

/**
 * Dummy class to use default Yii preload feature
 */
class ShortCodes extends CApplicationComponent
{
    protected $_menuSettings;
    protected $_seoSettings;

    public static function getShortName($surname, $name, $patronymic)
    {
        $res = $surname;

        $res .= self::truncateText($name);
        $res .= self::truncateText($patronymic);

        return $res;
    }

    public static function getUniversityCod(){
        $sql=<<<SQL
			select b15 from b where b1=0
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $id=$command->queryScalar();
        if(!empty($id))
            return $id;
        else
            return 0;
    }
    public static function truncateText($text)
    {
        if (empty($text))
            return '';

        return ' '.mb_substr($text, 0,1).'.';
    }

    /**
     * @param $us4
     * @param $us6
     * @return string
     */
    public static  function convertUsByStudentCard($us4, $us6){
        $type = '-';
        if($us4!==null)
            switch($us4){
                case 5:	$type = tt('Экз.'); break;
                case 6:
                    if($us6==1)
                        $type = tt('Зач.');
                    elseif($us6==2)
                        $type = tt('Диф.зач.');
                    break;
                case 8:	$type = tt('Курсов.'); break;
                default: $type='';
            }
        return $type;
    }

    public static  function getColorUsByStudentCard($us4, $us6){
        $color = '';
        if($us4!==null)
            switch($us4){
                case 5:	$color = '#ffe1e1'; break;
                case 6:
                    if($us6==1)
                        $color = '#ddffdd';
                    elseif($us6==2)
                        $color = '#d6d6bd';
                    break;
                case 8:	$color = '#d3ffff'; break;
                default: $color='';
            }
        return $color;
    }

    public static  function convertStus19($stus19){
        if($stus19!==null)
            switch($stus19){
                case 5:	$type = tt('Экз.'); break;
                case 6:	$type = tt('Зач.'); break;
                case 7:	$type = tt('Диф.зач.'); break;
                case 8:	$type = tt('Курсов.'); break;
                default: $type='';
            }
        else
            $type = '-';
        return $type;
    }

    public static  function getColorByStus19($stus19){
        if($stus19!==null)
            switch($stus19){
                case 5:	$color = '#ffe1e1'; break;
                case 6:	$color = '#ddffdd'; break;
                case 7:	$color = '#d6d6bd'; break;
                case 8:	$color = '#d3ffff'; break;
                default: $color='';
            }
        else
            $color = '-';
        return $color;
    }

    public static function formatDate($patternFrom, $patternTo, $value)
    {
        if (empty($value))
            return null;

        try{
            $result = DateTime::createFromFormat($patternFrom, $value)->format($patternTo);
        } catch(Exception $e) {
            $result = null;
        }

        return $result;
    }

    /*private static function getNameByUs4AndUs6($sql){
        $command = Yii::app()->db->createCommand($sql);
        $val = $command->queryScalar();

        if (empty($val))
            return "-";

        return $val;
    }*/

    /*public static function convertUS4ByUS6($us4,$us6)
    {
        if($us4!==null){
            if($us4>=5&&$us4<=8&&$us6!=0){
                switch($us4){
                    case 5:	$type = SH::getNameByUs4AndUs6("SELECT e2 FROM e WHERE e1="+$us6); break;
                    case 6:
                            if($us6==1)
                                $type = tt('Зач');
                            else
                                $type = tt('Диф');
                        break;
                    case 7:	$type = SH::getNameByUs4AndUs6("SELECT y2 FROM y WHERE y1="+$us6); break;
                    case 8:	$type = SH::getNameByUs4AndUs6("SELECT w2 FROM w WHERE w1="+$us6); break;
                    default: $type=SH::convertUS4($us4);
                }
            }else
                $type = SH::convertUS4($us4);
        } else
            $type = '-';
        return $type;
    }*/
    public static function convertTypeJournal($_type)
    {
        if($_type!==null) {

            $arr = FilterForm::getTypesForJournal();
            switch ($_type) {
                case 0:
                case 1:
                    $type = $arr[$_type];
                    break;

                default:
                    $type = '';
            }
        }else
            $type = '-';
        return $type;
    }

    public static function convertUS4($us4)
    {
        if($us4!==null)
            switch($us4){
                case 'Prak': $type = tt('Прак');break;
                case 'Dipl': $type = tt('Дипл');break;
                case 'Gek': $type = tt('ГЭК');break;
                case 'Asp': $type = tt('Асп');break;
                case 'Dop': $type = tt('Доп');break;

                case 0:	$type = tt('Всего'); break;
                case 1:	$type = tt('Лк'); break;
                case 2:	$type = tt('Пз'); break;
                case 3:	$type = tt('Сем'); break;
                case 4:	$type = tt('Лб'); break;
                case 5:	$type = tt('Экз'); break;
                case 6:	$type = tt('Зач'); break;
                case 7:	$type = tt('Кр'); break;
                case 8:	$type = tt('КП'); break;
                case 9: $type = tt('УЛк'); break;
                case 10: $type = tt('УПз'); break;
                case 11: $type = tt('УСем'); break;
                case 12: $type = tt('УЛб'); break;
                case 13: $type = tt('Доп'); break;
                case 14: $type = tt('Инд'); break;
                case 15: $type = ''; break;
                case 16: $type = tt('КнЧ'); break;
                case 17: $type = tt('Конс'); break;
                case 18: $type = tt('Пер'); break;
                default: $type='';
            }
        else
            $type = '-';
        return $type;
    }
    
    public static function getSel1ForRating($i)
    {
         $sel_1 = array(
            0 => tt('Младший специалист'),
            1 => tt('Бакалавр'),
            2 => tt('Специалист'),
            3 => tt('Магистр'),
        );
        if(isset($sel_1[$i]))
            return $sel_1[$i];
        else {
            return '-';
        }
    }
    
    public static function getSel2ForRating($i)
    {
        $sel_2 = array(
            0 => tt('Дневная'),
            1 => tt('Заочная'),
            2 => tt('Вечерняя'),
            3 => tt('Экстернат')
        );
        if(isset($sel_2[$i]))
            return $sel_2[$i];
        else {
            return '-';
        }
    }
    
    public static function getLessonColor($type)
    {
        $type = mb_strtolower($type);
        switch($type){
            case 'lec':
            case 'лк':
            case 'улк':
                $color = '#fffadb'; break;
            case 'prac':
            case 'пз':
            case 'пз1':
            case 'пз2':
            case 'пз3':
            case 'пз4':
            case 'упз':
                $color = '#e2ffe2'; break;
            case 'sem':
            case 'сем':
            case 'сем1':
            case 'сем2':
            case 'сем3':
            case 'сем4':
                $color = '#fff0ff'; break;
            case 'lab':
            case 'лб':
            case 'лб1':
            case 'лб2':
            case 'лб3':
            case 'лб4':
            case 'улб':
            case 'улб1':
            case 'улб2':
            case 'улб3':
            case 'улб4':
                $color = '#c4e5ff'; break;
            case 'exam':
            case 'экз':	$color = '#ffe1e1'; break;
            case 'offs':
            case 'зач':	$color = '#a7f1a7'; break;
            case 'cont':
            case 'кр':	$color = '#d6d6bd'; break;
            case 'cour':
            case 'кп':	$color = '#d4d4b9'; break;
            case 'add':
            case 'доп': $color = '#e3ebe4'; break;
            case 'ind':
            case 'инд': $color = '#fffadd'; break;
            case 'гек': $color = '#ff0000'; break;
            case 'кнч': $color = '#dfefff'; break;
            case 'cons':
            case 'конс': $color = '#ffe2ff'; break;
            case 'ret':
            case 'пер': $color = '#e2ffe2'; break;
            default: $color = '';
        }
        return $color;
    }
	
	public static function getClassColor($type)
    {
        $type = mb_strtolower($type);
        switch($type){
            case 'Lec':
            case 'лк':
            case 'улк':
                $color = '1'; break;
            case 'Prac':
            case 'пз':
            case 'пз1':
            case 'пз2':
            case 'пз3':
            case 'пз4':
            case 'упз':
                $color = '2'; break;
            case 'Sem':
            case 'сем':
            case 'сем1':
            case 'сем2':
            case 'сем3':
            case 'сем4':
                $color = '3'; break;
            case 'Lab':
            case 'лб':
            case 'лб1':
            case 'лб2':
            case 'лб3':
            case 'лб4':
            case 'улб':
            case 'улб1':
            case 'улб2':
            case 'улб3':
            case 'улб4':
                $color = '4'; break;
            case 'Exam':
            case 'экз':	$color = '5'; break;
            case 'Offs':
            case 'зач':	$color = '6'; break;
            case 'Cont':
            case 'кр':	$color = '7'; break;
            case 'Cour':
            case 'кп':	$color = '8'; break;
            case 'Add':
            case 'доп': $color = '9'; break;
            case 'Ind':
            case 'инд': $color = '10'; break;
            //case 'Exam':
            case 'гек': $color = '11'; break;
            //case 'Cont':
            case 'кнч': $color = '12'; break;
            case 'Cons':
            case 'конс': $color = '13'; break;
            case 'Ret':
            case 'пер': $color = '14'; break;
            default: $color = '';
        }
        return $color;
    }

    public static function getCurrentYearAndSem()
    {
        $month=8;
        if(isset(Yii::app()->params['month'])&&Yii::app()->params['month']!='')
            $month=(int)Yii::app()->params['month'];
        if (date('n') >= $month) {
            $year = date('Y');
            $sem  = 0;
        } else {
            $ps53 = PortalSettings::model()->findByPk(53)->ps2;
            if(!empty($ps53)){
                if(date('m-d')>=$ps53)
                {
                    $sem = 1;
                }else
                {
                    $sem  = 0;
                    $arr=explode('-',$ps53);
                    if(count($arr)==2) {
                        list($month,$day)=$arr;
                        if ((int)$month > 8)
                            $sem = 1;
                    }
                }
                $year = date('Y', strtotime('-1 year'));
            }else {
                $year = date('Y', strtotime('-1 year'));
                $sem = 1;
            }
        }

        return array($year, $sem);
    }

    public static function convertEducationType($sg4)
    {
        switch($sg4){
            case 0:	$type = tt('Дневная'); break;
            case 1:	$type = tt('Заочная'); break;
            case 2:	$type = tt('Вечерняя'); break;
            case 3:	$type = tt('Экстернат'); break;
            case 4:	$type = tt('Второе высшее'); break;
            default: $type='';
        }
        return $type;
    }

    public static function convertSem5($sem5)
    {
        switch($sem5){
            case 0:	$type = tt('Осенний'); break;
            case 1:	$type = tt('Весенний'); break;
            default: $type='';
        }
        return $type;
    }

    public static function showIcon($val)
    {
        $isPrint = stristr(Yii::app()->controller->action->id, 'print');
        if ($val == 1) {
            $symbol = ! $isPrint
                        ? '<i class="icon-ok green"></i>'
                        : '+';
        } else {
            $symbol = ! $isPrint
                        ? '<i class="icon-remove red"></i>'
                        : '-';
        }

        return $symbol;
    }

    public static function is($code)
    {
        return Yii::app()->params['code'] == $code;
    }

    public static function getServiceSeoSettings($controller, $action, $language)
    {
        $controller = mb_strtolower($controller);
        $action     = mb_strtolower($action);

        $settings = SH::getInstance()->getSeoSettings();

        parse_str(urldecode($settings), $menu);

        $title = isset($menu[$controller][$action]['title'.$language])
            ? $menu[$controller][$action]['title'.$language]
            : '';

        $description = isset($menu[$controller][$action]['description'.$language])
            ? $menu[$controller][$action]['description'.$language]
            : '';


        return array($title, $description);
    }

    public static function checkServiceFor($type, $controller, $action, $showAlert = false)
    {
        $controller = mb_strtolower($controller);
        $action     = mb_strtolower($action);

        $settings = SH::getInstance()->getMenuSettings();

        parse_str(urldecode($settings), $menu);

        $isVisible = isset($menu[$controller][$action][$type])
                       ? $menu[$controller][$action][$type]
                       : 1;

        $isVisible = (bool)$isVisible;

        if (! $isVisible && Yii::app()->user->isAdmin) {
            if ($showAlert)
                Yii::app()->user->setFlash('warning', tt('<strong>Внимание!</strong> Данный сервис закрыт администратором!'));
            return true;
        }


        return $isVisible;
    }

    public function getMenuSettings()
    {
        if (empty($this->_menuSettings)) {
            $file     = Yii::getPathOfAlias('application') . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'menu.txt';
            $settings = file_get_contents($file);
            $this->_menuSettings = $settings;
        }

        return $this->_menuSettings;
    }

    public function getSeoSettings()
    {
        if (empty($this->_seoSettings)) {
            $file     = Yii::getPathOfAlias('application') . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'seo.txt';
            if(file_exists($file)) {
                $settings = file_get_contents($file);
                $this->_seoSettings = $settings;
            }else
                return '';
        }

        return $this->_seoSettings;
    }

    /*public static function getBal()
    {
        $file     = Yii::getPathOfAlias('application') . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'bal.php';
        $arr=array();
        if(file_exists($file))
            $arr=require($file);
        return $arr;
    }*/

    public static function russianMonthName($month)
    {
        switch($month){
            case 1:	 $name=tt('Январь'); break;
            case 2:	 $name=tt('Февраль'); break;
            case 3:	 $name=tt('Март'); break;
            case 4:	 $name=tt('Апрель'); break;
            case 5:	 $name=tt('Май'); break;
            case 6:	 $name=tt('Июнь'); break;
            case 7:	 $name=tt('Июль'); break;
            case 8:	 $name=tt('Август'); break;
            case 9:	 $name=tt('Сентябрь'); break;
            case 10: $name=tt('Октябрь'); break;
            case 11: $name=tt('Ноябрь'); break;
            case 12: $name=tt('Декабрь'); break;
            default: $name='';
        }
        return $name;
    }
    
    public static function russianDayName($month)
    {
        switch($month){
            case 0:	 $name=tt('Вс'); break;
            case 1:	 $name=tt('Пн'); break;
            case 2:	 $name=tt('Вт'); break;
            case 3:	 $name=tt('Ср'); break;
            case 4:	 $name=tt('Чт'); break;
            case 5:	 $name=tt('Пт'); break;
            case 6:	 $name=tt('Сб'); break;
            case 7:	 $name=tt('Вс'); break;
            default: $name='';
        }
        return $name;
    }
}

class SH extends ShortCodes
{
    const MOBILE_URL = 'https://play.google.com/store/apps/details?id=com.mkr.shedule_app';

    const MOBILE_URL_APPLE = 'https://itunes.apple.com/us/app/%D1%80%D0%B0%D1%81%D0%BF%D0%B8%D1%81%D0%B0%D0%BD%D0%B8%D0%B5-%D0%B7%D0%B0%D0%BD%D1%8F%D1%82%D0%B8%D0%B9-%D0%BC%D0%BA%D1%80/id1382175045?l=uk&ls=1&mt=8';

    const MOBILE_URL_INSTRUCTION = 'https://new.mkr.org.ua/mobile-instruction.html';

    protected static $_instance;

    private function __construct(){}

    private function __clone(){}

    public static function getInstance() {

        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public static function user_browser($agent) {
        preg_match("/(MSIE|Opera|Firefox|Chrome|Version|Opera Mini|Netscape|Konqueror|SeaMonkey|Camino|Minefield|Iceweasel|K-Meleon|Maxthon)(?:\/| )([0-9.]+)/", $agent, $browser_info);
        list(,$browser,$version) = $browser_info;
        if (preg_match("/Opera ([0-9.]+)/i", $agent, $opera)) return 'Opera '.$opera[1];
        if ($browser == 'MSIE') {
            preg_match("/(Maxthon|Avant Browser|MyIE2)/i", $agent, $ie);
            if ($ie) return $ie[1].' based on IE '.$version;
            return 'IE '.$version;
        }
        if ($browser == 'Firefox') {
            preg_match("/(Flock|Navigator|Epiphany)\/([0-9.]+)/", $agent, $ff);
            if ($ff) return $ff[1].' '.$ff[2];
        }
        if ($browser == 'Opera' && $version == '9.80') return 'Opera '.substr($agent,-5);
        if ($browser == 'Version') return 'Safari '.$version;
        if (!$browser && strpos($agent, 'Gecko')) return 'Browser based on Gecko';
        return $browser.' '.$version;
    }

    /**
     * Конект к гарфической базе
     * @return CDbConnection
     */
    public static function getGrafConnection(){
        $string = Yii::app()->db->connectionString;
        $parts  = explode('=', $string);

        if(!isset($parts[1]))
            return null;

        $path = explode(';',$parts[1]);

        $host = trim($path[0]).'D';

        $newString = str_replace($path[0],$host,$string);
        //var_dump($newString);
        $login    = Yii::app()->db->username;
        $password = Yii::app()->db->password;

        $dbh = new CDbConnection($newString, $login, $password);

        return $dbh;
    }

    /**
     * Создать конектор к для подключения к дист образованию
     * @param int $universityCode
     * @param string $url
     * @param string $apiKey
     * @return DistEducation|null
     */
    private static function _getDiscEducation($universityCode, $url, $apiKey){
        switch ($universityCode){
            case U_ZSMU:
                return new EdxDistEducation($url, $apiKey);
                break;

            case U_NMU:
                return new MoodleDistEducation($url, $apiKey);
                break;

            case U_KNAME:
                return new MoodleDistEducation($url, $apiKey);
                break;

            case U_IRPEN:
                return new MoodleDistEducation($url, $apiKey);
                break;

            default:
                return null;
        }
    }

    /**
     * Создать форму к привязки к существующей учетке
     * @param int $universityCode
     * @return SingUpOldDistEducationForm|null
     */
    public static function getSingUpOldDistEducationForm($universityCode){
        switch ($universityCode){
            case U_ZSMU:
                return new EdxSignUpOldForm($universityCode);
                break;

            case U_NMU:
                return new MoodleSignUpOldForm($universityCode);
                break;

            case U_KNAME:
                return new MoodleSignUpOldForm($universityCode);
                break;

            case U_IRPEN:
                return new MoodleSignUpOldForm($universityCode);
                break;

            default:
                return null;
        }
    }

    /**
     * Создать конектор к для подключения к дист образованию
     * @param $universityCode int
     * @return DistEducation|null
     * @throws CHttpException if connector == null
     */
    public static function getDistEducationConnector($universityCode)
    {
        $connector = SH::_getDiscEducation(
            $universityCode,
            PortalSettings::model()->getSettingFor(PortalSettings::HOST_DIST_EDUCATION),
            PortalSettings::model()->getSettingFor(PortalSettings::APIKEY_DIST_EDUCATION)
        );

        if (empty($connector))
            throw new CHttpException(400, 'error create distEducation connector');

        return $connector;
    }

    /**
     * Поучить предедущий семестр
     *
     * @param $year int
     * @param $sem int
     * @return array
     */
    public static function getPrevSem($year, $sem){

        if($sem==1)
            $sem = 0;
        else {
            $year--;
            $sem=1;
        }

        return array($year, $sem);
    }
}




/**
 * Translates string to current language.
 * @see Yii::t()
 * @param string $str
 * @param array $params
 * @return string
 */
function tt($str, $params = array())
{
	return Yii::t('main', $str, $params);
}

