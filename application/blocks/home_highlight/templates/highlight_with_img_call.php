<?php defined("C5_EXECUTE") or die("Access Denied."); ?>
<?php
if (!empty($pagelink) && ($pagelink_c = Page::getByID($pagelink)) && (!empty($pagelink_c) || !$pagelink_c->error)) {

    $link_url = $pagelink_c->getCollectionLink();
}
?>

<div class="highlight-img">
    <a href="tel:80023622">
        <?php if ($image) { ?>
            <img data-src="<?php echo $image->getURL(); ?>" alt="<?php echo $image->getTitle(); ?>" class="lazy img-responsive" style="display: inline;"/>
        <?php } ?>
        <div class="content">
            <?php if (isset($content) && trim($content) != "") { ?>
                <?php echo $content; ?>
            <?php } ?>
        </div>
    </a>
</div>
