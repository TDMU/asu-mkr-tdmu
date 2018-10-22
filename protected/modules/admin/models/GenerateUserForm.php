<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 30.09.14
 * Time: 9:12
 */
class GenerateUserForm extends CFormModel
{
    public $lastName;
    public $firstName;
    public $secondName;
    public $type;
    public $bDate;

    public $users;


    public $faculty;
    public $course;
    public $speciality;

    public $chair;

    public $chairName;

    public function rules()
    {
        return array(
			array('type, faculty, course, speciality, chair', 'numerical', 'integerOnly'=>true),
			array('lastName,firstName,secondName, users, chairName', 'type', 'type'=>'string'),
            array('bDate', 'type', 'type'=>'date'),
		);
    }

    public function attributeLabels()
    {
        return array(
            'lastName' => tt('Фамилия'),
            'firstName'=>tt('Имя'),
            'secondName'=>tt('Отчество'),
            'bDate'=>tt('Дата рождения'),
            'type'=>tt('Тип'),
            'faculty'=>tt('Факультет'),
            'course'=>tt('Курс'),
            'speciality'=>tt('Специальность'),
            'chair'=>tt('Кафедра'),
        );
    }

    public static function getTypes(){
        return array(1=>tt('Студент'),2=>tt('Преподаватель'),3=>tt('Родитель'));
    }

    public static function getType($type){
        $types = self::getTypes();
        if(isset($types[$type+1]))
            return $types[$type+1];
        else
            return '-';
    }

