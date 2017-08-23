<?php

class m170313_150750_create_cwb extends CDbMigration
{
	public function safeUp()
	{
		$sql = <<<SQL
CREATE TABLE CWB (
    CWB1  INTE NOT NULL /*Код sg1*/
);
SQL;
		$this->execute($sql);

        $sql = <<<SQL
ALTER TABLE CWB ADD CONSTRAINT PK_CWB PRIMARY KEY (CWB1);
SQL;
        $this->execute($sql);

        $sql = <<<SQL
ALTER TABLE CWB ADD CONSTRAINT FK_CWB_1 FOREIGN KEY (CWB1) REFERENCES SG (SG1) ON DELETE CASCADE ON UPDATE CASCADE;
SQL;
        $this->execute($sql);
	}

	public function safeDown()
	{
		echo "m170313_150750_create_cwb does not support migration down.\\n";
		return false;
	}
}