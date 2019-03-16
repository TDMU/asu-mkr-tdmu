<?php
/**
 * Created by PhpStorm.
 * User: NEFF
 * Date: 18.01.2018
 * Time: 16:47
 */

/**
 * Форма для фильтров по рейтингу
 * Class RatingForm
 */
class RatingForm extends CFormModel
{
    const SPECIALITY = 1;

    const GROUP = 2;
    const STUDENT = 3;
    const COURSE = 4;

    /**
     * @var int филиал
     */
    public $filial = 0;

    /**
     * @var int факульетт
     */
    public $faculty;

    /**
     * @var int курс
     */
    public $course = 0;

    /**
     * Код группы
     * @var int| null
     */
    public $group;

    /**
     * Код студента
     * @var int| null
     */
    public $student;

    /**
     * Тип студентов
     * @var int
     * @see getStudentsTypes
     */
    public $stType = 0;

    /**
     * Тип Рейтинга (0 - рейтинг группы) (1- рейтинг потока)
     * @var int
     */
    public $ratingType = 0;

    /**
     * @var int семестр с
     */
    public $semStart;

    /**
     * @var int семестр по
     */
    public $semEnd;

    /**
     * validation rules
     * @return array
     */
    public function rules()
    {
        return array(
            array('filial, faculty, course, group, semStart, semEnd, ratingType, stType, student', 'numerical'),
            array('stType, ratingType', 'default', 'value'=>0, 'setOnEmpty'=>TRUE),
            array('filial, faculty, course, group, semStart, semEnd, ratingType, stType', 'required'),
        );
    }

    /**
     * attribute labels
     * @return array
     */
    public function attributeLabels()
    {
        $arr= array(
            'filial'=> tt('Учебн. заведение'),
            'faculty'=> tt('Факультет'),
        );

        $universityCode = Yii::app()->controller->universityCode;

        if($universityCode==7)
            $arr = array(
                'filial' => tt('Факультет'),
                'faculty' => tt('Вид подготовки'),
            );
        elseif($universityCode==15)
            $arr = array(
                'filial' => tt('Факультет'),
                'faculty' => tt('Направление подготовки'),
            );
        elseif ($universityCode==42)
            $arr = array(
                'filial' => tt('Факультет'),
                'faculty' => tt('Направление'),
            );

        return array_merge(
            array(
                'group'=> tt('Группа'),
                'course' => tt('Курс'),
                'semStart' => tt('Семестр "с"'),
                'semEnd' => tt('Семестр "по"'),
                'ratingType'=>tt('Рейтинг потока'),
                'stType'=>tt('Тип студентов'),
            ),
            $arr
        );
    }

    /**
     * Список типов студентов
     * @return array
     */
    public static function getStudentsTypes(){
        return array(
            0=>tt('Все'),
            1=>tt('Не иностранцы'),
            2=>tt('Иностранцы'),
        );
    }

    /**
     * Спиок
     * @see group
     * @return array
     */
    public function getSemestersForFilter(){
        if(empty($this->group))
            return array();

         return CHtml::listData(Sem::model()->getSemestersForRating($this->group, self::GROUP ), 'sem7', 'sem7', 'name');
    }

    /**
     * Код потока по группе
     * @see group
     * @return int
     */
    private function getSg1ByGroup(){
        if(empty($this->group))
            return 0;

        $gr = Gr::model()->findByPk($this->group);

        if(empty($gr))
            return 0;

        return $gr->gr2;
    }

    /**
     * Получить таблицу для рейтинга
     * @param $type int
     * @return array
     */
    public function getRating($type){

        $extraWhere = $extraJoin = $extraColumns = '';

        $params = array(
            ':GR1' => 0,
            ':ST1' => 0,
            ':SG1' => 0,
            ':SEM_START' => $this->semStart,
            ':SEM_END' => $this->semEnd
        );

        if($this->stType>0) {
            $extraWhere = ' AND  proc.INOSTR=:INOSTR';
            $params['INOSTR'] = $this->stType-1;
        }

        switch ($type){
            case self::GROUP:
                $params[':GR1']= $this->group;
                $extraColumns = 'proc.st1, st.st2, st.st3, st.st4, proc.inostr, proc.KYRS, gr.gr3, gr.gr19, gr.gr20, gr.gr21, gr.gr22, gr.gr23, gr.gr24, gr.gr28, ';
                $extraJoin = ' INNER JOIN st on (proc.st1 = st.st1) INNER JOIN gr on (proc.gr1 = gr.gr1) ';
                break;
            case self::STUDENT:
                $params[':ST1']= $this->student;
                $extraColumns = 'd2, proc.kyrs, proc.sem7, proc.tip, ';
                $extraJoin = ' INNER JOIN d on (proc.d1 = d.d1) ';
                break;
            case self::COURSE:
                $sg1 = $this->getSg1ByGroup();
                $params[':SG1']= $sg1;
                $extraColumns = 'proc.st1, st.st2, st.st3, st.st4, proc.inostr, proc.KYRS, gr.gr3, gr.gr19, gr.gr20, gr.gr21, gr.gr22, gr.gr23, gr.gr24, gr.gr28,';
                $extraJoin = ' INNER JOIN st on (proc.st1 = st.st1) INNER JOIN gr on (proc.gr1 = gr.gr1) ';
                break;
            default:
                return array();
        }

        $extraJoin.= 'INNER JOIN uo on (proc.uo1 = uo.uo1 and uo28=0) ';

        $sql = <<<SQL
            SELECT {$extraColumns} proc.bal_5, proc.bal_100 FROM IZ_OC(:ST1, :SG1, :GR1, 0, 0, CURRENT_TIMESTAMP) proc
              {$extraJoin}
            WHERE  proc.sem7 BETWEEN :SEM_START and :SEM_END and  proc.tip not in (6,10) and proc.std11 in (0,5,6,8) and proc.ANALIZ = 1  {$extraWhere} ORDER BY  proc.sem7 desc
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValues($params);
        $rows = $command->queryAll();

        if($type == self::STUDENT)
            return $rows;

        $ps81 = PortalSettings::model()->getSettingFor(81);
        $is5 = $ps81==0;

        $stInfo = array();

        foreach ($rows as $row){
            $st1 = $row['st1'];

            if(!isset($stInfo[$st1])){
                $stInfo[$st1] = array(
                    'count' =>0,
                    'sym5' => 0,
                    'sym100' => 0,
                    'inostr'=> $row['inostr'],
                    'st2' => $row['st2'],
                    'st3' => $row['st3'],
                    'st4' => $row['st4'],
                    'group' => Gr::model()->getGroupName($this->course, $row)
                );
            }

            $stInfo[$st1]['count']++;
            $stInfo[$st1]['sym5']+=$row['bal_5'];
            $stInfo[$st1]['sym100']+=$row['bal_100'];

            /*if($row['bal_100']>0)
                $is5 = false;*/
        }

        $rating = array();

        foreach ($stInfo as $key => $st){

            $field = $is5 ? 'sym5' : 'sym100';

            $rating[]= array(
                'st1'=>$key,
                'stInfo'=>$st,
                'value'=> !empty($st['count']) ? $st[$field]/$st['count'] : 0
            );
        }

        uasort (
            $rating ,
            function ($a, $b) {
                if($a['value'] == $b['value']) {
                    return 0;
                }
                return ($a['value'] > $b['value']) ? -1 : 1;
            }
        );

        return $rating;
    }
}