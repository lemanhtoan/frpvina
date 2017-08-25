<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . 
    basename(__FILE__) . ' is not allowed.');
defined("DS") OR define("DS", DIRECTORY_SEPARATOR);
/**
 *
 * @package  RealEstateManager
 * @copyright 2012 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com); 
 * Homepage: http://www.ordasoft.com
 * @version: 3.9 Free
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *
 */
$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'] = JPATH_SITE;
global $mosConfig_lang, $user_configuration; // for 1.6
$mainframe = JFactory::getApplication(); // for 1.6
$GLOBALS['mainframe'] = $mainframe;

if (get_magic_quotes_gpc()) {

    function stripslashes_gpc(&$value) {
        $value = stripslashes($value);
    }

    array_walk_recursive($_GET, 'stripslashes_gpc');
    array_walk_recursive($_POST, 'stripslashes_gpc');
    array_walk_recursive($_COOKIE, 'stripslashes_gpc');
    array_walk_recursive($_REQUEST, 'stripslashes_gpc');
}

jimport('joomla.html.pagination');
require_once($mosConfig_absolute_path . "/components/com_realestatemanager/compat.joomla1.5.php");
if (version_compare(JVERSION, "3.0.0", "lt"))
    include_once($mosConfig_absolute_path . '/libraries/joomla/application/pathway.php'); // for 1.6
include_once($mosConfig_absolute_path .
 '/components/com_realestatemanager/realestatemanager.main.categories.class.php');
jimport('joomla.application.pathway');
jimport('joomla.html.pagination');
jimport('joomla.filesystem.folder');

// $database = JFactory::getDBO();
// // load language
// $languagelocale = "";
// $query = "SELECT l.title, l.lang_code, l.sef ";
// $query .= "FROM #__rem_const_languages as cl ";
// $query .= "LEFT JOIN #__rem_languages AS l ON cl.fk_languagesid=l.id ";
// $query .= "LEFT JOIN #__rem_const AS c ON cl.fk_constid=c.id ";
// $query .= "GROUP BY  l.title";
// $database->setQuery($query);
// $languages = $database->loadObjectList();

// $lang = JFactory::getLanguage();
// foreach ($lang->getLocale() as $locale) {
//     foreach ($languages as $language) {
//         if (strtolower($locale) == strtolower($language->title) 
//             || strtolower($locale) == strtolower($language->lang_code) 
//             || strtolower($locale) == strtolower($language->sef) ) {
//             $mosConfig_lang = $locale;
//             $languagelocale = $language->lang_code;
//             break;
//         }
//     }
// } 

// if ($languagelocale == '')
//     $languagelocale = "en-GB";

// global $langContent;
// $langContent = substr($languagelocale, 0, 2);

// $query = "SELECT c.const, cl.value_const ";
// $query .= "FROM #__rem_const_languages as cl ";
// $query .= "LEFT JOIN #__rem_languages AS l ON cl.fk_languagesid=l.id ";
// $query .= "LEFT JOIN #__rem_const AS c ON cl.fk_constid=c.id ";
// $query .= "WHERE l.lang_code = '$languagelocale'";
// $database->setQuery($query);
// $langConst = $database->loadObjectList();

// foreach ($langConst as $item) {
//    if(!defined($item->const) )  define($item->const, $item->value_const); // $database->quote()
// }

require_once $mosConfig_absolute_path . "/administrator/components/com_realestatemanager/language.php";
require_once($mosConfig_absolute_path . "/components/com_realestatemanager/captcha.php");

/** load the html drawing class */
require_once ($mosConfig_absolute_path .
 "/components/com_realestatemanager/realestatemanager.class.rent.php");
require_once ($mosConfig_absolute_path .
 "/components/com_realestatemanager/realestatemanager.html.php"); // for 1.6
require_once ($mosConfig_absolute_path .
 "/components/com_realestatemanager/realestatemanager.class.php"); // for 1.6
require_once ($mosConfig_absolute_path .
 "/components/com_realestatemanager/realestatemanager.class.rent_request.php");
require_once ($mosConfig_absolute_path .
 "/components/com_realestatemanager/realestatemanager.class.buying_request.php");
require_once ($mosConfig_absolute_path .
 "/components/com_realestatemanager/realestatemanager.class.rent.php");
require_once ($mosConfig_absolute_path .
 "/components/com_realestatemanager/realestatemanager.class.review.php");
require_once ($mosConfig_absolute_path .
 "/administrator/components/com_realestatemanager/realestatemanager.class.others.php");
//added 2012_06_05 that's because it doesn't work with enabled plugin System-Legacy, so if it works, let it work :)
require_once($mosConfig_absolute_path .
 "/components/com_realestatemanager/functions.php");
require_once($mosConfig_absolute_path .
 "/components/com_realestatemanager/includes/menu.php");

//added 2012_06_05 that's because it doesn't work with enabled plugin System-Legacy, so if it works, let it work :)
if (!array_key_exists('realestatemanager_configuration', $GLOBALS)) {
    require_once ($mosConfig_absolute_path .
     "/administrator/components/com_realestatemanager/realestatemanager.class.conf.php");
    $GLOBALS['realestatemanager_configuration'] = $realestatemanager_configuration;
} else
    global $realestatemanager_configuration;

if (!isset($option))
    $GLOBALS['option'] = $option = mosGetParam($_REQUEST, 'option', 'com_realestatemanager');
else
    $GLOBALS['option'] = $option;

if (isset($option) && $option == "com_simplemembership") {
    if (!array_key_exists('user_configuration2', $GLOBALS)) {
        require_once (JPATH_SITE . DS . 'administrator' . DS . 'components' . DS .
         'com_simplemembership' . DS . 'admin.simplemembership.class.conf.php');
        $GLOBALS['user_configuration2'] = $user_configuration;
    } else {
        global $user_configuration;
    }
}

//remove_langs();exit;
$my = JFactory::getUser();
$acl = JFactory::getACL();
$GLOBALS['my'] = $my;
$GLOBALS['acl'] = $acl;
//print_r($_REQUEST);exit;
$id = intval(protectInjectionWithoutQuote('id', 0));
$catid = intval(mosGetParam($_REQUEST,'catid', 0));
$bids = mosGetParam($_REQUEST, 'bid', array(0));
$Itemid = protectInjectionWithoutQuote('Itemid', 0);
$printItem = trim(mosGetParam($_REQUEST, 'printItem', ""));
$doc = JFactory::getDocument(); // for 1.6
$GLOBALS['doc'] = $doc; // for 1.6
$GLOBALS['op'] = $doc; // for 1.6
$doc->setTitle(_REALESTATE_MANAGER_TITLE); // for 1.6

if (!isset($GLOBALS['Itemid']))
    $GLOBALS['Itemid'] = JRequest::getInt('Itemid');
if (!isset($GLOBALS['Itemid']))
    $GLOBALS['Itemid'] = $Itemid = intval(protectInjectionWithoutQuote('Itemid', 0));

// paginations
$intro = $realestatemanager_configuration['page']['items']; // page length

if ($intro) {
    $paginations = 1;
    $limit = intval(protectInjectionWithoutQuote('limit', $intro));
    $GLOBALS['limit'] = $limit;
    $limitstart = intval(protectInjectionWithoutQuote('limitstart', 0));
    $GLOBALS['limitstart'] = $limitstart;
    $total = 0;
    $LIMIT = 'LIMIT ' . $limitstart . ',' . $limit;
} else {
    $paginations = 0;
    $LIMIT = '';
}

$session = JFactory::getSession();
$session->set("array", $paginations);

if (!isset($task))
    $GLOBALS['task'] = $task = mosGetParam($_REQUEST, 'task', '');
else {
    $GLOBALS['task'] = $task;
}
    
if (isset($_REQUEST['view']))
    $view = $_REQUEST['view'];

if ((!isset($task) OR $task == '') AND isset($view))
    $GLOBALS['task'] = $task = $view;
 
if ( (!isset($task) OR $task == '' ) && (!isset($view) OR $view == '') ){
    $app = new JSite();
    $menu = $app->getMenu() ;
    $item  = $menu->getActive();
    if( isset($item) ) $GLOBALS['task'] = $task = $item->query['view'];
}

if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == "[ Rent Request ]")
    $task = "rent_request";
    
if ($realestatemanager_configuration['debug'] == '1') {
    echo "Task: " . $task . "<br />";
    print_r($_REQUEST);
    echo "<hr /><br />";
}

$bid = mosGetParam($_REQUEST, 'bid', array(0));
// -
if(isset ($_REQUEST["bid"]) AND isset ($_REQUEST["rent_from"]) AND isset($_REQUEST["rent_until"])){
    
    $bid_ajax_rent = $_REQUEST["bid"];
    $rent_from = $_REQUEST["rent_from"];
    $rent_until = $_REQUEST["rent_until"];
    
    if(isset($_REQUEST["special_price"])){
       $special_price = $_REQUEST["special_price"]; 
    }
    if(isset($_REQUEST["currency_spacial_price"])){
       $currency_spacial_price = $_REQUEST["currency_spacial_price"];
    }  
    
    if(isset($_REQUEST["comment_price"])){
        $comment_price = protectInjectionWithoutQuote("comment_price",'','STRING');
    } else {
        $comment_price = '';
    }
    
}
// print_r($task);exit;
switch ($task) {
    case 'ajax_rent_calcualete':        
        PHP_realestatemanager::ajax_rent_calcualete($bid_ajax_rent,$rent_from,$rent_until);
        break;

    case 'show_search_house':
        if (version_compare(JVERSION, '3.0', 'ge')) {
            $menu = new JTableMenu($database);
            $menu->load($GLOBALS['Itemid']);
            $params = new JRegistry;
            $params->loadString($menu->params);
        } else {
            $menu = new mosMenu($database);
            $menu->load($GLOBALS['Itemid']);
            $params = new mosParameters($menu->params);
        }
        $layout = $params->get('showsearchhouselayout', '');
        PHP_realestatemanager::showSearchHouses($option, $catid, $option, $layout);
        break;
    case 'show_search':
        PHP_realestatemanager::showSearchHouses($option, $catid, $option);
        break;

    case 'search':
        PHP_realestatemanager::searchHouses($option, $catid, $option, $languagelocale);
        break;

    case 'all_houses':
        if (version_compare(JVERSION, '3.0', 'ge')) {
            $menu = new JTableMenu($database);
            $menu->load($GLOBALS['Itemid']);
            $params = new JRegistry;
            $params->loadString($menu->params);
        } else {
            $menu = new mosMenu($database);
            $menu->load($GLOBALS['Itemid']);
            $params = new mosParameters($menu->params);
        }
        $layout = $params->get('allhouselayout', '');
        if ($layout == '')
            $layout = 'default';
        PHP_realestatemanager::ShowAllHouses($layout, $printItem);
        break;
    case 'view_house':
    case 'view':
        if (version_compare(JVERSION, '3.0', 'ge')) {
            $menu = new JTableMenu($database);
            $menu->load($GLOBALS['Itemid']);
            $params = new JRegistry;
            $params->loadString($menu->params);
        } else {
            $menu = new mosMenu($database);
            $menu->load($GLOBALS['Itemid']);
            $params = new mosParameters($menu->params);
        }
        $layout = $params->get('viewhouselayout', '');

        if ($layout == '' && isset($catid) && $catid != 0) { 
            $query = "SELECT params2 FROM #__rem_main_categories WHERE id =" . $catid;
            $database->setQuery($query);
            $params2 = $database->loadResult();
            $object_params = unserialize($params2);
            if (isset($object_params->view_house))
                $layout = $object_params->view_house;
        }
        if ($id) {
            $query = "SELECT idcat AS catid FROM #__rem_categories WHERE iditem=" . $id;
            $database->setQuery($query);
            $catid = $database->loadObjectList();
            $catid = $catid[0]->catid;
            PHP_realestatemanager::showItemREM($option, $id, $catid, $printItem, $layout);
        } else {
            if (version_compare(JVERSION, '3.0', 'ge')) {
                $menu = new JTableMenu($database);
                $menu->load($Itemid);
                $params = new JRegistry;
                $params->loadString($menu->params);
            } else {
                $menu = new mosMenu($database);
                $menu->load($GLOBALS['Itemid']);
                $params = new mosParameters($menu->params);
            }
            if (version_compare(JVERSION, "1.6.0", "lt")) {
                $id = $params->get('house');
            } else if (version_compare(JVERSION, "1.6.0", "ge") ) {
                $view_house_id = ''; // for 1.6 
                $view_house_id = $params->get('house');
                if ($view_house_id > 0) {
                    $id = $view_house_id;
                }
            }
            $query = "SELECT idcat AS catid FROM #__rem_categories WHERE iditem=" . $id;
            $database->setQuery($query);
            $catid = $database->loadObject();
            if(isset($catid))
            $catid = $catid->catid;

            PHP_realestatemanager::showItemREM($option, $id, $catid, $printItem, $layout);
        }
        break;
    case 'review_house':
    case 'review':
        PHP_realestatemanager::reviewHouse($option);
        break;

    case 'alone_category':
    case 'showCategory':
        if (version_compare(JVERSION, '3.0', 'ge')) {
            $menu = new JTableMenu($database);
            $menu->load($Itemid);
            $params = new JRegistry;
            $params->loadString($menu->params);
        } else {
            $menu = new mosMenu($database);
            $menu->load($GLOBALS['Itemid']);
            $params = new mosParameters($menu->params);
        }

        $layout = $params->get('categorylayout', '');
        

        if ($layout == '' && isset($catid) && $catid != 0) {
            $query = "SELECT params2 FROM #__rem_main_categories WHERE id =" . $catid;
            $database->setQuery($query);
            $params2 = $database->loadResult();
            $object_params = unserialize($params2);
            if (isset($object_params->alone_category))
                $layout = $object_params->alone_category;
        }

        //if ($layout == '' )

        if ($catid) {
            PHP_realestatemanager::showCategory($catid, $printItem, $option, $layout, $languagelocale);
        } else {
            $menu = new mosMenu($database);
            $menu->load($GLOBALS['Itemid']);
            $params = new mosParameters($menu->params);
            if (version_compare(JVERSION, "1.6.0", "lt")) {
                $catid = $params->get('catid');
            } else if (version_compare(JVERSION, "1.6.0", "ge")) {
                $single_category_id = ''; // for 1.6
                $single_category_id = $params->get('single_category');
                if ($single_category_id > 0)
                    $catid = $single_category_id;
            }
            PHP_realestatemanager::showCategory($catid, $printItem, $option, $layout, $languagelocale);
        }
        break;

    case 'rent_request':

        PHP_realestatemanager::showRentRequest($option, $bids);
        break;

    case 'save_rent_request':
        PHP_realestatemanager::saveRentRequest($option, $bids);
        break;

    case 'buying_request':

        PHP_realestatemanager::saveBuyingRequest($option, $bids);
        break;

    case 'mdownload':
        PHP_realestatemanager::mydownload($id);
        break;

    case 'downitsf':
        PHP_realestatemanager::downloaditself($id);
        break;
    
    case "ajax_rent_price":             
        rentPriceREM($bid_ajax_rent,$rent_from,$rent_until,$special_price,$comment_price,$currency_spacial_price);
        break;
    case 'all_categories':
        if (version_compare(JVERSION, '2.5', 'ge')) {
            $menu = new JTableMenu($database);
            $menu->load($GLOBALS['Itemid']);
            $params = new JRegistry;
            $params->loadString($menu->params);
        } else {
            $menu = new mosMenu($database);
            $menu->load($GLOBALS['Itemid']);
            $params = new mosParameters($menu->params);
        }
        $layout = $params->get('allcategorylayout', '');
        if ($layout == '')
            $layout = "default";
        PHP_realestatemanager::listCategories($catid, $layout, $languagelocale);
        break;

    default:

        if (version_compare(JVERSION, '3.0', 'ge')) {
            $menu = new JTableMenu($database);
            $menu->load($GLOBALS['Itemid']);
            $params = new JRegistry;
            $params->loadString($menu->params);
        } else {
            $menu = new mosMenu($database);
            $menu->load($GLOBALS['Itemid']);
            $params = new mosParameters($menu->params);
        }
        $layout = $params->get('allhouselayout', '');
        if ($layout == '')
            $layout = 'default';
        PHP_realestatemanager::ShowAllHouses($layout, $printItem);
        break;
}


