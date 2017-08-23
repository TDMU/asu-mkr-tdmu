<?php

class m170209_181028_insert_ps_113 extends CDbMigration
{
	public function safeUp()
	{
		/*количествол минут на поптіки*/
		$sql = <<<SQL
			insert into PORTAL_SETTINGS(PS1, PS2) values(113, 3);
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m170209_181028_insert_ps_113 does not support migration down.\\n";
		return false;
	}
}