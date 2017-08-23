<?php

class m170216_092755_insert_ps_115 extends CDbMigration
{
	public function safeUp()
	{
		/**
		 * защита сессий
		 */
		$sql = <<<SQL
			insert into PORTAL_SETTINGS(PS1, PS2) values(115, 1);
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m170216_092755_insert_ps_115 does not support migration down.\\n";
		return false;
	}
}