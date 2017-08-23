<?php

class m170208_134134_create_index_u6 extends CDbMigration
{
	public function safeUp()
	{
		try {
			$sql = <<<SQL
			DROP INDEX ID_USERS_6;
SQL;
			$this->execute($sql);
		}catch (Exception $error){

		}

		$sql = <<<SQL
			CREATE INDEX ID_USERS_6 ON USERS (U6);
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m170208_134134_create_index_u6 does not support migration down.\\n";
		return false;
	}
}