class PHP_realestatemanager {

    static function mylenStr($str, $lenght) {
        if (strlen($str) > $lenght) {
            $str = substr($str, 0, $lenght);
            $str = substr($str, 0, strrpos($str, " "));
        }
        return $str;
    }

    static function addTitleAndMetaTags($idHouse = 0) {
        global $database, $doc, $mainframe, $Itemid;

        $view = JREQUEST::getCmd('view', null);
        $catid = JREQUEST::getInt('catid', null);
        $id = JREQUEST::getInt('id', null);
        $lang = JREQUEST::getString('lang', null);
        $title = array();
        $sitename = htmlspecialchars($mainframe->getCfg('sitename'));

        if (isset($view)) {
            $view = str_replace("_", " ", $view);
            $view = ucfirst($view);
            $title[] = $view;
        }

        $s = getWhereUsergroupsCondition('c');

        if (!isset($catid)) {

            // Parameters
            if (version_compare(JVERSION, '3.0', 'ge')) {
                $menu = new JTableMenu($database);
                $menu->load($Itemid);
                $params = new JRegistry;
                $params->loadString($menu->params);
            } else {
                $menu = new mosMenu($database);
                $menu->load($Itemid);
                $params = new mosParameters($menu->params);
            }
            if (version_compare(JVERSION, "1.6.0", "lt")) {
                $catid = $params->get('catid');
            } else if (version_compare(JVERSION, "1.6.0", "ge")) {
                $single_category_id = ''; // for 1.6 
                $single_category_id = $params->get('single_category');
                if ($single_category_id > 0)
                    $catid = $single_category_id;
            }
        }

        //To get name of category
        if (isset($catid)) {
            $query = "SELECT  c.name, c.title, c.id AS catid, c.parent_id
                    FROM #__rem_main_categories AS c
                    WHERE ($s) AND c.id = " . intval($catid);
            $database->setQuery($query);
            $row = null;
            $row = $database->loadObject();
            if (isset($row)) {
                $cattitle = array();
                if ($row->title != '') {
                    $cattitle[] = $row->title; //$row->name
                } else {
                    $cattitle[] = $row->name;
                }               
                while (isset($row) && $row->parent_id > 0) {
                    $query = "SELECT  name, title, c.id AS catid, parent_id 
                        FROM #__rem_main_categories AS c
                        WHERE ($s) AND c.id = " . intval($row->parent_id);
                    $database->setQuery($query);
                    $row = $database->loadObject();
                    if (isset($row)) { 
                        if ($row->title == '' && $row->name != '') {
                            $cattitle[] = $row->name; //$row->name
                        } else {
                            $cattitle[] = $row->title; //$row->name
                        }
                    } 
                }
                $title = array_merge($title, array_reverse($cattitle));
            }
        }

        //To get Name of the houses
        if (isset($id)) {
            $query = "SELECT h.htitle, c.id AS catid 
                    FROM #__rem_houses AS h
                    LEFT JOIN #__rem_categories AS hc ON h.id=hc.iditem
                    LEFT JOIN #__rem_main_categories AS c ON c.id=hc.idcat 
                    WHERE ({$s}) AND h.id=" . intval($id) . "
                    GROUP BY h.id";
            $database->setQuery($query);
            $row = null;
            $row = $database->loadObject();
            if (isset($row)) {
                $idtitle = array();
                $idtitle[] = $row->htitle;
                $title = array_merge($title, $idtitle);
            }
        }

        if (empty($title) && $idHouse != 0) {
            $query = "SELECT h.htitle 
                    FROM #__rem_houses AS h
                    WHERE  h.id=" . $idHouse;
            $database->setQuery($query);
            $row = null;
            $row = $database->loadObject();
            if (isset($row)) {
                $idtitle = array();
                $idtitle[] = $row->htitle;
                $title = array_merge($title, $idtitle);
            }
        }

        $tagtitle = "";
        for ($i = 0; $i < count($title); $i++) {
            $tagtitle = trim($tagtitle) . " | " . trim($title[$i]);
        }
        /*******************************************/
        $app = JFactory::getApplication();

        if ($app->getParams()->get('page_title') !='') $rem = $app->getParams()->get('page_title');
        else $rem = $app->getMenu()->getActive()->title;
        /*******************************************/
        // $rem = $menu->getActive()->title; //"RealEstate Manager ";
        //To set Title
        $title_tag = PHP_realestatemanager::mylenStr($rem . $tagtitle, 75);
        //To set meta Description
        $metadata_description_tag = PHP_realestatemanager::mylenStr($rem . $tagtitle, 200);
        //To set meta KeywordsTag
        $metadata_keywords_tag = PHP_realestatemanager::mylenStr($rem . $tagtitle, 250);
        $doc->setTitle($title_tag);
        $doc->setMetaData('description', $metadata_description_tag);
        $doc->setMetaData('keywords', $metadata_keywords_tag);
    }

    static function output_file($file, $name, $mime_type = '') {
        /*
          This function takes a path to a file to output ($file),
          the filename that the browser will see ($name) and
          the MIME type of the file ($mime_type, optional).
          If you want to do something on download abort/finish,
          register_shutdown_function('function_name');
         */
        if (!is_readable($file))
            die('File not found or inaccessible!');
        $size = filesize($file);
        $name = rawurldecode($name);

        /* Figure out the MIME type (if not specified) */
        $known_mime_types = array(
            "pdf" => "application/pdf",
            "txt" => "text/plain",
            "html" => "text/html",
            "htm" => "text/html",
            "exe" => "application/octet-stream",
            "zip" => "application/zip",
            "doc" => "application/msword",
            "xls" => "application/vnd.ms-excel",
            "ppt" => "application/vnd.ms-powerpoint",
            "gif" => "image/gif",
            "png" => "image/png",
            "jpeg" => "image/jpg",
            "jpg" => "image/jpg",
            "php" => "text/plain"
        );

        if ($mime_type == '') {
            $file_extension = strtolower(substr(strrchr($file, "."), 1));
            if (array_key_exists($file_extension, $known_mime_types)) {
                $mime_type = $known_mime_types[$file_extension];
            } else
                $mime_type = "application/force-download";
        };

        $name = str_replace(" ", "", $name);
        ob_end_clean(); //turn off output buffering to decrease cpu usage
        // required for IE, otherwise Content-Disposition may be ignored
        if (ini_get('zlib.output_compression'))
            ini_set('zlib.output_compression', 'Off');

        header('Content-Type: application/force-download');
        header("Content-Disposition: inline; filename=$name");
        header("Content-Transfer-Encoding: binary");
        header('Accept-Ranges: bytes');

        /* The three lines below basically make the download non-cacheable */
        header("Cache-control: private");
        header('Pragma: private');
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        // multipart-download and download resuming support
        if (isset($_SERVER['HTTP_RANGE'])) {
            list($a, $range) = explode("=", $_SERVER['HTTP_RANGE'], 2);
            list($range) = explode(",", $range, 2);
            list($range, $range_end) = explode("-", $range);
            $range = intval($range);
            if (!$range_end)
                $range_end = $size - 1; else
                $range_end = intval($range_end);
            $new_length = $range_end - $range + 1;
            header("HTTP/1.1 206 Partial Content");
            header("Content-Length: $new_length");
        } else {
            $new_length = $size;
            header("Content-Length: " . $size);
        }

        $chunksize = 1 * (1024 * 1024); //you may want to change this
        $bytes_send = 0;
        if ($file = fopen($file, 'r')) {
            if (isset($_SERVER['HTTP_RANGE']))
                fseek($file, $range);
            while (!feof($file) && (!connection_aborted()) && ($bytes_send < $new_length)) {
                $buffer = fread($file, $chunksize);
                print($buffer); // is also possible
                flush();
                $bytes_send += strlen($buffer);
            }
            fclose($file);
        } else
            die('Error - can not open file.');
        die();
    }

    static function mydownload($id) {
        global $realestatemanager_configuration;
        global $mosConfig_absolute_path;

        $session = JFactory::getSession();
        $pas = $session->get("ssmid", "default");
        $sid_1 = $session->getId();

        if (!($session->get("ssmid", "default")) || $pas == "" || $pas != $sid_1 || $_COOKIE['ssd'] != $sid_1 ||
                !array_key_exists("HTTP_REFERER", $_SERVER) || $_SERVER["HTTP_REFERER"] == "" ||
                strpos($_SERVER["HTTP_REFERER"], $_SERVER['SERVER_NAME']) === false) {
            echo '<H3 align="center">Link failure</H3>';
            exit;
        }
            PHP_realestatemanager::downloaditself($id);
    }

    static function downloaditself($idt) {
        global $database, $my, $realestatemanager_configuration, $mosConfig_absolute_path;

        $session = JFactory::getSession();
        $pas = $session->get("ssmid", "default");
        $sid_1 = $session->getId();

        if (!($session->get("ssmid", "default")) || $pas == "" || $pas != $sid_1 ||
                $_COOKIE['ssd'] != $sid_1 || !array_key_exists("HTTP_REFERER", $_SERVER) ||
                $_SERVER["HTTP_REFERER"] == "" ||
                 strpos($_SERVER["HTTP_REFERER"], $_SERVER['SERVER_NAME']) === false) {
            echo '<H3 align="center">Link failure</H3>';
            exit;
        }
        $session->set("ssmid", "default");

        if (array_key_exists("id", $_POST))
            $id = intval($_POST['id']); else
            $id = $idt;

        $query = "SELECT * from #__rem_houses where id = " . $id;
        $database->setQuery($query);
        $house = $database->loadObjectList();

        if (strpos($_SERVER["HTTP_REFERER"], $_SERVER['SERVER_NAME']) !== false) {
            $name = explode('/', $house[0]->edok_link);
            $file_path = $mosConfig_absolute_path .
             $realestatemanager_configuration['edocs']['location'] . $name[count($name) - 1];
            set_time_limit(0);
            PHP_realestatemanager::output_file($file_path, $name[count($name) - 1]);
            exit;
        } else {
            header("Cache-control: private");
            header('Pragma: private');
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("HTTP/1.1 301 Moved Permanently");
            header('Content-Type: application/force-download');
            header("Location: " . $house[0]->edok_link);
            exit;
        }
    }

    static function saveRentRequest($option, $bids) {  
        global $mainframe, $database, $my, $acl, $realestatemanager_configuration, $mosConfig_mailfrom, $Itemid;
        $pathway = sefRelToAbs('index.php?option=' . $option . '&amp;task=rent_request&amp;Itemid=' . $Itemid);

        $transform_from = data_transform_rem($_POST['rent_from']);
        $transform_until = data_transform_rem($_POST['rent_until']);
        $data = JFactory::getDBO();

        PHP_realestatemanager::addTitleAndMetaTags();
        // for 1.6
         // order in #__realestatemanager_orders START benja
        $jinput = JFactory::getApplication()->input;
        if(isset($_POST['user_email']) && $_POST['user_email'] != '') {
            $email = $jinput->getString('user_email');
            $houseId = $jinput->get('houseid');
            $name = $jinput->getString('user_name');
            $calculated_price = JRequest::getVar('calculated_price');///with currency//akosha
            $sql = "SELECT u.id as userID, u.email, u.name  FROM #__users AS u  WHERE u.email =".
             $data->Quote($email);
            $database->setQuery($sql);
            $result = $database->loadObjectList();

            if($result == '0' || $result == null) {
                $name = $name;
                $email = $email;
                $user = '';
            } else {
                $email = $result[0]->email;
                $user = $result[0]->userID;
                $name = $result[0]->name;
            }
            $_REQUEST['userId'] = $user;
            $_REQUEST['id'] = $houseId;
            $_REQUEST['name_bayer'] = $name;
            $calculated_price = JRequest::getVar('calculated_price');
        }
        // order in #__realestatemanager_orders STOP benja

        $path_way = $mainframe->getPathway();
        $path_way->addItem(_REALESTATE_MANAGER_LABEL_TITLE_RENT_REQUEST, $pathway);
        // --

        if (!($realestatemanager_configuration['rentstatus']['show']) 
          || !checkAccess_REM($realestatemanager_configuration['rentrequest']['registrationlevel'], 
              'NORECURSE', userGID_REM($my->id), $acl)) {
            echo _REALESTATE_MANAGER_NOT_AUTHORIZED;
            return;
        }

        $help = array();

        $rent_request = new mosRealEstateManager_rent_request($database);
        $post = JRequest::get('post');
        if (!$rent_request->bind($post)) {
            echo "<script> alert('" . $rent_request->getError() . "'); window.history.go(-1); </script>\n";
            exit;
        }

        $date_format = $realestatemanager_configuration['date_format'];
        if(phpversion() >= '5.3.0') {
            $date_format = str_replace('%', '', $date_format);
            $d_from = DateTime::createFromFormat($date_format, $post['rent_from']);
            $d_until = DateTime::createFromFormat($date_format, $post['rent_until']);
            if ($d_from === FALSE or $d_until === FALSE) {
                echo "<script> alert('". _REALESTATE_MANAGER_ADMIN_BAD_DATE_ALERT .
                 "'); window.history.go(-1); </script>\n";
                exit;
            }
            $rent_request->rent_from = $d_from->format('Y-m-d');
            $rent_request->rent_until = $d_until->format('Y-m-d');
            
        } else {
            $rent_request->rent_from = data_transform_rem($post['rent_from'],'to');
            $rent_request->rent_until = data_transform_rem($post['rent_until'],'to');
        }

        $rent_request->user_email = ($rent_request->user_email);
        $rent_request->rent_request = date("Y-m-d H:i:s");
        $rent_request->fk_houseid = intval($_REQUEST["houseid"]);

        if ($rent_request->rent_from > $rent_request->rent_until) {
            echo "<script> alert('" . $rent_request->rent_from . " is more than " . $rent_request->rent_until .
            "'); window.history.go(-1); </script>\n";
            exit;
        }
        $query = "SELECT * FROM #__rem_houses where id= " . $rent_request->fk_houseid;
        $data->setQuery($query);
        $houseid = $data->loadObject();
      
        $rent_from = substr($rent_request->rent_from, 0, 10);
        $rent_until = substr($rent_request->rent_until, 0, 10);
         
        if ($my->id != 0)
            $rent_request->fk_userid = $my->id;
        if (!$rent_request->check()) {
            echo "<script> alert('" . $rent_request->getError() . "'); window.history.go(-1); </script>\n";
            exit;
        }

        if (!$rent_request->store()) {
            echo "<script> alert('" . $rent_request->getError() . "'); window.history.go(-1); </script>\n";
            exit;
        }   
        
        $time_difference = calculatePriceREM($houseid->id,$rent_from,$rent_until,
          $realestatemanager_configuration,$database);    
        
        $rent_request->checkin();
        array_push($help, $rent_request);

        $currentcat = new stdClass();

        // Parameters
        $menu = new mosMenu($database);
        $menu->load($Itemid);
        $params = new mosParameters($menu->params);
        $menu_name = set_header_name_rem($menu, $Itemid);
        $params->def('header', $menu_name);
        $params->def('pageclass_sfx', '');
        $params->def('back_button', $mainframe->getCfg('back_button'));
        // --

        $currentcat->descrip = _REALESTATE_MANAGER_LABEL_RENT_REQUEST_THANKS;
        $currentcat->img = "./components/com_realestatemanager/images/rem_logo.png";
        $currentcat->header = $params->get('header');

        // used to show table rows in alternating colours
        $tabclass = array('sectiontableentry1', 'sectiontableentry2');
        //********************   end add send mail for admin   ****************
        $backlink = JRoute::_($_SERVER['HTTP_REFERER']);
        HTML_realestatemanager :: showRentRequestThanks($params, $backlink, $currentcat, $houseid, $time_difference);
    }

