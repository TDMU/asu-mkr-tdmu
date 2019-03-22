<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 10.02.2016
 * Time: 9:53
 */
echo '<div class="col-md-12 filter-year-sem">';
    $this->renderPartial('/filter_form/mobile/year_sem');
echo '</div>';
echo '<div class="col-md-12 filter-select">';
    $this->renderPartial('/filter_form/mobile/discipline_group_type', array(
    'model' => $model,
    ));
echo '</div>';
