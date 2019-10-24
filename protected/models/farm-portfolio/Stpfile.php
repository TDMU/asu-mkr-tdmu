<?php

/**
 * This is the model class for table "stpfile".
 *
 * The followings are the available columns in table 'stpfile':
 * @property integer $stpfile1
 * @property string $stpfile2
 * @property integer $stpfile3
 * @property string $stpfile4
 * @property integer $stpfile5
 * @property integer $stpfile6
 * @property string $stpfile7
 *
 * The followings are the available model relations:
 * @property Users $stpfile30
 * @property St $stpfile50
 * @property Stportfolio[] $stportfolios
 *
 * @property Stppart $stppart
 * @property Stpeduwork $stpeduwork
 */
class Stpfile extends CActiveRecord
{
    const TYPE_STPORTFOLIO = 0;

    const TYPE_OTHERS = -1;

    const TYPE_STPPART = 1;

    const TYPE_STPEDUWORK = 2;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stpfile';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('stpfile1, stpfile3, stpfile5, stpfile6', 'numerical', 'integerOnly'=>true),
			array('stpfile2, stpfile7', 'length', 'max'=>180),
			array('stpfile4', 'length', 'max'=>20),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'stpfile30' => array(self::BELONGS_TO, 'Users', 'stpfile3'),
			'stpfile50' => array(self::BELONGS_TO, 'St', 'stpfile5'),
			'stportfolios' => array(self::HAS_MANY, 'Stportfolio', 'stportfolio6'),
            'stppart' => array(self::HAS_ONE, 'Stppart', 'stppart9'),
            'stpeduwork' => array(self::HAS_ONE, 'Stpeduwork', 'stpeduwork10'),
		);
	}


    public function attributeLabels()
    {
        return array(
            'stpfile2' => tt('Имя файла')
        );
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stpfile the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    /**
     * Путь к файлу
     * @param $model static
     * @return string
     */
    public static function generateFilePath($model){
        return date('Y').'/'.date('m').'/'.date('d').'/'.$model->stpfile5.'/'.$model->stpfile1.'.'.$model->stpfile2;
    }

    /**
     * @param $name
     */
    public function setFilePath($filePath){
        $this->stpfile7 = $filePath;
        $this->stpfile2 = pathinfo($filePath, PATHINFO_BASENAME);
    }
}
