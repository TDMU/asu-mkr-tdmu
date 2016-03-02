<?php

class JournalController extends Controller
{
	public function filters() {

        return array(
            'accessControl',
        );
    }

    public function accessRules() {

        return array(
            array('allow',
                'actions' => array(
                    'journal',
                    'insertStMark',
                    'insertDopMark',
                    'insertMinMaxMark',
                    'checkCountRetake',
                    'journalRetake',
                    'saveJournalRetake',
                    'journalExcel',

                    'retake',
                    'searchRetake',
                    'addRetake',
                    'showRetake',
                    'saveRetake',
                    'deleteRetake',

                    'thematicPlan',
                    'renderUstemTheme',
                    'insertUstemTheme',
                    'pasteUstemTheme',
                    'copyUstemTheme',
                    'deleteUstemTheme',
                    'copyThemePlanSg',
                    'thematicExcel',

                    'omissions',
                    'updateOmissionsStMark',
                    'insertOmissionsStMark',

                    'searchStudent'
                ),
                'expression' => 'Yii::app()->user->isAdmin || Yii::app()->user->isTch',
            ),
            array('allow',
                'actions' => array('attendanceStatistic')
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }
//---------------------Journal---------------------------------------------------------------
    public function actionJournal()
    {
        $model = new FilterForm;
        $model->scenario = 'journal';
        /*if (isset($_REQUEST['showRetake'])) {
            Yii::app()->user->setState('showRetake',(int)$_REQUEST['showRetake']);
            unset($_REQUEST['showRetake']);
        }*/
        if (isset($_REQUEST['FilterForm']))
            $model->attributes=$_REQUEST['FilterForm'];

        $read_only=false;
        /*if(!empty($model->group))
        {
            list($uo1,$gr1) = explode("/", $model->group);

            $sql = <<<SQL
                     SELECT * FROM  EL_GURNAL(:P1,:YEAR,:SEM,0,2,:US1,0,0,:TYPE_LESSON);
SQL;
            $command = Yii::app()->db->createCommand($sql);

            $command->bindValue(':P1', Yii::app()->user->dbModel->p1);
            $command->bindValue(':UO1', $uo1);
            $command->bindValue(':US1', 0);
            $command->bindValue(':GR1', $gr1);
            $command->bindValue(':TYPE_LESSON', $model->type_lesson);
            $command->bindValue(':YEAR', Yii::app()->session['year']);
            $command->bindValue(':SEM', Yii::app()->session['sem']);
            $res = $command->queryRow();
            if(empty($res)||$res['dostup']==0)
            {
                $read_only=true;
            }
        }*/

        $this->render('journal', array(
            'model' => $model,
            'read_only' => $read_only,
        ));
    }

    public function actionInsertStMark()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');
        $error=false;
        $errorType=0;

        $st1 = Yii::app()->request->getParam('st1', null);
        $r1 = Yii::app()->request->getParam('r1', null);
        $elgz1 = Yii::app()->request->getParam('elgz1', null);
        $nom = Yii::app()->request->getParam('nom', null);
        $date = Yii::app()->request->getParam('date', null);
        $field = Yii::app()->request->getParam('field', null);
        $gr1 = Yii::app()->request->getParam('gr1', null);
        $value = Yii::app()->request->getParam('value', null);

        if($st1==null || $elgz1==null || $r1==null || $field==null || $value===null|| $nom==null || $date==null || $gr1==null)
        {
            $error = true;
            $errorType=2;
        }
        else {
            $sql = <<<SQL
            SELECT * FROM  EL_GURNAL(:P1,0,0,0,2,0,:R1,0,0);
SQL;
            $command = Yii::app()->db->createCommand($sql);
            $command->bindValue(':P1', Yii::app()->user->dbModel->p1);
            $command->bindValue(':R1', $r1);
            $res = $command->queryRow();
            if(count($res)==0 || empty($res)||$res['dostup']==0)
            {
                $error=true;
                $errorType=3;
            }

            $whiteList = array(
                'elgzst3', 'elgzst4','elgzst5',
            );
            if (!in_array($field, $whiteList))
                throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

            $elgz = Elgz::model()->findByPk($elgz1);

            if(empty($elgz))
            {
                $error = true;
                $errorType=2;
            }else
            {
                $ps2 = PortalSettings::model()->getSettingFor(27);
                $perm_enable=false;
                $elgr = Elgr::model()->findByAttributes(array('elgr1'=>$gr1,'elgr2'=>$elgz1));
                if(!empty($elgr))
                {
                    if(strtotime($elgr->elgr3)<=strtotime('yesterday'))
                        throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');
                    else
                        $perm_enable=true;
                }
                //проверка на количество дней после занятия, если прошло больше денй чем указано в настрйоках запрещаем вносить
                if(! empty($ps2) &&!$perm_enable){
                    $date1  = new DateTime(date('Y-m-d H:i:s'));
                    $date2  = new DateTime($date);
                    $diff = $date1->diff($date2)->days;
                    if ($diff > $ps2)
                        $error = true;
                }

                //проверка на макимальный и минимальный бал
                if($value!=0&&$value!=-1)
                    if ($field == 'elgzst4'||$field == 'elgzst5')
                    {
                        $min=PortalSettings::model()->findByPk(37)->ps2;
                        $bal=PortalSettings::model()->findByPk(36)->ps2;
                        if($bal!=0)
                        {
                            if($value>$bal||$value<$min)
                                if($value!=0)
                                {
                                    $error=true;
                                    $errorType=4;
                                }
                        }
                    }
                //блокировка пересдач
                if($field=='elgzst5' && PortalSettings::model()->findByPk(29)->ps2==1)
                {
                    $error=true;
                    $errorType=0;
                }

                $st=St::model()->findByPk($st1);
                if($st->st45==1)
                {
                    $error=true;
                    $errorType=5;
                }
                $sem7 = Gr::model()->getSem7ByGr1ByDate($gr1,date('d.m.Y'));
                $ps60 = PortalSettings::model()->findByPk(60)->ps2;
                if($st->st71!=$sem7&&$ps60==1)
                {
                    $error=true;
                    $errorType=5;
                }

                if(!$error)
                {
                    $arr=array();
                    if ($field == 'elgzst3')
                    {
                        if($value==0)
                            $value=1;
                        else {
                            $value=0;
                            $arr=array('elgzst5'=>'0');
                        }
                    }

                    $elgzst= Elgzst::model()->findByAttributes(array('elgzst1'=>$st1,'elgzst2'=>$elgz1));
                    if(!empty($elgzst))
                    {
                        if($elgzst->elgzst3>0&&$field=='elgzst5')
                        {
                            $elg = Elg::model()->findByPk($elgz->elgz2);
                            if($elg->elg4==0)
                                if($value==0)
                                    $value=-1;
                                else {
                                    $value=0;
                                }
                        }

                        if($field=='elgzst3'&&$value==1&&$elgzst->elgzst4>0)
                        {
                            $error=true;
                        }

                        if($field=='elgzst4'&&$value>0&&$elgzst->elgzst3>0)
                        {
                            $error=true;
                        }

                        if($field=='elgzst5'&&$value>0&&$elgzst->elgzst3==0&&$elgzst->elgzst4==0)
                        {
                            $error=true;
                        }

                        if(!$error) {
                            $attr = array_merge(array(
                                $field => $value,
                                'elgzst7' => Yii::app()->user->dbModel->p1,
                                'elgzst6' => date('Y-m-d H:i:s'),
                            ), $arr);
                            $error = !$elgzst->saveAttributes($attr);
                        }
                    }else
                    {
                        if($field=='elgzst5')
                        {
                            $error=true;
                        }else {
                            $elgzst = new Elgzst();
                            $elgzst->elgzst0 = new CDbExpression('GEN_ID(GEN_ELGZST, 1)');
                            $elgzst->elgzst1 = $st1;
                            $elgzst->elgzst2 = $elgz1;
                            $elgzst->elgzst7 = Yii::app()->user->dbModel->p1;
                            $elgzst->elgzst6 = date('Y-m-d H:i:s');
                            $elgzst->elgzst3 = 0;
                            $elgzst->elgzst4 = 0;
                            $elgzst->elgzst5 = 0;
                            $elgzst->$field = $value;

                            $error = !$elgzst->save();
                            if (!$error)
                                $elgzst = Elgzst::model()->findByAttributes(array('elgzst1' => $st1, 'elgzst2' => $elgz1));
                        }
                    }

                    if(!$error)
                    {
                        if($field == 'elgzst3'&&$value==0)
                        {
                            //если убираем пропуск удалем все записи с регитрацией пропусвов и отработок по этому занятию
                            Elgotr::model()->deleteAllByAttributes(array('elgotr1'=>$elgzst->elgzst0));
                            Elgp::model()->deleteAllByAttributes(array('elgp1'=>$elgzst->elgzst0));
                        }elseif($field == 'elgzst3')
                        {
                            //если ставим пропуск ищем есть ли у нас запись в таблице elgp ,если нет создаем
                            $elgp=Elgp::model()->findByAttributes(array('elgp1'=>$elgzst->elgzst0));
                            if(empty($elgp))
                            {
                                $elgp= new Elgp();
                                $elgp->elgp0=new CDbExpression('GEN_ID(GEN_ELGP, 1)');
                                $elgp->elgp1=$elgzst->elgzst0;
                                $elgp->elgp3='';
                                $elgp->elgp4='';
                                $elgp->elgp5=date('Y-m-d H:i:s');
                                $elgp->elgp7=Yii::app()->user->dbModel->p1;
                                $elgp->elgp6=date('Y-m-d H:i:s');
                                $error =!$elgp->save();
                            }
                        }
                    }
                }
            }
        }

        Yii::app()->end(CJSON::encode(array('error' => $error, 'errorType' => $errorType)));
    }

    public function actionInsertDopMark()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');
        $error=false;
        $errorType=0;

        $st1 = Yii::app()->request->getParam('st1', null);
        $elg1 = Yii::app()->request->getParam('elg1', null);
        $value = Yii::app()->request->getParam('value', null);
        $field = Yii::app()->request->getParam('field', null);

        if($st1==null || $elg1==null || $value==null || $field==null)
        {
            $error = true;
            $errorType=2;
        }
        else {
            $elgd=Elgd::model()->findByAttributes(array('elgd1'=>$elg1,'elgd2'=>$field));
            if(empty($elgd))
            {
                $error = true;
                $errorType=2;
            }else
            {
                $elgdst=Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgd->elgd0));
                if(empty($elgdst))
                {
                    $elgdst=new Elgdst();
                    $elgdst->elgdst0=new CDbExpression('GEN_ID(GEN_elgdst, 1)');
                    $elgdst->elgdst1=$st1;
                    $elgdst->elgdst2=$elgd->elgd0;
                }

                $elgdst->elgdst3=$value;
                $elgdst->elgdst5=Yii::app()->user->dbModel->p1;
                $elgdst->elgdst4=date('Y-m-d H:i:s');
                $errorType=!$elgdst->save();
            }
        }

        Yii::app()->end(CJSON::encode(array('error' => $error, 'errorType' => $errorType)));
    }

    public function actionInsertMinMaxMark()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $elgz1 = Yii::app()->request->getParam('elgz1', null);
        $field = Yii::app()->request->getParam('field', null);
        $value = Yii::app()->request->getParam('value', null);

        if($elgz1==null || $field==null || $value==null)
            $error = true;
        else {

            $whiteList = array(
                'elgz5', 'elgz6'
            );
            if (!in_array($field, $whiteList))
                throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

            $elgz=Elgz::model()->findByPk($elgz1);
            if(empty($elgz))
                $error=true;
            else
            {
                $elgz->$field=$value;
                $error =!$elgz->save();
            }

        }
        Yii::app()->end(CJSON::encode(array('error' => $error)));
    }

    public function actionCheckCountRetake()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $st1 = Yii::app()->request->getParam('st1', null);
        $elgz1 = Yii::app()->request->getParam('elgz1', null);

        $error = false;
        $count_result=false;
        $errorType =0;

        if($st1==null || $elgz1==null)
        {
            $error = true;
            $errorType=2;
        }

        if(!$error)
        {
            $elgzst=Elgzst::model()->findByAttributes(array('elgzst1'=>$st1,'elgzst2'=>$elgz1));
            if(!empty($elgzst))
            {
                $count =Elgotr::model()->countByAttributes(array('elgotr1'=>$elgzst->elgzst0));

                if($count>0)
                    $count_result=true;
            }else
            {
                $error = true;
                $errorType=2;
            }
        }

        Yii::app()->end(CJSON::encode(array('error' => $error,'errorType'=>$errorType,'count'=>$count_result)));
    }

    public function actionJournalRetake()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $error=false;
        $errorType=0;
        $title='';
        $html='';

        $st1 = Yii::app()->request->getParam('st1', null);
        $r1 = Yii::app()->request->getParam('r1', null);
        $elgz1 = Yii::app()->request->getParam('elgz1', null);
        $nom = Yii::app()->request->getParam('nom', null);
        $date = Yii::app()->request->getParam('date', null);
        $gr1 = Yii::app()->request->getParam('gr1', null);
        $sem1 = Sem::model()->getSem1ByGr1($gr1);

        if($st1==null || $elgz1==null || $r1==null || $nom==null || $date==null || $gr1==null)
        {
            $error = true;
            $errorType=2;
        }else
        {
            $elgzst=Elgzst::model()->findByAttributes(array('elgzst1'=>$st1,'elgzst2'=>$elgz1));
            $elgz = Elgz::model()->findByPk($elgzst->elgzst2);
            if(empty($elgzst))
            {
                $error = true;
                $errorType=2;
            }else
            {
                //if(($elgzst->elgzst3!=0||($elgzst->elgzst4<=$elgzst->getMin()&&$elgzst->elgzst4!=0)))
                $ps55 = PortalSettings::model()->findByPk(55)->ps2;
                if($elgzst->elgzst3!=0||($elgzst->elgzst4<=$elgzst->getMin()&&($elgzst->elgzst4 != 0 || ($ps55 == 1 && $elgzst->elgzst4 == 0))))
                {
                    if($elgzst->checkMinRetakeForGrid())
                    {
                        $elg = Elg::model()->getElgByElgzst0($elgzst->elgzst0);

                        $sql=<<<SQL
                select first 1 us4 from EL_GURNAL_ZAN(:UO1,:GR1,:SEM1, :ELG4) where EL_GURNAL_ZAN.nom = :NOM
SQL;
                        $command=Yii::app()->db->createCommand($sql);
                        $command->bindValue(':NOM', $elgz->elgz3);
                        $command->bindValue(':GR1', $gr1);
                        $command->bindValue(':SEM1', $sem1);
                        $command->bindValue(':UO1', $elg['elg2']);
                        $command->bindValue(':ELG4', $elg['elg4']);
                        $us4 = $command->queryRow();
                        //print_r($elgz->elgz3.' /'.$gr1.'/'.$sem1.'/'.$elg['elg2'].'/'.$elg['elg4']);
                        $info=$elgzst->getInfoByElgzst0($elg['elg2'],$sem1,$gr1,$elg['elg4']);

                        if(!empty($info))
                            $title.=' '.SH::getShortName($info['st2'],$info['st3'],$info['st4']).' '.date('d.m.Y', strtotime($info['r2'])).' '.$info['d3'];

                        if($elgzst->elgzst3>0)
                        {
                            $elgp=Elgp::model()->findByAttributes(array('elgp1'=>$elgzst->elgzst0));
                            if(empty($elgp))
                            {
                                //$error = true;
                                //$errorType=2;
                                $elgp = new Elgp();
                                $elgp->elgp0=new CDbExpression('GEN_ID(GEN_ELGP, 1)');
                                $elgp->elgp1=$elgzst->elgzst0;
                                $elgp->elgp3='';
                                $elgp->elgp4='';
                                $elgp->elgp5=date('Y-m-d H:i:s');
                                $elgp->elgp7=Yii::app()->user->dbModel->p1;
                                $elgp->elgp6=date('Y-m-d H:i:s');
                                $elgp->save();
                            }

                            $html .= $this->renderPartial('journal/_add_omissions', array(
                                'model'=>$elgp,
                                'us4'=>$us4['us4']
                            ), true);

                        }

                        if(!empty($elgzst)&&!empty($us4))
                        {
                            $model=new Elgotr();
                            $model->unsetAttributes();
                            $model->elgotr1=$elgzst->elgzst0;

                            $html .= $this->renderPartial('journal/_add_retake', array(
                                'model' => $model,
                                'elgzst'=>$elgzst,
                                'us4'=>$us4['us4'],
                                'r1'=>$r1
                            ), true);
                        }else
                            $error=true;
                    }else
                    {
                        $error = true;
                        $errorType=2;
                    }
                }else
                {
                    $error = true;
                    $errorType=2;
                }
            }
        }

        $res = array(
            'title'=>$title,
            'html'=>$html,
            'error' => $error,
            'errorType'=>$errorType
        );

        Yii::app()->end(CJSON::encode($res));
    }

    public function actionSaveJournalRetake(){
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $error=false;
        $errorType=0;

        $elgp2=Yii::app()->request->getParam('elgp2', null);
        $elgp3=Yii::app()->request->getParam('elgp3', null);
        $elgp4=Yii::app()->request->getParam('elgp4', null);
        $elgp5=Yii::app()->request->getParam('elgp5', null);

        $elgotr1=Yii::app()->request->getParam('elgotr1', null);
        $elgotr2=Yii::app()->request->getParam('elgotr2', null);
        $elgotr3=Yii::app()->request->getParam('elgotr3', null);
        $elgotr4=Yii::app()->request->getParam('elgotr4', null);

        if($elgotr1==null || $elgotr2==null || $elgotr3==null)
        {
            $error = true;
            $errorType=2;
        }else
        {
            $elgzst=Elgzst::model()->findByPk($elgotr1);
            $elgz = Elgz::model()->findByPk($elgzst->elgzst2);
            if(empty($elgzst))
            {
                $error = true;
                $errorType=2;
            }else
            {
                $ps55 = PortalSettings::model()->findByPk(55)->ps2;
                if($elgzst->elgzst3!=0||($elgzst->elgzst4<=$elgzst->getMin()&&($elgzst->elgzst4 != 0 || ($ps55 == 1 && $elgzst->elgzst4 == 0))))
                {
                    if($elgzst->checkMinRetakeForGrid())
                    {
                        $gr1 = St::model()->getGr1BySt1($elgzst->elgzst1);

                        $elg = Elg::model()->getElgByElgzst0($elgzst->elgzst0);

                        $sem1 = Sem::model()->getSem1ByGr1($gr1);

                        $sql=<<<SQL
                select first 1 us4 from EL_GURNAL_ZAN(:UO1,:GR1,:SEM1, :ELG4) where EL_GURNAL_ZAN.nom = :NOM
SQL;
                        $command=Yii::app()->db->createCommand($sql);
                        $command->bindValue(':NOM', $elgz->elgz3);
                        $command->bindValue(':GR1', $gr1);
                        $command->bindValue(':SEM1', $sem1);
                        $command->bindValue(':UO1', $elg['elg2']);
                        $command->bindValue(':ELG4', $elg['elg4']);
                        //print_r($elgz->elgz3.' /'.$gr1.'/'.$sem1.'/'.$elg['elg2'].'/'.$elg['elg4']);
                        $us4 = $command->queryRow();


                        if($elgzst->elgzst3>0)
                        {
                            $elgp=Elgp::model()->findByAttributes(array('elgp1'=>$elgzst->elgzst0));
                            if(empty($elgp))
                            {
                                $error = true;
                                $errorType=2;
                            }else
                            {
                                $elgp->elgp2=$elgp2;
                                $elgp->elgp3=$elgp3;
                                $elgp->elgp4=$elgp4;
                                $elgp->elgp5=$elgp5;
                                $error=!$elgp->save();
                                if($error) {
                                    $errorType = 9;
                                   /* print_r($errorType);
                                    echo '</br>';*/
                                }
                            }
                        }
                        /*print_r($errorType);
                        echo '</br>';*/
                        $ps40=PortalSettings::model()->findByPk(40)->ps2;
                        if(! empty($ps40)){
                            /*$date1  = new DateTime(date('Y-m-d H:i:s'));
                            $date2  = new DateTime($stegn->stegn9);
                            $diff = $date1->diff($date2)->days;
                            if ($diff > $ps40)
                                $error=true;*/
                        }

                        if(!$error&&!empty($elgzst))
                        {
                            $model=new Elgotr();
                            $model->elgotr0=new CDbExpression('GEN_ID(GEN_ELGOTR, 1)');
                            $model->elgotr1=$elgzst->elgzst0;
                            $model->elgotr2=$elgotr2;
                            $model->elgotr3=$elgotr3;
                            $model->elgotr4=$elgotr4;
                            $model->elgotr5=date('Y-m-d H:i:s');
                            $error=!$model->save();
                            if(!$error)
                            {
                                $elgzst->elgzst5=$elgotr2;
                                $elgzst->save();
                            }else
                            {
                                $errorType=7;
                            }
                        }else
                        {
                            /*print_r($error);*/
                            $error=true;
                            $errorType=6;
                            /*print_r($elgzst);
                            print_r($us4);*/
                        }
                    }else
                    {
                        $error = true;
                        $errorType=2;
                    }
                }else
                {
                    $error = true;
                    $errorType=2;
                }
            }
        }
        $res = array(
            'error' => $error,
            'errorType'=>$errorType
        );

        Yii::app()->end(CJSON::encode($res));
    }

    public function actionJournalExcel()
    {
        $model = new FilterForm;
        $model->scenario = 'journal';
        if (isset($_REQUEST['FilterForm']))
            $model->attributes=$_REQUEST['FilterForm'];
        if(!empty($model->group))
        {
            list($uo1,$gr1) = explode("/", $model->group);
            $sql = <<<SQL

                select sem4,gr19,gr20,gr21,gr22,gr23,gr24,gr28,gr3,f3,f2
                from sem
                  inner join sg on (sem2 = sg1)
                  inner join gr on (sg1 = gr2)
                  inner join sp on (sg2 = sp1)
                  inner join f on (sp5 = f1)
                where gr1=:gr1 and sem3=:YEAR and sem5=:SEM
SQL;
            $command = Yii::app()->db->createCommand($sql);
            $command->bindValue(':gr1', $gr1);
            $command->bindValue(':YEAR', Yii::app()->session['year']);
            $command->bindValue(':SEM', Yii::app()->session['sem']);
            $res = $command->queryRow();
            $course='-';
            $group='-';
            $faculty='-';
            if($res!=null)
            {
                $course=$res['sem4'];
                $group=Gr::model()->getGroupName($res['sem4'], $res);
                $faculty=$res['f3'];
            }

            $students = St::model()->getStudentsForJournal($gr1, $uo1);
            $dates = R::model()->getDatesForJournal(
                $uo1,
                $gr1,
                $model->type_lesson
            );

            $elg1=Elg::getElg1($uo1,$model->type_lesson);
            $elg = Elg::model()->findByPk($elg1);
            if(empty($elg))
                throw new CHttpException(404, 'Elg empty.');

            $year=(int)Yii::app()->session['year'];
            $first_title='%s семестр %s - %s навчальний рік %s курс';
            $second_title='%s факультет %s група';

            Yii::import('ext.phpexcel.XPHPExcel');
            $objPHPExcel= XPHPExcel::createPHPExcel();
            $objPHPExcel->getProperties()->setCreator("ACY")
                ->setLastModifiedBy("ACY ".date('Y-m-d H-i'))
                ->setTitle("Jornal ".date('Y-m-d H-i'))
                ->setSubject("Jornal ".date('Y-m-d H-i'))
                ->setDescription("Jornal document, generated using ACY Portal. ".date('Y-m-d H:i:'))
                ->setKeywords("")
                ->setCategory("Result file");
            $objPHPExcel->setActiveSheetIndex(0);
            $sheet=$objPHPExcel->getActiveSheet();
            $sheet->mergeCells('A1:J1');
            $sheet->setCellValue('A1', sprintf($first_title,SH::convertSem5(Yii::app()->session['sem']),$year,($year+1),$course));
            $sheet->mergeCells('K1:Z1');
            $sheet->setCellValue('K1', sprintf($second_title,$faculty,$group));
            //таблица
            $sheet->getColumnDimension('A')->setWidth(4);
            $sheet->mergeCells('A2:A3')->setCellValue('A2','№ з/п')->getStyle('A2')->getAlignment()->setTextRotation(90)-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->getColumnDimension('B')->setWidth(30);
            $sheet->mergeCells('B2:B3')->setCellValue('B2','Прізвище, ім`я, по батькові')->getStyle('B2')->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->getRowDimension(2)->setRowHeight(26);
            $sheet->getRowDimension(3)->setRowHeight(93);
            //колонка с фио студентов
            $i=0;
            $count_st=0;
            foreach ($students as $key => $student)
            {
                //$name = ShortCodes::getShortName($student['st2'], $student['st3'], $student['st4']);
                $name = $student['st2'].' '.$student['st3'].' '.$student['st4'];
                $num  = $key+1;
                $sheet->mergeCellsByColumnAndRow(0, $i+ 4, 0, $i+5);
                $sheet->mergeCellsByColumnAndRow(1, $i+ 4, 1, $i+5);
                $sheet->setCellValueByColumnAndRow(0,$i + 4,$num);
                $sheet->setCellValueByColumnAndRow(1,$i + 4,$name)->getStyleByColumnAndRow(1,$i + 4)->getAlignment()->setWrapText(true);
                $i++;
                $i++;
                $count_st++;
            }
            $count_st_column=$i;
            //верх таблицы с датами
            $i=0;
            foreach($dates as $date) {
                $type='';
                switch ($date['elgz4']) {
                    case 1:
                        $type=tt('Субмодуль');
                        break;
                    case 2:
                        $type=tt('Модуль');
                        break;
                    default:
                        $type=tt('Занятие');
                        break;
                }
                $type=' '.$type;
                $title='№'.$date['elgz3'].' '.$date['formatted_date'].' '.SH::convertUS4($date['us4']).$type;
                $sheet->setCellValueByColumnAndRow($i+2,3,$title);
                $sheet->setCellValueByColumnAndRow($i+2,4+$count_st_column,$date['ustem7']);
                $sheet->setCellValueByColumnAndRow($i+2,5+$count_st_column,$date['ustem5']);
                $i++;
            }
            $sheet->mergeCellsByColumnAndRow(2, 2, 1+$i, 2)->setCellValueByColumnAndRow(2, 2,'Дата')->getStyleByColumnAndRow(2, 2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->getStyleByColumnAndRow(2, 3, 1+$i, 3)->getAlignment()->setWrapText(true)->setTextRotation(90)-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->setCellValueByColumnAndRow(1,4+$count_st_column,'Кількість годин')->getStyleByColumnAndRow(1,4+$count_st_column)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->getStyleByColumnAndRow(2, 4+$count_st_column, 1+$i, 4+$count_st_column)->getAlignment()->setWrapText(true)-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->setCellValueByColumnAndRow(1,5+$count_st_column,'Тема')->getStyleByColumnAndRow(1,5+$count_st_column)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->getStyleByColumnAndRow(2, 5+$count_st_column, 1+$i, 5+$count_st_column)->getAlignment()->setWrapText(true)->setTextRotation(90)-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->getRowDimension(5+$count_st_column)->setRowHeight(93);
            $sheet->setCellValueByColumnAndRow(1,6+$count_st_column,'Підпис викладача')->getStyleByColumnAndRow(1,6+$count_st_column)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->getRowDimension(6+$count_st_column)->setRowHeight(93);
            $i=0;
            $k=0;
            foreach($students as $st) {
                $st1 = $st['st1'];
                $marks = $elg->getMarksForStudent($st1);
                $k=0;
                foreach($dates as $key => $date) {
                    $key = $date['elgz3']; // 0 - r3
                    $elgzst3 = isset($marks[$key]) && $marks[$key]['elgzst3'] != 0
                        ? 'нб'
                        : '';
                    $elgzst4 = isset($marks[$key]) && $marks[$key]['elgzst4'] != 0
                        ? round($marks[$key]['elgzst4'], 1)
                        : '';
                    $elgzst5 = isset($marks[$key]) && $marks[$key]['elgzst5'] != 0 && $marks[$key]['elgzst5']!=-1
                        ? round($marks[$key]['elgzst5'], 1)
                        :( isset($marks[$key]) && $marks[$key]['elgzst5']==-1?tt('Отработано'):'');
                    if(($elgzst5!='')||($elgzst3!=''))
                    {
                        if($elgzst3!='')
                        {
                            $sheet->setCellValueByColumnAndRow($k+2,$i*2 + 5,$elgzst3);
                            $sheet->setCellValueByColumnAndRow($k+2,$i*2 + 4,$elgzst5);
                        }else
                        {
                            $sheet->setCellValueByColumnAndRow($k+2,$i*2 + 5,$elgzst4);
                            $sheet->setCellValueByColumnAndRow($k+2,$i*2 + 4,$elgzst5);
                        }
                    }else
                    {
                        $sheet->mergeCellsByColumnAndRow($k+2, $i*2+ 4, $k+2, $i*2+5);
                        $sheet->setCellValueByColumnAndRow($k+2,$i*2 + 4,$elgzst4);
                    }
                    $k++;
                }
                $i++;
            }
            $sheet->getStyleByColumnAndRow(0,1,$k+1,6+$count_st_column)->getBorders()->getAllBorders()->applyFromArray(array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')));
            $sheet->setTitle('Jornal '.date('Y-m-d H-i'));

            //----------------------отраотка---------------------------------
            $objWorkSheet = $objPHPExcel->createSheet(1);
            $objPHPExcel->setActiveSheetIndex(1);
            $sheet=$objPHPExcel->getActiveSheet();
            $sheet->setTitle('Retake '.date('Y-m-d H-i'));

            $sheet->mergeCells('A1:H1');
            $sheet->setCellValue('A1', tt('Журнал регистрации результатов отработки пропущенных занятий и "2" '))
                ->getStyle('A1')->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->mergeCells('A2:H2');
            $sheet->setCellValue('A2', tt('Группа').' '.$group)
                ->getStyle('A2')->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $otr = Elgotr::model()->getElgotrForExcel($model);

            $sheet->setCellValueByColumnAndRow(0,3, '№ п/п');
            $sheet->setCellValueByColumnAndRow(1,3,tt('ФИО'));
            $sheet->setCellValueByColumnAndRow(2,3,tt('№ зан.'));
            $sheet->setCellValueByColumnAndRow(3,3,tt('№ справки'));
            $sheet->setCellValueByColumnAndRow(4,3,tt('Причина нб("2")'));
            $sheet->setCellValueByColumnAndRow(5,3,tt('№ квитанции'));
            $sheet->setCellValueByColumnAndRow(6,3,tt('ФИО преподавателя (отработка)'));
            $sheet->setCellValueByColumnAndRow(7,3,tt('Результат отработки'));
            $sheet->getStyleByColumnAndRow(0,3,7,3)->getAlignment()->setWrapText(true)-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->getRowDimension(3)->setRowHeight(-1);
            $sheet->getColumnDimensionByColumn(0)->setWidth(4);
            $sheet->getColumnDimensionByColumn(1)->setWidth(30);
            $sheet->getColumnDimensionByColumn(2)->setWidth(7);
            $sheet->getColumnDimensionByColumn(3)->setWidth(20);
            $sheet->getColumnDimensionByColumn(4)->setWidth(10);
            $sheet->getColumnDimensionByColumn(5)->setWidth(20);
            $sheet->getColumnDimensionByColumn(6)->setWidth(30);
            $sheet->getColumnDimensionByColumn(7)->setWidth(10);
            $i=4;
            foreach($otr as $val)
            {
                $sheet->setCellValueByColumnAndRow(0,$i, $i-3);
                $sheet->setCellValueByColumnAndRow(1,$i,SH::getShortName($val['st2'],$val['st3'],$val['st4']));
                $sheet->setCellValueByColumnAndRow(2,$i,$val['elgz3']);
                $sheet->setCellValueByColumnAndRow(3,$i,$val['elgp3']);
                switch ($val['elgzst3']){
                    case 0: $type = tt('Двойка');
                            break;
                    case 1: $type = tt('Неув.');
                        break;
                    case 2: $type = tt('Уваж.');
                        break;
                    default: $type='-';
                }
                $sheet->setCellValueByColumnAndRow(4,$i,$type);
                $sheet->setCellValueByColumnAndRow(5,$i,$val['elgp4']);
                $sheet->setCellValueByColumnAndRow(6,$i,SH::getShortName($val['p3'],$val['p4'],$val['p5']));
                $elgotr2=$val['elgotr2'];
                if($elgotr2==-1)
                    $elgotr2 = tt('Отработано');
                $sheet->setCellValueByColumnAndRow(7,$i,$elgotr2);
                $sheet->getRowDimension($i)->setRowHeight(-1);
                $i++;
            }

            $sheet->getStyleByColumnAndRow(0,3,7,$i-1)->getBorders()->getAllBorders()->applyFromArray(array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')));

            //----------------------------------title---------------------------------
            $objWorkSheet = $objPHPExcel->createSheet(2);
            $objPHPExcel->setActiveSheetIndex(2);
            $sheet=$objPHPExcel->getActiveSheet();
            $sheet->setTitle('Title');

            $ps45 = PortalSettings::model()->findByPk(45)->ps2;//вуз
            $ps46 = PortalSettings::model()->findByPk(46)->ps2;//министерство

            $sheet->mergeCells('A1:I1');
            $sheet->setCellValue('A1',$ps46)->getStyle('A1')->getFont()->setSize(14);
            $sheet->mergeCells('A2:I2');
            $sheet->setCellValue('A2',$ps45)->getStyle('A2')->getFont()->setSize(12);
            $sheet->mergeCells('A10:I10');
            $sheet->setCellValue('A10',tt('Журнал'))->getStyle('A10')->getFont()->setSize(33);
            $sheet->getRowDimension(10)->setRowHeight(42);
            $sheet->mergeCells('A11:I11');
            $sheet->setCellValue('A11',tt('учета посещаемости и успеваемости студентов'))
                ->getStyle('A11')->getFont()->setSize(21);
            $sheet->getRowDimension(11)->setRowHeight(24);

            $type=tt('Лекции');
            if($model->type_lesson==1)
                $type=tt('Практические');
            $sheet->mergeCells('A12:I12');
            $sheet->setCellValue('A12',$type)->getStyle('A12')->getFont()->setSize(16);

            $sheet->getStyleByColumnAndRow(0,1,1,18)->getAlignment()->setWrapText(true)->
                setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $chair = K::model()->findByPk(K::model()->getChairByUo1($elg->elg2));

            if(!empty($chair))
            {
                $sheet->mergeCells('A17:I17');
                $sheet->setCellValue('A17',tt('Кафедра').' '.$chair->k3)->getStyle('A17')->getFont()->setSize(16);
            }

            $disp = D::model()->getDisciplineForUo1($elg->elg2);
            if(!empty($disp))
            {
                $sheet->mergeCells('A18:I18');
                $sheet->setCellValue('A18',tt('Дисциплина').': '.$disp->d2)->getStyle('A18')->getFont()->setSize(16);
            }
            $sheet->mergeCells('A22:I22');
            $sheet->setCellValue('A22',tt('Факультет').': '.$faculty)->getStyle('A22')->getFont()->setSize(16);
            $sheet->mergeCells('A23:I23');
            $sheet->setCellValue('A23',tt('Группа, курс').': '.$group.', '.$course)->getStyle('A23')->getFont()->setSize(16);
            $sheet->mergeCells('A24:I24');
            $res = P::model()->getTeacherByUo($elg->elg2);
            $p='';
            if(!empty($res))
                $p = SH::getShortName($res['p3'], $res['p4'], $res['p5']);
            $sheet->setCellValue('A24',tt('Преподаватель').': '.$p)->getStyle('A24')->getFont()->setSize(16);
            $res = Gr::model()->getStarostaFromGr1($gr1);
            $nameStarosta='';
            if(!empty($res))
                $nameStarosta=SH::getShortName($res['st2'],$res['st3'],$res['$st4']);
            $sheet->mergeCells('A25:I25');
            $sheet->setCellValue('A25',tt('Староста').': '.$res)->getStyle('A25')->getFont()->setSize(16);

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a clientâ€™s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="ACY_JORNAL_'.date('Y-m-d H-i').'.xls"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
            // If you're serving to IE over SSL, then the following may be needed
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        }
        Yii::app()->end();
    }

//--------------------------------------------------------------------------------------
//--------------------Omissions---------------------------------------------------------------
    public function actionOmissions()
    {
        $model = new TimeTableForm;
        $model->scenario = 'omissions';

        if (isset($_REQUEST['TimeTableForm']))
            $model->attributes=$_REQUEST['TimeTableForm'];
        $model->date1 = Yii::app()->session['date1'];
        $model->date2 = Yii::app()->session['date2'];
        $student = new St;
        $student->unsetAttributes();
        $this->render('omissions', array(
            'model'      => $model,
            'student'	 =>$student,
        ));
    }

    public function actionUpdateOmissionsStMark()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $st1 = Yii::app()->request->getParam('st1', null);
        $date1 = Yii::app()->request->getParam('date1', null);
        $date2 = Yii::app()->request->getParam('date2', null);
        $number = Yii::app()->request->getParam('number', null);
        $type = Yii::app()->request->getParam('type_omissions', null);
        $elgp4= Yii::app()->request->getParam('elgp4', null);
        $elgp5 = Yii::app()->request->getParam('elgp5', null);

        if($st1==null || $date1==null || $date2==null || $type==null)
            $error = true;
        else {
            $check=1;

            if($type<=4)
                $check=2;

            $attr = array(
                'elgzst3' => $check,
                'elgzst7' =>  Yii::app()->user->dbModel->p1,
                'elgzst6' =>  date('Y-m-d H:i:s'),
            );
            $elgz_arr=Elgzst::model()->getElgz1ArrForOmissions($st1,$date1,$date2);
            $criteria = new CDbCriteria();
            $criteria->compare('elgzst1', $st1);
            $criteria->compare('elgzst3','>=1');
            $criteria->addInCondition("elgzst2",$elgz_arr);
            $count = Elgzst::model()->updateAll($attr,$criteria);

            if ($count==-1)
                $error = true;
            else
            {
                $error = false;

                $criteria->select='elgzst0';
                //находим все стегн в этом интервале
                $models=Elgzst::model()->findAll($criteria);
                if(!empty($models)){
                    $arr_id=array();
                    foreach($models as $model)
                    {
                        array_push($arr_id,$model->elgzst0);
                    }
                    $new_attr=array(
                        'elgp3' => $number,
                        'elgp2' => $type,
                        'elgp4' => $elgp4,
                        'elgp5' => $elgp5,
                        'stegnp7' =>  Yii::app()->user->dbModel->p1,
                        'stegnp6' =>  date('Y-m-d H:i:s'),
                    );

                    $new_criteria = new CDbCriteria();
                    $new_criteria->addInCondition('elgp1', $arr_id);
                    //редактируем все стегнп коротые связы с этими стегн
                    $count = Elgp::model()->updateAll($new_attr,$new_criteria);
                    if ($count==-1)
                        $error = true;
                }
            }
        }

        Yii::app()->end(CJSON::encode(array('error' => $error)));
    }

    public function actionInsertOmissionsStMark()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $elgp0 = Yii::app()->request->getParam('elgp0', null);
        $field = Yii::app()->request->getParam('field', null);
        $value = Yii::app()->request->getParam('value', null);

        $error =false;

        if($elgp0==null  || $field==null || $value==null)
            $error = true;
        else {
            $whiteList = array(
                'elgp2','elgp3','elgp4','elgp5'
            );
            $arr=array();
            if($field=='elgp2')
            {
                $check=1;
                if($value<=4)
                    $check=2;

                $arr=array('elgzst3'=>$check);
            }

            if (!in_array($field, $whiteList))
                $error = true;
            $elgp=Elgp::model()->findByPk($elgp0);

            if(empty($elgp))
                $error=true;

            if(!$error)
            {
                $attr = array_merge(array(
                    $field => $value,
                    'elgzst7' =>  Yii::app()->user->dbModel->p1,
                    'elgzst6' =>  date('Y-m-d H:i:s'),
                ),$arr);

                $criteria = new CDbCriteria();
                $criteria->compare('elgzst0', $elgp->elgp1);
                $count = Elgzst::model()->updateAll($attr,$criteria);

                if ($count==-1)
                    $error = true;
                else
                {
                    $error = !$elgp->saveAttributes(
                        array(
                            $field => $value,
                            'elgp7' =>  Yii::app()->user->dbModel->p1,
                            'elgp6' =>  date('Y-m-d H:i:s')
                        )
                    );
                }
            }
        }
        Yii::app()->end(CJSON::encode(array('error' => $error)));
    }
//--------------------------------------------------------------------------------------
//--------------------Retake---------------------------------------------------------------
    public function actionRetake()
    {
        $model = new FilterForm();
        $model->scenario = 'retake';
        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize',(int)$_GET['pageSize']);
            unset($_GET['pageSize']);  // сбросим, чтобы не пересекалось с настройками пейджера
        }
        if (isset($_REQUEST['FilterForm']))
            $model->attributes=$_REQUEST['FilterForm'];
        $retake = new Elgzst();
        $retake->unsetAttributes();
        $this->render('retake', array(
            'model'      => $model,
            'retake'      => $retake,
        ));
    }

    public function actionSearchRetake($uo1,$us1)
    {
        //if (! Yii::app()->request->isAjaxRequest)
            //throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');
        $model = new Elgzst('search');
        $model->unsetAttributes();
        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize',(int)$_GET['pageSize']);
            unset($_GET['pageSize']);  // сбросим, чтобы не пересекалось с настройками пейджера
        }
        $model->uo1=$uo1;
        $us=Us::model()->findByPk($us1);
        $model->type_lesson=$us->us4;
        if (isset($_REQUEST['Elgzst']))
            $model->attributes = $_REQUEST['Elgzst'];


        $this->render('retake/_grid', array(
            'model' => $model,
            'us1'=>$us1
        ));
    }

    public function actionAddRetake()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $elgzst0 = Yii::app()->request->getParam('elgzst0', null);
        $sem1 = Yii::app()->request->getParam('sem1', null);
        $r1 = Yii::app()->request->getParam('r1', null);
        $gr1 = Yii::app()->request->getParam('gr1', null);


        $error=false;
        $html='';
        $title=tt('Отработка');

        if(empty($elgzst0))
            $error=true;

        if(!$error)
        {
            $elgzst=Elgzst::model()->findByAttributes(array('elgzst0'=>$elgzst0));
            $elgz = Elgz::model()->findByPk($elgzst->elgzst2);

            //$gr1 = St::model()->getGr1BySt1($elgzst->elgzst1);

            $elg = Elg::model()->getElgByElgzst0($elgzst->elgzst0);

            $sql=<<<SQL
                select first 1 us4 from EL_GURNAL_ZAN(:UO1,:GR1,:SEM1, :ELG4) where EL_GURNAL_ZAN.nom = :NOM
SQL;
            $command=Yii::app()->db->createCommand($sql);
            $command->bindValue(':NOM', $elgz->elgz3);
            $command->bindValue(':GR1', $gr1);
            $command->bindValue(':SEM1', $sem1);
            $command->bindValue(':UO1', $elg['elg2']);
            $command->bindValue(':ELG4', $elg['elg4']);
            $us4 = $command->queryRow();

            if(!empty($elgzst)&&!empty($us4))
            {
                $info=$elgzst->getInfoByElgzst0($elg['elg2'],$sem1,$gr1,$elg['elg4']);
                if(!empty($info))
                {
                    $title.=' '.SH::getShortName($info['st2'],$info['st3'],$info['st4']).' '.date('d.m.Y', strtotime($info['r2'])).' '.$info['d3'];
                }
                $model=new Elgotr();
                $model->unsetAttributes();
                $model->elgotr1=$elgzst0;
                $html = $this->renderPartial('retake/_add_retake', array(
                    'model' => $model,
                    'elgzst'=>$elgzst,
                    'us4'=>$us4['us4'],
                    'r1'=>$r1
                ), true);
            }else
                $error=true;
        }
        $res = array(
            'title'=>$title,
            'html' => $html,
            'error' => $error,
            'show'=>true,
        );

        Yii::app()->end(CJSON::encode($res));
    }

    public function actionShowRetake()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $elgzst0 = Yii::app()->request->getParam('elgzst0', null);
        $sem1 = Yii::app()->request->getParam('sem1', null);
        $gr1 = Yii::app()->request->getParam('gr1', null);

        $error=false;
        $html='';
        $title=tt('Отработка');
        if(empty($elgzst0))
            $error=true;

        if(!$error)
        {
            $models=Elgotr::model()->findAllByAttributes(array('elgotr1'=>$elgzst0));
            $elgzst=Elgzst::model()->findByAttributes(array('elgzst0'=>$elgzst0));
            $elgz = Elgz::model()->findByPk($elgzst->elgzst2);

            $gr1 = St::model()->getGr1BySt1($elgzst->elgzst1);

            $elg = Elg::model()->getElgByElgzst0($elgzst->elgzst0);

            $sql=<<<SQL
                select first 1 us4 from EL_GURNAL_ZAN(:UO1,:GR1,:SEM1, :ELG4) where EL_GURNAL_ZAN.nom = :NOM
SQL;
            $command=Yii::app()->db->createCommand($sql);
            $command->bindValue(':NOM', $elgz->elgz3);
            $command->bindValue(':GR1', $gr1);
            $command->bindValue(':SEM1', $sem1);
            $command->bindValue(':UO1', $elg['elg2']);
            $command->bindValue(':ELG4', $elg['elg4']);
            $us4 = $command->queryRow();

            $info=$elgzst->getInfoByElgzst0($elg['elg2'],$sem1,$gr1,$elg['elg4']);
            if(!empty($info))
            {
                $title.=' '.SH::getShortName($info['st2'],$info['st3'],$info['st4']).' '.date('d.m.Y', strtotime($info['r2'])).' '.$info['d3'];
            }

            $html = $this->renderPartial('retake/_show_retake', array(
                'models' => $models,
                'us4'=>$us4['us4']
            ), true);
        }

        $res = array(
            'title'=>$title,
            'html'=>$html,
            'error' => $error,
            'show'=>false,
        );

        Yii::app()->end(CJSON::encode($res));
    }

    public function actionSaveRetake()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $elgotr1 = Yii::app()->request->getParam('elgotr1', null);
        $value = Yii::app()->request->getParam('value', null);
        $date = Yii::app()->request->getParam('date', null);
        $p1 = Yii::app()->request->getParam('p1', null);

        $error=false;

        if(empty($elgotr1)||$value===null||empty($date)||empty($p1))
            $error=true;
        if(!$error)
        {
            $elgzst=Elgzst::model()->findByPk($elgotr1);
            $ps40=PortalSettings::model()->findByPk(40)->ps2;
            if(! empty($ps40)){
                /*$date1  = new DateTime(date('Y-m-d H:i:s'));
                $date2  = new DateTime($stegn->stegn9);
                $diff = $date1->diff($date2)->days;
                if ($diff > $ps40)
                    $error=true;*/
            }

            if($elgzst->elgzst5<=$elgzst->getMin()&&!$error)
            {
                $model= new Elgotr();
                $model->elgotr0=new CDbExpression('GEN_ID(GEN_ELGOTR, 1)');
                $model->elgotr1=$elgotr1;
                $model->elgotr2=$value;
                $model->elgotr3=$date;
                $model->elgotr4=Yii::app()->user->dbModel->p1;
                $model->elgotr4=$p1;
                //$model->elgotr5=Yii::app()->user->dbModel->p1;
                $model->elgotr5=date('Y-m-d H:i:s');
                $error=!$model->save();
                if(!$error)
                {
                    $elgzst->elgzst5=$value;
                    $elgzst->save();
                }
            }  else {
                $error= true;
                //print_r($elgzst->elgzst5);
            }

        }
        $res = array(
            'error' => $error,
        );


        Yii::app()->end(CJSON::encode($res));
    }

    public function actionDeleteRetake()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $elgotr0 = Yii::app()->request->getParam('elgotr0', null);

        $error=false;

        if(empty($elgotr0))
            $error=true;
        if(!$error)
        {
            $elgotr=Elgotr::model()->findByPk($elgotr0);
            $elgzst=Elgzst::model()->findByPk($elgotr->elgotr1);
            $error=!$elgotr->delete();
            if(!$error)
            {
                $last_model=Elgotr::model()->findByAttributes(array('elgotr1'=>$elgzst->elgzst0),array('order'=>'elgotr0 DESC'));
                if($last_model!=null)
                    $value=$last_model->elgotr2;
                else
                    $value=0;
                $elgzst->elgzst5=$value;
                $elgzst->save();
            }

        }
        $res = array(
            'error' => $error,
        );


        Yii::app()->end(CJSON::encode($res));
    }
