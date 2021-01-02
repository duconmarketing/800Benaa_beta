<?php defined("C5_EXECUTE") or die("Access Denied."); ?>
<li class="nav_<?php echo $bID; ?>" data-sid="<?php echo $bID; ?>">
    <div class="item" id="item_<?php echo $bID ?>" style="display:none;">
        <?php
        if (!empty($pagelink) && ($pagelink_c = Page::getByID($pagelink)) && (!empty($pagelink_c) || !$pagelink_c->error)) {
            $link = $pagelink_c->getCollectionLink();
        }
        ?>

        <?php
        if ($slideimage) {
            if ($pagelink_text == 'Deliver Fast') {
                ?>
                <video style="width:100%;" autoplay muted loop oncontextmenu="return false;">
                    <source src="https://www.800benaa.com/application/themes/build/video/800benaa-new.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>

                <?php } else { ?>        
                <a href=" <?php echo $link; ?>"><img src="<?php echo $slideimage->getURL(); ?>" alt="<?php echo $slideimage->getTitle(); ?>" class="img-responsive"/></a>
                <?php }
                ?>
<?php } ?>
    </div>

    <a href="javascript:void(0)"><?php echo $pagelink_text; ?></a>

</li>