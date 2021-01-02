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
		$getpagelocation= $db->GetAll("SELECT * FROM CommunityStoreProductLocations WHERE cID=".$getpageid."");
		if(!empty($getpagelocation)){
		foreach ($getpagelocation as $getpagelocationval) {
		$proval[]=$getpagelocationval['pID'];
		}
		
		$productid=implode(',',$proval);
		
		
		$getproductgroup=$db->GetAll("SELECT * FROM CommunityStoreProductGroups WHERE pID IN(".$productid.")");
		//echo $getproductgroup;die('sdfdsf');
		if(!empty($getproductgroup)){
		foreach ($getproductgroup as $getproductgroupval) {
		$progroupval[]=$getproductgroupval['gID'];
		}
		if(!empty($progroupval)){
		$productgroupid=implode(',',$progroupval);
		}
		
		if($productgroupid){
		$checkgroup='WHERE gID IN('.$productgroupid.')'; 
		//$sortedGroupList = Loader::db()->getAll("SELECT * FROM CommunityStoreGroups ORDER BY groupName");
		$sortedGroupList = Loader::db()->getAll("SELECT * FROM CommunityStoreGroups ".$checkgroup." ORDER BY groupName");
		//if($sortedGroupList){
		$grouplist = StoreGroupList::getGroupList();
		//}
		$this->set("grouplist",$grouplist);
		$this->set("sortedGroupList",$sortedGroupList);
		
		//}
		}}
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
    
}