<?php

class m170216_102721_insert_ps_117 extends CDbMigration
{
	public function safeUp()
	{
		/**
		 * привязка к user agent
		 */
		$sql = <<<SQL
			insert into PORTAL_SETTINGS(PS1, PS2) values(117, 1);
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m170216_102721_insert_ps_117 does not support migration down.\\n";
		return false;
	}
}