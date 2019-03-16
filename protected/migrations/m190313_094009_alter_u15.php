<?php

class m190313_094009_alter_u15 extends CDbMigration
{
	public function up()
	{
        $sql = <<<SQL
          ALTER TABLE USERS ADD U15 DAT
SQL;
        $this->execute($sql);
	}

	public function down()
	{
		echo "m190313_094009_alter_u15 does not support migration down.\n";
		return false;
	}
}