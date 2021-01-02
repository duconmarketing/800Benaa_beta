<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
$defaultimagewidth = 470;
$defaultimageheight = 470;

if (is_object($product) && $product->isActive()) {
    ?>

    <form class="store-product store-product-block" id="store-form-add-to-cart-<?= $product->getID() ?>" data-product-id="<?= $product->getID() ?>" itemscope itemtype="http://schema.org/Product">
        <div class="row">
            <?php if ($showImage) { ?>
                <div class="store-product-image col-md-6">

                    <div class="product_img">
                        <?php
                        $page=Page::getByID($product->getPageID());  
                        $parent_page = Page::getByID($page->getCollectionParentID());
                        $imgObj = $product->getImageObj();
                        $popImg = $product->getAttribute('image_popup');
                        if (is_object($imgObj)) {
                            $thumb = Core::make('helper/image')->getThumbnail($imgObj, $defaultimagewidth, $defaultimageheight, false);
                            ?>
                            <a itemprop="image" href="<?php
                            if ($imgObj) {
                                echo $imgObj->getRelativePath();
                            }
                            ?>"
                               title="<?= h($product->getName()); ?>" class="store-product-thumb text-center center-block"> <img class="xzoom" src="<?= $thumb->src ?>" xoriginal="<?php
                                   if ($imgObj) {
                                       echo $imgObj->getRelativePath();
                                   }
                                   ?>"> </a>
                            <?php } ?>

                        <?php ?><?php
                        $images = $product->getImagesObjects();
                        if (count($images) == 0) {
                            $groups = $product->getGroups();
                            foreach ($groups as $group) {
                                $brndName = $group->getGroup()->getGroupName();
                            }
                            //echo $brndName; 
                            //die;

                            if ($brndName == 'Topex') {
                                ?>
                                <img src="<?php echo $this->getThemePath() ?>/images/topex.png" class="img-responsive brand_det">
                                <?php
                            } elseif ($brndName == "Phocee'nne") {

                                // echo 'sdfksgfkhgs';
                                ?>
                                <img src="<?php echo $this->getThemePath() ?>/images/phoceenne.png" class="img-responsive brand_det">
                            <?php } elseif ($brndName == "EMC") { ?>
                                <img src="<?php echo $this->getThemePath() ?>/images/emc.png" class="img-responsive brand_det">
                            <?php } elseif ($brndName == "Sanshe") { ?>
                                <img src="<?php echo $this->getThemePath() ?>/images/sanshe.png" class="img-responsive brand_det">
                            <?php } else { ?>

                                <?php
                            }
                        }
                        ?><?php ?>
                    </div>

                    <?php
                    $images = $product->getImagesObjects();
                    if (count($images) > 0) {
                        $loop = 1;
                        echo '<div class="store-product-additional-images clearfix no-gutter">';

                        foreach ($images as $secondaryimage) {
                            if (is_object($secondaryimage)) {
                                $thumb = Core::make('helper/image')->getThumbnail($secondaryimage, $defaultimagewidth, $defaultimageheight, true);
                                ?>
                                <div class="store-product-additional-image  xzoom-thumbs">
                                    <a rel="<?= $secondaryimage->getRelativePath() ?> " href="javascript:void(0)" title="<?= h($product->getName()); ?>" class="">
                                        <img class="xzoom-gallery" src="<?= $thumb->src ?>"  class="img-responsive"/>


                                        <?php
                                        if ($loop == 1) {
                                            $groups = $product->getGroups();
                                            foreach ($groups as $group) {
                                                $brndName = $group->getGroup()->getGroupName();
                                            }
                                            //echo $brndName; 
                                            //die;

                                            if ($brndName == 'Topex') {
                                                ?>
                                                <img class="xzoom-gallery" style="position:absolute;top:0px;left:10px;" src="<?php echo $this->getThemePath() ?>/images/topex.png" class="img-responsive brand_det">
                                                <?php
                                            } elseif ($brndName == "Phocee'nne") {

                                                // echo 'sdfksgfkhgs';
                                                ?>
                                                <img class="xzoom-gallery" style="position:absolute;top:0px;left:10px;" src="<?php echo $this->getThemePath() ?>/images/phoceenne.png" class="img-responsive brand_det">
                                            <?php } elseif ($brndName == "EMC") { ?>
                                                <img class="xzoom-gallery" style="position:absolute;top:0px;left:10px;" src="<?php echo $this->getThemePath() ?>/images/emc.png" class="img-responsive brand_det">
                                            <?php } elseif ($brndName == "Sanshe") { ?>
                                                <img class="xzoom-gallery" style="position:absolute;top:0px;left:55px;" src="<?php echo $this->getThemePath() ?>/images/sanshe.png" class="img-responsive brand_det">
                                            <?php } else { ?>

                                                <?php
                                            }
                                        }
                                        ?>



                                    </a>
                                </div>
                                <?php
                            }

                            //if ($loop > 0 && $loop % 2 == 0 && count($images) > $loop) {
                            // echo '</div><div class="clearfix no-gutter">';
                            //}
                            $loop++;
                        }
                        echo '</div>';
                    }
                    ?> 

                </div>
            <?php } ?>
            <div class="store-product-details col-md-6">
                <div class="pro_det">
                    <?php if ($showProductName) { ?>
                        <h2 class="store-product-name" itemprop="name">
                            <?= utf8_encode($product->getName()); ?>
                            <p style="    font-size: 15px;display: block;color: #999;text-transform: uppercase;">
                                Item Code: <?= $product->getSKU() ?>
                            </p>
                        </h2>
                        <meta itemprop="sku" content="<?= $product->getSKU() ?>" />
                    <?php } ?>

                    <?php
                    $groups = $product->getGroups();
                    foreach ($groups as $group) {
                        //echo $group->getGroup()->getGroupName();
                    }
                    ?>




                    <?php if ($showProductPrice && !$product->allowCustomerPrice()) { ?>
                        <meta itemprop="priceCurrency" content="<?= Config::get('community_store.currency'); ?>" />
                        <?php
                        $unit = '';
                        $count = '';
                        if ($product->getAttribute('unit_per_truck')) {
                            $unit = ' / Truck';
                        }
                        if ($product->getAttribute('number_of_piece')) {
                            $count = ' (' . $product->getAttribute('number_of_piece') . ' Pcs.)';
                        }
                        if ($product->getAttribute('show_unit') && $product->getAttribute('unit_text')) {
                            $unit = ' / ' . $product->getAttribute('unit_text');
                        }

                        $salePrice = $product->getSalePrice();
                        if ($product->getAttribute('floating_price')) {
                            echo '<a href="tel:80023622"><h3> CONTACT US </h3></a><br>';
                        } else {
                            if (isset($salePrice) && $salePrice != "") {
                                echo '<h3>' . $product->getFormattedSalePrice() . $unit . $count . '<span style="font-size: 14px;color: #000;text-transform: none;font-weight:normal;"> Ex VAT</span><br>';
                                //echo '&nbsp;'.t('was').'&nbsp;';
                                echo '<span class="store-original-price" style="text-decoration: line-through;color:#999;font-size: 16px;">' . $product->getFormattedOriginalPrice() . '</span></h3>';
                                echo '<meta itemprop="price" content="' . $product->getSalePrice() . '" />';
                            } else {
                                // echo '<h3>'.$product->getFormattedPrice().'</h3>';
                                echo '<h3>AED ' . $product->getPrice() . $unit . $count . '<span style="font-size: 12px;color: #000;text-transform: none;font-weight:normal;"> Ex VAT</span></h3>';
                                echo '<meta itemprop="price" content="' . $product->getPrice() . '" />';
                            }
                        }
                        ?>
                    <?php } ?><p>
                        <?php if ($product->getAttribute('show_unit_prize')) { ?>
                            AED <?php echo $product->getAttribute('unit_price') ?> / <?php echo $product->getAttribute('unit_text') ?> 
                        <?php } ?></p>
                    <?php if ($product->getDetail() || $product->getAttribute('product_description')) { ?>
                        <div class="pro_detail">
                            <div id="exTab3">
                                <ul  class="nav nav-pills">
                                    <?php if ($product->getDetail()) { ?><li class="active"> <a  href="#1b" data-toggle="tab">Specification</a> </li><?php } ?>
                                    <?php if ($product->getAttribute('product_description')) { ?><li><a href="#2b" data-toggle="tab">Description</a> </li><?php } ?>
                                </ul>
                                <div class="tab-content clearfix">
                                    <?php if ($product->getDetail()) { ?><div class="tab-pane active" id="1b">
                                        <?= $product->getDetail() ?>
                                        </div><?php } ?>
                                    <?php if ($product->getAttribute('product_description')) { ?><div class="tab-pane" id="2b">
                                        <?php echo $product->getAttribute('product_description'); ?>
                                        </div><?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($product->isSellable()) { ?>
                        <p><i class="fa fa-shopping-cart" aria-hidden="true"></i> Available in stock</p>
                    <?php } ?>
                    <?php if ($product->getAttribute('free_delivery')) { ?>
                        <!--<p><i class="fa fa-truck" aria-hidden="true"></i> Free Delivery <font style="font-weight:normal;">*</font></p>-->
                    <?php } ?>
                    <?php if ($product->allowCustomerPrice()) { ?>
                        <div class="store-product-customer-price-entry form-group">
                            <?php
                            $pricesuggestions = $product->getPriceSuggestionsArray();
                            if (!empty($pricesuggestions)) {
                                ?>
                                <p class="store-product-price-suggestions">
                                    <?php foreach ($pricesuggestions as $suggestion) { ?>
                                        <a href="#" class="store-price-suggestion btn btn-default btn-sm" data-add-type="list" data-suggestion-value="<?= $suggestion; ?>">
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
                    <meta itemprop="description" content="<?= strip_tags($product->getDesc()); ?>" />
                    <?php if ($showProductDescription) { ?>
                        <div class="store-product-description">
                            <?= $product->getDesc() ?>
                        </div>
                    <?php } ?>
                    <?php if ($showDimensions) { ?>
                        <div class="store-product-dimensions"> <strong>
                                <?= t("Dimensions") ?>
                                :</strong>
                            <?= $product->getDimensions() ?>
                            <?= Config::get('community_store.sizeUnit'); ?>
                        </div>
                    <?php } ?>
                    <?php if ($showWeight) { ?>
                        <div class="store-product-weight"> <strong>
                                <?= t("Weight") ?>
                                :</strong>
                            <?= $product->getWeight() ?>
                            <?= Config::get('community_store.weightUnit'); ?>
                        </div>
                    <?php } ?>
                    <?php if ($showGroups && false) { ?>
                        <ul>
                            <?php
                            $productgroups = $product->getGroups();
                            foreach ($productgroups as $pg) {
                                ?>
                                <li class="store-product-group">
                                    <?= $pg->getGroup()->getGroupName(); ?>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                    <?php
                    if ($showIsFeatured) {
                        if ($product->isFeatured()) {
                            ?>
                            <span class="store-product-featured">
                                <?= t("Featured Item") ?>
                            </span>
                            <?php
                        }
                    }
                    ?>
                    <div class="store-product-options" id="product-options-<?= $bID; ?>">
                        <?php if ($product->allowQuantity() && $showQuantity) { ?>
                            <div class="store-product-quantity form-group">
                                <label class="store-product-option-group-label">
                                    <?= t('Quantity') ?>
                                </label>
                                <input type="number" name="quantity" class="store-product-qty form-control" value="1" min="1" step="1" style="width:20%;">
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="quantity" class="store-product-qty" value="1">
                        <?php } ?>
                        <?php
                        foreach ($product->getOptions() as $option) {
                            $optionItems = $option->getOptionItems();
                            $optionType = $option->getType();
                            $required = $option->getRequired();

                            $requiredAttr = '';

                            if ($required) {
                                $requiredAttr = ' required="required" placeholder="' . t('Required') . '" ';
                            }
                            ?>
                            <?php if (!$optionType || $optionType == 'select') { ?>
                                <div class="store-product-option-group form-group <?= $option->getHandle() ?>">
                                    <label class="store-product-option-group-label">
                                        <?= $option->getName() ?>
                                    </label>
                                    <select class="store-product-option form-control" name="po<?= $option->getID() ?>">
                                        <?php
                                        foreach ($optionItems as $optionItem) {
                                            if (!$optionItem->isHidden()) {
                                                ?>
                                                <option value="<?= $optionItem->getID() ?>">
                                                    <?= $optionItem->getName() ?>
                                                </option>
                                                <?php
                                            }
                                            // below is an example of a radio button, comment out the <select> and <option> tags to use instead
                                            //echo '<input type="radio" name="po'.$option->getID().'" value="'. $optionItem->getID(). '" />' . $optionItem->getName() . '<br />'; 
                                            ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            <?php } elseif ($optionType == 'text') { ?>
                                <div class="store-product-option-group form-group <?= $option->getHandle() ?>">
                                    <label class="store-product-option-group-label">
                                        <?= $option->getName() ?>
                                    </label>
                                    <input class="store-product-option-entry form-control" <?= $requiredAttr; ?> name="pt<?= $option->getID() ?>" />
                                </div>
                            <?php } elseif ($optionType == 'textarea') { ?>
                                <div class="store-product-option-group form-group <?= $option->getHandle() ?>">
                                    <label class="store-product-option-group-label">
                                        <?= $option->getName() ?>
                                    </label>
                                    <textarea class="store-product-option-entry form-control" <?= $requiredAttr; ?> name="pa<?= $option->getID() ?>"></textarea>
                                </div>
                            <?php } elseif ($optionType == 'hidden') { ?>
                                <input type="hidden" class="store-product-option-hidden <?= $option->getHandle() ?>" name="ph<?= $option->getID() ?>" />
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <?php if ($showCartButton) { ?>
                        <input type="hidden" name="pID" value="<?= $product->getID() ?>">
                        <?php if ($product->getAttribute('redirect_to_site') && $product->getAttribute('redirect_url') != '') { ?>
                        <a data-add-type="none" class="add_cart btn btn-primary" href="<?= $product->getAttribute('redirect_url') ?>">
                                <?= t("Go to Website") ?>
                            </a> 
                        <?php } else { ?>
                            <a data-add-type="none" data-product-id="<?= $product->getID() ?>"
                               class="add_cart store-btn-add-to-cart btn btn-primary <?= ($product->isSellable() ? '' : 'hidden'); ?> " style="color: #fff;background-color: #337ab7;border-color: #2e6da4;">
                                   <?= ($btnText ? h($btnText) : t("Add to Cart")) ?>
                            </a> <span
                                class="store-out-of-stock-label <?= ($product->isSellable() ? 'hidden' : ''); ?>">
                                    <?= t("Out of Stock") ?>
                            </span>
                        <?php }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php if ($product->getDetail() || $product->getAttribute('product_specification')) { ?>
            <?php /* ?><div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="pro_detail">
              <div id="exTab3" class="container">
              <ul  class="nav nav-pills">
              <?php if($product->getDetail()){ ?><li class="active"> <a  href="#1b" data-toggle="tab">Description</a> </li><?php } ?>
              <?php if($product->getAttribute('product_specification')){ ?><li><a href="#2b" data-toggle="tab">Specification</a> </li><?php } ?>
              </ul>
              <div class="tab-content clearfix">
              <?php if($product->getDetail()){ ?><div class="tab-pane active" id="1b">
              <?= $product->getDetail() ?>
              </div><?php } ?>
              <?php if($product->getAttribute('product_specification')){ ?><div class="tab-pane" id="2b">
              <?php echo $product->getAttribute('product_specification'); ?>
              </div><?php } ?>
              </div>
              </div>
              </div>
              </div>
              </div><?php */ ?>
        <?php } ?>
        <?php /* ?><div class="row">
          <?php if ($showProductDetails) { ?>
          <div class="store-product-detailed-description col-md-12">
          <?= $product->getDetail() ?>
          </div>
          <?php } ?>
          </div><?php */ ?>
    </form>
    <script type="text/javascript">
        $(function () {
            $('.store-product-thumb').magnificPopup({
                type: 'image',
                gallery: {enabled: true}
            });

    <?php if ($product->hasVariations() && !empty($variationLookup)) { ?>

        <?php
        $varationData = array();
        foreach ($variationLookup as $key => $variation) {
            $product->setVariation($variation);
            $imgObj = $product->getImageObj();

            $thumb = false;

            if ($imgObj) {
                $thumb = Core::make('helper/image')->getThumbnail($imgObj, $defaultimagewidth, $defaultimageheight, true);
            }

            $varationData[$key] = array(
                'price' => $product->getFormattedOriginalPrice(),
                'saleprice' => $product->getFormattedSalePrice(),
                'available' => ($variation->isSellable()),
                'imageThumb' => $thumb ? $thumb->src : '',
                'image' => $imgObj ? $imgObj->getRelativePath() : ''
            );
        }
        ?>

                $('#product-options-<?= $bID; ?> select, #product-options-<?= $bID; ?> input').change(function () {
                    var variationdata = <?= json_encode($varationData); ?>;
                    var ar = [];

                    $('#product-options-<?= $bID; ?> select, #product-options-<?= $bID; ?> input:checked').each(function () {
                        ar.push($(this).val());
                    });

                    ar.sort(communityStore.sortNumber);
                    var pdb = $(this).closest('.store-product-block');

                    if (variationdata[ar.join('_')]['saleprice']) {
                        var pricing = '<span class="store-sale-price"><?= t("On Sale: "); ?>' + variationdata[ar.join('_')]['saleprice'] + '</span>' +
                                '<span class="store-original-price">' + variationdata[ar.join('_')]['price'] + '</span>';

                        pdb.find('.store-product-price').html(pricing);
                    } else {
                        pdb.find('.store-product-price').html(variationdata[ar.join('_')]['price']);
                    }

                    if (variationdata[ar.join('_')]['available']) {
                        pdb.find('.store-out-of-stock-label').addClass('hidden');
                        pdb.find('.store-btn-add-to-cart').removeClass('hidden');
                    } else {
                        pdb.find('.store-out-of-stock-label').removeClass('hidden');
                        pdb.find('.store-btn-add-to-cart').addClass('hidden');
                    }
                    if (variationdata[ar.join('_')]['imageThumb']) {
                        var image = pdb.find('.store-product-primary-image img');

                        if (image) {
                            image.attr('src', variationdata[ar.join('_')]['imageThumb']);
                            var link = image.parent();
                            if (link) {
                                link.attr('href', variationdata[ar.join('_')]['image'])
                            }
                        }
                    }

                });
    <?php } ?>

        });
    </script>
    <?php } else { ?>
    <p class="alert alert-info">
    <?= t("Product not available") ?>
    </p>
<?php } ?>
<script type="application/javascript">
    $(".xzoom").xzoom({tint: '#333', Xoffset: 15});
    $(document).ready(function(e) {

    $('.store-product-additional-image').first().addClass('active');
    $('.store-product-additional-image a').click(function(){

    var ref= $(this).attr('rel');
    var ar= $('.product_img img').attr('xoriginal');
    console.log(ar);
    $('.store-product-additional-image').removeClass('active');
    $(this).parent().addClass('active');
    $('.product_img a').attr('href',ref);
    $('.product_img img').attr('xoriginal', ref);
    $('.product_img img').attr('src', ref).height(470).width(470);
    });
    });
</script>
<script>
    ga('create', 'UA-67543398-2');
    ga('require', 'ec');

    ga('ec:addProduct', {
      'id': '<?= $product->getSKU() ?>',
      'name': '<?= utf8_encode($product->getName()); ?>',
      'category': '<?=$parent_page->getCollectionName();?>',
      'brand': '<?=$brndName?>',
//      'variant': 'black'
    });

    ga('ec:setAction', 'detail');

    ga('send', 'pageview');       // Send product details view with the initial pageview.
</script>