<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\Product as StoreProduct;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Price as StorePrice;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductOption\ProductOption as StoreProductOption;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductOption\ProductOptionItem as StoreProductOptionItem;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Calculator as StoreCalculator;

$subject = t("Quote");
/**
 * HTML BODY START
 */
ob_start();
?>
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
<html>
<head>
<style type="text/css">
.style1{font-family: Arial, Helvetica, sans-serif;font-size:8pt;width: 100%;}
.style2{font-family: Arial, Helvetica, sans-serif;}
.style3{ font-size: small; background-color: #99CCFF;}
 
table.table1 {border-collapse:collapse; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; width:100%;}
.table1header {font-size: 9pt;font-family: Arial, Helvetica, sans-serif;color: #FFFFFF;background-color: #808080;}
.table1footer {text-align:right;font-weight:bold;border:solid black 0px;}
.table1col0 {text-align:left; width:25%; border-right:solid black 1px;font-family: Arial, Helvetica, sans-serif; font-size: 9pt;}
.table1col1 {text-align:left; width:15%;border-right:solid black 1px;font-family: Arial, Helvetica, sans-serif; font-size: 9pt;}
.table1col2,.table1col3,.table1col4{text-align:right; width:15%;border-right:solid black 1px;font-family: Arial, Helvetica, sans-serif; font-size: 9pt;}
.table1col5{text-align:right;width:15%;font-family: Arial, Helvetica, sans-serif; font-size: 9pt;}   
.table1lastrow{border-top:solid black 1px;}   
 </style>
</head>
<body>
<div class="store-cart-modal clearfix" id="cart-modal">
  <div class="store-cart-page-cart">
  <img src="<?php //echo $this->getThemePath()?>/images/sitelogo.png" alt="">
  <br /><br />
    <?php
            if($cart){ ?>
    <table class="style1">
    <tr>
                              <td class="style2">
                                    Company Address</td>
                              <td>
                                    {{!Quote.CreatedBy.Street}}</td>
                              <td>
                                    Created Date</td>
                              <td>&nbsp;
                                    </td>
                        </tr>
                        <tr>
                              <td>&nbsp;
                                    </td>
                              <td>
                                    {{!Quote.CreatedBy.State}}</td>
                              <td>
                                    Expiration Date</td>
                              <td>&nbsp;
                                    </td>
                        </tr>
                        <tr>
                              <td>&nbsp;
                                    </td>
                              <td>
                                    {{!Quote.CreatedBy.PostalCode}}</td>
                              <td>
                                    Quote Number</td>
                              <td>
                                    {{!Quote.QuoteNumber}}</td>
                        </tr>
                        <tr>
                              <td>&nbsp;
                                    </td>
                              <td>&nbsp;
                                    </td>
                              <td>&nbsp;
                                    </td>
                              <td>&nbsp;
                                    </td>
                        </tr>
                        <tr>
                              <td>
                                    Prepared By</td>
                              <td>
                                    {{!Quote.CreatedBy.Name}}</td>
                              <td>
                                    Contact Name</td>
                              <td>
                                    {{!Quote.Contact.name}}</td>
                        </tr>
                        <tr>
                              <td>
                                    E-Mail</td>
                              <td>
                                    {{!Quote.CreatedBy.Email}}</td>
                              <td>
                                    Phone</td>
                              <td>
                                    {{!Quote.Contact.phone}}</td>
                        </tr>
                        <tr>
                              <td>&nbsp;
                                    </td>
                              <td>&nbsp;
                                    </td>
                              <td>
                                    Email</td>
                              <td>
                                    {{!Quote.Contact.email}}</td>
                        </tr>
                        <tr>
                              <td>&nbsp;
                                    </td>
                              <td>&nbsp;
                                    </td>
                              <td>
                                    Fax</td>
                              <td>
                                    {{!Quote.Contact.fax}}</td>
                        </tr>
                        <tr>
                              <td>&nbsp;
                                    </td>
                              <td>&nbsp;
                                    </td>
                              <td>&nbsp;
                                    </td>
                              <td>&nbsp;
                                    </td>
                        </tr>
                        
                        <tr>
                              <td class="table1header">
                                    </td>
                              <td class="table1header">
                                    <?= t('Product'); ?></td>
                              <td class="table1header" align="right">
                                    <?= t('Price'); ?></td>
                              
                              <td class="table1header" align="right">
                                    <?= t('Quantity'); ?></td>
                        </tr>
            
            <tbody>
            <?php
            foreach ($cart as $k => $cartItem) {

                $qty = $cartItem['product']['qty'];
                $product = $cartItem['product']['object'];
                if (is_object($product)) {
                    ?>

                    <tr>
                        <?php $thumb = $product->getImageThumb(); ?>
                        <?php if ($thumb) { ?>
                        <td class="table1lastrow">
                            <a href="<?= URL::to(Page::getByID($product->getPageID())) ?>">
                                <?=  $product->getImageThumb() ?>
                            </a>
                        </td>
                        <td class="table1lastrow">
                        <?php } else { ?>
                        <td class="table1lastrow" colspan="2">
                        <?php } ?>
                        <a href="<?= URL::to(Page::getByID($product->getPageID())) ?>">
                            <?= $product->getName() ?>
                        </a>

                        <?php if ($cartItem['productAttributes']) { ?>
                            <div class="store-cart-list-item-attributes">
                                <?php foreach ($cartItem['productAttributes'] as $groupID => $valID) {

                                    if (substr($groupID, 0, 2) == 'po') {
                                        $groupID = str_replace("po", "", $groupID);
                                        $optionvalue = StoreProductOptionItem::getByID($valID);

                                        if ($optionvalue) {
                                            $optionvalue = $optionvalue->getName();
                                        }
                                    } elseif (substr($groupID, 0, 2) == 'pt')  {
                                        $groupID = str_replace("pt", "", $groupID);
                                        $optionvalue = $valID;
                                    } elseif (substr($groupID, 0, 2) == 'pa')  {
                                        $groupID = str_replace("pa", "", $groupID);
                                        $optionvalue = $valID;
                                    } elseif (substr($groupID, 0, 2) == 'ph')  {
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
                        <td class="table1lastrow">
                            <?php if (isset($cartItem['product']['customerPrice'])) { ?>
                                <?=StorePrice::format($cartItem['product']['customerPrice'])?>
                            <?php } else {  ?>
                                <?php
                                $salePrice = $product->getSalePrice();
                                if (isset($salePrice) && $salePrice != "") {
                                    echo '<span class="sale-price">' . StorePrice::format($salePrice) . '</span>';
                                } else {
                                    echo StorePrice::format($product->getActivePrice());
                                }
                                ?>
                            <?php } ?>
                        </td>
                        <td class="table1lastrow">
                            <?php if ($product->allowQuantity()) { ?>

                                <input type="hidden" name="instance[]" value="<?= $k ?>"/>
                                <input type="number" class="form-control" name="pQty[]" value="<?= $qty ?>" style="width: 50px;">
                            <?php } else { ?>
                                1
                            <?php } ?>

                            <a name="action" data-instance="<?= $k ?>"
                               class="store-btn-cart-list-remove btn-xs btn btn-danger" type="submit"><i
                                    class="fa fa-remove"></i><?php //echo t("Remove")
                                ?></a>
                        </td>
                    </tr>
                <?php }
            } 
			
			$totals = StoreCalculator::getTotals();
			$total=$totals['total'];
			$subTotal=$totals['subTotal'];
			?>
            <tr>
            <td colspan="4"  align="right"><strong class="cart-grand-total-label"><?= t("Items Sub Total") ?>:</strong>
        <span class="cart-grand-total-value"><?= StorePrice::format($subTotal) ?></span></td>
        </tr>

            </tbody>
        </table>
    <?php }//if cart
            ?>
  </div>
</div>
</body>

</html>
<?php 
$bodyHTML = ob_get_clean();
?>