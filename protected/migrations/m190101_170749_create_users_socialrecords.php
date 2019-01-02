<?php

class m190101_170749_create_users_socialrecords extends CDbMigration
{
	public function safeUp()
	{
		$sql = <<<SQL
		/*Store users social services (e.g. G Suite Directory) profile extra data*/
        CREATE TABLE USERS_SOCIALRECORDS (
            ID  INTE PRIMARY KEY /*Почта для подвреждения*/,
            USERID  INTE /*USER ID*/,
            USERTYPE SMALLINT DEFAULT NULL /*U5 from users*/,
            PERSONID INTEGER DEFAULT NULL /*U6 from useers*/,
            SERVICE VAR20 /*social service name*/,
            SERVICEID VARCHAR(50) DEFAULT NULL, 
            CREATED DAT,
            UPDATED DAT_CURRENT_TIMESTAMP 
        );
SQL;
		$this->execute($sql);

        $sql = <<<SQL
        ALTER TABLE USERS_SOCIALRECORDS ADD CONSTRAINT FK_USERS_SOCIALRECORDS_1 FOREIGN KEY (USERID) REFERENCES USERS (U1) ON DELETE CASCADE ON UPDATE CASCADE;
SQL;
        $this->execute($sql);
        
        $sql = <<<SQL
        CREATE SEQUENCE GEN_USOCIALRECORDS;
SQL;
        $this->execute($sql);
	}

	public function safeDown()
	{
		echo "m190101_170749_create_users_socialrecords does not support migration down.\\n";
		return false;
	}
}