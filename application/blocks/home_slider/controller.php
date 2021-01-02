<?php  namespace Application\Block\HomeSlider;

defined("C5_EXECUTE") or die("Access Denied.");

use Concrete\Core\Block\BlockController;
use Core;
use File;
use Page;

class Controller extends BlockController
{
    public $helpers = array('form');
    public $btFieldsRequired = array();
    protected $btExportFileColumns = array('slideimage');
    protected $btTable = 'btHomeSlider';
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 500;
    protected $btIgnorePageThemeGridFrameworkContainer = false;
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;
    protected $btCacheBlockOutputLifetime = 0;
    protected $pkg = false;
    
    public function getBlockTypeDescription()
    {
        return t("");
    }

    public function getBlockTypeName()
    {
        return t("Home Slider");
    }

    public function view()
    {
        
        if ($this->slideimage && ($f = File::getByID($this->slideimage)) && is_object($f)) {
            $this->set("slideimage", $f);
        } else {
            $this->set("slideimage", false);
        }
    }

    public function add()
    {
        $this->addEdit();
    }

    public function edit()
    {
        $this->addEdit();
    }

    protected function addEdit()
    {
        $this->requireAsset('core/file-manager');
        $this->set('btFieldsRequired', $this->btFieldsRequired);
    }

    public function validate($args)
    {
        $e = Core::make("helper/validation/error");
        if (in_array("slideimage", $this->btFieldsRequired) && (trim($args["slideimage"]) == "" || !is_object(File::getByID($args["slideimage"])))) {
            $e->add(t("The %s field is required.", t("Slide Image")));
        }
        if (in_array("pagelink", $this->btFieldsRequired) && (trim($args["pagelink"]) == "" || $args["pagelink"] == "0" || (($page = Page::getByID($args["pagelink"])) && $page->error !== false))) {
            $e->add(t("The %s field is required.", t("page link")));
        }
        return $e;
    }

    public function composer()
    {
        $this->edit();
    }
}