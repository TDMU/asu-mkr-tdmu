<?php

class StInfoForm extends CFormModel
{
    public $st34;
	public $st74;
	public $st75;
	public $st76;
	public $st107;
    public $st131;
    public $st132;

	public $speciality;
        
    public $passport;
    public $internationalPassport;

    public $inn;
    public $snils;


	public function rules()
	{
		return array(
			array('st74', 'length', 'max'=>35),
			array('st75, st76,st131,st132', 'length', 'max'=>20),
			array('st107', 'email'),
			array('speciality, st34', 'safe'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'st34'=> tt('Специализация'),
			'st74'=> tt('Фамилия (англ.)'),
			'st75'=> tt('Имя (англ.)'),
			'st76'=> tt('Отчество (англ.)'),
			'st107'=> 'Email',
            'st131'=> tt('ИНН (текст)'),
            'st132'=> tt('СНИЛС (текст)'),
            'passport'=> tt('Паспорт'),
            'inn'=> tt('ИНН'),
            'snils'=> tt('СНИЛС'),
			'internationalPassport'=> tt('Загран. паспорт'),
		);
	}

    public function customSave(TimeTableForm $model)
    {
        if (! $model->student)
            return;
        if($this->st34==null)
            $this->st34=0;
        $res1 = St::model()->updateByPk($model->student, array(
            'st34' => $this->st34,
            'st74' => $this->st74,
            'st75' => $this->st75,
            'st76' => $this->st76,
            'st107' => $this->st107,
            'st131' => $this->st131,
            'st132' => $this->st132,
        ));

        $res2 = Users::model()->updateAll(array(
            'u4' => $this->st107
        ), 'u5 =0 and u6 = '.$model->student);

        return $res1 && $res2;
    }

    public function fillData(TimeTableForm $model)
    {
        if (! $model->student)
            return;

        $st = St::model()->findByPk($model->student);

        $this->st34 = $st->st34;
        $this->st74 = $st->st74;
        $this->st75 = $st->st75;
        $this->st76 = $st->st76;
        $this->st107 = $st->st107;
        $this->st131 = $st->st131;
        $this->st132 = $st->st132;
        $this->speciality = Pnsp::model()->getSpecialityFor($st->st1);
    }
    
    public function getPassport($id,$type)
    {
        $sql = <<<SQL
        SELECT passport4 as foto
        FROM passport
        WHERE passport2 = {$id} AND passport3 = {$type}
SQL;

        $string = Yii::app()->db->connectionString;
        $parts  = explode('=', $string);

        $host     = trim($parts[1].'d');
        $login    = Yii::app()->db->username;
        $password = Yii::app()->db->password;
        $dbh      = ibase_connect($host, $login, $password, 'UTF8');

        $result = ibase_query($dbh, $sql);
        $data   = ibase_fetch_object($result);

        if (empty($data->FOTO)) {
            $defaultImg = imagecreatefrompng(Yii::app()->basePath.'/../theme/ace/assets/avatars/avatar2.png');
            imagepng($defaultImg);
        } else {
            header("Content-type: image/jpeg");
            ibase_blob_echo($data->FOTO);
        }

        ibase_free_result($result);
    }
    
    public function setPassport($id,$type)
    {
        $string = Yii::app()->db->connectionString;
        $parts  = explode('=', $string);

        $host     = trim($parts[1].'d');
        $login    = Yii::app()->db->username;
        $password = Yii::app()->db->password;
        $dbh      = ibase_connect($host, $login, $password, 'UTF8');
        
        $document = CUploadedFile::getInstanceByName('document_psp');
        $tmpName = tempnam(sys_get_temp_dir(), '_');
        $saved = $document->saveAs($tmpName);
        
        $f = fopen($tmpName,"r"); 			
        $blob = ibase_blob_import($f);
        if (! is_string($blob)) {
                // import failed
        } else {
            $sql = <<<SQL
                    SELECT passport4 as foto
                    FROM passport
                    WHERE passport2 = {$id} AND passport3 = {$type}
SQL;
            $result = ibase_query($dbh, $sql);
            $data   = ibase_fetch_object($result);  
            
            if (empty($data))
                    $query = "insert into PASSPORT(PASSPORT1,PASSPORT2,PASSPORT3,PASSPORT4)
                            VALUES (1,".$id.",".$type.",?)";
            else
                    $query = "update PASSPORT set PASSPORT4 = ? 
                                    where PASSPORT1 = 1 and PASSPORT2 = ".$id."
                                    and PASSPORT3 =".$type;

            $prepared = ibase_prepare($query);
            $res=-1;
            if (ibase_execute($prepared, $blob)) 
            {	
                $res=1;
                /*"*<a target='_blank' href='".base_url('')."autenth/showfoto/1/4q242".$this->session->userdata('idStd')."6642d/$pass_type'>
                                <img src='".base_url('')."autenth/resize/1/52831".$this->session->userdata('idStd')."83645/$pass_type' />
                           </a>*";*/
            }
            ibase_free_result($result);
            return $res;
        }
    }
    
    public function deletePassport($id,$type)
    {
        $sql = <<<SQL
        DELETE FROM passport
        WHERE passport2 = {$id} AND passport3 = {$type}
SQL;

        $string = Yii::app()->db->connectionString;
        $parts  = explode('=', $string);

        $host     = trim($parts[1].'d');
        $login    = Yii::app()->db->username;
        $password = Yii::app()->db->password;
        $dbh      = ibase_connect($host, $login, $password, 'UTF8');

        ibase_query($dbh, $sql);
    }

}