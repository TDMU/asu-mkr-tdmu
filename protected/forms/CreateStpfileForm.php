<?php

/**
 * Class CreateStpfileForm
 */
class CreateStpfileForm extends CFormModel
{
    /**
     */
    const TYPE_FIELD = 'field';
    /**
     */
    const TYPE_OTHERS = 'others';
    /**
     */
    const TYPE_FIELD23 = 'type23';

    const TYPE_FIELD3 = 'type3';

	public $st1;
    /**
     * @var CUploadedFile
     */
    public $file;
    public $id;
    public $type;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('st1, file, type, id', 'required'),
            array('type', 'in', 'range' => array(self::TYPE_FIELD, self::TYPE_OTHERS, self::TYPE_FIELD23, self::TYPE_FIELD3)),
            array('st1, id', 'numerical', 'integerOnly'=>true),
            array('file', 'validateForm'),
            array('file', 'file',
                'types'=> 'jpeg, jpg, png, doc, docx, pdf',
                'maxSize' => 1024 * 1024 * 8,
            )
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
		    'file' => tt('Файл'),
		);
	}

    /**
     * валидация формы
     * @param $attribute
     */
	public function validateForm($attribute){
	    if($this->hasErrors())
	        return;

        if($this->type == self::TYPE_FIELD){
            $field = Stportfolio::model()->findByAttributes(array('stportfolio0' => $this->id, 'stportfolio2'=>$this->st1));
            if(empty($field))
            {
                $this->addError($attribute, tt('Не найден елемент'));
                return;
            }
        }
    }

    /**
     * Сохран
     * @return bool
     * @throws CDbException
     * @throws CException
     */
    public function save(){
        if($this->type == self::TYPE_FIELD){
            $field = Stportfolio::model()->findByAttributes(array('stportfolio0' => $this->id, 'stportfolio2'=>$this->st1));
            if(empty($field))
            {
                return false;
            }

            if(!empty($field->stportfolio7))
                return false;

            $model = new Stpfile();
            $model->stpfile1 = Yii::app()->db->createCommand('select gen_id(GEN_STPFILE, 1) from rdb$database')->queryScalar();

            $trans = $model->getDbConnection()->beginTransaction();
            try {
                $model->stpfile2 = pathinfo($this->file->name, PATHINFO_BASENAME);
                $model->stpfile3 = Yii::app()->user->id;
                $model->stpfile4 = date('Y-m-d H:i:s');
                $model->stpfile5 = $this->st1;
                $model->stpfile6 = Stpfile::TYPE_STPORTFOLIO;
                $model->stpfile7 = Stpfile::generateFilePath($model);

                if ($model->save()) {
                    $fileName = PortalSettings::model()->getSettingFor(PortalSettings::PORTFOLIO_PATH) . '/' . $model->stpfile7;
                    $dir = dirname($fileName);
                    if (!file_exists($dir)) {
                        CFileHelper::createDirectory($dir, 0755, true);
                    }
                    if($this->file->saveAs($fileName) === false)
                        throw new CException('Ошибка сохранения файла');
                }else{
                    throw new CException('Ошибка добавления файла');
                }

                $field->stportfolio6 = $model->stpfile1;
                if (!$field->save()) {
                    throw new CException('Ошибка привязки файла');
                }

                $trans->commit();

                return true;
            }catch (CException $error){
                $trans->rollback();
                throw new CException('Ошибка сохранения файла, '. $error->getMessage());
            }
        }elseif ($this->type == static::TYPE_OTHERS){
            $model = new Stpfile();
            $model->stpfile1 = Yii::app()->db->createCommand('select gen_id(GEN_STPFILE, 1) from rdb$database')->queryScalar();

            $trans = $model->getDbConnection()->beginTransaction();
            try {
                $model->stpfile2 = pathinfo($this->file->name, PATHINFO_BASENAME);
                $model->stpfile3 = Yii::app()->user->id;
                $model->stpfile4 = date('Y-m-d H:i:s');
                $model->stpfile5 = $this->st1;
                $model->stpfile6 = Stpfile::TYPE_OTHERS;
                $model->stpfile7 = Stpfile::generateFilePath($model);

                if ($model->save()) {
                    $fileName = PortalSettings::model()->getSettingFor(PortalSettings::PORTFOLIO_PATH) . '/' . $model->stpfile7;
                    $dir = dirname($fileName);
                    if (!file_exists($dir)) {
                        CFileHelper::createDirectory($dir, 0755, true);
                    }
                    if($this->file->saveAs($fileName) === false)
                        throw new CException('Ошибка сохранения файла');
                }else{
                    throw new CException('Ошибка добавления файла');
                }

                $trans->commit();

                return true;
            }catch (CException $error){
                $trans->rollback();
                throw new CException('Ошибка сохранения файла, '. $error->getMessage());
            }
        } elseif ($this->type == static::TYPE_FIELD23){
            $field = Stppart::model()->findByAttributes(array('stppart1' => $this->id, 'stppart2'=>$this->st1));
            if(empty($field))
            {
                return false;
            }

            if(!empty($field->stppart12))
                return false;

            $model = new Stpfile();
            $model->stpfile1 = Yii::app()->db->createCommand('select gen_id(GEN_STPFILE, 1) from rdb$database')->queryScalar();

            $trans = $model->getDbConnection()->beginTransaction();
            try {
                $model->stpfile2 = pathinfo($this->file->name, PATHINFO_BASENAME);
                $model->stpfile3 = Yii::app()->user->id;
                $model->stpfile4 = date('Y-m-d H:i:s');
                $model->stpfile5 = $this->st1;
                $model->stpfile6 = Stpfile::TYPE_STPPART;
                $model->stpfile7 = Stpfile::generateFilePath($model);

                if ($model->save()) {
                    $fileName = PortalSettings::model()->getSettingFor(PortalSettings::PORTFOLIO_PATH) . '/' . $model->stpfile7;
                    $dir = dirname($fileName);
                    if (!file_exists($dir)) {
                        CFileHelper::createDirectory($dir, 0755, true);
                    }
                    if($this->file->saveAs($fileName) === false)
                        throw new CException('Ошибка сохранения файла');
                }else{
                    throw new CException('Ошибка добавления файла');
                }

                $field->stppart9 = $model->stpfile1;
                if (!$field->save()) {
                    throw new CException('Ошибка привязки файла');
                }

                $trans->commit();

                return true;
            }catch (CException $error){
                $trans->rollback();
                throw new CException('Ошибка сохранения файла, '. $error->getMessage());
            }
        } elseif ($this->type == static::TYPE_FIELD3){
            $field = Stpeduwork::model()->findByAttributes(array('stpeduwork1' => $this->id, 'stpeduwork2'=>$this->st1));
            if(empty($field))
            {
                return false;
            }

            if(!empty($field->stpeduwork8))
                return false;

            $model = new Stpfile();
            $model->stpfile1 = Yii::app()->db->createCommand('select gen_id(GEN_STPFILE, 1) from rdb$database')->queryScalar();

            $trans = $model->getDbConnection()->beginTransaction();
            try {
                $model->stpfile2 = pathinfo($this->file->name, PATHINFO_BASENAME);
                $model->stpfile3 = Yii::app()->user->id;
                $model->stpfile4 = date('Y-m-d H:i:s');
                $model->stpfile5 = $this->st1;
                $model->stpfile6 = Stpfile::TYPE_STPEDUWORK;
                $model->stpfile7 = Stpfile::generateFilePath($model);

                if ($model->save()) {
                    $fileName = PortalSettings::model()->getSettingFor(PortalSettings::PORTFOLIO_PATH) . '/' . $model->stpfile7;
                    $dir = dirname($fileName);
                    if (!file_exists($dir)) {
                        CFileHelper::createDirectory($dir, 0755, true);
                    }
                    if($this->file->saveAs($fileName) === false)
                        throw new CException('Ошибка сохранения файла');
                }else{
                    throw new CException('Ошибка добавления файла');
                }

                $field->stpeduwork10 = $model->stpfile1;
                if (!$field->save()) {
                    throw new CException('Ошибка привязки файла');
                }

                $trans->commit();

                return true;
            }catch (CException $error){
                $trans->rollback();
                throw new CException('Ошибка сохранения файла, '. $error->getMessage());
            }
        }

        return false;
    }
}