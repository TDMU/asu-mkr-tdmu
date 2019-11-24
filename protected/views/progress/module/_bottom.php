<?php
/**
 * @var ProgressController $this
 * @var ModuleForm $model
 */

if(!empty($model->countModules)) {
    $modules = $model->getModules();

    $this->renderPartial('module/_modules_form', array(
        'model' => $model,
        'modules' => $modules
    ));

    $this->renderPartial('module/_modules', array(
        'model' => $model,
        'modules' => $modules
    ));
}
