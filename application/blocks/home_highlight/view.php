<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>
<?php  if (isset($content) && trim($content) != "") { ?>
    <?php  echo $content; ?><?php  } ?>
<?php  if ($image) { ?>
    <img src="<?php  echo $image->getURL(); ?>" alt="<?php  echo $image->getTitle(); ?>"/><?php  } ?>
<?php  if (!empty($pagelink) && ($pagelink_c = Page::getByID($pagelink)) && (!empty($pagelink_c) || !$pagelink_c->error)) {
    ?>
    <?php  echo '<a href="' . $pagelink_c->getCollectionLink() . '">' . (isset($pagelink_text) && trim($pagelink_text) != "" ? $pagelink_text : $pagelink_c->getCollectionName()) . '</a>';
?><?php  } ?>