    static function saveBuyingRequest($option, $bids) {
        global $mainframe, $database, $my, $Itemid, $acl;
        global $realestatemanager_configuration, $mosConfig_mailfrom;
// echo __FILE__.":  ".__LINE__."<br />";
         $jinput = JFactory::getApplication()->input;
         if(isset($_POST['customer_email']) && $_POST['customer_email'] != '') {
            $email = $jinput->getString('customer_email');
            $bId = $jinput->get('bid');
            $name = $jinput->getString('customer_name');
            $time_difference = null;
            $sql = "SELECT u.id as userID, u.email, u.name  FROM `#__users` AS u  WHERE u.email ='". $email."'";
            $database->setQuery($sql);
            $result = $database->loadObjectList();
            if($result == '0' || $result == null) {
                $name = $name;
                $email = $email;
                $user = '';
            } else {
                $email = $result[0]->email;
                $user = $result[0]->userID;
                $name = $result[0]->name;
            }
            $_REQUEST['userId'] = $user;
            $_REQUEST['user_email'] = $email;
            $_REQUEST['name_bayer'] = $name;
            $_REQUEST['id'] = $houseId = $bId[0];

            if($realestatemanager_configuration['special_price']['show']){
                $rent_from = data_transform_rem(date('Y-m-d'));
                $rent_until = data_transform_rem(date('Y-m-d'));
                $query = "SELECT special_price as price,priceunit FROM `#__rem_rent_sal` WHERE fk_houseid = ".$houseId .
                    " AND (price_from <= ('" .$rent_until. "') AND price_to >= ('" .$rent_from. "'))";
                $database->setQuery($query);
                $res = $database->loadObjectList();
                if($res){
                    $time_difference = array();
                    $time_difference['0'] = $res['0']->price;
                    $time_difference['1'] = $res['0']->priceunit;
                    $sql = "SELECT htitle FROM #__rem_houses WHERE id='".$houseId."'";
                    $database->setQuery($sql);
                    $htitle = $database->loadResult();
                }else{
                    $sql = "SELECT price,priceunit,htitle FROM #__rem_houses WHERE id='".$houseId."'";
                    $database->setQuery($sql);
                    $res = $database->loadObjectList();
                    $htitle = $res[0]->htitle;
                }
            }else{
                $sql = "SELECT price,priceunit,htitle FROM #__rem_houses WHERE id='".$houseId."'";
                $database->setQuery($sql);
                $res = $database->loadObjectList();
                $htitle = $res[0]->htitle;
            }
            $calculated_price = $res['0']->price.' '.$res['0']->priceunit;
        }// order in #__rem_orders

        if (!($realestatemanager_configuration['buystatus']['show']) ||
                !checkAccess_REM($realestatemanager_configuration['buyrequest']['registrationlevel'],
                 'NORECURSE', userGID_REM($my->id), $acl)) {
            echo _REALESTATE_MANAGER_NOT_AUTHORIZED;
            return;
        }

        $buying_request = new mosRealEstateManager_buying_request($database);
      
        $post = JRequest::get('post');
        if (!$buying_request->bind($post)) {
            echo $buying_request->getError();
            exit;
        }
       
        $buying_request->customer_email = ($buying_request->customer_email);
        $buying_request->buying_request = date("Y-m-d H:i:s");
        $buying_request->fk_houseid = $bids[0];
        if (!$buying_request->store())
            echo "error:" . $buying_request->getError();
        $currentcat = new stdClass();

        // Parameters
        $menu = new JTableMenu($database);
        $menu->load($Itemid);
        $params = new mosParameters($menu->params);
        $menu_name = set_header_name_rem($menu, $Itemid);
        $params->def('header', $menu_name);
        $params->def('pageclass_sfx', '');
        $params->def('back_button', $mainframe->getCfg('back_button'));

        $currentcat->descrip = _REALESTATE_MANAGER_LABEL_BUYING_REQUEST_THANKS;

        // page image
        $currentcat->img = "./components/com_realestatemanager/images/rem_logo.png";
        $currentcat->header = $params->get('header');
        $query = "SELECT * FROM #__rem_houses where id= " . $buying_request->fk_houseid;
        $database->setQuery($query);
        $houseid = $database->loadObject();
        $backlink = JRoute::_($_SERVER['HTTP_REFERER']);
        HTML_realestatemanager :: showRentRequestThanks($params, $backlink, $currentcat,$houseid);
    }

    static function showRentRequest($option, $bid) {  exit;
        global $mainframe, $database, $my, $Itemid, $acl, $realestatemanager_configuration;

        $pathway = sefRelToAbs('index.php?option=' . $option . '&amp;task=rent_request&amp;Itemid=' . $Itemid);

        // for 1.6
        $path_way = $mainframe->getPathway();
        $path_way->addItem(_REALESTATE_MANAGER_LABEL_TITLE_RENT_REQUEST, $pathway);
        // --

        if (!($realestatemanager_configuration['rentstatus']['show']) ||
         !checkAccess_REM($realestatemanager_configuration['rentrequest']['registrationlevel'],
          'NORECURSE', userGID_REM($my->id), $acl)) {
            echo _REALESTATE_MANAGER_NOT_AUTHORIZED;
            return;
        }

        $bids = implode(',', $bid);

        // getting all houses for this category
        $query = "SELECT * FROM #__rem_houses"
                . "\nWHERE `id` IN (" . $bids . ") ORDER BY `catid`, `ordering`";
        $database->setQuery($query);
        $houses = $database->loadObjectList();
 
        $currentcat = new stdClass();

        // Parameters
        $menu = new mosMenu($database);
        $menu->load($Itemid);
        $params = new mosParameters($menu->params);
        $menu_name = set_header_name_rem($menu, $Itemid);
        $params->def('header', $menu_name);
        // --

        $params->def('pageclass_sfx', '');
        $params->def('show_rentstatus', 1);
        $params->def('show_rentrequest', 1);
        $params->def('rent_save', 1);
        $params->def('back_button', $mainframe->getCfg('back_button'));

        // page description
        $currentcat->descrip = _REALESTATE_MANAGER_DESC_RENT;

        // page image
        $currentcat->img = null;
        $currentcat->header = $params->get('header');
        // used to show table rows in alternating colours
        $tabclass = array('sectiontableentry1', 'sectiontableentry2');

        HTML_realestatemanager::showRentRequest($houses, $currentcat, $params, $tabclass,
         $catid, $sub_categories, false, $option);
    }

    /**
     * comments for registered users
     */
    static function reviewHouse() {
        global $mainframe, $database, $my, $Itemid, $acl, $realestatemanager_configuration,
         $mosConfig_absolute_path, $catid;
        global $mosConfig_mailfrom, $session, $option;

        if (!($realestatemanager_configuration['reviews']['show']) ||
         !checkAccess_REM($realestatemanager_configuration['reviews']['registrationlevel'],
          'NORECURSE', userGID_REM($my->id), $acl)) {
            echo _REALESTATE_MANAGER_NOT_AUTHORIZED;
            return;
        }
        $review = new mosRealEstateManager_review($database);
        $review->published = 0;

        //************publish on add end

        $review->date = date("Y-m-d H:i:s");
        $review->getReviewFrom($my->id);

        $post = JRequest::get('post');
        if (!$review->bind($post)) {
            echo "<script> alert('" . $house->getError() . "'); window.history.go(-1); </script>\n";
            exit;
        }
        $review->rating = intval($_POST['rating']);
        if (version_compare(JVERSION, "3.0", "ge"))
            $review->rating *= 2;
        if (!$review->check()) {
            echo "<script> alert('" . $house->getError() . "'); window.history.go(-1); </script>\n";
            exit;
        }
        if (!$review->store()) {
            echo "<script> alert('" . $house->getError() . "'); window.history.go(-1); </script>\n";
            exit;
        }

        //showing the original entries
        mosRedirect("index.php?option=" . $option . "&task=view_house&catid=" . intval($_POST['catid'])
         . "&id=$review->fk_houseid&Itemid=$Itemid");
    }

    static function constructPathway($cat) {
        global $mainframe, $database, $option, $Itemid, $mosConfig_absolute_path;

        $app = JFactory::getApplication();
        $path_way = $app->getPathway();

        $query = "SELECT * FROM #__rem_main_categories WHERE section = 'com_realestatemanager' AND published = 1";
        $database->setQuery($query);
        $rows = $database->loadObjectlist('id');
        if ($cat != NULL)
            $pid = $cat->id;  //need check
        $pathway = array();
        $pathway_name = array();
        while ($pid != 0) {
            $cat = @$rows[$pid];
            $pathway[] = sefRelToAbs('index.php?option=' . $option .
             '&task=showCategory&catid=' . @$cat->id . '&Itemid=' . $Itemid);
            $pathway_name[] = @$cat->title;
            $pid = @$cat->parent_id;
        }
        $pathway = array_reverse($pathway);
        $pathway_name = array_reverse($pathway_name);

        for ($i = 0, $n = count($pathway); $i < $n; $i++) {
            $path_way->addItem($pathway_name[$i], $pathway[$i]);
        }
    }

    //get current user groups
    static function getUserGroups() {
        $my = JFactory::getUser();
        $acl = JFactory::getACL();
        $usergroups = $acl->get_group_parents($my->gid, 'ARO', 'NORECURSE');
        if ($usergroups)
            $usergroups = ',' . implode(',', $usergroups); else
            $usergroups = '';
        return '-2,' . $my->gid . $usergroups;
    }

