<?php
defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Price as StorePrice;
use Concrete\Package\CommunityStore\Src\Attribute\Key\StoreOrderKey as StoreOrderKey;
use Concrete\Package\CommunityStore\Src\CommunityStore\Customer\Customer as StoreCustomer;

$dh = Core::make('helper/date');

$subject = t("New Order Notification #%s", $order->getOrderID());
/**
 * HTML BODY START
 */
ob_start();
?>
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
<html>
    <head>
    </head>
    <body style="width: 75%;alignment: center;">
        <?php $header = trim(\Config::get('community_store.receiptHeader')); ?>

        <?php
        if ($header) {
            echo $header;
        } else {
            ?>
        <h2><?= t('An order has been placed') ?></h2>
        <?php } ?>
        <h2 style="text-align: center;">Order Number : <?= $order->getOrderID(); ?></h2>
        <table border="0" width="100%" style="border-collapse: collapse;">
            <tr>
                <td width="50%" style="vertical-align: top; padding: 0; padding-right: 10px;padding-left: 10px;">
                    <p style="margin: 3px 0px;font-weight: bold;font-size: 18px;">Ducon Industries Fzco</p>
                    <p style="line-height: 18px;margin: 3px 0px;">(TRN: 100386329500003)</p>  
                    <!--<p style="line-height: 18px;margin: 3px 0px;">National Industries Park,<br/>TP010225,P.O Box 262394,<br/>Dubai,United Arab Emirates</p>-->
                    <p style="margin: 3px 0px;"><strong>Phone: </strong>+971 4 8806996</p>
                    <p style="margin: 3px 0px;"><strong>Fax: </strong>+971 4 8806980</p>
                    <!--<p style="margin: 3px 0px;"><strong>Email: </strong>sales@duconind.com</p>-->
                    <!--<p style="margin: 3px 0px;"><strong>Web: </strong>http://www.duconind.com/</p>-->
                </td>
                <td style="vertical-align: top; padding: 0;">
                    <p data-redactor-inserted-image="true" style="text-align: right;"><img id="image-marker" src="https://www.800benaa.com/download_file/view_inline/350" /></p>
                </td>
            </tr>
            <tr>
                <td width="50%" style="vertical-align: top; padding: 0; padding-right: 10px;padding-left: 10px;">
                    <h3 style="text-decoration:underline;"><?= t('Billing Information') ?></h3>
                    <p style="font-weight:bold;">
                        <?= $order->getAttribute("billing_first_name") . " " . $order->getAttribute("billing_last_name") ?><br>
                        <?php $address = StoreCustomer::formatAddress($order->getAttribute("billing_address")); ?>
                        <?= nl2br($address); ?>
                        <br>
                        <strong><?= t('Phone') ?></strong>: <?= $order->getAttribute("billing_phone") ?><br>
                        <strong><?= t('Email') ?></strong>: <?= $order->getAttribute("email") ?><br>
                        <?php
                        $vat_number = $order->getAttribute("vat_number");
                        if ($vat_number) {
                            ?>
                            <strong><?= t('VAT Number') ?></strong>: <?= $vat_number ?><br>
                        <?php } ?>
                    </p>
                </td>
                <td style="vertical-align: top; padding: 0;padding: 10px;text-align: right;">
                    <p style="margin: 3px 0px;"><strong><?= t("Order") ?>#: <?= $order->getOrderID() ?> | <?= t('Order placed date'); ?>: <?= $dh->formatDateTime($order->getOrderDate()) ?></strong></p>                   

                    <?php if (!empty($orderChoicesAttList)) { ?>
                        <h3><?= t("Other Choices") ?></h3>
                        <?php
                        foreach ($orderChoicesAttList as $ak) {
                            $orderOtherAtt = $order->getAttributeValueObject(StoreOrderKey::getByHandle($ak->getAttributeKeyHandle()));
                            if ($orderOtherAtt) {
                                ?>
                                <p style="margin:3px 0px;"><strong><?= $ak->getAttributeKeyDisplayName() ?></strong>
                                    <?php
                                    $orderotherchoiice = $orderOtherAtt->getValue('displaySanitized', 'display');
                                    if ($orderotherchoiice != '') {
                                        ?>
                                            <?= ': ' . str_replace("\r\n", " ", $orderOtherAtt->getValue('displaySanitized', 'display')); ?></p>
                                        <?php
                                    } else {
                                        echo ': N/A</p>';
                                    }
                                }
                                ?>
                            <?php } ?>
                        <?php } ?>
                </td>
            </tr>
            <tr>
                <td width="50%" style="vertical-align: top; padding: 0; padding-right: 10px;padding-left: 10px;">
                    <?php if ($order->isShippable()) { ?>
                        <h3><?= t('Shipping Information') ?></h3>
                        <p>
                            <?= $order->getAttribute("shipping_first_name") . " " . $order->getAttribute("shipping_last_name") ?>
                            <br>
                            <?php $shippingaddress = $order->getAttribute("shipping_address"); ?>
                            <?php
                            if ($shippingaddress) {
                                $shippingaddress = StoreCustomer::formatAddress($shippingaddress);
                                echo nl2br($shippingaddress);
                            }
                            ?>
                        </p>
                    <?php } ?>
                </td>
            </tr>
        </table>
        <h3><?= t('Order Details') ?></h3>
        <table border="1" cellpadding="0" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th style="border-bottom: 1px solid #aaa; text-align: left; padding-right: 10px;padding-left:10px; "><?= t('Product Name') ?></th>
                    <th style="border-bottom: 1px solid #aaa; text-align: center; padding-right: 10px;"><?= t('Product Code') ?></th>
                    <th style="border-bottom: 1px solid #aaa; text-align: center; padding-right: 10px;"><?= t('Qty') ?></th>
                    <th style="border-bottom: 1px solid #aaa; text-align: right; padding-right: 10px;"><?= t('Price') ?></th>               
                    <th style="border-bottom: 1px solid #aaa; text-align: right; padding-right: 10px;"><?= t('VAT %') ?></th>
                    <th style="border-bottom: 1px solid #aaa; text-align: right;padding-right: 5px;"><?= t('Subtotal') ?></th>  
                </tr>
            </thead>
            <tbody>
                <?php
                $items = $order->getOrderItems();
                if ($items) {
                    foreach ($items as $item) {
                        $pObj = $item->getProductObject();
                        ?>
                        <tr>
                            <td style="vertical-align: top; padding: 5px 10px 5px 10px;font-weight: bold;"><?= $item->getProductName() ?>
                                <?php
                                if ($sku = $item->getSKU()) {
                                    echo '(' . $sku . ')';
                                }
                                ?>
                            </td>
                            <!--<td style="vertical-align: top; padding: 5px 10px 5px 0;">-->
                                <?php
//                                $options = $item->getProductOptions();
//                                if ($options) {
//                                    $optionOutput = array();
//                                    foreach ($options as $option) {
//                                        if ($option['oioValue']) {
//                                            $optionOutput[] = "<strong>" . $option['oioKey'] . ": </strong>" . $option['oioValue'];
//                                        }
//                                    }
//                                    echo implode('<br>', $optionOutput);
//                                }
                                ?>
                            <!--</td>-->
                            <td style="vertical-align: top; padding: 5px 10px 5px 0;text-align: center;"><?= $item->getSKU() ?></td>
                            <td style="vertical-align: top; padding: 5px 10px 5px 0;text-align: center;"><?= $item->getQty() ?></td>
                            <td style="vertical-align: top; padding: 5px 10px 5px 0;text-align: right;"><?= ltrim(StorePrice::format($item->getPricePaid()), "AED") ?></td>
                            <td style="vertical-align: top; padding: 5px 10px 5px 0;text-align: right;"><?php echo '5%'; ?></td>
                            <td style="vertical-align: top; padding: 5px 5px 5px 0;text-align: right;"><?= ltrim(StorePrice::format($item->getSubTotal()), "AED") ?></td>
                        </tr>
                        <?php
                        $ltrimval = str_replace(',', '', ltrim(StorePrice::format($item->getSubTotal()), "AED"));
                        $subtotal[] = $ltrimval;
                    }
                }
                ?>
            </tbody>
        </table>

        <p>
            <?php if ($order->isShippable()) { ?>
                <strong><?= t("Shipping") ?>:</strong>  <?= StorePrice::format($order->getShippingTotal()) ?><br>
                <strong><?= t("Shipping Method") ?>: </strong><?= $order->getShippingMethodName() ?> <br>

                <?php
                $shippingInstructions = $order->getShippingInstructions();
                if ($shippingInstructions) {
                    ?>
                    <strong><?= t("Delivery Instructions") ?>: </strong><?= $shippingInstructions ?> <br />
                <?php } ?>
            <?php } ?>
        <table border="0" width="100%" style="border-collapse: collapse;">
            <tr>
                <td width="60%" style="vertical-align: top; padding: 10px;"></td>
                <td width="40%" style="vertical-align: top; padding: 0px;">
                    <table border="0" width="100%" style="border-collapse: collapse;padding: 5px;">
                        <tr>
                            <td width="50%" style="vertical-align: top; padding: 5px;text-align: right;"><strong>Sub Total : </strong></td>
                            <td width="50%" style="vertical-align: top; padding: 5px;text-align: right;font-weight: bold;"><?php echo number_format(array_sum($subtotal), 2); ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <?php
        $applieddiscounts = $order->getAppliedDiscounts();
        if (!empty($applieddiscounts)) {
            ?>
            <strong><?= (count($applieddiscounts) > 1 ? t('Discounts') : t('Discount')); ?>: </strong>
            <?php
            $discountsApplied = array();
            foreach ($applieddiscounts as $discount) {
                $discountsApplied[] = $discount['odDisplay'];
            }
            echo implode(',', $discountsApplied);
            ?>
            <br/>
        <?php } else { ?>
            <table border="0" width="100%" style="border-collapse: collapse;">
                <tr>
                    <td width="60%" style="vertical-align: top; padding: 10px;"></td>
                    <td width="40%" style="vertical-align: top; padding: 0px;">
                        <table border="0" width="100%" style="border-collapse: collapse;padding: 5px;">
                            <tr>
                                <td width="50%" style="vertical-align: top; padding: 5px;text-align: right;"><strong>Discount 0% : </strong></td>
                                <td width="50%" style="vertical-align: top; padding: 5px;text-align: right;font-weight: bold;"><?php echo '0.00'; ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        <?php } ?>
            
            <?php if ($order->isShippable()) { ?>
            <table border="0" width="100%" style="border-collapse: collapse;">
                <tr>
                    <td width="60%" style="vertical-align: top; padding: 10px;"></td>
                    <td width="40%" style="vertical-align: top; padding: 0px;">
                        <table border="0" width="100%" style="border-collapse: collapse;padding: 5px;">
                            <tr>
                                <td width="50%" style="vertical-align: top; padding: 5px;text-align: right;"><strong><?= t("Shipping Charge") ?> :</strong></td>
                                <td width="50%" style="vertical-align: top; padding: 5px;text-align: right;font-weight: bold;"><?= StorePrice::format($order->getShippingTotal()) ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>          
            <?php } ?>

        <table border="0" width="100%" style="border-collapse: collapse;">
            <tr>
                <td width="60%" style="vertical-align: top; padding: 10px;"></td> 
                <td width="40%" style="vertical-align: top; padding: 0px;">
                    <table border="0" width="100%" style="border-collapse: collapse;padding: 5px;"><tr>
                            <?php foreach ($order->getTaxes() as $tax) { ?>
                                <td width="50%" style="vertical-align: top; padding: 5px;text-align: right;"><strong><?= $tax['label'] ?> </strong> :</td> <td width="50%" style="vertical-align: top; padding: 5px;text-align: right;font-weight: bold;"><?= ltrim(StorePrice::format($tax['amount'] ? $tax['amount'] : $tax['amountIncluded']), "AED") ?></td>
                            <?php } ?>

                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table border="0" width="100%" style="border-collapse: collapse;">
            <tr>
                <td width="60%" style="vertical-align: top; padding: 10px;"></td>
                <td width="40%" style="vertical-align: top; padding: 0px;">
                    <table border="0" width="100%" style="border-collapse: collapse;padding: 5px;">
                        <tr>
                            <td width="50%" style="vertical-align: top; padding: 5px;text-align: right;"><strong><?= t("Grand Total") ?> :</strong></td>
                            <td width="50%" style="vertical-align: top; padding: 5px;text-align: right;font-weight: bold;"><?= StorePrice::format($order->getTotal()) ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <h3 style="border-bottom:1px solid #000;">TAX Summary</h3>
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <thead style="border-bottom:1px solid #000;">
                <tr>
                    <th>RATE</th>
                    <th>TAX</th>
                    <th>NET</th>
                </tr>
            </thead>
            <tbody style="border-bottom:1px solid #000;" >
                <tr>
                    <?php foreach ($order->getTaxes() as $tax) { ?> 
                        <td style="vertical-align: top; padding: 5px 10px 5px 0;text-align: center;border-bottom: 1px solid #000;">VAT TAX UAE @ 5%</td>
                        <td style="vertical-align: top; padding: 5px 10px 5px 0;text-align: center;border-bottom: 1px solid #000;"><?= ltrim(StorePrice::format($tax['amount'] ? $tax['amount'] : $tax['amountIncluded']), "AED") ?></td>
                        <td style="vertical-align: top; padding: 5px 10px 5px 0;text-align: center;border-bottom: 1px solid #000;"><?= StorePrice::format($order->getTotal()) ?></td>
                    <?php } ?>
                </tr>
            </tbody>
        </table>

        <?php if ($order->getTotal() > 0) { ?>
            <strong><?= t("Payment Method") ?>: </strong><?= $order->getPaymentMethodName() ?><br />

            <?php
            $paid = $order->getPaid();

            if ($paid) {
                $status = t('Paid') . ' - ' . $dh->formatDateTime($paid);
            } else {
                $status = t('Unpaid');
            }
            ?>
            <strong><?= t("Payment Status") ?>:</strong> <?= $status; ?><br>

            <?php
            $transactionReference = $order->getTransactionReference();
            if ($transactionReference) {
                ?>
                <strong><?= t("Transaction Reference") ?>: </strong><?= $transactionReference ?>
            <?php } ?>
        <?php } else { ?>
            <strong><?= t('Free Order') ?></strong>
        <?php } ?>
    </p>

    <p><a href="<?= \URL::to('/dashboard/store/orders/order/' . $order->getOrderID()); ?>"><?= t('View this order within the Dashboard'); ?></a></p>

</body>
</html>

<?php
$bodyHTML = ob_get_clean();
/**
 * HTML BODY END
 *
 * ======================
 *
 * PLAIN TEXT BODY START
 */
ob_start();
?>

<?= t("Order #:") ?> <?= $order->getOrderID() ?>
<?= t("A new order has been placed on your website") ?>
<?php $body = ob_get_clean(); ?>