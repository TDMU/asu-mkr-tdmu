<?php

/**
 * This is the model class for table "passport".
 *
 * The followings are the available columns in table 'passport':
 * @property integer $passport1
 * @property integer $passport2
 * @property integer $passport3
 * @property string $passport4
 */
class Passport extends CGraficActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'passport';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('passport2', 'required'),
			array('passport1, passport2, passport3', 'numerical', 'integerOnly'=>true),
			array('passport4', 'length', 'max'=>8),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Passport the static model class
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
    public static function renderPassport($id, $type){
        $model = Passport::model()->findByAttributes(array(
            'passport2' => $id,
            'passport3' => $type
        ));

        if($model==null)
        {
            throw new CHttpException(500, 'Невозможно отобразить');
        }else{
            if(!empty($model->passport4)) {
                $im = imagecreatefromstring($model->passport4);
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
}
