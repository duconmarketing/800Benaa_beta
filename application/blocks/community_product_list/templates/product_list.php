<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductVariation\ProductVariation as StoreProductVariation;

$c = Page::getCurrentPage();

if ($products) {


    // echo '<div class="store-product-list row store-product-list-per-row-'. $productsPerRow .'">';

    $i = 1;
    foreach ($products as $product) {


        $options = $product->getOptions();
        //echo $product->getSKU();die('fsdf');
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
        if ($c->getCollectionID() == $product->getPageID()) {
            $activeclass = 'on-product-page';
        }
        ?>

        <div class="store-product-list-item col-md-4 col-sm-4 col-xs-6">
            <div class="product_item pro hvr-float">
                <form   id="store-form-add-to-cart-list-<?= $product->getID() ?>" data-product-id="<?= $product->getID() ?>">
                    <?php
                    $imgObj = $product->getImageObj();
                    if (is_object($imgObj)) {
                        $thumb = $ih->getThumbnail($imgObj, 300, 280, true);
                        ?>
                        <?php if ($showQuickViewLink) { ?>
                            <a class="store-product-quick-view" data-product-id="<?= $product->getID() ?>" href="#"> <img src="<?= $thumb->src ?>" class="img-responsive" alt="<?= $product->getName() ?>"> </a>
                        <?php } elseif ($showPageLink) { ?>
                            <a href="<?= ($product->getAttribute('redirect_to_site') && $product->getAttribute('redirect_url') != '' ? $product->getAttribute('redirect_url') : \URL::to(Page::getByID($product->getPageID()))) ?>"> <img data-src="<?= $thumb->src ?>" class="lazy img-responsive" alt="<?= $product->getName() ?>"> </a>
                        <?php } else { ?>
                            <img src="<?= $thumb->src ?>" class="img-responsive" alt="<?= $product->getName() ?>">
                        <?php } ?>
                        <?php
                    }// if is_obj
                    ?>
                    <?php
                    $groups = $product->getGroups();
                    $brndName = '';
                    foreach ($groups as $group) {
                        $brndName = $group->getGroup()->getGroupName();
                    }
                    //echo $brndName; 
                    //die;

                    if ($brndName == 'Topex') {
                        ?>
                        <img src="<?php echo $this->getThemePath() ?>/images/topex.png" class="img-responsive brand_img">
                        <?php
                    } elseif ($brndName == "Phocee'nne") {

                        // echo 'sdfksgfkhgs';
                        ?>
                        <img src="<?php echo $this->getThemePath() ?>/images/phoceenne.png" class="img-responsive brand_img">
                    <?php } elseif ($brndName == "EMC") { ?>
                        <img src="<?php echo $this->getThemePath() ?>/images/emc.png" class="img-responsive brand_img">
                    <?php } elseif ($brndName == "Sanshe") { ?>
                        <img src="<?php echo $this->getThemePath() ?>/images/sanshe.png" class="img-responsive brand_img">
                    <?php } else { ?>

                    <?php } ?>
                    <div class="product_des">
                        <?php if ($showName) { ?>
                            <h5><a class="a_title" title="<?= $product->getName() ?>" href="<?= ($product->getAttribute('redirect_to_site') ? $product->getAttribute('redirect_url') : \URL::to(Page::getByID($product->getPageID()))) ?>">
                                    <?= $product->getName() ?>
                                </a></h5>
                        <?php } ?>
                        <?php
                        if ($product->getAttribute('floating_price')) {
                            echo '<a href="tel:80023622"><h6> CONTACT US </h6></a>';
                        } else {
                            ?>
                            <?php
                            if ($product->getAttribute('show_price_list')) {

                                if ($product->getAttribute('show_unit_prize')) {
                                    ?>
                                    <h6> AED <?php echo $product->getAttribute('unit_price') ?> <span style="font-size: 12px;color: #000;text-transform: none;font-weight: normal;">Ex VAT</span></h6>
                                    <?php
                                }
                            } else {
                                ?>
                                <h6>
                                    <?php
                                    $salePrice = $product->getSalePrice();
                                    if (isset($salePrice) && $salePrice != "") {
                                        echo $product->getFormattedSalePrice() . '<span style="font-size: 14px;color: #000;text-transform: none;font-weight:normal;"> Ex VAT</span><br>';
                                        echo '<span class="store-original-price" style="color: #999;font-size: 13px;">' . $product->getFormattedOriginalPrice() . '</span>';
                                    } else {
                                        echo $product->getFormattedPrice() . '<span style="font-size: 12px;color: #000;text-transform: none;font-weight:normal;"> Ex VAT</span>';
                                    }
                                    ?>
                                </h6>
                            <?php } ?>
                        <?php } ?>
                        <?php
                        if ($product->getSKU()) {
                            ?>
                            <p> ID: <span><?php echo $product->getSKU(); ?></span></p>
                        <?php } ?>
                    </div>
                    <?php if ($showAddToCart) {
                        $minQty = $product->getAttribute('minimum_qty');
                        $minQty = ($minQty < 1) ? 1 : $minQty;
                        ?>
                        <?php if ($product->allowQuantity() && $showQuantity) { ?>
                            <div class="store-product-quantity form-group">
                                <label class="store-product-option-group-label">
                                    <?= t('Quantity') ?>
                                </label>
                                <input type="number" name="quantity" class="store-product-qty form-control" value="<?= $minQty?>" min="<?= $minQty ?>" step="1">
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="quantity" class="store-product-qty" value="<?= $minQty?>">
                        <?php } ?>
                            
                        <input type="hidden" name="pID" value="<?= $product->getID() ?>">
                        <?php if ($product->getAttribute('redirect_to_site') && $product->getAttribute('redirect_url') != '') { ?>
                            <p class="store-btn-add-to-cart-container">
                                <a href="<?= $product->getAttribute('redirect_url') ?>" class="btn btn-primary" style="color: #FFF;">
                                    <?= t("Go to Website") ?>
                                </a>
                            </p>
                        <?php } else { ?>
                            <p class="store-btn-add-to-cart-container">
                                <button data-add-type="list" data-product-id="<?= $product->getID() ?>" class="store-btn-add-to-cart btn btn-primary <?= ($product->isSellable() ? '' : 'hidden'); ?> ">
                                    <?= ($btnText ? h($btnText) : t("Add to Cart")) ?>
                                </button>
                            </p>
                            <p class="store-out-of-stock-label alert-warning <?= ($product->isSellable() ? 'hidden' : ''); ?>">
                                <a class="btn btn-primary" style="cursor: default; background-color: white; color: #000;">
                                    <?= t("Out of Stock") ?>
                                </a>
                            </p>
                        <?php }
                    }
                    ?>
                    <a href="<?= ($product->getAttribute('redirect_to_site') && $product->getAttribute('redirect_url') != '' ? $product->getAttribute('redirect_url') : \URL::to(Page::getByID($product->getPageID()))) ?>" class="pro_but" title="<?= $product->getName() ?>">More Details</a>
                        <?php if ($showPrice) { ?>
                        <p class="store-product-list-price">
                            <?php
                            $salePrice = $product->getSalePrice();
                            if (isset($salePrice) && $salePrice != "") {
                                echo '<span class="store-sale-price">' . $product->getFormattedSalePrice() . '</span>';
                                echo ' ' . t('was') . ' ' . '<span class="store-original-price">' . $product->getFormattedOriginalPrice() . '</span>';
                            } else {
                                echo $product->getFormattedPrice();
                            }
                            ?>
                        </p>
                    <?php } ?>
                        <?php if ($product->allowCustomerPrice()) { ?>
                        <div class="store-product-customer-price-entry form-group">
                            <?php
                            $pricesuggestions = $product->getPriceSuggestionsArray();
                            if (!empty($pricesuggestions)) {
                                ?>
                                <p class="store-product-price-suggestions">
                                        <?php foreach ($pricesuggestions as $suggestion) { ?>
                                        <a href="#" class="store-price-suggestion btn btn-default btn-sm" data-suggestion-value="<?= $suggestion; ?>" data-add-type="list">
                                        <?= Config::get('community_store.symbol') . $suggestion; ?>
                                        </a>
                <?php } ?>
                                </p>
                                <label for="customerPrice" class="store-product-customer-price-label">
                                <?= t('Enter Other Amount') ?>
                                </label>
                                <?php } else { ?>
                                <label for="customerPrice" class="store-product-customer-price-label">
                                <?= t('Amount') ?>
                                </label>
                            <?php } ?>
                            <?php $min = $product->getPriceMinimum(); ?>
            <?php $max = $product->getPriceMaximum(); ?>
                            <div class="input-group col-md-6 col-sm-6 col-xs-6">
                                <div class="input-group-addon">
            <?= Config::get('community_store.symbol'); ?>
                                </div>
                                <input type="number" <?= $min ? 'min="' . $min . '"' : ''; ?>  <?= $max ? 'max="' . $max . '"' : ''; ?>class="store-product-customer-price-entry-field form-control" value="<?= $product->getPrice(); ?>" name="customerPrice" />
                            </div>
                                <?php if ($min >= 0 || $max > 0) { ?>
                                <span class="store-min-max help-block">
                                    <?php
                                    if (!is_null($min)) {
                                        echo t('minimum') . ' ' . Config::get('community_store.symbol') . $min;
                                    }

                                    if (!is_null($max)) {
                                        if ($min >= 0) {
                                            echo ', ';
                                        }
                                        echo t('maximum') . ' ' . Config::get('community_store.symbol') . $max;
                                    }
                                    ?>
                                </span>
                        <?php } ?>
                        </div>
                    <?php } ?>
                        <?php if ($showDescription) { ?>
                        <div class="store-product-list-description">
                        <?= $product->getDesc() ?>
                        </div>
                    <?php } ?>
        <?php if ($product->hasVariations() && !empty($variationLookup)) { ?>
                        <script>
                            $(function () {
            <?php
            $varationData = array();
            foreach ($variationLookup as $key => $variation) {
                $product->setVariation($variation);

                $imgObj = $product->getImageObj();

                if ($imgObj) {
                    $thumb = Core::make('helper/image')->getThumbnail($imgObj, 400, 280, true);
                }

                $varationData[$key] = array(
                    'price' => $product->getFormattedOriginalPrice(),
                    'saleprice' => $product->getFormattedSalePrice(),
                    'available' => ($variation->isSellable()),
                    'imageThumb' => $thumb ? $thumb->src : '',
                    'image' => $imgObj ? $imgObj->getRelativePath() : '');
            }
            ?>


                                $('#store-form-add-to-cart-list-<?= $product->getID() ?> select').change(function () {
                                    var variationdata = <?= json_encode($varationData); ?>;
                                    var ar = [];

                                    $('#store-form-add-to-cart-list-<?= $product->getID() ?> select').each(function () {
                                        ar.push($(this).val());
                                    });

                                    ar.sort(communityStore.sortNumber);

                                    var pli = $(this).closest('.store-product-list-item');

                                    if (variationdata[ar.join('_')]['saleprice']) {
                                        var pricing = '<span class="store-sale-price">' + variationdata[ar.join('_')]['saleprice'] + '</span>' +
                                                ' <?= t('was'); ?> ' + '<span class="store-original-price">' + variationdata[ar.join('_')]['price'] + '</span>';

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
                </form>
            </div>
            <!-- .product-list-item-inner --> 
        </div>
        <!-- .product-list-item -->

        <?php
        $i++;
    }// foreach    
    // echo "</div><!-- .product-list -->";

    if ($showPagination) {

        if ($paginator->getTotalPages() > 1) {
            echo '<div class="row">';
            echo $pagination;
            echo '</div>';
        }
        ?>
        <?php /* ?><div class="more-loader" style="display:none"></div>
          <div class="row paging_cover" <?php if ($paginator->getTotalPages() == 1) {  ?>style="display:none"<?php } ?>>
          <div class="col-md-12 col-sm-12 col-xs-12 ">
          <a href="javascript:void(0);" data-page="2" data-items="<?= $controller->maxProducts; ?>" data-has-filter="false" class="load_more"><i class="fa fa-arrow-circle-down" aria-hidden="true"></i> Load More...</a>
          </div>
          </div><?php */ ?>
        <?php
    }
} //if products
else {
    ?>
    <div class="row filteredProduct">
        <p class="alert alert-info">
    <?= t("No Products Available in this category") ?>
        </p>
    </div>
<?php } ?>
<script>
    $(document).ready(function () {
        $('.load_more').on('click', function () {
            $('.more-loader').show();

            var ccm_paging_p = $(this).data('page');
            var itemsPerPage = $(this).data('items');
            var hasFilterEnabled = $(this).data('has-filter');
            if (hasFilterEnabled) {
                runFilter(ccm_paging_p);
            } else {

                $.ajax({
                    url: '<?= URL::to('/product/ajax/controller/loadmore'); ?>',
                    data: {'ccm_paging_p': ccm_paging_p, 'itemsPerPage': itemsPerPage, 'filter': '<?= $controller->filter; ?>', 'filter': '<?= $controller->filter; ?>', 'filterCID': '<?= $controller->filterCID; ?>'},
                    success: function ($response) {
                        $('.more-loader').hide();
                        //process response
                        var data = $.parseJSON($response);
                        //	console.log();
                        loadMore.renderMore(data.products);
                        if (data.hasNextPage) {
                            $('.load_more').data('page', data.nextPage);
                        } else {
                            $('.paging_cover').hide();
                        }
                    },
                    error: function ($response) {
                        $('.more-loader').hide();
                    },
                    method: 'GET'
                });

            }


            $(document).ajaxComplete(function () {
                // This fires when clicking Load More on AJAX lists.  
                // Set font size for long headings
                $('.store-product-list-item').each(function () {
                    var h6Link = $(this).find('h6 a');
                    var linkTxt = $(h6Link).text();
                    if (linkTxt.length > 50 && linkTxt.length < 61) {
                        $(h6Link).addClass('medium');
                    } else if (linkTxt.length > 60 && linkTxt.length < 81) {
                        $(h6Link).addClass('small');
                    } else if (linkTxt.length > 80) {
                        $(h6Link).addClass('ex-small');
                    }

                });

                //Set product/image heights if a filter is not applied
                /*if(!$('.left_bar').find('.active').length) {
                 $('.right-col').each(function(){  
                 // Cache the highest
                 var highestBox = 0;
                 
                 // Select and loop the elements you want to equalise
                 $(this).find('.add_block').each(function(){
                 // If this box is higher than the cached highest then store it
                 if($(this).height() > highestBox) {
                 highestBox = $(this).height(); 
                 }
                 });  	
                 // Set the height of all those children to whichever was highest 
                 $('.add_block',this).height(highestBox);
                 
                 
                 // Cache the highest
                 var highestImg = 0;
                 
                 // Select and loop the elements you want to equalise
                 $(this).find('.img').each(function(){
                 // If this box is higher than the cached highest then store it
                 if($(this).height() > highestImg) {
                 highestImg = $(this).height(); 
                 }
                 });  	
                 // Set the height of all those children to whichever was highest 
                 $(this).find('.img').height(highestImg);		
                 }); 
                 
                 };*/

            });


        });
    });
</script> 
