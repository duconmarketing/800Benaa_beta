<?php
defined('C5_EXECUTE') or die("Access Denied.");


$dh = Core::make('helper/date');

$subject = t("New Order Notification #%s", $order->getOrderID());
/**
 * HTML BODY START
 */
ob_start();
?>
    <!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
    <html>
    <head></head>
    <body>
    <b>Dear Mr. Maged,</b><br /><br /><br />
    Please find the details of the Online Order, Kindly confirm the product availability for the same.<br /><br /><br />
    Regards,<br />
    Ducon Team
    </body>
</html>
<?php
$bodyHTML = ob_get_clean();
?>