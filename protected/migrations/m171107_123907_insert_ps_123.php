<?php

class m171107_123907_insert_ps_123 extends CDbMigration
{
	public function safeUp()
	{
        /**
         * Хост для дистанционного образования
         */
        $sql = <<<SQL
            insert into PORTAL_SETTINGS(PS1, PS2) values(123, '');
SQL;
        $this->execute($sql);
	}

	public function safeDown()
	{
		echo "m171107_123907_insert_ps_123 does not support migration down.\\n";
		return false;
	}
}