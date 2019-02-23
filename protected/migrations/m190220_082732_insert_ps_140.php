<?php

class m190220_082732_insert_ps_140 extends CDbMigration
{
	public function safeUp()
	{
        /**
         * Оповещение пиьсма студентами
         */
        $sql = <<<SQL
            insert into PORTAL_SETTINGS(PS1, PS2) values(140, 0);
SQL;
        $this->execute($sql);
	}

	public function safeDown()
	{
		echo "m190220_082732_insert_ps_140 does not support migration down.\\n";
		return false;
	}
}