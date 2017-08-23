<?php

class m170210_084523_alter_u12 extends CDbMigration
{
	public function safeUp()
	{
		/**
		 * $auth-key
		 */
		$sql = <<<SQL
			ALTER TABLE USERS ADD U12 VAR30
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m170210_084523_alter_u12 does not support migration down.\\n";
		return false;
	}
}