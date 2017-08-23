<?php
/**
 *
 * @var DefaultController $this
 */

    $this->pageHeader=tt('Отображение пунктов меню');
    $this->breadcrumbs=array(
        tt('Админ. панель'),
    );

    Yii::app()->clientScript->registerPackage('nestable');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/admin/menu.js');

?>

<button type="button" class="btn btn-info btn-small save-form">
    <i class="icon-ok bigger-110"></i>
    <?=tt('Сохранить')?>
</button>

<form id="menu" method="post">
    <?php
        $this->renderPartial('menu/_block', array(
            'settings' => $settings,
            'blocks' => array(
                array(
                    'name'       => 'Абитуриент',
                    'controller' => 'entrance',
                    'items' => array(
                        'documentReception' => 'Ход приема документов',
                        'rating'            => 'Рейтинговый список',
                        'registration'      => 'Регистрация',
                    ),
                ),
                array(
                    'name'       => 'Расписание',
                    'controller' => 'timeTable',
                    'items' => array(
                        'self'         => 'Личное',
                        'group'         => 'Академ. группы',
                        'teacher'       => 'Преподавателя',
                        'chair'       => 'Кафедры',
                        'student'       => 'Студента',
                        'classroom'     => 'Аудитории',
                        'freeClassroom' => 'Свободные аудитории',
                    ),
                ),
                array(
                    'name'       => 'Рабочий план',
                    'controller' => 'workPlan',
                    'items' => array(
                        'speciality' => 'Специальности',
                        'group'      => 'Группы',
                        'student'    => 'Студента',
                    ),
                ),
                array(
                    'name'       => 'Эл. журнал',
                    'controller' => 'journal',
                    'items' => array(
                        'stFinBlockStatisticExcel' => 'Статистика блокировки неоплативших студентов',
                        'thematicPlan' => 'Тематический план',
                        'journal' => 'Эл. журнал',
                        'retake' => 'Отработка',
                        'omissions'=>'Регистрация пропусков занятий',
                        'attendanceStatistic'=>'Статистика посещаемости',
                        'attendanceStatisticPrint'=>'Статистика посещаемости на поток',
                        'stJournal'=>'Ввод посещаемости (для старост)'
                    ),
                ),
                array(
                    'name'       => 'Для моб.',
                    'controller' => 'mobile',
                    'items' => array(
                        'journal' => 'Эл. журнал (моб.)',
                    ),
                ),
                array(
                    'name'       => 'Успеваемость',
                    'controller' => 'progress',
                    'items' => array(
                        'rating'      => 'Рейтинг',
                        'test'=>'Тестирование',
                        'modules'      => 'Ведение модулей',
                        'examSession'=>'Экз. сессия'
                    ),
                ),
				array(
                    'name'       => 'Список',
                    'controller' => 'list',
                    'items' => array(
                        'group'      => 'Группы',
                        'chair'      => 'Кафедры',
                        'virtualGroup' => 'Виртуальные группы',
                    ),
                ),
                array(
                    'name'       => 'Док.-оборот',
                    'controller' => 'doc',
                    'items' => array(
                        'index' => 'Документооборот',
                        'selfDoc' => 'Личные документы',
                    ),
                ),
                array(
                    'name'       => 'Нагрузка',
                    'controller' => 'workLoad',
                    'items' => array(
                        'self'    => 'Личная',
                        'teacher' => 'Преподавателя',
                        'amount'  => 'Объем учебной нагрузки',
                    ),
                ),
                array(
                    'name'       => 'Оплата',
                    'controller' => 'payment',
                    'items' => array(
                        'hostel'    => 'Общежитие',
                        'education' => 'Обучение',
                        'hostelCurator'    => 'Общежитие (кур.)',
                        'educationCurator' => 'Обучение (кур.)',
                    ),
                ),
                array(
                    'name'       => 'Другое',
                    'controller' => 'other',
                    'items' => array(
                        'phones' => 'Телефонный справочник',
                        'gostem' => 'Запись на гос. экзамены',
                        'employment' => 'Трудоустройство',
                        'subscription' => 'Запись на дисциплины',
                        'orderLesson' => 'Заказ переноса заняти',
                        'studentInfo' => 'Данные студенты',
                        'studentCard' => 'Карточка студента',
                    ),
                ),
            )
        ));
    ?>
</form>