    static function showCategory($catid, $printItem, $option, $layout, $languagelocale) {
        global $mainframe, $database, $acl, $my, $langContent;
        global $mosConfig_shownoauth, $mosConfig_live_site, $mosConfig_absolute_path;
        global $cur_template, $Itemid, $realestatemanager_configuration;
        global $mosConfig_list_limit, $limit, $total, $limitstart;

        PHP_realestatemanager::addTitleAndMetaTags();
        //getting the current category informations
        $database->setQuery("SELECT * FROM #__rem_main_categories WHERE id='" . intval($catid) . "'");
        $category = $database->loadObjectList();
        if (isset($category[0]))
            $category = $category[0];
        else {
            echo _REALESTATE_MANAGER_ERROR_ACCESS_PAGE;
            return;
        }

        if ($category->params == '')  $category->params = '-2';
        
        if (!checkAccess_REM($category->params, 'NORECURSE', userGID_REM($my->id), $acl)) {
            echo _REALESTATE_MANAGER_ERROR_ACCESS_PAGE;
            return;
        }
        //sorting

        $item_session = JFactory::getSession();
        $sort_arr = $item_session->get('rem_housesort', '');
        if (is_array($sort_arr)) {
            $tmp1 = mosGetParam($_POST, 'order_direction');
            if ($tmp1 != '') {
                $sort_arr['order_direction'] = $tmp1;
            }
            $tmp1 = mosGetParam($_POST, 'order_field');
            //$tmp1= $database->Quote($tmp1);
            if ($tmp1 != '') {
                $sort_arr['order_field'] = $tmp1;
            }
            $item_session->set('rem_housesort', $sort_arr);
        } else {
            $sort_arr = array();
            $sort_arr['order_field'] = 'htitle';
            $sort_arr['order_direction'] = 'asc';
            $item_session->set('rem_housesort', $sort_arr);
        }
        if ($sort_arr['order_field'] == "price")
            $sort_string = "CAST( " . $sort_arr['order_field'] . " AS SIGNED)" . " " . $sort_arr['order_direction'];
        else
            $sort_string = $sort_arr['order_field'] . " " . $sort_arr['order_direction'];



        if (isset($langContent)) {

            $lang = $langContent;
            // $query = "SELECT lang_code FROM #__languages WHERE sef = '$lang'";
            // $database->setQuery($query);
            // $lang = $database->loadResult();
            $lang = " and ( h.language = '$lang' or h.language like 'all' or h.language like '' "
             . " or h.language like '*' or h.language is null) "
             . " AND ( c.language = '$lang' or c.language like 'all' or c.language like '' or "
             . " c.language like '*' or c.language is null) ";
        } else {
            $lang = "";
        }
        $s = getWhereUsergroupsCondition('c');



        $query = "SELECT COUNT(DISTINCT h.id)
            \nFROM #__rem_houses AS h"
                . "\nLEFT JOIN #__rem_categories AS hc ON hc.iditem=h.id"
                . "\nLEFT JOIN #__rem_main_categories AS c ON c.id=hc.idcat"
                . "\nWHERE c.id = '$catid' AND h.published='1' $lang AND h.approved='1' AND c.published='1'
             AND ($s)";


        //getting groups of user
        $s = getWhereUsergroupsCondition('c');

        $database->setQuery($query);
        $total = $database->loadResult();


        $pageNav = new JPagination($total, $limitstart, $limit); // for J 1.6
        // getting all houses for this category

        $query = "SELECT h.*,hc.idcat AS catid,hc.idcat AS idcat, c.title as category_title "
                . "\nFROM #__rem_houses AS h "
                . "\nLEFT JOIN #__rem_categories AS hc ON hc.iditem=h.id "
                . "\nLEFT JOIN #__rem_main_categories AS c ON c.id=hc.idcat "
                . "\nWHERE hc.idcat = '" . $catid . "' AND h.published='1' "
                . "\n    AND c.published='1'  $lang AND ($s)"
                . "\nGROUP BY h.id"
                . "\nORDER BY " . $sort_string
                . "\nLIMIT $pageNav->limitstart,$pageNav->limit;";


        $database->setQuery($query);
        $houses = $database->loadObjectList();

        // For show all houses from subcategories which are included in main category use this request 
        //(just comment request to not display subcategory houses)

        // $query = "SELECT id FROM #__rem_main_categories WHERE parent_id = '" . $catid . "'";
        // $database->setQuery($query);
        // $if_parent = $database->loadColumn();
        // if(!empty($if_parent)){
        //     foreach($if_parent as $parent_cat){
        //         $query = "SELECT h.*,hc.idcat AS catid,hc.idcat AS idcat, c.title as category_title "
        //                 . "\nFROM #__rem_houses AS h "
        //                 . "\nLEFT JOIN #__rem_categories AS hc ON hc.iditem=h.id "
        //                 . "\nLEFT JOIN #__rem_main_categories AS c ON c.id=hc.idcat "
        //                 . "\nWHERE hc.idcat = '" . $parent_cat . "' AND h.published='1' "
        //                 . "\n AND c.published='1'  $lang AND ($s)"
        //                 . "\nGROUP BY h.id"
        //                 . "\nORDER BY " . $sort_string
        //                 . "\nLIMIT $pageNav->limitstart,$pageNav->limit;";
        //         $database->setQuery($query);
        //         $child_houses = $database->loadObjectList();
        //         $houses = array_merge($child_houses,$houses);
        //         $query = "SELECT id FROM #__rem_main_categories WHERE parent_id = '" . $parent_cat . "'";
        //         $database->setQuery($query);
        //         $if_parent2 = $database->loadColumn();
        //         foreach($if_parent2 as $child_id){
        //             $query = "SELECT h.*,hc.idcat AS catid,hc.idcat AS idcat, c.title as category_title "
        //                     . "\nFROM #__rem_houses AS h "
        //                     . "\nLEFT JOIN #__rem_categories AS hc ON hc.iditem=h.id "
        //                     . "\nLEFT JOIN #__rem_main_categories AS c ON c.id=hc.idcat "
        //                     . "\nWHERE hc.idcat = '" . $child_id . "' AND h.published='1' "
        //                     . "\n AND c.published='1'  $lang AND ($s)"
        //                     . "\nGROUP BY h.id"
        //                     . "\nORDER BY " . $sort_string
        //                     . "\nLIMIT $pageNav->limitstart,$pageNav->limit;";
        //             $database->setQuery($query);
        //             $child_houses = $database->loadObjectList();
        //             $houses = array_merge($child_houses,$houses);
        //         }
        //     }
        // }


        $query = "SELECT h.*,c.id, c.parent_id, c.title, c.image,COUNT(hc.iditem) as houses,
        '1' as display" .
                " \n FROM  #__rem_main_categories as c " .
                " \n LEFT JOIN #__rem_categories AS hc ON hc.idcat=c.id " .
                " \n LEFT JOIN #__rem_houses AS h ON h.id=hc.iditem " .
                "  \n WHERE c.section='com_realestatemanager'  $lang "
                . " AND c.published=1 AND ({$s})
        \n GROUP BY c.id
        \n ORDER BY c.parent_id DESC, c.ordering ";

        $database->setQuery($query);
        $cat_all = $database->loadObjectList();
        
        foreach ($cat_all as $k1 => $cat_item1) {            
            $query = "SELECT COUNT(hc.iditem) as houses" .
                         "\n FROM  #__rem_main_categories as c " .
                         "\n LEFT JOIN #__rem_categories AS hc ON hc.idcat=c.id " .
                         "\n LEFT JOIN #__rem_houses AS h ON h.id=hc.iditem " .
                         "\n WHERE c.section='com_realestatemanager' AND c.published=1  $lang
                          \n AND ( h.published || isnull(h.published) ) AND ( h.approved || isnull(h.approved )) AND ({$s})
                          \n AND c.id = " . $cat_all[$k1]->id . "    
                          \n GROUP BY c.id";              
                
                    $database->setQuery($query);

                    $houses_count = $database->loadObjectList();
                    if($houses_count)
                        $cat_all[$k1]->houses = $houses_count[0]->houses;
                    else
                        $cat_all[$k1]->houses = 0;                    
        }

        $currentcat = new stdClass();

        // Parameters
        $menu = new JTableMenu($database); //for 1.6
        $menu->load($Itemid);

        $menu_name = set_header_name_rem($menu, $Itemid);

        $params = new mosParameters($menu->params);
        $params->def('show_category', 1);
       // $params->def('header', $menu_name); // for 1.6
        $params->def('pageclass_sfx', '');
        $params->def('category_name', $category->title);
        if ($layout==''){
            $layout = ($params->get('allhouselayout'));
        }
        if(JRequest::getVar('module') == 'mod_realestatemanager_featured_pro_j3'){
            $layout = 'default';
        }
        PHP_realestatemanager::constructPathway($category);
      
//***************   begin show search_option    *********************
        if ($realestatemanager_configuration['search_option']['show']) {
            $params->def('search_option', 1);
            if (checkAccess_REM($realestatemanager_configuration['search_option']['registrationlevel'],
             'NORECURSE', userGID_REM($my->id), $acl)) {
                $params->def('search_option_registrationlevel', 1);
            }
        }
//**************   end show search_option     ******************************
      
        if (($realestatemanager_configuration['rentstatus']['show'])) {
            if (checkAccess_REM($realestatemanager_configuration['rentrequest']['registrationlevel'],
             'RECURSE', userGID_REM($my->id), $acl)) {
                $params->def('show_rentstatus', 1);
                $params->def('show_rentrequest', 1);
            }
        }

        if ($realestatemanager_configuration['price']['show']) {
            $params->def('show_pricestatus', 1);
            if (checkAccess_REM($realestatemanager_configuration['price']['registrationlevel'],
             'NORECURSE', userGID_REM($my->id), $acl)) {
                $params->def('show_pricerequest', 1);
            }
        }

       //***************   begin show search_option    *********************/
        if ($realestatemanager_configuration['search_option']['show']) {
            $params->def('search_option', 1);
            if (checkAccess_REM($realestatemanager_configuration['search_option']['registrationlevel'],
             'NORECURSE', userGID_REM($my->id), $acl)) {
                $params->def('search_option_registrationlevel', 1);
            }
        }
        //**************   end show search_option     ******************************
        $params->def('sort_arr_order_direction', $sort_arr['order_direction']);
        $params->def('sort_arr_order_field', $sort_arr['order_field']);

        //add for show in category picture
        if ($realestatemanager_configuration['cat_pic']['show'])
            $params->def('show_cat_pic', 1);

        $params->def('show_rating', 1);
        $params->def('hits', 1);
        $params->def('back_button', $mainframe->getCfg('back_button'));

        $currentcat->descrip = $category->description;
        

        $params->def('show_rating', 1);
        $params->def('hits', 1);
        $params->def('back_button', $mainframe->getCfg('back_button'));

        $currentcat->descrip = $category->description;
        // page image
        $currentcat->img = null;
        $path = $mosConfig_live_site . '/images/stories/';

        $currentcat->header = $params->get('header');
        $currentcat->header = ((trim($currentcat->header)) ? $currentcat->header . ":" : "") . $category->title;
        $currentcat->img = null;


        // used to show table rows in alternating colours
        $tabclass = array('sectiontableentry1', 'sectiontableentry2');

        $params->def('minifotohigh', $realestatemanager_configuration['foto']['high']);
        $params->def('minifotowidth', $realestatemanager_configuration['foto']['width']);

        foreach ($houses as $house) {
            if ($house->language != '*') {
                $query = "SELECT sef FROM #__languages WHERE lang_code = '$house->language'";
                $database->setQuery($query);
                $house->language = $database->loadResult();
            }
        }

        if (empty($houses)) {
            HTML_realestatemanager::displayHouses_empty($houses, $currentcat, $params,
               $tabclass, $catid, $cat_all, $pageNav,PHP_realestatemanager::is_exist_subcategory_houses($catid), $option);
        } else {
            HTML_realestatemanager::displayHouses($houses, $currentcat, $params,
             $tabclass, $catid, $cat_all, $pageNav,PHP_realestatemanager::is_exist_subcategory_houses($catid), $option, $layout);
        }
    }

    static function showItemREM($option, $id, $catid, $printItem, $layout) {
        global $mainframe, $database, $my, $acl, $option;
        global $mosConfig_shownoauth, $mosConfig_live_site, $mosConfig_absolute_path;
        global $cur_template, $Itemid, $realestatemanager_configuration;

        PHP_realestatemanager::addTitleAndMetaTags($id);

        $database->setQuery("SELECT id FROM #__rem_houses where id=$id ");
        if (version_compare(JVERSION, "3.0.0", "lt"))
            $trueid = $database->loadResultArray();
        else
            $trueid = $database->loadColumn();
        if (!in_array(intval($id), $trueid)) {
            echo _REALESTATE_MANAGER_ERROR_ACCESS_PAGE;
            return;
        }
        //add to path category name
        //getting the current category informations
        $query = "SELECT * FROM #__rem_main_categories WHERE id='" . intval($catid) . "'";

        $database->setQuery($query);
        $category = $database->loadObjectList();

        if (isset($category[0]))
            $category = $category[0];
        else {
            echo _REALESTATE_MANAGER_ERROR_ACCESS_PAGE;
            return;
        }
        PHP_realestatemanager::constructPathway($category);
        $pathway = sefRelToAbs('index.php?option=' . $option .
                '&task=showCategory&catid=' . $category->id . '&Itemid=' . $Itemid);
        $pathway_name = 'house';
        // for  1.6  
        $path_way = $mainframe->getPathway();
        $path_way->addItem($pathway_name, $pathway);

        //Record the hit
        $sql = "UPDATE #__rem_houses SET hits = hits + 1 WHERE id = " . $id . "";
        $database->setQuery($sql);
        $database->query();

        $sql2 = "UPDATE #__rem_houses SET featured_clicks = featured_clicks - 1 "
         . " WHERE featured_clicks > 0 and id = " . $id . "";
        $database->setQuery($sql2);
        $database->query();


        $sql3 = "UPDATE #__rem_houses SET featured_shows = featured_shows - 1 "
         . " WHERE featured_shows > 0 and id = " . $id . "";
        $database->setQuery($sql3);
        $database->query();

        //load the house
        $house = new mosRealEstateManager($database);
        $house->load($id);
        $house->setOwnerName();
        $access = $house->getAccess_REM();

        $selectstring = "SELECT a.* FROM #__rem_houses AS a";
        $database->setQuery($selectstring);
        $rows = $database->loadObjectList();
        $date = date(time());
        foreach ($rows as $row) {
            $check = strtotime($row->checked_out_time);
            $remain = 7200 - ($date - $check);
            if (($remain <= 0) && ($row->checked_out != 0)) {
                $database->setQuery("UPDATE #__rem_houses SET checked_out=0,checked_out_time=0");
                $database->query();
            }
        }

        if (!checkAccess_REM($access, 'RECURSE', userGID_REM($my->id), $acl)) {
            echo _REALESTATE_MANAGER_ERROR_ACCESS_PAGE;
            return;
        }
        if ($house->owneremail != $my->email) {
            if ($house->published == 0) {
                echo _REALESTATE_MANAGER_ERROR_HOUSE_NOT_PUBLISHED;
                return;
            }
            if ($house->approved == 0) {
                echo _REALESTATE_MANAGER_ERROR_HOUSE_NOT_APPROVED;
                return;
            }
        }
        $path_way->addItem(substr($house->htitle, 0, 32) . "");
        /////////////////////////////////////////////////////////////////////////////////////
        //Select list for listing type
        $listing_type[0] = _REALESTATE_MANAGER_OPTION_SELECT;
        $listing_type[1] = _REALESTATE_MANAGER_OPTION_FOR_RENT;
        $listing_type[2] = _REALESTATE_MANAGER_OPTION_FOR_SALE;

        //Select list for listing status
        $listing_status[_REALESTATE_MANAGER_OPTION_SELECT] = 0;
        $listing_status1 = explode(',', _REALESTATE_MANAGER_OPTION_LISTING_STATUS);
        $i = 1;
        foreach ($listing_status1 as $listing_status2) {
            $listing_status[$listing_status2] = $i;
            $i++;
        }

        //Select list for property type
        $property_type[_REALESTATE_MANAGER_OPTION_SELECT] = 0;
        $property_type1 = explode(',', _REALESTATE_MANAGER_OPTION_PROPERTY_TYPE);
        $i = 1;
        foreach ($property_type1 as $property_type2) {
            $property_type[$property_type2] = $i;
            $i++;
        }


        ////////////////////////////////////////////////////////////
        //$app = JFactory::getApplication();
        //$menu1 = $app->getMenu();
        //if ( $menu1->getItem($Itemid) )
        //$menu_name = $menu1->getItem($Itemid)->title ;
        //else $menu_name = '';
        // --
        // Parameters
        $menu = new JTableMenu($database); // for 1.6
        // Parameters
        $menu = new mosMenu($database);
        $menu->load($Itemid);

        $menu_name = set_header_name_rem($menu, $Itemid);

        $params = new mosParameters($menu->params);
        $params->def('header', $menu_name); //for 1.6
        $params->def('pageclass_sfx', '');
        if (!isset($my->id)) { //for 1.6
            $my->id = 0;
        }

        if ($realestatemanager_configuration['calendar']['show']) {
            $params->def('calendar_show', 1);
            if (checkAccess_REM($realestatemanager_configuration['calendarlist']['registrationlevel'],
               'NORECURSE', userGID_REM($my->id), $acl)) {
                $params->def('calendarlist_show', 1);
            }
        }

        //***  end add for  Manager mail to: button 'mail to'    **********

        if ($realestatemanager_configuration['rentstatus']['show']) {
            $params->def('show_rentstatus', 1);
            if (checkAccess_REM($realestatemanager_configuration['rentrequest']['registrationlevel'], 
              'NORECURSE', userGID_REM($my->id), $acl)) {
                $params->def('show_rentrequest', 1);
            }
        }

        if ($realestatemanager_configuration['buystatus']['show']) {
            $params->def('show_buystatus', 1);
            if (checkAccess_REM($realestatemanager_configuration['buyrequest']['registrationlevel'], 
              'NORECURSE', userGID_REM($my->id), $acl)) {
                $params->def('show_buyrequest', 1);
            }
        }

        if ($realestatemanager_configuration['reviews']['show']) {
            $params->def('show_reviews', 1);
            if (checkAccess_REM($realestatemanager_configuration['reviews']['registrationlevel'],
               'NORECURSE', userGID_REM($my->id), $acl)) {
                $params->def('show_inputreviews', 1);
            }
        }

        if ($realestatemanager_configuration['edocs']['show']) {
            $params->def('show_edocstatus', 1);
            if (checkAccess_REM($realestatemanager_configuration['edocs']['registrationlevel'],
               'NORECURSE', userGID_REM($my->id), $acl)) {
                $params->def('show_edocsrequest', 1); //+18.01
                //+18.01
            }
        }

        if ($realestatemanager_configuration['price']['show']) {
            $params->def('show_pricestatus', 1);
            if (checkAccess_REM($realestatemanager_configuration['price']['registrationlevel'], 
              'NORECURSE', userGID_REM($my->id), $acl)) {
                $params->def('show_pricerequest', 1); //+18.01
            }
        }

        if ($realestatemanager_configuration['sale_separator']) {
            $params->def('show_sale_separator', 1);
        }


        //************   begin show 'location and reviews tab'   ***************
        if (($realestatemanager_configuration['location_tab']['show'])) {
            $params->def('show_location', 1);
            if (checkAccess_REM($realestatemanager_configuration['location_tab']['registrationlevel'],
               'NORECURSE', userGID_REM($my->id), $acl)) {
                $params->def('show_locationtab_registrationlevel', 1); //+18.01
            }
        }
        if (($realestatemanager_configuration['reviews_tab']['show'])) {
            $params->def('show_reviews_tab', 1);
            if (checkAccess_REM($realestatemanager_configuration['reviews_tab']['registrationlevel'], 
             'NORECURSE', userGID_REM($my->id), $acl)) {
                $params->def('show_reviewstab_registrationlevel', 1); //+18.01
            }
        }
        if (($realestatemanager_configuration['calendar']['show'])) {
            $params->def('calendar_option', 1);
            $i = checkAccess_REM($realestatemanager_configuration['calendarlist']['registrationlevel'],
             'NORECURSE', userGID_REM($my->id), $acl);
            if ($i) {
                $params->def('calendar_option_registrationlevel', 1); //+18.01
            }
        }
        //************   end show 'location and reviews tab'   ***************

        $params->def('pageclass_sfx', '');
        $params->def('item_description', 1);
        $params->def('show_edoc', $realestatemanager_configuration['edocs']['show']);
        $params->def('back_button', $mainframe->getCfg('back_button'));

        // page header
        $currentcat = new stdClass();
        $currentcat->header = $params->get('header');
        $currentcat->header = ((trim($currentcat->header)) ? $currentcat->header . ":" : "") . $house->htitle;
        $currentcat->img = null;

        $query = "select main_img from #__rem_photos WHERE fk_houseid='$house->id' order by img_ordering,id";
        $database->setQuery($query);
        $house_photos = $database->loadObjectList();
        // show the house

        $query = "SELECT f.* ";
        $query .= "FROM #__rem_feature as f ";
        $query .= "LEFT JOIN #__rem_feature_houses as fv ON f.id = fv.fk_featureid ";
        $query .= "WHERE f.published = 1 and fv.fk_houseid = $id ";
        $query .= "ORDER BY f.categories";
        $database->setQuery($query);
        $house_feature = $database->loadObjectList();

/**********************************************/
        $currencyArr = array();
        $currentCurrency='';
        $currencys = explode(';', $realestatemanager_configuration['currency']);
        foreach ($currencys as $oneCurency) {
            $oneCurrArr = explode('=', $oneCurency);
            if(!empty($oneCurrArr[0]) && !empty($oneCurrArr[1])){
               $currencyArr[$oneCurrArr[0]] = $oneCurrArr[1]; 
               if($house->priceunit == $oneCurrArr[0]){
                   $currentCurrency = $oneCurrArr[1];
               }
            }
        }
        if($currentCurrency){
            foreach ($currencyArr as $key=>$value) {
                $currencys_price[$key] = round($value / $currentCurrency * $house->price, 2);

            }
        }else{
            if($house->owner_id == $my->id){
                JError::raiseWarning( 100, _REALESTATE_MANAGER_CURRENCY_ERROR);
            }
        }

/**********************************************/

        //////////////start select video/tracks
        $query = "SELECT src,type,youtube FROM #__rem_video_source AS r
                LEFT JOIN  #__rem_houses AS h ON r.fk_house_id=h.id
                WHERE r.fk_house_id =" . $house->id;
        $database->setQuery($query);
        $videos = $database->loadObjectList();
        $query = "SELECT src,kind,scrlang,label FROM #__rem_track_source AS t
                LEFT JOIN  #__rem_houses AS h ON t.fk_house_id = h.id
                WHERE t.fk_house_id = " . $house->id;
        $database->setQuery($query);
        $tracks = $database->loadObjectList();
        /////////////////////end

        HTML_realestatemanager::displayHouse($house, $tabclass,
             $params, $currentcat, $ratinglist, $house_photos,$videos,$tracks, $id, $catid,
              $option, $house_feature, $currencys_price, $layout);

    }

