<?php

/**
 * Class WebUser
 * @property bool $isStd  Являеться ли студентом
 * @property bool $isAdmin  Являеться ли администратором
 * @property bool $isTch  Являеться ли преподавателем
 * @property bool $isPrnt  Являеться ли родителем
 * @property bool $isBlock Заблокирован ли
 * @property P|St $dbModel  модель персоні
 * @property Users|null $model модель побзователя
 */
class WebUser extends CWebUser
{
    private $_model = null;

    public function getModel()
    {
        $id = Yii::app()->user->id;

        if (! $this->isGuest && $this->_model === null) {
            $this->_model = Users::model()->findByPk($id);
        }
        return $this->_model;
    }

    public function getIsAdmin()
    {
        return !$this->isGuest && $this->model->isAdmin;
    }

    public function getIsBlock()
    {
        return !$this->isGuest && $this->model->isBlock;
    }

    public function getName()
    {
        if (! $this->isGuest)
            return $this->model->name;

        return 'Guest';
    }

    public function getIsTch()
    {
        //if ($this->isAdmin)
        //    return true;

        return !$this->isGuest && $this->model->isTeacher;
    }

    public function getIsStd()
    {
        return !$this->isGuest && $this->model->isStudent;
    }

    public function getIsPrnt()
    {
        return !$this->isGuest && $this->model->isParent;
    }

    public function getDbModel()
    {
        $model = null;

        if ($this->isTch)
            $model = P::model()->findByPk($this->model->u6);
        elseif ($this->isStd)
            $model = St::model()->findByPk($this->model->u6);
        elseif ($this->isPrnt)
            $model = St::model()->findByPk($this->model->u6); null;

        return $model;
    }

}

