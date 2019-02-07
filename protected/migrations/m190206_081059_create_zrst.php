<?php

class m190206_081059_create_zrst extends CDbMigration
{
	public function safeUp()
	{
		$sql = <<<SQL
            CREATE TABLE ZRST (ZRST1 inte PRIMARY KEY,
              ZRST2 inte, /* код st1 */
              ZRST3 inte, /* us1 */
              ZRST4 smal, /* 0-закрепление за US1    1-закрепление за 1-й таблицей   2-закрепление за 2-й таблицей  */
              ZRST5 smal, /* номер позиции внутри ZRST3 */
              ZRST6 smal,  /* 0-работа 1-рецензия */
              ZRST7 var100);
SQL;
		$this->execute($sql);

        $sql = <<<SQL
            ALTER TABLE zrst ADD constraint FK_zrst2_st1 FOREIGN KEY (zrst2) REFERENCES st (st1) ON DELETE CASCADE ON UPDATE CASCADE;
SQL;
        $this->execute($sql);

        $sql = <<<SQL
            ALTER TABLE zrst ADD constraint FK_zrst3_us1 FOREIGN KEY (zrst3) REFERENCES us (us1) ON DELETE CASCADE ON UPDATE CASCADE;
SQL;
        $this->execute($sql);

        $sql = <<<SQL
            CREATE SEQUENCE GEN_ZRST;
SQL;
        $this->execute($sql);
	}

	public function safeDown()
	{
		echo "m190206_081059_create_zrst does not support migration down.\\n";
		return false;
	}
}