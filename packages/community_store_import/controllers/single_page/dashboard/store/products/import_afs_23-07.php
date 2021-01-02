<?php

namespace Concrete\Package\CommunityStoreImport\Controller\SinglePage\Dashboard\Store\Products;

use Concrete\Core\Controller\Controller;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Package\Package as Package;
use Concrete\Core\File\File;
use Log;
use Exception;
use Config;
use Core;

use Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductList;
use Concrete\Package\CommunityStore\Src\CommunityStore\Product\Product;
use Concrete\Package\CommunityStore\Src\Attribute\Key\StoreProductKey as StoreProductKey;
use Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductLocation as StoreProductLocation;
use Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductImage as StoreProductImage;
use Concrete\Package\CommunityStore\Src\CommunityStore\Group\Group as StoreGroup;
use Concrete\Package\CommunityStore\Src\CommunityStore\Product\ProductGroup;
use Concrete\Core\Attribute\Key\Category;


class Import extends DashboardPageController
{
    public $helpers = array('form', 'concrete/asset_library', 'json');
    private $attributes = array();

    public function view()
    {
        $this->loadFormAssets();
        $this->set('pageTitle', t('Product Import'));
    }

    public function loadFormAssets()
    {
        $this->requireAsset('core/file-manager');
        $this->requireAsset('core/sitemap');
        $this->requireAsset('css', 'select2');
        $this->requireAsset('javascript', 'select2');
        $this->set('concrete_asset_library', Core::make('helper/concrete/asset_library'));
        $this->set('form', Core::make('helper/form'));
    }

    public function run()
    {
        $this->saveSettings();

        $MAX_TIME = Config::get('community_store_import.max_execution_time');
        $MAX_EXECUTION_TIME = ini_get('max_execution_time');
        $MAX_INPUT_TIME = ini_get('max_input_time');
        ini_set('max_execution_time', $MAX_TIME);
        ini_set('max_input_time', $MAX_TIME);
        ini_set('auto_detect_line_endings', TRUE);

        $f = \File::getByID(Config::get('community_store_import.import_file'));
        $fname = $_SERVER['DOCUMENT_ROOT'] . $f->getApprovedVersion()->getRelativePath();

        if (!file_exists($fname) || !is_readable($fname)) {
            $this->error->add(t("Import file not found or is not readable."));
            return;
        }

        if (!$handle = @fopen($fname, 'r')) {
            $this->error->add(t('Cannot open file %s.', $fname));
            return;
        }

        $delim = Config::get('community_store_import.csv.delimiter');
        $delim = ($delim === '\t') ? "\t" : $delim;

        $enclosure = Config::get('community_store_import.csv.enclosure');
        $line_length = Config::get('community_store_import.csv.line_length');

        // Get headings
        $csv = fgetcsv($handle, $line_length, $delim, $enclosure);
        $headings = array_map('strtolower', $csv);

        if ($this->isValid($headings)) {
            $this->error->add(t("Required data missing."));
            return;
        }

        // Get attribute headings
        foreach ($headings as $heading) {
            if (preg_match('/^attr_/', $heading)) {
                $this->attributes[] = $heading;
            }
        }

        $updated = 0;
        $added = 0;

        while (($csv = fgetcsv($handle, $line_length, $delim, $enclosure)) !== FALSE) {
            if (count($csv) === 1) {
                continue;
            }

            // Make associative arrray
            $row = array_combine($headings, $csv);
            if (trim($row['psku']) == '') {
                continue;
            }

            /*setup cIDs*/


            if ($row['pdetail'] == '' && $row['pdesc'] != '') {
                $row['pdetail'] = $row['pdesc'];
                $row['pdesc'] = '';
            }
            $row['pdetail'] = '<p>' . $row['pdetail'] . '</p>';
            $row['cID'] = array();
            $collection_id = @explode(',', $row['collection_id']);
            $finish_id = @explode(',', $row['finish_id']);
            $room_id = @explode(',', $row['room_id']);
            $row['cID'] = array_map('trim', @array_merge($collection_id, $finish_id, $room_id));
            /*===================*/
            /*setup fID*/
            //$row['attr_product_type'] = array_map('trim', @explode(',', $row['attr_product_type']));
            $row['pProductGroups'] = array_map('trim', @explode(',', $row['pproductgroup_ids']));
            $row['pnoqty'] = 0;
            $row['pqtyunlim'] = true;
            if ($row['image1_id'] > 0) {
                $row['pfID'] = $row['image1_id'];
            } else {
                $row['pfID'] = Config::get('community_store_import.default_image');
            }

            $row['pifID'] = array();
            if ($row['image2_id'] != '') {
                $row['pifID'] = array_map('trim', @explode(',', $row['image2_id']));
            }
           /* if ($row['psku'] == 'SOM043') {
                break;
            }*/

            /*================================*/
            $p = Product::getBySKU($row['psku']);
            if ($p instanceof Product) {
                $this->update($p, $row);
                $updated++;
            } else {
                $p = $this->add($row);
                $added++;
            }
            // @TODO: dispatch events - see Products::save()
        }


        $this->set('success', $this->get('success') . "Import completed: $added products added, $updated products updated.");
        Log::addNotice($this->get('success'));

        ini_set('auto_detect_line_endings', FALSE);
        ini_set('max_execution_time', $MAX_EXECUTION_TIME);
        ini_set('max_input_time', $MAX_INPUT_TIME);
    }

