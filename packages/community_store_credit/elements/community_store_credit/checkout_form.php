<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
extract($vars);
?>

<div class="form-group">
    <label for="credit_period"><?= t('Select Period') ?></label>
<?php //echo $form->select('paypalTestMode', array(30 => '30 days', 60 => '60 days', 120 => '120 Days'), $paypalTestMode); ?>
    <select name="credit_period" id="credit_period" class="form-control md-6">
        <option value="30 Days">30 Days</option>
        <option value="60 Days">60 Days</option>
        <!--<option value="90 Days">90 Days</option>-->
        <!--<option value="120 Days">120 Days</option>-->
    </select>
</div>
