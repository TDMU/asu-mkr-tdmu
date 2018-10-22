<?php

class m171107_135850_insert_ps_124 extends CDbMigration
{
	public function safeUp()
	{
        /**
         * ApiKey для дистанционного образования Запорожье
         */
        $sql = <<<SQL
            insert into PORTAL_SETTINGS(PS1, PS2) values(124, '');
SQL;
        $this->execute($sql);
	}

	public function safeDown()
	{
		echo "m171107_135850_insert_ps_124 does not support migration down.\\n";
		return false;
	}
}