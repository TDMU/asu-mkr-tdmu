<?php

class m181116_130727_alter_grants9 extends CDbMigration
{
	public function safeUp()
	{
        /**
         * Доступ к опроснику (0 - нет, 1 -да)
         */
		$sql = <<<SQL
        ALTER TABLE GRANTS ADD GRANTS9 SMAL
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m181116_130727_alter_grants9 does not support migration down.\\n";
		return false;
	}
}