<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

use \Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Price as StorePrice;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductOption\ProductOption as StoreProductOption;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductOption\ProductOptionItem as StoreProductOptionItem;

global $u;
?>
<div class="store-cart-page">
    <h2><?= t("Your Cart") ?></h2>

    <?php if (isset($actiondata) and ! empty($actiondata)) { ?>
        <?php if ($actiondata['action'] == 'update') { ?>
            <!--<p class="alert alert-success"><?= t('Your cart has been updated'); ?></p>-->
        <?php } ?>

        <?php if ($actiondata['action'] == 'clear') { ?>
            <p class="alert alert-warning"><?= t('Your cart has been cleared'); ?></p>
        <?php } ?>

        <?php if ($actiondata['action'] == 'remove') { ?>
            <p class="alert alert-warning"><?= t('Item removed'); ?></p>
        <?php } ?>

        <?php if ($actiondata['quantity'] != $actiondata['added']) { ?>
            <p class="alert alert-warning"><?= t('Due to stock levels your quantity has been limited'); ?></p>
        <?php } ?>
    <?php } ?>

    <input id='cartURL' type='hidden' data-cart-url='<?= \URL::to("/cart/") ?>'>

    <?php
    if ($cart) {
        $i = 1;
        ?>
        <form method="post" class="form-inline">
            <table id="store-cart" class="store-cart-table table table-hover table-condensed">
                <thead>
                    <tr>
                        <th colspan="2"><?= t('Product'); ?></th>
                        <th><?= t('Price'); ?></th>
                        <th class="text-right"><?= t('Quantity'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($cart as $k => $cartItem) {

                        $qty = $cartItem['product']['qty'];
                        $product = $cartItem['product']['object'];
                        if (is_object($product)) {
                            ?>

                            <tr class="store-cart-item">
                                <?php $thumb = $product->getImageThumb(); ?>
                                <?php if ($thumb) { ?>
                                    <td class="store-cart-list-thumb">
                                        <a href="<?= URL::to(Page::getByID($product->getPageID())) ?>">
                                            <?= $product->getImageThumb() ?>
                                        </a>
                                    </td>
                                    <td class="store-cart-product-name">
                                    <?php } else { ?>
                                    <td class="store-cart-product-name" colspan="2">
                                    <?php } ?>
                                    <a href="<?= URL::to(Page::getByID($product->getPageID())) ?>">
                                        <?= $product->getName() ?>
                                    </a>

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
                                <td class="store-cart-item-price">
                                    <?php if (isset($cartItem['product']['customerPrice'])) { ?>
                                        <?= StorePrice::format($cartItem['product']['customerPrice']) ?>
                                    <?php } else { ?>
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
                                <td class="store-cart-product-qty text-right">
                                    <?php if ($product->allowQuantity()) { ?>

                                        <input type="hidden" name="instance[]" value="<?= $k ?>"/>
                                        <input type="number" class="form-control" name="pQty[]"
                                               min="1" <?= ($product->allowBackOrders() || $product->isUnlimited() ? '' : 'max="' . $product->getQty() . '"'); ?>
                                               value="<?= $qty ?>" style="width: 65px !important;" onchange="return updateQuantity()">
                                           <?php } else { ?>
                                        1
                                    <?php } ?>

                                    <a name="action" data-instance="<?= $k ?>"
                                       class="store-btn-cart-list-remove btn-xs btn btn-danger" type="submit">
                                        <i class="fa fa-remove" style="color: #fff;"></i><?php //echo t("Remove")?>
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>

                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="2" class="text-left">

                            <?php /*
                              if (!$u -> isLoggedIn ()) { ?>

                              <p>Email ID to get Quote</p><input type="email" name="quote_email" value="">
                              <button name="action" value="quote" class="store-btn-cart-list-clear btn btn-default"
                              type="submit"><?= t("Get quote") ?></button>

                              <?php

                              } */
                            ?>



                        </td>
                        <td colspan="2" class="text-right">
                            <button name="action" value="clear" class="store-btn-cart-list-clear btn btn-default"
                                    type="submit"><?= t("Clear Cart") ?></button>
                            <button name="action" value="update" class="store-btn-cart-list-update btn btn-default"
                                    type="submit" style="display:none;"><?= t("Update") ?></button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </form>

        <div class="box" style="display:none;" >
            <form method="post" id="qte" style="background: #f59836;">
                <div class="form-group field field-text modal-form box-inner">
                    <h2 class="getquoteh2" style="display:none; font-size:20px;color:#000;">Thank you for choosing Ducon</h2>
                    <div class="box-inner-form">
                        <label class="control-label" for="fname"> First Name <span class="text-muted small" style="font-weight: normal">*</span> </label>
                        <input name="firstname" id="firstname" class="form-control" type="text" value="" required style="width:100%">

                        <label class="control-label" for="lname"> Last Name </label>
                        <input name="lname" id="lname" class="form-control" type="text" value="" style="width:100%">

                        <label class="control-label" for="cname"> Company Name <span class="text-muted small" style="font-weight: normal">*</span> </label>
                        <input name="cname" id="cname" class="form-control" type="text" required value="" style="width:100%">

<!--                        <label class="control-label" for="trnnumber"> TRN Number </label>
                        <input name="trnnumber" id="trnnumber" class="form-control" type="text" value="" style="width:100%">-->

                        <label class="control-label" for="email"> Email <span class="text-muted small" style="font-weight: normal">*</span> </label>
                        <input name="email" id="email" class="form-control" type="email" value="" required style="width:100%">

                        <label class="control-label" for="pnumber"> Contact Number <span class="text-muted small" style="font-weight: normal">*</span> </label>
                        <input name="pnumber" id="pnumber" class="form-control" type="number" value="" required style="width:100%">

                        <div class="form-group field field-textarea ">
                            <label class="control-label" for="address"> Address <span class="text-muted small" style="font-weight: normal">*</span></label>
                            <textarea name="address" class="form-control" id="legal_issue" cols="50" rows="5" required style="width:100%"></textarea>
                        </div>
                        <div class="form-actions" style="padding-top:15px">
                            <button name="action" style="background-color:#000;color:#fff;" value="quote" class="store-btn-cart-list-clear btn getquotesubmit"
                                    type="submit"><?= t("Submit") ?></button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- <div class="box-inner">Lorem ipsum dolor sit amet... </div>-->
        </div>

        <button type="button" style="background-color:#000;color:#fff;" class="btn slide-toggle"><?= t("Get your online quote") ?></button>
        <!--    Hidden form for deleting-->
        <form method="post" id="deleteform">
            <input type="hidden" name="instance" value=""/>
            <input type="hidden" name="action" value="remove" value=""/>
        </form>

    <?php } ?>

    <?php if ($discountsWithCodesExist && $cart) { ?>
        <h3><?= t('Enter Discount Code'); ?></h3>
        <form method="post" action="<?= \URL::to('/cart/'); ?>" class="form-inline">
            <div class="form-group">
                <input type="text" class="store-cart-page-discount-field form-control" name="code" placeholder="<?= t('Code'); ?>" />
            </div>
            <input type="hidden" name="action" value="code"/>
            <button type="submit" class="store-cart-page-discount-apply btn btn-default"><?= t('Apply'); ?></button>
        </form>
    <?php } ?>

    <?php if ($codesuccess) { ?>
        <p><?= t('Discount has been applied'); ?></p>
    <?php } ?>

    <?php if ($codeerror) { ?>
        <p><?= t('Invalid code'); ?></p>
    <?php } ?>


    <?php if ($cart && !empty($cart)) { ?>
        <p class="store-cart-page-cart-total text-right">
            <strong class="cart-grand-total-label"><?= t("Items Sub Total") ?>:</strong>
            <span class="cart-grand-total-value"><?= StorePrice::format($subTotal) ?></span>
        </p>

        <?php if ($shippingEnabled) { ?>
<!--            <p class="store-cart-page-shipping text-right"><strong><?= t("Shipping") ?>:</strong>
                <span id="store-shipping-total">
                    <?= $shippingtotal !== false ? ($shippingtotal > 0 ? StorePrice::format($shippingtotal) : t('No Charge')) : t('to be determined'); ?>
                </span></p>-->
        <?php } ?>

        <?php if (!empty($discounts)) { ?>

            <p class="store-cart-page-discounts text-right">
                <strong><?= (count($discounts) == 1 ? t('Discount Applied') : t('Discounts Applied')); ?>
                    :</strong>
                <?php
                $discountstrings = array();
                foreach ($discounts as $discount) {
                    $discountstrings[] = h($discount->getDisplay());
                }
                echo implode(', ', $discountstrings);
                ?>
            </p>

        <?php } ?>
            
        <?php
        if ($taxtotal > 0) {
            echo '<p class="store-cart-page-cart-total text-right">';
            foreach ($taxes as $tax) {                
                if ($tax['taxamount'] > 0) { ?>
                    <strong class="store-cart-grand-total-label"><?= ($tax['name'] ? $tax['name'] : t("Tax")) ?>:</strong>
                    <span class="store-cart-grand-total-value"><?= StorePrice::format($tax['taxamount']); ?></span>
                    </li>
                <?php }
            }
            echo '</p>';
        }
        ?>
        
        <p class="store-cart-page-cart-total text-right">
            <strong class="store-cart-grand-total-label"><?= t("Total") ?>:</strong>
            <span class="store-cart-grand-total-value"><?= StorePrice::format($total) ?></span>
        </p>    
<!--        <p class="text-right">
            <small>*Orders below <strong>AED 500</strong>, Delivery charge <strong>AED 50 </strong>is applied.</small>
        </p>-->

        <div class="store-cart-page-cart-links pull-right">
            <a class="store-btn-cart-page-checkout btn btn-primary"
               href="<?= \URL::to('/checkout') ?>"><?= t('Checkout') ?>
            </a>
        </div>        
    <?php } else { ?>
        <p class="alert alert-info"><?= t('Your cart is empty'); ?></p>
        <a href="/product">Continue Shopping</a>
    <?php } ?>

</div>
<script type="text/javascript">

    $(document).ready(function () {
        $(".slide-toggle").click(function () {
            var $this = $(this);
            $this.toggleClass('slide-toggle');
            if ($this.hasClass('slide-toggle')) {
                $this.text('Get your online quote');
            } else {
                $this.text('x');
                $('.box-inner-form').css("display", "block");
            }
            $(".box").animate({
                width: "toggle"
            });
        });

        $('#qte').submit(function () {
            $('.box-inner-form').css("display", "none");
            $('.getquoteh2').css("display", "block");
            setTimeout(function () {
                $('#qte')[0].reset();
            }, 3000);
        });
    });


<?php /* $('#myModal').on('hidden.bs.modal', function () {
  $('.modal-content').find('input:text, input[name="email"], input:password,input:hidden, input:file, select, textarea').val('');
  $('.modal-form').show();
  $('.modal-body h2').hide();
  })

  $('#qte').submit(function(){
  $('.modal-form').hide();
  $('.modal-body h2').show();
  }) */ ?>
</script>
<style>

    .box{
        float:left;
        overflow: hidden;
    }
    .box-inner{
        width: 400px;
        padding: 10px;
        border: 1px solid #000;	
    }

    @media only screen and (min-width:320px) and (max-width:425px) {
        .box-inner{
            width: 250px;
            padding: 10px;
            border: 1px solid #000;	
        }
        .form-control{
            padding: 6px 3px;	
        }
    }

</style>

<script>
function updateQuantity(){
    $(".store-btn-cart-list-update").trigger("click");
}

window.setTimeout(function() {
    $(".alert-success, .alert-warning").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 2000);

$("#qte").submit(function(){
    jQuery.support.cors = true;
    var formData = '631421_81406pi_631421_81406='+$("#firstname").val();
    formData += '&631421_81408pi_631421_81408='+$("#lname").val();
    formData += '&631421_81410pi_631421_81410='+$("#cname").val();
    formData += '&631421_81412pi_631421_81412='+$("#email").val();
    formData += '&631421_81414pi_631421_81414='+$("#pnumber").val();
    formData += '&631421_81416pi_631421_81416='+$("#address").val();
    $.post('https://www2.duconodl.com/l/631421/2020-07-21/2zn55', formData, function(res){
    });
});
</script>
