<?php

class m160505_071622_alter_pm_11_12_13_14 extends CDbMigration
{
	public function safeUp()
	{
		$sql = <<<SQL
			alter table pm
					add pm12 smal,/*0 - доступно для всех, 1-доступно только для авторизированных пользователей */
					add pm13 smal,/*1 - запрет для показа студентам */
					add pm14 smal,/*1 - запрет для показа преподавателям */
					add pm15 smal;/*1 - запрет для показа родителям */
SQL;
		$this->execute($sql);
	}

	public function safeDown()
	{
		echo "m160505_071622_alter_pm_11_12_13_14 does not support migration down.\\n";
		return false;
	}
}