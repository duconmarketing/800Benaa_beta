<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>

<div class="form-group">
    <?php  echo $form->label("ShowallBrands", t("Show all Brands")); ?>
    <?php  echo (isset($btFieldsRequired) && in_array('ShowallBrands', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null); ?>
    <?php  echo $form->checkbox("ShowallBrands", '1', $ShowallBrands); ?>
</div>