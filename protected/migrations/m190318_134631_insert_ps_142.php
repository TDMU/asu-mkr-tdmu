<?php

class m190318_134631_insert_ps_142 extends CDbMigration
{
	public function safeUp()
	{
	    /*
	     * Показывать в карточке студента таб гос.экзамены
	     */
        $sql = <<<SQL
            insert into PORTAL_SETTINGS(PS1, PS2) values(142, 0);
SQL;
        $this->execute($sql);
	}

	public function safeDown()
	{
		echo "m190318_134631_insert_ps_142 does not support migration down.\n";
		return false;
	}
}