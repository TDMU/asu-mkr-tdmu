<?php

class m190221_161253_update_exist_zrst8 extends CDbMigration
{
	public function safeUp()
	{

        $sql = <<<SQL
            UPDATE zrst set zrst8='pdf'
SQL;
        $this->execute($sql);
	}

	public function safeDown()
	{
		echo "m190221_161253_update_exist_zrst8 does not support migration down.\\n";
		return false;
	}
}