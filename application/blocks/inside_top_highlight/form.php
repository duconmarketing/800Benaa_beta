<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>

<div class="form-group">
    <?php 
    if (isset($image) && $image > 0) {
        $image_o = File::getByID($image);
        if (!$image_o || $image_o->isError()) {
            unset($image_o);
        }
    } ?>
    <?php  echo $form->label('image', t("Image")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('image', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo Core::make("helper/concrete/asset_library")->image('ccm-b-inside_top_highlight-image-' . Core::make('helper/validation/identifier')->getString(18), $view->field('image'), t("Choose Image"), $image_o); ?>
</div>

<div class="form-group">
    <?php  echo $form->label('Content', t("Content")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('Content', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo Core::make('editor')->outputBlockEditModeEditor($view->field('Content'), $Content); ?>
</div>