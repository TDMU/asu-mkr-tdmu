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
        $teacherStr = tt('Преподаватель');
        $studentStr = tt('Студент');
        $parentStr = tt('Преподаватель');
        $doctorStr = tt('Доктор');

        $this->renderPartial('menu/_block', array(
            'settings' => $settings,
            'blocks' => array(
                array(
                    'name'       => 'Личное',
                    'controller' => 'self',
                    'items' => array(
                        'workLoad'    => array(
                            'name'=>'Личная нагрузка',
                            'authOnly' => $teacherStr
                        ),
                        'timeTable'         => array(
                            'name'=>'Личное раписание',
                            'authOnly' => array(
                                $teacherStr,
                                $studentStr
                            )
                        ),
                        'gostem' => array(
                            'name'=>'Запись на гос. экзамены',
                            'authOnly' => $studentStr
                        ),
                        'subscription' => array(
                            'name'=>'Запись на выборочные дисциплины',
                            'authOnly' => $studentStr
                        ),
                        'orderLesson' => array(
                            'name'=>'Заказ переноса занятия',
                            'authOnly' => $teacherStr
                        ),
                        'studentInfo' => 'Данные студенты',
                        'studentCard' => 'Карточка студента',
                    ),
                ),
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
                        'self'         => array(
                            'name'=>'Личное',
                            'authOnly' => array(
                                $teacherStr,
                                $studentStr
                            )
                        ),
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
                        'thematicPlan' => array(
                            'name'=>'Тематический план',
                            'authOnly' => $teacherStr
                        ),
                        'journal' => array(
                            'name'=>'Эл. журнал',
                            'authOnly' => $teacherStr
                        ),
                        'retake' => array(
                            'name'=>'Отработка',
                            'authOnly' => $teacherStr
                        ),
                        'omissions' => array(
                            'name'=>'Регистрация пропусков занятий',
                            'authOnly' => $teacherStr
                        ),
                        'attendanceStatistic'=>'Статистика посещаемости',
                        'newAttendanceStatistic'=> array(
                            'name'=>'Статистика посещаемости',
                            'authOnly' => $teacherStr
                        ),
                        'attendanceStatisticPrint'=>'Статистика посещаемости на поток',
                        'stJournal'=> array(
                            'name'=>'Ввод посещаемости (для старост)',
                            'authOnly' => $studentStr
                        )
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
                        'test'=> array(
                            'name'=>'Тестирование',
                            'authOnly' => $studentStr
                        ),
                        'modules' =>  array(
                            'name'=>'Ведение модулей',
                            'authOnly' => $teacherStr
                        ),
                        'examSession'=>array(
                            'name'=>'Экз. сессия',
                            'authOnly' => $teacherStr
                        )
                    ),
                ),
				array(
                    'name'       => 'Список',
                    'controller' => 'list',
                    'items' => array(
                        'group'      => 'Академ. группы',
                        'stream'      => 'Потока',
                        'chair'      => 'Кафедры',
                        'virtualGroup' => 'Группы по выборочным дисциплинам',
                    ),
                ),
                array(
                    'name'       => 'Документооборот',
                    'controller' => 'doc',
                    'items' => array(
                        'index' => 'Документооборот',/* array(
                            'name'=>'Документооборот',
                            //'authOnly' => $teacherStr
                        ),*/
                        'selfDoc' => array(
                            'name'=>'Личные документы',
                            'authOnly' => $teacherStr
                        )
                    ),
                ),
                array(
                    'name'       => 'Дист.образование',
                    'controller' => 'distEducation',
                    'items' => array(
                        'index' => array(
                            'name'=>'Закрепление',
                            'authOnly' => $teacherStr
                        ),
                        'subscription' => array(
                            'name'=>'Запись',
                            'authOnly' => $teacherStr
                        ),
                        'subscriptionsList' => array(
                            'name'=>'Итоги записи',
                            'authOnly' => $teacherStr
                        ),
                    ),
                ),
                array(
                    'name'       => 'Нагрузка',
                    'controller' => 'workLoad',
                    'items' => array(
                        'self'    => array(
                            'name'=>'Личная',
                            'authOnly' => $teacherStr
                        ),
                        'teacher' => 'Преподавателя',
                        'amount'  => 'Объем учебной нагрузки',
                    ),
                ),
                array(
                    'name'       => 'Оплата',
                    'controller' => 'payment',
                    'items' => array(
                        'hostel'    =>  array(
                            'name'=>'Общежитие',
                            'authOnly' => $studentStr
                        ),
                        'education' => array(
                            'name'=>'Обучение',
                            'authOnly' => $studentStr
                        ),
                        'hostelCurator'    => array(
                            'name'=>'Общежитие (кур.)',
                            'authOnly' => $teacherStr
                        ),
                        'educationCurator' => array(
                            'name'=>'Обучение (кур.)',
                            'authOnly' => $teacherStr
                        )
                    ),
                ),
                array(
                    'name'       => 'Опрос',
                    'controller' => 'quiz',
                    'items' => array(
                        'controller' => 'index',
                    ),
                ),
                array(
                    'name'       => 'Другое',
                    'controller' => 'other',
                    'items' => array(
                        'phones' => 'Телефонный справочник',
                        'gostem' => array(
                            'name'=>'Запись на гос. экзамены',
                            'authOnly' => $studentStr
                        ),
                        'employment' => 'Трудоустройство',
                        'subscription' => array(
                            'name'=>'Запись на выборочные дисциплины',
                            'authOnly' => $studentStr
                        ),
                        'orderLesson' => array(
                            'name'=>'Заказ переноса занятия',
                            'authOnly' => $teacherStr
                        ),
                        'studentInfo' => 'Данные студенты',
                        'studentCard' => 'Карточка студента',
                        'antiplagiat' =>  array(
                            'name'=>'Антиплагиат',
                            'authOnly' => $studentStr
                        )
                    ),
                ),
            )
        ));
    ?>
</form>
