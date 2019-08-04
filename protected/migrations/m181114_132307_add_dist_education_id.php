<?php

class m181114_132307_add_dist_education_id extends CDbMigration
{
	public function safeUp()
	{
		$universityCode = Yii::app()->core->universityCode;

		if($universityCode != U_IRPEN)
		    return true;

        /**
         * @var $connector MoodleDistEducation
         */
        $connector = SH::getDistEducationConnector(
            $universityCode
        );

        $stDistList = Stdist::model()->findAllByAttributes(array('stdist3' => 0));

        echo ' Students count '.count($stDistList) + "\n";

        foreach ($stDistList as $item) {

            echo '--- Email: '.$item->stdist2 + "\n";

            try {
                $id = $connector->getIdByEmail($item->stdist2);

                $item->stdist3 = $id;
                $item->save();

                echo '----- Success: '.$item->stdist3. ' '. $item->stdist2;

            }catch (Exception $error){
                echo '----- Error: '.$item->stdist2;
                return false;
            }
        }
	}

	public function safeDown()
	{
		echo "m181114_132307_add_dist_education_id does not support migration down.\\n";
		return false;
	}
}