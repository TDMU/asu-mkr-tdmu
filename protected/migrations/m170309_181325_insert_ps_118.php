<?php

class m170309_181325_insert_ps_118 extends CDbMigration
{
	public function safeUp()
	{
        /**
         * шаблон писма для отправки отчета об антиплагиате
         */
		$sql = <<<SQL
            insert into PORTAL_SETTINGS(PS1, PS2) values(118, '');
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m170309_181325_insert_ps_118 does not support migration down.\\n";
		return false;
	}
}