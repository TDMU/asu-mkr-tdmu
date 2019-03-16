<?php

class m190313_125213_create_um extends CDbMigration
{
	public function safeUp()
	{
	    $sql = <<<SQL
            CREATE TABLE UM (
                UM1   INTE PRIMARY KEY/* INTE = INTEGER DEFAULT 0 NOT NULL */,
                UM2   INTE /* INTE = INTEGER DEFAULT 0 NOT NULL */,
                UM3   DAT_CURRENT_TIMESTAMP /* DAT_CURRENT_TIMESTAMP = DATE default current_timestamp NOT NULL */,
                UM4   SMAL /* SMAL = SMALLINT DEFAULT 0 NOT NULL */,
                UM7   INTE /* INTE = INTEGER DEFAULT 0 NOT NULL */,
                UM8   INTE /* INTE = INTEGER DEFAULT 0 NOT NULL */,
                UM9   INTE /* INTE = INTEGER DEFAULT 0 NOT NULL */,
                UM10  INTEGER,
                UM5   BLOB SUB_TYPE 1 SEGMENT SIZE 4096
            );
SQL;

        $this->execute($sql);
        $sql = 'ALTER TABLE UM ADD CONSTRAINT FK_UM2_U1 FOREIGN KEY (UM2) REFERENCES USERS (U1) ON DELETE CASCADE ON UPDATE CASCADE;';
        $this->execute($sql);
        $sql = 'ALTER TABLE UM ADD CONSTRAINT FK_UM_1 FOREIGN KEY (UM10) REFERENCES UM (UM1) ON DELETE CASCADE ON UPDATE CASCADE;';
        $this->execute($sql);
        $sql = 'ALTER TABLE UM ADD CONSTRAINT FK_UM_2 FOREIGN KEY (UM8) REFERENCES GR (GR1) ON DELETE CASCADE ON UPDATE CASCADE;';
        $this->execute($sql);
        $sql = 'ALTER TABLE UM ADD CONSTRAINT FK_UM_3 FOREIGN KEY (UM9) REFERENCES SG (SG1) ON DELETE CASCADE ON UPDATE CASCADE;';
        $this->execute($sql);
        $sql = 'CREATE SEQUENCE GEN_UM;';
        $this->execute($sql);
	}

	public function safeDown()
	{
		echo "m190313_125213_create_um does not support migration down.\n";
		return false;
	}
}