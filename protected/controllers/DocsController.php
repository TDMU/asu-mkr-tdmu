<?php

class DocsController extends Controller
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
                    'tddo',
                    'tddoCreate',
                    'findExecutor',
                    'getTddoNextNumber',
                    'deleteTddo',
                    'tddoEdit',
                    'attachFileTddo',
                    'tddoPrint',
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
        $grants = Yii::app()->user->dbModel->grants;
        if (empty($grants))
            throw new CHttpException(404, 'Invalid request. You don\'t have access to the service.');

        if ($grants->grants5 != 1)
            throw new CHttpException(404, 'Invalid request. You don\'t have access to the service.');

        $filterChain->run();
    }


    public function actionTddo()
    {
        $docType = Yii::app()->request->getParam('docType', null);

        $model = new Tddo();
        $model->unsetAttributes();

        $model->tddo2 = $docType;

        if (isset($_REQUEST['Tddo'])) {
            $model->scenario = 'filter';
            $model->attributes = $_REQUEST['Tddo'];
        }

        $this->render('tddo/list', array(
            'docType' => $docType,
            'model'   => $model
        ));
    }

    public function actionTddoCreate()
    {
        $docType = Yii::app()->request->getParam('docType', null);
        if (empty($docType))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $model = new Tddo;
        $model->unsetAttributes();

        $model->tddo2 = $docType;
        $model->tddo4 = date('Y-m-d H:i:s');
        // next input registration number
        $model->tddo3 = $model->getNextNumberFor($docType);
        $model->tddo7 = $model->getNextNumberFor($docType);
        // default executor type
        //$model->executorType = $docType == 2 ? Tddo::ONLY_INDEXES : Tddo::ONLY_TEACHERS;

        if (isset($_REQUEST['Tddo'])) {
            $model->scenario   = 'create';
            $model->attributes = $_REQUEST['Tddo'];
            $model->tddo1  = new CDbExpression('GEN_ID(GEN_TDDO, 1)');
            $model->tddo11 = isset($_REQUEST['Dkid']) ? 1 : 2;

            if ($model->save()) {

                $this->saveExecutorsAndDatesFor($model);

                Yii::app()->request->redirect(Yii::app()->createUrl('/docs/tddo', array('docType' => $docType)));
            }
            //(var_dump($_REQUEST));
        }


        $this->render('tddo/create', array(
            'model' => $model,
        ));
    }

    public function actionTddoEdit()
    {
        $tddo1 = Yii::app()->request->getParam('tddo1', null);
        if (empty($tddo1))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $model = Tddo::model()->findByPk($tddo1);
        if (empty($model))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $model->scenario = 'edit';

        if (isset($_REQUEST['Tddo'])) {
            $model->attributes = $_REQUEST['Tddo'];
            $model->tddo11 = isset($_REQUEST['Dkid']) ? 1 : 2;

            if ($model->save()) {

                $this->saveExecutorsAndDatesFor($model);

                Yii::app()->request->redirect(Yii::app()->createUrl('/docs/tddo', array('docType' => $model->tddo2)));
            }
            //(var_dump($_REQUEST));
        }

        $this->render('tddo/edit', array(
            'model' => $model,
        ));
    }

    public function actionFindExecutor()
    {
        $query = Yii::app()->request->getParam('query', null);
        $type  = Yii::app()->request->getParam('type', null);

        if (empty($type))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        if ($type == Tddo::ONLY_TEACHERS)
            $items = P::model()->findTeacherByName($query);
        elseif($type == Tddo::ONLY_INDEXES)
            $items = Innf::model()->getIndexesByArray();
        elseif($type == Tddo::ONLY_CHAIRS)
            $items = K::model()->findChairsByName($query);

        $res = array();
        foreach ($items as $item) {
            $res[] = array('id' => $item['id'], 'name' => $item['name']);
        }

        Yii::app()->end(CJSON::encode($res));
    }

    public function actionGetTddoNextNumber()
    {
        $docType = Yii::app()->request->getParam('docType', null);
        $tddo4   = Yii::app()->request->getParam('tddo4', null);

        if (empty($docType) || empty($tddo4))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $nextNumber = Tddo::getNextNumberFor($docType, date('d.m.Y H:i', $tddo4/1000));

        $res = array('res' => $nextNumber);
        Yii::app()->end(CJSON::encode($res));
    }

    public function actionDeleteTddo()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $tddo1 = Yii::app()->request->getParam('tddo1', null);

        $deleted = (bool)Tddo::model()->deleteByPk($tddo1);

        $res = array(
            'deleted' => $deleted
        );

        Yii::app()->end(CJSON::encode($res));
    }

    private function saveExecutorsAndDatesFor($model)
    {
        $isEdit = $model->scenario == 'edit';

        $tddo1 = $isEdit
                    ? $model->tddo1
                    : Tddo::getLastInsertId();

        if ($isEdit) {
            Ido::model()->deleteAll('ido1='.$tddo1);
            Idok::model()->deleteAll('idok1='.$tddo1);
            Dkid::model()->deleteAll('dkid1='.$tddo1);
        }

        if (isset($_REQUEST['Dkid'])) {
            $dates = array_map("unserialize", array_unique(array_map("serialize", $_REQUEST['Dkid'])));
            foreach ($dates as $array) {

                if (empty($array['dkid2']))
                    continue;

                $date = new Dkid;
                $date->dkid1 = $tddo1;
                $date->dkid2 = $array['dkid2'];
                $date->dkid3 = $array['dkid3'];
                $date->save();
            }
        }

        if ($model->executorType == Tddo::ONLY_TEACHERS) {
            if (isset($_REQUEST['teachers'])) {
                $teachers = array_unique(array_filter($_REQUEST['teachers']));
                foreach ($teachers as $teacher) {
                    $executor = new Ido;
                    $executor->ido1 = $tddo1;
                    $executor->ido2 = $teacher;
                    $executor->ido5 = isset($_REQUEST['ido5'][$teacher]) ? 1 : 2;
                    $executor->save();
                    var_dump($executor->getErrors());
                }
            }
        }
        if ($model->executorType == Tddo::ONLY_INDEXES) {
            if (isset($_REQUEST['indexs'])) {
                $indexes = array_unique(array_filter($_REQUEST['indexs']));
                foreach ($indexes as $index) {
                    $executor = new Ido;
                    $executor->ido1 = $tddo1;
                    $executor->ido4 = $index;
                    $executor->ido5 = 2;
                    $executor->save();
                }
            }
        }

        if ($model->executorType == Tddo::ONLY_CHAIRS) {
            if (isset($_REQUEST['chairs'])) {
                $chairs = array_unique(array_filter($_REQUEST['chairs']));
                foreach ($chairs as $chair) {
                    $executor = new Idok;
                    $executor->idok1 = $tddo1;
                    $executor->idok2 = $chair;
                    $executor->idok4 = isset($_REQUEST['idok4'][$chair]) ? 1 : 2;
                    $executor->save();
                }
            }
        }
    }

    public function actionAttachFileTddo()
    {
        $tddo1 = Yii::app()->request->getParam('tddo1', null);
        if (empty($tddo1))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $model = Tddo::model()->findByPk($tddo1);
        if (empty($model))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        if (! empty($_FILES))
        {
            $files = CUploadedFile::getInstancesByName('files');

            foreach ($files as $file) {

                $name = time();
                $type = $file->getExtensionName();
                $newName = $name.'.'.$type;

                $fpdd = new Fpdd();
                $fpdd->fpdd1 = $tddo1;
                $fpdd->fpdd3 = Yii::app()->user->dbModel->p1;
                $fpdd->fpdd4 = $newName;
                $fpdd->fpdd5 = $file->getName();

                if ($fpdd->save()) {
                    $path = Yii::getPathOfAlias('webroot').'/uploads/docs/'.$newName;
                    $file->saveAs($path);
                }
            }
        }

        $attachedFiles = Fpdd::model()->findAll('fpdd1='.$tddo1);

        $this->render('tddo/attachFile', array(
            'model' => $model,
            'attachedFiles' => $attachedFiles
        ));
    }

    public function actionTddoPrint()
    {
        $docType = Yii::app()->request->getParam('docType', null);

        $model = new Tddo();
        $model->unsetAttributes();

        $model->tddo2 = $docType;

        if (isset($_REQUEST['Tddo'])) {
            $model->scenario = 'filter';
            $model->attributes = $_REQUEST['Tddo'];
        }

        $this->render('tddo/print', array(
            'docType' => $docType,
            'model'   => $model
        ));
    }

}