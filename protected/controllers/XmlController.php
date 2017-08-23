<?php

class XmlController extends Controller
{
    const VERSION = '0.0.3';

    const FORMAT_DATE = 'd.m.Y'; //09.08.2016- формат дат

    const ERROR_NOT_POST = 101; //ошибка если не пост запрос
    const ERROR_EMPTY_POST = 102; //ошибка если пост параментры пусты
    const ERROR_XML = 103; //ошибка если в потс параметре передаеться не хмл
    const ERROR_XML_STRUCTURE = 104; //ошибка если отсутсвют парамерты обязательные хмл
    const ERROR_PARAM = 105; //ошибка если парамтрры не валидные
    const ERROR_EMPTY_TIMETABLE = 106; //ошибка расписание пустое
    const ERROR_INVALID_LOGIN_OR_PASSWORD = 107; //не правльный логин или пароль

    /*Спецефические коды ошибок*/
        /*методы получения информации по пользователю*/
    const ERROR_NOT_FOUND_PERSON = 201;/*Не найден человек по задному критерию*/
    const ERROR_FOUND_MANY_PERSON = 202;/*Найдены несколько человек по задному критерию*/

    public $layout = '/xml/layout';

    /*расписание*/
    const VIEW_STUDENT = 1;
    const VIEW_TEACHER = 2;
    const VIEW_GROUP = 3;
    const VIEW_AUDIENCE = 4;
    /*Персона*/
    const PERSON_BY_INN = 5;
    const PERSON_BY_PASSPORT = 6;

    public function filters() {

        return array(
            'accessControl',
            'checkQueryType'
        );
    }

