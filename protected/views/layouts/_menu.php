<?php
$_m = isset(Yii::app()->controller->module) ? Yii::app()->controller->module->id : '';
$_c = Yii::app()->controller->id;
$_a = Yii::app()->controller->action->id;
function getDopGroup($_l)
{
    $res=Pmg::model()->findAllByAttributes(array('pmg7'=>1),array('order'=>'pmg9 DESC'));
    if(!empty($res))
    {
        $arr=array();
        foreach($res as $val)
        {
            $visible=true;
            if($val->pmg6>0)
            {
                if(!Yii::app()->user->isAdmin&&!Yii::app()->user->isStd&&!Yii::app()->user->isTch)
                    $visible=false;
            }
            array_push($arr,array(
                'label' => _l(getLabelGroup($val), $val->pmg9),
                'url' => '#',
                'linkOptions'=> $_l,
                'items' =>getDopItem($val->pmg1,0),
                'visible'=>$visible
            ));
        }
        return $arr;
    }else
        return array();
}
function getLabelGroup($item)
{
    $label='';
    switch (Yii::app()->language) {
        case 'uk':
            $label=$item->pmg2;
            break;
        case 'ru':
            $label=$item->pmg3;
            break;
        case 'en':
            $label=$item->pmg4;
            break;
        default:
            $label=$item->pmg5;
            break;
    }
    return $label;
}
function getLabelItem($item)
{
    $label='';
    switch (Yii::app()->language) {
        case 'uk':
            $label=$item->pm2;
            break;
        case 'ru':
            $label=$item->pm3;
            break;
        case 'en':
            $label=$item->pm4;
            break;
        default:
            $label=$item->pm5;    
            break;
    }
    return $label;
}
function getDopItem($controller,$level)
{
    if(empty($controller))
        return array();

    if($level>1)
        return array();

    if($level==0)
        $items=Pm::model()->findAllByAttributes(array('pm7'=>1,'pm10'=>$controller,'pm11'=>$level),array('order'=>'pm9 DESC'));
    else
        $items=Pm::model()->findAllBySql('SELECT * FROM pm inner join pmc on (pm1=pmc2) WHERE pm7=1 AND pmc1=:pmc1 and pm11=1 ORDER BY pm9 DESC',array(':pmc1'=>$controller,'pm11'=>$level));

    if(!empty($items))
    {
       $array=array();
       //'linkOptions' => array('target'=>'_blank')
       foreach ($items as $item) {
           $items=getDopItem($item->pm1,$level+1);
           if(empty($items))
            array_push($array,array(
              'label'  => getLabelItem($item),
              'url'    => $item->pm6,
              'linkOptions' => array('target'=>($item->pm8==1)?'_blank':'_self'),
          ));
           else
               array_push($array,array(
                   'label'  => _l(getLabelItem($item),'angle-down'),
                   'url' => '#',
                   'linkOptions'=> array('class' => 'dropdown-toggle'),
                   'items' =>$items,
               ));

       }
       //print_r($array);
       return $array;
    }    
    else
        return array();
}
function _l($name, $icon)
{
    return '<i class="icon-'.$icon.'"></i><span class="menu-text">'.tt($name).'</span><b class="arrow icon-angle-down"></b>';
}

function _u($url)
{
    return Yii::app()->createUrl($url);
}

function _ch($controller, $action)
{
    return SH::checkServiceFor(MENU_ELEMENT_VISIBLE, $controller, $action);
}

function _i($name)
{
    return array('class'=> Yii::app()->controller->id==$name ? 'active open' : '');
}

$_l = array(
    'class' => 'dropdown-toggle',
);
$_l2 = '<i class="icon-double-angle-right"></i>';

$isStd   = Yii::app()->user->isStd;
$isTch   = Yii::app()->user->isTch;
$isAdmin = Yii::app()->user->isAdmin;