    static function getMonth($month) {

        switch ($month) {
            case 1:
                $smonth = JText::_('JANUARY');
                break;
            case 2:
                $smonth = JText::_('FEBRUARY');
                break;
            case 3:
                $smonth = JText::_('MARCH');
                break;
            case 4:
                $smonth = JText::_('APRIL');
                break;
            case 5:
                $smonth = JText::_('MAY');
                break;
            case 6:
                $smonth = JText::_('JUNE');
                break;
            case 7:
                $smonth = JText::_('JULY');
                break;
            case 8:
                $smonth = JText::_('AUGUST');
                break;
            case 9:
                $smonth = JText::_('SEPTEMBER');
                break;
            case 10:
                $smonth = JText::_('OCTOBER');
                break;
            case 11:
                $smonth = JText::_('NOVEMBER');
                break;
            case 12:
                $smonth = JText::_('DECEMBER');
                break;
        }

        return $smonth;
    }

    static function showSearchHouses($options, $catid, $option, $layout = "") {
        global $mainframe, $database, $my, $langContent, $acl;
        global $mosConfig_shownoauth, $mosConfig_live_site, $mosConfig_absolute_path, $realestatemanager_configuration;
        global $cur_template, $Itemid;

        PHP_realestatemanager::addTitleAndMetaTags();

        $currentcat = new stdClass();
        //if it is't from menus, get layout from config.

        $jinput = JFactory::getApplication()->input;

        //parameters
        $menu = new mosMenu($database);
        $menu->load($Itemid);
        $params = new mosParameters($menu->params);

        $menu_name = set_header_name_rem($menu, $Itemid);

        $params->def('header', $menu_name);
        $params->def('pageclass_sfx', '');
        $params->def('show_category', '1');
        $params->def('back_button', $mainframe->getCfg('back_button'));
        $pathway = sefRelToAbs('index.php?option=' . $option . '&amp;task=show_search&amp;Itemid=' . $Itemid);
        $pathway_name = _REALESTATE_MANAGER_LABEL_SEARCH;

        $currentcat->descrip = " ";
        $currentcat->align = 'right';

        //page image
        $currentcat->img = "./components/com_realestatemanager/images/rem_logo.png";

        //used to show table rows in alternating colours
        $tabclass = array('sectiontableentry1', 'sectiontableentry2');

        //listing type
        $hlisting = $jinput->get('listing_type') ? $jinput->get('listing_type') : _REALESTATE_MANAGER_LABEL_ALL;
        $listing_type[] = mosHtml::makeOption(_REALESTATE_MANAGER_LABEL_ALL, _REALESTATE_MANAGER_LABEL_ALL);
        $listing_type[] = mosHtml::makeOption(1, _REALESTATE_MANAGER_OPTION_FOR_RENT);
        $listing_type[] = mosHtml::makeOption(2, _REALESTATE_MANAGER_OPTION_FOR_SALE);
        $listing_type_list = mosHTML :: selectList($listing_type, 'listing_type',
         'class="inputbox" size="1" style="width: 115px"', 'value', 'text', $hlisting);
        $params->def('listing_type_list', $listing_type_list);

        //listing status
        $hlistingstatus = $jinput->get('listing_status') ? $jinput->get('listing_status') : _REALESTATE_MANAGER_LABEL_ALL;
        $listing_status[] = mosHtml::makeOption(_REALESTATE_MANAGER_LABEL_ALL, _REALESTATE_MANAGER_LABEL_ALL);
        $listing_status1 = explode(',', _REALESTATE_MANAGER_OPTION_LISTING_STATUS);
        $i = 1;
        foreach ($listing_status1 as $listing_status2) {
            $listing_status[] = mosHtml::makeOption($i, $listing_status2);
            $i++;
        }
        $listing_status_list = mosHTML :: selectList($listing_status, 'listing_status', 
          'class="inputbox" size="1" style="width: 115px"', 'value', 'text', $hlistingstatus);
        $params->def('listing_status_list', $listing_status_list);

        //property type
        $hproperty = $jinput->get('property_type') ? $jinput->get('property_type') : _REALESTATE_MANAGER_LABEL_ALL;
        $property_type[] = mosHtml::makeOption(_REALESTATE_MANAGER_LABEL_ALL, _REALESTATE_MANAGER_LABEL_ALL);
        $property_type1 = explode(',', _REALESTATE_MANAGER_OPTION_PROPERTY_TYPE);
        $i = 1;
        foreach ($property_type1 as $property_type2) {
            $property_type[] = mosHtml::makeOption($i, $property_type2);
            $i++;
        }
        $property_type_list = mosHTML :: selectList($property_type, 'property_type', 'class="inputbox"
           size="1" style="width: 115px"', 'value', 'text', $hproperty);
        $params->def('property_type_list', $property_type_list);

        //categories        
        if (isset($langContent)) {
            $lang = $langContent;
            // $query = "SELECT lang_code FROM #__languages WHERE sef = '$lang'";
            // $database->setQuery($query);
            // $lang = $database->loadResult();
            $lang = " c.language = '$lang' or c.language like 'all' or c.language like '' "
             . " or c.language like '*' or c.language is null ";
        } else {
            $lang = "";
        }   
        
        $categories[] = mosHTML :: makeOption(_REALESTATE_MANAGER_LABEL_ALL, _REALESTATE_MANAGER_LABEL_ALL);
        $clist = com_house_categoryTreeList(0, '', true, $categories, $catid, $lang);

        //price
        $db = JFactory::getDBO();
        $query = "SELECT price  FROM   #__rem_houses ";
        $database->setQuery($query);
        if (version_compare(JVERSION, "3.0.0", "lt"))
            $prices = $database->loadResultArray();
        else
            $prices = $database->loadColumn();

        rsort($prices, SORT_NUMERIC);
        $max_price = $prices[0];
        $price[] = mosHTML :: makeOption(_REALESTATE_MANAGER_LABEL_FROM, _REALESTATE_MANAGER_LABEL_FROM);
        $price_to[] = mosHTML :: makeOption(_REALESTATE_MANAGER_LABEL_TO, _REALESTATE_MANAGER_LABEL_TO);
        
        $stepPrice = $max_price / 50;
        $stepPrice = (string) $stepPrice;
        $stepCount = strlen($stepPrice);
        if ($stepCount > 2) {
            $stepFinalPrice = $stepPrice[0] . $stepPrice[1];
            for ($i = 2; $i < $stepCount; $i++) {
                $stepFinalPrice .= '0';
            }
            $stepFinalPrice = (int) $stepFinalPrice;
        }
        else
            $stepFinalPrice = (int) $stepPrice;

        if($max_price == 0 || $stepFinalPrice == 0){
            $price[] = mosHTML :: makeOption(0, 0);
            $price_to[] = mosHTML :: makeOption(0, 0);
        }
        for ($i = 0; $i < $max_price; $i = $i + $stepFinalPrice) {
            $price[] = mosHTML :: makeOption($i, $i);
            $price_to[] = mosHTML :: makeOption($i, $i);
        }
        
//***************   begin show search_option    *********************
        if ($realestatemanager_configuration['search_option']['show']) {
            $params->def('search_option', 1);
            if (checkAccess_REM($realestatemanager_configuration['search_option']['registrationlevel'],
              'NORECURSE', userGID_REM($my->id), $acl)) {
                $params->def('search_option_registrationlevel', 1);
            }
        }
//**************   end show search_option     ******************************        
        
        $pricelist = mosHTML :: selectList($price, 'pricefrom2', 'class="inputbox" size="1"', 'value', 'text');
        $params->def('pricefrom2', $pricelist);
        $pricelistto = mosHTML :: selectList($price_to, 'priceto2', 'class="inputbox" size="1"', 'value', 'text');
        $params->def('priceto2', $pricelistto);

        HTML_realestatemanager::showSearchHouses($params, $currentcat, $clist, $option, $layout);
    }

    static function searchHouses($options, $catid, $option, $languagelocale, $ownername = '') {

        global $mainframe, $database, $my, $acl, $limitstart, $limit, $langContent;
        global $mosConfig_shownoauth, $mosConfig_live_site, $mosConfig_absolute_path;
        global $cur_template, $Itemid, $realestatemanager_configuration,$task, $layout;
        // print_r($_REQUEST);exit;
        PHP_realestatemanager::addTitleAndMetaTags();

        $ownernameTMP = $ownername;

        //get current user groups
        $s = getWhereUsergroupsCondition("c");
        $session = JFactory::getSession();
        if ($ownername == '') {
            $pathway = sefRelToAbs('index.php?option=' . $option . '&amp;task=show_search&amp;Itemid=' . $Itemid);
            $pathway_name = _REALESTATE_MANAGER_LABEL_SEARCH;
        }

        if (array_key_exists("searchtext", $_REQUEST)) {
            $search = protectInjectionWithoutQuote('searchtext', '');
            $search = addslashes($search);
            $session->set("poisk", $search);
        }

        $poisk_search = $session->get("poisk", "");

        $where = array();
        $Houseid = " ";
        $Description = " ";
        $Title = " ";
        $Address = " ";
        $Country = " ";
        $Region = " ";
        $City = " ";
        $Zipcode = " ";
        $Extra1 = " ";
        $Extra2 = " ";
        $Extra3 = " ";
        $Extra4 = " ";
        $Extra5 = " ";
        $Extra6 = " ";
        $Extra7 = " ";
        $Extra8 = " ";
        $Extra9 = " ";
        $Extra10 = " ";
        $Rooms = " ";
        $Bathrooms = " ";
        $Bedrooms = " ";
        $Contacts = " ";
        $Agent = " ";
        $House_size = " ";
        $Lot_size = " ";
        $Built_year = " ";
        $Rent = " ";
        $RentSQL = " ";
        $RentSQL_JOIN_1 = " ";
        $RentSQL_JOIN_2 = " ";
        $RentSQL_rent_until = " ";
        
        if (isset($_REQUEST['exactly']) && $_REQUEST['exactly'] == "on") {
            $exactly = $poisk_search;
        } else {
            $exactly = "%$poisk_search%";
        }

        
        //sorting
        $item_session = JFactory::getSession();
        $sort_arr = $item_session->get('rem_housesort', '');
        if (is_array($sort_arr)) {
            $tmp1 = protectInjectionWithoutQuote('order_direction');
            //$tmp1= $database->Quote($tmp1);
            if ($tmp1 != '')
                $sort_arr['order_direction'] = $tmp1;
            $tmp1 = protectInjectionWithoutQuote('order_field');
            if ($tmp1 != '')
                $sort_arr['order_field'] = $tmp1;
            $item_session->set('rem_housesort', $sort_arr);
        } else {
            $sort_arr = array();
            $sort_arr['order_field'] = 'htitle';
            $sort_arr['order_direction'] = 'asc';
            $item_session->set('rem_housesort', $sort_arr);
        }
        if ($sort_arr['order_field'] == "price")
            $sort_string = "CAST( " . $sort_arr['order_field'] . " AS SIGNED)" . " " . $sort_arr['order_direction'];
        else
            $sort_string = $sort_arr['order_field'] . " " . $sort_arr['order_direction'];  //end sortering

        $is_add_or = false;
        $add_or_value = "  ";

        if ($poisk_search != '') {
            if (isset($_REQUEST['Houseid']) && $_REQUEST['Houseid'] == "on") {
                $Houseid = " ";
                if ($is_add_or)
                    $Houseid = " or ";
                $is_add_or = true;
                $Houseid .= "LOWER(b.houseid) LIKE '$exactly' ";
            }
            if (isset($_REQUEST['Description']) && $_REQUEST['Description'] == "on") {
                $Description = " ";
                if ($is_add_or)
                    $Description = " or ";
                $is_add_or = true;
                $Description .=" LOWER(b.description) LIKE '$exactly' ";
            }
            if (isset($_REQUEST['Title']) && $_REQUEST['Title'] == "on") {
                $Title = " ";
                if ($is_add_or)
                    $Title = " or ";
                $is_add_or = true;
                $Title .=" LOWER(b.htitle) LIKE '$exactly' ";
            }

                if (isset($_REQUEST['Address']) && $_REQUEST['Address'] == "on") {
                $Address = " ";
                if ($is_add_or)
                    $Address = " or ";
                $is_add_or = true;
                $Address .=" LOWER(b.hlocation) LIKE '$exactly' ";
            }
            if (isset($_REQUEST['Country']) && $_REQUEST['Country'] == "on") {
                $Country = " ";
                if ($is_add_or)
                    $Country = " or ";
                $is_add_or = true;
                $Country .= "LOWER(b.hcountry) LIKE '$exactly' ";
            }
            if (isset($_REQUEST['Region']) && $_REQUEST['Region'] == "on") {
                $Region = " ";
                if ($is_add_or)
                    $Region = " or ";
                $is_add_or = true;
                $Region .= "LOWER(b.hregion) LIKE '$exactly' ";
            }
            if (isset($_REQUEST['City']) && $_REQUEST['City'] == "on") {
                $City = " ";
                if ($is_add_or)
                    $City = " or ";
                $is_add_or = true;
                $City .= "LOWER(b.hcity) LIKE '$exactly' ";
            }

                if (isset($_REQUEST['Zipcode']) && $_REQUEST['Zipcode'] == "on") {
                $Zipcode = " ";
                if ($is_add_or)
                    $Zipcode = " or ";
                $is_add_or = true;
                $Zipcode .= "LOWER(b.hzipcode) LIKE '$exactly' ";
            }
            if (isset($_REQUEST['extra1']) && $_REQUEST['extra1'] == "on") {
                $Extra1 = " ";
                if ($is_add_or)
                    $Extra1 = " or ";
                $is_add_or = true;
                $Extra1 .= "LOWER(b.extra1) LIKE '$exactly' ";
            }
            if (isset($_REQUEST['extra2']) && $_REQUEST['extra2'] == "on") {
                $Extra2 = " ";
                if ($is_add_or)
                    $Extra2 = " or ";
                $is_add_or = true;
                $Extra2 .= "LOWER(b.extra2) LIKE '$exactly' ";
            }
            if (isset($_REQUEST['extra3']) && $_REQUEST['extra3'] == "on") {
                $Extra3 = " ";
                if ($is_add_or)
                    $Extra3 = " or ";
                $is_add_or = true;
                $Extra3 .= "LOWER(b.extra3) LIKE '$exactly' ";
            }
            if (isset($_REQUEST['extra4']) && $_REQUEST['extra4'] == "on") {
                $Extra4 = " ";
                if ($is_add_or)
                    $Extra4 = " or ";
                $is_add_or = true;
                $Extra4 .= "LOWER(b.extra4) LIKE '$exactly' ";
            }
            if (isset($_REQUEST['extra5']) && $_REQUEST['extra5'] == "on") {
                $Extra5 = " ";
                if ($is_add_or)
                    $Extra5 = " or ";
                $is_add_or = true;
                $Extra5 .= "LOWER(b.extra5) LIKE '$exactly' ";
            }

            if (isset($_REQUEST['rooms']) && $_REQUEST['rooms'] == "on") {
                $Rooms = " ";
                if ($is_add_or)
                    $Rooms = " or ";
                $is_add_or = true;
                $Rooms .= "LOWER(b.Rooms) LIKE '$exactly' ";
            }

            if (isset($_REQUEST['Bathrooms']) && $_REQUEST['Bathrooms'] == "on") {
                $Bathrooms = " ";
                if ($is_add_or)
                    $Bathrooms = " or ";
                $is_add_or = true;
                $Bathrooms .= "LOWER(b.bathrooms) LIKE '$exactly' ";
            }
            if (isset($_REQUEST['Bedrooms']) && $_REQUEST['Bedrooms'] == "on") {
                $Bedrooms = " ";
                if ($is_add_or)
                    $Bedrooms = " or ";
                $is_add_or = true;
                $Bedrooms .= "LOWER(b.bedrooms) LIKE '$exactly' ";
            }

            if (isset($_REQUEST['Contacts']) && $_REQUEST['Contacts'] == "on") {
                $Contacts = " ";
                if ($is_add_or)
                    $Contacts = " or ";
                $is_add_or = true;
                $Contacts .=" LOWER(b.contacts) LIKE '$exactly' ";
            }
            if (isset($_REQUEST['Agent']) && $_REQUEST['Agent'] == "on") {
                $Agent = " ";
                if ($is_add_or)
                    $Agent = " or ";
                $is_add_or = true;
                $Agent .=" LOWER(b.agent) LIKE '$exactly' ";
            }
            if (isset($_REQUEST['house_size']) && $_REQUEST['house_size'] = "on") {
                $House_size = " ";
                if ($is_add_or)
                    $House_size = " or ";
                $is_add_or = true;
                $House_size .=" LOWER(b.house_size) LIKE '$exactly' ";
            }
            if (isset($_REQUEST['Lot_size']) && $_REQUEST['Lot_size'] = "on") {
                $Lot_size = " ";
                if ($is_add_or)
                    $Lot_size = " or ";
                $is_add_or = true;
                $Lot_size .=" LOWER(b.lot_size) LIKE '$exactly' ";
            }
            if (isset($_REQUEST['year']) && $_REQUEST['year'] = "on") {
                $House_size = " ";
                if ($is_add_or)
                    $House_size = " or ";
                $is_add_or = true;
                $House_size .=" LOWER(b.year) LIKE '$exactly' ";
            }

            if (isset($_REQUEST['Garages']) && $_REQUEST['Garages'] = "on") {
                $Garages = " ";
                if ($is_add_or)
                    $Garages = " or ";
                $is_add_or = true;
                $Garages .=" LOWER(b.garages) LIKE '$exactly' ";
            }
         }

        $listing_type = protectInjectionWithoutQuote('listing_type', '');
        $listing_status = protectInjectionWithoutQuote('listing_status', '');
        $property_type = protectInjectionWithoutQuote('property_type', '');
        $extra6 = protectInjectionWithoutQuote('extra6', '');
        $extra7 = protectInjectionWithoutQuote('extra7', '');
        $extra8 = protectInjectionWithoutQuote('extra8', '');
        $extra9 = protectInjectionWithoutQuote('extra9', '');
        $extra10 = protectInjectionWithoutQuote('extra10', '');
        if ($listing_type != _REALESTATE_MANAGER_LABEL_ALL && $listing_type != '') {
            $where[] = " LOWER(b.listing_type)='$listing_type'";
        }
        if ($listing_status != _REALESTATE_MANAGER_LABEL_ALL && $listing_status != '') {
            $where[] = " LOWER(b.listing_status)='$listing_status'";
        }
        if ($property_type != _REALESTATE_MANAGER_LABEL_ALL && $property_type != '') {
            $where[] = " LOWER(b.property_type)='$property_type'";
        }
        if ($extra6 != _REALESTATE_MANAGER_LABEL_ALL && $extra6 != '') {
            $where[] = " LOWER(b.extra6)='$extra6'";
        }
        if ($extra7 != _REALESTATE_MANAGER_LABEL_ALL && $extra7 != '') {
            $where[] = " LOWER(b.extra7)='$extra7'";
        }
        if ($extra8 != _REALESTATE_MANAGER_LABEL_ALL && $extra8 != '') {
            $where[] = " LOWER(b.extra8)='$extra8'";
        }
        if ($extra9 != _REALESTATE_MANAGER_LABEL_ALL && $extra9 != '') {
            $where[] = " LOWER(b.extra9)='$extra9'";
        }
        if ($extra10 != _REALESTATE_MANAGER_LABEL_ALL && $extra10 != '') {
            $where[] = " LOWER(b.extra10)='$extra10'";
        }
        $pricefrom = intval(protectInjectionWithoutQuote('pricefrom2', ''));
        $priceto = intval(protectInjectionWithoutQuote('priceto2', ''));
        if ($pricefrom > 0)
            $where[] = " CAST( b.price AS SIGNED) >= $pricefrom ";
        if ($priceto > 0)
            $where[] = " CAST( b.price AS SIGNED) <= $priceto ";

        if (isset($_REQUEST['ownername']) && $_REQUEST['ownername'] == "on")
            $ownername = "$exactly";

        if ($ownername != '' && $ownername != '%%'
          && !( $ownername == 'Guest' || $ownername == 'anonymous' || $ownername == _REALESTATE_MANAGER_LABEL_ANONYMOUS  )  ) {
            $query = "SELECT u.id FROM #__users AS u WHERE LOWER(u.id) LIKE '$ownername' OR LOWER(u.name) LIKE '$ownername';";
            $database->setQuery($query);
            if (version_compare(JVERSION, "3.0.0", "lt"))
                $owner_ids = $database->loadResultArray();
            else
                $owner_ids = $database->loadColumn();

            $ownername = "";
            if (count($owner_ids)) {
                foreach ($owner_ids as $owner_id) {
                    if (isset($_REQUEST['ownername']) && $_REQUEST['ownername'] == "on") {
                        //search from frontend
                        if ($is_add_or)
                            $ownername .= " or ";
                        $is_add_or = true;
                        $ownername .= "b.owner_id='$owner_id'";
                    } else {
                        //show owner houses
                        $where[] = "b.owner_id='$owner_id'";
                    }
                }
            } else if (!$is_add_or) { 
                echo"<h1 style='text-align:center'>" . _REALESTATE_MANAGER_LABEL_SEARCH_NOTHING_FOUND . "</h1>";
                return;
            }
        } else if($ownername == 'Guest' || $ownername == 'anonymous' || $ownername == _REALESTATE_MANAGER_LABEL_ANONYMOUS ){
            if (isset($_REQUEST['ownername']) && $_REQUEST['ownername'] == "on") {
                //search from frontend
                if ($is_add_or)
                    $ownername .= " or ";
                $is_add_or = true;
                $ownername .= "b.owner_id=''";
            } else {
                //show owner houses
                $where[] = "b.owner_id=''";
            }
        }
        
        $search_date_from = protectInjectionWithoutQuote('search_date_from', '');
        $search_date_from = addslashes(data_transform_rem($search_date_from, 'to'));
        $search_date_until = protectInjectionWithoutQuote('search_date_until', '');
        $search_date_until = addslashes(data_transform_rem($search_date_until, 'to'));

        if($realestatemanager_configuration['special_price']['show'])
        {
            $sign = '=';      
        }else{
            $sign = '';
        }        

        if (isset($_REQUEST['search_date_from']) && (trim($_REQUEST['search_date_from']) ) &&
               trim($_REQUEST['search_date_until']) == "") {
            $RentSQL = "((fk_rentid = 0 OR b.id NOT IN (select dd.fk_houseid " .
             " from #__rem_rent AS dd where dd.rent_until >".$sign." ' " . $search_date_from .
             "' and dd.rent_from <= '" . $search_date_from . 
             "' and dd.fk_houseid=b.id and dd.rent_return is null)) AND (listing_type = \"1\"))";

            // print_r($RentSQL);
            // exit;

            if ($is_add_or)
                $RentSQL .= " AND ";
            $RentSQL_JOIN_1 = "\nLEFT JOIN #__rem_rent AS d ";
            $RentSQL_JOIN_2 = "\nON d.fk_houseid=b.id ";
        }


        if (isset($_REQUEST['search_date_until']) && (trim($_REQUEST['search_date_until']) )
         && trim($_REQUEST['search_date_from']) == "") {
            $RentSQL = "((fk_rentid = 0 OR b.id NOT IN (select dd.fk_houseid "
             . "from #__rem_rent AS dd where dd.rent_from <".$sign." '" . $search_date_until . "' and dd.rent_until >= '"
             . $search_date_until . "' and dd.fk_houseid=b.id and dd.rent_return is null)) AND (listing_type = \"1\"))";
            if ($is_add_or)
                $RentSQL .= " AND ";
            $RentSQL_JOIN_1 = "\nLEFT JOIN #__rem_rent AS d ";
            $RentSQL_JOIN_2 = "\nON d.fk_houseid=b.id ";
        }



        if (isset($_REQUEST['search_date_until']) && (trim($_REQUEST['search_date_until']))
                && isset($_REQUEST['search_date_from']) && ( trim($_REQUEST['search_date_from']))) {
            $RentSQL = "((fk_rentid = 0 OR b.id NOT IN (select dd.fk_houseid from #__rem_rent AS dd
            where (dd.rent_until >".$sign." '" . $search_date_from . "' and dd.rent_from <".$sign." '" . $search_date_from . "') or " .
                    " (dd.rent_from <".$sign." ' " . $search_date_until . "' and dd.rent_until >".$sign." '" . $search_date_until . "' ) or " .
                    " (dd.rent_from >= '" . $search_date_from . "' and dd.rent_until <= '" . $search_date_until . "')  and dd.rent_return is null ) ) " .
                    " AND (listing_type = \"1\"))";
            if ($is_add_or)
                $RentSQL .= " AND ";
            $RentSQL_JOIN_1 = "\nLEFT JOIN #__rem_rent AS d ";
            $RentSQL_JOIN_2 = "\nON d.fk_houseid=b.id ";
        }
     
        $RentSQL = $RentSQL . (($is_add_or) ? ( "( ( " . $Houseid . "  " . $Description .
            "  " . $Title . "  " . $Address .
            "  " . $Country . "  " . $Region . "  " . $City . "  " . $Zipcode . "  " . $Extra1 .
            "  " . $Extra2 . "  " . $Extra3 . "  " . $Extra4 . "  " . $Extra5 . "  " . $Rooms .
            "  " . $Bathrooms . "  " . $Bedrooms . "  " . $Contacts . "  " . $Agent .
            "  " . $House_size . " " . $Lot_size . " " . $Built_year . "  " . $ownername . "  ))") : (" "));

        if (trim($RentSQL) != "")
            array_push($where, $RentSQL);
        //select category, to which user has access
        $where[] = " ($s) ";
        $where[] = " c.published = '1' ";

        //select published and approved houses
        array_push($where, " b.published = '1' ");
        array_push($where, " b.approved = '1' ");

        if (isset($langContent)) {

            $lang = $langContent;
            // $query = "SELECT lang_code FROM #__languages WHERE sef = '$lang'";
            // $database->setQuery($query);
            // $lang = $database->loadResult();
            $where[] = " ( b.language = '$lang' or b.language like 'all' or b.language like '' "
              ." or b.language like '*' or b.language is null) ";
            $where[] = "  ( c.language = '$lang' or c.language like 'all' or c.language like '' "
              ." or c.language like '*' or c.language is null) ";
        }

        if ($catid)
        array_push($where, "c.id=" . intval($catid) . "");

        $query = "SELECT COUNT(DISTINCT b.id)
                    FROM #__rem_houses AS b
                    LEFT JOIN #__rem_categories AS hc ON b.id=hc.iditem
                    LEFT JOIN #__rem_main_categories AS c ON hc.idcat = c.id " .
                    $RentSQL_JOIN_1 . $RentSQL_JOIN_2 .
                    ((count($where) ? "\n WHERE " . implode(' AND ', $where) : ""));
        $database->setQuery($query);
        $total = $database->loadResult();
        $pageNav = new JPagination($total, $limitstart, $limit); // for J 1.6
        // getting all houses for this category
        $query = "SELECT distinct hc.idcat as idcat, b . * , c.title AS category_titel, c.ordering AS category_ordering, c.id as catid
                    FROM #__rem_houses AS b
                    LEFT JOIN #__rem_categories AS hc ON b.id=hc.iditem
                    LEFT JOIN #__rem_main_categories AS c ON hc.idcat = c.id " .
                    $RentSQL_JOIN_1 . $RentSQL_JOIN_2 .
                    ((count($where) ? "\n WHERE " . implode(' AND ', $where) : "")) .
                    " GROUP BY b.id ORDER BY $sort_string
                    \nLIMIT " . $pageNav->limitstart . "," . $pageNav->limit;
        $database->setQuery($query);
        $houses = $database->loadObjectList();
//             print_r($_REQUEST);
//             echo  "11111111221111$is_add_or:".$task;
//             print_r($database);
//             exit;
            
        $currentcat = new stdClass();

        //parameters
        if (version_compare(JVERSION, '3.0', 'ge')) {
            $menu = new JTableMenu($database);
            $menu->load($Itemid);
            $params = new JRegistry;
            $params->loadString($menu->params);
        } else {
            $menu = new mosMenu($database);
            $menu->load($Itemid);
            $params = new mosParameters($menu->params);
        }

        $menu_name = set_header_name_rem($menu, $Itemid);
        $params->def('header', $menu_name);
        $params->def('pageclass_sfx', '');
        $params->def('category_name', _REALESTATE_MANAGER_LABEL_SEARCH);
        $params->def('search_request', '1');
        $params->def('hits', 1);
        $params->def('show_rating', 1);
        $params->def('sort_arr_order_direction', $sort_arr['order_direction']);
        $params->def('sort_arr_order_field', $sort_arr['order_field']);
        $database->setQuery("SELECT id FROM #__menu WHERE link='index.php?option=com_realestatemanager'");
        if ($database->loadResult() != $Itemid)
            $params->def('wrongitemid', '1');

        if ($realestatemanager_configuration['rentstatus']['show']) {
            $params->def('show_rentstatus', 1);
            if (checkAccess_REM($realestatemanager_configuration['rentrequest']['registrationlevel'],
             'NORECURSE', userGID_REM($my->id), $acl)) {
                $params->def('show_rentrequest', 1);
            }
        }
        if ($realestatemanager_configuration['buystatus']['show']) {
            $params->def('show_buystatus', 1);
            if (checkAccess_REM($realestatemanager_configuration['buyrequest']['registrationlevel'],
             'NORECURSE', userGID_REM($my->id), $acl)) {
                $params->def('show_buyrequest', 1);
            }
        }
   
     
        if ($realestatemanager_configuration['price']['show']) {
            $params->def('show_pricestatus', 1);
            if (checkAccess_REM($realestatemanager_configuration['price']['registrationlevel'],
             'NORECURSE', userGID_REM($my->id), $acl)) {
                $params->def('show_pricerequest', 1);
            }
        }

        if ($realestatemanager_configuration['cat_pic']['show'])
            $params->def('show_cat_pic', 1);
        $params->def('back_button', $mainframe->getCfg('back_button'));
        $currentcat->descrip = " ";
        $currentcat->align = 'right';

        //page image
        //$currentcat->img = "./components/com_realestatemanager/images/rem_logo.png";
        $currentcat->img = null;

        //$currentcat->header = $params->get( 'header' );
        //$currentcat->header = $currentcat->header .":". _REALESTATE_MANAGER_LABEL_SEARCH;
        //used to show table rows in alternating colours
        $tabclass = array('sectiontableentry1', 'sectiontableentry2');

        if ($realestatemanager_configuration['search_option']['show']) {
            $params->def('search_option', 1);
            if (checkAccess_REM($realestatemanager_configuration['search_option']['registrationlevel'],
             'NORECURSE', userGID_REM($my->id), $acl)) {
                $params->def('search_option_registrationlevel', 1);
            }
        }
        
        // show map for layout search_result list
        if (($realestatemanager_configuration['searchlayout_map']['show'])) {
            if (checkAccess_REM($realestatemanager_configuration['searchlayout_map']['registrationlevel'],
             'RECURSE', userGID_REM($my->id), $acl)) {
                $params->def('show_searchlayout_map', 1);
            }
        }
        // show order by form for layout search_result list
        if (($realestatemanager_configuration['searchlayout_orderby']['show'])) {
            if (checkAccess_REM($realestatemanager_configuration['searchlayout_orderby']['registrationlevel'],
             'RECURSE', userGID_REM($my->id), $acl)) {
                $params->def('show_searchlayout_orderby', 1);
            }
        }
       
        if (isset($_REQUEST['searchLayout'])){
          $layout = $_REQUEST['searchLayout'];
        } else {
          $layout = '';
        }
        if (isset($_REQUEST['typeLayout'])){
          $type = $_REQUEST['typeLayout'];
        } else {
          $type = '';
        }

        if (count($houses)) {
            if (  $task == 'my_houses' || $task == 'show_my_houses' || $task == 'showmyhouses'  ) PHP_realestatemanager::showTabs();
            if ($task == 'search') {
                $layout = $params->get('searchresultlayout');
                $layoutsearch = $params->get('showsearchhouselayout');
                if (empty($layout)) $layout = 'default';

                HTML_realestatemanager::displaySearchHouses($houses, $currentcat, $params, $tabclass, $catid, null, 
                $pageNav, false, $option, $layout, $layoutsearch);
            } else {
                HTML_realestatemanager::displayHouses($houses, $currentcat, $params, $tabclass, $catid, null, 
                $pageNav, false, $option, $layout, $type);
            }
        } else {
          if ( $task == 'my_houses'
            || $task == 'show_my_houses' || $task == 'showmyhouses'  )
              PHP_realestatemanager::showTabs();

              positions_rem($params->get('notfound01'));
              $layoutsearch = $params->get('showsearchhouselayout', 'default');
              PHP_realestatemanager::showSearchHouses($option, $catid, $option, $layoutsearch);

              print_r("<h1 style='text-align:center'>" . _REALESTATE_MANAGER_LABEL_SEARCH_NOTHING_FOUND .
               " </h1><br><br><div class='row-fluid'><div class='span9'></div></div>");
             positions_rem($params->get('notfound02'));

            // "<div class='span3'><div class='rem_house_contacts'>
            // <div id='rem_house_titlebox'>" . _REALESTATE_MANAGER_SHOW_SEARCH . "</div> "
             // PHP_realestatemanager::showSearchHouses($option, $catid, $option, $layout);
             // print_r('</div></div></div>');
        }
    }
    
static function ajax_rent_calcualete($bid,$rent_from,$rent_until){ 
    
    
    global $realestatemanager_configuration;
    
    $database = JFactory::getDBO();    
    
    $resulArr = calculatePriceREM ($bid,$rent_from,$rent_until,$realestatemanager_configuration,$database);
    
    echo $resulArr[0].' '.$resulArr[1];
    exit; 
   }

    function checkAccess_REM($accessgroupid, $recurse, $usersgroupid, $acl) {
        $usersgroupid = explode(',', $usersgroupid);

        //parse usergroups
        $tempArr = array();
        $tempArr = explode(',', $accessgroupid);

        for ($i = 0; $i < count($tempArr); $i++) {
            if (($tempArr[$i] == $usersgroupid OR in_array($tempArr[$i], $usersgroupid)) || $tempArr[$i] == -2) {
                //allow access
                return true;
            } else {
                if ($recurse == 'RECURSE') {
                    if (is_array($usersgroupid)) {
                        for ($j = 0; $j < count($usersgroupid); $j++) {
                            if (in_array($usersgroupid[$j], $tempArr))
                                return 1;
                        }
                    } else {
                        if (in_array($usersgroupid, $tempArr))
                            return 1;
                    }
                }
            }
        } // end for
        //deny access
        return 0;
    }

     static function showTabs() {
        global $mosConfig_live_site, $realestatemanager_configuration, $database, $Itemid, $my, $option;
        $acl = JFactory::getACL();
        $doc = JFactory::getDocument();
        $doc->addStyleSheet($mosConfig_live_site . '/components/com_realestatemanager/includes/realestatemanager.css');

        $menu = new mosMenu($database);
        $menu->load($Itemid);
        $params = new mosParameters($menu->params);


         if ($option == "com_comprofiler") {
           return;
         }

        $userid = $my->id;
        $query = "SELECT u.id, u.name AS username FROM #__users AS u WHERE u.id = " . $userid;
        $database->setQuery($query);
        $ownerslist = $database->loadObjectList();
        foreach ($ownerslist as $owner) {
            $username = $owner->username;
        }

        $query = "SELECT h.owner_id FROM #__rem_houses AS h" .
                " INNER JOIN #__rem_rent_request AS r ON h.id=r.fk_houseid " .
                " WHERE h.owner_id = '" . $my->id . "' AND r.status=0";
        $database->setQuery($query);
        $ownerrenthouse = $database->loadObjectList();
        foreach ($ownerrenthouse as $owner) {
            $rent_owner_id = $owner->owner_id;
            break;
        }

        $query = "SELECT h.owner_id  FROM #__rem_houses AS h" .
                " INNER JOIN  #__rem_buying_request AS br ON h.id=br.fk_houseid" .
                " WHERE h.owner_id = '" . $my->id . "'";
        $database->setQuery($query);
        $ownerbuyhouse = $database->loadObjectList();
        foreach ($ownerbuyhouse as $owner) {
            $buy_owner_id = $owner->owner_id;
            break;
        }

        $query = "SELECT * FROM #__rem_rent AS r WHERE r.fk_userid = " . $my->id;
        $database->setQuery($query);
        $current_user_rent_history_array = $database->loadObjectList();
        $check_for_show_rent_history = 0;
        if (isset($current_user_rent_history_array)) {
            foreach ($current_user_rent_history_array as $temp) {
                if ($temp->fk_userid == $my->id)
                    $check_for_show_rent_history = 1;
            }
        }

        if ($realestatemanager_configuration['cb_edit']['show']) {
            $params->def('show_edit', 1);
            $i = checkAccess_REM($realestatemanager_configuration['cb_edit']['registrationlevel'],
             'NORECURSE', userGID_REM($my->id), $acl);
            if ($i)
                $params->def('show_edit_registrationlevel', 1);
        }

        if (isset($rent_owner_id) && $my->id == $rent_owner_id) {
            if (($realestatemanager_configuration['cb_rent']['show'])) {
                $params->def('show_rent', 1);
                $i = checkAccess_REM($realestatemanager_configuration['cb_rent']['registrationlevel'],
                 'NORECURSE', userGID_REM($my->id), $acl);
                if ($i)
                    $params->def('show_rent_registrationlevel', 1);
            }
        }

        if (isset($buy_owner_id) && $my->id == $buy_owner_id) {
            if (($realestatemanager_configuration['cb_buy']['show'])) {
                $params->def('show_buy', 1);
                $i = checkAccess_REM($realestatemanager_configuration['cb_buy']['registrationlevel'],
                 'NORECURSE', userGID_REM($my->id), $acl);
                if ($i)
                    $params->def('show_buy_registrationlevel', 1);
            }
        }

        if ($check_for_show_rent_history != 0) {
            if (($realestatemanager_configuration['cb_history']['show'])) {
                $params->def('show_history', 1);
                $i = checkAccess_REM($realestatemanager_configuration['cb_history']['registrationlevel'],
                 'NORECURSE', userGID_REM($my->id), $acl);
                if ($i)
                    $params->def('show_history_registrationlevel', 1);
            }
        }

        HTML_realestatemanager::showTabs($params, $userid, $username, $comprofiler, $option);
    }

    static function editMyHouses($option) {
        global $database, $Itemid, $mainframe, $my, $realestatemanager_configuration, $acl;

        PHP_realestatemanager::addTitleAndMetaTags();

        $is_edit_all_houses = false ;
        if (checkAccess_REM($realestatemanager_configuration['option_edit']['registrationlevel'], 'RECURSE', userGID_REM($my->id), $acl)) {
            $is_edit_all_houses = true ;
        }

        $menu = new JTableMenu($database);

        $menu->load($Itemid);
        $params = new mosParameters($menu->params);
   
        $limit = $realestatemanager_configuration['page']['items'];
        $limitstart = protectInjectionWithoutQuote('limitstart', 0);

        $menu_name = set_header_name_rem($menu, $Itemid);
        $params->def('header', $menu_name);

        //check user
        if ($my->email == null) {
            mosRedirect("index.php", "Please login");
            exit;
        }

        if( !$is_edit_all_houses  ) $who_edit = " owner_id='$my->id' ";
        else $who_edit = " ";

        $database->setQuery("SELECT COUNT(id) FROM `#__rem_houses` " . 
            ($who_edit == " " ? "" : " WHERE $who_edit"));
        $total = $database->loadResult();
        $pageNav = new JPagination($total, $limitstart, $limit); // for J 1.6
        
        //getting my cars
        $selectstring = "SELECT a.*, GROUP_CONCAT(cc.title SEPARATOR ', ') AS category,
		l.id as rentid, l.rent_from as rent_from, l.rent_return as rent_return,
		l.rent_until as rent_until, u.name AS editor" .
                "\nFROM #__rem_houses AS a" .
                "\nLEFT JOIN #__rem_categories AS hc ON hc.iditem = a.id" .
                "\nLEFT JOIN #__categories AS cc ON cc.id = hc.idcat" .
                "\nLEFT JOIN #__rem_rent AS l ON l.fk_houseid = a.id  and l.rent_return is null " .
                "\nLEFT JOIN #__users AS u ON u.id = a.checked_out" .
                ($who_edit == " " ? "" : " WHERE $who_edit") .
                // "\nWHERE owner_id='" . $my->id . "' " .
                "\nGROUP BY a.id" .
                "\nORDER BY a.htitle " .
                "\nLIMIT " . $pageNav->limitstart . "," . $pageNav->limit . ";";
        $database->setQuery($selectstring);
        $houses = $database->loadObjectList();

        $rows = $database->loadObjectList();
        $date = date(time());
        foreach ($houses as $row) {
            $check = strtotime($row->checked_out_time);
            $remain = 7200 - ($date - $check);
            if (($remain <= 0) && ($row->checked_out != 0)) {
                $database->setQuery("UPDATE #__rem_houses SET checked_out=0,checked_out_time=0");
                $database->query();
            }
        }
        HTML_realestatemanager::showMyHouses($houses, $params, $pageNav, $option);
    }

    static function ShowAllHouses($layout = "default", $printItem) {
        global $mainframe, $database, $acl, $my, $langContent;
        global $mosConfig_shownoauth, $mosConfig_live_site, $mosConfig_absolute_path;
        global $cur_template, $Itemid, $realestatemanager_configuration,
         $mosConfig_list_limit, $limit, $total, $limitstart;

        PHP_realestatemanager::addTitleAndMetaTags();

        if (isset($langContent)) {

            $lang = $langContent;
            // $query = "SELECT lang_code FROM #__languages WHERE sef = '$lang'";
            // $database->setQuery($query);
            // $lang = $database->loadResult();
            $lang = " and ( h.language = '$lang' or h.language like 'all' or "
              ." h.language like '' or h.language like '*' or h.language is null) "
              . " AND ( c.language = '$lang' or c.language like 'all' or "
              ." c.language like '' or c.language like '*' or c.language is null) ";
        } else {
            $lang = "";
        }

        //sorting
        $item_session = JFactory::getSession();
        $sort_arr = $item_session->get('rem_housesort', '');
        if (is_array($sort_arr)) {
            $tmp1 = protectInjectionWithoutQuote('order_direction');
            if ($tmp1 != '')
                $sort_arr['order_direction'] = $tmp1;
            $tmp1 = protectInjectionWithoutQuote('order_field');
            if ($tmp1 != '')
                $sort_arr['order_field'] = $tmp1;
            $item_session->set('rem_housesort', $sort_arr);
        } else {
            $sort_arr = array();
            $sort_arr['order_field'] = 'htitle';
            $sort_arr['order_direction'] = 'asc';
            $item_session->set('rem_housesort', $sort_arr);
        }
        if ($sort_arr['order_field'] == "price")
            $sort_string = "CAST( " . $sort_arr['order_field'] . " AS SIGNED)" . " " .
             $sort_arr['order_direction'];
        else
            $sort_string = $sort_arr['order_field'] . " " . $sort_arr['order_direction'];

        //getting groups of user
        $s = getWhereUsergroupsCondition("c");

        $query = "SELECT COUNT(DISTINCT h.id)
            \nFROM #__rem_houses AS h"
                . "\nLEFT JOIN #__rem_categories AS hc ON hc.iditem=h.id"
                . "\nLEFT JOIN #__rem_main_categories AS c ON c.id=hc.idcat"
                . "\nWHERE h.published='1' AND h.approved='1' AND c.published='1'  $lang
              AND ($s)";

        $database->setQuery($query);
        $total = $database->loadResult();

        $pageNav = new JPagination($total, $limitstart, $limit);

        // getting all items for this category
        $query = "SELECT h.*,hc.idcat AS catid,hc.idcat AS idcat, c.title as category_titel
       \nFROM #__rem_houses AS h "
                . "\nLEFT JOIN #__rem_categories AS hc ON hc.iditem=h.id "
                . "\nLEFT JOIN #__rem_main_categories AS c ON c.id=hc.idcat "
                . "\nWHERE h.published='1' AND h.approved='1'  "
                . "\nAND c.published='1' $lang AND ($s) "
                . "\nGROUP BY h.id "
                . "\nORDER BY " . $sort_string
                . "\nLIMIT $pageNav->limitstart,$pageNav->limit;";
        $database->setQuery($query);

        $houses = $database->loadObjectList();

        $query = "SELECT h.*,c.id, c.parent_id, c.title, c.published, c.image,COUNT(hc.iditem) as houses, '1' as display" .
                " \n FROM  #__rem_main_categories as c
              \n LEFT JOIN #__rem_categories AS hc ON hc.idcat=c.id
              \n LEFT JOIN #__rem_houses AS h ON h.id=hc.iditem
              \n WHERE c.section='com_realestatemanager' 
              AND c.published=1 AND ({$s}) $lang 
              \n GROUP BY c.id
              \n ORDER BY c.parent_id DESC, c.ordering ";

        $database->setQuery($query);
        $cat_all = $database->loadObjectList();

        foreach ($cat_all as $k1 => $cat_item1) {
            if (PHP_realestatemanager::is_exist_curr_and_subcategory_houses($cat_all[$k1]->id)) {
                foreach ($cat_all as $cat_item2) {
                    if ($cat_item1->id == $cat_item2->parent_id) {
                        $cat_all[$k1]->houses += $cat_item2->houses;
                    }
                }
            } else
                $cat_all[$k1]->display = 0;
        }

        if (version_compare(JVERSION, '3.0', 'ge')) {
            $menu = new JTableMenu($database);
            $menu->load($Itemid);
            $params = new JRegistry;
            $params->loadString($menu->params);
        } else {
            $menu = new mosMenu($database);
            $menu->load($Itemid);
            $params = new mosParameters($menu->params);
        }

        $menu_name = set_header_name_rem($menu, $Itemid);
        $params->def('header', $menu_name);
        $params->def('pageclass_sfx', '');
        //$params->set('category_name', $category->title);
        $params->def('show_category', '1');

        if (($realestatemanager_configuration['rentstatus']['show'])) {
            if (checkAccess_REM($realestatemanager_configuration['rentrequest']['registrationlevel'],
             'RECURSE', userGID_REM($my->id), $acl)) {
                $params->def('show_rentstatus', 1);
                $params->def('show_rentrequest', 1);
            }
        }

        if ($realestatemanager_configuration['reviews']['show']) {
            $params->def('show_reviews', 1);
            if (checkAccess_REM($realestatemanager_configuration['reviews']['registrationlevel'],
             'NORECURSE', userGID_REM($my->id), $acl)) {
                $params->def('show_inputreviews', 1);
            }
        }
//***************   begin show search_option    *********************
        if ($realestatemanager_configuration['search_option']['show']) {
            $params->def('search_option', 1);
            if (checkAccess_REM($realestatemanager_configuration['search_option']['registrationlevel'],
             'NORECURSE', userGID_REM($my->id), $acl)) {
                $params->def('search_option_registrationlevel', 1);
            }
        }
//**************   end show search_option     ******************************
        $params->def('sort_arr_order_direction', $sort_arr['order_direction']);
        $params->def('sort_arr_order_field', $sort_arr['order_field']);

                //add for show in category picture
        if ($realestatemanager_configuration['cat_pic']['show'])
            $params->def('show_cat_pic', 1);

        $params->def('search_request', 1);
        $params->def('show_rating', 1);
        $params->def('hits', 1);
        $params->def('back_button', $mainframe->getCfg('back_button'));

        // used to show table rows in alternating colours
        $tabclass = array('sectiontableentry1', 'sectiontableentry2');

        $params->def('minifotohigh', $realestatemanager_configuration['foto']['high']);
        $params->def('minifotowidth', $realestatemanager_configuration['foto']['width']);

        // price
        if ($realestatemanager_configuration['price']['show']) {
            $params->def('show_pricestatus', 1);
            if (checkAccess_REM($realestatemanager_configuration['price']['registrationlevel'],
             'NORECURSE', userGID_REM($my->id), $acl)) {
                $params->def('show_pricerequest', 1);
            }
        }

        HTML_realestatemanager::displayAllHouses($houses, $params, $tabclass, $pageNav, $layout);
    }
    
    
    //this function check - is exist folders under this category 
    static function is_exist_subcategory_houses($catid)
    {
        global $database, $my;

        $s = getWhereUsergroupsCondition("cc");

        $query = "SELECT *, COUNT(a.id) AS numlinks FROM #__rem_main_categories AS cc"
                . "\n  JOIN #__rem_categories AS hc ON hc.idcat = cc.id"
                . "\n  JOIN #__rem_houses AS a ON a.id = hc.iditem"
                . "\n WHERE a.published='1' AND a.approved='1' AND section='com_realestatemanager' "
                . " AND cc.parent_id='" . intval($catid) . "' AND cc.published='1' AND ($s) "
                . "\n GROUP BY cc.id"
                . "\n ORDER BY cc.ordering";
        $database->setQuery($query);

        $categories = $database->loadObjectList();
        if (count($categories) != 0)
            return true;

        $query = "SELECT id "
                . "FROM #__rem_main_categories AS cc "
                . " WHERE section='com_realestatemanager' AND parent_id='" .
                 intval($catid) . "' AND published='1' AND ($s) ";
        $database->setQuery($query);
        $categories = $database->loadObjectList();

        if (count($categories) == 0)
            return false;

        foreach ($categories as $k) {
            if (PHP_realestatemanager::is_exist_subcategory_houses($k->id))
                return true;
        }
        return false;
    }

    static function is_exist_curr_and_subcategory_houses($catid) {
        global $database, $my;

        $s = getWhereUsergroupsCondition("cc");

        $query = "SELECT *, COUNT(a.id) AS numlinks FROM #__rem_main_categories AS cc"
                . "\n  JOIN #__rem_categories AS hc ON hc.idcat = cc.id"
                . "\n  JOIN #__rem_houses AS a ON a.id = hc.iditem"
                . "\n WHERE a.published='1' AND a.approved='1' AND section='com_realestatemanager' "
                . " AND cc.id='" . intval($catid) . "' AND cc.published='1' AND ($s) "
                . "\n GROUP BY cc.id"
                . "\n ORDER BY cc.ordering";
        $database->setQuery($query);

        $categories = $database->loadObjectList();
        if (count($categories) != 0)
            return true;

        $query = "SELECT id "
                . "FROM #__rem_main_categories AS cc "
                . " WHERE section='com_realestatemanager' AND parent_id='" .
                 intval($catid) . "' AND published='1' AND ($s) ";
        $database->setQuery($query);
        $categories = $database->loadObjectList();

        if (count($categories) == 0)
            return false;

        foreach ($categories as $k) {
            if (PHP_realestatemanager::is_exist_curr_and_subcategory_houses($k->id))
                return true;
        }
        return false;
    }

    static function listCategories($catid, $layout, $languagelocale) {
        global $mainframe, $database, $my, $acl, $langContent;
        global $mosConfig_shownoauth, $mosConfig_live_site, $mosConfig_absolute_path;
        global $cur_template, $Itemid, $realestatemanager_configuration;

        PHP_realestatemanager::addTitleAndMetaTags();

        $s = getWhereUsergroupsCondition("c");



        if (isset($langContent)) {

            $lang = $langContent;
            // $query = "SELECT lang_code FROM #__languages WHERE sef = '$lang'";
            // $database->setQuery($query);
            // $lang = $database->loadResult();
            $lang = " and ( h.language = '$lang' or h.language like 'all' or "
              ." h.language like '' or h.language like '*' or h.language is null) "
              . " AND ( c.language = '$lang' or c.language like 'all' or " 
              . " c.language like '' or c.language like '*' or c.language is null) ";
        } else {
            $lang = "";
        }

         $query = "SELECT h.*,c.id, c.parent_id, c.title, c.image,COUNT(hc.iditem) as houses, '1' as display" .
                "\n FROM  #__rem_main_categories as c " .
                "\n LEFT JOIN #__rem_categories AS hc ON hc.idcat=c.id " .
                "\n LEFT JOIN #__rem_houses AS h ON h.id=hc.iditem AND ".
                "( h.published || isnull(h.published) ) AND ( h.approved || isnull(h.approved ) )" .
                "\n WHERE c.section='com_realestatemanager' AND c.published=1 AND h.published = 1 AND h.approved = 1
                 \n  $lang AND ({$s})     
        \n GROUP BY c.id ORDER BY c.parent_id DESC, c.ordering";


        $database->setQuery($query);
        $cat_all = $database->loadObjectList();
// print_r($query);print_r($cat_all); exit;
        foreach ($cat_all as $k1 => $cat_item1) {
            
          $cat_all[$k1]->display = PHP_realestatemanager::is_exist_curr_and_subcategory_houses($cat_all[$k1]->id);
                    
        }

        $currentcat = new stdClass();

        // Parameters
        $menu = new JTableMenu($database);
        $menu->load($Itemid);
        $params = new mosParameters($menu->params);
        $menu_name = set_header_name_rem($menu, $Itemid);

        $params->def('header', $menu_name);
        $params->def('pageclass_sfx', '');
        $params->def('back_button', $mainframe->getCfg('back_button'));

        // page header
        $currentcat->header = $params->get('header');

//***************   begin show search_option    *********************
        if ($realestatemanager_configuration['search_option']['show']) {
            $params->def('search_option', 1);
            if (checkAccess_REM($realestatemanager_configuration['search_option']['registrationlevel'], 
              'NORECURSE', userGID_REM($my->id), $acl)) {
                $params->def('search_option_registrationlevel', 1);
            }
        }
//**************   end show search_option     ******************************        

        //add for show in category picture
        if ($realestatemanager_configuration['cat_pic']['show'])
            $params->def('show_cat_pic', 1);

        // page description
        $currentcat->descrip = _REALESTATE_MANAGER_DESC;

        // used to show table rows in alternating colours
        $tabclass = array('sectiontableentry1', 'sectiontableentry2');

        $params->def('allcategories01', "{loadposition com_realestatemanager_all_categories_01,xhtml}");
        $params->def('allcategories02', "{loadposition com_realestatemanager_all_categories_02,xhtml}");
        $params->def('allcategories03', "{loadposition com_realestatemanager_all_categories_03,xhtml}");
        $params->def('allcategories04', "{loadposition com_realestatemanager_all_categories_04,xhtml}");
        $params->def('allcategories05', "{loadposition com_realestatemanager_all_categories_05,xhtml}");
        $params->def('allcategories06', "{loadposition com_realestatemanager_all_categories_06,xhtml}");
        $params->def('allcategories07', "{loadposition com_realestatemanager_all_categories_07,xhtml}");
        $params->def('allcategories08', "{loadposition com_realestatemanager_all_categories_08,xhtml}");
        $params->def('allcategories09', "{loadposition com_realestatemanager_all_categories_09,xhtml}");
        $params->def('allcategories10', "{loadposition com_realestatemanager_all_categories_10,xhtml}");


        HTML_realestatemanager::showCategories($params, $cat_all, $catid, $tabclass, $currentcat, $layout);
    }

static function getMonthCal($month, $year, $id) {
  global $database, $realestatemanager_configuration;
  $query = "SELECT rent_from, rent_until, rent_return FROM #__rem_rent WHERE fk_houseid='$id' ORDER BY rent_from";
  $database->setQuery($query);
  $calenDate = $database->loadObjectList();        
  $skip = date("w", mktime(0, 0, 0, $month, 1, $year)) - 1;
  if ($skip < 0){
    $skip = 6;
  }
  $daysInMonth = date("t", mktime(0, 0, 0, $month, 1, $year));      
/*******************************get only rent days*****************************/  
  $rentDataArr = array();
  $i=0;
  foreach ($calenDate as &$value) {
    if(!($value->rent_return)){
      if(isset($calenDate[($i+1)]) && $calenDate[($i+1)]->rent_from == $calenDate[$i]->rent_until){
        $calenDate[($i+1)]->rent_from = $calenDate[$i]->rent_from;
        unset($calenDate[$i]);
        $i++;
        continue;
      }   
     array_push($rentDataArr, $value);
    }$i++;
  }
  $calenDate = $rentDataArr;       
  $calendar = '';
  $day = 1;
  $smonth = PHP_realestatemanager::getMonth($month);
  $calendar = '<table class="rem_tableC" style="border-collapse: separate;'.
    ' border-spacing: 2px;text-align:center"><tr class="year"><th colspan = "7">' .
    $smonth . ' ' . $year . '</th></tr><tr class="days"><th>' . JText::_('MON') .
    '</th><th>' . JText::_('TUE') . '</th><th>' . JText::_('WED') . '</th><th>' .
    JText::_('THU') . '</th><th>' . JText::_('FRI') . '</th><th>' . JText::_('SAT') .
     '</th><th>' . JText::_('SUN') . '</th></tr>';
  for ($i = 0; $i < 6; $i++) {
    $calendar .= '<tr>';
    for ($j = 0; $j < 7; $j++) {
      if (($skip > 0) or ($day > $daysInMonth)){
        $calendar .= '<td> &nbsp; </td>';
        $skip--;
      }else{ 
        $isAvilable = getAvilableRM($calenDate,$month,$year,$realestatemanager_configuration,$day);
        $calendar .= '<td class="'.$isAvilable.'">' . $day . '</td>';
        $day++;
      }
    }
    $calendar .= '</tr>';
  }
  $calendar .= '</table>';
 
  return $calendar;
}

static function getCalendarPrice($month, $year, $id){
  global $database;
  $query = "SELECT * FROM `#__rem_rent_sal` " .
    " WHERE (`fk_houseid`='$id') and (`yearW`='$year') and (`monthW`='$month')";
  $database->setQuery($query);
  $calenWeeks = $database->loadObjectList();
  if (!empty($calenWeeks)){
    $calenWeek = $calenWeeks[0];
    $calendar = "";
    $calendar = '<table style="text-align:left">';
    $calendar .= '<tr><td><b>' . _REALESTATE_MANAGER_LABEL_CALENDAR_WEEK . '<b></td></tr>';
    $calendar .= '<tr><td>' . str_replace("\n", "<br>\n", $calenWeek->week) . '</td></tr>';
    $calendar .= '<tr><td><b>' . _REALESTATE_MANAGER_LABEL_CALENDAR_WEEKEND . '<b></td></tr>';
    $calendar .= '<tr><td>' . str_replace("\n", "<br>\n", $calenWeek->weekend) . '</td></tr>';
    $calendar .= '<tr><td><b>' . _REALESTATE_MANAGER_LABEL_CALENDAR_MIDWEEK . '</b></td></tr>';
    $calendar .= '<tr><td><span>' . str_replace("\n", "<br>\n", $calenWeek->midweek) . '<span></td></tr>';
    $calendar .= '</table>';
    return $calendar;
  }
}

static function getCalendar($month, $year, $id){
  $month = (int) $month;
      $year = (int) $year;

      if ($month == 1)
      {
          $month1 = 12;
          $year1 = $year - 1;
      } else
      {
          $month1 = $month - 1;
          $year1 = $year;
      }

      if ($month == 12)
      {
          $month2 = 1;
          $month3 = 2;
          $year2 = $year3 = $year + 1;
      } else
      {
          $month2 = $month + 1;
          $month3 = $month + 2;
          $year2 =$year3 = $year;
      }
      if($month3 > 12){
        $month3 = $month3 - 12;
        $year3 = $year + 1;
      }
  $calendar = new stdClass();
  $calendar->tab1 = PHP_realestatemanager::getMonthCal($month1, $year1, $id);
  $calendar->tab2 = PHP_realestatemanager::getMonthCal($month, $year, $id);
  $calendar->tab3 = PHP_realestatemanager::getMonthCal($month2, $year2, $id);
  $calendar->tab4 = PHP_realestatemanager::getMonthCal($month3, $year3, $id);
  $calendar->tab21 = PHP_realestatemanager::getCalendarPrice($month1, $year1, $id);
  $calendar->tab22 = PHP_realestatemanager::getCalendarPrice($month, $year, $id);
  $calendar->tab23 = PHP_realestatemanager::getCalendarPrice($month2, $year2, $id);
  $calendar->tab24 = PHP_realestatemanager::getCalendarPrice($month3, $year3, $id);

  return $calendar;
}

    static function checkItemidViewHouse() {
        global $database;

        $Itemid_from_db = null;
        $query = " SELECT id FROM `#__menu` WHERE link 
                   LIKE'%option=com_realestatemanager&view=view_house%'";
        $database->setQuery($query);
        $Itemid_from_db = $database->loadResult();
        if (!empty($Itemid_from_db) && $Itemid_from_db != '') {
          return $Itemid_from_db;
        }        
    }
}