    public function accessRules() {

        return array(
            array('allow',
                'actions' => array(
                    'GetVersion',

                    'GetTimetableForStudent',
                    'GetTimetableForGroup',
                    'GetTimetableForTeacher',
                    'GetTimetableForAudience',

                    'UploadStudentsId',
                    'UploadTeachersId',

                    'GetChairs',
                    'GetFaculties',
                    'GetGroups',
                    'GetAudiences',
                    'GetTeachers',

                    'GetPersonByINN',
                    'GetPersonByPassport',
                    'GetStudentPerson',
                    'GetTeacherPerson',

                    'GetJournalMark'
                ),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }
    /*очищение скриптов итд*/
    private function clearScriptFiles(){
        Yii::app()->clientscript->reset();
        //Yii::app()->clientscript->scriptFiles=array();
    }
    /*возврат ошибка*/
    private function errorXml($code, $message){

        $this->render('error',array(
            'code'=>$code,
            'message'=>$message
        ),false);
        Yii::app()->end();
    }
    /*Возврщает хмл из пост запроса*/
    private function getXmlFromPost(){
        $params = trim(file_get_contents('php://input'));

        libxml_use_internal_errors(true);
        $xmlData = simplexml_load_string($params);


        if($xmlData !== false)
        {
            return $xmlData;
        }
        else
        {
            $message = '';
            foreach(libxml_get_errors() as $error)
            {
                $message.='Error parsing XML : ' . $error->message.PHP_EOL;
            }

            $message = htmlspecialchars($message, ENT_XML1, 'UTF-8');
            $this->errorXml(self::ERROR_XML,$message);
            return '';
        }

    }

    public function filterCheckQueryType($filter)
    {
        $this->clearScriptFiles();

        if (! Yii::app()->request->isPostRequest){
            $this->errorXml(self::ERROR_NOT_POST,'Поддерживаються только Post запросы');
        }else {
            //$params = Yii::app()->request->getPost();
            $params = trim(file_get_contents('php://input'));
            //$params = $_POST;
            if(empty($params)){
                $this->errorXml(self::ERROR_EMPTY_POST,'Пустой Post запрос');
            }else
                $filter->run();
        }
    }
    /**
     * Версия
     */
    public function actionGetVersion(){
        $xml = $this->getXmlFromPost();
        if(empty($xml))
            Yii::app()->end();
        else{
            if($xml->getName()!='Request'||!isset($xml->GetVersion))
                $this->errorXml(self::ERROR_XML_STRUCTURE,'Ошибка струтуры xml');
            else {
                $this->render('version',array(
                    'version'=>self::VERSION
                ));
            }
        }
    }
    /**
     * Оценки по журналу
     */
    public function actionGetJournalMark(){

        ini_set("memory_limit", "128M");

        $xml = $this->getXmlFromPost();
        if(empty($xml))
            Yii::app()->end();
        else{
            /*Проверка есть ли тег GetPersonByINN*/
            if($xml->getName()!='Request'||!isset($xml->GetJournalMark))
                $this->errorXml(self::ERROR_XML_STRUCTURE,'Ошибка струтуры xml');
            else {
                $xmlAction = $xml->GetJournalMark;
                /*Проверка есть ли теги нужные параметры*/
                if (
                    !isset($xmlAction->Faculty) ||
                    !isset($xmlAction->Year) ||
                    !isset($xmlAction->Semestr) ||
                    !isset($xmlAction->PeriodStart) ||
                    !isset($xmlAction->PeriodFinish)||
                    !isset($xmlAction->Login)||
                    !isset($xmlAction->Password)
                )
                    $this->errorXml(self::ERROR_XML_STRUCTURE, 'Ошибка струтуры(параметры) xml');
                else {
                    $this->checkAccess($xmlAction);

                    /*загрузка параментров*/
                    $Faculty = $xmlAction->Faculty->__ToString();
                    $Semestr = $xmlAction->Semestr->__ToString();
                    $Year = $xmlAction->Year->__ToString();
                    //print_r($StudentID);
                    $PeriodStart = $xmlAction->PeriodStart->__ToString();
                    $PeriodFinish = $xmlAction->PeriodFinish->__ToString();

                    /*фильтры*/
                    $chair = null;
                    $student = null;
                    $course = null;
                    $group = null;
                    /*перебираем всех наследников ищем наши фильтрі*/
                    foreach($xmlAction->children() as $child){
                        /* @var $child SimpleXMLElement */

                        $tag = $child->getName();

                        switch($tag){
                            case 'Chair':
                                $chair = $child->__ToString();
                                break;
                            case 'Student':
                                $student = $child->__ToString();
                                break;
                            case 'Group':
                                $group = $child->__ToString();
                                break;
                            case 'Course':
                                $course = $child->__ToString();
                                break;
                        }
                    }

                    $where = '';

                    if($chair!=null){
                        $where.=' AND k1=:CHAIR';
                    }

                    if($student!=null){
                        $where.=' AND st1=:STUDENT';
                    }

                    if($course!=null){
                        $where.=' AND sem4=:COURSE';
                    }

                    if($group!=null){
                        $where.=' AND gr1=:GROUP';
                    }

                    $dateStart = date_create($PeriodStart);
                    if ($dateStart === false)
                        $this->errorXml(self::ERROR_PARAM, 'PeriodStart не являеться датой');

                    $dateFinish = date_create($PeriodFinish);
                    if ($dateFinish === false)
                        $this->errorXml(self::ERROR_PARAM, 'PeriodFinish не являеться датой');

                    $faculty = F::model()->findByAttributes(array('f1'=>$Faculty));
                    if($faculty==null)
                        $this->errorXml(self::ERROR_PARAM, 'Faculty '.$Faculty.' не являеться валидным');

                    $sql = <<<SQL
                    SELECT elgzst3, elgzst4, elgzst5,uo3, r2, us4, nr30, st1, st108, gr1, sp1, sem4
                    FROM elgzst
                         inner join st ON (elgzst.elgzst1=st.st1)
                         inner join std ON (st.st1=std.std2)
                         inner join gr ON (std.std3=gr.gr1)
                         inner join elgz on (elgzst.elgzst2 = elgz.elgz1)
                         inner join elg on (elgz.elgz2 = elg.elg1)
                         inner join uo ON (elg.elg2=uo.uo1)
                         inner join sg ON (gr.gr2=sg.sg1)
                         inner join sp ON (sg.sg2=sp.sp1)
                         inner join sem on (elg.elg3 = sem.sem1 AND sem3=:YEAR and sem5=:SEM)
                         inner join EL_GURNAL_ZAN(elg.elg2,gr1,elg.elg3, elg.elg4) on (elgz.elgz3 = EL_GURNAL_ZAN.nom AND EL_GURNAL_ZAN.r2>=:DATE_1 and EL_GURNAL_ZAN.r2<=:DATE_2)
                         inner join nr on (r1 = nr1)
                    WHERE sp5=:FACULTY AND  std11 in (0, 5, 6, 8) and std7 is null {$where}
SQL;
                    $command = Yii::app()->db->createCommand($sql);

                    $command->bindValue(':STUDENT', $student);
                    $command->bindValue(':COURSE', $course);
                    $command->bindValue(':CHAIR', $chair);
                    $command->bindValue(':GROUP', $group);

                    $command->bindValue(':FACULTY', $Faculty);
                    $command->bindValue(':YEAR', $Year);
                    $command->bindValue(':SEM', $Semestr);
                    $command->bindValue(':DATE_1', $dateStart->format(self::FORMAT_DATE));
                    $command->bindValue(':DATE_2', $dateFinish->format(self::FORMAT_DATE));
                    $rows = $command->queryAll();

                    $this->render('journalMark',array(
                        'faculty'=>$faculty,
                        'rows'=>$rows
                    ));
                }
            }
        }
    }
    /**
     * Персона студента
     */
    public function actionGetStudentPerson(){
        $xml = $this->getXmlFromPost();
        if(empty($xml))
            Yii::app()->end();
        else{
            /*Проверка есть ли тег GetStudentPerson*/
            if($xml->getName()!='Request'||!isset($xml->GetStudentPerson))
                $this->errorXml(self::ERROR_XML_STRUCTURE,'Ошибка струтуры xml');
            else {
                $xmlAction = $xml->GetStudentPerson;
                /*Проверка есть ли теги нужные параметры*/

                if (!isset($xmlAction->StudentID)||!isset($xmlAction->Login)||!isset($xmlAction->Password))
                    $this->errorXml(self::ERROR_XML_STRUCTURE, 'Ошибка струтуры(параметры) xml');
                else {

                    $this->checkAccess($xmlAction);

                    $id = $xmlAction->StudentID->__ToString();
                    $type = 0;
                    if(isset($xmlAction->Type))
                        $type = $xmlAction->Type->__ToString();

                    if($type==0){
                        $student = St::model()->findByAttributes(array('st108'=>$id));
                    }else{
                        $student = St::model()->findByAttributes(array('st1'=>$id));
                    }

                    if($student==null)
                        $this->errorXml(self::ERROR_PARAM, 'StudentID '.$id.' не являеться валидным');

                    $this->render('personStudent',array(
                        'student'=>$student
                    ));
                }
            }
        }
    }
    /**
     * Персона преподователя
     */
    public function actionGetTeacherPerson(){
        $xml = $this->getXmlFromPost();
        if(empty($xml))
            Yii::app()->end();
        else{
            /*Проверка есть ли тег GetTeacherPerson*/
            if($xml->getName()!='Request'||!isset($xml->GetTeacherPerson))
                $this->errorXml(self::ERROR_XML_STRUCTURE,'Ошибка струтуры xml');
            else {
                $xmlAction = $xml->GetTeacherPerson;
                /*Проверка есть ли теги нужные параметры*/

                if (!isset($xmlAction->TeacherID)||!isset($xmlAction->Login)||!isset($xmlAction->Password))
                    $this->errorXml(self::ERROR_XML_STRUCTURE, 'Ошибка струтуры(параметры) xml');
                else {

                    $this->checkAccess($xmlAction);

                    $id = $xmlAction->TeacherID->__ToString();
                    $type = 0;
                    if(isset($xmlAction->Type))
                        $type = $xmlAction->Type->__ToString();

                    if($type==0){
                        $teacher = P::model()->findByAttributes(array('p132'=>$id));
                    }else{
                        $teacher = P::model()->findByAttributes(array('p1'=>$id));
                    }

                    if($teacher==null)
                        $this->errorXml(self::ERROR_PARAM, 'TeacherID '.$id.' не являеться валидным');

                    $this->render('personTeacher',array(
                        'teacher'=>$teacher
                    ));
                }
            }
        }
    }
    /**
     * Персона по ИНН
     */
    public function actionGetPersonByINN(){
        $xml = $this->getXmlFromPost();
        if(empty($xml))
            Yii::app()->end();
        else{
            /*Проверка есть ли тег GetPersonByINN*/
            if($xml->getName()!='Request'||!isset($xml->GetPersonByINN))
                $this->errorXml(self::ERROR_XML_STRUCTURE,'Ошибка струтуры xml');
            else {
                $xmlAction = $xml->GetPersonByINN;
                /*Проверка есть ли теги нужные параметры*/

                if (!isset($xmlAction->INN)||!isset($xmlAction->Login)||!isset($xmlAction->Password))
                    $this->errorXml(self::ERROR_XML_STRUCTURE, 'Ошибка струтуры(параметры) xml');
                else {

                    $this->checkAccess($xmlAction);

                    $INN = $xmlAction->INN->__ToString();

                    $person = array();

                    $st = St::model()->findAll('st15=:ID ORDER BY st1 DESC', array(':ID'=>$INN));
                    $p  = P::model()->findAll('p13=:ID', array(':ID'=>$INN));

                    $thereIsNotSuchId = count($st) + count($p) == 0;
                    if ($thereIsNotSuchId)
                        $this->errorXml(self::ERROR_NOT_FOUND_PERSON, 'По заданому критерию людей не найдено!');

                    $thereIsDuplicate = count($st) + count($p) > 1;
                    if ($thereIsDuplicate)
                        $this->errorXml(self::ERROR_FOUND_MANY_PERSON, 'По заданому критерию найдено несколько людей!');

                    if($p){
                        $person['type'] = 1;
                        $person['firstName'] = $p[0]->p4;
                        $person['secondName'] = $p[0]->p5;
                        $person['lastName'] = $p[0]->p3;
                        $person['date'] = $p[0]->p9;
                        $person['id'] = $p[0]->p1;
                        $person['outId'] = $p[0]->p132;
                    }else{
                        $person['type'] = 0;
                        $person['firstName'] = $st[0]->st3;
                        $person['secondName'] = $st[0]->st4;
                        $person['lastName'] = $st[0]->st2;
                        $person['date'] = $st[0]->st7;
                        $person['id'] = $st[0]->st1;
                        $person['outId'] = $st[0]->st108;
                    }

                    $this->render('personByInn',array(
                        'person'=>$person,
                        'type' => self::PERSON_BY_INN
                    ));
                }
            }
        }
    }
    /**
     * Персона по Пасорту (серии и номеру)
     */
    public function actionGetPersonByPassport(){
        $xml = $this->getXmlFromPost();
        if(empty($xml))
            Yii::app()->end();
        else{
            /*Проверка есть ли тег GetPersonByPassport*/
            if($xml->getName()!='Request'||!isset($xml->GetPersonByPassport))
                $this->errorXml(self::ERROR_XML_STRUCTURE,'Ошибка струтуры xml');
            else {
                $xmlAction = $xml->GetPersonByPassport;
                /*Проверка есть ли теги нужные параметры*/

                if (!isset($xmlAction->Serial)||!isset($xmlAction->Number)||!isset($xmlAction->Login)||!isset($xmlAction->Password))
                    $this->errorXml(self::ERROR_XML_STRUCTURE, 'Ошибка струтуры(параметры) xml');
                else {

                    $this->checkAccess($xmlAction);

                    $serial = $xmlAction->Serial->__ToString();
                    $number = $xmlAction->Number->__ToString();

                    $person = array();

                    $st = St::model()->findAll('st17=:SERIAL AND st18=:NUMBER ORDER BY st1 DESC', array(':SERIAL'=>$serial, ':NUMBER'=>$number));
                    $p  = P::model()->findAll('p15=:SERIAL AND p16=:NUMBER', array(':SERIAL'=>$serial, ':NUMBER'=>$number));

                    $thereIsNotSuchId = count($st) + count($p) == 0;
                    if ($thereIsNotSuchId)
                        $this->errorXml(self::ERROR_NOT_FOUND_PERSON, 'По заданому критерию людей не найдено!');

                    $thereIsDuplicate = count($st) + count($p) > 1;
                    if ($thereIsDuplicate)
                        $this->errorXml(self::ERROR_FOUND_MANY_PERSON, 'По заданому критерию найдено несколько людей!');

                    if($p){
                        $person['type'] = 1;
                        $person['firstName'] = $p[0]->p4;
                        $person['secondName'] = $p[0]->p5;
                        $person['lastName'] = $p[0]->p3;
                        $person['date'] = $p[0]->p9;
                        $person['id'] = $p[0]->p1;
                        $person['outId'] = $p[0]->p132;
                    }else{
                        $person['type'] = 0;
                        $person['firstName'] = $st[0]->st3;
                        $person['secondName'] = $st[0]->st4;
                        $person['lastName'] = $st[0]->st2;
                        $person['date'] = $st[0]->st7;
                        $person['id'] = $st[0]->st1;
                        $person['outId'] = $st[0]->st108;
                    }

                    $this->render('personByPassport',array(
                        'person'=>$person,
                        'type' => self::PERSON_BY_PASSPORT
                    ));
                }
            }
        }
    }
    /**
     * расписание группы
     */
    public function actionGetTimetableForGroup(){
        $xml = $this->getXmlFromPost();
        if(empty($xml))
            Yii::app()->end();
        else{
            /*Проверка есть ли тег TimetableForGroup*/
            if($xml->getName()!='Request'||!isset($xml->TimetableForGroup))
                $this->errorXml(self::ERROR_XML_STRUCTURE,'Ошибка струтуры xml');
            else {
                $xmlAction = $xml->TimetableForGroup;
                /*Проверка есть ли теги нужные параметры*/
                if (
                    !isset($xmlAction->Group) ||
                    !isset($xmlAction->PeriodStart) ||
                    !isset($xmlAction->PeriodFinish)
                )
                    $this->errorXml(self::ERROR_XML_STRUCTURE, 'Ошибка струтуры(параметры) xml');
                else {
                    /*загрузка параментров*/

                    $Group = $xmlAction->Group->__ToString();
                    //print_r($StudentID);
                    $PeriodStart = $xmlAction->PeriodStart->__ToString();
                    $PeriodFinish = $xmlAction->PeriodFinish->__ToString();

                    $dateStart = date_create($PeriodStart);
                    if($dateStart===false)
                        $this->errorXml(self::ERROR_PARAM, 'PeriodStart не являеться датой');

                    $dateFinish = date_create($PeriodFinish);
                    if($dateFinish===false)
                        $this->errorXml(self::ERROR_PARAM, 'PeriodFinish не являеться датой');

                    $group = Gr::model()->findByAttributes(array('gr1'=>$Group));
                    if($group==null)
                        $this->errorXml(self::ERROR_PARAM, 'Group '.$Group.' не являеться валидным');


                    $timeTable=$this->getTimeTable($group->gr1, $dateStart->format(self::FORMAT_DATE), $dateFinish->format(self::FORMAT_DATE), 0);

                    if(empty($timeTable))
                        $this->errorXml(self::ERROR_EMPTY_TIMETABLE, 'Расписание не найдено');

                    $timeTable = $this->fillTimeTableByXml($timeTable,self::VIEW_GROUP);

                    $this->render('timeTableGroup',array(
                        'timeTable'=>$timeTable,
                        'type' => self::VIEW_GROUP
                    ));
                }
            }
        }
    }

    /**
     * расписание аудитории
     */
    public function actionGetTimetableForAudience(){
        $xml = $this->getXmlFromPost();
        if(empty($xml))
            Yii::app()->end();
        else{
            /*Проверка есть ли тег TimetableForAudience*/
            if($xml->getName()!='Request'||!isset($xml->TimetableForAudience))
                $this->errorXml(self::ERROR_XML_STRUCTURE,'Ошибка струтуры xml');
            else {
                $xmlAction = $xml->TimetableForAudience;
                /*Проверка есть ли теги нужные параметры*/
                if (
                    !isset($xmlAction->Audience) ||
                    !isset($xmlAction->PeriodStart) ||
                    !isset($xmlAction->PeriodFinish)
                )
                    $this->errorXml(self::ERROR_XML_STRUCTURE, 'Ошибка струтуры(параметры) xml');
                else {
                    /*загрузка параментров*/

                    $audienceID = $xmlAction->Audience->__ToString();
                    //print_r($audience);
                    $PeriodStart = $xmlAction->PeriodStart->__ToString();
                    $PeriodFinish = $xmlAction->PeriodFinish->__ToString();

                    $dateStart = date_create($PeriodStart);
                    if($dateStart===false)
                        $this->errorXml(self::ERROR_PARAM, 'PeriodStart не являеться датой');

                    $dateFinish = date_create($PeriodFinish);
                    if($dateFinish===false)
                        $this->errorXml(self::ERROR_PARAM, 'PeriodFinish не являеться датой');

                    $audience = A::model()->findByAttributes(array('a1'=>$audienceID));
                    if($audience==null)
                        $this->errorXml(self::ERROR_PARAM, 'audienceID '.$audienceID.' не являеться валидным');


                    $timeTable=$this->getTimeTable($audience->a1, $dateStart->format(self::FORMAT_DATE), $dateFinish->format(self::FORMAT_DATE), 3);

                    if(empty($timeTable))
                        $this->errorXml(self::ERROR_EMPTY_TIMETABLE, 'Расписание не найдено');

                    $timeTable = $this->fillTimeTableByXml($timeTable,self::VIEW_AUDIENCE);

                    $this->render('timeTableAudience',array(
                        'timeTable'=>$timeTable,
                        'type' => self::VIEW_AUDIENCE
                    ));
                }
            }
        }
    }
    /**
     * расписание преодователя
     */
    public function actionGetTimetableForTeacher(){
        $xml = $this->getXmlFromPost();
        if(empty($xml))
            Yii::app()->end();
        else{
            /*Проверка есть ли тег TimetableForTeacher*/
            if($xml->getName()!='Request'||!isset($xml->TimetableForTeacher))
                $this->errorXml(self::ERROR_XML_STRUCTURE,'Ошибка струтуры xml');
            else {
                $xmlAction = $xml->TimetableForTeacher;
                /*Проверка есть ли теги нужные параметры*/
                if (
                    !isset($xmlAction->TeacherID) ||
                    !isset($xmlAction->PeriodStart) ||
                    !isset($xmlAction->PeriodFinish)
                )
                    $this->errorXml(self::ERROR_XML_STRUCTURE, 'Ошибка струтуры(параметры) xml');
                else {
                    /*загрузка параментров*/

                    $TeacherID = $xmlAction->TeacherID->__ToString();
                    //print_r($StudentID);
                    $PeriodStart = $xmlAction->PeriodStart->__ToString();
                    $PeriodFinish = $xmlAction->PeriodFinish->__ToString();

                    $type = 0;
                    if(isset($xmlAction->Type))
                        $type = $xmlAction->Type->__ToString();

                    $dateStart = date_create($PeriodStart);
                    if($dateStart===false)
                        $this->errorXml(self::ERROR_PARAM, 'PeriodStart не являеться датой');

                    $dateFinish = date_create($PeriodFinish);
                    if($dateFinish===false)
                        $this->errorXml(self::ERROR_PARAM, 'PeriodFinish не являеться датой');

                    if($type==0){
                        $teacher = P::model()->findByAttributes(array('p132'=>$TeacherID));
                    }else{
                        $teacher = P::model()->findByAttributes(array('p1'=>$TeacherID));
                    }

                    if($teacher==null)
                        $this->errorXml(self::ERROR_PARAM, 'TeacherID '.$TeacherID.' не являеться валидным');


                    $timeTable=$this->getTimeTable($teacher->p1, $dateStart->format(self::FORMAT_DATE), $dateFinish->format(self::FORMAT_DATE), 2);

                    if(empty($timeTable))
                        $this->errorXml(self::ERROR_EMPTY_TIMETABLE, 'Расписание не найдено');

                    $timeTable = $this->fillTimeTableByXml($timeTable,self::VIEW_TEACHER);

                    $this->render('timeTableTeacher',array(
                        'timeTable'=>$timeTable,
                        'type' => self::VIEW_TEACHER
                    ));
                }
            }
        }
    }

    /**
     * Расписание студента
     */
    public function actionGetTimetableForStudent(){
        $xml = $this->getXmlFromPost();
        if(empty($xml))
            Yii::app()->end();
        else{
            /*Проверка есть ли тег TimetableForStudent*/
            if($xml->getName()!='Request'||!isset($xml->TimetableForStudent))
                $this->errorXml(self::ERROR_XML_STRUCTURE,'Ошибка струтуры xml');
            else {
                $xmlAction = $xml->TimetableForStudent;
                /*Проверка есть ли теги нужные параметры*/
                if (
                    !isset($xmlAction->StudentID) ||
                    !isset($xmlAction->PeriodStart) ||
                    !isset($xmlAction->PeriodFinish)
                )
                    $this->errorXml(self::ERROR_XML_STRUCTURE, 'Ошибка струтуры(параметры) xml');
                else {
                    /*загрузка параментров*/

                    $StudentID = $xmlAction->StudentID->__ToString();
                    //print_r($StudentID);
                    $PeriodStart = $xmlAction->PeriodStart->__ToString();
                    $PeriodFinish = $xmlAction->PeriodFinish->__ToString();

                    $type = 0;
                    if(isset($xmlAction->Type))
                        $type = $xmlAction->Type->__ToString();

                    $dateStart = date_create($PeriodStart);
                    if($dateStart===false)
                        $this->errorXml(self::ERROR_PARAM, 'PeriodStart не являеться датой');

                    $dateFinish = date_create($PeriodFinish);
                    if($dateFinish===false)
                        $this->errorXml(self::ERROR_PARAM, 'PeriodFinish не являеться датой');

                    //$student = St::model()->findByAttributes(array('st1'=>$StudentID));
                    if($type==0){
                        $student = St::model()->findByAttributes(array('st108'=>$StudentID));
                    }else{
                        $student = St::model()->findByAttributes(array('st1'=>$StudentID));
                    }

                    if($student==null)
                        $this->errorXml(self::ERROR_PARAM, 'StudentID '.$StudentID.' не являеться валидным');


                    $timeTable=$this->getTimeTable($student->st1, $dateStart->format(self::FORMAT_DATE), $dateFinish->format(self::FORMAT_DATE), 1);

                    if(empty($timeTable))
                        $this->errorXml(self::ERROR_EMPTY_TIMETABLE, 'Расписание не найдено');

                    $timeTable = $this->fillTimeTableByXml($timeTable,self::VIEW_STUDENT);

                    $this->render('timeTableStudent',array(
                        'timeTable'=>$timeTable,
                        'type' => self::VIEW_STUDENT
                    ));
                }
            }
        }
    }
    /**
     * Список Аудитории
     * возможно через необязательные теги фильтрация по филиалу, адимтории
     */
    public function actionGetAudiences(){
        $xml = $this->getXmlFromPost();
        if(empty($xml))
            Yii::app()->end();
        else{
            /*Проверка есть ли тег GetAudiences*/
            if($xml->getName()!='Request'||!isset($xml->GetAudiences))
                $this->errorXml(self::ERROR_XML_STRUCTURE,'Ошибка струтуры xml');
            else {
                $xmlAction = $xml->GetAudiences;
                /*фильтры*/
                $audience = null;
                $filial = null;
                /*перебираем всех наследников ищем наши фильтрі*/
                foreach($xmlAction->children() as $child){
                    /* @var $child SimpleXMLElement */

                    $tag = $child->getName();

                    switch($tag){
                        case 'Audience':
                            $audience = $child->__ToString();
                            break;
                        case 'Filial':
                            $filial = $child->__ToString();
                            break;
                    }
                }

                $where = '';

                if($filial!=null){
                    $where.=' AND A6=:FILIAL';
                }

                if($audience!=null){
                    $where.=' AND A1=:AUDIENCE';
                }

                $sql= <<<SQL
                  SELECT *
                  FROM A
                  WHERE A5 is null and A1>0 {$where}
                  ORDER BY A8,A9,A2
SQL;
                $command = Yii::app()->db->createCommand($sql);
                $command->bindValue(':AUDIENCE', $audience);
                $command->bindValue(':FILIAL', $filial);
                $audiences = $command->queryAll();

                $this->render('audiences',array(
                    'audiences'=>$audiences
                ));
            }
        }
    }
    /**
     * Список Группы
     * возможно через необязательные теги фильтрация по факультету,курсу,кафедре черз логическое и
     */
    public function actionGetGroups(){
        $xml = $this->getXmlFromPost();
        if(empty($xml))
            Yii::app()->end();
        else{
            /*Проверка есть ли тег GetGroups*/
            if($xml->getName()!='Request'||!isset($xml->GetGroups))
                $this->errorXml(self::ERROR_XML_STRUCTURE,'Ошибка струтуры xml');
            else {
                $xmlAction = $xml->GetGroups;
                /*фильтры*/
                $course = null;
                $faculty = null;
                $filial = null;
                $group = null;
                /*перебираем всех наследников ищем наши фильтрі*/
                foreach($xmlAction->children() as $child){
                    /* @var $child SimpleXMLElement */

                    $tag = $child->getName();

                    switch($tag){
                        case 'Faculty':
                            $faculty = $child->__ToString();
                            break;
                        case 'Filial':
                            $filial = $child->__ToString();
                            break;
                        case 'Course':
                            $course = $child->__ToString();
                            break;
                        case 'Group':
                            $group = $child->__ToString();
                            break;
                    }
                }

                $where = '';

                if($faculty!=null){
                    $where.=' AND sp5=:FACULTY';
                }

                if($filial!=null){
                    $where.=' AND f14=:FILIAL';
                }

                if($course!=null){
                    $where.=' AND sem4=:COURSE';
                }

                if($group!=null){
                    $where.=' AND GR1=:GROUP';
                }

                $sql=<<<SQL
                    SELECT sg4, sem4, gr13,gr7,gr3,gr1, gr19,gr20,gr21,gr22,gr23,gr24,gr25,gr26, sp5,f14
                    from sp
                        inner join f on (sp.sp5 = f.f1)
                       inner join sg on (sp.sp1 = sg.sg2)
                       inner join sem on (sg.sg1 = sem.sem2)
                       inner join gr on (sg.sg1 = gr.gr2)
                       inner join ucgn on (gr.gr1 = ucgn.ucgn2)
                       inner join ucgns on (ucgn.ucgn1 = ucgns.ucgns2)
                       inner join ucxg on (ucgn.ucgn1 = ucxg.ucxg2)
                    WHERE ucxg1<30000 and gr13=0 and gr6 is null
                         and sem3=:YEAR1 and sem5=:SEM1 and ucgns5=:YEAR2 and ucgns6=:SEM2 $where
                    GROUP BY sg4, sem4, gr13,gr7,gr3,gr1, gr19,gr20,gr21,gr22,gr23,gr24,gr25,gr26,sp5,f14
                    ORDER BY sp5, sem4, gr7,gr3
SQL;
                list($year, $sem) = SH::getCurrentYearAndSem();

                $command = Yii::app()->db->createCommand($sql);
                $command->bindValue(':GROUP', $group);
                $command->bindValue(':FACULTY', $faculty);
                $command->bindValue(':FILIAL', $filial);
                $command->bindValue(':COURSE', $course);
                $command->bindValue(':YEAR1', $year);
                $command->bindValue(':YEAR2', $year);
                $command->bindValue(':SEM1', $sem);
                $command->bindValue(':SEM2', $sem);
                $chairs = $command->queryAll();

                $this->render('groups',array(
                    'groups'=>$chairs
                ));
            }
        }
    }
    /**
     * Список кафедр
     * возможно через необязательные теги фильтрация по факультету, филиалу,кафедре черз логическое и
     */
    public function actionGetChairs(){
        $xml = $this->getXmlFromPost();
        if(empty($xml))
            Yii::app()->end();
        else{
            /*Проверка есть ли тег GetChairs*/
            if($xml->getName()!='Request'||!isset($xml->GetChairs))
                $this->errorXml(self::ERROR_XML_STRUCTURE,'Ошибка струтуры xml');
            else {
                $xmlAction = $xml->GetChairs;
                /*фильтры*/
                $filial = null;
                $faculty = null;
                $chair = null;
                /*перебираем всех наследников ищем наши фильтрі*/
                foreach($xmlAction->children() as $child){
                    /* @var $child SimpleXMLElement */

                    $tag = $child->getName();

                    switch($tag){
                        case 'Faculty':
                                $faculty = $child->__ToString();
                            break;
                        case 'Chair':
                                $chair = $child->__ToString();
                            break;
                        case 'Filial':
                                $filial = $child->__ToString();
                            break;
                    }
                }

                $where = '';

                if($filial!=null){
                    $where.=' AND k10=:FILIAL';
                }

                if($faculty!=null){
                    $where.=' AND F1=:FACULTY';
                }

                if($chair!=null){
                    $where.=' AND K1=:CHAIR';
                }

                $sql=<<<SQL
                    SELECT K1,K2,K3,K10,K7
                        FROM F
                        inner join k on (f.f1 = k.k7)
                    WHERE f12='1' and f17='0' and k11='1' and (k9 is null) and K1>0 $where
                    ORDER BY K3 collate UNICODE
SQL;

                $command = Yii::app()->db->createCommand($sql);
                $command->bindValue(':FILIAL', $filial);
                $command->bindValue(':FACULTY', $faculty);
                $command->bindValue(':CHAIR', $chair);
                $chairs = $command->queryAll();

                $this->render('chairs',array(
                    'chairs'=>$chairs
                ));
            }
        }
    }
    /**
     * Список Преподователей
     * возможно через необязательные теги фильтрация по кафедре, фамилии() черз логическое и
     */
    public function actionGetTeachers(){
        $xml = $this->getXmlFromPost();
        if(empty($xml))
            Yii::app()->end();
        else{
            /*Проверка есть ли тег GetTeachers*/
            if($xml->getName()!='Request'||!isset($xml->GetTeachers))
                $this->errorXml(self::ERROR_XML_STRUCTURE,'Ошибка струтуры xml');
            else {
                /* @var $xmlAction SimpleXMLElement */
                $xmlAction = $xml->GetTeachers;
                /*фильтры*/
                $chair = null;
                $name = null;
                /*перебираем всех наследников ищем наши фильтрі*/
                foreach($xmlAction->children() as $child){
                    /* @var $child SimpleXMLElement */

                    $tag = $child->getName();

                    switch($tag){
                        case 'Chair':
                            $chair = $child->__ToString();
                            break;
                        case 'Name':
                            $name = $child->__ToString();
                            break;
                    }
                }

                $where = '';

                if($chair!=null){
                    $where.=' AND PD4 = :CHAIR';
                }

                if($name!=null){
                    $where.=' and P3 CONTAINING :NAME';
                }

                $today = date('d.m.Y 00:00');

                $sql=<<<SQL
                    SELECT P1,P3,P4,P5,P132,PD4
                    FROM P
                        INNER JOIN PD ON (P1=PD2)
                        INNER JOIN DOL ON (PD45 = DOL1)
                    WHERE PD28 in (0,2,5,9) and PD3=0 and (PD13 IS NULL or PD13>'{$today}') {$where}
                    group by P1,P3,P4,P5,P132,PD4
                    ORDER BY P3 collate UNICODE
SQL;

                $command = Yii::app()->db->createCommand($sql);
                $command->bindValue(':CHAIR', $chair);
                $command->bindValue(':NAME', $name);
                $teachers = $command->queryAll();

                $this->render('teachers',array(
                    'teachers'=>$teachers
                ));
            }
        }
    }
    /**
     * Список факультетов
     * возможно через необязательные теги фильтрация по факультету, филиалу черз логическое и
     */
    public function actionGetFaculties(){
        $xml = $this->getXmlFromPost();
        if(empty($xml))
            Yii::app()->end();
        else{
            /*Проверка есть ли тег GetFaculties*/
            if($xml->getName()!='Request'||!isset($xml->GetFaculties))
                $this->errorXml(self::ERROR_XML_STRUCTURE,'Ошибка струтуры xml');
            else {
                /* @var $xmlAction SimpleXMLElement */
                $xmlAction = $xml->GetFaculties;
                /*фильтры*/
                $filial = null;
                $faculty = null;
                /*перебираем всех наследников ищем наши фильтрі*/
                foreach($xmlAction->children() as $child){
                    /* @var $child SimpleXMLElement */

                    $tag = $child->getName();

                    switch($tag){
                        case 'Faculty':
                            $faculty = $child->__ToString();
                            break;
                        case 'Filial':
                            $filial = $child->__ToString();
                            break;
                    }
                }

                $where = '';

                if($filial!=null){
                    $where.=' AND f14=:FILIAL';
                }

                if($faculty!=null){
                    $where.=' AND F1=:FACULTY';
                }

                $sql=<<<SQL
                     SELECT f1,f2, f3, f14
                    FROM f
                    WHERE f1>0 and f12<>0 and f17=0 and (f19 is null) and f32 = 0 $where
                    ORDER BY f15,f3 collate UNICODE
SQL;

                $command = Yii::app()->db->createCommand($sql);
                $command->bindValue(':FILIAL', $filial);
                $command->bindValue(':FACULTY', $faculty);
                $faculties = $command->queryAll();

                $this->render('faculties',array(
                    'faculties'=>$faculties
                ));
            }
        }
    }
    /**
     * Загрузка внешних id для студентов
     */
    public function actionUploadStudentsId(){
        $xml = $this->getXmlFromPost();
        if(empty($xml))
            Yii::app()->end();
        else{
            /*Проверка есть ли тег UploadStudentsID*/
            if($xml->getName()!='Request'||!isset($xml->UploadStudentsID))
                $this->errorXml(self::ERROR_XML_STRUCTURE,'Ошибка струтуры xml');
            else {
                $uploads = $xml->UploadStudentsID;
                if(!isset($uploads->Students)||!isset($uploads->Login)||!isset($uploads->Password))
                    $this->errorXml(self::ERROR_XML_STRUCTURE,'Ошибка струтуры xml');
                else{
                    $errors = array();
                    $students = $uploads->Students;

                    $ps95 = PortalSettings::model()->findByPk(95)->ps2;
                    $ps96 = PortalSettings::model()->findByPk(96)->ps2;

                    $login = $uploads->Login->__ToString();
                    $password = $uploads->Password->__ToString();
                    if($login!=$ps95||$password!=$ps96||empty($login)||empty($password))
                        $this->errorXml(self::ERROR_INVALID_LOGIN_OR_PASSWORD,'Неправльный логин иили пароль');

                    foreach($students->children() as $student){

                        /* @var $student SimpleXMLElement */

                        /*проверяем являеться ли дочерний тег тегом Student*/
                        if($student->getName()=='Student'){
                            /*берем айди из контента тега*/
                            $id = $student->__ToString();
                            if(!is_numeric($id)){
                                $this->errorXml(self::ERROR_XML_STRUCTURE, 'Ошибка струтуры(параметры) xml');
                            }
                            /*если пустой айди добавляем ошибку*/
                            if(empty($id)){
                                array_push($errors,
                                    array(
                                        'id'=>$id,
                                        'message'=>'Пустой id'
                                    )
                                );
                            }else {
                                $arr = St::model()->findAllByAttributes(array('st108' => $id));
                                if (!empty($arr)) {
                                    array_push($errors,
                                        array(
                                            'id' => $id,
                                            'message' => sprintf(
                                                'Студент с таким id=%s уже существует',
                                                $id
                                            )
                                        )
                                    );
                                } else
                                {
                                    /*название атрибута фамилия*/
                                    $attrLName = 'LastName';
                                    /*название атрибута имя*/
                                    $attrFName = 'FirstName';
                                    /*название атрибута отчество*/
                                    $attrSName = 'SecondName';
                                    /*название атрибута дата рождения*/
                                    $attrBDay = 'BirthDay';

                                    $lName = (string)$student->attributes()->$attrLName;
                                    $fName = (string)$student->attributes()->$attrFName;
                                    $sName = (string)$student->attributes()->$attrSName;
                                    $bDay = date_create((string)$student->attributes()->$attrBDay);
                                    if ($bDay === false) {
                                        //$this->errorXml(self::ERROR_PARAM, 'BirthDay не являеться датой');
                                        array_push($errors,
                                            array(
                                                'id' => $id,
                                                'message' => 'BirthDay не являеться датой'
                                            )
                                        );
                                    }

                                    $arr = St::model()->findAllByAttributes(array(
                                        'st2' => $lName,
                                        'st3' => $fName,
                                        'st4' => $sName,
                                        'st7' => $bDay->format(self::FORMAT_DATE),
                                    ));

                                    //проверяем есть по нашему запросы студенты
                                    if (empty($arr))
                                        array_push($errors,
                                            array(
                                                'id' => $id,
                                                'message' => sprintf(
                                                    'Не найден студент для id=%s с параментрами %s=%s, %s=%s, %s=%s, %s=%s',
                                                    $id,
                                                    $attrLName, $lName,
                                                    $attrFName, $fName,
                                                    $attrSName, $sName,
                                                    $attrBDay, $bDay->format(self::FORMAT_DATE)
                                                )
                                            )
                                        );
                                    else {
                                        /*если мы нашли больше одного стеднта*/
                                        if (count($arr) > 1) {
                                            array_push($errors,
                                                array(
                                                    'id' => $id,
                                                    'message' => sprintf(
                                                        'Найдено несколько студентов для id=%s с параментрами %s=%s, %s=%s, %s=%s, %s=%s',
                                                        $id,
                                                        $attrLName, $lName,
                                                        $attrFName, $fName,
                                                        $attrSName, $sName,
                                                        $attrBDay, $bDay->format(self::FORMAT_DATE)
                                                    )
                                                )
                                            );
                                        } else {
                                            $save = $arr[0]->saveAttributes(array('st108' => $id));
                                            if (!$save) {
                                                array_push($errors,
                                                    array(
                                                        'id' => $id,
                                                        'message' => sprintf(
                                                            'Не сохранен id=%s для студента с параментрами %s=%s, %s=%s, %s=%s, %s=%s',
                                                            $id,
                                                            $attrLName, $lName,
                                                            $attrFName, $fName,
                                                            $attrSName, $sName,
                                                            $attrBDay, $bDay->format(self::FORMAT_DATE)
                                                        )
                                                    )
                                                );
                                                //print_r( $arr[0]->getErrors());
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $this->render('uploadStudents',array(
                    'errors'=>$errors
                ));
            }
        }
    }

    /**
     * Загрузка внешних id для преподователей
     */
    public function actionUploadTeachersId(){
        $xml = $this->getXmlFromPost();
        if(empty($xml))
            Yii::app()->end();
        else{
            /*Проверка есть ли тег UploadTeachersID*/
            if($xml->getName()!='Request'||!isset($xml->UploadTeachersID))
                $this->errorXml(self::ERROR_XML_STRUCTURE,'Ошибка струтуры xml');
            else {
                $uploads = $xml->UploadTeachersID;
                if(!isset($uploads->Teachers)||!isset($uploads->Login)||!isset($uploads->Password))
                    $this->errorXml(self::ERROR_XML_STRUCTURE,'Ошибка струтуры xml');
                else{
                    $errors = array();
                    $teachers = $uploads->Teachers;

                    $ps95 = PortalSettings::model()->findByPk(95)->ps2;
                    $ps96 = PortalSettings::model()->findByPk(96)->ps2;

                    $login = $uploads->Login->__ToString();
                    $password = $uploads->Password->__ToString();
                    if($login!=$ps95||$password!=$ps96||empty($login)||empty($password))
                        $this->errorXml(self::ERROR_INVALID_LOGIN_OR_PASSWORD,'Неправльный логин иили пароль');


                    foreach($teachers->children() as $teacher){

                        /* @var $teacher SimpleXMLElement */

                        /*проверяем являеться ли дочерний тег тегом Teacher*/
                        if($teacher->getName()=='Teacher'){
                            /*берем айди из контента тега*/
                            $id = $teacher->__ToString();
                            if(!is_numeric($id)){
                                $this->errorXml(self::ERROR_XML_STRUCTURE, 'Ошибка струтуры(параметры) xml');
                            }
                            /*если пустой айди добавляем ошибку*/
                            if(empty($id)){
                                array_push($errors,
                                    array(
                                        'id'=>$id,
                                        'message'=>'Пустой id'
                                    )
                                );
                            }else {
                                $arr = P::model()->findAllByAttributes(array('p132' => $id));
                                if (!empty($arr)) {
                                    array_push($errors,
                                        array(
                                            'id' => $id,
                                            'message' => sprintf(
                                                'Преподователь с таким id=%s уже существует',
                                                $id
                                            )
                                        )
                                    );
                                } else
                                {
                                    /*название атрибута фамилия*/
                                    $attrLName = 'LastName';
                                    /*название атрибута имя*/
                                    $attrFName = 'FirstName';
                                    /*название атрибута отчество*/
                                    $attrSName = 'SecondName';
                                    /*название атрибута дата рождения*/
                                    $attrBDay = 'BirthDay';

                                    $lName = (string)$teacher->attributes()->$attrLName;
                                    $fName = (string)$teacher->attributes()->$attrFName;
                                    $sName = (string)$teacher->attributes()->$attrSName;
                                    $bDay = date_create((string)$teacher->attributes()->$attrBDay);
                                    if ($bDay === false) {
                                        //$this->errorXml(self::ERROR_PARAM, 'BirthDay не являеться датой');
                                        array_push($errors,
                                            array(
                                                'id' => $id,
                                                'message' => 'BirthDay не являеться датой'
                                            )
                                        );
                                    }else {

                                        $arr = P::model()->findAllByAttributes(array(
                                            'p3' => $lName,
                                            'p4' => $fName,
                                            'p5' => $sName,
                                            'p9' => $bDay->format(self::FORMAT_DATE),
                                        ));

                                        //проверяем есть по нашему запросы преподы
                                        if (empty($arr))
                                            array_push($errors,
                                                array(
                                                    'id' => $id,
                                                    'message' => sprintf(
                                                        'Не найден преподователь для id=%s с параментрами %s=%s, %s=%s, %s=%s, %s=%s',
                                                        $id,
                                                        $attrLName, $lName,
                                                        $attrFName, $fName,
                                                        $attrSName, $sName,
                                                        $attrBDay, $bDay->format(self::FORMAT_DATE)
                                                    )
                                                )
                                            );
                                        else {
                                            /*если мы нашли больше одного препода*/
                                            if (count($arr) > 1) {
                                                array_push($errors,
                                                    array(
                                                        'id' => $id,
                                                        'message' => sprintf(
                                                            'Найдено несколько преподователей для id=%s с параментрами %s=%s, %s=%s, %s=%s, %s=%s',
                                                            $id,
                                                            $attrLName, $lName,
                                                            $attrFName, $fName,
                                                            $attrSName, $sName,
                                                            $attrBDay, $bDay->format(self::FORMAT_DATE)
                                                        )
                                                    )
                                                );
                                            } else {
                                                $save = $arr[0]->saveAttributes(array('p132' => $id));
                                                if (!$save) {
                                                    array_push($errors,
                                                        array(
                                                            'id' => $id,
                                                            'message' => sprintf(
                                                                'Не сохранен id=%s для преподователя с параментрами %s=%s, %s=%s, %s=%s, %s=%s',
                                                                $id,
                                                                $attrLName, $lName,
                                                                $attrFName, $fName,
                                                                $attrSName, $sName,
                                                                $attrBDay, $bDay->format(self::FORMAT_DATE)
                                                            )
                                                        )
                                                    );
                                                    //print_r( $arr[0]->getErrors());
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $this->render('uploadTeachers',array(
                    'errors'=>$errors
                ));
            }
        }
    }

    /*Получить расписание*/
    /*
     * $id -> индефикатор st1, gr1, p1, a1
     * $dateStart -> дата "c"
     * $dateFinish -> дата "по"
     * $type - 1 расписание студента, 0 - расписание группы, 2-преподователя, 3-аудитории
     * */
    private function getTimeTable($id, $dateStart, $dateFinish, $type){
        switch($type)
        {
            case 0:
                $sql ='SELECT * FROM TTGR(:ID, :DATE_1, :DATE_2) ORDER BY ned, r2, r3';
                break;
            case 1:
                $sql ='SELECT * FROM TTST(:ID, :DATE_1, :DATE_2) ORDER BY ned, r2, r3';
                break;
            case 2:
                $sql ='SELECT * FROM TTPR(:ID, :DATE_1, :DATE_2) ORDER BY ned, r2, r3';
                break;
            case 3:
                $sql ='SELECT * FROM TTA(:ID, :DATE_1, :DATE_2) ORDER BY ned, r2,r3';
                break;
        }
        $command = Yii::app()->db->createCommand($sql);
        //if($type!=2)
        $command->bindValue(':LANG', 1);
        $command->bindValue(':ID', $id);
        $command->bindValue(':DATE_1', $dateStart);
        $command->bindValue(':DATE_2', $dateFinish);
        $timeTable = $command->queryAll();

        if (empty($timeTable))
            return array();

        return $timeTable;
    }
    /**/
    private function fillTimeTableByXml($timeTable, $type)
    {
        $res = array();

        foreach($timeTable as $key => $day) {

            $r2 = strtotime($day['r2']); // date
            $r3 = $day['r3'];            // lesson
            if($type!=self::VIEW_GROUP) {
                if (!isset($res[$r2]['timeTable'][$r3])) {
                    $res[$r2]['timeTable'][$r3] = $day;
                    $res[$r2]['timeTable'][$r3]['gr'][$day['gr1']] = $day['gr13'];
                } else
                    $res[$r2]['timeTable'][$r3]['gr'][$day['gr1']] = $day['gr13'];
            }
            else{
                $res[$r2]['timeTable'][$r3] = $day;
            }
        }
        //die(var_dump($res));
        return $res;
    }

    /**
     * проверка логина и пароля
     * @param $xmlAction
     */
    private function checkAccess($xmlAction)
    {
        $ps95 = PortalSettings::model()->findByPk(95)->ps2;
        $ps96 = PortalSettings::model()->findByPk(96)->ps2;

        $login = $xmlAction->Login->__ToString();
        $password = $xmlAction->Password->__ToString();
        if ($login != $ps95 || $password != $ps96 || empty($login) || empty($password))
            $this->errorXml(self::ERROR_INVALID_LOGIN_OR_PASSWORD, 'Неправльный логин иили пароль');
    }

}