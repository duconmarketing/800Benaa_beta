<?php namespace Application\Controller;
use Concrete\Core\Controller\Controller;
use \Concrete\Core\Http\Request;
use Core;
use Page;
use Database;
use Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductList as StoreProductList;
use stdClass;
use Loader;
use URL;
use Theme;

class ProductService extends Controller {
 	public function filter() {
		
	   $filterOptioncategoryRough = $_POST['filterOptioncategory'];
	   
	   $filterOptionbrand = $_POST['filterOptionbrand'];
           
	   $filterOptionPrice = $_POST['filterOptionPrice'];
	   
	   $productParentPage = $_POST['currentSection'];
	   
	   $showBestSeller = $_POST['showBestSeller'];
	   
	   $filterOptionSubcategory = $_POST['filterOptionSubcategory'];
	   
	   if(count($filterOptioncategoryRough)>0 && count($filterOptionSubcategory)>0){
		     $filterOptioncategory =array();
			 	foreach($filterOptioncategoryRough as $f_Rough){
					if(count($filterOptionSubcategory[$f_Rough])>0){
						$newCategories = $filterOptionSubcategory[$f_Rough];
						$filterOptioncategory = array_merge($filterOptioncategory,$newCategories);	
						unset($newCategories);					
					}else{
						array_push($filterOptioncategory,$f_Rough);
					}
				} 	
				
					   
		}else{
			$filterOptioncategory = $filterOptioncategoryRough;
		}
		 
	   $noProductsUnderThisOne = false;
	   	   
	   $productList = new StoreProductList();
	   
	   //$productList->setCID($_POST['currentSection']);
	 
       $productList->setSortByDirection('asc');
      
	   
	   $productList->setItemsPerPage(20);
	 
	 
	   if (count($filterOptionbrand)>0){
               if($filterOptionbrand[0] == 0){
                    $productList->filter(false, 'p.pID not in(SELECT pg.pID FROM `CommunityStoreProductGroups` pg)');
                }else{		
                    $productList->filter(false, 'p.pID in(SELECT pg.pID FROM `CommunityStoreProductGroups` pg where pg.gID in('.implode(',',$filterOptionbrand).'))');
                }

            }            
              
                
        //-------- Added for the pricewise filtering ----------------------
	   if ($filterOptionPrice != ''){
               
               $priceArray = array();
               foreach ($filterOptionPrice as $query){
                   $priceArray = array_merge($priceArray, explode('-', $query));
               }                   
		
//		$productList->filter(false, 'p.pPrice BETWEEN '. str_replace('-', ' AND ', $query));                
		$productList->filter(false, 'p.pPrice BETWEEN '. min($priceArray).' AND '. max($priceArray));                
		
            }			
		 
		 
		 
		$topCategoryPage = Page::getByID($productParentPage);
		$childcIDs = $topCategoryPage->getCollectionChildrenArray();
		
			if(count($childcIDs)>0){
				$productList->filter(false,'p.cID in ('.implode(',',$childcIDs) .')');
			}else{
				$productList->setCID($productParentPage);
			}
	
		
	 	
		 
		$paginator = $productList->getPagination();
		if($noProductsUnderThisOne){
				$products = '';
			
		}else{
			$products = self::arrangeProducts($paginator->getCurrentPageResults());
		}
		
		$collection = new stdClass();
		
		$collection->products = $products;
		
		
		$currentPage = $_REQUEST['ccm_paging_p'];
		$nextPage = $currentPage+1;
		if($nextPage <= $paginator->getTotalPages()){
			$collection->hasNextPage = true;
			$collection->nextPage = $nextPage;
		}else{
			$collection->hasNextPage = false;
		}
		echo json_encode($collection);
		exit();
		
	} 
	public function getChildrenCID($filterOptioncategory){
		
		$returnChildCID = array();
		foreach($filterOptioncategory as $cID){
		 $filterPage = Page::getByID($cID);
		 $childPage = $filterPage->getCollectionChildrenArray();
		 	if(count($childPage)>0){
			 $returnChildCID = array_merge($returnChildCID,$childPage);
			}
		}
		return $returnChildCID;
		
	}
	public function arrangeProducts($products,$convertTojson = true){
		$ih = Loader::helper('image');
		$nh = Loader::helper('navigation');
		$returnArray = array();
		foreach($products as $product){
			//if($product->isActive()){
			$productObj = new stdClass;
			$productObj->pName = $product->getName();
			$productObj->pID = $product->getID();
			$productObj->sku = $product->getSKU();
			$productObj->price = $product->getFormattedPrice();
			if(is_object($product->getImageObj())&&!$product->getImageObj()->isError()){
			$productObj->image = $ih->getThumbnail($product->getImageObj(),300,280,false)->src;
			}
			$productObj->pageLink = $nh->getCollectionUrl(Page::getByID($product->getPageID()));
			$groups = $product->getGroups();
		    foreach($groups as $group){
			$brndName=$group->getGroup()->getGroupName();
			}
			
			//echo $brndName;die;
			if($brndName=='Topex'){
				
				$logo= BASE_URL.DIR_REL.'/application/themes/build/images/topex.png';
				
				}elseif($brndName=="Phocee'nne"){
					
				$logo= BASE_URL.DIR_REL.'/application/themes/build/images/phoceenne.png';
					
				}elseif($brndName=="EMC"){
					
				$logo= BASE_URL.DIR_REL.'/application/themes/build/images/emc.png';
					
					}else{
						
						}
			$productObj->brandLogo = $logo;
			
			
			array_push($returnArray,$productObj);
			//}
		}
		if(count($returnArray)>0){
			if($convertTojson){
				return json_encode($returnArray);
			}else{
				return $returnArray;
			}
		}else{
		return '';	
		}
	}
	public function bestseller(){
		
		self::filter();
		
	}
	public function brand(){
		
		 $filterOptionbrand = $_POST['filterOptionbrand'];
		 $productList = new StoreProductList();
		 $productList->setSortByDirection('asc');
		 if(count($filterOptionbrand)>0){
		 $productList->filter(false, 'pID in(SELECT pID FROM `CommunityStoreProductGroups` where gID in('.implode(',',$filterOptionbrand).'))');
		 }
		 $products = self::arrangeProducts($productList->get());
		 
		 echo $products;
		
		die;
	}
	public function loadMore(){
		
		$products = new StoreProductList();
       // $products->setSortBy($this->sortOrder);
		 $products->setSortByDirection('asc');
		if ($_REQUEST['filter'] == 'page' || $_REQUEST['filter'] == 'page_children') {
            if ($_REQUEST['filterCID']) {
                $products->setCID($_REQUEST['filterCID']);

                if ($_REQUEST['filter'] == 'page_children') {
                    $targetpage = Page::getByID($_REQUEST['filterCID']);
                    if ($targetpage) {
                        $products->setCIDs($targetpage->getCollectionChildrenArray());
                    }
                }
            }
        }
		
		$products->setItemsPerPage($_REQUEST['itemsPerPage']);
		
		$paginator = $products->getPagination();
        $products = $paginator->getCurrentPageResults();
		
		$products = self::arrangeProducts($products,true);
		
		$collection = new stdClass();
		
		$collection->products = $products;
		
		
		$currentPage = $_REQUEST['ccm_paging_p'];
		$nextPage = $currentPage+1;
		if($nextPage <= $paginator->getTotalPages()){
			$collection->hasNextPage = true;
			$collection->nextPage = $nextPage;
		}else{
			$collection->hasNextPage = false;
		}
		echo json_encode($collection);
		exit();
		
	}
	public function template(){
		
			$html = '<div class="store-product-list-item col-md-4 col-sm-4 col-xs-6" <% if(!visibility){%> style="visibility:hidden;" <% } %>>
  <div class="product_item pro hvr-float">
    <form id="store-form-add-to-cart-list-<%=pID%>">
      <a href="<%=pageLink%>" title="<%=pName%>"> <img src="<%=thumbnail%>" class="img-responsive"> </a>
	  
      <div class="product_des">
        <h5><a class="a_title" href="<%=pageLink%>" title="<%=pName%>"><%=pName%></a></h5>
        <h6> <%=price%> <span style="font-size: 12px;color: #000;text-transform: none;font-weight:normal;"> Ex VAT</span></h6>
        <p> ID: <span><%=sku%></span></p>
      </div>
      <input type="hidden" name="quantity" class="store-product-qty" value="1">
      <input type="hidden" name="pID" value="<%=pID%>">
      <p class="store-btn-add-to-cart-container">
        <button data-add-type="list" data-product-id="<%=pID%>" class="store-btn-add-to-cart btn btn-primary  ">Add to Cart</button>
      </p>
      <p class="store-out-of-stock-label alert alert-warning hidden">Out of Stock</p>
      <a href="<%=pageLink%>" class="pro_but" >More Details</a>
    </form>
  </div>
</div>';
			echo $html;
			exit();
		
		
	}
 
}