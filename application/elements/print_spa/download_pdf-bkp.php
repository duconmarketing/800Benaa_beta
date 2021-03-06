<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\Product as StoreProduct;
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
            $this->useTemplate($this->_tplIdx, 0, 0);
//        }

    }

    public function Footer() {
        
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
$pdf->SetMargins(20, 60, 40);
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
$pdf->SetFont('arial', '', 8, '', true);

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
$pdf1->SetMargins(20, 60, 40);
$pdf1->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf1->SetFooterMargin(0);

//set auto page breaks
$pdf1->SetAutoPageBreak(TRUE, 20);

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
$pdf1->SetFont('arial', '', 8, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf1->AddPage();
$pdf1->setCellHeightRatio(1.5);

// output the HTML content
//$content
ob_start();
?>
<style type="text/css">
    .style1{font-family: Arial, Helvetica, sans-serif;font-size:10pt;width: 100%;}
    .style2{font-family: Arial, Helvetica, sans-serif;}
    .style3{ font-size: small; background-color: #99CCFF;}

    table.table1 {border-collapse:collapse; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; width:100%;}
    .table1header {font-size: 10pt;font-family: Arial, Helvetica, sans-serif;color: #FFFFFF;background-color: #ec7c05;padding:10px}
    .table1footer {text-align:right;font-weight:bold;border:solid black 0px;}
    .table1col0 {text-align:left; width:25%; border-right:solid black 1px;font-family: Arial, Helvetica, sans-serif; font-size: 9pt;}
    .table1col1 {text-align:left; width:15%;border-right:solid black 1px;font-family: Arial, Helvetica, sans-serif; font-size: 9pt;}
    .table1col2,.table1col3,.table1col4{text-align:right; width:15%;border-right:solid black 1px;font-family: Arial, Helvetica, sans-serif; font-size: 9pt;}
    .table1col5{text-align:right;width:15%;font-family: Arial, Helvetica, sans-serif; font-size: 9pt;}   
    .table1lastrow{border-top:solid black 1px;height:10px;}
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
    .max{
        width:18.33%;
    }
    .min{
        width:15%;
    }		
    .middle{
        width:30%;
    }	
    .xsmall{
        width:10%;
    }	
</style>
<?php
$cssstyle = ob_get_contents();
ob_clean();
ob_start();
//print_r($postValues);die('asas');
$date = date("M d, Y");
$date7 = strtotime($date);
$date7 = strtotime("+6 day", $date7);
$date7 = date('M d, Y', $date7);
?>
<div class="credit_left" style="float:left;width:50%;">

</div>
<div class="credit_right" style="float:right;width:50%;">

</div>
<table>
    <tr>
        <td valign="left" style="text-align:left">
            <label>To:</label><br>
            <label>Attn: MR/MS. <?php echo $postValues['firstname'] . ' ' . $postValues['lname'] ?>,</label><br>
            <label><?php echo $postValues['cname'] ?></label><br>
            <label><?php echo 'TRN :'.$postValues['trnnumber']; ?></label><br>
            <label><?php echo $postValues['address'] ?></label><br>
            <label>Contact Number: <?php echo $postValues['pnumber'] ?></label><br>
            <label>Email: <?php echo $postValues['email'] ?></label>
        </td>
        <td valign="right" style="text-align:right">
            <label>Date:</label>&nbsp;<?php echo $date; ?><br>
            <label>Expiry date:</label>&nbsp;<?php echo $date7; ?><br>
            <label>Quotation Ref:</label>&nbsp;<?php echo 'EGL' . date('Y-m-d H:i:s') ?>
        </td>
    </tr>
</table>
<br/>
<?php if ($cart) { ?>
    <table class="style1" style="margin-top:20px;">
        <tr>
            <td class="table1header xsmall">
                <?= t('No'); ?></td>
            <td class="table1header middle">
                <?= t('Product'); ?></td>
            <td class="table1header max">
                <?= t('Product Code'); ?></td>
            <td class="table1header xsmall">
                <?= t('Qty'); ?></td>
            <td class="table1header xsmall">
                <?= t('Unit'); ?></td>
            <td class="table1header max" >
                <?= t('Unit Price'); ?></td>            
            <td class="table1header max" style="text-align:right;">
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
                        <td class="table1lastrow xsmall">

                            <?= $i; ?>

                        </td>
                        <td class="table1lastrow middle">

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
                                            <div class="store-cart-list-item-attribute">
                                                <span class="store-cart-list-item-attribute-label"><?= ($optiongroup ? h($optiongroup->getName()) : '') ?>:</span>
                                                <span class="store-cart-list-item-attribute-value"><?= ($optionvalue ? h($optionvalue) : '') ?></span>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </td>
                        <td class="table1lastrow max">
                            <?= $product->getSKU() ?> 
                        </td>
                        <td class="table1lastrow xsmall">
                            <?= $qty ?>
                        </td>
                        <td class="table1lastrow xsmall">
                            <?php if($product->getAttribute('unit_text')!=''){ echo $product->getAttribute('unit_text'); } else { echo "Pcs"; } ?>
                        </td>
                        <td class="table1lastrow max">
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
                        
                        <td style="text-align:right;" class="table1lastrow max">
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
    
    <?php ?>
    <?php
    $crecover = ob_get_contents();
    ob_clean();
    ?>
    <table nobr="true" class="style1" style="margin-bottom:25px;">
        <tbody>
            <tr>
                    <td colspan="6" align="right" ><strong class="cart-grand-total-label"><b><?= t("Sub Total") ?>:</b></strong></td>
                    <td  align="right"><?php
                        $subtotalval = number_format(array_sum($subtot), 2);
                        echo 'AED ' . number_format(array_sum($subtot), 2);
                        ?>
                    </td>
                </tr>   

                            <?php
//                            if(array_sum($subtot)<500){
//                                    $shipping = 50;
                            $shipping = 0;
                            ?>
                                    <tr>
                    <td colspan="6" align="right" ><strong class="cart-grand-total-label"><b><?= t("Shipping Charge") ?>:</b></strong></td>
                    <td  align="right"><?php
//                        echo 'AED ' . number_format($shipping, 2);
                        echo 'Not Included';
                        ?>
                    </td>
                                    </tr>
                            <?php
//                            }	
                            ?>
                            <tr>
                    <td colspan="6" align="right" ><strong class="cart-grand-total-label"><b><?= t("TAX (5%)") ?>:</b></strong></td>
                    <td  align="right"><?php
                        $vatval = number_format((array_sum($subtot) + $shipping) * 0.05, 2);
                        echo 'AED ' . number_format((array_sum($subtot) + $shipping) * 0.05, 2);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" align="right" ><strong class="cart-grand-total-label"><b><?= t("Grand Total") ?>:</b></strong></td>
                    <td  align="right"><?php
                        $grandtotal = str_replace(',', '', $subtotalval) + str_replace(',', '', $vatval) + $shipping;
                        echo 'AED ' . number_format($grandtotal, 2);
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
//    $pdf->Cell(0, 10, '', 0, 1, 'C', 0, '', 0);
    //Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M');
    $pdf1->writeHTML($cssstyle . $crecover);
    $pdf1->writeHTML($totHtml);
//    $pdf1->Cell(0, 10, '', 0, 1, 'C', 0, '', 0);
    
    $pdf->Image(DIR_BASE . '/application/elements/print_spa/sales-sign.jpg',13, '', 185, 0, 'JPG', '', 'L', true, 300, '', false, false, 0, false, false, FALSE);
    $pdf1->Image(DIR_BASE . '/application/elements/print_spa/sales-sign.jpg',13, '', 185, 0, 'JPG', '', 'L', true, 300, '', false, false, 0, false, false, FALSE);
//    Image( $file, $x = '', $y = '', $w = 0, $h = 0, $type = '', $link = '', $align = '', $resize = false, $dpi = 300, $palign = 'C', $ismask = false, $imgmask = false, $border = 0, $fitbox = false, $hidden = false, $fitonpage = false, $alt = false, $altimgs = array() );
    
    $pdfname = $postValues['firstname'] . '_' . $postValues['lname'] . '_' . date('Y-m-d-h-i-s') . '.pdf';
    //
    $pdf->Output($pdfname, 'D');
    $pdf1->Output('application/quotation/' . $pdfname, 'F');

    if (file_exists('application/quotation/' . $pdfName)) {
        $mh = new MailService();
        $mh->to('sales@800benaa.com');
        $mh->from('noreply@800benaa.com');
        $mh->setBody('Greetings From Ducon Industries FZCO, Hello Mr/Ms.' . $postValues['firstname'] . ' ' . $postValues['lname'] . ' Please find the attched Quotation with this email');
        $mh->setSubject(t('Live quotation'));
        $afiles = array();
        $pdffilepath = DIR_BASE . '/application/quotation/' . $pdfname;
        $afiles[0]['path'] = $pdffilepath;
        $afiles[0]['mime'] = 'application/pdf';
        $afiles[0]['name'] = basename($pdffilepath);
        $mh->addAttachments($afiles);
        $mh->sendMail();
        unlink(DIR_BASE . '/application/quotation/' . $pdfname);
    }
}