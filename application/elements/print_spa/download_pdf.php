<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

use \Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Price as StorePrice;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductOption\ProductOption as StoreProductOption;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductOption\ProductOptionItem as StoreProductOptionItem;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Calculator as StoreCalculator;
use \Application\Src\MailExtended\MailService;

//die('asdasdasd');
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
            $this->setSourceFile(DIR_BASE . '/application/elements/print_spa/quote2.pdf');
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
$pdf->SetMargins(20, 75, 20);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(0);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 75);

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

$pdf1 = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false);
// set document information
$pdf1->SetCreator(PDF_CREATOR);
$pdf1->SetAuthor(SITE);
$pdf1->SetTitle(SITE);
$pdf1->SetSubject(SITE);
$pdf1->SetKeywords(SITE);
$pdf1->setPageOrientation('P', TRUE, 10);
// set default monospaced font
$pdf1->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//set margins
$pdf1->SetMargins(20, 75, 20);
$pdf1->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf1->SetFooterMargin(0);

//set auto page breaks
$pdf1->SetAutoPageBreak(TRUE, 75);

//set image scale factor
$pdf1->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
//$pdf->setLanguageArray($l);
// ---------------------------------------------------------
// set default font subsetting mode
$pdf1->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf1->SetFont('arial', '', 10, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf1->AddPage();
$pdf1->setCellHeightRatio(1.5);

$pdf->AliasNbPages();
$pdf1->AliasNbPages();

// output the HTML content
//$content
ob_start();
?>
    <style type="text/css">
        .style1{font-family: Arial, Helvetica, sans-serif;font-size:10pt;width: 100%;}
        .style2{font-family: Arial, Helvetica, sans-serif;}
        .style3{ font-size: small; background-color: #99CCFF;}

        table.table1 {border-collapse:collapse; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; width:100%;}
        .table1header {font-size: 10pt;font-family: Arial, Helvetica, sans-serif;color: #FFFFFF;background-color: #ec7c05;text-align: center;}
        .table1footer {text-align:right;font-weight:bold;border:solid black 0px;}
        .table1col0 {text-align:left; width:25%; border-right:solid black 1px;font-family: Arial, Helvetica, sans-serif; font-size: 9pt;}
        .table1col1 {text-align:left; width:15%;border-right:solid black 1px;font-family: Arial, Helvetica, sans-serif; font-size: 9pt;}
        .table1col2,.table1col3,.table1col4{text-align:right; width:15%;border-right:solid black 1px;font-family: Arial, Helvetica, sans-serif; font-size: 9pt;}
        .table1col5{text-align:right;width:15%;font-family: Arial, Helvetica, sans-serif; font-size: 9pt;}
        .table1lastrow{border-top:solid black 1px;height:10px;padding: 8px;}
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
            width:40%;
        }
        .xmin{
            width:6%;
        }
        .xmiddle{
            width:13%;
        }
        .xsmall{
            width:8%;
        }
        .xsmall2{
            width:10%;
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
$date7 = strtotime($date);
$date7 = strtotime("+6 day", $date7);
$date7 = date('M d, Y', $date7);
?>

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td valign="left" style="text-align:left"><b>To:</b><br>
                Attn: Mr/Ms. <?php echo $extdetail['fname'] . ' ' . $extdetail['lname'] ?>,<br>
                Contact Number: <?php echo $extdetail['billing_phone'] ?><br>
                Email: <?php echo $extdetail['email'] ?><br>
                <!-- Company: <?php echo $extdetail['cname'] ?><br> -->Address: <?php echo $extdetail['address1'] ?>, <?php echo $extdetail['state_province'] ?>, <?php echo $extdetail['postal_code'] ?><br>
                Country: <?php echo $extdetail['country'] ?>

            </td>
            <td valign="right" style="text-align:right">
                Date:&nbsp;<?php echo $date; ?><br>
                Expiry date:&nbsp;<?php echo $date7; ?><br>
                Quotation Ref:&nbsp;<?php echo 'EGL' . date('Y-m-d H:i:s') ?><br>
                <?php if(strlen($extdetail['comp_name']) > 1){?>
                    <b>Company Details:</b><br>
                    Company Name: <?php echo $extdetail['comp_name'] ?><br>
                    Delivery Contact Person: <?php echo $extdetail['comp_dev_per'] ?><br>
                    Delivery Contact Number: <?php echo $extdetail['comp_dev_no'] ?>
                <?php } ?>
            </td>
        </tr>
    </table>
    <br/>
<?php if ($cart) { ?>
    <table width="100%" style="margin-top:10px;font-family: Arial, Helvetica, sans-serif; font-size: 10pt; width: 100%;" cellpadding="0" cellspacing="0">
        <tr>
            <td class="table1header xmin">
                <?= t('No'); ?></td>
            <td class="table1header xmax">
                <?= t('Product'); ?></td>
            <td class="table1header xmiddle ">
                <?= t('SKU Code'); ?></td>
            <td class="table1header xsmall">
                <?= t('Qty'); ?></td>
            <td class="table1header xsmall2">
                <?= t('Unit'); ?></td>
            <td class="table1header xmiddle" >
                <?= t('Unit Price'); ?></td>
            <td class="table1header xmiddle" >
                <?= t('Total'); ?></td>
        </tr>
        <tbody>
        <?php
        $i = 1;
        foreach ($cart as $k => $cartItem) {

            $qty = $cartItem['product']['qty'];
            $product = $cartItem['product']['object'];
            if (is_object($product)) {
                ?>

                <tr nobr="true">
                    <?php $thumb = $product->getImageThumb(); ?>
                    <td class="table1lastrow xmin">
                        <?= $i; ?>
                    </td>
                    <td class="table1lastrow " style="text-align: left;">

                        <?= ucwords(strtolower($product->getName())) ?>

                        <?php if ($cartItem['productAttributes']) { ?>
                            <div class="store-cart-list-item-attributes">
                                <?php
                                foreach ($cartItem['productAttributes'] as $groupID => $valID) {

                                    if (substr($groupID, 0, 2) == 'po') {
                                        $groupID = str_replace("po", "", $groupID);
                                        $optionvalue = StoreProductOptionItem::getByID($valID);

                                        if ($optionvalue) {
                                            $optionvalue = $optionvalue->getName();
                                        }
                                    } elseif (substr($groupID, 0, 2) == 'pt') {
                                        $groupID = str_replace("pt", "", $groupID);
                                        $optionvalue = $valID;
                                    } elseif (substr($groupID, 0, 2) == 'pa') {
                                        $groupID = str_replace("pa", "", $groupID);
                                        $optionvalue = $valID;
                                    } elseif (substr($groupID, 0, 2) == 'ph') {
                                        $groupID = str_replace("ph", "", $groupID);
                                        $optionvalue = $valID;
                                    }

                                    $optiongroup = StoreProductOption::getByID($groupID);
                                    ?>
                                    <?php if ($optionvalue) { ?>
                                        <span class="store-cart-list-item-attribute-label">( <?= ($optiongroup ? h($optiongroup->getName()) : '') ?>:</span>
                                        <span class="store-cart-list-item-attribute-value"><?= ($optionvalue ? h($optionvalue) : '') ?> )</span>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </td>
                    <td class="table1lastrow " style="text-align: center;">
                        <?= $product->getSKU() ?>
                    </td>
                    <td class="table1lastrow xsmall" style="text-align: center;">
                        <?= $qty ?>
                    </td>
                    <td class="table1lastrow " style="text-align: center;">
                        <?php if($product->getAttribute('unit_text')!=''){ echo $product->getAttribute('unit_text'); } else { echo "Pcs"; } ?>
                    </td>
                    <td class="table1lastrow" style="text-align: right;">
                        <?php
                        $price = '';
                        if (isset($cartItem['product']['customerPrice'])) {
                            $price = StorePrice::format($cartItem['product']['customerPrice']);
                            ?>
                            <?= ltrim($price,"AED"); ?>
                        <?php } else { ?>
                            <?php
                            $salePrice = $product->getSalePrice();
                            if (isset($salePrice) && $salePrice != "") {

                                $price = StorePrice::format($salePrice);
                                echo '<span class="sale-price">' . ltrim($price,"AED") . '</span>';
                            } else {
                                $price = StorePrice::format($product->getActivePrice());
                                echo ltrim($price,"AED");
                            }
                            ?>
                        <?php } ?>
                    </td>

                    <td style="text-align:right;" class="table1lastrow ">
                        <?php
                        $pp = preg_replace("/[^0-9,.]/", "", $price);
                        $pp = str_replace(",", "", $pp);
                        echo number_format(($pp * $qty), 2);
                        $subtot[] = $pp * $qty;
                        //echo number_format(str_replace(',','',$pp) * $qty);
                        ?>
                    </td>
                </tr>
                <?php
            }
            $i++;
        }

        $totals = StoreCalculator::getTotals();
        $total = $totals['total'];
        $subTotal = $totals['subTotal'];
        $shipping = 0;
        ?>
        </tbody>
    </table>
    <?php
    $crecover = ob_get_contents();
    ob_clean();
    ?>
    <table border="0" width="100%" style="border-collapse: collapse;">
        <tbody>
        <tr>
            <td align="right" width="80%" ><strong class="cart-grand-total-label"><b><?= t("Sub Total") ?>:</b></strong></td>
            <td align="right" width="20%"><?php
                echo 'AED ' . number_format($extdetail['subTotal'], 2);
                ?>
            </td>
        </tr>
        <?php
        $shipping = 0;
        ?>
        <tr>
            <td align="right" ><strong class="cart-grand-total-label"><b><?= t("Shipping Charge") ?>:</b></strong></td>
            <td align="right"><?php
                echo 'AED ' . number_format($extdetail['shippingTotal'], 2);
                ?>
            </td>
        </tr>
        <?php if(Session::get('no_shipping')){ ?>
            <tr>
                <td align="right" ><strong class="cart-grand-total-label"><b><?= t("Shipping Type") ?>:</b></strong></td>
                <td align="right"><?php
                    echo 'Collect from Store';
                    ?>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td align="right" ><strong class="cart-grand-total-label"><b><?= t("TAX (5%)") ?>:</b></strong></td>
            <td align="right"><?php
                echo 'AED ' . number_format($extdetail['taxTotal'], 2);
                ?>
            </td>
        </tr>
        <tr>
            <td align="right" ><strong class="cart-grand-total-label"><b><?= t("Grand Total") ?>:</b></strong></td>
            <td align="right"><?php
                echo 'AED ' . number_format($extdetail['total'], 2);
                ?>
            </td>
        </tr>
        </tbody>
    </table>
    <?php
    $totHtml = ob_get_contents();
    ob_clean();
    
    $pdf->writeHTML($cssstyle . $crecover);
    $pdf->writeHTML($totHtml);

    $pdf1->writeHTML($cssstyle . $crecover);
    $pdf1->writeHTML($totHtml);

    $pdfname = 'Quotation EGL' . date('Y-m-d-h-i-s') . '.pdf';
    $pdf->Output($pdfname, 'D');
    $pdf1->Output('application/quotation/' . $pdfname, 'F');

    if (file_exists('application/quotation/' . $pdfName)) {
        $mh = new MailService();
        // $mh->to('marketing@duconind.com,sales@800benaa.com,akhalil@duconind.com');
        // $mh->to('sabinonweb@gmail.com');
        $mh->to($extdetail['email']);
        $mh->bcc('sabinonweb@gmail.com');
        // $mh->cc('afsarlko@gmail.com');
        $mh->from('sales@800benaa.com');
        $mail_body = '<html><body><b>Hi, </b><br /><br />Please find the details of the Online Quotation, Kindly confirm the product availability for the same.<br /><br />Regards,<br />Ducon Team</body></html>';
        $mh->setBodyHTML($mail_body);
        $mh->setSubject(t('New Quotation Notification #EGL' . date('Y-m-d-h-i-s') . ''));
        $afiles = array();
        $pdffilepath = DIR_BASE . '/application/quotation/' . $pdfname;
        $afiles[0]['path'] = $pdffilepath;
        $afiles[0]['mime'] = 'application/pdf';
        $afiles[0]['name'] = basename($pdffilepath);
        $mh->addAttachments($afiles);
        sleep(1);
        $mh->sendMail();
        unlink(DIR_BASE . '/application/quotation/' . $pdfname);
    }
}