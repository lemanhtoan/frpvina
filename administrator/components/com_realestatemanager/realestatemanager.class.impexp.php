<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . 
    basename(__FILE__) . ' is not allowed.');

/**
 *
 * @package  RealEstateManager
 * @copyright 2012 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com); 
 * Homepage: http://www.ordasoft.com
 * @version: 3.9 Free
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *
 */
$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'];
//$database = JFactory::getDBO();
// load language
require_once ($mosConfig_absolute_path . "/components/com_realestatemanager/realestatemanager.class.feature.php");
require_once ($mosConfig_absolute_path . "/components/com_realestatemanager/realestatemanager.main.categories.class.php");
require_once $mosConfig_absolute_path . "/administrator/components/com_realestatemanager/language.php";

function print_vars($obj) {
    $arr = get_object_vars($obj);
    while (list($prop, $val) = each($arr))
        if (class_exists($val))
            print_vars($val);
        else
            echo "\t $prop = $val\n<br />";
}

function print_methods($obj) {
    $arr = get_class_methods(get_class($obj));
    foreach ($arr as $method)
        echo "\tfunction $method()\n <br />";
}

if (PHP_VERSION >= 5) {

    // Emulate the old xslt library functions
    function xslt_create() {
        return new XsltProcessor();
    }

    function xslt_process($xsltproc, $xml_arg, $xsl_arg, $xslcontainer = null, $args = null, $params = null) {
        // Create instances of the DomDocument class
        $xml = new DomDocument;
        $xsl = new DomDocument;

        // Load the xml document and the xsl template
        $xml->load($xml_arg);
        $xsl->load($xsl_arg);

        // Load the xsl template
        $xsltproc->importStyleSheet($xsl);

        // Set parameters when defined
        if ($params)
            foreach ($params as $param => $value)
                $xsltproc->setParameter("", $param, $value);
        // Start the transformation
        $processed = $xsltproc->transformToXML($xml);
        // Put the result in a file when specified
        if ($xslcontainer)
            return @file_put_contents($xslcontainer, $processed); else
            return $processed;
    }

    function xslt_free($xsltproc) {
        unset($xsltproc);
    }

}

class mosRealEstateManagerImportExport {

    /**
     * Imports the lines given to this method into the database and writes a
     * table containing the information of the imported houses.
     * The imported houses will be set to [not published] 
     * Format: #;id;isbn;title;author;language
     * @param array lines - an array of lines read from the file
     * @param int catid - the id of the category the houses should be added to 
     */
    static function importHousesCSV($lines, $catid) {
        global $database;
        $retVal = array();
        $i = 0;
        foreach ($lines as $k => $line) {
            $tmp = array();
            if (trim($line) == "")
                continue;
            $line = explode('|', $line);
            array_push($line, "", "");
            $house = new mosRealEstateManager($database);

            $house->houseid = trim($line[0]);
            $house->description = $line[1];
            $house->link = $line[2];
            $house->listing_type = $line[3];
            if (($house->listing_type) != '') {
                $listing_type[_REALESTATE_MANAGER_OPTION_SELECT] = 0;
                $listing_type[_REALESTATE_MANAGER_OPTION_FOR_RENT] = 1;
                $listing_type[_REALESTATE_MANAGER_OPTION_FOR_SALE] = 2;
                $house->listing_type = $listing_type[$house->listing_type];
            } else
                $house->listing_type = 0;
                $house->price = $line[4];
                $house->priceunit = $line[5];
                $house->htitle = $line[6];
                $house->hcountry = $line[7];
                $house->hregion = $line[8];
                $house->hcity = $line[9];
                $house->hzipcode = $line[11];
                $house->hlocation = $line[12];
                $house->hlatitude = $line[13];
                $house->hlongitude = $line[14];
                $house->map_zoom = $line[15];
                $house->bathrooms = $line[16];
                $house->bedrooms = $line[17];
                $house->contacts = $line[19];
                $house->image_link = $line[20];
                $house->listing_status = $line[21];
            if (($house->listing_status) != '') {
                $listing_status[_REALESTATE_MANAGER_OPTION_SELECT] = 0;
                $listing_status1 = explode(',', _REALESTATE_MANAGER_OPTION_LISTING_STATUS);
                $k = 1;
                foreach ($listing_status1 as $listing_status2) {
                    $listing_status[$listing_status2] = $k;
                    $k++;
                }
                $house->listing_status = $listing_status[$house->listing_status];
            } else
                $house->listing_status = 0;
            $house->property_type = $line[23];
            if (($house->property_type) != '') {
                $property_type[_REALESTATE_MANAGER_OPTION_SELECT] = 0;
                $property_type1 = explode(',', _REALESTATE_MANAGER_OPTION_PROPERTY_TYPE);
                $k = 1;
                foreach ($property_type1 as $property_type2) {
                    $property_type[$property_type2] = $k;
                    $k++;
                }
                $house->property_type = $property_type[$house->property_type];
            } else
                $house->property_type = 0;

                $house->year = $line[25];
                $house->agent = $line[26];
                $house->house_size = $line[29];
                $house->lot_size = $line[31];
                $house->garages = $line[32];
                $house->date = $line[38];
                $house->hits = $line[39];
                $house->published = $line[40];
                $house->owneremail = trim($line[41]);
                $house->featured_clicks = $line[42];
                $house->featured_shows = $line[43];
                $house->extra1 = $line[44];
                $house->extra2 = $line[45];
                $house->extra3 = $line[46];
                $house->extra4 = $line[47];
                $house->extra5 = $line[48];
                $house->extra6 = $line[49];
                $house->extra7 = $line[50];
                $house->extra8 = $line[51];
                $house->extra9 = $line[52];
                $house->extra10 = $line[53];
                $house->language = $line[54];
                $house->catid = $catid;
                $tmp[0] = $i;
                $tmp[1] = $house->houseid;
                $tmp[2] = $house->hlocation;
                $tmp[3] = $house->htitle;
            if (!$house->check() || !$house->store()) {
                $tmp[5] = $house->getError();
            } else {
                $tmp[5] = "OK";
                $house->saveCatIds($house->catid);
            }
            if (version_compare(JVERSION, "3.0.0", "lt")) {
                $house->checkin();
            }
            $retVal[$i] = $tmp;
            $i++;
        }

        return $retVal;
    }

    static function getXMLItemValue($item, $item_name) {
        $house_items = $item->getElementsByTagname($item_name);
        
        $house_item = $house_items->item(0);
       
        if (NULL != $house_item)
            return $house_item->nodeValue;
        else
            return "";
    }

