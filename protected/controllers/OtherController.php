<?php

class OtherController extends Controller
{
    public function filters() {

        return array(
            'accessControl',
            'requestPayment + deleteRequestPayment, createRequestPayment'
        );
    }

    public function filterRequestPayment($filterChain)
    {
        if (!Yii::app()->user->isStd)
            throw new CHttpException(403, 'Invalid request. You don\'t have access to the service.');

        if (PortalSettings::model()->getSettingFor(PortalSettings::ENABLE_REGISTRATION_PASS)!=1)
            throw new CHttpException(403, 'Invalid request. You don\'t have access to the service.');

        if (PortalSettings::model()->getSettingFor(PortalSettings::SHOW_REGISTRATION_PASS_TAB)!=1)
            throw new CHttpException(403, 'Invalid request. You don\'t have access to the service.');

        $filterChain->run();
    }

    public function accessRules() {

        return array(
            array('allow',
                'actions' => array(
                    'renderAddSpkr',
                    'addSpkr'
                ),
                'expression' => 'Yii::app()->user->isTch',
            ),
            array('allow',
                'actions' => array(
                    'subscription',
                    'saveCiklVBloke',
                    'saveDisciplines',
                    'cancelSubscription',
                    'renderAddSpkr',
                    'addSpkr',
                    'studentInfoPdf',
                    'deleteRequestPayment',
                    'createRequestPayment',
                    'antiplagiat'
                ),
                'expression' => 'Yii::app()->user->isStd',
            ),
            array('allow',
                'actions' => array(
                    'searchStudent',
                    'phones',
                    'studentInfo', //проверка идет в самом методе

                    'studentPassport', //Проверка идет в самом методе
                    'deletePassport', //проверка идет в самом методе
                    'showPassport', //проверка идет в самом методе
                    'uploadPassport', //проверка идет в самом методе
                    'changePassport', //проверка идет в самом методе
                    'autocompleteTeachers',
                    'studentCard', //проверка идет в самом методе
                    'showRetake', //проверка идет в самом методе
                ),
            ),
            array('allow',
                'actions' => array(
                    'updateNkrs',
                ),
                'users' => array('@'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionDeleteRequestPayment($id)
    {
        if(!Yii::app()->request->isPostRequest)
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');

        if (!Yii::app()->user->isStd)
            throw new CHttpException(403, 'Invalid request. You don\'t have access to the service.');

        $model=Zsno::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');

        if($model->zsno1!=Yii::app()->user->dbModel->st1)
            throw new CHttpException(403, 'Invalid request. You don\'t have access to the service.');

        if($model->delete()){
            Yii::app()->user->setFlash('success', tt('Заявление на оплату №{number} от {date} успешно удалено', array(
                '{number}' => $model->zsno0,
                '{date}'=> date("d.m.Y",strtotime($model->zsno2))
            )));
        }else{
            Yii::app()->user->setFlash('success', tt('Ошибка удаления заявления на оплату №{number} от {date}', array(
                '{number}' => $model->zsno0,
                '{date}'=> date("d.m.Y",strtotime($model->zsno2))
            )));
        }

        $this->redirect('/other/studentCard');
    }

    public function actionCreateRequestPayment()
    {
        if(!Yii::app()->request->isPostRequest)
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');

        if (!Yii::app()->user->isStd)
            throw new CHttpException(403, 'Invalid request. You don\'t have access to the service.');

        if(!isset($_POST['lessons']))
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');

        $form = new CreateRequestPaymentForm();
        $form->lessons = $_POST['lessons'];

        if(!$form->validateLessons())
            throw new CHttpException(400,'Ошибка в выбранных занятиях, возможно, некоторые занятия отработаны или уже участвуют в справках.');


        $model = new Zsno();
        $model->zsno1= Yii::app()->user->dbModel->st1;
        $model->zsno0 =  Yii::app()->db->createCommand('select gen_id(GEN_ZSNO, 1) from rdb$database')->queryScalar();
        $model->zsno2 = date('Y-m-d H:i:s');

        $trans = Yii::app()->db->beginTransaction();

        try{

            if(!$model->save()){
                throw new Exception(tt('Ошибка создания заявления на оплату'));
            }

            foreach ( $form->lessons as $lesson) {
                $zsnop = new Zsnop();
                $zsnop->zsnop0 = $model->zsno0;
                $zsnop->zsnop1 = $lesson;

                if(!$zsnop->save())
                    throw new Exception('Ошибка добавления занятия в заявление на оплату');
            }

            $trans->commit();

            Yii::app()->user->setFlash('success', tt('Заявление на оплату №{number} от {date} успешно создано', array(
                '{number}' => $model->zsno0,
                '{date}'=> date("d.m.Y",strtotime($model->zsno2))
            )));

        }catch (Exception $error){
            $trans->rollback();

            throw new CHttpException(400,tt('Ошибка создания заявления на оплату №{number} от {date}: {error}', array(
                '{number}' => $model->zsno0,
                '{date}'=> date("d.m.Y",strtotime($model->zsno2)),
                '{error}' => $error->getMessage()
            )));
        }
    }

    public function actionShowRetake()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $uo1 = Yii::app()->request->getParam('uo1', null);
        $sem1 = Yii::app()->request->getParam('sem1', null);
        $type = Yii::app()->request->getParam('type', null);
        $st1 = Yii::app()->request->getParam('st1', null);
        $gr1 = Yii::app()->request->getParam('gr1', null);

        if (empty($uo1) || empty($sem1) || empty($st1) || $type === null)
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');

        if (Yii::app()->user->isAdmin) {


        } elseif (Yii::app()->user->isPrnt) {
            if($st1!=Yii::app()->user->dbModel->st1)
                throw new CHttpException(403, 'Invalid request. Please do not repeat this request again.');
        } elseif (Yii::app()->user->isStd) {
            if($st1!=Yii::app()->user->dbModel->st1)
                throw new CHttpException(403, 'Invalid request. Please do not repeat this request again.');
        } else
            throw new CHttpException(403, 'You don\'t have an access to this service');

        $uo = Uo::model()->findByPk($uo1);
        $sem = Sem::model()->findByPk($sem1);

        $gr1 = St::model()->getGroupByStudent($st1, $uo->uo19, $sem->sem3, $sem->sem5);

        $info = Elg::model()->getDispByStSemUoType($st1,$uo1,$sem1,$type);
        $error = false;

        $title = "";
        if(!empty($info)) {
            if(!empty($info['d27'])&&Yii::app()->language=="en")
                $d2=$info['d27'];
            else
                $d2=$info['d2'];
            $title = sprintf("%s | %s (%s)",$d2,$info['k2'],SH::convertUS4($info['us4']));
        }

        $html = $this->renderPartial('studentCard/_show_retake', array(
            'uo1'=>$uo1,
            'sem1'=>$sem1,
            'type'=>$type,
            'st1'=>$st1,
            'gr1'=>$gr1
        ), true);

        $res = array(
            'title'=>$title,
            'html'=>$html,
            'error' => $error,
        );

        Yii::app()->end(CJSON::encode($res));
    }

    public function actionStudentCard()
    {
        $model = new TimeTableForm;
        $model->scenario = 'student';
        if (isset($_REQUEST['TimeTableForm']))
            $model->attributes=$_REQUEST['TimeTableForm'];

        $student = new St;
        $student->unsetAttributes();
        if (Yii::app()->user->isAdmin) {


        }
        elseif (Yii::app()->user->isPrnt) {
            $model->student = Yii::app()->user->dbModel->st1;
        } elseif (Yii::app()->user->isStd) {

            $model->student = Yii::app()->user->dbModel->st1;
        } else
            throw new CHttpException(404, 'You don\'t have an access to this service');

        $this->render('studentCard', array(
            'student'=>$student,
            'model'=>$model
        ));
    }

    public function actionPhones()
    {
        $department = Yii::app()->request->getParam('department', null);

        $phones = Tso::model()->getAllPhonesInArray($department);

        $this->render('phones', array(
            'phones' => $phones,
            'department' => $department
        ));
    }

    public function actionSubscription()
    {
        $model = Yii::app()->user->dbModel;

        $this->render('subscription', array(
            'model' => $model,
        ));
    }

    public function actionSaveCiklVBloke()
    {
        $u1_cikl = Yii::app()->request->getParam('u1_cikl', null);

        $res = false;
        if (! empty($u1_cikl)) {

            $_SESSION['u1_vib'] .= ','.$u1_cikl;
            $_SESSION['u1_vib_disc'] = $u1_cikl;

            $_SESSION['min_block']++;
            $_SESSION['func'] = 'PROCEDURA_VIBOR_DISCIPLIN';

            $res = true;
        }

        Yii::app()->end(CJSON::encode(array('res' => $res)));
    }

    public function actionSaveDisciplines()
    {
        $disciplines = Yii::app()->request->getParam('disciplines', null);

        $res = false;
        if (! empty($disciplines)) {
            try {
                foreach ($disciplines as $ucg1_kod) {
                    U::model()->doUpdates($ucg1_kod);
                }

                $_SESSION['min_block']++;
                $_SESSION['func'] = 'PROCEDURA_CIKL_PO_BLOKAM';

                $res = true;
                $msg = '';
            }catch (Exception $error){
                $res = false;
                $msg = $error->getMessage();
            }
        }

        Yii::app()->end(CJSON::encode(array('res' => $res, 'msg' => $msg)));
    }

    public function actionCancelSubscription()
    {
        if(St::model()->enableSubcription(Yii::app()->user->dbModel->st1)) {
            $model = St::model()->findByPk(Yii::app()->user->dbModel->st1);
            if(!empty($model)) {
                $params = $model->subscriptionParams;
                if(!empty($params)) {
                    U::model()->cancelSubscription();
                    unset($_SESSION['u1_vib'], $_SESSION['u1_vib_disc'], $_SESSION['func'], $_SESSION['min_block']);
                }
            }
        }

        Yii::app()->end(CJSON::encode(array('res' => true)));
    }
    
    private function checkAccessPasport($psp1)
    {
        if (Yii::app()->user->isTch) {

            $grants = Yii::app()->user->dbModel->grants;

            if (empty($grants))
                throw new CHttpException(403, 'You don\'t have an access to this service');

            $type = $grants->getGrantsFor(Grants::STUDENT_INFO);

            if ($type == 0)
                throw new CHttpException(403, 'You don\'t have an access to this service');

        } elseif (Yii::app()->user->isStd) {
             if(Yii::app()->user->dbModel->st1!=$psp1)   
                 throw new CHttpException(403, 'You don\'t have an access to this service');
        } else
            throw new CHttpException(403, 'You don\'t have an access to this service');
    }
    
    public function actionStudentPassport($type,$psp1)
    {
        $this->checkAccessPasport($psp1);
        $stInfoForm= new StInfoForm;
        $stInfoForm->getPassport($psp1,$type);
    }
    
    public function actionDeletePassport()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');
        
        $error=false;
        
        $psp1 = Yii::app()->request->getParam('psp1', null);
        $type = Yii::app()->request->getParam('type', null);
        
        if($psp1!=null && $type!=null)
        {
            $this->checkAccessPasport($psp1);
            $stInfoForm= new StInfoForm;
            $stInfoForm->deletePassport($psp1,$type); 
        }else
           $error=true;  

        $res = array(
            'error'       => $error,
        );
        
        Yii::app()->end(CJSON::encode($res));
    }
    
    public function actionShowPassport()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');
        
        $error=false;
        $html='';
        
        $psp1 = Yii::app()->request->getParam('psp1', null);
        $type = Yii::app()->request->getParam('type', null);
        
        if($psp1!=null && $type!=null)
        {
            $this->checkAccessPasport($psp1);
            $stInfoForm= new StInfoForm;
            $html = $this->renderPartial('studentInfo/_show',array(
                'psp1'=>$psp1,
                'type'=>$type
            ), true);
        }else
           $error=true;  

        $res = array(
            'error'       => $error,
            'html'=>$html,
            'title'=>tt('Просмотр')
        );
        
        Yii::app()->end(CJSON::encode($res));
    }
    
     public function actionChangePassport()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');
        
        $error=false;
        $html='';
        
        $psp1 = Yii::app()->request->getParam('psp1', null);
        $type = Yii::app()->request->getParam('type', null);
        
        if($psp1!=null && $type!=null)
        {
            $this->checkAccessPasport($psp1);
            $stInfoForm= new StInfoForm;
            $html = $this->renderPartial('studentInfo/_change',array(
                'psp1'=>$psp1,
                'type'=>$type
            ), true);
        }else
           $error=true;  

        $res = array(
            'error'       => $error,
            'html'=>$html,
            'title'=>tt('Изменить')
        );
        
        Yii::app()->end(CJSON::encode($res));
    }

