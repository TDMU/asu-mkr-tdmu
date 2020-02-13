<?php

class m200210_145718_drop_ps107 extends CDbMigration
{
	public function up()
	{
        $sql = <<<SQL
            DELETE from PORTAL_SETTINGS WHERE ps1 = 107
SQL;
        $this->execute($sql);
	}

	public function down()
	{
		echo "m200210_145718_drop_ps107 does not support migration down.\n";
		return false;
	}
}