//--------------------------------------------------------------------------------------
//-------------------- ThematicPlan---------------------------------------------------------------
    public function actionThematicPlan()
    {
        $model = new FilterForm();
        $model->scenario = 'thematicPlan';
        if (isset($_REQUEST['FilterForm'])) {
            $model->attributes=$_REQUEST['FilterForm'];
        }

        $read_only=false;
        $us1=0;
        if (!empty($model->group))
            list($us1,$us6) = explode("/", $model->group);

        $sql = <<<SQL
                SELECT * FROM  EL_GURNAL(:P1,:YEAR,:SEM,0,4,:US1,0,1,0);
SQL;
        $command = Yii::app()->db->createCommand($sql);

        $command->bindValue(':P1', Yii::app()->user->dbModel->p1);
        $command->bindValue(':US1', $us1);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
        $res = $command->queryRow();
        if(empty($res)||$res['dostup']==0)
        {
            //$read_only=true;
        }

        $deleteThematicPlan = Yii::app()->request->getParam('delete-thematic-plan', null);
        if ($deleteThematicPlan&&!$read_only)
            Ustem::model()->deleteThematicPlan($model);
        $acceptThematicPlan = Yii::app()->request->getParam('accept-thematic-plan', null);
        if ($acceptThematicPlan&&!$read_only)
            Ustem::model()->acceptThematicPlan($model);

        $this->render('thematicPlan', array(
            'model' => $model,
            'read_only'=>$read_only
        ));
    }

    public function actionThematicExcel()
    {
        $model = new FilterForm();
        $model->scenario = 'thematicPlan';
        if (isset($_REQUEST['FilterForm'])) {
            $model->attributes=$_REQUEST['FilterForm'];
        }

        if (!empty($model->group)) {

            $disp = D::model()->findByPk($model->discipline);

            list($us1,$us6) = explode("/", $model->group);
            $groups = Us::model()->getGroups($us1);
            $hours = Ustem::model()->getHours($us1,0);

            $potok = Gr::model()->getPotokByThematicExcel($model->discipline,$us1);

            $h4=tt('Часы по рабочему плану - ').' '.$us6.' / '.tt('Часы по тематическому плану - ').' '.$hours;

            $groups_name = '';
            if(!empty($groups)){
                foreach ($groups as $group)
                {
                    if($groups_name!='')
                        $groups_name.=', ';
                    $groups_name.=Gr::model()->getGroupName($group['sem4'], $group);
                }
                $groups_name=tt('Группы').': '.$groups_name;
            }

            Yii::import('ext.phpexcel.XPHPExcel');
            $objPHPExcel= XPHPExcel::createPHPExcel();
            $objPHPExcel->getProperties()->setCreator("ACY")
                ->setLastModifiedBy("ACY ".date('Y-m-d H-i'))
                ->setTitle("ThematicPlan ".date('Y-m-d H-i'))
                ->setSubject("ThematicPlan ".date('Y-m-d H-i'))
                ->setDescription("ThematicPlan document, generated using ACY Portal. ".date('Y-m-d H:i:'))
                ->setKeywords("")
                ->setCategory("Result file");
            $objPHPExcel->setActiveSheetIndex(0);
            $sheet=$objPHPExcel->getActiveSheet();

            $sheet->mergeCells('A1:J1');
            $sheet->setCellValue('A1', tt('Дисциплина').': '.$disp->d2);
            $sheet->mergeCells('A2:J2');
            $sheet->setCellValue('A2', tt('Поток').': '.$potok['name']);
            $sheet->mergeCells('A3:J3');
            $sheet->setCellValue('A3', $h4);
            $sheet->mergeCells('A4:J4');
            $sheet->setCellValue('A4', $groups_name);

            $sheet->setTitle('ThematicPlan '.date('Y-m-d H-i'));

            $themes = Ustem::model()->getTheme($us1);

            $i=7;
            $start=6;

            $sheet->setCellValueByColumnAndRow(0,$start, '№ занятия');
            $sheet->setCellValueByColumnAndRow(1,$start,tt('Кафедра'));
            $sheet->setCellValueByColumnAndRow(2,$start,tt('Длительность занятия'));
            $sheet->setCellValueByColumnAndRow(3,$start,tt('№ темы'));
            $sheet->setCellValueByColumnAndRow(4,$start,tt('Тема'));
            $sheet->setCellValueByColumnAndRow(5,$start,tt('Тип'));
            $sheet->getStyleByColumnAndRow(0,$start,5,$start)->getAlignment()->setWrapText(true)-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->getRowDimension(3)->setRowHeight(-1);
            $sheet->getColumnDimensionByColumn(0)->setWidth(10);
            $sheet->getColumnDimensionByColumn(1)->setWidth(20);
            $sheet->getColumnDimensionByColumn(2)->setWidth(15);
            $sheet->getColumnDimensionByColumn(3)->setWidth(15);
            $sheet->getColumnDimensionByColumn(4)->setWidth(30);
            $sheet->getColumnDimensionByColumn(5)->setWidth(15);

            $start++;

            foreach($themes as $theme)
            {
                $tip = Ustem::model()->getUstem6($theme['ustem6']);
                $ustem7=round($theme['ustem7'],2);

                $sheet->setCellValueByColumnAndRow(0,$i,$theme['ustem4']);
                $sheet->setCellValueByColumnAndRow(1,$i,$theme['k2']);
                $sheet->setCellValueByColumnAndRow(2,$i,$ustem7);
                $sheet->setCellValueByColumnAndRow(3,$i,$theme['ustem3']);
                $sheet->setCellValueByColumnAndRow(4,$i,$theme['ustem5']);
                $sheet->setCellValueByColumnAndRow(5,$i,$tip);
                $sheet->getRowDimension($i)->setRowHeight(-1);
                $sheet->getStyleByColumnAndRow(0, $i, 5, $i)->getAlignment()->setWrapText(true)-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $i++;
            }

            $sheet->getStyleByColumnAndRow(0,$start-1,5,$i-1)->getBorders()->getAllBorders()->applyFromArray(array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')));

            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a clientâ€™s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="ACY_ThematicPlan_'.date('Y-m-d H-i').'.xls"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
            // If you're serving to IE over SSL, then the following may be needed
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');

        }

        Yii::app()->end();
    }

    public function actionRenderUstemTheme()
    {
       //if (! Yii::app()->request->isAjaxRequest)
            //throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $ustem1 = Yii::app()->request->getParam('ustem1', null);
        $d1     = Yii::app()->request->getParam('d1', null);
        $error=false;

        if (empty($ustem1) || empty($d1))
            throw new CHttpException(404, '1Invalid request. Please do not repeat this request again.');

        $model = Ustem::model()->findByAttributes(array('ustem1' => $ustem1));
        $typeError=0;

        if (isset($_REQUEST['Ustem'])) {

            $sql = <<<SQL
                SELECT * FROM  EL_GURNAL(:P1,:YEAR,:SEM,0,4,:US1,0,1,0);
SQL;
            $command = Yii::app()->db->createCommand($sql);

            $command->bindValue(':P1', Yii::app()->user->dbModel->p1);
            $command->bindValue(':US1', $model->ustem2);
            $command->bindValue(':YEAR', Yii::app()->session['year']);
            $command->bindValue(':SEM', Yii::app()->session['sem']);
            $res = $command->queryRow();
            if(empty($res)||$res['dostup']==0)
            {
                //throw new CHttpException(404, '2Invalid request. Please do not repeat this request again.');
                $error=true;
                $typeError=3;
            }
            if(!$error)
            {
                $hours = Ustem::model()->getHours($model->ustem2,$model->ustem1);
                $us = Us::model()->findByPk($model->ustem2);

                $model->attributes = $_REQUEST['Ustem'];
                if((int)$us->us6>=(int)$hours+(int)$model->ustem7)
                {
                    $model->ustem9=Yii::app()->user->dbModel->p1;
                    $model->ustem8=date('Y-m-d H:i:s');
                    try {
                        $error = !$model->save();
                    }catch (Exception $e)
                    {
                        $error = true;
                    }
                    if(!$error)
                        Ustem::model()->noAcceptThematicPlan($model->ustem2);
                }else
                {
                    $error=true;
                    $typeError=1;
                }
            }
        }

        $html = $this->renderPartial('thematicPlan/_theme', array(
            'model' => $model,
        ), true);

        $res = array(
            'html' => $html,
            'errors' => $model->getErrors(),
            'error'=>$error,
            'typeError'=>$typeError
        );

        Yii::app()->end(CJSON::encode($res));
    }

    public function actionPasteUstemTheme()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $us1 = Yii::app()->request->getParam('us1', null);
        $error=false;

        $model = new Ustem;
        $model->ustem2=$us1;
        $model->ustem4=1;
        $typeError=0;

        if (isset($_REQUEST['Ustem'])) {

            $model->attributes = $_REQUEST['Ustem'];
            $sql = <<<SQL
                SELECT * FROM  EL_GURNAL(:P1,:YEAR,:SEM,0,4,:US1,0,1,0);
SQL;
            $command = Yii::app()->db->createCommand($sql);

            $command->bindValue(':P1', Yii::app()->user->dbModel->p1);
            $command->bindValue(':US1', $model->ustem2);
            $command->bindValue(':YEAR', Yii::app()->session['year']);
            $command->bindValue(':SEM', Yii::app()->session['sem']);
            $res = $command->queryRow();
            if(empty($res)||$res['dostup']==0)
            {
                //throw new CHttpException(404, '2Invalid request. Please do not repeat this request again.');
                //$error=true;
                $typeError=3;
            }
            if(!$error)
            {
                $sql=<<<SQL
                    SELECT MAX(USTEM1) FROM USTEM
SQL;
                $command = Yii::app()->db->createCommand($sql);
                $ustem1 = (int)$command->queryScalar();

                $hours = Ustem::model()->getHours($model->ustem2,0);
                $us = Us::model()->findByPk($model->ustem2);
                if((int)$us->us6>=(int)$hours+(int)$model->ustem7)
                {
                    $model->ustem1=$ustem1+1;
                    $model->ustem4=$model->ustem4-1;
                    $model->ustem9=Yii::app()->user->dbModel->p1;
                    $model->ustem8=date('Y-m-d H:i:s');
                    $error=!$model->save();
                    if(!$error)
                        Ustem::model()->noAcceptThematicPlan($model->ustem2);
                }else
                {
                    $error=true;
                    $typeError=1;
                }
            }
        }

        $html = $this->renderPartial('thematicPlan/_theme', array(
            'model' => $model,
        ), true);

        $res = array(
            'html' => $html,
            'errors' => $model->getErrors(),
            'error'=>$error,
            'typeError'=>$typeError
        );

        Yii::app()->end(CJSON::encode($res));
    }

    public function actionCopyUstemTheme()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $us1 = Yii::app()->request->getParam('us1', null);
        $us1_2 = Yii::app()->request->getParam('us1_2', null);

        if (empty($us1)||empty($us1_2))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $error=false;
        $errorType=0;

        $sql = <<<SQL
                SELECT * FROM  EL_GURNAL(:P1,:YEAR,:SEM,0,4,:US1,0,1,0);
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':P1', Yii::app()->user->dbModel->p1);
        $command->bindValue(':US1', $us1);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
        $res = $command->queryRow();
        if(empty($res)||$res['dostup']==0)
        {
            //$error=true;
            $errorType=3;//Нет доступа на запись
        }
        if(!$error)
        {
            $hours = Ustem::model()->getHours($us1,0);
            $hours_2 = Ustem::model()->getHours($us1_2,0);
            $us = Us::model()->findByPk($us1);
            if((int)$us->us6>=(int)$hours+(int)$hours_2)
            {
                $sql=<<<SQL
                SELECT MAX(USTEM1) FROM USTEM
SQL;
                $command = Yii::app()->db->createCommand($sql);
                $ustem1 = (int)$command->queryScalar();

                $ustem_copy= Ustem::model()->findAllByAttributes(array('ustem2'=>$us1_2));
                if(!empty($ustem_copy))
                {
                    $k=1;
                    foreach($ustem_copy as $val)
                    {
                        $model = new Ustem;
                        $model->ustem1=$ustem1+$k;
                        $model->ustem2=$us1;
                        $model->ustem3=$val->ustem3;
                        $model->ustem4=$val->ustem4;
                        $model->ustem5=$val->ustem5;
                        $model->ustem6=$val->ustem6;
                        $model->ustem7=$val->ustem7;
                        $model->ustem9=Yii::app()->user->dbModel->p1;
                        $model->ustem8=date('Y-m-d H:i:s');
                        $model->save();
                        $k++;
                    }
                    Ustem::model()->noAcceptThematicPlan($us1);
                }else
                {
                    $error=true;
                    $errorType=2;//Пустой копируемый план
                }

            }else
            {
                $error=true;
                $errorType=1;//Превышено колчество часов
            }
        }

        $res = array(
            'error'=>$error,
            'errorType'=>$errorType,
        );

        Yii::app()->end(CJSON::encode($res));
    }

    public function actionInsertUstemTheme()
    {
        if (! Yii::app()->request->isAjaxRequest)
          throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $ustem2 = Yii::app()->request->getParam('us1', null);
        $ustem3 = Yii::app()->request->getParam('ustem3', null);
        $ustem4 = Yii::app()->request->getParam('ustem4', null);
        $ustem5 = Yii::app()->request->getParam('ustem5', null);
        $ustem6 = Yii::app()->request->getParam('ustem6', null);
        $ustem7 = Yii::app()->request->getParam('ustem7', null);
        $ustem11 = Yii::app()->request->getParam('ustem11', null);

        if (empty($ustem2))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $sql=<<<SQL
                SELECT MAX(USTEM1) FROM USTEM
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $ustem1 = (int)$command->queryScalar();

        $error=false;
        $errorType=0;

        $sql = <<<SQL
                SELECT * FROM  EL_GURNAL(:P1,:YEAR,:SEM,0,4,:US1,0,1,0);
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':P1', Yii::app()->user->dbModel->p1);
        $command->bindValue(':US1', $ustem2);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
        $res = $command->queryRow();
        if(empty($res)||$res['dostup']==0)
        {
            //$error=true;
            $errorType=3;//Нет доступа на запись
        }
        if(!$error)
        {
            $hours = Ustem::model()->getHours($ustem2,0);
            $us = Us::model()->findByPk($ustem2);
            if((int)$us->us6>=(int)$hours+(int)$ustem7)
            {
                $model = new Ustem;
                $model->ustem1=$ustem1+1;
                $model->ustem2=$ustem2;
                $model->ustem3=$ustem3;
                $model->ustem4=$ustem4;
                $model->ustem5=$ustem5;
                $model->ustem6=$ustem6;
                $model->ustem7=$ustem7;
                $model->ustem9=Yii::app()->user->dbModel->p1;
                $model->ustem8=date('Y-m-d H:i:s');
                $model->ustem11=$ustem11;
                $error=!$model->save();
                $ustem1=$model->ustem1;
                if(!$error)
                    Ustem::model()->noAcceptThematicPlan($model->ustem2);
            }else
            {
                $error=true;
                $errorType=1;
            }
        }
        
        $res = array(
            'error'=>$error,
            'errorType' =>$errorType,
            'ustem1'=>$ustem1
        );

        Yii::app()->end(CJSON::encode($res));
    }

    public function actionDeleteUstemTheme()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $ustem1 = Yii::app()->request->getParam('ustem1', null);

        $ustem=Ustem::model()->findByPk($ustem1);

        if(!empty($ustem))
        {
            $error=false;
            $sql = <<<SQL
                    SELECT * FROM  EL_GURNAL(:P1,:YEAR,:SEM,0,4,:US1,0,1,0);
SQL;
            $command = Yii::app()->db->createCommand($sql);

            $command->bindValue(':P1', Yii::app()->user->dbModel->p1);
            $command->bindValue(':US1', $ustem->ustem2);
            $command->bindValue(':YEAR', Yii::app()->session['year']);
            $command->bindValue(':SEM', Yii::app()->session['sem']);
            $res = $command->queryRow();
            if(empty($res)||$res['dostup']==0)
            {
                //$error=true;
            }

            if(!$error)
            {
                $ustem2=$ustem->ustem2;
                $ustem3=$ustem->ustem3;
                $ustem4=$ustem->ustem4;
                $ustem5=$ustem->ustem5;
                $ustem6=$ustem->ustem6;
                $ustem7=$ustem->ustem7;

                $deleted = (bool)Ustem::model()->deleteByPk($ustem1);

                $res = array(
                    'deleted' => $deleted
                );

                if($deleted)
                {
                    Ustem::model()->noAcceptThematicPlan($ustem2);
                    $pattern='%s Ustem1-%s,Ustem2-%s,Ustem3-%s,Ustem4-%s,Ustem5-%s,Ustem6-%s,,Ustem7-%s';
                    $text = sprintf($pattern,P::model()->getTeacherNameBy(Yii::app()->user->dbModel->p1,false),$ustem1,$ustem2,$ustem3,$ustem4,$ustem5,$ustem6,$ustem7);
                    $sql = <<<SQL
                      INSERT into adz (adz1,adz2,adz3,adz4,adz5,adz6) VALUES (:adz1,:adz2,:adz3,:adz4,:adz5,:adz6);
SQL;
                    $command=Yii::app()->db->createCommand($sql);
                    $command->bindValue(':adz1', 0);
                    $command->bindValue(':adz2', 999);
                    $command->bindValue(':adz3', 1);
                    $command->bindValue(':adz4', date('Y-m-d H:i:s'));
                    $command->bindValue(':adz5', 0);
                    $command->bindValue(':adz6', $text);
                    $command->execute();
                }
            }else
            {
                $res = array(
                    'deleted' => !$error
                );
            }

            Yii::app()->end(CJSON::encode($res));
        }else
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionCopyThemePlanSg()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $sem = Yii::app()->request->getParam('sem_rows', null);
        $year = Yii::app()->request->getParam('year_rows', null);
        $d1 = Yii::app()->request->getParam('d1', null);

        if (empty($year)|| empty($d1))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        if(empty($sem))
            $sem=0;

        $html = $this->renderPartial('thematicPlan/_copy', array(
            'sem' => $sem,
            'year' => $year,
            'discipline'=>$d1
        ), true);

        $res = array(
            'tmp' => $html,
        );

        Yii::app()->end(CJSON::encode($res));
    }
//----------------------------------------------------------------------------------------------
//-------------------- attendanceStatistic---------------------------------------------------------------
    public function actionAttendanceStatistic()
    {
        $model = new FilterForm();
        $model->scenario = 'attendanceStatistic';

        if (isset($_REQUEST['FilterForm']))
            $model->attributes=$_REQUEST['FilterForm'];
        $this->render('attendanceStatistic', array(
            'model' => $model,
            'type_statistic'=>PortalSettings::model()->findByPk(41)->ps2
        ));
    }
//----------------------------------------------------------------------------------------------
    public function actionSearchStudent()
    {
        $model = new St;
        $model->unsetAttributes();
        if (isset($_REQUEST['St']))
            $model->attributes = $_REQUEST['St'];

        $this->render('/filter_form/default/search_student', array(
            'model' => $model,
        ));
    }
}