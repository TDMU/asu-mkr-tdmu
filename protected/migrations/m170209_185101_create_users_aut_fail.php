<?php

class m170209_185101_create_users_aut_fail extends CDbMigration
{
	public function safeUp()
	{
		$sql = <<<SQL
			CREATE TABLE USERS_AUTH_FAIL (
				USER_ID    INTE,
				DATE_FAIL  DAT_CURRENT_TIMESTAMP
			);
SQL;
		$this->execute($sql);

		$sql = <<<SQL
			ALTER TABLE USERS_AUTH_FAIL ADD CONSTRAINT PK_USERS_AUTH_FAIL PRIMARY KEY (USER_ID,DATE_FAIL)
SQL;
		$this->execute($sql);

		$sl = <<<SQL
			ALTER TABLE USERS_AUTH_FAIL ADD CONSTRAINT FK_USERS_AUTH_FAIL_1 FOREIGN KEY (USER_ID) REFERENCES USERS(U1)
			ON DELETE CASCADE ON UPDATE CASCADE
SQL;

	}

	public function safeDown()
	{
		echo "m170209_185101_create_users_aut_fail does not support migration down.\\n";
		return false;
	}
}