<?php defined('C5_EXECUTE') or die(_("Access Denied."));
extract($vars);
// Here we're setting up the form we're going to submit to paypal.
// This form will automatically submit itself 
?>
<?php

?>

<form action="<?= $actionUrl ?>" method="post" name="network_online_payment" id="network_online_payment">
<input type="hidden" name="requestParameter" value='<?= $requestParameter ?>'>



