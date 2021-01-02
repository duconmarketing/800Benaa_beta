<?php

namespace Concrete\Package\NetworkStandard;

use Package;
use Route;
use Whoops\Exception\ErrorException;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Payment\Method as PaymentMethod;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package
{
    protected $pkgHandle = 'network_standard';
    protected $appVersionRequired = '5.7.2';
    protected $pkgVersion = '1.0';

    public function getPackageDescription()
    {
        return t("Network International Standard Payment Method for Community Store");
    }

    public function getPackageName()
    {
        return t("Network Payment Method");
    }

    public function install()
    {
        $installed = Package::getInstalledHandles();
        if(!(is_array($installed) && in_array('community_store',$installed)) ) {
            throw new ErrorException(t('This package requires that Community Store be installed'));
        } else {
			
			
            $pkg = parent::install();
            $pm = new PaymentMethod();
            $pm->add('network_standard','Network Standard',$pkg);
        }

    }
    public function uninstall()
    {
        $pm = PaymentMethod::getByHandle('network_standard');
        if ($pm) {
            $pm->delete();
        }
        $pkg = parent::uninstall();
    }

    public function on_start() {
        //Route::register('/checkout/networkresponse','\Concrete\Package\NetworkStandard\Src\CommunityStore\Payment\Methods\NetworkStandard\NetworkonlieBitmapPaymentIntegration::decryptData');
		
		Route::register('/checkout/networkresponse','\Concrete\Package\NetworkStandard\Src\CommunityStore\Payment\Methods\NetworkStandard\NetworkStandardPaymentMethod::decryptData');
		
    }
}
?>