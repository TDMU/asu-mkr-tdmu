<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 19.02.2019
 * Time: 16:49
 */

/**
 * @var $this AlertController
 * @var $text string
 * @var $name string
 * @var $url string
 * @var $date string
 * @var $extra string
 */

$pattern = <<<HTML
            <div class="media">
              <a class="pull-left" href="#">
                <img class="media-object" src="%s">
              </a>
              <div class="media-body">
                <h4 class="media-heading">%s</h4>
                <div class="well well-small">%s</div> 
                %s
              </div>
            </div>
HTML;


echo sprintf($pattern,
    $url,
    tt('{username} <small>{date}</small>', array(
        '{username}' => $name,
        '{date}' => date('d.m.Y',strtotime($date))
    )),
    CHtml::encode($text),
    $extra);