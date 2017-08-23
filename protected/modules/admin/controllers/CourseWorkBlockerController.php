<?php

class CourseWorkBlockerController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('index','save'),
                'expression' => 'Yii::app()->user->isAdmin',
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
	    $year = date('Y');
	    $prevYear = $year-1;

		$sql = <<<SQL
		  SELECT sg1, cwb1, sp2, sg3, sg4 from sg
		    LEFT JOIN cwb on (sg1 = cwb1)
		    INNER JOIN sp ON (sg2=sp1)
		    INNER JOIN f ON (sp5 = f1)
		    INNER JOIN sem ON (sg1=sem2)
		WHERE sp7 is null and sg1>0 and sg8 is null and (sem3 = $year or sem3 = $prevYear ) and f14=0
        GROUP BY sg1, cwb1, sp2, sg3, sg4 
        ORDER BY sg3 DESC, sp2 COLLATE UNICODE
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $list = $command->queryAll();

		$this->render('index',array(
			'models'=>$list,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
     * @return Cwb|null
	 */
	public function loadModel($id)
	{
		$model=Cwb::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

    public function actionSave()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $error=false;
        $errorType=0;

        $sg1 = Yii::app()->request->getParam('sg1', null);
        $type = Yii::app()->request->getParam('type', null);

        if($type==0){
            $error = ! $this->loadModel($sg1)->delete();
        }else{
            $model = new Cwb();
            $model->cwb1 = $sg1;

            $error = ! $model->save();
        }

        Yii::app()->end(CJSON::encode(array('error' => $error, 'errorType' => $errorType)));
    }
}
