<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 22.11.2016
 * Time: 20:31
 */?>

<?php
$textFooter = (isset(PortalSettings::model()->findByPk(99)->ps2)?PortalSettings::model()->findByPk(99)->ps2:'');
if(!empty($textFooter))
    echo '<div class="user-block">',$textFooter,'</div>';
?>
©2015 ООО НПП "МКР",
<?php
	$ps104 = (isset(PortalSettings::model()->findByPk(104)->ps2)?PortalSettings::model()->findByPk(104)->ps2:0);
    if($ps104==0) {
        echo '<a target="_ablank" title="www.mkr.org.ua" href="http://mkr.org.ua/">www.mkr.org.ua</a>';
    }else{
        echo 'www.mkr.org.ua';
    }
