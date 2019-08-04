<?php
/**
 * Created by PhpStorm.
 * User: Lesha
 * Date: 05.07.2019
 * Time: 12:31
 */

/**
 * Class Core
 * @property-read $universityCode int Код вуза
 * @property-read $isMobile bool Являеться ли запрос от мобильного устровйсва
 * @property-read $defaultLanguage string Язік по умполчанию
 * @property-read $currentYear int текущий год
 * @property-read $currentSemester int текущий семестр
 */
class Core extends CComponent
{
    /**
     * Код вуза
     * @var int
     */
    private $_universityCode;

    /**
     * Являеться ли запрос от мобильного устровйсва
     * @var bool
     */
    private $_isMobile;

    /**
     * язык по умолчанию
     * @var string
     */
    private $_defaultLanguage;

    /**
     * Текуший год
     * @var int
     */
    private $_currentYear;

    /**
     * Текуший семестр
     * @var int
     */
    private $_currentSemester;


    /**
     * Инициализация
     * @throws CException
     */
    public function init(){
        $this->_universityCode = $this->_getUniversityCode();

        $detect = Yii::app()->mobileDetect;
        $this->_isMobile = $detect->isMobile()||$detect->isTablet();

        $this->_defaultLanguage = PortalSettings::model()->getSettingFor(58);
        if(empty($this->_defaultLanguage))
            $this->_defaultLanguage = 'ru';
        ELangPick::setLanguage();

        $this->_loadCurrentYearAndSemester();
    }

    /**
     * загрузка текущего года и семестра
     */
    private function _loadCurrentYearAndSemester()
    {

        $month=8;
        if(isset(Yii::app()->params['month'])&&Yii::app()->params['month']!='')
            $month=(int)Yii::app()->params['month'];
        if (date('n') >= $month) {
            $year = date('Y');
            $sem  = 0;
        } else {
            $ps53 = PortalSettings::model()->getSettingFor(53);
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

        $this->_currentYear = $year;
        $this->_currentSemester = $sem;
    }

    /**
     * Получить код Вуза
     * @return int
     * @throws
     */
    private function _getUniversityCode()
    {
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

    /**
     * Являеться ли запрос от мобильного устровйсва
     * @return bool
     */
    public function getIsMobile(){
        return $this->_isMobile;
    }

    /**
     * Язык по умолчанию
     * @return bool
     */
    public function getDefaultLanguage(){
        return $this->_defaultLanguage;
    }


    /**
     * Код вуза
     * @return int
     */
    public function getUniversityCode(){
        return $this->_universityCode;
    }


    /**
     * Текуший год
     * @return int
     */
    public function getCurrentYear(){
        return $this->_currentYear;
    }


    /**
     * Текущи1 семестр
     * @return int
     */
    public function getCurrentSemester(){
        return $this->_currentSemester;
    }
}