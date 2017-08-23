<?php

class m170209_175724_insert_ps_111 extends CDbMigration
{
	public function safeUp()
	{
		/*количетво потыток после чего будет блокировка*/
		$sql = <<<SQL
		insert into PORTAL_SETTINGS(PS1, PS2) values(111, 5);
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m170209_175724_insert_ps_111 does not support migration down.\\n";
		return false;
	}
}