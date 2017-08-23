<?php

class m170124_181559_insert_ps_107 extends CDbMigration
{
	public function safeUp()
	{
		/*карточка студента по стусу или стусв (временно)*/
		$sql = <<<SQL
		insert into PORTAL_SETTINGS(PS1, PS2) values(107, 0);
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m170124_181559_insert_ps_107 does not support migration down.\\n";
		return false;
	}
}