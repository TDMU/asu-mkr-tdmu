<?php

class m170207_152839_insert_ps109 extends CDbMigration
{
	public function safeUp()
	{
		/*новый таб бля фарма в карточке студентов(статистика по студенту)*/
		$sql = <<<SQL
			insert into PORTAL_SETTINGS(PS1, PS2) values(109, 0);
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m170207_152839_insert_ps109 does not support migration down.\\n";
		return false;
	}
}