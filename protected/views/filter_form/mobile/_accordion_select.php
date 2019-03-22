<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 10.02.2016
 * Time: 9:46
 */
?>

<div class="panel-group" id="accordion-select" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingOne">
            <h2 class="panel-title">
                <a id="title-select" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="collapsed">
                    <span><?=tt('Выбор')?></span><i class="glyphicon glyphicon-chevron-down"></i>
                </a>
            </h2>
        </div><!--panel-heading-->
        <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body filter">
                <?php
                $this->renderPartial($render, $arr);?>
            </div><!--panel-body-->
        </div><!--#collapseOne-->
    </div><!--panel panel-default-->
</div><!--#accordion-select-->
