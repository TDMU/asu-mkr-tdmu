<?php
/**
 * Created by PhpStorm.
 * User: NEFF
 * Date: 26.03.2018
 * Time: 13:57
 */

/**
 * Ведомость с дист образования
 * Class DistVedomost
 * @property $discipline int Код Дисциплина учебного плана
 * @property $group int Код группы
 * @property $courseId int Код курса
 * @property $groupId int Код группы в дист. образовании
 * @property $marks array Оценки
 */
class DistVedomost
{
    private $_uo1;
    private $_gr1;
    private $_courseId;
    private $_groupId;
    private $_module;

    private $_marks;

    /**
     * @return  array
     */
    public function getMarks(){
        return $this->_marks;
    }

    /**
     * @see discipline
     * @return int
     */
    public function getDiscipline(){
        return $this->_uo1;
    }

    /**
     * @see group
     * @return int
     */
    public function getGroup(){
        return $this->_uo1;
    }

    /**
     * @see groupId
     * @return int
     */
    public function getGroupId(){
        return $this->_groupId;
    }

    /**
     * @see courseId
     * @return int
     */
    public function getCourseId(){
        return $this->_courseId;
    }

    /**
     * @see courseId
     * @return int
     */
    public function getModuleNumber(){
        return $this->_module;
    }

    /**
     * DistVedomost constructor.
     * @param $uo1 int
     * @param $gr1 int
     * @param $module int
     * @throws Exception
     */
    public function __construct($uo1, $gr1, $module = 0)
    {
        if(empty($uo1) || empty($gr1))
            throw new Exception('Ошибка создания, пустые обязательные параметры');

        $this->_uo1 = $uo1;
        $this->_gr1 = $gr1;
        $this->_module = $module;

        $distDisp = DispDist::model()->findByPk($uo1);
        if($distDisp == null)
            throw new Exception('Дисциплина не приявзана');

        $this->_courseId = $distDisp->dispdist3;

        $distGroup = Distgr::model()->findByAttributes(array('distgr1'=>$gr1, 'distgr2'=>$uo1));
        if($distGroup != null)
            $this->_groupId = $distGroup->distgr3;

        $this->_marks = array();
    }

    /**
     * Добавить оценку в ведомость
     * @param $mark double
     * @param $st1 int
     */
    public function addMark($st1, $mark){
        $this->_marks[$st1] = $mark;
    }
}