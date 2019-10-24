<?php

/**
 * This is the model class for table "foto".
 *
 * The followings are the available columns in table 'foto':
 * @property integer $foto1
 * @property integer $foto2
 * @property string $foto3
 * @property string $foto4
 */
class Foto extends CGraficActiveRecord
{
    const DEFAULT_FOTO = '/../theme/ace/assets/avatars/avatar2.png';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'foto';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('foto1', 'required'),
			array('foto1, foto2', 'numerical', 'integerOnly'=>true),
			array('foto3, foto4', 'length', 'max'=>8),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Foto the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * Рендер фото пользователя
     * @param $id
     * @param $type
     * @throws CHttpException
     */
    public static function renderFoto($id, $type){
        $model = self::model()->findByAttributes(array('foto1'=>$id, 'foto2'=>$type));

        if($model==null)
        {
            self::_renderDefaultFoto();
        }else{
            if(!empty($model->foto3)) {
                $im = imagecreatefromstring($model->foto3);
                if ($im !== false) {
                    header('Content-Type: image/jpeg');
                    imagejpeg($im);
                    imagedestroy($im);
                }else
                    self::_renderDefaultFoto();
            }else {
                self::_renderDefaultFoto();
            }
        }

    }

    /**
     * Рендер фото пользователя
     * @param $id
     * @param $type
     * @throws CHttpException
     */
    public static function renderStudentBarcode($st1){
        $model = self::getStudentFoto($st1);

        if($model==null)
        {
            throw new CHttpException(500, 'Невозможно отобразить');
        }else{
            if(!empty($model->foto4)) {
                $im = imagecreatefromstring($model->foto4);
                if ($im !== false) {
                    header('Content-Type: image/jpeg');
                    imagejpeg($im);
                    imagedestroy($im);
                }else
                    throw new CHttpException(500, 'Невозможно отобразить');
            }else {
                throw new CHttpException(500, 'Невозможно отобразить');
            }
        }

    }

    /**
     * Почуить модельку фото и штрихкода студента
     * @param $st1
     * @return Foto
     */
    public static function getStudentFoto($st1)
    {
        return Foto::model()->findByAttributes(array('foto1'=>$st1, 'foto2'=>1));
    }

    /**
     * Рендер фото по умослчанию
     * @throws CHttpException
     */
    private static function _renderDefaultFoto(){
        $path = Yii::app()->basePath .self::DEFAULT_FOTO;

        if(!file_exists($path)){
            throw new CHttpException(500, 'Невозможно отобразить');
        }

        $im = imagecreatefrompng($path);
        if ($im !== false) {
            header('Content-Type: image/png');
            imagepng($im);
            imagedestroy($im);
        }else {
            throw new CHttpException(500, 'Невозможно отобразить');
        }
    }
}
