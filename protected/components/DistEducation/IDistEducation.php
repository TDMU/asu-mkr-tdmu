<?php
/**
 * Created by PhpStorm.
 * User: NEFF
 * Date: 06.11.2017
 * Time: 16:01
 */

/**
 * Interface IDistEducation
 * Коннектор для дистанционного обучения
 */
interface IDistEducation
{
    /**
     * Геттер для хоста
     * @return mixed
     */
    public function getHost();

    /**
     * Регистрация в системе дистанционного обучения
     * @param Users $user
     * @return mixed
     */
    public function signUp($user);

    /**
     * Привязать к уже существующей
     * @param $user Users
     * @param $params array
     * @return mixed
     */
    public function signUpOld($user, $params);

    /**
     * Список курсов
     * @return mixed
     */
    public function getCoursesList();

    /**
     * Инфо по курсу по id
     * @param string|int id
     * @return object|null|array
     */
    public function getCourse($id);

    /**
     * Авторизация в системе дистанционного обучения
     * @param $user Users
     * @return mixed
     */
    public function login($user);

    /**
     * IDistEducation constructor.
     * @param string $host Хост
     * @param string $appKey Апкей
     */
    public function __construct($host, $appKey);

    /**
     * Список курсов для combobox @see CHtml::listData()
     * @return mixed
     */
    //public function getCoursesListForLisData();

    /**
     * Колонки для грид вьюва
     * @return array
     */
    public function getColumnsForGridView();

    /**
     * Сохранения привязки
     * @param $uo1 int
     * @param $course object|array
     * @return bool
     */
    public function saveLinkCourse($uo1, $course);

    /**
    * Список курсов
     * @param string $email
    * @return bool
    */
    public function validateEmail($email);

    /**
     * Записать студента на курс
     * @param $st St
     * @param $ucgns1
     * @return mixed
     */
    public function subscribeToCourse($st, $ucgns1);

    /**
     * Отписаит студента с курса
     * @param $st St
     * @param $ucgns1
     * @return mixed
     */
    public function unsubscribeToCourse($st, $ucgns1);
}