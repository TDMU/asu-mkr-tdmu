<?php

class m181024_115101_insert_ps_141 extends CDbMigration
{
	public function safeUp()
	{
        /**
         * Показывать таб "ресгистрацию пропусков" в карточке студентов
         */
		$sql = <<<SQL
        insert into PORTAL_SETTINGS(PS1, PS2) values(141, '0');
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m181024_115101_insert_ps_138 does not support migration down.\\n";
		return false;
	}
}