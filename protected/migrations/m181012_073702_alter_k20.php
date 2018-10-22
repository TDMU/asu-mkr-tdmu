<?php

class m181012_073702_alter_k20 extends CDbMigration
{
	public function safeUp()
	{
	    /* 0-кафедру отображать на портале 1-не отображать */
		$sql = <<<SQL
        ALTER TABLE K ADD K20 SMAL
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m181012_073702_alter_k20 does not support migration down.\\n";
		return false;
	}
}