    protected function getCategoryObject()
    {
        return Category::getByHandle('store_product');
    }

    private function setAttributes($product, $row)
    {
        foreach ($this->attributes as $attr) {
			//echo  $attr;die('sdfdsf');
			$ak = preg_replace('/^attr_/', '', $attr);
           // $ak_handle = preg_replace('/^attr_/', '', $attr);
            //$ak = $this->getCategoryObject()->getController()->getByHandle($ak_handle);
           // if (is_object($ak)) {
              //  $product->setAttribute($ak, $row[$attr]);
           // }
		   if (StoreProductKey::getByHandle($ak)) {
                    $product->setAttribute($ak, $row[$attr]);
                }
        }
    }

    private function setGroups($product, $row)
    {
        if ($row['pproductgroups']) {
            $pGroupNames = explode(',', $row['pproductgroups']);
            $pGroupIDs = array();
            foreach ($pGroupNames as $pGroupName) {
                $pgID = StoreGroup::getByName(trim($pGroupName));
                if (!$pgID instanceof StoreGroup) {
                    $pgID = StoreGroup::add(trim($pGroupName));
                }
                $pGroupIDs[] = $pgID;
            }
            $data['pProductGroups'] = $pGroupIDs;

            // Update groups
            ProductGroup::addGroupsForProduct($data, $product);
        }
    }

    private function add($row)
    {
        $data = array(
            'pSKU' => $row['psku'],
            'pName' => $row['pname'],
            'pDesc' => trim($row['pdesc']),
            'pDetail' => trim($row['pdetail']),
            'pCustomerPrice' => $row['pcustomerprice'],
            'pFeatured' => $row['pfeatured'],
            'pQty' => $row['pqty'],
            'pNoQty' => $row['pnoqty'],
            'pTaxable' => $row['ptaxable'],
            'pActive' => $row['pactive'],
            'pShippable' => $row['pshippable'],
            'pCreateUserAccount' => $row['pcreateuseraccount'],
            'pAutoCheckout' => $row['pautocheckout'],
            'pExclusive' => $row['pexclusive'],

            'pPrice' => $row['pprice'],
            'pSalePrice' => $row['psaleprice'],
            'pPriceMaximum' => $row['ppricemaximum'],
            'pPriceMinimum' => $row['ppriceminimum'],
            'pPriceSuggestions' => $row['ppricesuggestions'],
            'pQtyUnlim' => $row['pqtyunlim'],
            'pBackOrder' => $row['pbackorder'],
            'pLength' => $row['plength'],
            'pWidth' => $row['pwidth'],
            'pHeight' => $row['pheight'],
            'pWeight' => $row['pweight'],
            'pNumberItems' => $row['pnumberitems'],

            // CS v1.4.2+
            'pMaxQty' => $row['pmaxqty'],
            'pQtyLabel' => $row['pqtylabel'],
            'pAllowDecimalQty' => (isset($row['pallowdecimalqty']) ? $row['pallowdecimalqty'] : false),
            'pQtySteps' => $row['pqtysteps'],
            'pSeperateShip' => $row['pseperateship'],
            'pPackageData' => $row['ppackagedata'],

            // CS v2+
            'pQtyLabel' => (isset($row['pqtylabel']) ? $row['pqtylabel'] : ''),
            'pMaxQty' => (isset($row['pmaxqty']) ? $row['pmaxqty'] : 0),
            // Not supported in CSV data
            'cID' => $row['cID'],
            'pfID' => $row['pfID'],
            'pifID' => $row['pifID'],
            'pVariations' => false,
            'pQuantityPrice' => false,
            'pTaxClass' => 1        // 1 = default tax class
        );

        // Save product
        $p = Product::saveProduct($data);

        //save category locations
        StoreProductLocation::addLocationsForProduct($row, $p);

        //save images
        StoreProductImage::addImagesForProduct($row, $p);

        // Add product attributes
        $this->setAttributes($p, $row);

        // Add product groups
        $this->setGroups($p, $row);

        // Add groups
        ProductGroup::addGroupsForProduct($row, $p);

        return $p;
    }