    static function findCategory(&$categories, $new_category) {
        global $database;
        // print_r($categories) ;
        foreach ($categories as $category)
           
            if ($category->old_id == $new_category->old_id)
                return $category;
        $new_parent_id = -1;
        if ($new_category->old_parent_id != 0) {
            foreach ($categories as $category) {
                if ($category->old_id == $new_category->old_parent_id) {
                    $new_parent_id = $category->id;
                    break;
                }
            }
        } else
            $new_parent_id = 0;

        //sanity test
        if ($new_parent_id === -1) {
            echo "error in import !";
            exit;
        }
        $row = new mainRealEstateCategories($database);
        $row->section = 'com_realestatemanager';
        $row->parent_id = $new_parent_id;
        $row->name = $new_category->name;
        $row->title = $new_category->title;
        $row->published = $new_category->published;
        $row->params = $new_category->params;
        $row->params2 = $new_category->params2;
        $row->language = $new_category->language;
        $row->associate_category = $new_category->associate_category;
        
        
        if (!$row->check()) {
            echo "error in import2 !";
            exit;
            exit();
        }
        if (!$row->store()) {
            echo "error in import3 !";
            exit;
            exit();
        }

        $row->updateOrder("section='com_realestatemanager' AND parent_id='$row->parent_id'");
        $new_category->id = $row->id;
        $categories[] = $new_category;
        return $new_category;
    }
    
    static function updateAssociateCategories($infoArr){
 
        $dataToUpdate = array();
        global $database;
        for($i = 0; $i < count($infoArr); $i++){ 
            if(isset($infoArr[$i]['associate']) && $infoArr[$i]['associate']){
                $currentAssocId = array();
                $newObjAssociate = unserialize($infoArr[$i]['associate']);
             
                foreach ($newObjAssociate as $key=>$value){
                    
                    if($value && $value != 0){
                        for($j = 0; $j < count($infoArr); $j++){
                            if(isset($infoArr[$j]['oldId']) && $infoArr[$j]['oldId'] == $value){ 
                                $newObjAssociate[$key] = $infoArr[$j]['newId'];
                                $currentAssocId[] = $infoArr[$j]['newId'];
                                break;
                            }
                        }
                    }         
                } 
                $newSerializAssoc = serialize($newObjAssociate);
                $currentAssocIdToString = implode(',', $currentAssocId);
                if(!isset($dataToUpdate[$newSerializAssoc])){
                    $dataToUpdate[$newSerializAssoc] = $currentAssocIdToString;
                }
            }    
        }  
        if(!empty($dataToUpdate)){  
            foreach ($dataToUpdate as $key=>$value){
                if(isset($key) && $key !="" && isset($value) && $value !="" ){
                    $query = "UPDATE #__rem_main_categories
                              SET associate_category = '$key'
                              WHERE id in ($value) ";
                    $database->setQuery($query);
                    $database->query();
                }
            }
        }
    }
        
