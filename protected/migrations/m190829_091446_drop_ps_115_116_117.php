<?php

class m190829_091446_drop_ps_115_116_117 extends CDbMigration
{
	public function up()
	{
        $sql = <<<SQL
            DELETE from PORTAL_SETTINGS WHERE ps1 = 115
SQL;
        $this->execute($sql);
        $sql = <<<SQL
            DELETE from PORTAL_SETTINGS WHERE ps1 = 116
SQL;
        $this->execute($sql);
        $sql = <<<SQL
            DELETE from PORTAL_SETTINGS WHERE ps1 = 117
SQL;
        $this->execute($sql);
	}

	public function down()
	{
		echo "m190829_091446_drop_ps_115_116_117 does not support migration down.\n";
		return false;
	}
}