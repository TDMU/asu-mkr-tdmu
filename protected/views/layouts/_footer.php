<?php

/**
 * @var $this Controller
 */

/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 22.11.2016
 * Time: 20:31
 */?>

<?php
$textFooter = PortalSettings::model()->findByPk(99)->ps2;
if(!empty($textFooter))
    echo '<div class="user-block text-center">'.$textFooter.'</div>';
else {
    echo '©2015 ООО НПП "МКР", ';

    $mkrUrl = in_array($this->universityCode, array(3, 7, 15, 21, 31, 34, 42)) ? 'https://mkr.org.ru' : 'http://mkr.org.ua';

    $ps104 = PortalSettings::model()->findByPk(104)->ps2;
    if ($ps104 == 0) {
        echo "<a target='_ablank' title='{$mkrUrl}' href='{$mkrUrl}'>{$mkrUrl}</a>";
    } else {
        echo $mkrUrl;
    }

    echo ' (v' . ASU_PORTAL_VERSION . ')';
}