    static function updateAssociateHouses($infoArr){


        $dataToUpdate = array();
        global $database;
        for($i = 0; $i < count($infoArr); $i++){ 
            if(isset($infoArr[$i]['associate']) && $infoArr[$i]['associate']){
                $currentAssocId = array();
                $newObjAssociate = unserialize($infoArr[$i]['associate']);
             
                foreach ($newObjAssociate as $key=>$value){
                    
                    if($value && $value != 0){
                        for($j = 0; $j < count($infoArr); $j++){
                            if(isset($infoArr[$j]['oldId']) && $infoArr[$j]['oldId'] == $value){ 
                                $newObjAssociate[$key] = $infoArr[$j]['newId'];
                                $currentAssocId[] = $infoArr[$j]['newId'];
                                break;
                            }
                        }
                    }         
                } 
                $newSerializAssoc = serialize($newObjAssociate);
                $currentAssocIdToString = implode(',', $currentAssocId);
                if(!isset($dataToUpdate[$newSerializAssoc])){
                    $dataToUpdate[$newSerializAssoc] = $currentAssocIdToString;
                }
            }    
        }  
        if(!empty($dataToUpdate)){  
            foreach ($dataToUpdate as $key=>$value){ 
                $query = "UPDATE #__rem_houses
                          SET associate_house = '$key'
                          WHERE id in ($value) ";
                $database->setQuery($query);
                $database->query();  
            }
        }
    }

//******************   begin add for import XML format   ****************************
    static function importHousesXML($files_name_pars, $catid) {
        global $database;
        $retVal = array();
        $k = 0;
        $new_categories = array();
        $new_relate_ids = array();

        $dom = new domDocument('1.0', 'utf-8');
        $dom->load($files_name_pars);

        if ($catid === null) {
            mosRealEstateManagerImportExport::clearDatabase();
            $cat_list = $dom->getElementsByTagname('category');
            $associateSaveArr = array();
            for ($i = 0; $i < $cat_list->length; $i++) {
                $category = $cat_list->item($i);
                $new_category = new stdClass();
                if (mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_section') == 'com_realestatemanager') {
                    $new_category->old_id = mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_id');
                    $new_category->old_parent_id = mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_parent_id');
                    $new_category->old_asset_id = mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_asset_id');
                    $new_category->name = mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_name');
                    $new_category->title = mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_title');
                    $new_category->alias = mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_alias');
                    $new_category->image = mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_image');
                    $new_category->section = mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_section');
                    $new_category->image_position = mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_image_position');
                    $new_category->description = mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_description');
                    $new_category->published = mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_published');
                    $new_category->checked_out = mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_checked_out');
                    $new_category->checked_out_time = mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_checked_out_time');
                    $new_category->editor = mosRealEstateManagerImportExport::getXMLItemValue($category, 'editor');
                    $new_category->ordering = mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_ordering');
                    $new_category->access = mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_access');
                    $new_category->count = mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_count');
                    $new_category->params = mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_params');
                    if ($new_category->params == '') $new_category->params = '-2';
                    $new_category->params2 = mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_params2');
                    $new_category->language = mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_language');
                    $new_category->associate_category = mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_associate_category');
                    $new_category = mosRealEstateManagerImportExport::findCategory($new_categories, $new_category);
                    
                    $ussuesArray = array();
                    $ussuesArray["associate"] = mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_associate_category');
                    $ussuesArray["oldId"] = mosRealEstateManagerImportExport::getXMLItemValue($category, 'category_id');
                    $ussuesArray["newId"] = $new_category->id;
                    
                    $associateSaveArr[] = $ussuesArray;
                }
            }
            //update accosiate for categoris
            mosRealEstateManagerImportExport::updateAssociateCategories($associateSaveArr); 

        }

        $feature_list = $dom->getElementsByTagname('feature');
        for ($i = 0; $i < $feature_list->length; $i++) {
            $feature = $feature_list->item($i);
            $new_feature = new mosRealEstateManager_feature($database);
            $old_id = mosRealEstateManagerImportExport::getXMLItemValue($feature, 'feature_id');
            $new_feature->name = mosRealEstateManagerImportExport::getXMLItemValue($feature, 'feature_name');
            $new_feature->categories = mosRealEstateManagerImportExport::getXMLItemValue($feature, 'feature_categories');
            $new_feature->published = mosRealEstateManagerImportExport::getXMLItemValue($feature, 'feature_published');
            $new_feature->image_link = mosRealEstateManagerImportExport::getXMLItemValue($feature, 'feature_image_link');
            if (!$new_feature->check() || !$new_feature->store()) {
                $tmp[5] = $new_feature->getError();
            } else {
                $database->setQuery("UPDATE #__rem_feature SET id =" . $old_id . " WHERE id = " . $new_feature->id . "");
                $database->query();
                $tmp[5] = "OK";
            }
        }

        $house_list = $dom->getElementsByTagname('house');
     
        $associateSaveArr = array();
        for ($i = 0; $i < $house_list->length; $i++) {
        
            $house_class = new mosRealEstateManager($database);
            $house = $house_list->item($i);
            
            //get HouseID
            $houseid = $house_class->houseid = mosRealEstateManagerImportExport::getXMLItemValue($house, 'houseid');
            $old_house_id = mosRealEstateManagerImportExport::getXMLItemValue($house, 'id');
           
            //get rent ID
            $house_class->fk_rentid = mosRealEstateManagerImportExport::getXMLItemValue($house, 'fk_rentid');
            //get description
            $house_description = $house_class->description = mosRealEstateManagerImportExport::getXMLItemValue($house, 'description');
            //get link
            $house_class->link = mosRealEstateManagerImportExport::getXMLItemValue($house, 'link');
            //get listing_type
            $house_class->listing_type = mosRealEstateManagerImportExport::getXMLItemValue($house, 'listing_type');
            if (($house_class->listing_type) != '') {
                $listing_type[_REALESTATE_MANAGER_OPTION_SELECT] = 0;
                $listing_type[_REALESTATE_MANAGER_OPTION_FOR_RENT] = 1;
                $listing_type[_REALESTATE_MANAGER_OPTION_FOR_SALE] = 2;
                $house_class->listing_type = $listing_type[$house_class->listing_type];
            } else
                $house_class->listing_type = 0;

            //get listing status
            $house_class->listing_status = mosRealEstateManagerImportExport::getXMLItemValue($house, 'listing_status');
            if (($house_class->listing_status) != '') {
                $listing_status[_REALESTATE_MANAGER_OPTION_SELECT] = 0;
                $listing_status1 = explode(',', _REALESTATE_MANAGER_OPTION_LISTING_STATUS);
                $k = 1;
                foreach ($listing_status1 as $listing_status2) {
                    $listing_status[$listing_status2] = $k;
                    $k++;
                }
                $house_class->listing_status = $listing_status[$house_class->listing_status];
            } else
                $house_class->listing_status = 0;
            //get property type
            $house_class->property_type = mosRealEstateManagerImportExport::getXMLItemValue($house, 'property_type');
            if (($house_class->property_type) != '') {
                $property_type[_REALESTATE_MANAGER_OPTION_SELECT] = 0;
                $property_type1 = explode(',', _REALESTATE_MANAGER_OPTION_PROPERTY_TYPE);
                $k = 1;
                foreach ($property_type1 as $property_type2) {
                    $property_type[$property_type2] = $k;
                    $k++;
                }
                $house_class->property_type = $property_type[$house_class->property_type];
            } else
                $house_class->property_type = 0;

             //get price
            $house_class->price = mosRealEstateManagerImportExport::getXMLItemValue($house, 'price');
            $house_priceunit = $house_class->priceunit = mosRealEstateManagerImportExport::getXMLItemValue($house, 'priceunit');
            //get Title(house)
            $house_htitle = $house_class->htitle = mosRealEstateManagerImportExport::getXMLItemValue($house, 'htitle');
            //get country
            $house_class->hcountry = mosRealEstateManagerImportExport::getXMLItemValue($house, 'hcountry');
            //get region
            $house_class->hregion = mosRealEstateManagerImportExport::getXMLItemValue($house, 'hregion');
            //get city
            $house_class->hcity = mosRealEstateManagerImportExport::getXMLItemValue($house, 'hcity');
            //get zipcode
            $house_class->hzipcode = mosRealEstateManagerImportExport::getXMLItemValue($house, 'hzipcode');
            //get location
            $house_hlocation = $house_class->hlocation = mosRealEstateManagerImportExport::getXMLItemValue($house, 'hlocation');
            //get latitude
            $house_hlatitude = $house_class->hlatitude = mosRealEstateManagerImportExport::getXMLItemValue($house, 'hlatitude');
            //get longitude
            $house_hlongitude = $house_class->hlongitude = mosRealEstateManagerImportExport::getXMLItemValue($house, 'hlongitude');
            //get map_zoom
            $house_map_zoom = $house_class->map_zoom = mosRealEstateManagerImportExport::getXMLItemValue($house, 'map_zoom');
            //get bathrooms
            $house_class->bathrooms = mosRealEstateManagerImportExport::getXMLItemValue($house, 'bathrooms');
            //get bedrooms
            $house_class->bedrooms = mosRealEstateManagerImportExport::getXMLItemValue($house, 'bedrooms');
            //get rooms
            $house_class->rooms = mosRealEstateManagerImportExport::getXMLItemValue($house, 'rooms');
            //get contacts
            $house_class->contacts = mosRealEstateManagerImportExport::getXMLItemValue($house, 'contacts');
             //get contacts
            $house_class->image_link = mosRealEstateManagerImportExport::getXMLItemValue($house, 'image_link');
            //get year
            $house_class->year = mosRealEstateManagerImportExport::getXMLItemValue($house, 'year');
            //get agent
            $house_class->agent = mosRealEstateManagerImportExport::getXMLItemValue($house, 'agent');
            //get lot_size
            $house_class->lot_size = mosRealEstateManagerImportExport::getXMLItemValue($house, 'lot_size');
            //get house_size
            $house_class->house_size = mosRealEstateManagerImportExport::getXMLItemValue($house, 'house_size');
            //get garages
            $house_class->garages = mosRealEstateManagerImportExport::getXMLItemValue($house, 'garages');
            //get date
            $house_class->date = mosRealEstateManagerImportExport::getXMLItemValue($house, 'date');
            //get hits
            $house_class->hits = mosRealEstateManagerImportExport::getXMLItemValue($house, 'hits');
            //get published
            $house_class->published = mosRealEstateManagerImportExport::getXMLItemValue($house, 'published');
            //get owneremail
            $house_class->owneremail = mosRealEstateManagerImportExport::getXMLItemValue($house, 'owneremail');
            //get featured_clicks
            $house_class->featured_clicks = mosRealEstateManagerImportExport::getXMLItemValue($house, 'featured_clicks');
            //get featured_shows
            $house_class->featured_shows = mosRealEstateManagerImportExport::getXMLItemValue($house, 'featured_shows');
            //get owner_id
            $house_class->extra1 = mosRealEstateManagerImportExport::getXMLItemValue($house, 'extra1');
            //get owner_id
            $house_class->extra2 = mosRealEstateManagerImportExport::getXMLItemValue($house, 'extra2');
            //get owner_id
            $house_class->extra3 = mosRealEstateManagerImportExport::getXMLItemValue($house, 'extra3');
            //get owner_id
            $house_class->extra4 = mosRealEstateManagerImportExport::getXMLItemValue($house, 'extra4');
            //get owner_id
            $house_class->extra5 = mosRealEstateManagerImportExport::getXMLItemValue($house, 'extra5');
            //get owner_id
            $house_class->extra6 = mosRealEstateManagerImportExport::getXMLItemValue($house, 'extra6');
            //get owner_id
            $house_class->extra7 = mosRealEstateManagerImportExport::getXMLItemValue($house, 'extra7');
            //get owner_id
            $house_class->extra8 = mosRealEstateManagerImportExport::getXMLItemValue($house, 'extra8');
            //get owner_id
            $house_class->extra9 = mosRealEstateManagerImportExport::getXMLItemValue($house, 'extra9');
            //get owner_id
            $house_class->extra10 = mosRealEstateManagerImportExport::getXMLItemValue($house, 'extra10');
            //get language
            $house_class->language = mosRealEstateManagerImportExport::getXMLItemValue($house, 'language');
            if ($catid === null) {
              //get associate_house
              $house_class->associate_house = mosRealEstateManagerImportExport::getXMLItemValue($house, 'associate_house');
            }
            //get category
      
            if ($catid === null) {
                $new_category = new stdClass();
                $catidsxml = $house->getElementsByTagname('catid');
                $tempcat = array();
                for ($t = 0; $t < $catidsxml->length; $t++) {
                    $tempxml[$t] = $catidsxml->item($t);
                    $new_category = new stdClass();
                    $new_category->old_id = $tempxml[$t]->nodeValue;
                    $new_category = mosRealEstateManagerImportExport::findCategory($new_categories, $new_category);
                    $tempcat[] = $new_category->id;
                }
            } else {
                $tempcat = array();                
                $tempcat = $catid;
            }
            
            //for output rezult in table
            $tmp[0] = $i;
            $tmp[1] = $houseid;
            $tmp[2] = $house_hlocation;
            $tmp[3] = $house_htitle;


            //check houseid for existing
            if (!$house_class->check() || !$house_class->store()) {
                $tmp[5] = $house_class->getError();
            } else {
                $house_class->saveCatIds($tempcat);
                $tmp[5] = "OK";
            }
            if ($catid === null){
                $house_class->checkin();
            }
            $retVal[$i] = $tmp;
            
            $ussuesArray = array();
            $ussuesArray["associate"] = mosRealEstateManagerImportExport::getXMLItemValue($house, 'associate_house');
            $ussuesArray["oldId"] = mosRealEstateManagerImportExport::getXMLItemValue($house, 'id');
            $ussuesArray["newId"] = $house_class->id;
            
            $associateSaveArr[] = $ussuesArray;
  
            //get Reviews
            if ($tmp[5] == "OK" && mosRealEstateManagerImportExport::getXMLItemValue($house, 'reviews') != "") {
                $review_list = $house->getElementsByTagname('review');
                for ($j = 0; $j < $review_list->length; $j++) {
                    $review = $review_list->item($j);
                    $review_user_name = mosRealEstateManagerImportExport::getXMLItemValue($review, 'user_name');
                    $review_user_email = mosRealEstateManagerImportExport::getXMLItemValue($review, 'user_email');
                    $review_date = mosRealEstateManagerImportExport::getXMLItemValue($review, 'date');
                    $review_rating = mosRealEstateManagerImportExport::getXMLItemValue($review, 'rating');
                    $review_title = mosRealEstateManagerImportExport::getXMLItemValue($review, 'title');
                    $review_comment = mosRealEstateManagerImportExport::getXMLItemValue($review, 'comment');
                    $review_published = mosRealEstateManagerImportExport::getXMLItemValue($review, 'published');

                    //insert data in table #__rem_review
                    $database->setQuery("INSERT INTO #__rem_review" .
                            "\n (fk_houseid, user_name,user_email, date, rating, title, comment, published)" .
                            "\n VALUES " .
                            "\n (" . $house_class->id . ", '" . $review_user_name . "','" . $review_user_email .
                            "', '" . $review_date . "'," . $review_rating . ",'" . $review_title . "', '" . addslashes($review_comment) . "', '" . $review_published . "');");
                    $database->query();
                } //end for(...) - REVIEW
            } //end if(...) - REVIEW
            //get rents
            if ($tmp[5] == "OK" && mosRealEstateManagerImportExport::getXMLItemValue($house, 'rents') != "") {
                $rent_list = $house->getElementsByTagname('rent');
                for ($j = 0; $j < $rent_list->length; $j++) {
                    $rent = $rent_list->item($j);
                    $help = new mosRealEstateManager_rent($database);
                    $help->fk_houseid = $house_class->id;
                    $help->rent_from = mosRealEstateManagerImportExport::getXMLItemValue($rent, 'rent_from');
                    $help->rent_until = mosRealEstateManagerImportExport::getXMLItemValue($rent, 'rent_until');
                    $rent_return = mosRealEstateManagerImportExport::getXMLItemValue($rent, 'rent_return');
                    $help->user_name = mosRealEstateManagerImportExport::getXMLItemValue($rent, 'user_name');
                    $help->user_email = mosRealEstateManagerImportExport::getXMLItemValue($rent, 'user_email');
                    $help->user_mailing = mosRealEstateManagerImportExport::getXMLItemValue($rent, 'user_mailing');

                    //insert data in table #__rem_rent
                    if (empty($rent_return))
                        $help->rent_return = new stdClass(); else
                        $help->rent_return = $rent_return;

                    if (!$help->check() || !$help->store()) {
                        $tmp[5] = $help->getError();
                    } else {
                        $house_class->fk_rentid = $help->id;
                        if (!$house_class->check() || !$house_class->store()) {
                            $tmp[5] = $house_class->getError();
                        } else
                            $tmp[5] = "OK";
                    }
                } //end for(...) - rent
            } //end if(...) - rent
            //print_r($house_class->id);
            if ($tmp[5] == "OK" && mosRealEstateManagerImportExport::getXMLItemValue($house, 'features_houses') != "") {
                 $feature_house_list = $house->getElementsByTagname('features_house');
                 
                 for ($j = 0; $j < $feature_house_list->length; $j++) {
                 $feature_house = $feature_house_list->item($j);
                 $house_feature_id = mosRealEstateManagerImportExport::getXMLItemValue($feature_house, 'fk_featureid');
                 
                 $database->setQuery("INSERT INTO #__rem_feature_houses" .
                                 "\n (fk_houseid, fk_featureid)" .
                                 "\n VALUES " .
                                 "\n (" . $house_class->id . ", " . $house_feature_id . ");");
          
                         $database->query();
                 }
            }

            if ($tmp[5] == "OK" && mosRealEstateManagerImportExport::getXMLItemValue($house, 'rent_sals') != "") { 
                $rent_sal_list = $house->getElementsByTagname('rent_sal');
                
                for ($j = 0; $j < $rent_sal_list->length; $j++) {
                
                    $rent_sal = $rent_sal_list->item($j);
                    $sp_from = mosRealEstateManagerImportExport::getXMLItemValue($rent_sal, 'price_from');
                    $sp_to= mosRealEstateManagerImportExport::getXMLItemValue($rent_sal, 'price_to');
                    $special_price = mosRealEstateManagerImportExport::getXMLItemValue($rent_sal, 'special_price');
                    $priceunit = mosRealEstateManagerImportExport::getXMLItemValue($rent_sal, 'priceunit');

                    //insert data in table #__rem_rent_sal
                    $database->setQuery("INSERT INTO #__rem_rent_sal" .
                            "\n (fk_houseid, price_from, price_to, special_price, priceunit)" .
                            "\n VALUES " . " (" . $house_class->id . 
                            ", '" . $sp_from . "','" . $sp_to .
                            "', " . $special_price . ",'" . $priceunit . "');");
                    $database->query();
                } 
            } 
            
            //get rentrequests
            if ($tmp[5] == "OK" && mosRealEstateManagerImportExport::getXMLItemValue($house, 'rentrequests') != "") {
                 $rentrequests_list = $house->getElementsByTagname('rentrequest');
                 for ($j = 0; $j < $rentrequests_list->length; $j++) {
                     $rentrequest = $rentrequests_list->item($j);
                     $rr_rent_from = mosRealEstateManagerImportExport::getXMLItemValue($rentrequest, 'rent_from');
                     $rr_rent_until = mosRealEstateManagerImportExport::getXMLItemValue($rentrequest, 'rent_until');
                     $rr_rent_request = mosRealEstateManagerImportExport::getXMLItemValue($rentrequest, 'rent_request');
                     $rr_user_name = mosRealEstateManagerImportExport::getXMLItemValue($rentrequest, 'user_name');
                     $rr_user_email = mosRealEstateManagerImportExport::getXMLItemValue($rentrequest, 'user_email');
                     $rr_user_mailing = mosRealEstateManagerImportExport::getXMLItemValue($rentrequest, 'user_mailing');
                     $rr_status = mosRealEstateManagerImportExport::getXMLItemValue($rentrequest, 'status');
 
                     //insert data in table #__rem_rent_request
                     $database->setQuery("INSERT INTO #__rem_rent_request " .
                             "\n (fk_houseid, rent_from,rent_until, rent_request, user_name, user_email, user_mailing,status)" .
                             "\n VALUES " .
                             "\n (" . $house_class->id . ", '" . $rr_rent_from . "', '" . $rr_rent_until .
                             "', '" . $rr_rent_request . "', '" . $rr_user_name . "','" . $rr_user_email . "', '" . addslashes($rr_user_mailing) .
                             "', " . $rr_status . ");");
                     $database->query();
                 } //end for(...) - rentrequest
             } //end if(...) - rentrequest
             
            //get buyingrequests
             if ($tmp[5] == "OK" && mosRealEstateManagerImportExport::getXMLItemValue($house, 'buyingrequests') != "") {
                 $buyingrequests_list = $house->getElementsByTagname('buyingrequest');
                 
                for ($j = 0; $j < $buyingrequests_list->length; $j++) {
                     $buyingrequest = $buyingrequests_list->item($j);
                     
                     $br_buying_request = mosRealEstateManagerImportExport::getXMLItemValue($buyingrequest, 'buying_request');
                     $br_customer_name = mosRealEstateManagerImportExport::getXMLItemValue($buyingrequest, 'customer_name');
                     $br_customer_email = mosRealEstateManagerImportExport::getXMLItemValue($buyingrequest, 'customer_email');
                     $br_customer_phone = mosRealEstateManagerImportExport::getXMLItemValue($buyingrequest, 'customer_phone');
                     $br_customer_com = mosRealEstateManagerImportExport::getXMLItemValue($buyingrequest, 'customer_comment');
                     $br_status = mosRealEstateManagerImportExport::getXMLItemValue($buyingrequest, 'status');
 
                     //insert data in table #__rem_buying_request
                     $database->setQuery("INSERT INTO #__rem_buying_request " .
                             "\n (fk_houseid, buying_request, customer_name, customer_email, customer_phone, customer_comment, status)" .
                             "\n VALUES " . " (" . $house_class->id .
                             ", '" . $br_buying_request . 
                             "', '" . $br_customer_name . 
                             "','" . $br_customer_email. 
                             "','" . $br_customer_phone .
                             "' , '" . addslashes($br_customer_com) .
                             "' , " . $br_status . ");");
                     $database->query();
                    
                 } //end for(...) - $buyingrequest
             } //end if(...) - $buyingrequest
        


            //get images
            if ($tmp[5] == "OK" && mosRealEstateManagerImportExport::getXMLItemValue($house, 'images') != "") {
                $images_list = $house->getElementsByTagname('image');
                for ($j = 0; $j < $images_list->length; $j++) {
                    $image = $images_list->item($j);
                    $image_thumbnail_img = mosRealEstateManagerImportExport::getXMLItemValue($image, 'thumbnail_img');
                    $image_main_img = mosRealEstateManagerImportExport::getXMLItemValue($image, 'main_img');
                    $database->setQuery("INSERT INTO #__rem_photos " .
                            "\n (fk_houseid, thumbnail_img, main_img)" .
                            "\n VALUES " .
                            "\n (" . $house_class->id .
                            ", '" . $image_thumbnail_img . "','" . $image_main_img . "');");
                    $database->query();
                } //end for(...) - images
            } //end if(...) - images
        }//end for(...) - house 
          //get videos
          if ($tmp[5] == "OK" && mosRealEstateManagerImportExport::getXMLItemValue($house, 'videos') != ""){
            $videos_list = $house->getElementsByTagname('video');
            for ($j = 0; $j < $videos_list->length; $j++) {
              $video = $videos_list->item($j);
              $sequence_number = mosRealEstateManagerImportExport::getXMLItemValue($video, 'sequence_number');
              $src = mosRealEstateManagerImportExport::getXMLItemValue($video, 'src');
              $type = mosRealEstateManagerImportExport::getXMLItemValue($video, 'type');
              $media = mosRealEstateManagerImportExport::getXMLItemValue($video, 'media');
              $youtube = mosRealEstateManagerImportExport::getXMLItemValue($video, 'youtube');
              //insert data in table __rem_video_source
              $database->setQuery("INSERT INTO #__rem_video_source" .
                      "\n (fk_house_id, sequence_number, src, type, media, youtube)" .
                      "\n VALUES " .
                      "\n (" . $house_class->id .",
                      '" . $sequence_number . "',
                      '" . $src . "',
                      '" . $type . "',
                      '" . $media . "',
                      '" . $youtube . "');");
              $database->query();
            } //end for(...) - videos
          } //end if(...) - videos

          //get tracks
          if ($tmp[5] == "OK" && mosRealEstateManagerImportExport::getXMLItemValue($house, 'tracks') != ""){
            $tracks_list = $house->getElementsByTagname('track');
            for ($j = 0; $j < $tracks_list->length; $j++) {
              $track = $tracks_list->item($j);
              $sequence_number = mosRealEstateManagerImportExport::getXMLItemValue($track, 'sequence_number');
              $src = mosRealEstateManagerImportExport::getXMLItemValue($track, 'src');
              $kind = mosRealEstateManagerImportExport::getXMLItemValue($track, 'kind');
              $scrlang = mosRealEstateManagerImportExport::getXMLItemValue($track, 'scrlang');
              $label = mosRealEstateManagerImportExport::getXMLItemValue($track, 'label');
              //insert data in table __rem_track_source
              $database->setQuery("INSERT INTO #__rem_track_source" .
                      "\n (fk_house_id, sequence_number, src, kind, scrlang, label)" .
                      "\n VALUES " .
                      "\n (" . $house_class->id .", 
                      '" . $sequence_number . "',
                      '" . $src . "',
                      '" . $kind . "',
                      '" . $scrlang . "',
                      '" . $label . "');");
              $database->query();
            } //end for(...) - tracks
          } //end if(...) - tracks
  
               //get orders
                  $orders_list = $dom->getElementsByTagname('order');
                  $odrers_ids = array();
                  for ($j = 0; $j < $orders_list->length; $j++) {
                      $orders = $orders_list->item($j);
                      $order_id = mosRealEstateManagerImportExport::getXMLItemValue($orders, 'id');
                      $order_userid = mosRealEstateManagerImportExport::getXMLItemValue($orders, 'fk_user_id');
                      $htitle = mosRealEstateManagerImportExport::getXMLItemValue($orders, 'fk_houses_htitle');
                      $order_email = mosRealEstateManagerImportExport::getXMLItemValue($orders, 'email');
                      $order_name = mosRealEstateManagerImportExport::getXMLItemValue($orders, 'name');
                      $order_status = mosRealEstateManagerImportExport::getXMLItemValue($orders, 'status');
                      $order_data = mosRealEstateManagerImportExport::getXMLItemValue($orders, 'order_date');
                      $order_payer = mosRealEstateManagerImportExport::getXMLItemValue($orders, 'payer_id');
                      $order_payer_status = mosRealEstateManagerImportExport::getXMLItemValue($orders, 'payer_status');
                      $order_calculated_price = mosRealEstateManagerImportExport::getXMLItemValue($orders, 'order_calculated_price');
                      $order_price = mosRealEstateManagerImportExport::getXMLItemValue($orders, 'order_price');
                      $order_currency_code = mosRealEstateManagerImportExport::getXMLItemValue($orders, 'order_currency_code');
  
                      //insert data in table #__rem_orders
                      $database->setQuery("INSERT INTO #__rem_orders " .
                              "\n (fk_user_id,fk_houses_htitle, email, name, status, order_date, fk_house_id,
                               payer_id, payer_status, order_calculated_price, order_price, order_currency_code)" .
                              "\n VALUES " . " ('" . $order_userid . 
                              "', '" . $htitle .
                              "', '" . $order_email . 
                              "', '" . $order_name . 
                              "', '" . $order_status .
                              "', '" . $order_data .
                              "', '" . $old_house_id .
                              "', '" . $order_payer .
                              "', '" . $order_payer_status .
                              "', '" . $order_calculated_price .
                              "', '" . $order_price .
                              "', '" . $order_currency_code . "');");
                      $database->query();
                      $odrers_ids[$order_id]=$database->insertid();
                      $database->setQuery("UPDATE #__rem_orders SET fk_house_id =" . $house_class->id . " WHERE fk_house_id = " . $old_house_id . "");
                      $database->query();
                     
                  } 
                   //get orders details
                  $details_list = $dom->getElementsByTagname('orders_detail');
                  for ($j = 0; $j < $details_list->length; $j++) {
                      $detail= $details_list->item($j);
                      $order_id = mosRealEstateManagerImportExport::getXMLItemValue($detail, 'fk_order_id');
                      $order_userid = mosRealEstateManagerImportExport::getXMLItemValue($detail, 'fk_user_id');
                      $htitle = mosRealEstateManagerImportExport::getXMLItemValue($detail, 'fk_houses_htitle');
                      $order_email = mosRealEstateManagerImportExport::getXMLItemValue($detail, 'email');
                      $order_name = mosRealEstateManagerImportExport::getXMLItemValue($detail, 'name');
                      $order_status = mosRealEstateManagerImportExport::getXMLItemValue($detail, 'status');
                      $order_data = mosRealEstateManagerImportExport::getXMLItemValue($detail, 'order_date');
                      $order_payer = mosRealEstateManagerImportExport::getXMLItemValue($detail, 'payer_id');
                      $order_payer_status = mosRealEstateManagerImportExport::getXMLItemValue($detail, 'payer_status');
                      $payment_details = mosRealEstateManagerImportExport::getXMLItemValue($detail, 'payment_details');
                      $order_calculated_price = mosRealEstateManagerImportExport::getXMLItemValue($orders, 'order_calculated_price');
                      $order_price = mosRealEstateManagerImportExport::getXMLItemValue($orders, 'order_price');
                      $order_currency_code = mosRealEstateManagerImportExport::getXMLItemValue($orders, 'order_currency_code');
                      $order_id = $odrers_ids[$order_id];
  
                      //insert data in table #__rem_orders
                      $database->setQuery("INSERT INTO #__rem_orders_details " .
                              "\n (fk_order_id,fk_user_id,fk_houses_htitle, email, name, status, order_date,
                               fk_house_id, payer_id, payer_status, order_calculated_price, order_price,
                                order_currency_code,payment_details)" .
                              "\n VALUES " . " (
                              '" . $order_id .
                              "', '" . $order_userid . 
                              "', '" . $htitle .
                              "', '" . $order_email . 
                              "', '" . $order_name . 
                              "', '" . $order_status .
                              "', '" . $order_data .
                              "', '" . $old_house_id .
                              "', '" . $order_payer .
                              "', '" . $order_payer_status .
                              "',  '" . $order_calculated_price .
                              "',  '" . $order_price .
                              "', '" . $order_currency_code .
                              "', " . $database->Quote($payment_details) . ");");
                      $database->query();
                      $database->setQuery("UPDATE #__rem_orders_details SET fk_house_id =" . $house_class->id . " WHERE fk_house_id = " . $old_house_id . "");
                      $database->query();
                     
                  } 
  
        if ($catid === null) {
          //update accosiate for houses
          mosRealEstateManagerImportExport::updateAssociateHouses($associateSaveArr); 
        }  
        return $retVal;
    }

