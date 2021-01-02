<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>

<div class="form-group">
    <?php  echo $form->label('content', t("Content")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('content', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo Core::make('editor')->outputBlockEditModeEditor($view->field('content'), $content); ?>
</div>

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
    <?php  echo Core::make("helper/concrete/asset_library")->image('ccm-b-home_highlight-image-' . Core::make('helper/validation/identifier')->getString(18), $view->field('image'), t("Choose Image"), $image_o); ?>
</div>

<div class="form-group">
    <?php  echo $form->label('pagelink', t("Pagelink")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('pagelink', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo Core::make("helper/form/page_selector")->selectPage($view->field('pagelink'), $pagelink); ?>    <?php  echo $form->label('pagelink_text', t("Pagelink") . " " . t("Text")); ?>
    <?php  echo $form->text($view->field('pagelink_text'), $pagelink_text, array()); ?></div>