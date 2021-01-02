<?php
defined('C5_EXECUTE') or die("Access Denied.");
$th = Loader::helper('text');
$c = Page::getCurrentPage();
$dh = Core::make('helper/date'); /* @var $dh \Concrete\Core\Localization\Service\Date */
?>
<?php if ( $c->isEditMode() && $controller->isBlockEmpty()) { ?>

<div class="ccm-edit-mode-disabled-item"><?php echo t('Empty Page List Block.')?></div>
<?php } else { ?>
<?php if (isset($pageListTitle) && $pageListTitle): ?>
<div class="ccm-block-page-list-header">
  <h5><?php echo h($pageListTitle)?></h5>
</div>
<?php endif; ?>
<?php if (isset($rssUrl) && $rssUrl): ?>
<a href="<?php echo $rssUrl ?>" target="_blank" class="ccm-block-page-list-rss-feed"><i class="fa fa-rss"></i></a>
<?php endif; ?>
<?php

    $includeEntryText = false;
    if (
        (isset($includeName) && $includeName)
        ||
        (isset($includeDescription) && $includeDescription)
        ||
        (isset($useButtonForLink) && $useButtonForLink)
    ) {
        $includeEntryText = true;
    }
//print_r($pages);die;
    foreach ($pages as $page):
         //print_r($page);die;
		// Prepare data for each page being listed...
        $buttonClasses = 'ccm-block-page-list-read-more';
        $entryClasses = 'ccm-block-page-list-page-entry';
		$title = $th->entities($page->getCollectionName());
		$url = ($page->getCollectionPointerExternalLink() != '') ? $page->getCollectionPointerExternalLink() : $nh->getLinkToCollection($page);
		$target = ($page->getCollectionPointerExternalLink() != '' && $page->openCollectionPointerExternalLinkInNewWindow()) ? '_blank' : $page->getAttribute('nav_target');
		$target = empty($target) ? '_self' : $target;
		$description = $page->getCollectionDescription();
		$description = $controller->truncateSummaries ? $th->wordSafeShortText($description, $controller->truncateChars) : $description;
		$description = $th->entities($description);
        $thumbnail = false;
        if ($displayThumbnail) {
            $thumbnail = $page->getAttribute('thumbnail');
        }
        if (is_object($thumbnail) && $includeEntryText) {
            $entryClasses = 'ccm-block-page-list-page-entry-horizontal';
        }

        $date = $dh->formatDateTime($page->getCollectionDatePublic(), true);


		//Other useful page data...


		//$last_edited_by = $page->getVersionObject()->getVersionAuthorUserName();

		//$original_author = Page::getByID($page->getCollectionID(), 1)->getVersionObject()->getVersionAuthorUserName();

		/* CUSTOM ATTRIBUTE EXAMPLES:
		 * $example_value = $page->getAttribute('example_attribute_handle');
		 *
		 * HOW TO USE IMAGE ATTRIBUTES:
		 * 1) Uncomment the "$ih = Loader::helper('image');" line up top.
		 * 2) Put in some code here like the following 2 lines:
		 *      $img = $page->getAttribute('example_image_attribute_handle');
		 *      $thumb = $ih->getThumbnail($img, 64, 9999, false);
		 *    (Replace "64" with max width, "9999" with max height. The "9999" effectively means "no maximum size" for that particular dimension.)
		 *    (Change the last argument from false to true if you want thumbnails cropped.)
		 * 3) Output the image tag below like this:
		 *		<img src="<?php echo $thumb->src ?>" width="<?php echo $thumb->width ?>" height="<?php echo $thumb->height ?>" alt="" />
		 *
		 * ~OR~ IF YOU DO NOT WANT IMAGES TO BE RESIZED:
		 * 1) Put in some code here like the following 2 lines:
		 * 	    $img_src = $img->getRelativePath();
		 *      $img_width = $img->getAttribute('width');
		 *      $img_height = $img->getAttribute('height');
		 * 2) Output the image tag below like this:
		 * 	    <img src="<?php echo $img_src ?>" width="<?php echo $img_width ?>" height="<?php echo $img_height ?>" alt="" />
		 */

		/* End data preparation. */

		/* The HTML from here through "endforeach" is repeated for every item in the list... */ 
		$childs=$page->getCollectionChildren();
		//print_r($childs);die('fef');
		?>
<div class="col-md-3 col-sm-3 col-xs-6">
  <div class="home_pro hvr-float">
  <a href="<?php echo $url ?>" title="<?php echo $title; ?>"> 
    <?php if (is_object($thumbnail)): ?>
    <?php
                $img = Core::make('html/image', array($thumbnail));
                $tag = $img->getTag();
                $tag->addClass('lazy'); //added for Lazy load
                $tag->addClass('img-responsive');
//                print $tag;
                echo str_replace('src', 'data-src', $tag); //added for Lazy load
                ?>
    <?php endif; ?>
    </a>
    <h5><a href="<?php echo $url ?>" title="<?php echo $title; ?>"> <?php echo $title; ?></a></h5>
    <ul>
      <?php
			  $i=1;
              foreach ($childs as $child){
                  if($child->getNumChildren() > 0){
			  ?>
        <li><a style="display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;" href="<?php echo $nh->getLinkToCollection($child)?>" title="<?php echo $child->getCollectionName();?>"><?php echo ucwords(strtolower($child->getCollectionName()));?></a></li>
      <?php
			  if($i==3){
				 break;
				  
			  }
			  $i++;
                  }
			   }?>
      <li><a href="<?php echo $url ?>" class="view_child">View All..</a></li>
    </ul>
  </div>
</div>
<?php endforeach; ?>
<?php if (count($pages) == 0): ?>
<div class="ccm-block-page-list-no-pages"><?php echo h($noResultsMessage)?></div>
<?php endif;?>

<!-- end .ccm-block-page-list -->

<?php if ($showPagination): ?>
<?php echo $pagination;?>
<?php endif; ?>
<?php } ?>
