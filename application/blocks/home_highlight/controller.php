<?php  namespace Application\Block\HomeHighlight;

defined("C5_EXECUTE") or die("Access Denied.");

use Concrete\Core\Block\BlockController;
use Core;
use Concrete\Core\Editor\LinkAbstractor;
use File;
use Page;

class Controller extends BlockController
{
    public $helpers = array('form');
    public $btFieldsRequired = array();
    protected $btExportFileColumns = array('image');
    protected $btTable = 'btHomeHighlight';
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
        return t("Home Highlight");
    }

    public function getSearchableContent()
    {
        $content = array();
        $content[] = $this->content;
        return implode(" ", $content);
    }

    public function view()
    {
        $this->set('content', LinkAbstractor::translateFrom($this->content));
        
        if ($this->image && ($f = File::getByID($this->image)) && is_object($f)) {
            $this->set("image", $f);
        } else {
            $this->set("image", false);
        }
    }

    public function add()
    {
        $this->addEdit();
    }

    public function edit()
    {
        $this->addEdit();
        
        $this->set('content', LinkAbstractor::translateFromEditMode($this->content));
    }

    protected function addEdit()
    {
        $this->requireAsset('redactor');
        $this->requireAsset('core/file-manager');
        $this->set('btFieldsRequired', $this->btFieldsRequired);
    }

    public function save($args)
    {
        $args['content'] = LinkAbstractor::translateTo($args['content']);
        parent::save($args);
    }

    public function validate($args)
    {
        $e = Core::make("helper/validation/error");
        if (in_array("content", $this->btFieldsRequired) && (trim($args["content"]) == "")) {
            $e->add(t("The %s field is required.", t("Content")));
        }
        if (in_array("image", $this->btFieldsRequired) && (trim($args["image"]) == "" || !is_object(File::getByID($args["image"])))) {
            $e->add(t("The %s field is required.", t("Image")));
        }
        if (in_array("pagelink", $this->btFieldsRequired) && (trim($args["pagelink"]) == "" || $args["pagelink"] == "0" || (($page = Page::getByID($args["pagelink"])) && $page->error !== false))) {
            $e->add(t("The %s field is required.", t("Pagelink")));
        }
        return $e;
    }

    public function composer()
    {
        $this->edit();
    }
}