<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>
<div class="trade_high bg">
<?php  if ($image) { ?>
    <img src="<?php  echo $image->getURL(); ?>" alt="<?php  echo $image->getTitle(); ?>"/><?php  } ?>
    
    
    <?php  if (!empty($pagelink) && ($pagelink_c = Page::getByID($pagelink)) && (!empty($pagelink_c) || !$pagelink_c->error)) {
    ?>
    <?php  echo '<h4><a href="' . $pagelink_c->getCollectionLink() . '" class="trade_button">' . (isset($pagelink_text) && trim($pagelink_text) != "" ? $pagelink_text : $pagelink_c->getCollectionName()) . '</a></h4>';
?><?php  } ?>
<?php  if (isset($content) && trim($content) != "") { ?>
    <?php  echo $content; ?><?php  } ?>
</div>