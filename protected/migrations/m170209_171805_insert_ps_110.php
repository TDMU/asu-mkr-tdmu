<?php

class m170209_171805_insert_ps_110 extends CDbMigration
{
	public function safeUp()
	{
		/*блокировать учетки(безопасность)*/
		$sql = <<<SQL
			insert into PORTAL_SETTINGS(PS1, PS2) values(110, 0);
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m170209_171805_insert_ps_110 does not support migration down.\\n";
		return false;
	}
}