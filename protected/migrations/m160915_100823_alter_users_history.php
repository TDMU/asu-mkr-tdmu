<?php

class m160915_100823_alter_users_history extends CDbMigration
{
	public function safeUp()
	{
		/*$sql = 'update RDB$RELATION_FIELDS set
			RDB$FIELD_SOURCE = \'VAR200\'
			where (RDB$FIELD_NAME = \'UH4\') and
			(RDB$RELATION_NAME = \'USERS_HISTORY\')';*/

		$sql = 'ALTER TABLE USERS_HISTORY ALTER COLUMN UH4 TYPE VAR200';
		$this->execute($sql);

		/*$sql = 'update RDB$RELATION_FIELDS set
			RDB$FIELD_SOURCE = \'VAR200\'
			where (RDB$FIELD_NAME = \'UH6\') and
			(RDB$RELATION_NAME = \'USERS_HISTORY\')';*/
        $sql = 'ALTER TABLE USERS_HISTORY ALTER COLUMN UH6 TYPE VAR200';
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m160915_100823_alter_users_history does not support migration down.\\n";
		return false;
	}
}