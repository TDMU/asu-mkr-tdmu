<?php

class m190221_142833_alter_zrst8 extends CDbMigration
{
	public function safeUp()
	{
        /**
         * Названеи файла
         */
        $sql = <<<SQL
          ALTER TABLE ZRST ADD ZRST8 var10
SQL;
        $this->execute($sql);
	}

	public function safeDown()
	{
		echo "m190221_142833_alter_zrst8 does not support migration down.\\n";
		return false;
	}
}