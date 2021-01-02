<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>

<div class="form-group">
    <?php 
    if (isset($slideimage) && $slideimage > 0) {
        $slideimage_o = File::getByID($slideimage);
        if (!$slideimage_o || $slideimage_o->isError()) {
            unset($slideimage_o);
        }
    } ?>
    <?php  echo $form->label('slideimage', t("Slide Image")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('slideimage', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo Core::make("helper/concrete/asset_library")->image('ccm-b-home_slider-slideimage-' . Core::make('helper/validation/identifier')->getString(18), $view->field('slideimage'), t("Choose Image"), $slideimage_o); ?>
</div>

<div class="form-group">
    <?php  echo $form->label('pagelink', t("page link")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('pagelink', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo Core::make("helper/form/page_selector")->selectPage($view->field('pagelink'), $pagelink); ?>    <?php  echo $form->label('pagelink_text', t("page link") . " " . t("Text")); ?>
    <?php  echo $form->text($view->field('pagelink_text'), $pagelink_text, array()); ?></div>