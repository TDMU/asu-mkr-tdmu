<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 14.12.2016
 * Time: 20:46
 */

class DocController extends Controller
{
    public function filters() {

        return array(
            'accessControl',
            //'checkPermission -index, -selfDoc'//не выполянеться для индекса
        );
    }

    public function accessRules() {

        return array(
            array('allow',
                'actions' => array(
                    'selfDoc'
                ),
                'expression' => 'Yii::app()->user->isAdmin || Yii::app()->user->isTch',
            ),
            array('allow',
                'actions' => array(
                    'index',
                    'view',
                    'file'
                ),
                'expression' => 'Yii::app()->user->isAdmin || Yii::app()->user->isTch || Yii::app()->user->isStd',
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function filterCheckPermission($filterChain)
    {
        if(!Yii::app()->user->isAdmin) {
            $grants = Yii::app()->user->dbModel->grants;

            if (empty($grants))
                throw new CHttpException(403, 'Invalid request. You don\'t have access to the service.');

            if ($grants->grants5 != 1)
                throw new CHttpException(403, 'Invalid request. You don\'t have access to the service.');
        }
        $filterChain->run();
    }

    public function actionIndex()
    {
        $docType = Yii::app()->request->getParam('docType', null);
        $docYear = Yii::app()->request->getParam('docYear', null);

        $model = new Tddo();
        $model->unsetAttributes();

        //$model->tddo2 = $docType;

        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize',(int)$_GET['pageSize']);
            unset($_GET['pageSize']);  // сбросим, чтобы не пересекалось с настройками пейджера
        }

        /*if (isset($_REQUEST['Tddo'])) {
            $model->scenario = 'filter';
            $model->attributes = $_REQUEST['Tddo'];
        }*/
        $model->scenario = 'filter';
        if (isset($_REQUEST['Tddo']))
        {
            $model->attributes = $_REQUEST['Tddo'];
            Yii::app()->user->setState('SearchParamsTddo', $_REQUEST['Tddo']);
        }
        else
        {
            $searchParams = Yii::app()->user->getState('SearchParamsTddo');
            if ( isset($searchParams) )
            {
                $model->attributes = $searchParams;
            }
        }

        $model->tddo2 = $docType;
        if(!empty($docYear))
            $model->tddo23 = $docYear;

        $this->render('index', array(
            //'docType' => $docType,
            'model'   => $model
        ));
    }

    public function actionSelfDoc()
    {
        $docType = Yii::app()->request->getParam('docType', null);
        $docYear = Yii::app()->request->getParam('docYear', null);

        $model = new Tddo();
        $model->unsetAttributes();

        //$model->tddo2 = $docType;

        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize',(int)$_GET['pageSize']);
            unset($_GET['pageSize']);  // сбросим, чтобы не пересекалось с настройками пейджера
        }

        $model->scenario = 'filter';
        if (isset($_REQUEST['Tddo']))
        {
            $model->attributes = $_REQUEST['Tddo'];
            Yii::app()->user->setState('SearchParamsTddo', $_REQUEST['Tddo']);
        }
        else
        {
            $searchParams = Yii::app()->user->getState('SearchParamsTddo');
            if ( isset($searchParams) )
            {
                $model->attributes = $searchParams;
            }
        }

        $model->tddo2 = $docType;
        if(!empty($docYear))
            $model->tddo23 = $docYear;

        $this->render('self-doc', array(
            'model'   => $model
        ));
    }

    public function loadModel($id)
    {
        $model=Tddo::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Проверка доступа к документу
     * @param $tddo Tddo
     * @return bool
     * @throws
     */
    private function checkAccessToDoc($tddo){
        if($tddo->tddo24 == 1)
            return false;

        if(Yii::app()->user->isAdmin)
            return true;

        if(Yii::app()->user->isStd) {
            if($tddo->tddo26 != 1)
                return false;
            else
                return true;
        }

        if($tddo->tddo25 == 1)
            return true;

        $sql = <<<SQL
              SELECT COUNT(*) FROM IDO
              INNER JOIN PD on (IDO2=PD1)
              WHERE PD2=:P1 AND IDO1=:TDDO1
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':TDDO1', $tddo->tddo1);
        $command->bindValue(':P1', Yii::app()->user->dbModel->p1);
        $count = $command->queryScalar();

        $result = $count>0;

        if($result)
            return $result;

        $sql = <<<SQL
              SELECT COUNT(*) FROM IDO
              WHERE IDO9=:P1 AND IDO1=:TDDO1
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':TDDO1', $tddo->tddo1);
        $command->bindValue(':P1', Yii::app()->user->dbModel->p1);
        $count = $command->queryScalar();

        $result = $count>0;

        if($result)
            return $result;

        $sql = <<<SQL
              SELECT COUNT(*) FROM IDO
              INNER JOIN INNFP on (IDO4=INNFP2)
              WHERE INNFP1=:P1 AND IDO1=:TDDO1
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':TDDO1', $tddo->tddo1);
        $command->bindValue(':P1', Yii::app()->user->dbModel->p1);
        $count = $command->queryScalar();

        $result = $count>0;

        return $result;
    }

    public function actionView($id){
        $model = $this->loadModel($id);

        if(!$this->checkAccessToDoc($model))
            throw new CHttpException(403,'Your don`t have access to this doc');

        $this->render('view',array(
            'model'=>$model
        ));
    }

    public function actionFile($id){
        /**
         * @var $file Fpdd
         */
        $file = Fpdd::model()->findByPk($id);

        if(empty($file))
            throw new CHttpException(404,'The requested page does not exist.');

        $model = $this->loadModel($file->fpdd2);

        if(!$this->checkAccessToDoc($model))
            throw new CHttpException(403,'Your don`t have access to this doc');

        header('Content-Disposition: inline; filename="'.$file->fpdd4.'"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        if($file->isImage())
            header("Content-type: image/".$file->getExtension());
        else
            header("Content-type: application/".$file->getExtension());
        echo $file->fpdd3;
    }
}