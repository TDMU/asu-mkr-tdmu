<?php

class m160922_085109_insert_ps92_ps93_p94 extends CDbMigration
{
	public function safeUp()
	{
		/*Сообщение после авторизации для студентов*/
		$sql = <<<SQL
			insert into PORTAL_SETTINGS(PS1, PS2) values(92, '');
SQL;
		$this->execute($sql);
		/*Сообщение после авторизации для преподавателей*/
		$sql = <<<SQL
			insert into PORTAL_SETTINGS(PS1, PS2) values(93, '');
SQL;
		$this->execute($sql);
		/*Сообщение после авторизации для родителей*/
		$sql = <<<SQL
			insert into PORTAL_SETTINGS(PS1, PS2) values(94, '');
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m160922_085109_insert_ps92_ps93_p94 does not support migration down.\\n";
		return false;
	}
}