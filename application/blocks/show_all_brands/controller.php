<?php 
namespace Application\Block\ShowAllBrands;
defined("C5_EXECUTE") or die("Access Denied.");
use Concrete\Core\Block\BlockController;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Group\GroupList as StoreGroupList;
use Page;
use Core;
use Loader;

class Controller extends BlockController
{
    public $helpers = array (
  0 => 'form',
);
    public $btFieldsRequired = array (
);
    protected $btExportFileColumns = array (
);
    protected $btTable = 'btShowAllBrands';
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 500;
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;
    protected $btCacheBlockOutputLifetime = 0;

    public function getBlockTypeDescription()
    {
        return t("");
    }

    public function getBlockTypeName()
    {
        return t("Show all Brands");
    }

    public function getSearchableContent()
    {
    	$content = array();
		$content[] = $this->ShowallBrands;
		return implode(" ", $content);
	}

    public function view()
    {
		$page = Page::getCurrentPage();
		$getpageid=$page->getCollectionID();
		$db = Loader::db();
//                $getpagelocation= $db->GetAll("SELECT * FROM CommunityStoreProductLocations WHERE cID=".$getpageid."");
		$getpagelocation= $db->GetAll("SELECT * FROM CommunityStoreProductLocations INNER JOIN CommunityStoreProducts ON CommunityStoreProducts.pID = CommunityStoreProductLocations.pID WHERE CommunityStoreProductLocations.cID=".$getpageid." AND CommunityStoreProducts.pActive != 0");
		if(!empty($getpagelocation)){
		foreach ($getpagelocation as $getpagelocationval) {
                    $proval[]=$getpagelocationval['pID'];
		}
		
		$productid=implode(',',$proval);
		
                $productTotal = count($getpagelocation);
		$brandCount = 0;
		$getproductgroup=$db->GetAll("SELECT *, count(*) as pCount FROM CommunityStoreProductGroups WHERE pID IN(".$productid.") group by gID");

		if(!empty($getproductgroup)){
                    foreach ($getproductgroup as $getproductgroupval) {
                        $progroupval[]=$getproductgroupval['gID'];
                        $brandCount += $getproductgroupval['pCount'];
                    }
                }
		if(!empty($getproductgroup)){

                    if(!empty($progroupval)){
                        $productgroupid=implode(',',$progroupval);
                    }

                        $sortedGroupList = array();
                        if($productgroupid){
                        $checkgroup='WHERE gID IN('.$productgroupid.')'; 
                        //$sortedGroupList = Loader::db()->getAll("SELECT * FROM CommunityStoreGroups ORDER BY groupName");
                        $sortedGroupList = Loader::db()->getAll("SELECT * FROM CommunityStoreGroups ".$checkgroup." ORDER BY groupName");

                        $key = 0;
                        foreach ($getproductgroup as $group){
                            $key = array_search($group['gID'], array_column($sortedGroupList, 'gID'));
                            $sortedGroupList[$key]['pCount'] = $group['pCount'];        // To get the product count per each Brand, as selco :-|
                        }                

                        //if($sortedGroupList){
                        $grouplist = StoreGroupList::getGroupList();
                        //}
                        $this->set("grouplist",$grouplist);
        //		$this->set("sortedGroupList",$sortedGroupList);

                    //}
                    }
                }
                $noBrand = array('gID' => 0, 'pCount' => ($productTotal - $brandCount), 'groupName' => 'No Brand');
                $sortedGroupList[] = $noBrand;
                $this->set("sortedGroupList",$sortedGroupList);
                
                //---- To create price filtering ---------------------//
                
                $maxPrice = $db->GetOne("SELECT max(pPrice) FROM CommunityStoreProducts WHERE pID IN(".$productid.")");
                $maxPrice = $this->getCeiledPrice($maxPrice);   
                $maxPrice = ($maxPrice< 50) ? 50 : $maxPrice;
                
                $firstFilter = round((25/100) * $maxPrice);
                $secondFilter = round((50/100) * $maxPrice);
                $thirdFilter = round((75/100) * $maxPrice);
                
                $priceArray = array();
                $priceArray[0]['price_query'] = '0-'. $firstFilter;
                $priceArray[0]['price_name'] = 'AED0 - AED'.$firstFilter;
                $priceArray[1]['price_query'] = ($firstFilter + 1) . '-'. $secondFilter;
                $priceArray[1]['price_name'] = 'AED'. ($firstFilter + 1).' - AED'.($secondFilter);
                $priceArray[2]['price_query'] = ($secondFilter + 1). '-'. ($thirdFilter);
                $priceArray[2]['price_name'] = 'AED'.($secondFilter + 1).' - AED'.($thirdFilter);
                $priceArray[3]['price_query'] = ($thirdFilter + 1). '-'. $maxPrice;
                $priceArray[3]['price_name'] = 'AED'. ($thirdFilter + 1). ' - AED'.$maxPrice;
                
                $this->set("maxPrice", $maxPrice);
                $this->set("priceArray", $priceArray);
	}
    }

    public function add()
    {
        $this->set('btFieldsRequired', $this->btFieldsRequired);
        
    }

    public function edit()
    {
        $this->set('btFieldsRequired', $this->btFieldsRequired);
        
    }

    public function save($args)
    {
       if(empty($args['ShowallBrands'])){
		   $args['ShowallBrands']='0';
		}
        parent::save($args);
    }

    public function validate($args)
    {
        $e = Loader::helper("validation/error");
        if(in_array("ShowallBrands",$this->btFieldsRequired) && trim($args["ShowallBrands"]) == ""){
            $e->add(t("The %s field is required.", "Show all Brands"));
        }
        return $e;
    }
    
    public function getCeiledPrice($price){
        
        if($price >= 1000) {
         return ceil($price / 1000) * 1000;
        }
        else {
          $length = strlen(ceil($price));
          $times = str_pad('1', $length, "0");
          return ceil($price / $times) * $times;
        }
    }
    
}