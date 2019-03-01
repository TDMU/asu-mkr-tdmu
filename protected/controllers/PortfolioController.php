<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 16.05.2018
 * Time: 10:33
 */

class PortfolioController extends Controller
{
    public function filters() {
        return array(
            'accessControl',
            'checkPermission'
        );
    }

    public function accessRules() {

        return array(
            array('allow',
                'actions' => array(
                    'teacher'
                ),
                'expression' => 'Yii::app()->user->isTch || Yii::app()->user->isAdmin',
            ),
            array('allow',
                'actions' => array(
                    'student',
                ),
                'expression' => 'Yii::app()->user->isStd || Yii::app()->user->isAdmin',
            ),
            array('allow',
                'actions' => array(
                    'uploadFile',
                    'showFile',
                    'removeFile'
                ),
                'users' => array('@'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    /**
     * Дополнительная проверка
     * @param $filterChain
     * @throws CHttpException
     */
    public function filterCheckPermission($filterChain)
    {
        if(PortalSettings::model()->getSettingFor(PortalSettings::USE_PORTFOLIO)==0)
            throw new CHttpException(403, tt('Данный сервис не активный, обратитесь к администратору.'));

        $parh = PortalSettings::model()->getSettingFor(PortalSettings::PORTFOLIO_PATH);
        if(empty($parh))
            throw new CHttpException(403, tt('Не заданы необходимые настройки, обратитесь к администратору.'));

        if(!$this->_checkPath())
            throw new CHttpException(403, tt('Неверные настройки, обратитесь к администратору.'));

        $filterChain->run();
    }

    /**
     *
     * @throws CHttpException
     */
    public function actionStudent()
    {
        $model = new TimeTableForm;
        $model->scenario = 'student';

        if (Yii::app()->user->isAdmin) {
            $params = array();
            if (!isset($_REQUEST['TimeTableForm'])) {
                if (isset(Yii::app()->session['TimeTableForm'])){
                    $params=Yii::app()->session['TimeTableForm'];
                }
            }
            else{
                $params = $_REQUEST['TimeTableForm'];
            }
            $model->attributes = $params;
            Yii::app()->session['TimeTableForm']=$params;

        } elseif (Yii::app()->user->isStd) {

            $model->student = Yii::app()->user->dbModel->st1;
        } else
            throw new CHttpException(404, 'You don\'t have an access to this service');

        $this->render('student', array(
            'model'=>$model
        ));
    }

    /**
     * @throws CHttpException
     */
    public function actionTeacher()
    {
        $model = new FilterForm();
        $model->scenario = 'portfolio-teacher';

        $params = array();
        if (!isset($_REQUEST['FilterForm'])) {
            if (isset(Yii::app()->session['FilterForm'])){
                $params=Yii::app()->session['FilterForm'];
            }
        }
        else{
            $params = $_REQUEST['FilterForm'];
        }
        $model->attributes = $params;
        Yii::app()->session['FilterForm']=$params;

        if (Yii::app()->user->isAdmin) {


        } elseif (Yii::app()->user->isTch) {

            $model->teacher = Yii::app()->user->dbModel->p1;
        } else
            throw new CHttpException(404, 'You don\'t have an access to this service');

        $this->render('teacher', array(
            'model'=>$model
        ));
    }

    /**
     * Проыерка существования директории
     * @return bool
     */
    private function _checkPath(){
        $path = PortalSettings::model()->getSettingFor(PortalSettings::PORTFOLIO_PATH);

        if(empty($path))
            return false;

        return is_dir($path);
    }

    /**
     * Проверка доступа к файлу для
     * @param Zrst $zrst
     * @param int $accessLevel 0 - чтение, 1 - редактирование
     * @return bool
     */
    private function _checkPermissionUserForFile($zrst, $accessLevel = 0){
        if(Yii::app()->user->isAdmin)
            return true;

        if(Yii::app()->user->isStd) {
            if($accessLevel == 0)
                return $zrst->zrst2 == Yii::app()->user->dbModel->st1;
            else
                return $zrst->zrst2 == Yii::app()->user->dbModel->st1 && $zrst->zrst6 == 0;
        }

        if(Yii::app()->user->isTch){
            if($accessLevel == 0)
                return true;
            else

                return $zrst->checkAccessForTeacher(Yii::app()->user->dbModel->p1);
        }

        return false;
    }

    /**
     * Удаление файла
     * @param $id
     * @throws CDbException
     * @throws CHttpException
     */
    public function actionRemoveFile($id){
        $model=$this->_loadModel($id);

        if(!$this->_checkPermissionUserForFile($model, 1))
            throw new CHttpException(403,'You don\'t have an access to this service.');

        $fileName = PortalSettings::model()->getSettingFor(PortalSettings::PORTFOLIO_PATH).'/'.$id.'.'.$model->zrst8;

        if(!file_exists($fileName))
            throw new CHttpException(400,tt('Файл не существует или удален.'.$fileName));

        try {
            if ($model->delete()) {
                unlink($fileName);
            } else {
                throw  new Exception('Ошибка');
            }

            Yii::app()->user->setFlash('success', 'Файл успешно удален');

        }catch (Exception $error){
            Yii::app()->user->setFlash('error', 'Ошибка удаления файла '. $error);
        }

        $this->_redirect($model->zrst6 == 0 ? 'student' : 'teacher');
    }

    private function _redirect($actionForAdmin = ''){
        $url = !Yii::app()->user->isAdmin ?
            (

                Yii::app()->user->isStd ? 'student' : 'teacher'
            ) :
            (
                empty($actionForAdmin) ? 'teacher': $actionForAdmin
            );

        $this->redirect('/portfolio/'.$url);
    }

    /**
     * Экшен отображения файла
     * @param $id
     * @throws CHttpException
     */
    public function actionShowFile($id){
        $model=$this->_loadModel($id);

        if(!$this->_checkPermissionUserForFile($model, 0))
            throw new CHttpException(403,'You don\'t have an access to this service.');

        $fileName = PortalSettings::model()->getSettingFor(PortalSettings::PORTFOLIO_PATH).'/'.$id.'.'.$model->zrst8;

        if(!file_exists($fileName))
            throw new CHttpException(400,tt('Файл не существует или удален.'));

        header('Content-Type: application/'.$model->zrst8);
        header('Content-Disposition: inline; filename="'.basename($fileName).'"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        header('Content-Length: ' . filesize($fileName));
        @readfile($fileName);
        exit;
    }

    /**
     * @param $id
     * @return Zrst|null
     * @throws CHttpException
     */
    private function _loadModel($id)
    {
        $model=Zrst::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Загрузка файла
     * @param $type
     * @param $id
     * @param int $us1
     * @throws CHttpException
     */
    public function actionUploadFile($type, $id, $us1 = 0){
        if(!in_array($type, array(
            CreateZrstForm::TYPE_TABLE1,
            CreateZrstForm::TYPE_TABLE2,
            CreateZrstForm::TYPE_TABLE3,
            CreateZrstForm::TYPE_TABLE4,
            CreateZrstForm::TYPE_TABLE5,
            CreateZrstForm::TYPE_TEACHER
        )))
            throw new CHttpException(400, 'Bad request');

        if(in_array($type, array(
            CreateZrstForm::TYPE_TABLE2,
            CreateZrstForm::TYPE_TABLE3,
            CreateZrstForm::TYPE_TABLE4,
            CreateZrstForm::TYPE_TABLE5
        )))
            $us1 = 0;

        $model = new CreateZrstForm($type);

        if(isset($_POST['CreateZrstForm']))
        {
            $model->attributes=$_POST['CreateZrstForm'];
            $model->st1 = $id;
            $model->us1 = $us1;
            $model->file=CUploadedFile::getInstance($model,'file');

            if ($model->validate()) {

                try {
                    if ($model->save()) {
                        Yii::app()->user->setFlash('success', 'Файл успешно добавлен');
                    } else {
                        Yii::app()->user->setFlash('error', 'Ошибка добавления файла1');
                    }
                }catch (CException $error){
                    Yii::app()->user->setFlash('error', 'Ошибка добавления файла '. $error);
                }
                $this->_redirect($model->scenario == CreateZrstForm::TYPE_TEACHER ? 'teacher' : 'student');
            }
        }

        $this->render('upload',array('model'=>$model));
    }
}