<?php

class m170209_180102_insert_ps_112 extends CDbMigration
{
	public function safeUp()
	{
		$sql = <<<SQL
			insert into PORTAL_SETTINGS(PS1, PS2) values(112, 10);
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m170209_180102_insert_ps_112 does not support migration down.\\n";
		return false;
	}
}