<?php

class m170210_155247_insert_ps_114 extends CDbMigration
{
	public function safeUp()
	{
		/**
		 * одновременое подключение только одно
		 */
		$sql = <<<SQL
			insert into PORTAL_SETTINGS(PS1, PS2) values(114, 0);
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m170210_155247_insert_ps_114 does not support migration down.\\n";
		return false;
	}
}