//***************************************************************************************************
//***********************   end add for import XML format   *****************************************
//***************************************************************************************************



static function exportHouses($option) {

    global $database, $realestatemanager_configuration;

    $catid = mosGetParam($_POST, 'export_catid', 0);
    $type = mosGetParam($_POST, 'export_type', 0);
    $where = array();
    
    if (count($catid) > 0 && $type != 4){
            foreach ($catid as $id){
                array_push($where, "hc.idcat='$id'");
            }     
    }
    
    $selectstring = "SELECT distinct a.id FROM #__rem_houses AS a
            \nLEFT JOIN #__rem_categories AS hc ON hc.iditem=a.id" .
            "\nLEFT JOIN #__rem_main_categories AS c ON c.id=hc.idcat" .
            (count($where) ? " WHERE " . implode(' or ', $where) : "") .
            "\n GROUP BY a.id
             \n ORDER BY c.parent_id, a.ordering";
    $database->setQuery($selectstring);
    $bids = $database->loadObjectList();
    
    
    if (version_compare(JVERSION, '3.0', 'lt')) {
        $bids = $database->loadResultArray();
    } else {
        $bids = $database->loadColumn();
    }

    echo $database->getErrorMsg();

    if ($database->getErrorNum()) {
        echo $database->stderr();
        return;
    }
     if ($database->getErrorNum())
    {
        echo $database->stderr();
        return;
    }
    
    $listing_type[0] = '';
    $listing_type[1] = _REALESTATE_MANAGER_OPTION_FOR_RENT;
    $listing_type[2] = _REALESTATE_MANAGER_OPTION_FOR_SALE;

    $listing_status[0] = '';
    $listing_status1 = explode(',', _REALESTATE_MANAGER_OPTION_LISTING_STATUS);
    $k = 1;
    foreach ($listing_status1 as $listing_status2) {
        $listing_status[$k] = $listing_status2;
        $k++;
    }
    $property_type[0] = '';
    $property_type1 = explode(',', _REALESTATE_MANAGER_OPTION_PROPERTY_TYPE);
    $k = 1;
    foreach ($property_type1 as $property_type2) {
        $property_type[$k] = $property_type2;
        $k++;
    }

    $categories = '';

    if ($type == '4') { 
        $database->setQuery("SELECT *  FROM #__rem_main_categories " .
            "WHERE section='com_realestatemanager' order by parent_id ; ");
        $categories = $database->loadObjectList();   
    
        $database->setQuery("SELECT * FROM #__rem_feature ");
        $features = $database->loadObjectList();
    
        $database->setQuery("SELECT * FROM #__rem_feature_houses ");
        $features_houses = $database->loadObjectList();      
        
        $database->setQuery("SELECT * FROM #__rem_orders ");
        $orders = $database->loadObjectList(); 

        $database->setQuery("SELECT * FROM #__rem_orders_details ");
        $orders_details = $database->loadObjectList(); 

    }

    switch ($type) {
        case '0':break;
        case '1':
            $type2 = 'csv';
            //move to xml - all data
            $createFeatured = true;
            $all = false;
            break;
        case '2':
            $type2 = 'xml';
            //move to xml - some category
            $createFeatured = false;
            $all = false;
            break;
        default :
            $type2 = 'xml';
            //move to xml - all category
            $createFeatured = true;
            $all = true;
            break;

    }  
            
    
    $order = array("\r\n", "\n", "\r");
    $strXmlDoc = "";
    $strXmlDoc.= "<?xml version='1.0' encoding='utf-8' ?>\n";
    $strXmlDoc.= "<houses_data>\n";
    $strXmlDoc.= "<version>" . $realestatemanager_configuration['release']['version'] . "</version>\n"; 
     

    $strXmlDocCategory = "";
    $strXmlDocCategory.= "<categories>\n";
    if(!empty($categories)){
        foreach($categories as $cat){
            $strXmlDocCategory.= "<category>\n";
            foreach($cat as $field => $value) {

                $strXmlDocCategory.= '<category_' . $field . '><![CDATA[' . $value . ']]></category_' . $field . ">\n";
            }                 
            $strXmlDocCategory.= "</category>\n";                
        }
    }
    $strXmlDocCategory.= "</categories>\n";
     
     
    $strXmlDocFeatures = "";
    $strXmlDocFeatures.= "<features>\n";
    if(!empty($features)){
        foreach($features as $feature1){
            $strXmlDocFeatures .= "<feature>\n";
            foreach ($feature1 as $field => $value) {
                $strXmlDocFeatures.= '<feature_' . $field . '><![CDATA[' . $value . ']]></feature_' . $field . ">\n";   
            }
            $strXmlDocFeatures .= "</feature>\n";
        }    
    }   
    $strXmlDocFeatures.= "</features>\n";
    
    $strXmlDocOrders = "";
    $strXmlDocOrders.= "<orders>\n";;
    if(!empty($orders)){
        foreach($orders as $order1){
            $strXmlDocOrders .= "<order>\n";
            foreach ($order1 as $field => $value) {
                $strXmlDocOrders.= '<' . $field . '><![CDATA[' . $value . ']]></' . $field . ">\n";   
            }
            $strXmlDocOrders .= "</order>\n";
        }    
    }   
    $strXmlDocOrders.= "</orders>\n";
    
    $strXmlDocOrdersDet = "";
    $strXmlDocOrdersDet.= "<orders_details>\n";;
    if(!empty($orders_details)){
        foreach($orders_details as $order1){
            $strXmlDocOrdersDet .= "<orders_detail>\n";
            foreach ($order1 as $field => $value) {
                $strXmlDocOrdersDet.= '<' . $field . '><![CDATA[' . $value . ']]></' . $field . ">\n";   
            }
            $strXmlDocOrdersDet .= "</orders_detail>\n";
        }    
    }   
    $strXmlDocOrdersDet.= "</orders_details>\n";

    $strXmlDocHousesList = "";
    $strXmlDocHousesList.= "<houses_list>\n";
    $tmp = new mosRealEstateManager($database); 
    foreach($bids as $bid){  

        if($tmp->load(intval($bid))){

            $tmp->listing_type = str_replace('|', '-', $listing_type[$tmp->listing_type]);
            $tmp->listing_type = str_replace($order, ' ', $tmp->listing_type);
            $tmp->listing_status = str_replace('|', '-', $listing_status[$tmp->listing_status]);
            $tmp->listing_status = str_replace($order, ' ', $tmp->listing_status);
            $tmp->property_type = str_replace('|', '-', $property_type[$tmp->property_type]);
            $tmp->property_type = str_replace($order, ' ', $tmp->property_type);
            $tmp->contacts = str_replace('|', '-', $tmp->contacts);
            $tmp->contacts = str_replace($order, ' ', $tmp->contacts);
            $tmp->htitle = str_replace('|', '-', $tmp->htitle);
            $tmp->htitle = str_replace($order, ' ', $tmp->htitle);
            $tmp->description = str_replace('|', '-', $tmp->description);
            $tmp->description = str_replace($order, ' ', $tmp->description);
            $tmp->hcountry = str_replace('|', '-', $tmp->hcountry);
            $tmp->hcountry = str_replace($order, ' ', $tmp->hcountry);
            $tmp->hregion = str_replace('|', '-', $tmp->hregion);
            $tmp->hregion = str_replace($order, ' ', $tmp->hregion);
            $tmp->hcity = str_replace('|', '-', $tmp->hcity);
            $tmp->hcity = str_replace($order, ' ', $tmp->hcity);
            $tmp->hlocation = str_replace('|', '-', $tmp->hlocation);
            $tmp->hlocation = str_replace($order, ' ', $tmp->hlocation);
            $tmp->agent = str_replace('|', '-', $tmp->agent);
            $tmp->agent = str_replace($order, ' ', $tmp->agent);
            $tmp->garages = str_replace('|', '-', $tmp->garages);
            $tmp->garages = str_replace($order, ' ', $tmp->garages);
            $tmp->owneremail = str_replace('|', '-', $tmp->owneremail);
            $tmp->owneremail = str_replace($order, ' ', $tmp->owneremail);
            $tmp->priceunit = str_replace('|', '-', $tmp->priceunit);
            $tmp->priceunit = str_replace($order, ' ', $tmp->priceunit);
            $tmp->featured_clicks = str_replace('|', '-', $tmp->featured_clicks);
            $tmp->featured_clicks = str_replace($order, ' ', $tmp->featured_clicks);
            $tmp->featured_shows = str_replace('|', '-', $tmp->featured_shows);
            $tmp->featured_shows = str_replace($order, ' ', $tmp->featured_shows);
            $tmp->extra1 = str_replace('|', '-', $tmp->extra1);
            $tmp->extra1 = str_replace($order, ' ', $tmp->extra1);
            $tmp->extra2 = str_replace('|', '-', $tmp->extra2);
            $tmp->extra2 = str_replace($order, ' ', $tmp->extra2);
            $tmp->extra3 = str_replace('|', '-', $tmp->extra3);
            $tmp->extra3 = str_replace($order, ' ', $tmp->extra3);
            $tmp->extra4 = str_replace('|', '-', $tmp->extra4);
            $tmp->extra4 = str_replace($order, ' ', $tmp->extra4);
            $tmp->extra5 = str_replace('|', '-', $tmp->extra5);
            $tmp->extra5 = str_replace($order, ' ', $tmp->extra5);
            $tmp->extra6 = str_replace('|', '-', $tmp->extra6);
            $tmp->extra6 = str_replace($order, ' ', $tmp->extra6);
            $tmp->extra7 = str_replace('|', '-', $tmp->extra7);
            $tmp->extra7 = str_replace($order, ' ', $tmp->extra7);
            $tmp->extra8 = str_replace('|', '-', $tmp->extra8);
            $tmp->extra8 = str_replace($order, ' ', $tmp->extra8);
            $tmp->extra9 = str_replace('|', '-', $tmp->extra9);
            $tmp->extra9 = str_replace($order, ' ', $tmp->extra9);
            $tmp->extra10 = str_replace('|', '-', $tmp->extra10);
            $tmp->extra10 = str_replace($order, ' ', $tmp->extra10);
            $tmp->language = str_replace('|', '-', $tmp->language);
            $tmp->language = str_replace($order, ' ', $tmp->language);
            $tmp->owner_id = str_replace('|', '-', $tmp->owner_id);
            $tmp->owner_id = str_replace($order, ' ', $tmp->owner_id);        
            $tmp->associate_house = str_replace('|', '-', $tmp->associate_house);
            $tmp->associate_house = str_replace($order, ' ', $tmp->associate_house); 
            $tmp->lot_size = str_replace('|', '-', $tmp->lot_size);
            $tmp->lot_size = str_replace($order, ' ', $tmp->lot_size); 
            $tmp->bathrooms = str_replace('|', '-', $tmp->bathrooms);
            $tmp->bathrooms = str_replace($order, ' ', $tmp->bathrooms); 
            $tmp->bedrooms = str_replace('|', '-', $tmp->bedrooms);
            $tmp->bedrooms = str_replace($order, ' ', $tmp->bedrooms); 
            $tmp->year = str_replace('|', '-', $tmp->year);
            $tmp->year = str_replace($order, ' ', $tmp->year); 
            $tmp->house_size = str_replace('|', '-', $tmp->house_size);
            $tmp->house_size = str_replace($order, ' ', $tmp->house_size); 
            $tmp->rooms = str_replace('|', '-', $tmp->rooms);
            $tmp->rooms = str_replace($order, ' ', $tmp->rooms);           

            $strXmlDocHousesList.= $tmp->toXML2($all);
        }
    }
    
    $strXmlDocHousesList.= "</houses_list>\n";
            
    
    if($createFeatured){   
        $strXmlDoc.= $strXmlDocCategory;
        $strXmlDoc.= $strXmlDocFeatures;
        $strXmlDoc.= $strXmlDocOrders;
        $strXmlDoc.= $strXmlDocOrdersDet;
    }
    $strXmlDoc.= $strXmlDocHousesList;
    $strXmlDoc.= "</houses_data>\n";
    
    $retVal = $strXmlDoc;
    
    $InformationArray = mosRealEstateManagerImportExport :: storeExportFile($retVal, $type2);
    HTML_realestatemanager :: showExportResult($InformationArray, $option);
}


    static function storeExportFile($data, $type) {

        global $mosConfig_live_site, $mosConfig_absolute_path, $realestatemanager_configuration;
        $fileName = "realestatemanager_" . date("Ymd_His");
        $fileBase = "/administrator/components/com_realestatemanager/exports/";

        //write the xml file
        $fp = fopen($mosConfig_absolute_path . $fileBase . $fileName . ".xml", "w", 0); #open for writing

        fwrite($fp, $data); #write all of $data to our opened file
        fclose($fp); #close the file
        $InformationArray = array();
        $InformationArray['xml_file'] = $fileName . '.xml';
        $InformationArray['log_file'] = $fileName . '.log';
        $InformationArray['fileBase'] = "file://" . getcwd() . "/components/com_realestatemanager/exports/";
        $InformationArray['urlBase'] = $mosConfig_live_site . $fileBase;
        $InformationArray['out_file'] = $InformationArray['xml_file'];
        $InformationArray['error'] = new stdClass();

        switch ($type) {
            case 'csv':
                $InformationArray['xslt_file'] = 'csv.xsl';
                $InformationArray['out_file'] = $fileName . '.csv';
                mosRealEstateManagerImportExport :: transformPHP4($InformationArray);
                break;
            default:
                break;
        }
        return $InformationArray;
    }

    static function transformPHP4(&$InformationArray) {

        // create the XSLT processor^M
        $xh = xslt_create() or die("Could not create XSLT processor");

        // Process the document
        $result = xslt_process($xh, $InformationArray['fileBase'] .
           $InformationArray['xml_file'], $InformationArray['fileBase'] . 
           $InformationArray['xslt_file'], $InformationArray['fileBase'] . $InformationArray['out_file']);

        if (!$result) {
            // Something croaked. Show the error
            $InformationArray['error'] = "Cannot process XSLT document: ";
        }
        // Destroy the XSLT processor
        xslt_free($xh);
    }

    static function clearDatabase() {
        global $database;
        $database->setQuery("DELETE FROM #__rem_main_categories "); // for 1.6
        $database->query();
        $database->setQuery("DELETE FROM #__rem_feature_houses");
        $database->query();
        $database->setQuery("DELETE FROM #__rem_feature");
        $database->query();
        $database->setQuery("DELETE FROM #__rem_categories");
        $database->query();
        $database->setQuery("DELETE FROM #__rem_houses");
        $database->query();
        $database->setQuery("DELETE FROM #__rem_photos");
        $database->query();
        $database->setQuery("DELETE FROM #__rem_rent");
        $database->query();
        $database->setQuery("DELETE FROM #__rem_rent_request");
        $database->query();
        $database->setQuery("DELETE FROM #__rem_review");
        $database->query();
        $database->setQuery("DELETE FROM #__rem_buying_request");
        $database->query();
        $database->setQuery("DELETE FROM #__rem_orders");
        $database->query();
        $database->setQuery("DELETE FROM #__rem_orders_details");
        $database->query();
        $database->setQuery("DELETE FROM #__rem_rent_sal");
        $database->query();
    }

}