    public function search(){

        $params = array();

        //list($year, $sem) = SH::getCurrentYearAndSem();

        $where = '';
        $sqlSt = '
            SELECT st1 as id, st2 as last_name, st3 as first_name, st4 as second_name, 
              0 as type, st7 as b_date, std20 as course, 
              f1 as faculty, sp1 as speciality, null as chair, null as chair_name, f2 as faculty_name, sp2 as speciality_name from f
               inner join sp on (f.f1 = sp.sp5)
               inner join sg on (sp.sp1 = sg.sg2)
               inner join gr on (sg.sg1 = gr.gr2)
               inner join std on (gr.gr1 = std.std3)
               inner join st on (std.std2 = st.st1)
            WHERE  std11 in (0,5,6,8) and std7 is null  {{%where}}
            GROUP by st1, st2, st3, st4, st7, std20, f1, sp1, f2, sp2
        ';
        $sqlPrnt = '
            SELECT st1 as id, st2 as last_name, st3 as first_name, st4 as second_name, 
              2 as type, st7 as b_date, std20 as course, 
              f1 as faculty, sp1 as speciality, 
              null as chair, null as chair_name, f2 as faculty_name, sp2 as speciality_name from f
               inner join sp on (f.f1 = sp.sp5)
               inner join sg on (sp.sp1 = sg.sg2)
               inner join gr on (sg.sg1 = gr.gr2)
               inner join std on (gr.gr1 = std.std3)
               inner join st on (std.std2 = st.st1)
            WHERE  std11 in (0,5,6,8) and std7 is null  {{%where}}
            GROUP by st1, st2, st3, st4, st7, std20, f1, sp1,  f2, sp2
        ';

        $wherePrnt =$whereSt = $whereTch = '';
        $today = date('d.m.Y 00:00');
        $sqlTch = "
                SELECT p1 as id, p3 as last_name, p4 as first_name, p5 as second_name, 
                    1 as type, p9 as b_date, null as course, null as faculty, null as speciality,
                    pd4 as chair, k3 as chair_name, null as faculty_name, null as speciality_name FROM p
                    INNER JOIN PD ON (P1=PD2)
                    INNER JOIN K on (pd4 = k1)
                    WHERE  PD28 in (0,2,5,9) and PD3=0 and (PD13 IS NULL or PD13>'$today') {{%where}}
                GROUP by p1, p3, p4, p5, p9, pd4, k3
      ";

        if(!empty($this->lastName))
        {
            $where.=" AND last_name CONTAINING :last_name";
            $params[':last_name'] = $this->lastName;
        }

        if(!empty($this->firstName))
        {
            $where.=" AND first_name CONTAINING :first_name";
            $params[':first_name'] = $this->firstName;
        }

        if(!empty($this->secondName))
        {
            $where.=" AND second_name CONTAINING :second_name";
            $params[':second_name'] = $this->secondName;
        }

        if(!empty($this->bDate))
        {
            $where.=" AND b_date CONTAINING :b_date";
            $params[':b_date'] = date_format(date_create_from_format('d-m-Y', trim($this->bDate)), 'Y-m-d');
        }

        if(!empty($this->type))
        {
            $where.=' AND type = :type';
            $params[':type'] = $this->type-1;
        }


        if(!empty($this->chairName))
        {
            $where.=" AND chair_name CONTAINING :chair";
            $params[':chair'] = $this->chairName;
        }

        if(!empty($this->course))
        {
            $where.=" AND course = :course";
            $params[':course'] = $this->course;

            /*$wherePrnt.=" AND STD20 = :course1";
            $params[':course1'] = $this->course;*/
        }

        if(!empty($this->faculty))
        {
            $where.=" AND faculty = :faculty";
            $params[':faculty'] = $this->faculty;

            /*$wherePrnt.=" AND F1 = :faculty1";
            $params[':faculty1'] = $this->faculty;*/
        }

        if(!empty($this->speciality))
        {
            $where.=" AND speciality = :speciality";
            $params[':speciality'] = $this->speciality;

            /*$wherePrnt.=" AND sp1 = :speciality1";
            $params[':speciality1'] = $this->speciality;*/
        }

        $sql = str_replace('{{%where}}', $whereSt, $sqlSt);
        $sql.= ' UNION ';
        $sql .= str_replace('{{%where}}', $whereTch, $sqlTch);
        $sql.= ' UNION ';
        $sql .= str_replace('{{%where}}', $wherePrnt, $sqlPrnt);;
        $sql = 'SELECT * FROM ('.$sql.') t WHERE last_name<>\'\' AND (SELECT COUNT(*) FROM USERS WHERE u6=t.id and u5=t.type)=0 '.$where;
        $rawData = Yii::app()->db->createCommand($sql); //or use ->queryAll(); in CArrayDataProvider

        //$countData = Yii::app()->db->createCommand('SELECT COUNT(*) FROM (' . $sql . ') as count_record'); //the count
        /*foreach ($params as $key=>$value) {
            $rawData->bindParam($key, $value);
        }*/

        $rawData->bindValues($params);
        //$countData->bindValues($params);

        //$count = $countData->queryScalar();

        /*print_r($sql);

        var_dump($params);*/

        $provider = new CArrayDataProvider($rawData->queryAll(), array(
            //'keyField' => 'id',
            //'totalItemCount' => $count,
            'sort' => array(
                'attributes' => array(
                    'type',
                    'firstName'=>array(
                        'asc'=>'first_name asc',
                        'desc'=>'first_name desc'
                    ),
                    'secondName'=>array(
                        'asc'=>'second_name asc',
                        'desc'=>'second_name desc'
                    ),
                    'lastName'=>array(
                        'asc'=>'last_name asc',
                        'desc'=>'last_name desc'
                    ),
                    'course'=>array(
                        'asc'=>'course asc',
                        'desc'=>'course desc'
                    ),
                    'faculty'=>array(
                        'asc'=>'faculty_name asc',
                        'desc'=>'faculty_name desc'
                    ),
                    'speciality'=>array(
                        'asc'=>'speciality_name asc',
                        'desc'=>'speciality_name desc'
                    ),
                    'chair'=>array(
                        'asc'=>'chair_name asc',
                        'desc'=>'chair_name desc'
                    ),
                    'bDate'=>array(
                        'asc'=>'b_date asc',
                        'desc'=>'b_date desc'
                    )
                ),
                'defaultOrder' => array(
                    'id' => CSort::SORT_ASC, //default sort value
                ),
            ),
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize',10),
            ),
        ));
        return $provider;
    }
}
?>