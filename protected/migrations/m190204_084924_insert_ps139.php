<?php

class m190204_084924_insert_ps139 extends CDbMigration
{
	public function safeUp()
	{
        /**
         * Путь к папке с файлами для портволио
         */
        $sql = <<<SQL
            insert into PORTAL_SETTINGS(PS1, PS2) values(139, '');
SQL;
        $this->execute($sql);
	}

	public function safeDown()
	{
		echo "m190204_084924_insert_ps139 does not support migration down.\\n";
		return false;
	}
}