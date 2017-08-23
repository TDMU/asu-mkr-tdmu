<?php

class m170216_102716_insert_ps_116 extends CDbMigration
{
	public function safeUp()
	{
		/**
		 * привязка к ip
		 */
		$sql = <<<SQL
			insert into PORTAL_SETTINGS(PS1, PS2) values(116, 1);
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m170216_102716_insert_ps_116 does not support migration down.\\n";
		return false;
	}
}