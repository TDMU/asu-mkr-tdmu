<?php

class m170209_184225_alter_u11 extends CDbMigration
{
	public function safeUp()
	{
		$sql = <<<SQL
			ALTER TABLE USERS ADD U11 DAT
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m170209_184225_alter_u11 does not support migration down.\\n";
		return false;
	}
}