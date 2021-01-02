<?php defined('C5_EXECUTE') or die("Access Denied.");

$navItems = $controller->getNavItems();
$c = Page::getCurrentPage();

/**
 * The $navItems variable is an array of objects, each representing a nav menu item.
 * It is a "flattened" one-dimensional list of all nav items -- it is not hierarchical.
 * However, a nested nav menu can be constructed from this "flat" array by
 * looking at various properties of each item to determine its place in the hierarchy
 * (see below, for example $navItem->level, $navItem->subDepth, $navItem->hasSubmenu, etc.)
 *
 * Items in the array are ordered with the first top-level item first, followed by its sub-items, etc.
 *
 * Each nav item object contains the following information:
 *	$navItem->url        : URL to the page
 *	$navItem->name       : page title (already escaped for html output)
 *	$navItem->target     : link target (e.g. "_self" or "_blank")
 *	$navItem->level      : number of levels deep the current menu item is from the top (top-level nav items are 1, their sub-items are 2, etc.)
 *	$navItem->subDepth   : number of levels deep the current menu item is *compared to the next item in the list* (useful for determining how many <ul>'s to close in a nested list)
 *	$navItem->hasSubmenu : true/false -- if this item has one or more sub-items (sometimes useful for CSS styling)
 *	$navItem->isFirst    : true/false -- if this is the first nav item *in its level* (for example, the first sub-item of a top-level item is TRUE)
 *	$navItem->isLast     : true/false -- if this is the last nav item *in its level* (for example, the last sub-item of a top-level item is TRUE)
 *	$navItem->isCurrent  : true/false -- if this nav item represents the page currently being viewed
 *	$navItem->inPath     : true/false -- if this nav item represents a parent page of the page currently being viewed (also true for the page currently being viewed)
 *	$navItem->attrClass  : Value of the 'nav_item_class' custom page attribute (if it exists and is set)
 *	$navItem->isHome     : true/false -- if this nav item represents the home page
 *	$navItem->cID        : collection id of the page this nav item represents
 *	$navItem->cObj       : collection object of the page this nav item represents (use this if you need to access page properties and attributes that aren't already available in the $navItem object)
 */


/** For extra functionality, you can add the following page attributes to your site (via Dashboard > Pages & Themes > Attributes):
 *
 * 1) Handle: exclude_nav
 *    (This is the "Exclude From Nav" attribute that comes pre-installed with concrete5, so you do not need to add it yourself.)
 *    Functionality: If a page has this checked, it will not be included in the nav menu (and neither will its children / sub-pages).
 *
 * 2) Handle: exclude_subpages_from_nav
 *    Type: Checkbox
 *    Functionality: If a page has this checked, all of that pages children (sub-pages) will be excluded from the nav menu (but the page itself will be included).
 *
 * 3) Handle: replace_link_with_first_in_nav
 *    Type: Checkbox
 *    Functionality: If a page has this checked, clicking on it in the nav menu will go to its first child (sub-page) instead.
 *
 * 4) Handle: nav_item_class
 *    Type: Text
 *    Functionality: Whatever is entered into this textbox will be outputted as an additional CSS class for that page's nav item (NOTE: you must un-comment the "$ni->attrClass" code block in the CSS section below for this to work).
 */


/*** STEP 1 of 2: Determine all CSS classes (only 2 are enabled by default, but you can un-comment other ones or add your own) ***/
foreach ($navItems as $ni) {
	$classes = array();

	if ($ni->isCurrent) {
		//class for the page currently being viewed
		$classes[] = 'nav-selected active';
	}

	if ($ni->inPath) {
		//class for parent items of the page currently being viewed
		$classes[] = 'nav-path-selected active';
	}

	/*
	if ($ni->isFirst) {
		//class for the first item in each menu section (first top-level item, and first item of each dropdown sub-menu)
		$classes[] = 'nav-first';
	}
	*/

	/*
	if ($ni->isLast) {
		//class for the last item in each menu section (last top-level item, and last item of each dropdown sub-menu)
		$classes[] = 'nav-last';
	}
	*/

	/*
	if ($ni->hasSubmenu) {
		//class for items that have dropdown sub-menus
		$classes[] = 'nav-dropdown';
	}
	*/

	/*
	if (!empty($ni->attrClass)) {
		//class that can be set by end-user via the 'nav_item_class' custom page attribute
		$classes[] = $ni->attrClass;
	}
	*/

	/*
	if ($ni->isHome) {
		//home page
		$classes[] = 'nav-home';
	}
	*/

	/*
	//unique class for every single menu item
	$classes[] = 'nav-item-' . $ni->cID;
	*/

	//Put all classes together into one space-separated string
	$ni->classes = implode(" ", $classes);
}


//*** Step 2 of 2: Output menu HTML ***/