$this->widget('zii.widgets.CMenu', array(
    'encodeLabel' => false,
    'htmlOptions' => array(
        'id' => false,
        'class' => 'nav nav-list'
    ),
    'submenuHtmlOptions' => array(
        'class' => 'submenu',
    ),
    'items'=>array_merge(array(
        array(
            'label' => _l('Админ. панель', 'dashboard'),
            'url' => '#',
            'linkOptions'=>$_l,
            'itemOptions'=>array('class'=> $_m=='admin' ? 'active open' : ''),
            'items' => array(
                array(
                    'label'  => $_l2.tt('Администраторы'),
                    'url'    => _u('/admin/default/admin'),
                    'active' => $_a=='admin'
                ),
                array(
                    'label'  => $_l2.tt('Преподаватели'),
                    'url'    => _u('/admin/default/teachers'),
                    'active' => $_a=='teachers' || $_a=='PGrants'
                ),
                array(
                    'label'  => $_l2.tt('Студенты'),
                    'url'    => _u('/admin/default/students'),
                    'active' => $_a=='students' || $_a=='StGrants'
                ),
                array(
                    'label'  => $_l2.tt('Родители'),
                    'url'    => _u('/admin/default/parents'),
                    'active' => $_a=='parents' || $_a=='prntGrants'
                ),
                array(
                    'label'  => $_l2.tt('Эл. журнал'),
                    'url'    => _u('/admin/default/journal'),
                    'active' => $_a=='journal' && $_m=='admin'
                ),
                array(
                    'label'  => $_l2.tt('Список'),
                    'url'    => _u('/admin/default/list'),
                    'active' => $_a=='list' && $_m=='admin'
                ),
                array(
                    'label'  => $_l2.tt('Ведение модулей'),
                    'url'    => _u('/admin/default/modules'),
                    'active' => $_a=='modules' && $_m=='admin'
                ),
                array(
                    'label'  => $_l2.tt('Абитуриент'),
                    'url'    => _u('/admin/default/entrance'),
                    'active' => $_a=='entrance' && $_m=='admin'
                ),
                array(
                    'label'  => $_l2.tt('Трудоустройство'),
                    'url'    => _u('/admin/default/employment'),
                    'active' => $_a=='employment' && $_m=='admin'
                ),
                array(
                    'label'  => $_l2.tt('Меню'),
                    'url'    => _u('/admin/default/menu'),
                    'active' => $_a=='menu' && $_m=='admin'
                ),
                array(
                    'label'  => $_l2.tt('Настройки'),
                    'url'    => _u('/admin/default/settings'),
                    'active' => $_a=='settings' && $_m=='admin'
                ),
                array(
                    'label'  => $_l2.tt('Настройки Портала'),
                    'url'    => _u('/admin/default/settingsPortal'),
                    'active' => $_a=='settingsPortal' && $_m=='admin'
                ),
                array(
                    'label'  => $_l2.tt('Группы пунктов меню (доп.)'),
                    'url'    => _u('/admin/menuGroup'),
                    'active' => $_c=='menuGroup' && $_m=='admin'
                ),
                array(
                    'label'  => $_l2.tt('Пункты меню (доп.)'),
                    'url'    => _u('/admin/menuItem'),
                    'active' => $_c=='menuItem' && $_m=='admin'
                ),
            ),
            'visible' => $isAdmin,
        ),
        array(
            'label' => _l('Расписание', 'calendar'),
            'url' => '#',
            'linkOptions'=> $_l,
            'itemOptions'=>_i('timeTable'),
            'items' =>array_merge( array(
                array(
                    'label'   => $_l2.tt('Личное'),
                    'url'     => _u('/timeTable/self'),
                    'active'  => $_c=='timeTable' && $_a=='self',
                    'visible' => _ch('timeTable', 'self')&& ($isStd||$isTch)
                ),
                array(
                    'label'   => $_l2.tt('Академ. группы'),
                    'url'     => _u('/timeTable/group'),
                    'active'  => $_c=='timeTable' && $_a=='group',
                    'visible' => _ch('timeTable', 'group')
                ),
                array(
                    'label'   => $_l2.tt('Преподавателя'),
                    'url'     => _u('/timeTable/teacher'),
                    'active'  => $_c=='timeTable' && $_a=='teacher',
                    'visible' => _ch('timeTable', 'teacher')
                ),
                array(
                    'label'   => $_l2.tt('Кафедры'),
                    'url'     => _u('/timeTable/chair'),
                    'active'  => $_c=='timeTable' && $_a=='chair',
                    'visible' => _ch('timeTable', 'chair')
                ),
                array(
                    'label'   => $_l2.tt('Студента'),
                    'url'     => _u('/timeTable/student'),
                    'active'  => $_c=='timeTable' && $_a=='student',
                    'visible' => _ch('timeTable', 'student')
                ),
                array(
                    'label'   => $_l2.tt('Аудитории'),
                    'url'     => _u('/timeTable/classroom'),
                    'active'  => $_c=='timeTable' && $_a=='classroom',
                    'visible' => _ch('timeTable', 'classroom')
                ),
                array(
                    'label'   => $_l2.tt('Свободные аудитории'),
                    'url'     => _u('/timeTable/freeClassroom'),
                    'active'  => $_c=='timeTable' && $_a=='freeClassroom',
                    'visible' => _ch('timeTable', 'freeClassroom')
                ),
            ),getDopItem('timeTable',0)),
            'visible' => _ch('timeTable', 'main')
        ),
        array(
            'label' => _l('Рабочий план', 'edit'),
            'url' => '#',
            'linkOptions'=> $_l,
            'itemOptions'=>_i('workPlan'),
            'items' => array_merge(array(
                array(
                    'label'   => $_l2.tt('Специальности'),
                    'url'     => _u('/workPlan/speciality'),
                    'active'  => $_c=='workPlan' && $_a=='speciality',
                    'visible' => _ch('workPlan', 'speciality')
                ),
                array(
                    'label'   => $_l2.tt('Группы'),
                    'url'     => _u('/workPlan/group'),
                    'active'  => $_c=='workPlan' && $_a=='group',
                    'visible' => _ch('workPlan', 'group')
                ),
                array(
                    'label'   => $_l2.tt('Студента'),
                    'url'     => _u('/workPlan/student'),
                    'active'  => $_c=='workPlan' && $_a=='student',
                    'visible' => _ch('workPlan', 'student')
                ),
            ),getDopItem('workPlan',0)),
            'visible' => _ch('workPlan', 'main')
        ),
	array(
            'label' => _l('Список', 'user'),
            'url' => '#',
            'linkOptions'=> $_l,
            'itemOptions'=>_i('list'),
            'items' =>array_merge( array(
                array(
                    'label'   => $_l2.tt('Группы'),
                    'url'     => _u('/list/group'),
                    'active'  => $_c=='list' && $_a=='group',
                    'visible' => _ch('list', 'group')
                ),
                array(
                    'label'   => $_l2.tt('Кафедры'),
                    'url'     => _u('/list/chair'),
                    'active'  => $_c=='list' && $_a=='chair',
                    'visible' => _ch('list', 'chair')
                ),
            ),getDopItem('list',0)),
            'visible' => _ch('list', 'main')
        ),
        array(
            'label' => _l('Эл. журнал', 'list'),
            'url' => '#',
            'linkOptions'=> $_l,
            'itemOptions'=>_i('journal'),
            'items' => array_merge(array(
                array(
                    'label'   => $_l2.tt('Тематический план'),
                    'url'     => _u('/journal/thematicPlan'),
                    'visible' => _ch('journal', 'thematicPlan') && $isTch,
                    'active'  => $_c=='journal' && $_a=='thematicPlan'
                ),
                array(
                    'label'   => $_l2.tt('Эл. журнал'),
                    'url'     => _u('/journal/journal'),
                    'visible' => _ch('journal', 'journal') && $isTch,
                    'active'  => $_c=='journal' && $_a=='journal'
                ),
                array(
                    'label'   => $_l2.tt('Отработка'),
                    'url'     => _u('/journal/retake'),
                    'visible' => _ch('journal', 'retake')&& $isTch,
                    'active'  => $_c=='journal' && $_a=='retake'
                ),
                array(
                    'label'   => $_l2.tt('Регистрация пропусков занятий'),
                    'url'     => _u('/journal/omissions'),
                    'visible' => _ch('journal', 'omissions')&& $isTch,
                    'active'  => $_c=='journal' && $_a=='omissions'
                ),
                array(
                    'label'   => $_l2.tt('Статистика посещаемости'),
                    'url'     => _u('/journal/attendanceStatistic'),
                    'visible' => _ch('journal', 'attendanceStatistic'),
                    'active'  => $_c=='journal' && $_a=='attendanceStatistic'
                ),
            ),getDopItem('journal',0)),
            'visible' => _ch('journal', 'main')
        ),
        array(
            'label' => _l('Успеваемость', 'list'),
            'url' => '#',
            'linkOptions'=> $_l,
            'itemOptions'=>_i('progress'),
            'items' => array_merge(array(
				array(
                    'label'   => $_l2.tt('Рейтинг'),
                    'url'     => _u('/progress/rating'),
                    'active'  => $_c=='progress' && $_a=='rating',
                    'visible' => _ch('progress', 'rating')
                ),
                array(
                    'label'   => $_l2.tt('Тестирование'),
                    'url'     => _u('/progress/test'),
                    'visible' => _ch('progress', 'test') && $isStd,
                    'active'  => $_c=='progress' && $_a=='test'
                ),
                array(
                    'label'   => $_l2.tt('Ведение модулей'),
                    'url'     => _u('/progress/module'),
                    'visible' => _ch('progress', 'modules') && $isTch,
                    'active'  => $_c=='progress' && $_a=='module'
                ),
                array(
                    'label'   => $_l2.tt('Экз. сессия'),
                    'url'     => _u('/progress/examSession'),
                    'visible' => _ch('progress', 'examSession') && $isTch,
                    'active'  => $_c=='progress' && $_a=='examSession'
                ),
            ),getDopItem('progress',0)),
            'visible' => _ch('progress', 'main')
        ),
        array(
            'label' => _l('Док.-оборот', 'folder-open'),
            'url' => '#',
            'linkOptions'=> $_l,
            'itemOptions'=>_i('docs'),
            'items' => array(
                array(
                    'label'   => $_l2.tt('Документооборот'),
                    'url'     => _u('/docs/tddo'),
                    'visible' => _ch('docs', 'tddo') && $isTch,
                    'active'  => $_c=='docs' && stristr($_a, 'tddo')
                ),
            ),
            'visible' => _ch('docs', 'main') && $isTch,
        ),
        array(
            'label' => _l('Абитуриент', 'book'),
            'url' => '#',
            'linkOptions'=> $_l,
            'itemOptions'=>_i('entrance'),
            'items' => array_merge(array(
                array(
                    'label'   => $_l2.tt('Ход приема документов'),
                    'url'     => _u('/entrance/documentReception'),
                    'visible' => _ch('entrance', 'documentReception'),
                    'active'  => $_c=='entrance' && $_a=='documentReception'
                ),
                array(
                    'label'   => $_l2.tt('Рейтинговый список'),
                    'url'     => _u('/entrance/rating'),
                    'visible' => _ch('entrance', 'rating'),
                    'active'  => $_c=='entrance' && $_a=='rating' && !Yii::app()->request->getParam('sortByStatus', null)
                ),
                array(
                    'label'   => $_l2.tt('Список рекомендованных'),
                    'url'     => _u('/entrance/rating', array('sortByStatus' => 1)),
                    'visible' => _ch('entrance', 'rating'),
                    'active'  => $_c=='entrance' && $_a=='rating' && Yii::app()->request->getParam('sortByStatus', null)
                ),
                array(
                    'label'   => $_l2.tt('Регистрация'),
                    'url'     => _u('/entrance/registration'),
                    'visible' => _ch('entrance', 'registration'),
                    'active'  => $_c=='entrance' && $_a=='registration'
                ),
            ),getDopItem('entrance',0)),
            'visible' => _ch('entrance', 'main')
        ),
        array(
            'label' => _l('Нагрузка', 'briefcase'),
            'url' => '#',
            'linkOptions'=> $_l,
            'itemOptions'=>_i('workLoad'),
            'items' =>array_merge( array(
                array(
                    'label'   => $_l2.tt('Личная'),
                    'url'     => _u('/workLoad/self'),
                    'visible' => _ch('workLoad', 'self') && $isTch,
                    'active'  => $_c=='workLoad' && $_a=='self'
                ),
                array(
                    'label'   => $_l2.tt('Преподавателя'),
                    'url'     => _u('/workLoad/teacher'),
                    'visible' => _ch('workLoad', 'teacher'),
                    'active'  => $_c=='workLoad' && $_a=='teacher'
                ),
                array(
                    'label'   => $_l2.tt('Объем учебной нагрузки'),
                    'url'     => _u('/workLoad/amount'),
                    'visible' => _ch('workLoad', 'amount'),
                    'active'  => $_c=='workLoad' && $_a=='amount'
                ),
            ),getDopItem('workLoad',0)),
            'visible' => _ch('workLoad', 'main')
        ),
        array(
            'label' => _l('Оплата', 'money'),
            'url' => '#',
            'linkOptions'=> $_l,
            'itemOptions'=>_i('payment'),
            'items' => array_merge(array(
                array(
                    'label'   => $_l2.tt('Общежитие'),
                    'url'     => _u('/payment/hostel'),
                    'visible' => _ch('payment', 'hostel'),
                    'active'  => $_c=='payment' && $_a=='hostel'
                ),
                array(
                    'label'   => $_l2.tt('Обучение'),
                    'url'     => _u('/payment/education'),
                    'visible' => _ch('payment', 'education'),
                    'active'  => $_c=='payment' && $_a=='education',
                ),
            ),getDopItem('payment',0)),
            'visible' => _ch('payment', 'main') && $isStd
        ),
        array(
            'label' => _l('Другое', 'globe'),
            'url' => '#',
            'linkOptions'=> $_l,
            'itemOptions'=>_i('other'),
            'items' =>array_merge( array(
                array(
                    'label'   => $_l2.tt('Телефонный справочник'),
                    'url'     => _u('/other/phones'),
                    'visible' => _ch('other', 'phones'),
                    'active'  => $_c=='other' && $_a=='phones'
                ),
                array(
                    'label'   => $_l2.tt('Запись на гос. экзамены'),
                    'url'     => _u('/other/gostem'),
                    'active'  => $_c=='other' && $_a=='gostem',
                    'visible' => _ch('other', 'gostem') && $isStd,
                ),
                array(
                    'label'   => $_l2.tt('Заказ переноса занятий'),
                    'url'     => _u('/other/orderLesson'),
                    'active'  => $_c=='other' && $_a=='orderLesson',
                    'visible' => _ch('other', 'orderLesson') && $isTch,
                ),
                array(
                    'label'   => $_l2.tt('Трудоустройство'),
                    'url'     => _u('/other/employment'),
                    'active'  => $_c=='other' && $_a=='employment',
                    'visible' => _ch('other', 'employment'),
                ),
                array(
                    'label'   => $_l2.tt('Запись на дисциплины'),
                    'url'     => _u('/other/subscription'),
                    'active'  => $_c=='other' && $_a=='subscription',
                    'visible' => _ch('other', 'subscription') && $isStd,
                ),
                array(
                    'label'   => $_l2.tt('Данные студенты'),
                    'url'     => _u('/other/studentInfo'),
                    'active'  => $_c=='other' && $_a=='studentInfo',
                    'visible' => _ch('other', 'studentInfo') && ($isTch || $isStd),
                ),
            ),getDopItem('other',0)),
            'visible' => _ch('other', 'main')
        ),
    ),getDopGroup($_l)),
));

