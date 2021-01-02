<?php defined('C5_EXECUTE') or die(_("Access Denied."));
$ih = Core::make('helper/image');
$view->requireAsset('javascript','mamaorganica');
 ?>
<div class="row filteredProduct">

<?php 

$showPageLink = true;
$showAddToCart=true;

    foreach($products as $product){

        $options = $product->getOptions();

        if ($product->hasVariations()) {
            $variations = StoreProductVariation::getVariationsForProduct($product);

            $variationLookup = array();

            if (!empty($variations)) {
                foreach ($variations as $variation) {
                    // returned pre-sorted
                    $ids = $variation->getOptionItemIDs();
                    $variationLookup[implode('_', $ids)] = $variation;
                }
            }
        }

        //this is done so we can get a type of active class if there's a product list on the product page
        if(Page::getCurrentPage()->getCollectionID()==$product->getPageID()){
            $activeclass =  'on-product-page';
        }

    ?>
    
        <div class="store-product-list-item col-md-4 col-sm-6 col-xs-12 <?= $activeclass; ?>">
          <div class="add_block">
            <form   id="store-form-add-to-cart-list-<?= $product->getID()?>">
                
                <?php 
                    $imgObj = $product->getImageObj();
                    if(is_object($imgObj)){
                        $thumb = $ih->getThumbnail($imgObj,150,150,false);?>
                        
                            <?php if($showQuickViewLink){ ?>
                              
                                    <div class="img"><a href="<?= \URL::to(Page::getByID($product->getPageID()))?>" class="img-link"><img src="<?= $thumb->src?>" class="img-responsive"></a></div>
                               
                            <?php } elseif ($showPageLink) { ?>
                              
                                    <div class="img"><a href="<?= \URL::to(Page::getByID($product->getPageID()))?>" class="img-link"><img src="<?= $thumb->src?>" class="img-responsive"></a></div>
                               
                            <?php } else { ?>
                                <div class="img"><a href="<?= \URL::to(Page::getByID($product->getPageID()))?>" class="img-link"><img src="<?= $thumb->src?>" class="img-responsive"></a></div>
                            <?php } ?>
                        
                <?php
                    }// if is_obj
                ?>
                <h6><a href="<?= \URL::to(Page::getByID($product->getPageID()))?>" class="h6-link"><?= $product->getName()?></a></h6>
               
                    <?php
                        $salePrice = $product->getSalePrice();
                        if(isset($salePrice) && $salePrice != ""){
                            echo '<span class="sale-price">'.$product->getFormattedSalePrice().'</span>';
                            echo ' ' . t('was') . ' ' . '<span class="original-price">'.$product->getFormattedOriginalPrice().'</span>';
                        } else {
                            echo '<span>'.$product->getFormattedPrice().'</span>';
                        }
                    ?>
               
                <?php if($showDescription){ ?>
                <div class="store-product-list-description"><?= $product->getDesc()?></div>
                <?php } ?>
                <?php if($showPageLink){?>
              <div class="brk">  <a  href="<?= \URL::to(Page::getByID($product->getPageID()))?>" class="more_det"><?= t("More Details")?></a></div>
                <?php } ?>
                <?php if($showAddToCart){ ?>

                <?php if ($product->allowQuantity() && $showQuantity) { ?>
                    <div class="store-product-quantity form-group">
                        <label class="store-product-option-group-label"><?= t('Quantity') ?></label>
                        <input type="number" name="quantity" class="store-product-qty form-control" value="1" min="1" step="1">
                    </div>
                <?php } else { ?>
                    <input type="hidden" name="quantity" class="store-product-qty" value="1">
                <?php } ?>

                <?php
                foreach($options as $option) {/*
                    $optionItems = $option->getOptionItems();
                    ?>
                    <?php if (!empty($optionItems)) { ?>
                        <div class="store-product-option-group form-group">
                            <label class="store-option-group-label"><?= $option->getName() ?></label>
                            <select class="form-control" name="po<?= $option->getID() ?>">
                                <?php
                                foreach ($optionItems as $optionItem) {
                                    if (!$optionItem->isHidden()) { ?>
                                    <option value="<?= $optionItem->getID() ?>"><?= $optionItem->getName() ?></option>
                                    <?php }
                                    // below is an example of a radio button, comment out the <select> and <option> tags to use instead
                                    //echo '<input type="radio" name="po'.$option->getID().'" value="'. $optionItem->getID(). '" />' . $optionItem->getName() . '<br />'; ?>
                                <?php } ?>
                            </select>
                        </div>
                    <?php }
                */}?>

                <input type="hidden" name="pID" value="<?= $product->getID()?>">


               <div class="brk"> <a data-add-type="list" data-product-id="<?= $product->getID()?>" href="javascript:void(0);" class="store-btn-add-to-cart <?= ($product->isSellable() ? '' : 'hidden');?> "><?=  ($btnText ? h($btnText) : t("Add to Bag"))?></a></div>
                <p class="store-out-of-stock-label alert alert-warning <?= ($product->isSellable() ? 'hidden' : '');?>"><?= t("Out of Stock")?></p>

                <?php } ?>

                <?php if ($product->hasVariations() && !empty($variationLookup)) {?>
                    <script>
                        $(function() {
                            <?php
                            $varationData = array();
                            foreach($variationLookup as $key=>$variation) {
                                $product->setVariation($variation);

                                $imgObj = $product->getImageObj();

                                if ($imgObj) {
                                    $thumb = Core::make('helper/image')->getThumbnail($imgObj,400,280,true);
                                }

                                $varationData[$key] = array(
                                'price'=>$product->getFormattedOriginalPrice(),
                                'saleprice'=>$product->getFormattedSalePrice(),
                                'available'=>($variation->isSellable()),
                                'imageThumb'=>$thumb ? $thumb->src : '',
                                'image'=>$imgObj ? $imgObj->getRelativePath() : '');

                            } ?>


                            $('#store-form-add-to-cart-list-<?= $product->getID()?> select').change(function(){
                                var variationdata = <?= json_encode($varationData); ?>;
                                var ar = [];

                                $('#store-form-add-to-cart-list-<?= $product->getID()?> select').each(function(){
                                    ar.push($(this).val());
                                })

                                ar.sort();

                                var pli = $(this).closest('.store-product-list-item');

                                if (variationdata[ar.join('_')]['saleprice']) {
                                    var pricing =  '<span class="store-sale-price">'+ variationdata[ar.join('_')]['saleprice']+'</span>' +
                                        ' <?= t('was');?> ' + '<span class="store-original-price">' + variationdata[ar.join('_')]['price'] +'</span>';

                                    pli.find('.store-product-list-price').html(pricing);

                                } else {
                                    pli.find('.store-product-list-price').html(variationdata[ar.join('_')]['price']);
                                }

                                if (variationdata[ar.join('_')]['available']) {
                                    pli.find('.store-out-of-stock-label').addClass('hidden');
                                    pli.find('.store-btn-add-to-cart').removeClass('hidden');
                                } else {
                                    pli.find('.store-out-of-stock-label').removeClass('hidden');
                                    pli.find('.store-btn-add-to-cart').addClass('hidden');
                                }

                                if (variationdata[ar.join('_')]['imageThumb']) {
                                    var image = pli.find('.store-product-list-thumbnail img');

                                    if (image) {
                                        image.attr('src', variationdata[ar.join('_')]['imageThumb']);

                                    }
                                }

                            });
                        });
                    </script>
                <?php } ?>

            </form><!-- .product-list-item-inner -->
            </div>
        </div><!-- .product-list-item -->
        
        <?php 
           
	} ?>
    </div>
    