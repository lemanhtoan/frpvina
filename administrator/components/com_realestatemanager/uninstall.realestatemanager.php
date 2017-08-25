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
global $mosConfig_absolute_path;
include_once( JPATH_SITE . '/components/com_realestatemanager/compat.joomla1.5.php' );
require_once (dirname(__FILE__) . "/realestatemanager.class.conf.php");
$db = JFactory::getDbo();
    $query = "DROP TABLE IF EXISTS `#__rem_const`, `#__rem_languages`, `#__rem_const_languages`, `#__rem_feature`, `#__rem_feature_houses`, 
		`#__rem_rent_sal`, `#__rem_rent`, `#__rem_rent_request`, `#__rem_buying_request`,`#__rem_review`, `#__rem_suggestion`, 
		`#__rem_categories`, `#__rem_photos`, `#__rem_version`,
     `#__rem_houses`, `#__rem_main_categories`,
      `#__rem_mime_types`, `#__rem_orders`, `#__rem_orders_details`, `#__rem_track_source`, `#__rem_video_source`, `#__rem_users_wishlist`";
    $db->setQuery($query);
    $db->query();

function com_uninstall() {
    echo "Uninstalled! ";
}

