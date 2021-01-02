<?php  namespace Application\Block\InsideTopHighlight;

defined("C5_EXECUTE") or die("Access Denied.");

use Concrete\Core\Block\BlockController;
use Core;
use File;
use Page;
use Concrete\Core\Editor\LinkAbstractor;

class Controller extends BlockController
{
    public $helpers = array('form');
    public $btFieldsRequired = array();
    protected $btExportFileColumns = array('image');
    protected $btTable = 'btInsideTopHighlight';
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
        return t("Inside Top Highlight");
    }

    public function getSearchableContent()
    {
        $content = array();
        $content[] = $this->Content;
        return implode(" ", $content);
    }

    public function view()
    {
        
        if ($this->image && ($f = File::getByID($this->image)) && is_object($f)) {
            $this->set("image", $f);
        } else {
            $this->set("image", false);
        }
        $this->set('Content', LinkAbstractor::translateFrom($this->Content));
    }

    public function add()
    {
        $this->addEdit();
    }

    public function edit()
    {
        $this->addEdit();
        
        $this->set('Content', LinkAbstractor::translateFromEditMode($this->Content));
    }

    protected function addEdit()
    {
        $this->requireAsset('core/file-manager');
        $this->requireAsset('redactor');
        $this->set('btFieldsRequired', $this->btFieldsRequired);
    }

    public function save($args)
    {
        $args['Content'] = LinkAbstractor::translateTo($args['Content']);
        parent::save($args);
    }

    public function validate($args)
    {
        $e = Core::make("helper/validation/error");
        if (in_array("image", $this->btFieldsRequired) && (trim($args["image"]) == "" || !is_object(File::getByID($args["image"])))) {
            $e->add(t("The %s field is required.", t("Image")));
        }
        if (in_array("Content", $this->btFieldsRequired) && (trim($args["Content"]) == "")) {
            $e->add(t("The %s field is required.", t("Content")));
        }
        return $e;
    }

    public function composer()
    {
        $this->edit();
    }
}