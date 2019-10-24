<?php

require Yii::app()->getBasePath() . '/../vendor/yiisoft/yii/framework/cli/commands/MessageCommand.php';

class ttMessageCommand extends MessageCommand
{
    protected function extractMessages($fileName,$translator)
    {
        echo "Extracting messages from $fileName...\n";
        $subject=file_get_contents($fileName);
        $messages=array();
        if(!is_array($translator))
            $translator=array($translator);

        foreach ($translator as $currentTranslator)
        {
            $t = '/\b'.$currentTranslator.'\s*\(\s*(\'.*?(?<!\\\\)\'|".*?(?<!\\\\)")\s*[,\)]/s';
            $n=preg_match_all($t,$subject,$matches,PREG_SET_ORDER);

            for($i=0;$i<$n;++$i)
            {
                $category = 'main';
                $message=$matches[$i][1];
                $messages[$category][]=eval("return $message;");  // use eval to eliminate quote escape
            }
        }
        return $messages;
    }

}
