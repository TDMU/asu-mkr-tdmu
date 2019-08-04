<?php

class m190705_103749_delete_ps38_ps39 extends CDbMigration
{
	public function safeUp()
	{
		$sql = <<<SQL
            DELETE from PORTAL_SETTINGS WHERE ps1 = 38 or ps1 = 39
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m190705_103749_delete_ps38_ps39 does not support migration down.\\n";
		return false;
	}
}