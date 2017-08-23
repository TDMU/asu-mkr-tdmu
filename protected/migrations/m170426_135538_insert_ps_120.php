<?php

class m170426_135538_insert_ps_120 extends CDbMigration
{
	public function safeUp()
	{
        /**
         * Проставлять по умолчанию пропуски или присутсвие (для настрйоки проставлять не проставлениые занятия)лк
         * 0-присусивме, 1 - отсутсвие
         */
        $sql = <<<SQL
			SELECT ps2 FROM portal_settings where ps1=119
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $value = $command->queryScalar();

        $sql = <<<SQL
            insert into PORTAL_SETTINGS(PS1, PS2) values(120, '$value');
SQL;
        $this->execute($sql);
	}

	public function safeDown()
	{
		echo "m170426_135538_insert_ps_120 does not support migration down.\\n";
		return false;
	}
}