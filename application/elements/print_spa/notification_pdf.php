<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\Product as StoreProduct;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Price as StorePrice;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductOption\ProductOption as StoreProductOption;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductOption\ProductOptionItem as StoreProductOptionItem;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Calculator as StoreCalculator;
use \Application\Src\MailExtended\MailService;

require('tcpdf/config/lang/eng.php');
require('tcpdf/config/tcpdf_config.php');
require('tcpdf/config/tcpdf_config_alt.php');
require('tcpdf/tcpdf.php');
require_once('fpdi.php'); // Extend the TCPDF class to create custom Header and Footer
// Extend the TCPDF class to create custom Header and Footer


class MYPDF extends FPDI {

    var $_tplIdx;

//Page header
    public function Header() {
//        if ($this->page == 1 || $this->page == 2) {
        if (is_null($this->_tplIdx)) {
            $this->setSourceFile(DIR_BASE . '/application/elements/print_spa/online_order.pdf');
            $this->_tplIdx = $this->importPage(1);
        }
        $this->useTemplate($this->_tplIdx, -3, 0);
//        }

    }

    public function Footer() {
        $this->SetY(-10);
//        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 10, 'Page '.$this->PageNo(). '/{nb}' , 0, 1, 'C', 0, '', 0);
    }

}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(SITE);
$pdf->SetTitle(SITE);
$pdf->SetSubject(SITE);
$pdf->SetKeywords(SITE);
$pdf->setPageOrientation('P', TRUE, 10);
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//set margins
$pdf->SetMargins(12, 65, 10);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(0);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 20);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
//$pdf->setLanguageArray($l);
// ---------------------------------------------------------
// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('arial', '', 10, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();
$pdf->setCellHeightRatio(1.5);
$pdf->AliasNbPages();
// output the HTML content
//$content
ob_start();
?>
    <style type="text/css">
        .style1{font-family: Arial, Helvetica, sans-serif;font-size:10pt;width: 100%;}
        .style2{font-family: Arial, Helvetica, sans-serif;}
        .style3{ font-size: small; background-color: #99CCFF;}

        table.table1 {border-collapse:collapse; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; width:100%;}
        .table1header {font-size: 10pt;font-family: Arial, Helvetica, sans-serif;color: #FFFFFF;background-color: #f57b17;padding:8px; font-weight: bold;}

        .table1footer {text-align:right;font-weight:bold;border:solid black 0px;}
        .table1col0 {text-align:left; width:25%; border-right:solid black 1px;font-family: Arial, Helvetica, sans-serif; font-size: 9pt;}
        .table1col1 {text-align:left; width:15%;border-right:solid black 1px;font-family: Arial, Helvetica, sans-serif; font-size: 9pt;}
        .table1col2,.table1col3,.table1col4{text-align:right; width:15%;border-right:solid black 1px;font-family: Arial, Helvetica, sans-serif; font-size: 9pt;}
        .table1col5{text-align:right;width:15%;font-family: Arial, Helvetica, sans-serif; font-size: 9pt;}
        .table1lastrow{border-bottom:solid black 1px;height:10px;padding: 8px;}
        .credit_left{
            float:left;
            width:50%;
            margin-top:50px;
        }
        .credit_right{
            float:right;
            width:50%;
            margin-top:50px;
        }
        .credit_left label{
            font-size:9pt;
            margin:0;
            padding:0;
        }
        .credit_left p{
            margin:0;
            padding:0
        }
        .credit_right{
            float:right;
            width:100%;
        }
        .bor_bott{
            border-bottom:1px solid #000 !important;
            vertical-align:middle;
        }
        .row{
            float:left;
            width:100%;
            border-bottom:1px solid #000 !important;
        }
        .column{
            float:left !important;
            width:20% !important;
        }
        .xmax{
            width:45%;
            text-align: left;
        }
        .xmin{
            width:5%;
            text-align: center;
        }
        .xmiddle{
            width:15%;
            text-align: right;
        }
        .xsmall{
            width:10%;
            text-align: center;
        }
        h3 {
            display: block;
            font-size: 1.17em;
            font-weight: bold;
            margin-block-start: 0em;
            margin-block-end: 0em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
        }
    </style>
<?php
$cssstyle = ob_get_contents();
ob_clean();
ob_start();
$date = date("M d, Y");
if ($order) {
    ?>
    <table width="100%" style="border-collapse: collapse;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="text-align:left;">To,<br />
                <strong><?= t("Mr/Ms") ?>: </strong><?php echo $order->getAttribute("billing_first_name") . ' ' . $order->getAttribute("billing_last_name"); ?><br />
                <strong><?= t("Contact Number") ?>: </strong><?php echo $order->getAttribute("billing_phone"); ?><br />
                <strong><?= t("Email") ?>: </strong><?php echo $order->getAttribute("email"); ?>
            </td>
            <td style="text-align: center;"><h2>Order #<?php echo $ordId=$order->getOrderId();?></h2></td>
            <td valign="right" style="text-align:right;">
                <label><b>Date:</b></label>&nbsp;<?php echo date('M d, Y H:i:s'); ?>
            </td>
        </tr>
    </table>
    <br />
    <strong>Order Details</strong><br />
    <table width="100%" style="margin-top:10px;font-family: Arial, Helvetica, sans-serif; font-size: 10pt; width: 100%;" cellpadding="0" cellspacing="0">
        <tr style="background-color: #f57b17; font-weight: bold;">
            <th style="padding: 10px; width: 5%; text-align: center;"><?= t('No'); ?></th>
            <th style="padding: 10px; width: 50%; text-align: left;"><?= t('Product'); ?></th>
            <th style="padding: 10px; width: 15%; text-align: center;"><?= t('SKU Code'); ?></th>
            <th style="padding: 10px; width: 7%; text-align: center;"><?= t('Qty'); ?></th>
            <th style="padding: 10px; width: 12%; text-align: right;"><?= t('Unit Price'); ?></th>
            <th style="padding: 10px; width: 12%; text-align: right;"><?= t('Total&nbsp;&nbsp;'); ?></th>
        </tr>
        <tbody>
        <?php
        $i=1;
        $items = $order->getOrderItems();
        if ($items) {
            foreach ($items as $item) {
                $pObj = $item->getProductObject();
                ?>
                <tr style="border-bottom: 1px solid #000;" >
                    <td style="padding: 10px; width: 5%; text-align: center; border-bottom: 1px solid #000;"><?= $i++; ?></td>
                    <td style="padding: 10px; width: 50%; text-align: left; border-bottom: 1px solid #000;" nowrap="true"><?= $item->getProductName() ?></td>
                    <td style="padding: 10px; width: 15%; text-align: center; border-bottom: 1px solid #000;"><?= $item->getSKU() ?></td>
                    <td style="padding: 10px; width: 7%; text-align: center; border-bottom: 1px solid #000;"><?= $item->getQty() ?></td>
                    <td style="padding: 10px; width: 12%; text-align: right; border-bottom: 1px solid #000;"><?= ltrim(StorePrice::format($item->getPricePaid()), "AED") ?></td>
                    <td style="padding: 10px; width: 12%; text-align: right; border-bottom: 1px solid #000;"><?= ltrim(StorePrice::format($item->getSubTotal()), "AED") ?></td>
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
    $crecover = ob_get_contents();
    ob_clean();
    ob_start();
    ?>
    <table border="0" width="100%" style="border-collapse: collapse;">
        <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
        <tr>
            <td width="60%" valign="top"><?php if ($order->isShippable()){?><strong><?= t("Shipping") ?>:</strong>  <?= StorePrice::format($order->getShippingTotal()) ?><br><strong><?= t("Shipping Location") ?>: </strong><?= $order->getShippingMethodName() ?>
                    <?php
                    $shippingInstructions = $order->getShippingInstructions();
                    if ($shippingInstructions) {
                        ?>
                        <strong><?= t("Delivery Instructions") ?>: </strong><?= $shippingInstructions ?>
                    <?php } ?>
                <?php } ?>
                <?php if ($order->getTotal() > 0) { ?>
                    <br><strong><?= t("Payment Method") ?>: </strong><?= $order->getPaymentMethodName() ?><br />
                    <?php
                    $paid = $order->getPaid();
                    if ($paid) {
                        $status = t('Paid');
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
            </td>
            <td width="40%">
                <table border="0" width="100%">
                    <tr>
                        <td width="60%" style="vertical-align: top; padding: 2px;;text-align: right;"><strong>Sub Total :</strong></td>
                        <td width="40%" style="vertical-align: top; padding: 2px;;text-align: right;font-weight: bold;"><?php echo number_format(array_sum($subtotal), 2); ?></td>
                    </tr>
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
                        <tr>
                            <td width="60%" style="vertical-align: top; padding: 2px;;text-align: right;"><strong>Discount 0% :</strong></td>
                            <td width="40%" style="vertical-align: top; padding: 2px;;text-align: right;font-weight: bold;"><?php echo '0.00'; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if ($order->isShippable()) { ?>
                        <tr>
                            <td width="60%" style="vertical-align: top; padding: 2px;;text-align: right;"><strong><?= t("Shipping Charge") ?> :</strong></td>
                            <td width="40%" style="vertical-align: top; padding: 2px;;text-align: right;font-weight: bold;"><?= StorePrice::format($order->getShippingTotal()) ?></td>
                        </tr>
                    <?php } ?>
                    <?php foreach ($order->getTaxes() as $tax) { ?>
                        <tr>
                            <td width="60%" style="vertical-align: top; padding: 2px;;text-align: right;"><strong><?= t("VAT TAX UAE @ 5%") ?> </strong> :</td>
                            <td width="40%" style="vertical-align: top; padding: 2px;;text-align: right;font-weight: bold;"><?= ltrim(StorePrice::format($tax['amount'] ? $tax['amount'] : $tax['amountIncluded']), "AED") ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td width="60%" style="vertical-align: top; padding: 2px;;text-align: right;"><strong><?= t("Grand Total") ?> :</strong></td>
                        <td width="40%" style="vertical-align: top; padding: 2px;;text-align: right;font-weight: bold;"><?= StorePrice::format($order->getTotal()) ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <?php
    $totHtml = ob_get_contents();
    ob_clean();

    $pdf->writeHTML($cssstyle);
    $pdf->writeHTML($crecover);
    $pdf->writeHTML($totHtml);
    $pdfname = 'application/files/tempUploads/online_order_'. $ordId . '.pdf';
    $pdf->Output($pdfname, 'F');
}