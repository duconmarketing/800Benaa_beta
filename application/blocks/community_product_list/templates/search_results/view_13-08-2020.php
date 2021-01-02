<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

use \Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductVariation\ProductVariation as StoreProductVariation;

//echo '<div class="description"><h3>'.t('Search results :').'</h3></div>';
if ($products && !empty($_GET['keywords']) && empty($_GET['category'])) {

    $columnClass = 'col-md-12';

    if ($productsPerRow == 2) {
        $columnClass = 'col-md-6';
    }

    if ($productsPerRow == 3) {
        $columnClass = 'col-md-4';
    }

    if ($productsPerRow == 4) {
        $columnClass = 'col-md-3';
    }


    // echo '<div class="store-product-list row searchResults store-product-list-per-row-'. $productsPerRow .'">';
    echo "<div class='row filteredProduct'>";
    $i = 1;
    foreach ($products as $product) {

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
        if (Page::getCurrentPage()->getCollectionID() == $product->getPageID()) {
            $activeclass = 'on-product-page';
        }
        ?>
        <div class="store-product-list-item col-md-3 col-sm-3 col-xs-6">
            <div class="product_item pro hvr-float">
                <form  id="store-form-add-to-cart-list-<?= $product->getID() ?>">

                    <?php
                    $imgObj = $product->getImageObj();
                    if (is_object($imgObj)) {
                        $thumb = $ih->getThumbnail($imgObj, 300, 280, true);
                        ?>

                        <?php if ($showQuickViewLink) { ?>
                            <a class="store-product-quick-view" data-product-id="<?= $product->getID() ?>" href="#">
                                <img src="<?= $thumb->src ?>" class="img-responsive">
                            </a>
                        <?php } elseif ($showPageLink) { ?>
                            <a href="<?= ($product->getAttribute('redirect_to_site') && $product->getAttribute('redirect_url') != '' ? $product->getAttribute('redirect_url') : \URL::to(Page::getByID($product->getPageID()))) ?>">
                                <img src="<?= $thumb->src ?>" class="img-responsive">
                            </a>
                        <?php } else { ?>
                            <img src="<?= $thumb->src ?>" class="img-responsive">
                        <?php } ?>

                        <?php
                    }// if is_obj
                    ?>

                    <div class="product_des">
                        <?php if ($showName) { ?>
                            <h5><a class="a_title" href="<?= ($product->getAttribute('redirect_to_site') ? $product->getAttribute('redirect_url') : \URL::to(Page::getByID($product->getPageID()))) ?>">
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
                                        echo '<span class="store-original-price" style="color: #999;font-size: 13px; text-decoration: line-through;">' . $product->getFormattedOriginalPrice() . '</span>';
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
                    <?php if ($showAddToCart) { ?>

                        <?php if ($product->allowQuantity() && $showQuantity) { ?>
                            <div class="store-product-quantity form-group">
                                <label class="store-product-option-group-label"><?= t('Quantity') ?></label>
                                <input type="number" name="quantity" class="store-product-qty form-control" value="1" min="1" step="1">
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="quantity" class="store-product-qty" value="1">
                        <?php } ?>

                        <?php /* ?><?php
                          foreach ($product->getOptions() as $option) {
                          $optionItems = $option->getOptionItems();
                          $optionType = $option->getType();
                          $required = $option->getRequired();

                          $requiredAttr = '';

                          if ($required) {
                          $requiredAttr = ' required="required" placeholder="'.t('Required').'" ';
                          }
                          ?>

                          <?php if (!$optionType || $optionType == 'select') { ?>
                          <div class="store-product-option-group form-group <?= $option->getHandle() ?>">
                          <label class="store-product-option-group-label"><?= $option->getName() ?></label>
                          <select class="store-product-option form-control" name="po<?= $option->getID() ?>">
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
                          <?php } elseif ($optionType == 'text') { ?>
                          <div class="store-product-option-group form-group <?= $option->getHandle() ?>">
                          <label class="store-product-option-group-label"><?= $option->getName() ?></label>
                          <input class="store-product-option-entry form-control" <?= $requiredAttr; ?> name="pt<?= $option->getID() ?>" />
                          </div>
                          <?php } elseif ($optionType == 'textarea') { ?>
                          <div class="store-product-option-group form-group <?= $option->getHandle() ?>">
                          <label class="store-product-option-group-label"><?= $option->getName() ?></label>
                          <textarea class="store-product-option-entry form-control" <?= $requiredAttr; ?> name="pa<?= $option->getID() ?>"></textarea>
                          </div>
                          <?php } elseif ($optionType == 'hidden') { ?>
                          <input type="hidden" class="store-product-option-hidden <?= $option->getHandle() ?>" name="ph<?= $option->getID() ?>" />
                          <?php } ?>
                          <?php } ?><?php */ ?>


                        <input type="hidden" name="pID" value="<?= $product->getID() ?>">
                        <?php if ($product->getAttribute('redirect_to_site') && $product->getAttribute('redirect_url') != '') { ?>
                            <p class="store-btn-add-to-cart-container">
                                <a href="<?= $product->getAttribute('redirect_url') ?>" class="btn btn-primary" style="color: #FFF;">
                                    <?= t("Go to Website") ?>
                                </a>
                            </p>
                        <?php } else { ?>

                            <p class="store-btn-add-to-cart-container"><button data-add-type="list" data-product-id="<?= $product->getID() ?>" class="store-btn-add-to-cart btn btn-primary <?= ($product->isSellable() ? '' : 'hidden'); ?> "><?= ($btnText ? h($btnText) : t("Add to Cart")) ?></button></p>
                            <p class="store-out-of-stock-label alert-warning <?= ($product->isSellable() ? 'hidden' : ''); ?>">
                                <a class="btn btn-primary" style="cursor: default; background-color: white; color: #000;">
                                    <?= t("Out of Stock") ?>
                                </a>
                            </p>

                        <?php }
                    }
                    ?>
                    <a href="<?= ($product->getAttribute('redirect_to_site') && $product->getAttribute('redirect_url') != '' ? $product->getAttribute('redirect_url') : \URL::to(Page::getByID($product->getPageID()))) ?>" class="pro_but" title="<?= $product->getName() ?>">More Details</a>

                </form><!-- .product-list-item-inner -->
            </div>
        </div><!-- .product-list-item -->
        <?php
        $i++;
    }// foreach    
    echo "</div>";
    //  echo "</div><!-- .product-list -->";

    if ($showPagination) {
        if ($paginator->getTotalPages() > 1) {
            echo '<div class="row">';
            echo $pagination;
            echo '</div>';
        }
    }
} else if ($products && !empty($_GET['keywords']) && !empty($_GET['category'])) {

    $columnClass = 'col-md-12';

    if ($productsPerRow == 2) {
        $columnClass = 'col-md-6';
    }

    if ($productsPerRow == 3) {
        $columnClass = 'col-md-4';
    }

    if ($productsPerRow == 4) {
        $columnClass = 'col-md-3';
    }


    // echo '<div class="store-product-list row searchResults store-product-list-per-row-'. $productsPerRow .'">';
    echo "<div class='row filteredProduct'>";
    $i = 1;
    foreach ($products as $product) {

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
        if (Page::getCurrentPage()->getCollectionID() == $product->getPageID()) {
            $activeclass = 'on-product-page';
        }


        // echo 'testval' . $product->getPageID() . '/' . $_GET['category'];

        $getparentval = Page::getByID($product->getPageID());
        $nh = Core::make('helper/navigation');
        $cobj = $nh->getTrailToCollection($getparentval);

        $rcobj = array_reverse($cobj);
        if (is_object($rcobj[2])) {
            $pID = $rcobj[2]->getCollectionID();
            $page = Page::getByID($pID);
            // echo $page->getCollectionID();
            if ($page->getCollectionID() == $_GET['category']) {


                // $getpval = $getparentval->getCollectionParentID();
                // echo '/testval1=' . $getpval . '/';
                ?>
                <div class="store-product-list-item col-md-3 col-sm-3 col-xs-6">
                    <div class="product_item pro hvr-float">
                        <form  id="store-form-add-to-cart-list-<?= $product->getID() ?>">

                            <?php
                            $imgObj = $product->getImageObj();
                            if (is_object($imgObj)) {
                                $thumb = $ih->getThumbnail($imgObj, 300, 280, true);
                                ?>

                    <?php if ($showQuickViewLink) { ?>
                                    <a class="store-product-quick-view" data-product-id="<?= $product->getID() ?>" href="#">
                                        <img src="<?= $thumb->src ?>" class="img-responsive">
                                    </a>
                    <?php } elseif ($showPageLink) { ?>
                                    <a href="<?= \URL::to(Page::getByID($product->getPageID())) ?>">
                                        <img src="<?= $thumb->src ?>" class="img-responsive">
                                    </a>
                                <?php } else { ?>
                                    <img src="<?= $thumb->src ?>" class="img-responsive">
                                <?php } ?>

                                <?php
                            }// if is_obj
                            ?>

                            <div class="product_des">
                                <?php if ($showName) { ?>
                                    <h5><a class="a_title" href="<?= \URL::to(Page::getByID($product->getPageID())) ?>"><?= $product->getName() ?></a></h5>
                                <?php } ?>
                                <?php
                                if ($product->getAttribute('floating_price')) {
                                    echo '<a href="tel:80023622"><h6> CONTACT US </h6></a>';
                                } else {
                                    ?>
                                    <h6> AED <?php echo $product->getPrice(); ?> <span style="font-size: 12px;color: #000;text-transform: none;font-weight: normal;">Ex VAT</span></h6>
                                <?php } ?>
                                <?php if ($product->getSKU()) { ?>
                                    <p> ID: <span><?php echo $product->getSKU(); ?></span></p><br/>
                <?php } ?>

                            </div>
                            <?php if ($showAddToCart) { ?>

                    <?php if ($product->allowQuantity() && $showQuantity) { ?>
                                    <div class="store-product-quantity form-group">
                                        <label class="store-product-option-group-label"><?= t('Quantity') ?></label>
                                        <input type="number" name="quantity" class="store-product-qty form-control" value="1" min="1" step="1">
                                    </div>
                                <?php } else { ?>
                                    <input type="hidden" name="quantity" class="store-product-qty" value="1">
                    <?php } ?>

                                <input type="hidden" name="pID" value="<?= $product->getID() ?>">


                                <p class="store-btn-add-to-cart-container"><button data-add-type="list" data-product-id="<?= $product->getID() ?>" class="store-btn-add-to-cart btn btn-primary <?= ($product->isSellable() ? '' : 'hidden'); ?> "><?= ($btnText ? h($btnText) : t("Add to Cart")) ?></button></p>
                                <p class="store-out-of-stock-label alert-warning <?= ($product->isSellable() ? 'hidden' : ''); ?>">
                                    <a class="btn btn-primary" style="cursor: default; background-color: white; color: #000;">
                                        <?= t("Out of Stock") ?>
                                    </a>
                                </p>

                <?php } ?>
                            <a href="<?= \URL::to(Page::getByID($product->getPageID())) ?>" class="pro_but" title="<?= $product->getName() ?>">More Details</a>

                        </form><!-- .product-list-item-inner -->
                    </div>
                </div><!-- .product-list-item -->
                <?php
                $i++;
                $yes[] = $product->getID();
            }
        } else {
            $no = 1;
        }
    }// foreach    
    if (empty($yes) && $no = 1) {
        echo "<p>No results found</p>";
    }
    echo "</div>";
    //  echo "</div><!-- .product-list -->";

    if ($showPagination) {
        if ($paginator->getTotalPages() > 1) {
            echo '<div class="row">';
            echo $pagination;
            echo '</div>';
        }
    }
} else {
    ?>
    <p class=""><?= t("No results found") ?></p>
<?php } ?>
