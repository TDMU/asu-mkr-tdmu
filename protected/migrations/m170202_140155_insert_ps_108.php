<?php

class m170202_140155_insert_ps_108 extends CDbMigration
{
	public function safeUp()
	{
		/*Индикация расписания*/
		$sql = <<<SQL
			insert into PORTAL_SETTINGS(PS1, PS2) values(108, 5);
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m170202_140155_insert_ps_108 does not support migration down.\\n";
		return false;
	}
}