if (count($navItems) > 0) {
	
	//print_r($navItems);die;
    echo '<ul style="display: flex;>'; //opens the top-level menu

    foreach ($navItems as $ni) {
		
		$megaMenuClass = "";
	   	$hasMegamenu = $ni->cObj->getAttribute('enable_mega_menu');
		if($hasMegamenu){
			$megaMenuClass ='mmenu-nav-path';
		}
		
		 if ($ni->isHome) {
			 
//		 echo'<li class="' . $ni->classes . '"><a href="' . $ni->url . '" target="' . $ni->target . '" class="' . $ni->classes . '"><img src="'.$this->getThemePath().'/images/home.png" class="img-responsive"></a>';
		 echo'<li class="' . $ni->classes . '"><a style="padding: 7px 7px 7px 4px; text-align: center;" href="' . $ni->url . '" class="' . $ni->classes . '"><i class="fa fa-home" aria-hidden="true" style="font-size: 25px;color: #fff;"></i></a>';
	       }elseif($hasMegamenu){
                                      
                   $nh = Loader::helper('navigation');
                   $children = $ni->cObj->getCollectionChildrenArray(1);
                   foreach ($children as $key => $childId) {
                       $child = Page::getByID($childId);
                       if($child->getCollectionAttributeValue('exclude_nav')){
                           unset($children[$key]);
                       }
                   }
                   $children  = array_slice($children, 0, 11);
                   foreach ($children as $childId) {
                                              
                        $child = Page::getByID($childId);
                        if(!$child->getCollectionAttributeValue('exclude_nav')){
                            if ($c->getCollectionID() == $child->getCollectionID()) {
                               $classes = 'nav-selected active'; 
                            }else{
                                $classes = '';
                            }

                            $hasChildMegamenu = $child->getAttribute('enable_mega_menu');
                            $megaMenuClass = "";
                            if($hasChildMegamenu){
                                    $megaMenuClass ='mmenu-nav-path';
                            }

                            echo '<li class="' . $classes . ' '.$megaMenuClass.'">'; //opens a nav item
                            echo '<a style="text-transform: none; font-size: 12px;padding: 7px 7px 7px 7px; text-align: center;" href="' . $nh->getCollectionURL($child) . '" target="' . $ni->target . '" class="' . $classes . '">' . ucwords(strtolower($child->getCollectionName())) . '</a>';
        //                    if($hasChildMegamenu){

        //                        Loader::element('mega_menu',array('c'=>$child));

                                echo '<div class="mmmenu">';
                                echo '<div class="row">';
                                echo '<div class="col-md-8 col-sm-8">';
                                echo '<ul class="sub_nav" style="column-count:2; -webkit-column-count:2;">'; //opens the top-level menu

                                $megaChildred = $child->getCollectionChildrenArray(1);
                                foreach ($megaChildred as $megaChildID) {

                                    $megaChild = Page::getByID($megaChildID);
                                    if(!$megaChild->getCollectionAttributeValue('exclude_nav')){
                                        
                                        if ($c->getCollectionID() == $child->getCollectionID()) {
                                            $classes = 'nav-selected active'; 
                                         }else{
                                             $classes = '';
                                         }
                            
                                        echo '<li class="' . $classes . '">'; //opens a nav item
                                        echo '<a style="text-transform: none; font-size: 13px;font-weight: 700;padding: 10px 3px 10px 3px; text-align: center;" href="' . $nh->getCollectionURL($megaChild) . '" target="' . $ni->target . '" class="' . $classes . '">' . ucwords(strtolower($megaChild->getCollectionName())) . '</a>';
                                        echo '</li>'; //closes a nav item         
                                    }
                                }
                                echo '</ul>'; //closes the top-level menu
        //                    }
                                echo '</div>';
                                echo '<div class="col-md-4 col-sm-4"></div>';
                                echo '</div></div></li>';
                          }
                   }
                   

//        echo '<li class="' . $ni->classes . ' '.$megaMenuClass.'">'; //opens a nav item
//        echo '<a href="' . $ni->url . '" target="' . $ni->target . '" class="' . $ni->classes . '">' . $ni->name . '</a>';

//      if($hasMegamenu){
//			Loader::element('mega_menu',array('c'=>$ni->cObj));
//		  }

//        if ($ni->hasSubmenu) {
//            echo '<ul>'; //opens a dropdown sub-menu
//        } else {
//            echo '</li>'; //closes a nav item
//            echo str_repeat('</ul></li>', $ni->subDepth); //closes dropdown sub-menu(s) and their top-level nav item(s)
//        }
                   echo '</li>';
		   }
    }

    echo '</ul>'; //closes the top-level menu
} else if (is_object($c) && $c->isEditMode()) { ?>
    <div class="ccm-edit-mode-disabled-item"><?php echo t('Empty Auto-Nav Block.')?></div>
<?php }