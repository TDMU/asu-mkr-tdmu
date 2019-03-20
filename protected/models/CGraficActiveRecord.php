<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 15.03.2019
 * Time: 18:14
 */

/**
 * Базовая моделька для графической базы
 * Class CGraficActiveRecord
 */
class CGraficActiveRecord extends CActiveRecord
{
    /**
     * @return CDbConnection the database connection used for this class
     * @throws
     */
    public function getDbConnection()
    {
        return Yii::app()->db2;
    }
}