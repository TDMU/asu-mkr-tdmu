<?php

class m170210_084746_create_auth_key extends CDbMigration
{
	public function safeUp()
	{

		$sql = <<<SQL
        SELECT u1
        FROM users
SQL;
		$users = Yii::app()->db->createCommand($sql)->queryAll();

		foreach ($users as $user) {

			$token = openssl_random_pseudo_bytes(12);
			$key   = bin2hex($token);

			$sql = <<<SQL
            UPDATE users set u12='{$key}' WHERE u1={$user['u1']};
SQL;
			$this->execute($sql);
		}
	}

	public function safeDown()
	{
		echo "m170210_084746_create_auth_key does not support migration down.\\n";
		return false;
	}
}