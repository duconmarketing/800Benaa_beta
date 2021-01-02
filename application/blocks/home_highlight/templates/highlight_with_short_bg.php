<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>
<?php  if (!empty($pagelink) && ($pagelink_c = Page::getByID($pagelink)) && (!empty($pagelink_c) || !$pagelink_c->error)) {
	
	$link_url=$pagelink_c->getCollectionLink();
	}
    ?>

<div class="bg1" style="background-image:url('<?php echo $image->getURL(); ?>');cursor:pointer" <?php if($link_url!=''){echo 'onclick="window.location.href=\''.$link_url.'\'" ';} ?>>
  <?php  if (isset($content) && trim($content) != "") { ?>
  <?php  echo $content; ?>
  <?php  } ?>
</div>
