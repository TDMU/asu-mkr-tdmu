<?php

class m170116_112305_create_gen_elgpmkst extends CDbMigration
{
	public function safeUp()
	{
		$sql = <<<SQL
			CREATE SEQUENCE GEN_ELGPMKST
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		//echo "m170116_112305_create_gen_elgpmkst does not support migration down.\\n";
		//return false;

		$sql = <<<SQL
			DROP SEQUENCE GEN_ELGPMKST
SQL;
		$this->execute($sql);
	}
}