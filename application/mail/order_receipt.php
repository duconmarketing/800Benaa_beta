<?php
defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Price as StorePrice;
use Concrete\Package\CommunityStore\Src\Attribute\Key\StoreOrderKey as StoreOrderKey;
use Concrete\Package\CommunityStore\Src\CommunityStore\Customer\Customer as StoreCustomer;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\Product as StoreProduct;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductOption\ProductOption as StoreProductOption;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductOption\ProductOptionItem as StoreProductOptionItem;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Calculator as StoreCalculator;

$dh = Core::make('helper/date');
$subject = t("Order Receipt #%s", $order->getOrderID());

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
            <h2><?= t('Your Order') ?></h2>
        <?php } ?>
            <h2 style="text-align: center;">Order Number : <?= $order->getOrderID(); ?></h2>
        <table border="0" width="100%" style="border-collapse: collapse;">
            <tr>
                <td width="50%" style="vertical-align: top; padding: 0; padding-right: 10px;padding-left: 10px;">
                    <p style="margin: 3px 0px;font-weight: bold;font-size: 18px;">Ducon Industries Fzco</p>
                    <p style="line-height: 18px;margin: 3px 0px;">(TRN: 100386329500003)</p>                    
<!--                    <p style="line-height: 18px;margin: 3px 0px;">National Industries Park,<br/>TP010225,P.O Box 262394,<br/>Dubai,United Arab Emirates</p>                    -->
                    <p style="margin: 3px 0px;"><strong>Phone: </strong>+971 4 8806996</p>
                    <p style="margin: 3px 0px;"><strong>Fax: </strong>+971 4 8806980</p>
                    <!--<p style="margin: 3px 0px;"><strong>Email: </strong>sales@duconind.com</p>-->
                    <!--<p style="margin: 3px 0px;"><strong>Web: </strong>http://www.duconind.com/</p>-->
                </td>
                <td style="vertical-align: top; padding: 0;">
                    <!--<p data-redactor-inserted-image="true" style="text-align: right;"><img id="image-marker" src="https://www.800benaa.com/download_file/view_inline/350" /></p>-->
                    <p style="text-align: right;padding-right: 10px;font-size: 18px;margin-top: 0px;font-weight: bold;">Customer Copy</p>
                </td>
            </tr>
            <tr>
                <td style="font-size: 18px;width: 100%;" colspan="2">
                    <p></p>
                    <p style="margin-top: 30px;">Hi <b><?=$order->getAttribute("billing_first_name") ?></b>,</p>
                    <p>
                        Thank you for placing order on 800Benaa, Please find your order details below. We will contact you soon for delivery.
                    </p>
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

        <h3 style="text-decoration:underline;"><?= t('Order Details') ?></h3>
        <table border="1" cellpadding="0" cellspacing="0" width="100%">
            <thead>
                <tr bgcolor="#ec7c05" style="background-color: #ec7c05d9;height:40px;">
                    <th style="border-bottom: 1px solid #aaa; text-align: left; padding-right: 10px;padding-left:10px; "><?= t('Product Name') ?></th>
                    <th style="border-bottom: 1px solid #aaa; text-align: center; padding-right: 10px;"><?= t('Product Code') ?></th>
                    <th style="border-bottom: 1px solid #aaa; text-align: center; padding-right: 10px;"><?= t('Product Option') ?></th>
                    <th style="border-bottom: 1px solid #aaa; text-align: center; padding-right: 10px;"><?= t('Qty') ?></th>
                    <th style="border-bottom: 1px solid #aaa; text-align: right; padding-right: 10px;"><?= t('Price') ?></th>               
                    <!-- <th style="border-bottom: 1px solid #aaa; text-align: right; padding-right: 10px;"><?= t('VAT %') ?></th> -->
                    <th style="border-bottom: 1px solid #aaa; text-align: right;padding-right: 5px;"><?= t('Subtotal') ?></th>  
                </tr>
            </thead>
            <tbody>
                <?php
                $items = $order->getOrderItems();
                if ($items) {
                    $i = 2;
                    foreach ($items as $item) {
                        $pObj = $item->getProductObject();
                        if ($i % 2) {
                            $bgcolor = 'background-color:#d6d6d6';
                        } else {
                            $bgcolor = '';
                        }
                        $i++;
                        ?>
                        <tr style="height:40px;<?php echo $bgcolor; ?>">
                            <td style="vertical-align: top; padding: 5px 10px 5px 10px;font-weight: bold;"><?= $item->getProductName() ?></td>
                            <td style="vertical-align: top; padding: 5px 10px 5px 0;text-align: center;"><?= $item->getSKU() ?></td>
                            <td style="vertical-align: top; padding: 5px 10px 5px 0;text-align: center;">
                                <?php
                               $options = $item->getProductOptions();
                               if ($options) {
                                   $optionOutput = array();
                                   foreach ($options as $option) {
                                       if ($option['oioValue']) {
                                           $optionOutput[] = "<strong>" . $option['oioKey'] . ": </strong>" . $option['oioValue'];
                                       }
                                   }
                                   echo implode('<br>', $optionOutput);
                               }
                                ?>
                            </td>                            
                            <td style="vertical-align: top; padding: 5px 10px 5px 0;text-align: center;"><?= $item->getQty() ?></td>
                            <td style="vertical-align: top; padding: 5px 10px 5px 0;text-align: right;"><?= ltrim(StorePrice::format($item->getPricePaid()), "AED") ?></td>
                            <!-- <td style="vertical-align: top; padding: 5px 10px 5px 0;text-align: right;"><?php echo '5%'; ?></td> -->
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


        <?php
        $downloads = array();
        foreach ($items as $item) {
            $pObj = $item->getProductObject();

            if (is_object($pObj)) {
                if ($pObj->hasDigitalDownload()) {
                    $fileObjs = $pObj->getDownloadFileObjects();
                    $downloads[$item->getProductName()] = $fileObjs[0];
                }
            }
        }
        if (count($downloads) > 0) {
            ?>

            <h3><?= t("Your Downloads") ?></h3>
            <ul class="order-downloads">
                <?php
                foreach ($downloads as $name => $file) {
                    if (is_object($file)) {
                        echo '<li><a href="' . $file->getForceDownloadURL() . '">' . $name . '</a></li>';
                    }
                }
                ?>
            </ul>

        <?php } ?>

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
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 5px;" >
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
            <strong><?= t("Payment Method") ?>: </strong><?= $order->getPaymentMethodName() ?><br>
        <?php } else { ?>
            <strong><?= t('Free Order') ?></strong><br>
        <?php } ?>

        <?php
        $refunded = $order->getRefunded();
        $paid = $order->getPaid();
        $cancelled = $order->getCancelled();
        $status = '';

        if ($cancelled) {
            echo '<br /><strong>' . t('Cancelled') . '</strong>';
        } else {
            if ($refunded) {
                $status = t('Refunded');
            } elseif ($paid) {
                $status = t('Paid') . ' - ' . $dh->formatDateTime($paid);
            } elseif ($order->getTotal() > 0) {
                $status = t('Unpaid');
            }
        }
        ?>

        <?php if ($status) { ?>
            <strong><?= t("Payment Status") ?>:</strong> <?= $status; ?>
            <br>
            <?php
            $transactionReference = $order->getTransactionReference();
            if ($transactionReference) {
                ?>
                <strong><?= t("Transaction Reference") ?>: </strong><?= $transactionReference ?>
            <?php } ?>
                
        <?php } ?>
    </p>

    <?php echo $paymentInstructions; ?>
    <p style="text-align:center;font-size:16px;margin:0px;">Thanks for your business</p>

    <?php echo trim(\Config::get('community_store.receiptFooter')); ?>

</body>
</html>

<?php
$bodyHTML = ob_get_clean();
/**
 * HTML BODY END
 *
 */
?>