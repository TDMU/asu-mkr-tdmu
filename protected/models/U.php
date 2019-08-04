<?php

/**
 * This is the model class for table "u".
 *
 * The followings are the available columns in table 'u':
 * @property integer $u1
 * @property integer $u2
 * @property integer $u8
 * @property integer $u9
 * @property integer $u10
 * @property integer $u15
 * @property string $u16
 * @property integer $u17
 * @property integer $u18
 * @property integer $u19
 * @property string $u20
 * @property double $u21
 * @property double $u22
 * @property double $u23
 * @property string $u25
 * @property integer $u26
 */
class U extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'u';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('u1', 'required'),
			array('u1, u2, u8, u9, u10, u15, u17, u18, u19, u26', 'numerical', 'integerOnly'=>true),
			array('u21, u22, u23', 'numerical'),
			array('u16', 'length', 'max'=>40),
			array('u20', 'length', 'max'=>300),
			array('u25', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('u1, u2, u8, u9, u10, u15, u16, u17, u18, u19, u20, u21, u22, u23, u25, u26', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'u1' => 'U1',
			'u2' => 'U2',
			'u8' => 'U8',
			'u9' => 'U9',
			'u10' => 'U10',
			'u15' => 'U15',
			'u16' => 'U16',
			'u17' => 'U17',
			'u18' => 'U18',
			'u19' => 'U19',
			'u20' => 'U20',
			'u21' => 'U21',
			'u22' => 'U22',
			'u23' => 'U23',
			'u25' => 'U25',
			'u26' => 'U26',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('u1',$this->u1);
		$criteria->compare('u2',$this->u2);
		$criteria->compare('u8',$this->u8);
		$criteria->compare('u9',$this->u9);
		$criteria->compare('u10',$this->u10);
		$criteria->compare('u15',$this->u15);
		$criteria->compare('u16',$this->u16,true);
		$criteria->compare('u17',$this->u17);
		$criteria->compare('u18',$this->u18);
		$criteria->compare('u19',$this->u19);
		$criteria->compare('u20',$this->u20,true);
		$criteria->compare('u21',$this->u21);
		$criteria->compare('u22',$this->u22);
		$criteria->compare('u23',$this->u23);
		$criteria->compare('u25',$this->u25,true);
		$criteria->compare('u26',$this->u26);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return U the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getMinMAxBlocks($sg1)
    {
        $sql = <<<SQL
          SELECT min(u9) as MIN_BLOK, max(u9) as MAX_BLOK
          FROM u
          WHERE u27=0 and u2=:SG1 and u9>0
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':SG1', $sg1);
        $row = $command->queryRow();

        return array($row['min_blok'], $row['max_blok']);
    }

    public function getU9($sg1_kod, $block)
    {
        $sql = <<<SQL
          SELECT first 1 u9 as U9_ROOT
          FROM u
          WHERE u1 IN (
            SELECT u17
            FROM u
            WHERE u2=:SG1_KOD and u9=:BLOCK
         )
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':SG1_KOD', $sg1_kod);
        $command->bindValue(':BLOCK', $block);
        $u9_root = $command->queryScalar();

        return $u9_root;
    }

    public function getU17($sg1_kod, $block)
    {
        $sql = <<<SQL
         SELECT first 1 u17 as U1_ROOT
         FROM u
         WHERE u2=:SG1_KOD and u9=:BLOCK
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':SG1_KOD', $sg1_kod);
        $command->bindValue(':BLOCK', $block);
        $u1_root = $command->queryScalar();

        return $u1_root;
    }

    public function getU1($sg1_kod, $block, $u1_vib)
    {
        $sql = <<<SQL
          SELECT first 1 u1
          FROM u
          WHERE u2=:SG1_KOD and u9=:BLOCK and u17 in ({$u1_vib})
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':SG1_KOD', $sg1_kod);
        $command->bindValue(':BLOCK', $block);
        $u1 = $command->queryScalar();

        return $u1;
    }

    public function getAnalyze($uch_god, $semester, $block, $sg1_kod)
    {
        $subSql = <<<SQL
        select u1 from u where u2={$sg1_kod} and u9={$block}
SQL;

        $sql = <<<SQL
            select first 1 u1 as ANALIZIROVAT
            from u
            inner join uo on (u1 = uo.uo22)
            inner join us on (uo.uo1 = us.us2 and us.us6>0)
            inner join sem on (us.us3 = sem.sem1)
            where (u38<=current_timestamp and u39>=current_timestamp) and (sem3 = {$uch_god}) and (sem5 = {$semester}) and (
                (u1 in ({$subSql})) or
                (u1 in (select u1 from u where u17 in ({$subSql}))) or
                (u1 in (select u1 from u where u17 in (select u1 from u where u17 in ({$subSql})))) or 
                (u1 in (select u1 from u where u17 in (select u1 from u where u17 in (select u1 from u where u17 in ({$subSql}))))) or
                (u1 in (select u1 from u where u17 in (select u1 from u where u17 in (select u1 from u where u17 in (select u1 from u where u17 in ({$subSql}))))))
            )
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $u1 = $command->queryScalar();

        return $u1;
    }

    public function getU1_VIBRAL($st1, $u1_kod, $block, $sg1_kod)
    {
        $subSql = <<<SQL
        select u1 from u where u1={$u1_kod}
SQL;

        $sql = <<<SQL
            SELECT u1 as U1_VIBRAL
            FROM u
            INNER JOIN uo on (u.u1 = uo22)
            INNER JOIN (select ucx1 from ucx where ucx5>1) on (uo19 = ucx1)
            INNER JOIN ucxg on (ucx1 = ucxg1)
            INNER JOIN ucgn on (ucxg2 = ucgn1)
            INNER JOIN ucgns on (ucgn1 = ucgns2)
            INNER JOIN (select ucsn1,ucsn2 from ucsn where ucsn2={$st1} and ucsn6=0) on (ucgns1 = ucsn1)
            WHERE
                ucsn2 is not null and (
                (u1 in ({$subSql}))or
                (u1 in (select u1 from u where u17 in ({$subSql})))or
                (u1 in (select u1 from u where u17 in (select u1 from u where u17 in ({$subSql})))) or 
                (u1 in (select u1 from u where u17 in (select u1 from u where u17 in (select u1 from u where u17 in ({$subSql}))))) or
                (u1 in (select u1 from u where u17 in (select u1 from u where u17 in (select u1 from u where u17 in (select u1 from u where u17 in ({$subSql}))))))
            )
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $u1 = $command->queryScalar();

        return $u1;
    }

    public function getU1_KOD($block, $sg1_kod)
    {
        $sql = <<<SQL
          SELECT u1 as U1_KOD
          FROM u
          WHERE u2=:SG1_KOD and u9=:BLOCK
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':SG1_KOD', $sg1_kod);
        $command->bindValue(':BLOCK', $block);
        $u1 = $command->queryColumn();

        return $u1;
    }

    public function getKOL($block, $sg1_kod)
    {
        $sql = <<<SQL
          SELECT count(*) as KOL
          FROM u
          WHERE u2=:SG1_KOD and u9=:BLOCK
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':SG1_KOD', $sg1_kod);
        $command->bindValue(':BLOCK', $block);
        $u1 = $command->queryScalar();

        return $u1;
    }

    public function get_U1($block, $sg1_kod)
    {
        $sql = <<<SQL
          SELECT U1
          FROM u
          WHERE u2=:SG1_KOD and u9=:BLOCK
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':SG1_KOD', $sg1_kod);
        $command->bindValue(':BLOCK', $block);
        $u1 = $command->queryScalar();

        return $u1;
    }

    public function getCiklList($block, $sg1_kod)
    {
        $sql = <<<SQL
            select c2,u1 as U1_CIKL
            from c
            inner join u on (c.c1 = u.u15)
            where u2=:SG1_KOD and u9=:BLOCK
            order by u10
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':SG1_KOD', $sg1_kod);
        $command->bindValue(':BLOCK', $block);
        $blocks = $command->queryAll();

        $data = array();
        foreach ($blocks as $block) {
            $data[$block['u1_cikl']] = $block['c2'];
        }

        return $data;
    }

    public function getNADO_VIBRAT($u1_vib_disc, $uch_god, $semester)
    {

        $sql = <<<SQL
            select first 1 u8 as NADO_VIBRAT
            from uo
            inner join us on (uo1 = us2  and us4<=12)
            inner join u on (uo22 = u1)
            inner join sem on (us3 = sem1)
            where (uo22 = (:U1_VIB_DISC)) and (sem3 = :UCH_GOD) and (sem5 = :SEMESTER)
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':U1_VIB_DISC', $u1_vib_disc);
        $command->bindValue(':UCH_GOD', $uch_god);
        $command->bindValue(':SEMESTER', $semester);
        $u8 = $command->queryScalar();

        return $u8;
    }

    public function getKOL2($u1_vib_disc, $uch_god, $semester, $st1)
    {
        $sql = <<<SQL
            select count(*)  as KOL from
            (select us2
            from uo
            inner join us on (uo.uo1 = us.us2 and us.us6>0  and us4<=12)
            inner join sem on (us.us3 = sem.sem1)
            inner join (select ucx1 from ucx where ucx5>1) on (uo.uo19 = ucx1)
            inner join ucxg on (ucx1 = ucxg1)
            inner join ucgn on (ucxg2 = ucgn1)
            inner join ucgns on (ucgn1 = ucgns2)
            inner join ucsn on (ucgns1 = ucsn1)
            where (uo22 = :U1_VIB_DISC) and (sem3 = :UCH_GOD1) and (sem5 = :SEMESTER1) and (ucgns5  = :UCH_GOD2) and (ucgns6 = :SEMESTER2) and (ucsn2=:ST1)
            group by us2)
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':U1_VIB_DISC', $u1_vib_disc);
        $command->bindValue(':UCH_GOD1', $uch_god);
        $command->bindValue(':SEMESTER1', $semester);
        $command->bindValue(':UCH_GOD2', $uch_god);
        $command->bindValue(':SEMESTER2', $semester);
        $command->bindValue(':ST1', $st1);
        $kol = $command->queryScalar();

        return $kol;
    }

    public function getDisciplines($u1_vib_disc, $uch_god, $semester, $gr1_kod)
    {
        $sql = <<<SQL
            select d1,d2,ucgn1 as UCGN1_KOD, ucx6/*, c2*/
            from d
            inner join uo on (d.d1 = uo.uo3)
            /*inner join u on (uo.uo22 = u.u1)
            inner join c on (u.u15 = c.c1)*/
            inner join us on (uo.uo1 = us.us2 and us.us6>0  and us4<=12)
            inner join sem on (us.us3 = sem.sem1)
            inner join (select ucx1, ucx6 from ucx where ucx5>1) on (uo.uo19 = ucx1)
            inner join ucxg on (ucx1 = ucxg1)
            inner join ucgn on (ucxg2 = ucgn1)
            inner join ucgns on (ucgn1 = ucgns2)
            where (uo22 = :U1_VIB_DISC) and (sem3 = :UCH_GOD1) and (sem5 = :SEMESTER1) and (ucgns5  = :UCH_GOD2) and (ucgns6 = :SEMESTER2) and ucgn2 = :GR1_KOD
            group by d1,d2,UCGN1, ucx6/*, c2*/
            order by d2 collate UNICODE
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':U1_VIB_DISC', $u1_vib_disc);
        $command->bindValue(':UCH_GOD1', $uch_god);
        $command->bindValue(':SEMESTER1', $semester);
        $command->bindValue(':UCH_GOD2', $uch_god);
        $command->bindValue(':SEMESTER2', $semester);
        $command->bindValue(':GR1_KOD', $gr1_kod);
        $disciplines  = $command->queryAll();
        foreach ($disciplines as $key => $d) {
            $disciplines[$key]['count_st'] = $this->getCountSt($u1_vib_disc, $uch_god, $semester,$d['d1']);
        }
        return $disciplines;
    }

    public function getC2($u1_vib_disc, $uch_god, $semester, $gr1_kod)
    {
        $sql = <<<SQL
            select first 1 c2
            from d
            inner join uo on (d.d1 = uo.uo3)
            inner join u on (uo.uo22 = u.u1)
            inner join c on (u.u15 = c.c1)
            inner join us on (uo.uo1 = us.us2 and us.us6>0  and us4<=12)
            inner join sem on (us.us3 = sem.sem1)
            inner join (select ucx1, ucx6 from ucx where ucx5>1) on (uo.uo19 = ucx1)
            inner join ucxg on (ucx1 = ucxg1)
            inner join ucgn on (ucxg2 = ucgn1)
            inner join ucgns on (ucgn1 = ucgns2)
            where (uo22 = :U1_VIB_DISC) and (sem3 = :UCH_GOD1) and (sem5 = :SEMESTER1) and (ucgns5  = :UCH_GOD2) and (ucgns6 = :SEMESTER2) and ucgn2 = :GR1_KOD
            order by d2 collate UNICODE
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':U1_VIB_DISC', $u1_vib_disc);
        $command->bindValue(':UCH_GOD1', $uch_god);
        $command->bindValue(':SEMESTER1', $semester);
        $command->bindValue(':UCH_GOD2', $uch_god);
        $command->bindValue(':SEMESTER2', $semester);
        $command->bindValue(':GR1_KOD', $gr1_kod);
        $c2 = $command->queryScalar();

        return $c2;
    }

    private function getCountSt($u1_vib_disc, $uch_god, $semester, $d1)
    {
        $sql = <<<SQL
     select count(*)  as KOL from
                (select ucsn2
                from uo
                   inner join us on (uo.uo1 = us.us2 and us.us6>0  and us4<=12)
                   inner join sem on (us.us3 = sem.sem1)
                   inner join (select ucx1 from ucx where ucx5>1) on (uo.uo19 = ucx1)
                   inner join ucxg on (ucx1 = ucxg1)
                   inner join ucgn on (ucxg2 = ucgn1)
                   inner join ucgns on (ucgn1 = ucgns2)
                   inner join ucsn on (ucgns1 = ucsn1)
                where (uo3=:d1) and (uo22 = :U1_VIB_DISC) and (sem3 = '{$uch_god}') and (sem5 = '{$semester}') and (ucgns5  = '{$uch_god}') and (ucgns6 = '{$semester}')
                group by ucsn2)
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':U1_VIB_DISC', $u1_vib_disc);
        $command->bindValue(':d1', $d1);
        $kol  = $command->queryRow();
        if(!empty($kol))
            return $kol['kol'];
        else
            return 0;
    }

    public function getAlreadyChecked($u1_vib_disc, $uch_god, $semester, $st1)
    {
        $sql = <<<SQL
            select uo3
            from uo
            inner join us on (uo.uo1 = us.us2 and us.us6>0  and us4<=12)
            inner join sem on (us.us3 = sem.sem1)
            inner join (select ucx1 from ucx where ucx5>1) on (uo.uo19 = ucx1)
            inner join ucxg on (ucx1 = ucxg.ucxg1)
            inner join ucgn on (ucxg.ucxg2 = ucgn.ucgn1)
            inner join ucgns on (ucgn.ucgn1 = ucgns.ucgns2)
            inner join ucsn on (ucgns.ucgns1 = ucsn.ucsn1)
            where (uo22 = (:U1_VIB_DISC)) and (sem3 = :UCH_GOD1) and (sem5 = :SEMESTER1) and (ucgns5  = :UCH_GOD2) and (ucgns6 = :SEMESTER2) and (ucsn2=:ST1)
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':U1_VIB_DISC', $u1_vib_disc);
        $command->bindValue(':UCH_GOD1', $uch_god);
        $command->bindValue(':SEMESTER1', $semester);
        $command->bindValue(':UCH_GOD2', $uch_god);
        $command->bindValue(':SEMESTER2', $semester);
        $command->bindValue(':ST1', $st1);
        $disciplines  = $command->queryColumn();

        return $disciplines;
    }

    public function doUpdates($ucgn1_kod)
    {
        $enableDistEducation = PortalSettings::model()->getSettingFor(PortalSettings::ENABLE_DIST_EDUCATION);
        $enableInSubscriptionDistEducation = PortalSettings::model()->getSettingFor(PortalSettings::ENABLE_IN_SUBSCRIPTION_DIST_EDUCATION);

        $connector = null;
        if($enableDistEducation==1&&$enableInSubscriptionDistEducation==1)
            $connector = SH::getDistEducationConnector(
                Yii::app()->core->universityCode
            );

        $uch_god  = $_SESSION['uch_god'];
        $semester = $_SESSION['semester'];
        $st1      = $_SESSION['st1'];

        $sql = <<<SQL
            SELECT ucgns1 as UCGNS1_VIB,ucgns5,ucgns6
            FROM ucgns
            WHERE ucgns2=:UCGN1_KOD and ucgns5>=:UCH_GOD
            ORDER BY ucgns5,ucgns6
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':UCGN1_KOD', $ucgn1_kod);
        $command->bindValue(':UCH_GOD', $uch_god);
        $codes  = $command->queryAll();

        $start = false;

        foreach ($codes as $code) {

            if (! $start)
                if ($code['ucgns5'] == $uch_god && $code['ucgns6'] == $semester)
                    $start = true;

            if (! $start)
                continue;

            if($enableDistEducation==1&&$enableInSubscriptionDistEducation==1){
                list($result, $error) = $connector->subscribeToCourse(Yii::app()->user->model, $code['ucgns1_vib']);
                Yii::app()->user->setFlash($result ? 'success' : 'error' , $error);
                if(!$result)
                    throw new CHttpException('400',tt('Ошибка записи {error}', array(
                        '{error}' => $error
                    )));
            }

            $sql = <<<SQL
UPDATE or INSERT INTO ucsn (ucsn1,ucsn2,ucsn4,ucsn5, ucsn6)
VALUES (:UCGNS1_VIB,:ST1,current_timestamp,0, 0) MATCHING(ucsn1,ucsn2)
SQL;
            $params = array(
                ':UCGNS1_VIB' => $code['ucgns1_vib'],
                ':ST1' => $st1
            );

            $command = Yii::app()->db->createCommand($sql);
            $command->bindValues($params);
            $command->execute();

            // записываю количество бюджетников и контрактников
            $sql = <<<SQL
UPDATE ucgns SET
        ucgns3=(SELECT  count( *) as BUDGET
              FROM UCSN
                 INNER JOIN ST ON (UCSN2 = ST1)
                 INNER JOIN SK ON (ST1 = SK2)
              WHERE sk3=0 and sk5 is null and ucsn1=:UCGNS1_VIB1)
        ,ucgns4=(SELECT count( *) as KONTR
              FROM UCSN
                 INNER JOIN ST ON (UCSN2 = ST1)
                 INNER JOIN SK ON (ST1 = SK2)
              WHERE sk3=1 and sk5 is null and ucsn1=:UCGNS1_VIB2)
      WHERE ucgns1=:UCGNS1_VIB3
SQL;
            $params = array(
                ':UCGNS1_VIB1' => $code['ucgns1_vib'],
                ':UCGNS1_VIB2' => $code['ucgns1_vib'],
                ':UCGNS1_VIB3' => $code['ucgns1_vib'],
            );
            $command = Yii::app()->db->createCommand($sql);
            $command->bindValues($params);
            $command->execute();
        }

    }

    public function cancelSubscription()
    {
        $st1          = $_SESSION['st1'];
        $data_nachala = $_SESSION['data_nachala'];

        $sql = <<<SQL
            SELECT ucsn1 as KOD_UCGNS1
            FROM ucsn
            WHERE ucsn2=:ST1 and ucsn4>=:DATA_NACHALA and ucsn5=0
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ST1', $st1);
        $command->bindValue(':DATA_NACHALA', $data_nachala);
        $codes  = $command->queryColumn();

        $sql = <<<SQL
        DELETE FROM ucsn WHERE ucsn2=:ST1 and ucsn4>=:DATA_NACHALA and ucsn5=0
SQL;
        $params = array(
            ':ST1' => $st1,
            ':DATA_NACHALA' => $data_nachala,
        );
        Yii::app()->db->createCommand($sql)->execute($params);

        $enableDistEducation = PortalSettings::model()->getSettingFor(PortalSettings::ENABLE_DIST_EDUCATION);
        $enableInSubscriptionDistEducation = PortalSettings::model()->getSettingFor(PortalSettings::ENABLE_IN_SUBSCRIPTION_DIST_EDUCATION);

        $connector = null;
        if($enableDistEducation==1&&$enableInSubscriptionDistEducation==1)
            $connector = SH::getDistEducationConnector(
                Yii::app()->core->universityCode
            );

        foreach ($codes as $code) {

            $sql = <<<SQL
UPDATE ucgns SET
      ucgns3=(SELECT  count( *) as BUDGET
            FROM UCSN
               INNER JOIN ST ON (UCSN2 = ST1)
               INNER JOIN SK ON (ST1 = SK2)
            WHERE sk3=0 and sk5 is null and ucsn1=:UCGNS1_VIB1)
      ,ucgns4=(SELECT count( *) as KONTR
            FROM UCSN
               INNER JOIN ST ON (UCSN2 = ST1)
               INNER JOIN SK ON (ST1 = SK2)
            WHERE sk3=1 and sk5 is null and ucsn1=:UCGNS1_VIB2)
     WHERE ucgns1=:UCGNS1_VIB3
SQL;

            $params = array(
                ':UCGNS1_VIB1' => $code,
                ':UCGNS1_VIB2' => $code,
                ':UCGNS1_VIB3' => $code,
            );
            Yii::app()->db->createCommand($sql)->execute($params);

            if($enableDistEducation==1&&$enableInSubscriptionDistEducation==1){
                list($result, $error) = $connector->unsubscribeToCourse(Yii::app()->user->model, $code);
                Yii::app()->user->setFlash($result ? 'success' : 'error' , $error);
            }
        }
    }

    public function getSubscribedDisciplines($st1,$data_nachala)
    {
        $sql = <<<SQL
            select d2
            from d
            inner join uo on (d.d1 = uo.uo3)
            inner join ucx on (uo.uo19 = ucx.ucx1)
            inner join ucxg on (ucx.ucx1 = ucxg.ucxg1)
            inner join ucgn on (ucxg.ucxg2 = ucgn.ucgn1)
            inner join ucgns on (ucgn.ucgn1 = ucgns.ucgns2)
            inner join ucsn on (ucgns.ucgns1 = ucsn.ucsn1)
            where ucsn2=:ST1 and ucsn4>=:DATA_NACHALA and ucx5=2
            group by d2
            order by d2 collate UNICODE
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ST1', $st1);
        $command->bindValue(':DATA_NACHALA', $data_nachala);
        $disciplines  = $command->queryColumn();

        return $disciplines;
    }
}
