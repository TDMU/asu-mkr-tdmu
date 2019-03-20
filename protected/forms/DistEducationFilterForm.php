<?php
/**
 * Created by PhpStorm.
 * User: NEFF
 * Date: 27.11.2017
 * Time: 17:07
 */

/**
 * Форма отображения спсика дисциплин
 * Class DistEducationFilterForm
 *
 * @property bool $isAdminDistEducation
 * @property null|WebUser $user
 * @property null|Grants $grants
 * @property null|K $chair
 * @property null|int $chairId
 */
class DistEducationFilterForm extends CFormModel
{
    /**
     * @var string для фильтра по названию дисциплины
     */
    public $d2;

    /**
     * @var int для фильтра по курсу
     */
    public $course;

    /**
     * @var int code дисциплины
     */
    public $discipline;


    /**
     * @var string для фильтра по названию специальности
     */
    public $sp2;
    /**
     * @var int для фильтра закрпелена или нет дисциплина
     */
    public $isSubscript;
    /**
     * @var string для фильтра по названию курса
     */
    public $dispdist2;



    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('discipline, isSubscript', 'numerical'),
            array('d2, sp2, dispdist2', 'length', 'max'=>255),
            array('course', 'numerical', 'max'=>6, 'min'=> 1),
        );
    }
    /**
     * Прльзователь
     * @var null|WebUser
     */
    private $_user = null;

    /**
     * Права пользователя
     * @var null|Grants
     */
    private $_grants = null;

    /**
     * @var null|K
     */
    private $_chair = null;


    /**
     * Геттер для права пользователя
     * @see $grants
     * @return null|Grants
     */
    public function getGrants(){
        return $this->_grants;
    }

    /**
     * Getter for Пользователь
     * @see $user
     * @return null|WebUser
     */
    public function getUser(){
        return $this->_user;
    }

    /**
     * Геттер для кафедры пользователя
     * @see $chair
     * @return null|K
     */
    public function getChair(){
        return $this->_chair;
    }

    /**
     * Геттер для проперти IsAdminDistEducation
     * если админские права для закрепления
     * @see $isAdminDistEducation
     * @return bool
     */
    public function getIsAdminDistEducation(){

        if($this->_user->isAdmin)
            return true;

        if(empty($this->_user) || empty($this->_grants))
            return false;
        return ($this->_grants->getGrantsFor(Grants::DIST_EDUCATION_ADMIN) == 1);
    }

    /**
     * Для администраторов можно изменить кафедру
     * @param $chairId
     */
    public function setChairId($chairId){
        if(!$this->isAdminDistEducation)
            return;

        if(!empty($chairId))
            if($chairId!=$this->chairId)
                $this->_chair = K::model()->findByPk($chairId);
    }

    /**
     * Геттер для Код кафедры
     * @see $chairId
     * @return null|int
     */
    public function getChairId(){
        return !empty($this->chair) ? $this->chair->k1 : null;
    }

    /**
     * DistEducationFilterForm constructor.
     * @param WebUser $user
     * @param string $scenario
     */
    public function __construct(WebUser $user, $scenario = '')
    {
        parent::__construct($scenario);

        $this->_setUser($user);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'd2' => tt('Дисциплина'),
            'sp2' => tt('Специальность'),
            'course'=>tt('Курс потока'),
        );
    }

    /**
     * @param $user WebUser
     */
    private function _setUser($user){
        if($user==null)
            return;

        $this->_user = $user;

        $this->_chair = $this->_user->dbModel->chair;

        $this->_grants = $this->_user->dbModel->grants;
    }

    /**
     * Список дисциплин для зарупеления с дист образование для кафедры
     * @param $subscription bool true если нужні уже привязаніе дсициплині
     * @return CSqlDataProvider|null
     */
    public function getDispListForDistEducation($subscription = false){

        $k1 = $this->chairId;

        if(empty($k1))
            return null;

        $params = array(
            ':K1' => $k1,
            ':YEAR' =>  Yii::app()->session['year'],
            ':SEM' =>  Yii::app()->session['sem']
        );

        $where = '';

        if(!empty($this->d2)) {
            $where .= " AND d2 CONTAINING :D2 ";
            $params[':D2'] = $this->d2;
        }

        if(!empty($this->sp2)) {
            $where .= " AND sp2 CONTAINING :SP2 ";
            $params[':SP2'] = $this->sp2;
        }

        if(!empty($this->course)) {
            $where .= " AND sem4 = :COURSE ";
            $params[':COURSE'] = $this->course;
        }

        if(!$subscription)
            if(!empty($this->isSubscript)) {
                if($this->isSubscript==1)
                    $where .= "AND dispdist2 is not null ";
                else
                    $where .= "AND dispdist2 is null ";
            }

        if(!empty($this->dispdist2)) {
            $where .= " AND dispdist2 CONTAINING :COURSE_NAME ";
            $params[':COURSE_NAME'] = $this->dispdist2;
        }

        if($subscription){
            $where .= " AND dispdist2 is not null ";
        }

        $sql = <<<SQL
          SELECT d2,d1,uo1,sem4,sp2, dispdist2, dispdist3, uo4
            FROM us
            INNER JOIN uo ON (US.US2 = UO.UO1)
            inner join u on (uo.uo22 = u.u1)
            inner join sg on (u.u2 = sg.sg1)
            inner join sp on (sg2 = sp1)
            INNER JOIN d ON (UO.uo3 = D.D1)
            INNER JOIN k ON (UO.uo4 = K.K1)
            INNER JOIN sem ON (Us.us3 = sem.sem1)
            LEFT JOIN dispdist on (uo.uo1 = dispdist1)
        WHERE uo4 =:K1 and sem3=:YEAR and sem5=:SEM {$where}
            group BY d2,d1,uo1,sem4,sp2, dispdist2, dispdist3, uo4
SQL;

        $countSQL =
            'SELECT COUNT(*) FROM (' .
                $sql.
            ') ';

        $command=Yii::app()->db->createCommand($countSQL);
        $command->params = $params;
        $count = $command->queryScalar();

        $sort = new CSort();
        $sort->sortVar = 'sort';
        $sort->defaultOrder = 'd2 ASC';
        $sort->attributes = array(
            'd2'=>array(
                'asc'=>'d2 ASC',
                'desc'=>'d2 DESC',
                'default'=>'ASC',
            ),
            'sp2'=>array(
                'asc'=>'sp2 ASC',
                'desc'=>'sp2 DESC',
                'default'=>'ASC',
            ),
            'course'=>array(
                'asc'=>'sem4 ASC',
                'desc'=>'sem4 DESC',
                'default'=>'ASC',
            ),
            'dispdist2'=>array(
                'asc'=>'dispdist2 ASC',
                'desc'=>'dispdist2 DESC',
                'default'=>'ASC',
            ),
        );

        $pageSize=Yii::app()->user->getState('pageSize',10);
        if($pageSize==0)
            $pageSize=10;

        return new CSqlDataProvider($sql, array(
            'keyField' => 'uo1',
            'totalItemCount'=>$count,
            'params'=>$params,
            'sort'=>$sort,
            'pagination'=> $subscription ? false : array(
                'pageSize'=> $pageSize,
            ),
        ));
    }

    /**
     * Поучить инфо по дисциплине с проверкой доступа $uo1
     * @param $uo1
     * @return array|null
     */
    public function getDispInfo($uo1){
        if(empty($uo1))
            return null;

        $where = '' ;
        $params = array(
            ':UO1' => $uo1,
            ':YEAR' =>  Yii::app()->session['year'],
            ':SEM' =>  Yii::app()->session['sem']
        );

        if(!$this->isAdminDistEducation){
            $where = 'and uo4 =:K1';
            $params[':K1']= $this->chairId;
        }

        $sql = <<<SQL
          SELECT d2,d1,uo1,sem4,sp2, dispdist2, dispdist3, uo4
            FROM us
            INNER JOIN uo ON (US.US2 = UO.UO1)
            inner join u on (uo.uo22 = u.u1)
            inner join sg on (u.u2 = sg.sg1)
            inner join sp on (sg2 = sp1)
            INNER JOIN d ON (UO.uo3 = D.D1)
            INNER JOIN k ON (UO.uo4 = K.K1)
            INNER JOIN sem ON (Us.us3 = sem.sem1)
            LEFT JOIN dispdist on (uo.uo1 = dispdist1)
        WHERE sem3=:YEAR and sem5=:SEM and uo1=:UO1 {$where}
            group BY d2,d1,uo1,sem4,sp2, dispdist2, dispdist3, uo4
SQL;

        $command=Yii::app()->db->createCommand($sql);
        $command->params = $params;
        $disp = $command->queryRow();

        return empty($disp)? null : $disp;
    }

    /**
     * @return Kdist|null
     */
    public function getKdist(){
        return Kdist::model()->findByAttributes(array(
            'kdist1'=>$this->chairId,
            'kdist2'=>Yii::app()->session['year'],
            'kdist3'=>Yii::app()->session['sem']
        ));
    }

    /**
     * Список Групп по дисциплине учебного плана
     * @param $uo1
     * @param $gr1 int|null
     * @return array|null
     */
    public function getGroupsByUo1($uo1, $gr1 = null){
        $where = '';
        if(!empty($gr1))
            $where = ' AND gr1=:GR1';

        $sql = <<<SQL
          select
            gr1, gr3, sem4,gr19,gr20,gr21,gr22,gr23,gr24,gr28
          from gr
            inner join ucgn on (gr.gr1 = ucgn.ucgn2)
			inner join ucgns on (ucgn.ucgn1 = ucgns.ucgns2 and (UCGNS3>0 or UCGNS4>0))
            inner join ucxg on (ucgn.ucgn1 = ucxg.ucxg2)
            inner join ucx on (ucxg.ucxg1 = ucx.ucx1)
            inner join uo on (ucx.ucx1 = uo.uo19)
            inner join us on (UO.UO1 = US.US2)
			INNER JOIN sem ON (Us.us3 = sem.sem1)
          WHERE uo1=:UO1 and gr13=0 and UCGNS5=:YEAR and UCGNS6=:SEM  $where
          GROUP BY gr1, gr3, sem4,gr19,gr20,gr21,gr22,gr23,gr24,gr28
SQL;

        $params = array(
            ':UO1' => $uo1,
            ':YEAR'=>Yii::app()->session['year'],
            ':SEM'=>Yii::app()->session['sem']
        );

        if(!empty($gr1))
            $params[':GR1'] = $gr1;

        $command=Yii::app()->db->createCommand($sql);
        $command->params = $params;
        $rows = (!empty($gr1))? $command->queryRow() : $command->queryAll();

        return $rows;
    }
}