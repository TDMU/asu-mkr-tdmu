<?php
/**
 *
 * @var DocController $this
 * @var Tddo $model
 */

/**
 * @var $attachedFiles Fpdd[]
 */
$attachedFiles = $model->getFiles();
?>

    <ul class="thumbnails">

        <?php
        $html = '';
        $pattern = <<<HTML
            <li class="span3">
                <a href="%s" target="_blank">
                    <div class="thumbnail">
                            %s
                        <h3>%s</h3>
                    </div>
                </a>
            </li>
HTML;

        foreach ($attachedFiles as $file) {
            $link = Yii::app()->createUrl('/doc/file/',array('id'=>$file->fpdd1));

            if($file->isImage()) {
                $file_ = '<img src="%s" alt="%s">';
                $file_ = sprintf($file_, $link, $file->fpdd4);
            }else{
                $file_ ='';
            }

            $html .= sprintf($pattern,$link, $file_, $file->fpdd4);
        }
        echo $html;
        ?>
    </ul>
<?php


