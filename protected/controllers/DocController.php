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
            'checkPermission -index, -selfDoc'//не выполянеться для индекса
        );
    }

    public function accessRules() {

        return array(
            array('allow',
                'actions' => array(
                    'index',
                    'selfDoc',
                    'view',
                    'file'
                ),
                'expression' => 'Yii::app()->user->isAdmin || Yii::app()->user->isTch',
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function filterCheckPermission($filterChain)
    {
        /*if(!Yii::app()->user->isAdmin) {
            $grants = Yii::app()->user->dbModel->grants;

            if (empty($grants))
                throw new CHttpException(404, 'Invalid request. You don\'t have access to the service.');

            if ($grants->grants5 != 1)
                throw new CHttpException(404, 'Invalid request. You don\'t have access to the service.');
        }*/
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
     * @param $id tdoo1
     * @return bool
     */
    private function checkAccessToDoc($id){
        if(!Yii::app()->user->isAdmin) {

            if(Yii::app()->user->isStd)
                return false;

            $sql = <<<SQL
              SELECT COUNT(*) FROM IDO
              INNER JOIN PD on (IDO2=PD1)
              WHERE PD2=:P1 AND IDO1=:TDDO1
SQL;
            $command = Yii::app()->db->createCommand($sql);
            $command->bindValue(':TDDO1', $id);
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
            $command->bindValue(':TDDO1', $id);
            $command->bindValue(':P1', Yii::app()->user->dbModel->p1);
            $count = $command->queryScalar();

            $result = $count>0;

            return $result;
        }else
            return true;
    }

    public function actionView($id){
        $model = $this->loadModel($id);

        if(!$this->checkAccessToDoc($id))
            throw new CHttpException(403,'Your don`t have access to this doc');

        $this->render('view',array(
            'model'=>$model
        ));
    }

    public function actionFile($id){

        $dbh = SH::getGrafConnection();

        try{
            $dbh->active = true;
        }catch(Exception $error) {
            throw new Exception("Ошибка подключения к uрафической базе, с ошибкой: " . $error->getMessage());
        }

        $sql = <<<SQL
			SELECT *
			FROM fpdd
			WHERE fpdd1 = {$id}
SQL;
        $command=$dbh->createCommand($sql);
        $file=$command->queryRow();
        $dbh->active = false;

        if(empty($file))
            throw new CHttpException(404,'The requested page does not exist.');

        $model = $this->loadModel($file['FPDD2']);

        if(!$this->checkAccessToDoc($file['FPDD2']))
            throw new CHttpException(403,'Your don`t have access to this doc');

        if(Tddo::model()->isImage($file['FPDD4'])) {
            header("Content-type: image/jpeg");
        }else{
            header("Content-type: application/".Tddo::model()->getExtByName($file['FPDD4']));
        }
        header('Content-Disposition: filename="'.$file['FPDD4'].'"');
        echo $file['FPDD3'];
    }
}