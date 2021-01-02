<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<style>
    .cart-count{
        background-color: #3866df;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        border-radius: 100%;
        line-height: 1;
        width: 22px;
        height: 22px;
        align-items: center;
        display: flex;
        justify-content: center;
        position: relative;
        top: -15px;
        right: -20px;
        -webkit-box-align: center;
        -ms-flex-align: center;
        -webkit-box-pack: center;
    }
    
    @media screen and (min-width: 800px) {
        .store-utility-links{
            position: fixed;
            right: 375px;
            z-index: 1000;
            background-image: linear-gradient(to right, transparent, #ec7c05);
            right: -30px;
            width: 407px;
        }
      }
      
</style>
<?php if (!$shoppingDisabled) { ?>
<div class="store-utility-links <?= ($itemCount == 0 ? 'store-cart-empty' : ''); ?>">
    <?php if ($showSignIn || $showGreeting) { ?>
    <p class="store-utility-links-login">
        <?php if ($showSignIn) {
            $u = new User();
            if (!$u->isLoggedIn()) {
                echo '<a href="' . \URL::to('/login') . '">' . t("Sign In") . '</a>';
            }
        } ?>
        <?php if ($showGreeting) {
            $u = new User();
            if ($u->isLoggedIn()) {
                $msg = '<span class="store-welcome-message">' . t("Welcome back") . '</span>';
                $ui = UserInfo::getByID($u->getUserID());
                if ($firstname = $ui->getAttribute('billing_first_name')) {
                    $msg = '<span class="store-welcome-message">' . t("Welcome back, ") . '<span class="first-name">' . $firstname . '</span></span>';
                }
                echo $msg;
            }
        } ?>
        </p>
    <?php } ?>

    <?php if ($showCartItems || $showCartTotal) { ?>
        
            <?php if ($showCartItems) { ?>
                <a href="<?= \URL::to('/cart') ?>" title="Your cart" data-toggle="tooltip"><img src="<?php echo $this->getThemePath();?>/images/cart_new.png">
                    <span class="cart-count">
                        <?= $itemCount ?>
                    </span>
                </a>
            <?php } ?>

            <?php if ($showCartTotal) { ?>
                <span class="store-total-cart-amount"><?= $total ?></span>
            <?php } ?>
        
    <?php } ?>

    <?php if (!$inCart) { ?>
        <p class="store-utility-links-cart-link">
            <?php if ($popUpCart && !$inCheckout) { ?>
                <?php /*?><a href="#" class="store-cart-link store-cart-link-modal"><img src="<?php echo $this->getThemePath();?>/images/cart.png">(<?= $itemCount ?>)</a><?php */?>
            <?php } else { ?>
                <?php /*?><a href="<?= \URL::to('/cart') ?>" class="store-cart-link"><?= $cartLabel ?></a><?php */?>
            <?php } ?>
        </p>
    <?php } ?>

    <?php if (!$inCheckout) { ?>
        <?php if ($showCheckout) { ?>
        <p  class="store-utility-links-checkout-link">
            <a href="<?= \URL::to('/checkout') ?>" class="store-cart-link"><?= t("Checkout") ?></a>
        </p>
        <?php } ?>
    <?php } ?>
</div>
<?php } ?>