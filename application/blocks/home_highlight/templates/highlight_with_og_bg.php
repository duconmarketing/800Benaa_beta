<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>
<?php  if (!empty($pagelink) && ($pagelink_c = Page::getByID($pagelink)) && (!empty($pagelink_c) || !$pagelink_c->error)) {
	
	$link_url=$pagelink_c->getCollectionLink();
	
	}
    ?>

<div class="box_or" <?php if($link_url!=''){echo 'onclick="window.location.href=\''.$link_url.'\'" style="cursor:pointer"';} ?>>
  <?php  if ($image) { ?>
  <img src="<?php  echo $image->getURL(); ?>" alt="<?php  echo $image->getTitle(); ?>"/>
  <?php  } ?>
  <div class="content">
    <?php  if (isset($content) && trim($content) != "") { ?>
    <?php  echo $content; ?>
    <?php  } ?>
  </div>
</div>
