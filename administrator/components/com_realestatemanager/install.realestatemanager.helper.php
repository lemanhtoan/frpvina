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
include_once($mosConfig_absolute_path . "/components/com_realestatemanager/compat.joomla1.5.php");
require_once ($mosConfig_absolute_path . "/components/com_realestatemanager/realestatemanager.class.php");

if (!defined('_JLEGACY'))
    $GLOBALS['path'] = $mosConfig_live_site . "/components/com_realestatemanager/images/";
else
    $GLOBALS['path'] = $mosConfig_live_site . "/administrator/components/com_realestatemanager/images/";
$path = $GLOBALS['path'];

class DMInstallHelper {

    static function getComponentId() {
        global $database;
        if (version_compare(JVERSION, "1.6.0", "lt")) {
            static $id;
            if (!$id) {
                $database->setQuery("SELECT id FROM #__components WHERE `option`='com_realestatemanager' AND `parent`=0 ");
                $id = $database->loadResult();
            }
            return $id;
        } else if (version_compare(JVERSION, "1.6.0", "ge")) {
            $database->setQuery("SELECT extension_id FROM #__extensions WHERE `element`='com_realestatemanager' ");
            $id = $database->loadResult();
            return $id;
        } else {
            echo "Sanity test. Error version check!";
            exit;
        }
    }

    static function getParentId() {
        $id = DMInstallHelper::getComponentId();
        global $database;
        if (version_compare(JVERSION, "1.6.0", "lt")) {
            //
        } else if (version_compare(JVERSION, "1.6.0", "ge")) {
            $database->setQuery("SELECT id FROM #__menu WHERE title='RealEstateManager' and level=1 and parent_id=1 and component_id=$id");
            $parent_id = $database->loadResult();
            return $parent_id;
        } else {
            echo "Sanity test. Error version check!";
            exit;
        }
    }

    static function setAdminMenuImages() {
        global $database, $path;
        $id = DMInstallHelper::getComponentId();
        if (version_compare(JVERSION, "1.6.0", "lt")) {
            // Main menu
            $database->setQuery("UPDATE #__components SET admin_menu_img = '" . $path . "dm_component_16.png' WHERE id=$id");
            $database->query();

            // Submenus
            $submenus = array();
            $submenus[] = array('image' => $path . 'dm_edit_16.png', 'name' => 'Houses');
            $submenus[] = array('image' => $path . 'dm_component_16.png', 'name' => 'Categories');
            $submenus[] = array('image' => $path . 'dm_component_16.png', 'name' => 'Booking Manager');
            $submenus[] = array('image' => $path . 'dm_component_16.png', 'name' => 'Booking History');
            $submenus[] = array('image' => $path . 'dm_component_16.png', 'name' => 'Sale Manager');	
            $submenus[] = array('image' => $path . 'dm_component_16.png', 'name' => 'Settings');
            $submenus[] = array('image' => $path . 'dm_credits_16.png', 'name' => 'About');

            foreach ($submenus as $submenu) {
                $database->setQuery("UPDATE #__components SET admin_menu_img = '" . $submenu['image'] . "' WHERE parent=$id AND name = '" . $submenu['name'] . "';");
                $database->query();
            }
        } else if (version_compare(JVERSION, "1.6.0", "ge")) {
            $parent_id = DMInstallHelper::getParentId();

            // Main menu
            $database->setQuery("UPDATE #__menu SET img = 'class:component' WHERE title='RealEstateManager' and level=1 and parent_id=1 and component_id=$id");
            $database->query();

            // Submenus
            $submenus = array();
            $submenus[] = array('img' => 'class:component', 'title' => 'RealEstateManager','alias'=>'RealEstateManager');
            $submenus[] = array('img' => 'class:module', 'title' => 'Houses','alias'=>'Houses');
            $submenus[] = array('img' => 'class:category', 'title' => 'Categories','alias'=>'Categories');
            $submenus[] = array('img' => 'class:writemess', 'title' => 'Amenities Manager','alias'=>'Amenities Manager');
            $submenus[] = array('img' => 'class:writemess', 'title' => 'Booking Manager','alias'=>'Booking Manager');
            $submenus[] = array('img' => 'class:writemess', 'title' => 'Booking History','alias'=>'Booking History');
            $submenus[] = array('img' => 'class:writemess', 'title' => 'Sale Manager','alias'=>'Sale Manager');
            $submenus[] = array('img' => 'class:writemess', 'title' => 'Language Manager','alias'=>'Language Manager');
            $submenus[] = array('img' => 'class:config', 'title' => 'Settings','alias'=>'Settings');
            $submenus[] = array('img' => 'class:info', 'title' => 'About','alias'=>'About');

            foreach ($submenus as $submenu) {
                $database->setQuery("UPDATE #__menu SET img = '" . $submenu['img'] . "' WHERE component_id=$id AND parent_id = '" . $parent_id . "' and level=2  AND title = '" . $submenu['title'] . "';");
                $database->query();
                $database->setQuery("UPDATE #__menu SET alias = '" . $submenu['alias'] . "'" . "\n WHERE component_id=$id AND title = '" . $submenu['title'] . "';");
                $database->query();
            }
        } else {
            echo "Sanity test. Error version check!";
            exit;
        }
    }

}

