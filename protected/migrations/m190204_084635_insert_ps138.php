<?php

class m190204_084635_insert_ps138 extends CDbMigration
{
	public function safeUp()
	{
        /**
         * Включить портфолио
         */
        $sql = <<<SQL
            insert into PORTAL_SETTINGS(PS1, PS2) values(138, 0);
SQL;
        $this->execute($sql);
	}

	public function safeDown()
	{
		echo "m190204_084635_insert_ps138 does not support migration down.\\n";
		return false;
	}
}