<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 18.08.2017
 * Time: 9:36
 */

/**
 * масив настроек с пользовательго файла
 * @param string $fileName
 * @return array|mixed
 */
function getSettingsArrayFromFile($fileName){
    $arr=array();
    if(file_exists($fileName))
    {
        $content = file_get_contents($fileName);
        if(!empty($content))
            $arr = unserialize(base64_decode($content));
    }else{
        $file_ = fopen($fileName, 'w+');
        fclose($file_);
    }
    return $arr;
}