    public function actionRenderAddSpkr()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        if(PortalSettings::model()->findByPk(72)->ps2==1)
            throw new CHttpException(403, 'Редактирование тем закрыто.');
        $error=false;
        $html='';

        $model=new Spkr;
        $model->unsetAttributes();
        $html = $this->renderPartial('studentInfo/_render_spkr',array('model'=>$model), true);

        $res = array(
            'error'=> $error,
            'html'=>$html,
            'title'=>tt('Добавить тему курсовой')
        );

        Yii::app()->end(CJSON::encode($res));
    }

    public function actionAddSpkr()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        if(PortalSettings::model()->findByPk(72)->ps2==1)
            throw new CHttpException(403, 'Редактирование тем закрыто.');

        $error=false;
        $html='';

        $model=new Spkr;
        $model->unsetAttributes();

        $spkr2= Yii::app()->request->getParam('spkr2', null);
        $spkr3 = Yii::app()->request->getParam('spkr3', null);

        if(empty($spkr2)||empty($spkr3))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $sql=<<<SQL
                SELECT MAX(spkr1) FROM spkr
SQL;
        $command = Yii::app()->db->createCommand($sql);
        //$command->bindValue(':us1', $ustem2);
        $spkr1 = (int)$command->queryScalar();

        $model->spkr1= $spkr1+1;
        $model->spkr2=$spkr2;
        $model->spkr3=$spkr3;
        $model->spkr4=1;
        $model->spkr5=1;
        $model->spkr6=0;
        $model->spkr8 =  date('Y-m-d H:i:s');
        $model->spkr9=0;

        $res = array(
            'error'=> !$model->save(),
        );

        Yii::app()->end(CJSON::encode($res));
    }
    
     public function actionUploadPassport()
    {
        if (! Yii::app()->request->isPostRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');
        
        $html='';
        
        $psp1 = Yii::app()->request->getParam('psp1', null);
        $type = Yii::app()->request->getParam('type', null);
        
        $error = "";
        $msg = "";
        $result = "";
        $fileElementName = 'document_psp';
        
        if($psp1!=null && $type!=null)
        {
            $this->checkAccessPasport($psp1);
            $stInfoForm= new StInfoForm;
            $files_count = (isset($_FILES[$fileElementName]["name"])) ? count($_FILES[$fileElementName]["name"]) : 0;
            for ($i = 0; $i < $files_count; $i++) {
		if($i > 0) break;

		$_FILES[$fileElementName]['name'][$i] = mb_strtolower($_FILES[$fileElementName]['name'][$i]);
		$_FILES[$fileElementName]['name'][$i] = str_replace(" ","_",$_FILES[$fileElementName]['name'][$i]);		
			
			$res=1;
       if(!empty($_FILES[$fileElementName]['error'][$i]))
			{
				switch($_FILES[$fileElementName]['error'][$i])
				{
					case '1':
						$error = 'размер загруженного файла превышает размер установленный параметром upload_max_filesize  в php.ini ';
						break;
					case '2':
						$error = 'размер загруженного файла превышает размер установленный параметром MAX_FILE_SIZE в HTML форме. ';
						break;
					case '3':
						$error = 'загружена только часть файла ';
						break;
					case '4':
						$error = 'файл не был загружен (Пользователь в форме указал неверный путь к файлу). ';					
						break;
					case '6':
						$error = 'неверная временная дирректория';
						break;
					case '7':
						$error = 'ошибка записи файла на диск';
						break;
					case '8':
						$error = 'загрузка файла прервана';
						break;
					case '999':
					default:
						$error = 'No error code avaiable';
				}
			}elseif(empty($_FILES[$fileElementName]['tmp_name'][$i]) || $_FILES[$fileElementName]['tmp_name'][$i] == 'none')
			{
				$error = 'No file was uploaded..';
                                echo $error;
			}else 
			{				
                            $res=$stInfoForm->setPassport($psp1,$type);
                                
                        }   
                        if(empty($error)&&$res>-1)
                        {
                            Yii::app()->user->setFlash("upload_passport_success", tt('Загрузка успешна!'));
                        }else
                        {
                            if(!empty($error))
                            {
                                Yii::app()->user->setFlash("upload_passport_error", $error);
                            }else
                            {
                                Yii::app()->user->setFlash("upload_passport_error", tt('Ошибка сохранения'));
                            }
                        }
			unset($error);
		}
		echo $result;
            $this->redirect(array('/other/studentinfo'));
            
        }else
        {
            throw new CHttpException(404, '');
        }
        
    }
    
    public function actionSearchStudent()
    {
        $model = new St;
        $model->unsetAttributes();
        if (isset($_REQUEST['St']))
            $model->attributes = $_REQUEST['St'];
		
        $this->render('/timeTable/search_student', array(
            'model' => $model,
            'url'=>array('other/studentInfo')
        ));
    }
    
    public function actionStudentInfo()
    {
        $model = new TimeTableForm;
        $model->scenario = 'student';
        if (isset($_REQUEST['TimeTableForm']))
            $model->attributes=$_REQUEST['TimeTableForm'];

        $canSelectSt = false;
        if (Yii::app()->user->isTch) {

            $grants = Yii::app()->user->dbModel->grants;

            if (empty($grants))
                throw new CHttpException(404, 'You don\'t have an access to this service');

            $type = $grants->getGrantsFor(Grants::STUDENT_INFO);

            if ($type == 0)
                throw new CHttpException(404, 'You don\'t have an access to this service');

            $canSelectSt = true;

        } elseif (Yii::app()->user->isStd) {

            $model->student = Yii::app()->user->dbModel->st1;

        } else
            throw new CHttpException(404, 'You don\'t have an access to this service');

        /*if (! empty($_FILES)) {
            $nkrs1 = Yii::app()->request->getParam('nkrs1', null);
            $nkrs6 = Yii::app()->request->getParam('nkrs6', null);

            $this->proccessAntiplagiat($model->student, $nkrs1, $nkrs6);
        }*/

        $stInfoForm = new StInfoForm();
        $stInfoForm->fillData($model);
        if (isset($_REQUEST['StInfoForm'])) {
            $stInfoForm->attributes=$_REQUEST['StInfoForm'];
            if ($stInfoForm->validate())
                $stInfoForm->customSave($model);
        }
        $student = new St;
        $student->unsetAttributes();
        
        $this->render('studentInfo', array(
            'canSelectSt' => $canSelectSt,
            'stInfoForm'  => $stInfoForm,
            'model' => $model,
            'student'=>$student
        ));
    }

    public  function actionAntiplagiat(){

        $student = St::model()->findByPk(Yii::app()->user->dbModel->st1);

        if(empty($student))
            throw new Exception("Error load student!", 400);

        $nkrsList = $student->getNkrsList();

        list($year, $sem) = SH::getCurrentYearAndSem();

        if(!$student->checkAntiplagiatAccess($year)){
            Yii::app()->user->setFlash('error', tt('Доступ на проверку в Антиплагиат запрещен, превышен лимит проверок'));
            $this->redirect('/other/studentInfo');
        }

        if (! empty($_FILES) && !empty($nkrsList)) {

            $last = $nkrsList[count($nkrsList)-1];
            $nkrs1 = $last['nkrs1'];
            $nkrs6 = $last['nkrs6'];

            $this->proccessAntiplagiat($student->st1, $nkrs1, $nkrs6);
        }

        $this->render('antiplagiat', array(
            'student'=>$student,
            'nkrsList'=>$nkrsList,
            'year'=>$year
        ));
    }

    /**
     * Обработка докумнента для отправки и отправка в антиплагиат
     * @param $student int студент
     * @param $nkrs1 int
     * @param $nkrs6 mixed
     */
    private function proccessAntiplagiat($student, $nkrs1, $nkrs6){
        $st = St::model()->findByPk($student);
        if($st == null) {
            Yii::app()->user->setFlash('error', tt('Ошибка: не найден студент'));
            return false;
        }
        list($year, $sem) = SH::getCurrentYearAndSem();

        if (! empty($_FILES)) {

            $document = CUploadedFile::getInstanceByName('document');
            $tmpName = tempnam(sys_get_temp_dir(), '_');
            $saved = $document->saveAs($tmpName);


            if ($saved) {
                list($id, $url) = $this->sendToAntiPlagiarism($document, $tmpName);
                if ($nkrs1)
                    D::model()->updateNkrs($nkrs1, 'nkrs8', $id);
                Yii::app()->user->setFlash('success', tt('Документ был отправлен на проверку в Антиплагиат'));
                Yii::app()->user->setFlash('info', tt('Результат можно посмотреть здесь:') .' '. CHtml::link(tt('Отчет'), $url, array('target' => '_blank')));

                $_modelLimit = $st->getAntio($year);
                $_modelLimit->antio3++;
                $_modelLimit->save();

                $this->sendEmails($student, $nkrs6, $url);
            }

            unset($_FILES);
        }
    }

    /**
     * Печать заявления на утверждения курсовой в пдф
     * @throws CHttpException
     */
    public function actionStudentInfoPdf()
    {
        $model = new TimeTableForm;
        if (Yii::app()->user->isStd) {

            $model->student = Yii::app()->user->dbModel->st1;

        } else
            throw new CHttpException(404, '1You don\'t have an access to this service');

        $stInfoForm = new StInfoForm();
        $stInfoForm->fillData($model);

        if(empty($model->student))
            throw new CHttpException(404, '2You don\'t have an access to this service');
        else
        {
            $student=St::model()->findByPk($model->student);
            if(empty($student))
                throw new CHttpException(404, '3You don\'t have an access to this service');
            else
            {
                $discipline = D::model()->getDisciplineForCourseWork($model->student);
                if ($discipline) {
                    $courseWork = D::model()->getFirstCourseWork($model->student, $discipline['us1']);

                    $p1    = $courseWork['nkrs6'];
                    $nkrs6 = P::model()->getTeacherNameWithDol($courseWork['nkrs6']);
                    $nkrs7 = $courseWork['nkrs7'];
                    $nkrs4 = $courseWork['nkrs4'];
                    $nkrs5 = $courseWork['nkrs5'];
                    list($zav, $kav) = P::model()->getZavKavByTeacher($p1);
                    $k2=$kav['k3'];
                    $st_info=St::model()->getInfoForStudentInfoExcel($model->student);
                    $zav_name=Sh::getShortName($zav['p3'],$zav['p4'],$zav['p5']);
                    $spkr=Spkr::model()->findByPk($nkrs7);
                    if(!empty($spkr))
                    {
                        $nkrs4=$spkr->spkr2;
                        $nkrs5=$spkr->spkr3;
                    }

                    $mPDF1 = new Mpdf\Mpdf();

                    $patternTitle = <<<HTML
                        <style>
                        p {
                            text-indent: 20px; /* Отступ первой строки в пикселах */
                        }
                        td,th {
                        border: 1px solid black; /* Параметры рамки */
                       }
                        </style>
                        <div style="margin-left: 50%%">
                            <span>
                                На кафедру <br>
                                %s <br>
                                (заведующий - профессор %s) <br>
                                студента %s курса %s группы <br>
                                %s <br>
                                %s
                            </span>
                        </div>
                        <h2 style="text-align: center">Заявление</h2>

                        <p>Прошу утвердить тему моей курсовой/дипломной работы</p>
                        <table>
                            <tbody>
                                <tr>
                                    <td>Название на русском языке</td>
                                    <td><strong>%s</strong></td>
                                </tr>
                               <tr>
                                    <td>Название на английском языке</td>
                                    <td><strong>%s</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        <p>Научный руководитель %s</p>
                        <p>
                            <div style="float: left; width: 40%%">« ___ » __________ 20__г.</div><div style="float: right;text-align: right; width: 40%%">_____________</div>
                        </p>

                        <p>
                            <div style="float: left; width: 30%%">«не возражаю»</div><div style="float: left; text-align: center;width: 30%%">_____________</div><div style="float: right;text-align: right; width: 30%%">Научный руководитель</div>
                        </p>

                        <p>
                            <div style="float: left; width: 30%%">«не возражаю»</div><div style="float: left; text-align: center;width: 30%%">_____________</div><div style="float: right;text-align: right; width: 30%%">Заведующий кафедрой</div>
                            <br>
                            <div style="float: left; width: 30%%">(только для дипломных работ)</div>
                        </p>
HTML;

                    $mPDF1->WriteHTML(sprintf(
                        $patternTitle,
                        $k2,
                        $zav_name,
                        $st_info['sem4'],
                        $st_info['name'],
                        $st_info['f3'],
                        SH::getShortName($student->st2,$student->st3,$student->st4),
                        $nkrs4,
                        $nkrs5,
                        $nkrs6
                    ));

                    $data=Foto::getStudentFoto($model->student);
                    if($data!=null)
                    {
                        //штрихкод
                        $url = $this->createUrl('/site/studentBarcode', array('_id' => $model->student));
                        $htmlBarcode = '<p style="text-align:right;margin-top: 100px;"><img src="'.$url.'" alt=""/><p>';
                        $mPDF1->WriteHTML($htmlBarcode);
                    }


                    $mPDF1->Output();
                }
                else
                    throw new CHttpException(404, '4You don\'t have an access to this service');
            }
        }

    }

    public function sendToAntiPlagiarism($document, $tmpName)
    {
        $LOGIN = PortalSettings::model()->findByPk(68)->ps2;
        $PASSWORD = PortalSettings::model()->findByPk(69)->ps2;
        $COMPANY_NAME = PortalSettings::model()->findByPk(70)->ps2;
        $APICORP_ADDRESS = PortalSettings::model()->findByPk(71)->ps2;

        $client = new SoapClient("https://$APICORP_ADDRESS/apiCorp/$COMPANY_NAME?singleWsdl",
            array("trace"=>1,
                "login"=>$LOGIN,
                "password"=>$PASSWORD,
                "soap_version" => SOAP_1_1,
                "features" => SOAP_SINGLE_ELEMENT_ARRAYS,
                //"timeout" => 300,
                # PHP 5.6
                "stream_context"=>stream_context_create(
                    array(
                        "ssl"=>array(
                            "verify_peer"=>false,
                            "allow_self_signed"=>true,
                            "verify_peer_name"=>false
                        )
                    )
                )));


// Используется для получения ссылок на отчеты
        $ANTIPLAGIAT_URI = "http://$COMPANY_NAME.antiplagiat.ru";///конец

        // Описание загружаемого файла
        $data = array(
            "Data"     => file_get_contents($tmpName),
            "FileName" => $document->name,
            "FileType" => '.'.$document->extensionName
        );

        // Загрузка файла
        $uploadResult = $client->UploadDocument(array("data"=>$data));

        // Идентификатор документа. Если загружается не архив, то список загруженных документов будет состоять из одного элемента.
        $id = $uploadResult->UploadDocumentResult->Uploaded[0]->Id;

        // Иницировать проверку с использованием собственного модуля поиска и модуля поиска "wikipedia"
        $client->CheckDocument(array("docId" => $id));

        // Получить текущий статус последней проверки
        $status = $client->GetCheckStatus(array("docId" => $id));

        // Цикл ожидания окончания проверки
        while ($status->GetCheckStatusResult->Status === "InProgress")
        {
            sleep($status->GetCheckStatusResult->EstimatedWaitTime * 0.2);
            $status = $client->GetCheckStatus(array("docId" => $id));
        }

        // Проверка закончилась неудачно.
        if ($status->GetCheckStatusResult->Status === "Failed")
        {
            echo("При проверке документа произошла ошибка:" . $status->GetCheckStatusResult->FailDetails);
            return;
        }

        $url = $ANTIPLAGIAT_URI.$status->GetCheckStatusResult->Summary->ReportWebId;
        return array($id->Id, $url);
    }

    public function actionAutocompleteTeachers()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $query = Yii::app()->request->getParam('query', null);

        $teachers = P::model()->findTeacherByName($query);

        foreach($teachers as $tch)
        {
            $t = '';
            if ($tch['pd7'] == 1)
                $t = 'совм.';
            elseif ($tch['pd7'] == 3 || $tch['pd7'] == 5)
                $t = 'почас.';
            elseif ($tch['pd7'] == 4)
                $t = 'совмещ.';

            $suggestions[] = array(
                'value' => implode(' ', array($tch['dol2'], SH::getShortName($tch['p3'], $tch['p4'], $tch['p5']))) .' '. $t,
                'p1'    => $tch['p1']
            );
        }

        $res = array(
            'query'       => $query,
            'suggestions' => $suggestions,
        );

        Yii::app()->end(CJSON::encode($res));
    }

    public function actionUpdateNkrs()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        if(PortalSettings::model()->findByPk(72)->ps2==1)
            throw new CHttpException(403, 'Редактирование тем закрыто.');

        $field = Yii::app()->request->getParam('field', null);
        $value = Yii::app()->request->getParam('value', null);
        $nkrs1 = Yii::app()->request->getParam('nkrs1', null);
        $st1 = Yii::app()->request->getParam('st1', null);
        $us1 = Yii::app()->request->getParam('us1', null);

        $sg1=St::model()->getSg1BySt1($st1);

        if(Cwb::model()->findByPk($sg1)!==null)
            throw new CHttpException(403, 'Редактирование тем закрыто.');

        if (! in_array($field, array('nkrs6', 'nkrs7')))
            throw new CHttpException(404, '1Invalid request. Please do not repeat this request again.');

        $nkrs=Nkrs::model()->findByPk($nkrs1);
        if(!empty($nkrs))
            $res = D::model()->updateNkrs($nkrs1, $field, $value);
        else
        {
            $nkrs= new Nkrs();
            $nkrs->nkrs1=new CDbExpression('GEN_ID(GEN_NKRS, 1)');
            $nkrs->nkrs2=$st1;
            $nkrs->nkrs3=$us1;
            $nkrs->nkrs4='';
            $nkrs->nkrs5='';
            if($field=='nkrs6')
                $nkrs->nkrs7=0;
            else
                $nkrs->nkrs6=0;
            $nkrs->nkrs8='';
            //$nkrs->$field=$value;
            $res=$nkrs->save();
            /*if(!$res)
            {
                print_r($nkrs->getErrors());
               */ //throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');
            /*}*/

        }

        Yii::app()->end(CJSON::encode(array('res' => $res)));
    }

    /**
     * @param $st1 int student id
     * @param $p1 int teacher id (nkrs6)
     * @param $url string url antiplagiat report
     */
    public function sendEmails($st1, $p1, $url)
    {
        $student = Users::model()->find('u5 = 0 and u6 = '.$st1);

        $st = St::model()->findByPk($st1);
        $studentName = $st->getShortName();

        $ps118 = PortalSettings::model()->getSettingFor(118);
        $body = '{student} You can find you antiplagiat results here: {link}';
        if(!empty($ps118))
            $body = $ps118;

        $link = tt('Отчет');
        $body = str_replace('{student}',$studentName,$body);
        $body = str_replace('{link}','<a href="'.$url.'">'.$link.'</a>',$body);

        if (! empty($student)) {

            if ($student->u4) {
                /*Apostle::setup("a596c9f9cb4066dd716911ef92be9bd040b0664d");
                $mail = new Mail( "antiplagiat-notification", array( "email" => $student->u4 ) );
                $mail->url  = $url;
                $mail->textFrom = implode(' ', array($st->st2, $st->st3, $st->st4));
                $mail->deliver();*/
                $message = str_replace('{username}',$student->u2,$body);
                $message = str_replace('{name}',$studentName,$message);

                list($status, $message)  = $this->mailsend($student->u4, 'Antiplagiat results', $message);

                if(!$status)
                    Yii::app()->user->setFlash('error', tt('Ошибка отправки email. Текст ошибки: ').$message);
            }

        }

        if ($p1 && PortalSettings::model()->getSettingFor(121)==1 ) {
            $teacher = Users::model()->find('u5 = 1 and u6 = '.$p1);
            if(empty($teacher))
                return;

            if ($teacher->u4) {

                $p = P::model()->findByPk($p1);
                /*Apostle::setup("a596c9f9cb4066dd716911ef92be9bd040b0664d");
                $mail = new Mail( "antiplagiat-notification", array( "email" => $teacher->u4 ) );
                $mail->url  = $url;
                $mail->textFrom = implode(' ', array($st->st2, $st->st3, $st->st4));
                $mail->deliver();*/

                $message = str_replace('{username}',$teacher->u2,$body);
                $message = str_replace('{name}',$p->getShortName(),$message);

                list($status, $message) =  $this->mailsend($teacher->u4, 'Antiplagiat results '.$studentName, $message);
                if(!$status)
                    Yii::app()->user->setFlash('error', tt('Ошибка отправки email для преподавателя. Текст ошибки: ').$message);
            }
        }

    }
}
