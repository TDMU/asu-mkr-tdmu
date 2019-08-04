<?php

class m190705_103815_delete_ps43 extends CDbMigration
{
	public function safeUp()
	{
        $sql = <<<SQL
            DELETE from PORTAL_SETTINGS WHERE ps1 = 43
SQL;
        $this->execute($sql);
	}

	public function safeDown()
	{
		echo "m190705_103815_delete_ps43 does not support migration down.\\n";
		return false;
	}
}