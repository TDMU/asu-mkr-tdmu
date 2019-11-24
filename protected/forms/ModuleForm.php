<?php

/**
 * Class ModuleForm
 */
class ModuleForm extends CFormModel
{
    /**
     * @var int
     */
    public $discipline;

    /**
     * @var int
     */
    public $group;

    /**
     * @var int
     */
    public $countModules = 0;

    /**
     * Код преподователя
     * @var int
     */
    protected $_teacherId;

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('discipline, countModules', 'numerical'),
            array('discipline, group', 'required'),
            /**
             * @see validateGroup()
             */
            array('group', 'validateGroup')
        );
    }

    /**
     * ModuleForm constructor.
     * @param $teacherId
     * @param string $scenario
     */
    public function __construct($teacherId, $scenario = '')
    {
        parent::__construct($scenario);
        $this->_teacherId = $teacherId;
    }

    /**
     *
     */
    public function validateGroup(){
        try{
            list($us1, $group) = $this->getGroupParams();
        }catch (Exception $error){
            $this->addError('group', tt('Не верное значение'));
            return false;
        }

        if(empty(Us::model()->findByPk($us1))) {
            $this->addError('group', tt('Не верное значение'));
            return false;
        }

        if(empty(Gr::model()->findByPk($group))) {
            $this->addError('group', tt('Не верное значение'));
            return false;
        }
    }

    /**
     * attribute labels
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'discipline'=>tt('Дисциплина'),
            'group'=>tt('Группа'),
            'countModules'=>tt('Количество модулей'),
        );
    }

    /**
     * саисок дисиплин преподователя для ввода модульного окнтпроля
     * @return array
     * @throws CException
     */
    public function getDisciplines(){
        $sql = <<<SQL
            select d2,d1
                from sem
                   inner join us on (sem.sem1 = us.us3)
                   inner join nr on (us.us1 = nr.nr2)
                   inner join pd on (nr.nr6 = pd.pd1 and pd2=:P1)
                   inner join uo on (us.us2 = uo.uo1)
                   inner join d on (uo.uo3 = d.d1)
                where us4 in (5,6,8) and d8=0 and sem3=:YEAR and sem5=:SEM
                group by d2,d1
                order by d2 collate UNICODE
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':P1', $this->_teacherId);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
        return $command->queryAll();
    }

    /**
     * Список групп по дисциплине
     * @param $discipline
     * @return array
     * @throws CException
     */
    public function getGroups($discipline){
        if(empty($discipline))
            return array();

        $sql = <<<SQL
            select sg4,gr7,gr3,gr1,us1,uo1
            from sg
               inner join gr on (sg.sg1 = gr.gr2)
               inner join ug on (gr.gr1 = ug.ug2)
               inner join nr on (ug.ug1 = nr.nr1)
               inner join us on (nr.nr2 = us.us1)
               inner join sem on (us.us3 = sem.sem1)
               inner join uo on (us.us2 = uo.uo1)
               inner join pd on (nr.nr6 = pd.pd1 and pd2=:P1)
            where us4 in (5,6,8) and sem3=:YEAR and sem5=:SEM and uo3=:D1
            group by sg4,gr7,gr3,gr1,us1,uo1
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':P1', $this->_teacherId);
        $command->bindValue(':D1', $discipline);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
        $rows = $command->queryAll();

        $result = array();
        foreach ($rows as $row){
            $result[$row['us1'].'/'.$row['gr1']] = $row['gr3'];
        }
        return $result;
    }

    /**
     * Проверка доступа к ведомостям
     * @return bool
     */
    public function checkAccess(){
        list($us1, $group) = $this->getGroupParams();
        if(empty($us1) || empty($group))
            return false;
        $sql = <<<SQL
            select count(*)
            from sg
               inner join gr on (sg.sg1 = gr.gr2)
               inner join ug on (gr.gr1 = ug.ug2)
               inner join nr on (ug.ug1 = nr.nr1)
               inner join us on (nr.nr2 = us.us1)
               inner join sem on (us.us3 = sem.sem1)
               inner join uo on (us.us2 = uo.uo1)
               inner join pd on (nr.nr6 = pd.pd1 and pd2=:P1)
            where us4 in (5,6,8) and sem3=:YEAR and sem5=:SEM and uo3=:D1 and us1=:US1 and gr1=:GR1
            group by sg4,gr7,gr3,gr1,us1,uo1
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':P1', $this->_teacherId);
        $command->bindValue(':D1', $this->discipline);
        $command->bindValue(':GR1', $group);
        $command->bindValue(':US1', $us1);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
        return (int)$command->queryScalar()>0;
    }

    /**
     * Количество модулей
     * @return int
     * @throws CException
     */
    public function getCountModules()
    {
        if(empty($this->group))
            return 0;

        list($us1, $group) = $this->getGroupParams();

        $sql = <<<SQL
            SELECT COUNT(*) FROM MOD INNER JOIN MODGR on (MODGR2 = MOD1) WHERE MOD2 = :US1 and MODGR3 = :GR1 AND MOD3=0
SQL;
        return (int) Yii::app()->db->createCommand($sql)->queryScalar(array(
            ':US1' => $us1,
            ':GR1' => $group
        ));
    }

    /**
     *
     * @return array
     */
    public function getGroupParams(){
        if(empty($this->group))
            return array(null, null);
        return explode('/', $this->group);
    }

    /**
     * Список модулей
     * @return Modgr[]
     */
    public function getModules(){
        if(empty($this->group))
            return array();

        list($us1, $group) = $this->getGroupParams();

        $modules = Modgr::model()->findAllBySql(<<<SQL
            SELECT modgr.* from modgr join mod on (modgr2 = mod1) where mod2=:US1 and modgr3=:GR1
SQL
        , array(
            ':US1' => $us1,
            ':GR1' => $group
        ));

        $result = [];
        foreach ($modules as $module){
            $result[$module->modgr1] = $module;
        }
        return $result;
    }

    /**
     * @throws CException
     * @throws CHttpException
     */
    public function createModules()
    {
        if(empty($this->group))
            throw new CHttpException(400);

        list($us1, $group) = $this->getGroupParams();

        $modules = Mod::model()->findAllByAttributes(array('mod2' =>$us1));

        $pd1 = $this->_getPd1();

        $trans = Yii::app()->db->beginTransaction();

        try {
            if (empty($modules)) {
                $countModules = $this->countModules+1;
                $modules = array();
                for ($i = 1; $i <= $countModules; $i++) {
                    $mod = new Mod();
                    $mod->mod1 = Yii::app()->db->createCommand('select gen_id(GEN_MOD, 1) from rdb$database')->queryScalar();
                    $mod->mod2 = $us1;
                    $mod->mod3 = $i == $countModules ? 1 : 0;
                    $mod->mod4 = $i;
                    $mod->mod5 = $i == $countModules ? 'Итоговый модуль' :'Модуль №'.$i;
                    $mod->mod7 = null;
                    $mod->mod8 = null;
                    if(!$mod->save())
                        throw new Exception('Ошибка добавления модуля');
                    $modules[] = $mod;
                }


            }

            foreach ($modules as $module){
                if(!empty(Modgr::model()->findByAttributes(array('modgr2'=> $us1, 'modgr3' => $group))))
                    continue;

                $modgr = new Modgr();
                $modgr->modgr1 = Yii::app()->db->createCommand('select gen_id(GEN_MODGR, 1) from rdb$database')->queryScalar();
                $modgr->modgr2 = $module->mod1;
                $modgr->modgr3 = $group;
                $modgr->modgr4 = $pd1;
                $modgr->modgr5 = 0;

                if(!$modgr->save())
                    throw new Exception('Ошибка добавления модуля #'.$module->mod1);
            }

            $trans->commit();
        }catch (Exception $error){
            $trans->rollback();
            throw $error;
        }
    }

    /**
     * @return int
     * @throws CException
     */
    private function _getPd1(){
        list($us1, $group) = $this->getGroupParams();

        $sql = <<<SQL
            select first 1 nr6
            from sg
               inner join gr on (sg.sg1 = gr.gr2)
               inner join ug on (gr.gr1 = ug.ug2)
               inner join nr on (ug.ug1 = nr.nr1)
               inner join us on (nr.nr2 = us.us1)
               inner join sem on (us.us3 = sem.sem1)
               inner join uo on (us.us2 = uo.uo1)
               inner join pd on (nr.nr6 = pd.pd1 and pd2=:P1)
            where us4 in (5,6,8) and sem3=:YEAR and sem5=:SEM and uo3=:D1 and us1=:US1 and gr1=:GR1
SQL;

        return (int) Yii::app()->db->createCommand($sql)->queryScalar(array(
            ':US1' => $us1,
            ':GR1' => $group,
            ':P1' => $this->_teacherId,
            ':D1' => $this->discipline,
            ':SEM' => Yii::app()->session['sem'],
            ':YEAR' => Yii::app()->session['year']
        ));
    }

    /**
     * Список студентов
     * @return St[]
     */
    public function getStudents(){

        list($us1, $group) = $this->getGroupParams();
        $us = Us::model()->findByPk($us1);

        $sql = <<<SQL
            select st2, st3, st4, st.st1, st5 from LISTST(current_timestamp,:YEAR,:SEM,5,0,0,0,:UO1,0)
                INNER JOIN st on (st.st1 = LISTST.st1)
            WHERE gr1=:GR1 ORDER BY st2 COLLATE UNICODE
SQL;
        return St::model()->findAllBySql($sql,array(
            ':UO1' => $us->us2,
            ':GR1' => $group,
            ':SEM' => Yii::app()->session['sem'],
            ':YEAR' => Yii::app()->session['year']
        ));
    }

    /**
     * Проверка доступа к модулю
     * @param $modgr1
     * @return bool
     * @throws CException
     */
    public function checkAccessForModule($modgr1){
        $sql = <<<SQL
            select COUNT(*)
            from sg
               inner join gr on (sg.sg1 = gr.gr2)
               inner join ug on (gr.gr1 = ug.ug2)
               inner join nr on (ug.ug1 = nr.nr1)
               inner join us on (nr.nr2 = us.us1)
               inner join sem on (us.us3 = sem.sem1)
               inner join uo on (us.us2 = uo.uo1)
               inner join pd on (nr.nr6 = pd.pd1 and pd2=:P1)
               inner join mod on (mod.mod2 = us.us1)
               inner join modgr on (modgr.modgr2 = mod.mod1 and modgr.modgr3=gr.gr1)
            where us4 in (5,6,8) and sem3=:YEAR and sem5=:SEM and modgr1=:MODULE
SQL;

        return (int) Yii::app()->db->createCommand($sql)->queryScalar(array(
            ':P1' => $this->_teacherId,
            ':MODULE' => $modgr1,
            ':SEM' => Yii::app()->session['sem'],
            ':YEAR' => Yii::app()->session['year']
        )) > 0;
    }

    /**
     * Проверка доступа к модулю
     * @param $mod1
     * @return bool
     * @throws CException
     */
    public function checkAccessForMod($mod1){
        $sql = <<<SQL
            select COUNT(*)
            from sg
               inner join gr on (sg.sg1 = gr.gr2)
               inner join ug on (gr.gr1 = ug.ug2)
               inner join nr on (ug.ug1 = nr.nr1)
               inner join us on (nr.nr2 = us.us1)
               inner join sem on (us.us3 = sem.sem1)
               inner join uo on (us.us2 = uo.uo1)
               inner join pd on (nr.nr6 = pd.pd1 and pd2=:P1)
               inner join mod on (mod.mod2 = us.us1)
            where us4 in (5,6,8) and sem3=:YEAR and sem5=:SEM and mod1=:MODULE
SQL;

        return (int) Yii::app()->db->createCommand($sql)->queryScalar(array(
                ':P1' => $this->_teacherId,
                ':MODULE' => $mod1,
                ':SEM' => Yii::app()->session['sem'],
                ':YEAR' => Yii::app()->session['year']
            )) > 0;
    }

    /**
     * Оценки студента
     * @param $st1
     * @return Mods[]
     */
    public function getModuleMarks($st1){
        list($us1, $group) = $this->getGroupParams();

        $sql = <<<SQL
            select mods.*
            from mod
               inner join modgr on (modgr.modgr2 = mod.mod1 and modgr.modgr3=:GR1)
               inner join mods on (mods1 = modgr1 and mods2=:ST1)
               inner join us on (mod2 = us.us1)
               inner join sem on (us.us3 = sem.sem1)
            where us4 in (5,6,8) and sem3=:YEAR and sem5=:SEM and mod2=:US1
SQL;
        $list = Mods::model()->findAllBySql($sql,array(
            ':US1' => $us1,
            ':ST1' => $st1,
            ':GR1' => $group,
            ':SEM' => Yii::app()->session['sem'],
            ':YEAR' => Yii::app()->session['year']
        ));

        $result = array();

        foreach ($list as $mods){
            $result[$mods->mods1] = $mods;
        }

        return $result;
    }
}