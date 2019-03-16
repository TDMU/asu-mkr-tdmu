<?php

class m181024_115047_insert_ps_137 extends CDbMigration
{
	public function safeUp()
	{
        /**
         * Использовать регистрацию пропусков
         */
		$sql = <<<SQL
        insert into PORTAL_SETTINGS(PS1, PS2) values(137, '0');
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m181024_115047_insert_ps_137 does not support migration down.\\n";
		return false;
	}
}