    private function update($p, $row)
    {
        if ($row['psku']) $p->setSKU($row['psku']);
//        if ($row['pname']) $p->setName($row['pname']);
//        if ($row['pdesc']) $p->setDescription($row['pdesc']);
////        if ($row['pdetail']) $p->setDetail($row['pdetail']);
//        if ($row['pfeatured']) $p->setIsFeatured($row['pfeatured']);
//        if ($row['pqty']) $p->setQty($row['pqty']);
//        if ($row['pnoqty']) $p->setNoQty($row['pnoqty']);
//        if ($row['ptaxable']) $p->setISTaxable($row['ptaxable']);
//        if ($row['pactive']) $p->setIsActive($row['pactive']);
//        if ($row['pshippable']) $p->setIsShippable($row['pshippable']);
//        if ($row['pcreateuseraccount']) $p->setCreatesUserAccount($row['pcreateuseraccount']);
//        if ($row['pautocheckout']) $p->setAutoCheckout($row['pautocheckout']);
//        if ($row['pexclusive']) $p->setIsExclusive($row['pexclusive']);

        if ($row['pprice']) $p->setPrice($row['pprice']);
//        if ($row['psaleprice']) $p->setSalePrice($row['psaleprice']);
//        if ($row['ppricemaximum']) $p->setPriceMaximum($row['ppricemaximum']);
//        if ($row['ppriceminimum']) $p->setPriceMinimum($row['ppriceminimum']);
//        if ($row['ppricesuggestions']) $p->setPriceSuggestions($row['ppricesuggestions']);
//        if ($row['pqtyunlim']) $p->setIsUnlimited($row['pqtyunlim']);
//        if ($row['pbackorder']) $p->setAllowBackOrder($row['pbackorder']);
//        if ($row['plength']) $p->setLength($row['plength']);
//        if ($row['pwidth']) $p->setWidth($row['pwidth']);
//        if ($row['pheight']) $p->setHeight($row['pheight']);
//        if ($row['pweight']) $p->setWeight($row['pweight']);
//        if ($row['pnumberitems']) $p->setNumberItems($row['pnumberitems']);

        // CS v1.4.2+
//        if ($row['pmaxqty']) $p->setMaxQty($row['pmaxqty']);
//        if ($row['pqtylabel']) $p->setQtyLabel($row['pqtylabel']);
//        if ($row['pallowdecimalqty']) $p->setAllowDecimalQty($row['pallowdecimalqty']);
//        if ($row['pqtysteps']) $p->setQtySteps($row['pqtysteps']);
//        if ($row['pseparateship']) $p->setSeparateShip($row['pseparateship']);
//        if ($row['ppackagedata']) $p->setPackageData($row['ppackagedata']);
//        if (!$p->getImageId())
//            $p->setImageId($row['pfID']);

        //save category locations
//        StoreProductLocation::addLocationsForProduct($row, $p);

        //save images
//        StoreProductImage::addImagesForProduct($row, $p);

         //Product attributes
        $this->setAttributes($p, $row);

        // Product groups
//        $this->setGroups($p, $row);

        $p = $p->save();

        return $p;
    }

    private function saveSettings()
    {
        $data = $this->post();

        // @TODO: Validate post data

        Config::save('community_store_import.import_file', $data['import_file']);
        Config::save('community_store_import.default_image', $data['default_image']);
        Config::save('community_store_import.max_execution_time', $data['max_execution_time']);
        Config::save('community_store_import.csv.delimiter', $data['delimiter']);
        Config::save('community_store_import.csv.enclosure', $data['enclosure']);
        Config::save('community_store_import.csv.line_length', $data['line_length']);
    }

    private function isValid($headings)
    {
        // @TODO: implement

        // @TODO: interrogate database for non-null fields
        $dbname = Config::get('database.connections.concrete.database');

        /*
            SELECT GROUP_CONCAT(column_name) nonnull_columns
            FROM information_schema.columns
            WHERE table_schema = '$dbname'
                AND table_name = 'CommunityStoreProducts'
                AND is_nullable = 'NO'
                // pfID is excluded because it is not-null but also an optional field
                AND column_name not in ('pID', 'pfID', pDateAdded');
        */

        return (false);
    }
}

