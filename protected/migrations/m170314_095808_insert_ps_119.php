<?php

class m170314_095808_insert_ps_119 extends CDbMigration
{
	public function safeUp()
	{
        /**
         * Проставлять по умолчанию пропуски или присутсвие (для настрйоки проставлять не проставлениые занятия) пз
         * 0-присусивме, 1 - отсутсвие
         */
        $sql = <<<SQL
            insert into PORTAL_SETTINGS(PS1, PS2) values(119, 0);
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m170314_095808_insert_ps_119 does not support migration down.\\n";
		return false;
	}
}