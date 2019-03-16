<?php

class m190314_125932_insert_ps_for_irpen extends CDbMigration
{
    public function safeUp()
    {
        /**
         */
        $sql = <<<SQL
            insert into PORTAL_SETTINGS(PS1, PS2) values(24999999, 15);
SQL;
        $this->execute($sql);

        /**
         */
        $sql = <<<SQL
            insert into PORTAL_SETTINGS(PS1, PS2) values(24999998, 4);
SQL;
        $this->execute($sql);
    }

    public function safeDown()
    {
        echo "m190220_082732_insert_ps_140 does not support migration down.\\n";
        return false;
    }
}