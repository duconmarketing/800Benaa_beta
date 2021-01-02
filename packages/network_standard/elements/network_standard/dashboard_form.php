<?php 
defined('C5_EXECUTE') or die(_("Access Denied."));
extract($vars);
?>
<div class="form-group">
    <label><?= t('Test Mode')?></label>
    <?= $form->select('networkStandardMode',array(false=>'Live',true=>'Test Mode'),$networkStandardMode); ?>
</div>
<div class="form-group">
    <label><?= t("Network Merchant ID")?></label>
    <input type="text" name="networkMerchantID" value="<?= $networkMerchantID?>" class="form-control">
</div>
<div class="form-group">
    <label><?= t("Network Merchant Key")?></label>
    <input type="text" name="networkMerchantKey" value="<?= $networkMerchantKey?>" class="form-control">
</div>