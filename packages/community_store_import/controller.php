<?php
namespace Concrete\Package\CommunityStoreImport;

use Package;
use Route;
use Asset;
use AssetList;
use URL;
use Core;
use Page;
use SinglePage;
use Config;
use Whoops\Exception\ErrorException;

class Controller extends Package
{
    protected $pkgHandle = 'community_store_import';
    protected $appVersionRequired = '5.7.1';
    protected $pkgVersion = '0.9.4';

    public function getPackageDescription()
    {
        return t("Product import for concrete5 Community Store.");
    }

    public function getPackageName()
    {
        return t("Community Store Import");
    }

    public function install()
    {
        $installed = Package::getInstalledHandles();

        if (!(is_array($installed) && in_array('community_store', $installed))) {
            throw new ErrorException(t('This package requires that Community Store is installed.'));
        }

        $pkg = parent::install();

        self::installSinglePage('/dashboard/store/products/import', 'Import', $pkg);

        self::setConfigValue('community_store_import.import_file', null);
        self::setConfigValue('community_store_import.max_execution_time', '60');
        self::setConfigValue('community_store_import.default_image', null);
        self::setConfigValue('community_store_import.csv.delimiter', ',');
        self::setConfigValue('community_store_import.csv.enclosure', '"');
        self::setConfigValue('community_store_import.csv.line_length', 1000);
    }

    public static function installSinglePage($path, $name, $pkg)
    {
        $page = Page::getByPath($path);
        if (!is_object($page) || $page->isError()) {
            $page = SinglePage::add($path, $pkg);
            $page->update(array('cName' => t($name)));
        }
    }

    private function setConfigValue($key, $value)
    {
        if (!Config::has($key)) {
            Config::save($key, $value);
        }
    }

    public function upgrade()
    {
        parent::upgrade();
    }

    public function uninstall()
    {
        parent::uninstall();
    }
}
