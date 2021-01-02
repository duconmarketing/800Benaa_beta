<?php  namespace Concrete\Package\BlockDesigner;

use Package;
use Page;
use SinglePage;
use Symfony\Component\ClassLoader\Psr4ClassLoader as SymfonyClassLoader;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package
{
    protected $pkgHandle = 'block_designer';
    protected $appVersionRequired = '5.7.0.4';
    protected $pkgVersion = '2.1.2';

    public function getPackageName()
    {
        return t("Block Designer");
    }

    public function getPackageDescription()
    {
        return t("Design your own content blocks within a few clicks!");
    }

    public function install()
    {
        $this->on_start();
        $pkg = parent::install();
        SinglePage::add('/dashboard/blocks/block_order', $pkg);
        SinglePage::add('/dashboard/blocks/block_designer', $pkg);
        SinglePage::add('/dashboard/blocks/block_designer/block_config', $pkg);
    }

    public function uninstall()
    {
        parent::uninstall();
    }

    public function upgrade()
    {
        $this->on_start();
        $pkg = $this;
        $sp = Page::getByPath('/dashboard/blocks/block_designer/block_config');
        if ($sp->isError() || (!is_object($sp))) {
            SinglePage::add('/dashboard/blocks/block_designer/block_config', $pkg);
        }
        parent::upgrade();
    }

    public function on_start()
    {
        $strictLoader = new SymfonyClassLoader();
        $strictLoader->addPrefix('\RamonLeenders\BlockDesigner', DIR_PACKAGES . '/block_designer/src');
        $strictLoader->register();
    }

    public function getToolbarDesignerPresets()
    {
        return array(
            '/dashboard/blocks/block_designer' => array(
                'title'             => t('Block Designer'),
                'controller_handle' => 'item_default',
                'pkg_handle'        => 'toolbar_designer',
                'cPath'             => '/dashboard/blocks/block_designer',
            ),
        );
    }

    protected function fieldTypes(){
        return array(
            t("Code"),
            t("Color Picker"),
            t("Date Time"),
            t("Number"),
            t("Text Box"),
        );
    }
}