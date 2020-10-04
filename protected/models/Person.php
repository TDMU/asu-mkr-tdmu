<?php

/**
 * This is the model class for table "pe".
 *
 * @property int $pe1
 * @property string $pe2
 * @property string $pe3
 * @property string $pe4
 * @property string $pe5
 * @property string $pe6
 * @property string $pe7
 * @property int $pe8
 * @property string $pe9
 * @property string $pe10
 * @property string $pe11
 * @property int $pe12
 * @property string $pe19
 * @property string $pe20
 * @property int $pe21
 * @property string $pe22
 * @property string $pe23
 * @property string $pe24
 * @property string $pe25
 * @property int $pe30
 * @property string $pe31
 * @property int $pe32
 * @property int $pe33
 * @property string $pe34
 * @property string $pe35
 * @property string $pe36
 * @property string $pe37
 * @property int $pe50
 * @property string $pe51
 * @property string $pe52
 * @property string $pe53
 * @property int $pe54
 * @property int $pe59
 * @property string $pe60
 * @property string $pe61
 * @property string $pe62
 * @property string $pe63
 * @property int $pe64
 *
 * @property int $pe38
 * @property int $pe39
 * @property int $pe40
 *
 * @property Student[] $students
 * @property Pefio $fioEng
 * @property UserSystem $userSystem
 *
 * @property Users $studentUser
 * @property Users $parentUser
 *
 * @property Passport $scanPassport
 * @property Passport $scanInternationalPassport
 * @property Passport $scanInn
 * @property Passport $scanSnils
 * @property Passport $scanEducationDocument
 * @property Passport $scanPropiska
 * @property Passport $scanRegistration
 *
 * @property Foto $foto
 */
class Person extends CActiveRecord
{
    /**
     * Мужcкой пол
     */
    const SEX_MALE = 0;
    /**
     * Женский пол
     */
    const SEX_FEMALE = 1;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pe';
	}

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pe1', 'pe8', 'pe12', 'pe21', 'pe30', 'pe32', 'pe33', 'pe38', 'pe39', 'pe50', 'pe54', 'pe59', 'pe64'], 'integer'],
            [['pe9', 'pe25', 'pe31', 'pe51', 'pe52', 'pe60', 'pe61'], 'safe'],
            [['pe2', 'pe4', 'pe5'], 'string', 'max' => 35],
            [['pe3', 'pe34', 'pe35', 'pe36', 'pe37'], 'string', 'max' => 50],
            [['pe6', 'pe7'], 'string', 'max' => 20],
            [['pe10', 'pe11'], 'string', 'max' => 75],
            [['pe20', 'pe63', 'pe19'], 'string', 'max' => 15],
            [['pe22'], 'string', 'max' => 10],
            [['pe23'], 'string', 'max' => 25],
            [['pe24'], 'string', 'max' => 150],
            [['pe53'], 'string', 'max' => 100],
            [['pe62'], 'string', 'max' => 1],
            [['pe1'], 'unique'],
            //[['pe54'], 'exist', 'skipOnError' => true, 'targetClass' => Voen::className(), 'targetAttribute' => ['pe54' => 'voen1']],
            //[['pe30'], 'exist', 'skipOnError' => true, 'targetClass' => Sgr::className(), 'targetAttribute' => ['pe30' => 'sgr1']],
            //[['pe32'], 'exist', 'skipOnError' => true, 'targetClass' => UserSystem::class, 'targetAttribute' => ['pe32' => 'i1']],

            //[['pe38'], 'exist', 'targetClass' => Users::class, 'targetAttribute' => ['pe38' => 'u1']],
            //[['pe39'], 'exist', 'targetClass' => Users::class, 'targetAttribute' => ['pe39' => 'u1']],
        ];
    }

    /**
	 * @return array customized attribute labels (name=>label)
	 */
    public function attributeLabels()
    {
        return [
            'pe19' => tt('СНІЛС'),
            'pe20' => tt('ІНН')
        ];
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
