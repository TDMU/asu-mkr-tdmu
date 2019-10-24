<?php

/**
 * This is the model class for table "fpdd".
 *
 * The followings are the available columns in table 'fpdd':
 * @property integer $fpdd1
 * @property integer $fpdd2
 * @property string $fpdd3
 * @property string $fpdd4
 */
class Fpdd extends CGraficActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'fpdd';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fpdd1, fpdd2, fpdd3, fpdd4', 'required'),
			array('fpdd1, fpdd2', 'numerical', 'integerOnly'=>true),
			//array('fpdd3', 'length', 'max'=>8),
			array('fpdd4', 'length', 'max'=>200),
		);
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Fpdd the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * Являеться ли пикрепленый файл изображением
     * @return bool
     */
    public function isImage(){
        $ext=$this->getExtension();

        switch($ext){
            case 'doc':
            case 'docx':
            case 'xls':
            case 'xlsx':
            case 'pdf':
                $result = false;
                break;
            default:
                $result = true;
                break;
        }
        return $result;
    }

    /**
     * расширение прикрепленого файла по имени
     * @param $fileName
     * @return mixed
     */
    public function getExtension(){
        $ext = explode(".",$this->fpdd4);
        return strtolower(end($ext));
    }
}
