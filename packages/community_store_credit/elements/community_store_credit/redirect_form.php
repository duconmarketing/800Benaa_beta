<?php defined('C5_EXECUTE') or die(_("Access Denied."));
extract($vars);
// Here we're setting up the form we're going to submit to paypal.
// This form will automatically submit itself 
?>
<?php /*?><input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="first_name" value="<?= $customer->getValue("billing_first_name")?>">
<input type="hidden" name="last_name" value="<?= $customer->getValue("billing_last_name")?>">
<input type="hidden" name="address1" value="<?= $customer->getValue("billing_address")->address1?>">
<input type="hidden" name="address2" value="<?= $customer->getValue("billing_address")->address2?>">
<input type="hidden" name="city" value="<?= $customer->getValue("billing_address")->city?>">
<input type="hidden" name="state" value="<?= $customer->getValue("billing_address")->state_province?>">
<input type="hidden" name="zip" value="<?= $customer->getValue("billing_address")->postal_code?>">
<input type="hidden" name="country" value="<?= $customer->getValue("billing_address")->country?>">
<input type="hidden" name="amount" value="<?= $total?>">
<input type="hidden" name="currency_code" value="<?= $currencyCode?>">
<input type="hidden" name="business" value="<?= $paypalEmail?>">
<input type="hidden" name="notify_url" value="<?= $notifyURL?>">
<input type="hidden" name="item_name" value="<?= t('Order from %s', $siteName)?>">
<input type="hidden" name="invoice" value="<?= $orderID?>">
<input type="hidden" name="return" value="<?= $returnURL?>">
<input type="hidden" name="cancel_return" value="<?= $cancelReturn?>"><?php */?>


<input type="hidden" name="requestParameter" value="200702091000001||NI||bwjh1K9BhH9LModpp5GIaMkqp7cgy+P044Zb0n9PyHzEM95CA9WVQXM4o0QDfx0870+vtUM+KP JE+Dge7175+vh5+rNA0pNtoaFby3NZOnQkZehdLeZ7isoNgQsoypqWQsH4vyaiwoeHW6lhhkXoKkWr/uZ+Nb/gJDIluF6Ds4 KBn7Rip2aWmU1YiYhe6VxL7Rs+bqk+7S2317ib8I4HH7GRnLzQowtoTs4kNWj4DNSpfFTAYhCZAXENXnw8OlLn/HeRqAm 5CtDSrSmHibLGLX9jYKYjl90TLCSsGgLgFSOkQR8bbfdx+Asu7B6wDCX59q7+uHinlIHaZwxI3vi46FPEVxKDdvwbrHmQBq b1GCZxb5G5Ul3AAoulnZv/J4zL5z6DthV8FkVKi81MDXpDFCiPX4qjK4Z9LZgcVNZeVU3+HQFNVsQoNQ6xwQyqOlGgdTUU wHvYDjJBdg8P32kXjLKqqBxF6OMHp4TQqqYZLnj3p40brmwW34WUTB1qnW5E ">


