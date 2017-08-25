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
defined('_VM_IS_BACKEND') or define('_VM_IS_BACKEND', '1');

$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'] = JPATH_SITE;
include_once( $mosConfig_absolute_path . '/components/com_realestatemanager/compat.joomla1.5.php' );
include_once( $mosConfig_absolute_path . '/components/com_realestatemanager/functions.php' );

$my = $GLOBALS['my'];
if (get_magic_quotes_gpc()) {
    function stripslashes_gpc(&$value) {
        $value = stripslashes($value);
    }
    array_walk_recursive($_GET, 'stripslashes_gpc');
    array_walk_recursive($_POST, 'stripslashes_gpc');
    array_walk_recursive($_COOKIE, 'stripslashes_gpc');
    array_walk_recursive($_REQUEST, 'stripslashes_gpc');
}
$css = $mosConfig_absolute_path .
 '/administrator/components/com_realestatemanager/admin_realestatemanager.css';
$mainframe = JFactory::getApplication();
jimport('joomla.html.pagination');
jimport('joomla.filesystem.folder');

require_once ($mosConfig_absolute_path .
 "/administrator/components/com_realestatemanager/realestatemanager.html.php");
require_once ($mosConfig_absolute_path .
 "/components/com_realestatemanager/realestatemanager.class.language.php");
require_once ($mosConfig_absolute_path .
 "/components/com_realestatemanager/realestatemanager.class.feature.php");
require_once ($mosConfig_absolute_path .
 "/components/com_realestatemanager/realestatemanager.class.php");
require_once ($mosConfig_absolute_path .
 "/components/com_realestatemanager/realestatemanager.class.rent.php");
require_once ($mosConfig_absolute_path .
 "/components/com_realestatemanager/realestatemanager.class.rent_request.php");
require_once ($mosConfig_absolute_path .
 "/components/com_realestatemanager/realestatemanager.class.buying_request.php");
require_once ($mosConfig_absolute_path .
 "/administrator/components/com_realestatemanager/realestatemanager.class.impexp.php");
require_once ($mosConfig_absolute_path .
 "/administrator/components/com_realestatemanager/realestatemanager.class.conf.php");
require_once ($mosConfig_absolute_path .
 "/components/com_realestatemanager/functions.php");
require_once ($mosConfig_absolute_path .
 "/components/com_realestatemanager/realestatemanager.main.categories.class.php");

$GLOBALS['realestatemanager_configuration'] = $realestatemanager_configuration;
$GLOBALS['database'] = $database;
$GLOBALS['my'] = $my;
$GLOBALS['mosConfig_absolute_path'] = $mosConfig_absolute_path;
$table_prefix = $database->getPrefix(); // for J 1.6
$GLOBALS['table_prefix'] = $table_prefix; // for J 1.6
$GLOBALS['task'] = $task = mosGetParam($_REQUEST, 'task', '');
$GLOBALS['option'] = $option = mosGetParam($_REQUEST, 'option', 'com_realestatemanager');

global $mosConfig_lang;

$cls_path = $mosConfig_absolute_path . "/components/com_realestatemanager/realestatemanager.class.php";
require_once ($cls_path);
if (version_compare(JVERSION, "3.3.0", "lt")) {
    require_once $mosConfig_absolute_path . "/administrator/components/com_realestatemanager/language.php";
}
$bid = mosGetParam($_REQUEST, 'bid', array(0));

$section = mosGetParam($_REQUEST, 'section', 'courses');

if ($realestatemanager_configuration['debug'] == '1') {
    echo "Task: " . $task . "<br />";
    print_r($_REQUEST);
    echo "<hr /><br />";
}

if (version_compare(JVERSION, "3.0.0", "ge"))
    require_once ($mosConfig_absolute_path .
     "/administrator/components/com_realestatemanager/toolbar.realestatemanager.php");
if (isset($section) && $section == 'categories') {
    switch ($task) {
        case "edit" :
            editCategory($option, $bid[0]);
            break;
        case "add":
            editCategory($option, 0);
            break;
        case "cancel":
            cancelCategory();
            break;
        case "save":
        case 'apply':        
            saveCategory();
            break;
        case "remove":
            removeCategories($option, $bid);
            break;
        case "publish":
            publishCategories("com_realestatemanager", $id, $bid, 1);
            break;
        case "unpublish":
            publishCategories("com_realestatemanager", $id, $bid, 0);
            break;
        case "orderup":
            orderCategory($bid[0], -1);
            break;
        case "orderdown":
            orderCategory($bid[0], 1);
            break;
        case "accesspublic":
            accessCategory($bid[0], 0);
            break;
        case "accessregistered":
            accessCategory($bid[0], 1);
            break;
        case "accessspecial":
            accessCategory($bid[0], 2);
            break;
        case "show":
        default :
            showCategories();
    }
} elseif ($section == 'featured_manager') {
    switch ($task) {
        case "edit" :
            editFeaturedManager($option, $bid[0]);
            break;

        case "add":
            editFeaturedManager($option, 0);
            break;

        case "cancel":
            cancelFeaturedManager();
            break;

        case "save":
            saveFeaturedManager();
            break;

        case "remove":
            removeFeaturedManager($option, $bid);
            break;

        case "publish":
            publishFeaturedManager("com_realestatemanager", $id, $bid, 1);
            break;

        case "unpublish":
            publishFeaturedManager("com_realestatemanager", $id, $bid, 0);
            break;
        case "addFeature":
            save_featured_category($option);
            showFeaturedManager($option);
            break;
        default:
            showFeaturedManager($option);
            break;
    }
} elseif ($section == 'language_manager') {
    switch ($task) {
        case "edit":
            editLanguageManager($option, $bid[0]);
            break;

        case "cancel":
            cancelLanguageManager();
            break;

        case "save":
            saveLanguageManager();
            break;

        default:
            showLanguageManager($option);
            break;
    }
} else {
    switch ($task) {
        case "categories":
            echo "now work $section=='categories , this part not work";
            exit;
            mosRedirect("index.php?option=categories&section=com_realestatemanager");
            break;

        case "add":
            editHouse($option, 0);
            break;

        case "edit":
            editHouse($option, array_pop($bid));
            break;
        
        case "ajax_rent_price":    
          $get_tmp = JRequest::get('get');
          
          if(isset ($get_tmp["bid"]) AND 
            isset ($get_tmp["rent_from"]) AND 
            isset($get_tmp["rent_until"]) AND 
            isset($get_tmp["special_price"])){

                $bid_ajax_rent = $get_tmp["bid"];
                $rent_from = $get_tmp["rent_from"];
                $rent_until = $get_tmp["rent_until"];
                $special_price = $get_tmp["special_price"];
                $currency_spacial_price = $get_tmp["currency_spacial_price"];
                if(isset($get_tmp["comment_price"]))
                    $comment_price = $get_tmp["comment_price"];
                else
                    $comment_price = '';
            }
             
            rentPrice($bid_ajax_rent,$rent_from,$rent_until,$special_price,
              $comment_price,$currency_spacial_price);
            break;
        
        case "clon_rem":             
            clonHouse($bid,$option);
            break;

        case "apply":
        case "save":
            saveHouse($option, $task);
            break;

        case "remove":
            removeHouses($bid, $option);
            break;

        case "publish":
            publishHouses($bid, 1, $option);
            break;

        case "unpublish":
            publishHouses($bid, 0, $option);
            break;

        case "approve":
            approveHouses($bid, 1, $option);
            break;

        case "unapprove":
            approveHouses($bid, 0, $option);
            break;

        case "cancel":
            cancelHouse($option);
            break;

        case "houseorderdown":
            orderHouses($bid[0], 1, $option);
            break;

        case "houseorderup":
            orderHouses($bid[0], -1, $option);
            break;

        case "config":
            configure($option);
            break;

        case "config_save":
            configure_save_frontend($option);
            configure_save_backend($option);
            configure($option);
            break;

        case "rent":
            
            if (mosGetParam($_POST, 'save') == 1)
                saveRent($option, $bid, '',false); 
            else
                rent($option, $bid);
            break;

        case "rent_history":
            rent_history($option, $bid);
            break;

        case "users_rent_history":
            users_rent_history($option, $bid);
            break;

        case "rent_requests":
            rent_requests($option, $bid);
            break;

        case "buying_requests":
            buying_requests($option);
            break;

        case "accept_rent_requests":
            accept_rent_requests($option, $bid);
            break;

        case "decline_rent_requests":
            decline_rent_requests($option, $bid);
            break;

        case "accept_buying_requests":
            accept_buying_requests($option, $bid);
            break;

        case "decline_buying_requests":
            decline_buying_requests($option, $bid);
            break;

        case "about":
            HTML_realestatemanager :: about();
            break;

        case "show_info":
            showInfo($option, $bid);
            break;

        case "rent_return":
            if (mosGetParam($_POST, 'save') == 1)
                saveRent_return($option, $bid,false); else
                rent_return($option, $bid);
            break;

        case "edit_rent":
            if (mosGetParam($_POST, 'save') == 1) {
                if (count($bid) > 1) {
                    echo "<script> alert('". _REALESTATE_MANAGER_ADMIN_ONE_ITEM_ALERT .
                     "'); window.history.go(-1); </script>\n";
                    exit;
                }
                saveRent($option, $bid, "edit_rent",false);
            } else
                edit_rent($option, $bid);
            break;

        case "delete_review":
            $ids = explode(',', $bid[0]);
            delete_review($option, $ids[1]);
            editHouse($option, $ids[0]);
            break;

        case "edit_review":
            $ids = explode(',', $bid[0]);
            edit_review($option, $ids[1], $ids[0]);
            break;

        case "update_review":
            $title = mosGetParam($_POST, 'title');
            $comment = mosGetParam($_POST, 'comment');
            $rating = mosGetParam($_POST, 'rating');
            $house_id = mosGetParam($_POST, 'house_id');
            $review_id = mosGetParam($_POST, 'review_id');
            update_review($title, $comment, $rating, $review_id);
            editHouse($option, $house_id);
            break;

        case "cancel_review_edit":
            $house_id = mosGetParam($_POST, 'house_id');
            editHouse($option, $house_id);
            break;

        case "checkFile":
            checkFile();
            break;
        default:
            showHouses($option);
            break;
    }
} //else

class CAT_Utils {

    static function categoryArray() {
        global $database;
        // get a list of the menu items
        $query = "SELECT c.*, c.parent_id AS parent"
                . "\n FROM #__rem_main_categories c"
                . "\n WHERE section='com_realestatemanager'"
                . "\n AND published <> -2"
                . "\n ORDER BY ordering";
        $database->setQuery($query);
        $items = $database->loadObjectList();
        // establish the hierarchy of the menu
        $children = array();
        // first pass - collect children
        foreach ($items as $v) {
            $pt = $v->parent;
            $list = @$children[$pt] ? $children[$pt] : array();
            array_push($list, $v);
            $children[$pt] = $list;
        }
        // second pass - get an indent list of the items
        $array = mosTreeRecurseREM(0, '', array(), $children);
        return $array;
    }

}

/**
 * HTML Class
 * Utility class for all HTML drawing classes
 * @desc class General HTML creation class. We use it for back/front ends.
 */
class HTML {

    // TODO :: merge categoryList and categoryParentList
    // add filter option ?
//     function categoryList($id, $action, $options = array()){
//         $list = CAT_Utils::categoryArray();
//         // assemble menu items to the array
//         foreach ($list as $item) {
//             $options[] = mosHTML::makeOption($item->id, $item->treename);
//         }
//         $parent = mosHTML::selectList($options, 'catid', 'id="catid" class="inputbox"
// size="1" onchange="' . $action . '"', 'value', 'text', $id);
//         return $parent;
//     }


    static function categoryParentList($id, $action, $options = array()) {
        global $database;
        $list = CAT_Utils::categoryArray();
        $cat = new mainRealEstateCategories($database); //for 1.6
        $cat->load($id);

        $this_treename = '';
        $childs_ids = Array();
        foreach ($list as $item) {
            if ($item->id == $cat->id || array_key_exists($item->parent_id, $childs_ids))
                $childs_ids[$item->id] = $item->id;
        }

        foreach ($list as $item) {
            if ($this_treename) {
                if ($item->id != $cat->id
                        && strpos($item->treename, $this_treename) === false
                        && array_key_exists($item->id, $childs_ids) === false) {
                    $options[] = mosHTML::makeOption($item->id, $item->treename);
                }
            } else {
                if ($item->id != $cat->id) {
                    $options[] = mosHTML::makeOption($item->id, $item->treename);
                } else {
                    $this_treename = "$item->treename/";
                }
            }
        }

        $parent = null;
        $parent = mosHTML::selectList($options, 'parent_id', 'class="inputbox" size="1"',
         'value', 'text', $cat->parent_id);

        return $parent;
    }

    static function imageList($name, &$active, $javascript = null, $directory = null) {
        global $mosConfig_absolute_path;
        if (!$javascript) {
            $javascript = "onchange=\"javascript:if (document.adminForm." . $name .
                    ".options[selectedIndex].value!='') {document.imagelib.src='../images/stories/' + document.adminForm."
                    . $name . ".options[selectedIndex].value} else {document.imagelib.src='../images/blank.png'}\"";
        }
        if (!$directory) {
            $directory = '/images/stories';
        }

        if (!file_exists($mosConfig_absolute_path . $directory)) {
            @mkdir($mosConfig_absolute_path . $directory, 0777) or
             die("Error of directory creating: [" . $mosConfig_absolute_path . $directory . "] ");
        }

        $imageFiles = mosReadDirectory($mosConfig_absolute_path . $directory);
        $images = array(mosHTML::makeOption('', _A_SELECT_IMAGE));


        foreach ($imageFiles as $file) {
            if (preg_match("/bmp|gif|jpeg|jpg|png/i", $file)) {
                $images[] = mosHTML::makeOption($file);
            }
        }
        $images = mosHTML::selectList($images, $name, 'id="' . $name .
         '" class="inputbox" size="1" ' . $javascript, 'value', 'text', $active);

        return $images;
    }
}

function houseLibraryTreeRecurse($id, $indent, $list, &$children, $maxlevel = 9999, $level = 0, $type = 1) {
    if (@$children[$id] && $level <= $maxlevel) {
        $parent_id = $id;
        foreach ($children[$id] as $v) {
            $id = $v->id;
            if ($type) {
                $pre = 'L ';
                $spacer = '.      ';
            } else {
                $pre = '- ';
                $spacer = '  ';
            }
            if ($v->parent == 0) {
                $txt = $v->name;
            } else {
                $txt = $pre . $v->name;
            }
            $pt = $v->parent;
            $list[$id] = $v;
            $list[$id]->treename = "$indent$txt";
            $list[$id]->children = count(@$children[$id]);
            $list[$id]->all_fields_in_list = count(@$children[$parent_id]);
            $list = houseLibraryTreeRecurse($id, $indent . $spacer, $list,
             $children, $maxlevel, $level + 1, $type);
        }
    }
    return $list;
}

function showCategories() {
    global $database, $my, $acl, $option, $menutype, $mainframe, $mosConfig_list_limit;
    $mainframe = JFactory::getApplication();

    $groups = get_group_children_rem();

    $section = "com_realestatemanager";
    $sectionid = $mainframe->getUserStateFromRequest("sectionid{$section}{$section}", 'sectionid', 0);
    $limit = $mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mosConfig_list_limit);
    $limitstart = $mainframe->getUserStateFromRequest("view{$section}limitstart", 'limitstart', 0);
    $levellimit = $mainframe->getUserStateFromRequest("view{$option}limit$menutype", 'levellimit', 10);

    $query = "SELECT  c.*, c.checked_out as checked_out_contact_category, "
            . " c.parent_id as parent, u.name AS editor, c.params, COUNT(hc.id) AS cc"
            . "\n FROM #__rem_main_categories AS c"
            . "\n LEFT JOIN #__rem_categories AS hc ON hc.idcat=c.id"
            . "\n LEFT JOIN #__users AS u ON u.id = c.checked_out"
            . "\n WHERE c.section='$section'"
            . "\n GROUP BY c.id"
            . "\n ORDER BY parent DESC, ordering";

    $database->setQuery($query);
    $rows = $database->loadObjectList();

    if (version_compare(JVERSION, "3.0.0", "lt"))
        $curdate = strtotime(JFactory::getDate()->toMySQL());
    else
        $curdate = strtotime(JFactory::getDate()->toSQL());
    foreach ($rows as $row) {
        $check = strtotime($row->checked_out_time);
        $remain = 7200 - ($curdate - $check);
        if (($remain <= 0) && ($row->checked_out != 0)) {
            $item = new mainRealEstateCategories($database);
            $item->checkin($row->id);
        }
    }

    if ($database->getErrorNum()) {
        echo $database->stderr();
        return false;
    }

    foreach ($rows as $k => $v) {
        $rows[$k]->ncourses = 0;
        foreach ($rows as $k1 => $v1)
            if ($v->id == $v1->parent)
                $rows[$k]->cc +=$v1->cc;
        ($rows[$k]->cc == 0) ? "-" : "<a href=\"?option=com_realestatemanager&section=house&catid="
         . $v->id . "\">" . ($v->cc) . "</a>"; //for 1.6
        $curgroup = "";
        $ss = explode(',', $v->params);
        foreach ($ss as $s) {
            if ($s == '')
                $s = '-2';
            $curgroup[] = $groups[$s];
        }
        $rows[$k]->groups = implode(', ', $curgroup);
    }

    if ($database->getErrorNum()) {
        echo $database->stderr();
        return false;
    }

    // establish the hierarchy of the categories
    $children = array();
    // first pass - collect children
    foreach ($rows as $v) {
        $pt = $v->parent;
        $list = @$children[$pt] ? $children[$pt] : array();
        array_push($list, $v);
        $children[$pt] = $list;
    }
    // second pass - get an indent list of the items
    $list = houseLibraryTreeRecurse(0, '', array(), $children, max(0, $levellimit - 1));
    $total = count($list);

    $pageNav = new JPagination($total, $limitstart, $limit); // for J 1.6

    $levellist = mosHTML::integerSelectList(1, 20, 1,
     'levellimit', 'size="1" onchange="document.adminForm.submit();"', $levellimit);
    // slice out elements based on limits
    $list = array_slice($list, $pageNav->limitstart, $pageNav->limit);

    $count = count($list);
    $javascript = 'onchange="document.adminForm.submit();"';
    $lists['sectionid'] =
     version_compare(JVERSION, '3.0', 'ge') ? NUll : mosAdminMenus::SelectSection(
     'sectionid', $sectionid, $javascript);
    HTML_Categories::show($list, $my->id, $pageNav, $lists, 'other');
}

    function createAssociateArray($row, $database){
        $associateArray = array();
        if($row->id){
            $query = "SELECT lang_code FROM `#__languages` WHERE 1";
            $database->setQuery($query);
            $allLanguages =  $database->loadColumn(); 
     
            $query = "SELECT id,language,title FROM `#__rem_main_categories` WHERE 1";
            $database->setQuery($query);
            $allInCategories =  $database->loadObjectlist(); 
  
            $query = "select associate_category from #__rem_main_categories where id =".$row->id;
            $database->setQuery($query);
            $categoryAssociateCategory =  $database->loadResult(); 

            if(!empty($categoryAssociateCategory)){
                $categoryAssociateCategory = unserialize($categoryAssociateCategory);
            }else{
                $categoryAssociateCategory = array();
            }

            foreach ($allLanguages as &$oneLang) {
                $associate_category = array();
                $associate_category[] = mosHtml::makeOption(0, 'select'); 
                $i = 0;
       
                foreach($allInCategories as &$oneCat){
                    if($oneLang == $oneCat->language && $oneCat->id != $row->id){
                        $associate_category[] = mosHtml::makeOption(($oneCat->id), $oneCat->title);
                    } 
                } 
       
                if($row->language != $oneLang){
                    $associate_category_list = mosHTML :: selectList($associate_category,
                     'language_associate_category', 'class="inputbox" size="1"', 'value', 'text', ""); 
                }else{
                    $associate_category_list = null;
                }
       
                $associateArray[$oneLang]['list'] = $associate_category_list;
       
                if(isset($categoryAssociateCategory[$oneLang])){
                    $associateArray[$oneLang]['assocId'] = $categoryAssociateCategory[$oneLang];
                }else{
                    $associateArray[$oneLang]['assocId'] = 0;
                }
            }
        } 
        return $associateArray;
    }

function editCategory($section = '', $uid = 0) {
    global $database, $my, $acl;
    global $mosConfig_absolute_path, $mosConfig_live_site, $realestatemanager_configuration;

    $type = mosGetParam($_REQUEST, 'type', '');
    $redirect = mosGetParam($_POST, 'section', '');
    ;

    $row = new mainRealEstateCategories($database);

    // load the row from the db table
    $row->load($uid);
    // fail if checked out not by 'me'
    if ($row->checked_out && $row->checked_out <> $my->id) {
        mosRedirect('index.php?option=com_realestatemanager&task=categories', 'The category ' .
         $row->title . ' is currently being edited by another administrator');
    }

    if ($uid) {
        // existing record
        $row->checkout($my->id);
        // code for Link Menu
    } else {
        // new record
        $row->section = $section;
        $row->published = 1;
    }
    

    
    $associateArray = createAssociateArray($row, $database);

    // build the select list for the image positions
    $active = ($row->image_position ? $row->image_position : 'left');
    $lists['image_position'] = version_compare(JVERSION, '3.0', 'ge') 
      ? NUll : mosAdminMenus::Positions('image_position', $active, null, 0, 0);
    // Imagelist
    $lists['image'] = HTML::imageList('image', $row->image);
    // build the html select list for the group access
    $lists['access'] = version_compare(JVERSION, '3.0', 'ge') ? NUll : mosAdminMenus::Access($row);
    // build the html radio buttons for published
    $lists['published'] = mosHTML::yesnoRadioList('published', 'class="inputbox"', $row->published);
    
    $options = array();
    $options[] = mosHTML::makeOption('0', _A_SELECT_TOP);
    $lists['parent'] = HTML::categoryParentList($row->id, "", $options);
    //***********access category
    $gtree = get_group_children_tree_rem();

    $f = "";
    if (trim($row->params) == '')
        $row->params = '-2';
    $s = explode(',', $row->params);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);
    $lists['category']['registrationlevel'] = mosHTML::selectList($gtree,
     'category_registrationlevel[]', 'size="" multiple="multiple"', 'value', 'text', $f);
    //********end access category

    $query = "SELECT lang_code, title FROM #__languages";
    $database->setQuery($query);
    $languages = $database->loadObjectList();

    $languages_row[] = mosHTML::makeOption('*', 'All');
    foreach ($languages as $language) {
        $languages_row[] = mosHTML::makeOption($language->lang_code, $language->title);
    }

    $lists['languages'] = mosHTML :: selectList($languages_row, 'language',
     'class="inputbox" size="1"', 'value', 'text', $row->language);

    $params2 = unserialize($row->params2);

    if (empty($params2)) {
        $params2 = new stdClass();
        $params2->alone_category = '';
        $params2->view_house = '';
    }

    //***************************************************

    HTML_Categories::edit($row, $section, $lists, $redirect, $associateArray);
}

function saveCategory() {
    global $database,$task,$option;

    $row = new mainRealEstateCategories($database);

    $post = JRequest::get('post', JREQUEST_ALLOWHTML);

    saveAssociateCayegoriesREM($post, $database);
   
    $params2 = new stdClass();

    $post['params2'] = serialize($params2);

    if (!$row->bind($post)) {
        echo "<script> alert('" . addslashes($row->getError()) . "'); window.history.go(-1); </script>\n";
        exit;
    }
    $row->section = 'com_realestatemanager';
    $row->parent_id = $_REQUEST['parent_id'];
    //****set access level
    $row->params = implode(',', JRequest::getVar('category_registrationlevel'));
    //****end set access level

    if (!$row->check()) {
        echo "<script> alert('" . addslashes($row->getError()) . "'); window.history.go(-1); </script>\n";
        exit;
    }


    if (!$row->store()) {
        echo "<script> alert('" . addslashes($row->getError()) . "'); window.history.go(-1); </script>\n";
        exit;
    }

    $row->checkin();
    
    
    switch ($task) {
        case 'apply':
            mosRedirect("index.php?section=categories&option=" . $option . "&task=edit&bid[]=" . $row->id);
            break;

        case 'save':
            mosRedirect("index.php?section=categories&option=" . $option);
            break;
    }
}

 function is_exist_curr_and_subcategory_houses($catid) {
    global $database, $my;
    $query = "SELECT *, COUNT(a.id) AS numlinks FROM #__rem_main_categories AS cc"
            . "\n  JOIN #__rem_categories AS hc ON hc.idcat = cc.id"
            . "\n  JOIN #__rem_houses AS a ON a.id = hc.iditem"
            . "\n WHERE  section='com_realestatemanager' AND cc.id='$catid' "
            . "\n GROUP BY cc.id"
            . "\n ORDER BY cc.ordering";
    $database->setQuery($query);
    $categories = $database->loadObjectList();
    if (count($categories) != 0)
        return true;

    $query = "SELECT id "
            . "FROM #__rem_main_categories AS cc "
            . " WHERE section='com_realestatemanager' AND parent_id='$catid' ";
    $database->setQuery($query);
    $categories = $database->loadObjectList();

    if (count($categories) == 0)
        return false;

    foreach ($categories as $k) {
        if (is_exist_curr_and_subcategory_houses($k->id))
            return true;
    }
    return false;
} 

function removeCategoriesFromDB($cid) {
    global $database, $my;
    $query = "SELECT id  "
            . " FROM #__rem_main_categories AS cc "
            . " WHERE section='com_realestatemanager' AND parent_id='$cid' ";
    $database->setQuery($query);
    $categories = $database->loadObjectList();

    if (count($categories) != 0) {
        //delete child
        foreach ($categories as $k) {
            removeCategoriesFromDB($k->id);
        }
    }
    $sql = "DELETE FROM #__rem_main_categories WHERE id = $cid ";
    $database->setQuery($sql);
    $database->query();
}

/**
 * Deletes one or more categories from the categories table
 * 
 * @param string $ The name of the category section
 * @param array $ An array of unique category id numbers
 */
function removeCategories($section, $cid) {
    global $database;

    if (count($cid) < 1) {
        echo "<script> alert('". _REALESTATE_MANAGER_ADMIN_ONE_CTEGORY_ALERT .
         "'); window.history.go(-1);</script>\n";
        exit;
    }

    foreach ($cid as $catid)
        removeCategoriesFromDB($catid);

    $msg = (count($err) > 1 ? "Categories " : _CATEGORIES_NAME . " ") . _DELETED;
    mosRedirect('index.php?option=com_realestatemanager&section=categories&mosmsg=' . $msg);
}

/**
 * Publishes or Unpublishes one or more categories
 * 
 * @param string $ The name of the category section
 * @param integer $ A unique category id (passed from an edit form)
 * @param array $ An array of unique category id numbers
 * @param integer $ 0 if unpublishing, 1 if publishing
 * @param string $ The name of the current user
 */
function publishCategories($extension, $categoryid = null, $cid = null, $publish = 1) {
    global $database, $my;

    if (!is_array($cid))
        $cid = array();
    if ($categoryid)
        $cid[] = $categoryid;

    if (count($cid) < 1) {
        $action = $publish ? _PUBLISH : _DML_UNPUBLISH;
        echo "<script> alert('" . _DML_SELECTCATTO . " $action'); window.history.go(-1);</script>\n";
        exit;
    }

    $cids = implode(',', $cid);

    $query = "UPDATE #__rem_main_categories SET published='$publish'"
            . "\nWHERE id IN ($cids) AND (checked_out=0 OR (checked_out='$my->id'))";
    $database->setQuery($query);
    if (!$database->query()) {
        echo "<script> alert('" . addslashes($database->getErrorMsg()) .
         "'); window.history.go(-1); </script>\n";
        exit();
    }

    if (count($cid) == 1) {
        $row = new mainRealEstateCategories($database);
        $row->checkin($cid[0]);
    }
    mosRedirect('index.php?option=com_realestatemanager&section=categories');
}

/**
 * Cancels an edit operation
 * 
 * @param string $ The name of the category section
 * @param integer $ A unique category id
 */
function cancelCategory() {
    global $database;
    $row = new mainRealEstateCategories($database);
    $row->bind($_POST);
    $row->checkin();
    mosRedirect('index.php?option=com_realestatemanager&section=categories');
}

/**
 * Moves the order of a record
 * 
 * @param integer $ The increment to reorder by
 */
function orderCategory($uid, $inc) {
    global $database;
    $row = new mainRealEstateCategories($database);
    $row->load($uid);
    if ($row->ordering == 1 && $inc == -1)
        mosRedirect('index.php?option=com_realestatemanager&section=categories');
    $new_order = $row->ordering + $inc;
    //change ordering - for other element
    $query = "UPDATE #__rem_main_categories SET ordering='" . ($row->ordering) . "'"
            . "\nWHERE parent_id = $row->parent_id and ordering=$new_order";
    $database->setQuery($query);
    $database->query();
    //change ordering - for this element
    $query = "UPDATE #__rem_main_categories SET ordering='" . $new_order . "'"
            . "\nWHERE id = $uid";
    $database->setQuery($query);
    $database->query();
    mosRedirect('index.php?option=com_realestatemanager&section=categories');
}

/**
 * changes the access level of a record
 * 
 * @param integer $ The increment to reorder by
 */
function accessCategory($uid, $access) {
    global $database;
    $row = new mainRealEstateCategories($database);
    $row->load($uid);
    $row->access = $access;
    if (!$row->check())
        return $row->getError();
    if (!$row->store())
        return $row->getError();
    mosRedirect('index.php?option=com_realestatemanager&section=categories');
}

function update_review($title, $comment, $rating, $review_id) {
    global $database;

    $review = new mosRealEstateManager_review($database);
    $review->load($review_id);

    if (!$review->bind($_POST)) {
        echo "<script> alert('" . addslashes($review->getError()) . "'); window.history.go(-1); </script>\n";
        exit();
    }

    if (!$review->check()) {
        echo "<script> alert('" . addslashes($review->getError()) . "'); window.history.go(-1); </script>\n";
        exit();
    }

    if (!$review->store()) {
        echo "<script> alert('" . addslashes($review->getError()) . "'); window.history.go(-1); </script>\n";
        exit();
    }
}

function edit_review($option, $review_id, $house_id) {
    global $database;
    $database->setQuery("SELECT * FROM #__rem_review WHERE id=" . $review_id . " ");
    $review = $database->loadObjectList();
    echo $database->getErrorMsg();
    HTML_realestatemanager :: edit_review($option, $house_id, $review);
}

/*
 * Add Nikolay.
 * Function for delete coment
 * (comment for every house) 
 * in database.
 */

function delete_review($option, $id) {
    global $database;
    //delete review where id =.. ;
    $database->setQuery("DELETE FROM #__rem_review WHERE #__rem_review.id=" . $id . ";");
    $database->query();
    echo $database->getErrorMsg();
}

//*************************************************************************************************************
//*********************************   begin for manage reviews   **********************************************
//*************************************************************************************************************
function delete_manage_review($option, $id) {
    global $database;
    for ($i = 0; $i < count($id); $i++) {
        //delete review where id =.. ;
        $database->setQuery("DELETE FROM #__rem_review WHERE #__rem_review.id=" . $id[$i] . ";");
        $database->query();
        echo $database->getErrorMsg();
    }
}

function edit_manage_review($option, $review_id) {
    global $database;
    if (count($review_id) > 1) {
        echo "<script> alert('". _REALESTATE_MANAGER_ADMIN_ONE_REVIEW_ALERT ."'); window.history.go(-1); </script>\n";
    } else {
        $database->setQuery("SELECT * FROM #__rem_review WHERE id=" . $review_id[0] . " ");
        $review = $database->loadObjectList();
        echo $database->getErrorMsg();
        HTML_realestatemanager :: edit_manage_review($option, $review);
    }
}

//*************************************************************************************************************
//*********************************   end for manage reviews   ************************************************
//*************************************************************************************************************


function showInfo($option, $bid) {
    if (is_array($bid) && count($bid) > 0)
        $bid = $bid[0];
    echo "Test: " . $bid;
}

function decline_rent_requests($option, $bids) {
    global $database, $realestatemanager_configuration;
    $datas = array();
    foreach ($bids as $bid) {
        $rent_request = new mosRealEstateManager_rent_request($database);
        $rent_request->load($bid);
        $tmp = $rent_request->decline();
        if ($tmp != null) {
            echo "<script> alert('" . $tmp . "'); window.history.go(-1); </script>\n";
            exit();
        }
        foreach ($datas as $c => $data) {
            if ($rent_request->user_email == $data['email']) {
                $datas[$c]['ids'][] = $rent_request->fk_houseid;
                continue 2;
            }
        }
        $datas[] = array('email' => $rent_request->user_email,
         'name' => $rent_request->user_name, 'id' => $rent_request->fk_houseid);
    }
    mosRedirect("index.php?option=$option&task=rent_requests");
}

function accept_rent_requests($option, $bids) {
    global $database, $realestatemanager_configuration;
    $datas = array();
    foreach ($bids as $bid) {
   
              $rent_request = new mosRealEstateManager_rent_request($database);             
              $rent_request->load($bid);
              $tmp = $rent_request->accept();
      
              if ($tmp != null) {
                  echo "<script> alert('" . $tmp . "'); window.history.go(-1); </script>\n";
                  exit();
              }
            
              foreach ($datas as $c => $data) {
                  if ($rent_request->user_email == $data['email']) {
                      $datas[$c]['ids'][] = $rent_request->fk_houseid;
                      continue 2;
                  }
              }
              $datas[] = array('email' => $rent_request->user_email,
               'name' => $rent_request->user_name, 'id' => $rent_request->fk_houseid,
                'fk_userid' => $rent_request->fk_userid);
            }
    mosRedirect("index.php?option=$option&task=rent_requests");
}

function accept_buying_requests($option, $bids) {
    global $database, $realestatemanager_configuration;
    foreach ($bids as $bid) {
        $buying_request = new mosRealEstateManager_buying_request($database);
        $buying_request->load($bid);
        $datas[] = array('name' => $buying_request->customer_name,
            'email' => $buying_request->customer_email,
            'id' => $buying_request->fk_houseid
        );
        $buying_request->delete($bid);
        if ($tmp != null) {
            echo "<script> alert('" . $tmp . "'); window.history.go(-1); </script>\n";
            exit();
        }
    }
    mosRedirect("index.php?option=$option&task=buying_requests");
}

function decline_buying_requests($option, $bids) {
    global $database, $realestatemanager_configuration;
    $datas = array();
    foreach ($bids as $bid) {
        $buying_request = new mosRealEstateManager_buying_request($database);
        $buying_request->load($bid);
        $datas[] = array('name' => $buying_request->customer_name,
            'email' => $buying_request->customer_email,
            'id' => $buying_request->fk_houseid
        );
        $tmp = $buying_request->decline();

        if ($tmp != null) {
            echo "<script> alert('" . $tmp . "'); window.history.go(-1); </script>\n";
            exit();
        }
    }
    mosRedirect("index.php?option=$option&task=buying_requests");
}

function rent_requests($option, $bid) {

    global $database, $mainframe, $mosConfig_list_limit;
    $mainframe = JFactory::getApplication();

    $limit = $mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mosConfig_list_limit);
    $limitstart = $mainframe->getUserStateFromRequest("view{$option}limitstart", 'limitstart', 0);

    $database->setQuery("SELECT count(*) FROM #__rem_houses AS a" .
            " \nLEFT JOIN #__rem_rent_request AS l" .
            " \nON l.fk_houseid = a.id" .
            " \nWHERE l.status = 0");
    $total = $database->loadResult();
    echo $database->getErrorMsg();

    $pageNav = new JPagination($total, $limitstart, $limit); // for J 1.6

    $database->setQuery("SELECT * FROM #__rem_houses AS a" .
            " \nLEFT JOIN #__rem_rent_request AS l" .
            " \nON l.fk_houseid = a.id" .
            " \nWHERE l.status = 0" .
            " \nORDER BY l.id DESC" .
            " \nLIMIT $pageNav->limitstart,$pageNav->limit;");
    $rent_requests = $database->loadObjectList();
    echo $database->getErrorMsg();
    $total = $database->loadResult();

    foreach ($rent_requests as $request) {
        if($request->associate_house){
            if($assoc_rem = getAssociateHouses($request->fk_houseid)){
                $database->setQuery("SELECT group_concat(distinct a.htitle) FROM #__rem_houses AS a" .
                  "\n LEFT JOIN #__rem_rent_request AS l ON l.fk_houseid = a.id" .
                  "\n WHERE a.id in ($assoc_rem) AND a.id != $request->fk_houseid");
                $request->title_assoc = $database->loadResult(); 
            }
        }
    }  

    HTML_realestatemanager :: showRequestRentHouses($option, $rent_requests, $pageNav);
}

function buying_requests($option) {

    global $database, $mainframe, $mosConfig_list_limit;
    $mainframe = JFactory::getApplication();

    $limit = $mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mosConfig_list_limit);
    $limitstart = $mainframe->getUserStateFromRequest("view{$option}limitstart", 'limitstart', 0);

    $database->setQuery("SELECT count(*) FROM #__rem_houses AS a" .
            "\nLEFT JOIN #__rem_buying_request AS s" .
            "\nON s.fk_houseid = a.id" .
            "\nWHERE s.status = 0");
    $total = $database->loadResult();
    echo $database->getErrorMsg();

    $pageNav = new JPagination($total, $limitstart, $limit); // for J 1.6

    $database->setQuery("SELECT * FROM #__rem_houses AS a" .
            "\nLEFT JOIN #__rem_buying_request AS s" .
            "\nON s.fk_houseid = a.id" .
            "\nWHERE s.status = 0" .
            "\nORDER BY s.customer_name" .
            "\nLIMIT $pageNav->limitstart,$pageNav->limit;");
    $rent_requests = $database->loadObjectList();
    echo $database->getErrorMsg();
    HTML_realestatemanager ::showRequestBuyingHouses($option, $rent_requests, $pageNav);
}

/**
 * Compiles a list of records
 * @param database - A database connector object
 * select categories
 */
function showHouses($option) {
    global $database, $mainframe, $mosConfig_list_limit;
    $mainframe = JFactory::getApplication();

    $limit = $mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mosConfig_list_limit);
    $limitstart = $mainframe->getUserStateFromRequest("view{$option}limitstart", 'limitstart', 0);
    $catid = $mainframe->getUserStateFromRequest("catid{$option}", 'catid', '-1'); //old 0
    $language_owner = $mainframe->getUserStateFromRequest("language{$option}", 'language', '-1'); 
    $rent = $mainframe->getUserStateFromRequest("rent{$option}", 'rent', '-1'); //add nik
    $pub = $mainframe->getUserStateFromRequest("pub{$option}", 'pub', '-1'); //add nik
    $owner = $mainframe->getUserStateFromRequest("owner{$option}", 'owner', '-1');
    $search = $mainframe->getUserStateFromRequest("search{$option}", 'search', '');
    //$search = $database->getEscaped(trim(strtolower($search)));

    $where = array();

    if ($rent == "rent") {
        array_push($where, "a.fk_rentid <> 0");
    } else if ($rent == "not_rent") {
        array_push($where, "a.fk_rentid = 0");
    }
    if ($pub == "pub") {
        array_push($where, "a.published = 1");
    } else if ($pub == "not_pub") {
        array_push($where, "a.published = 0");
    }
    if ($owner != -1)
        array_push($where, "a.owner_id = '$owner'");
    if ($catid > 0) {
        array_push($where, "hc.idcat='$catid'");
    }
    if ($language_owner != '0' and $language_owner != '*'and $language_owner != '-1' )
    {
        array_push($where, "a.language='$language_owner'");        
    }
    if ($search) {
        array_push($where, "(LOWER(a.htitle) LIKE '%$search%' OR " .
         " LOWER(a.hlocation) LIKE '%$search%' OR LOWER(a.houseid) LIKE '%$search%')");
    }
    $database->setQuery("SELECT count(*) FROM #__rem_houses AS a" .
            "\nLEFT JOIN #__rem_categories AS hc ON a.id=hc.iditem" .
            "\nLEFT JOIN #__rem_rent AS l" .
            "\nON a.fk_rentid = l.id" .
            (count($where) ? "\nWHERE " . implode(' AND ', $where) : ""));

    $total = $database->loadResult();
    echo $database->getErrorMsg();

    $pageNav = new JPagination($total, $limitstart, $limit);
    $selectstring = "SELECT a.*, GROUP_CONCAT(DISTINCT(cc.title) SEPARATOR ',') AS category, 
            l.id as rentid, l.rent_from as rent_from, l.rent_return as rent_return,
            l.rent_until as rent_until, u.name AS editor, ue.name AS editor1" .
            "\nFROM #__rem_houses AS a" .
            "\nLEFT JOIN #__rem_categories AS hc ON hc.iditem = a.id" .
            "\nLEFT JOIN #__rem_main_categories AS cc ON cc.id = hc.idcat" .
            "\nLEFT JOIN #__rem_rent AS l ON l.fk_houseid = a.id  and l.rent_return is null " .
            "\nLEFT JOIN #__users AS u ON u.id = a.owner_id" .
            "\nLEFT JOIN #__users AS ue ON ue.id = a.checked_out" .
            (count($where) ? "\nWHERE " . implode(' AND ', $where) : "") .
            "\nGROUP BY a.id" .
            "\nORDER BY a.htitle " .
            "\nLIMIT $pageNav->limitstart,$pageNav->limit;";
    $database->setQuery($selectstring);
    $rows = $database->loadObjectList();

    if (version_compare(JVERSION, "3.0.0", "lt"))
        $curdate = strtotime(JFactory::getDate()->toMySQL());
    else
        $curdate = strtotime(JFactory::getDate()->toSQL());
    foreach ($rows as $row) {
        $check = strtotime($row->checked_out_time);
        $remain = 7200 - ($curdate - $check);
        if (($remain <= 0) && ($row->checked_out != 0)) {
            $item = new mosRealEstateManager($database);
            $item->checkin($row->id);
        }
    }
    if ($database->getErrorNum()) {
        echo $database->stderr();
        return false;
    }

    // get list of categories
    /*
     * select list treeSelectList
     */

    $categories[] = mosHTML :: makeOption('0', _REALESTATE_MANAGER_LABEL_SELECT_CATEGORIES);
    $categories[] = mosHTML :: makeOption('-1', _REALESTATE_MANAGER_LABEL_SELECT_ALL_CATEGORIES);
//*************   begin add for sub category in select in manager houses   *************
    $options = $categories;
    $id = 0; //$categories_array;
    $list = CAT_Utils::categoryArray();

    $cat = new mainRealEstateCategories($database);
    $cat->load($id);

    $this_treename = '';
    foreach ($list as $item) {
        if ($this_treename) {
            if ($item->id != $cat->id && strpos($item->treename, $this_treename) === false) {
                $options[] = mosHTML::makeOption($item->id, $item->treename);
            }
        } else {
            if ($item->id != $cat->id) {
                $options[] = mosHTML::makeOption($item->id, $item->treename);
            } else {
                $this_treename = "$item->treename/";
            }
        }
    }

    $clist = mosHTML::selectList($options, 'catid',
     'class="inputbox" size="1" onchange="document.adminForm.submit();"',
      'value', 'text', $catid); //new nik edit
//*****  end add for sub category in select in manager houses   **********

    $rentmenu[] = mosHTML :: makeOption('0', _REALESTATE_MANAGER_LABEL_SELECT_TO_RENT);
    $rentmenu[] = mosHTML :: makeOption('-1', _REALESTATE_MANAGER_LABEL_SELECT_ALL_RENT);
    $rentmenu[] = mosHTML :: makeOption('not_rent', _REALESTATE_MANAGER_LABEL_SELECT_NOT_RENT);
    $rentmenu[] = mosHTML :: makeOption('rent', _REALESTATE_MANAGER_LABEL_SELECT_RENT);

    $rentlist = mosHTML :: selectList($rentmenu, 'rent',
     'class="inputbox" size="1" onchange="document.adminForm.submit();"',
      'value', 'text', $rent);

    $pubmenu[] = mosHTML :: makeOption('0', _REALESTATE_MANAGER_LABEL_SELECT_TO_PUBLIC);
    $pubmenu[] = mosHTML :: makeOption('-1', _REALESTATE_MANAGER_LABEL_SELECT_ALL_PUBLIC);
    $pubmenu[] = mosHTML :: makeOption('not_pub', _REALESTATE_MANAGER_LABEL_SELECT_NOT_PUBLIC);
    $pubmenu[] = mosHTML :: makeOption('pub', _REALESTATE_MANAGER_LABEL_SELECT_PUBLIC);

    $publist = mosHTML :: selectList($pubmenu, 'pub',
     'class="inputbox" size="1" onchange="document.adminForm.submit();"',
      'value', 'text', $pub);

    $ownermenu[] = mosHTML :: makeOption('-1', _REALESTATE_MANAGER_LABEL_SELECT_ALL_USERS);
    $selectstring = "SELECT id,name FROM  #__users GROUP BY name ORDER BY id ";

    $database->setQuery($selectstring);
    $owner_list = $database->loadObjectList();

    if ($database->getErrorNum()) {
        echo $database->stderr();
        return false;
    }
    $i = 2;
    foreach ($owner_list as $item) {
        $ownermenu[$i] = mosHTML::makeOption($item->id, $item->name);
        $i++;
    }

    $ownerlist = mosHTML :: selectList($ownermenu,
     'owner', 'class="inputbox" size="1" onchange="document.adminForm.submit();"',
      'value', 'text', $owner);
    
    $language = array();
    $selectlanguage = "SELECT `language` FROM `#__rem_houses` WHERE language <> '*' GROUP BY language ";

    $database->setQuery($selectlanguage);
    $languages = $database->loadObjectList();
    $language_list[]= mosHTML :: makeOption('0', _REALESTATE_MANAGER_LABEL_SELECT_LANGUAGE);
    
    foreach ($languages as $language) {
        $language_list[] = mosHTML::makeOption($language->language, $language->language);
        
    }
    $language = mosHTML :: selectList($language_list, 'language',
     'class="inputbox input-medium" size="1" onchange="document.adminForm.submit();"',
      'value', 'text', $language_owner);

    
    HTML_realestatemanager :: showHouses($option, $rows, $clist,$language,
     $rentlist, $publist, $ownerlist, $search, $pageNav);
}

/**
 * Compiles information to add or edit houses
 * @param integer bid The unique id of the record to edit (0 if new)
 * @param array option the current options
 */
function rentPrice($bid,$rent_from,$rent_until,$special_price,$comment_price,
  $currency_spacial_price){
   rentPriceREM($bid,$rent_from,$rent_until,$special_price,$comment_price,
     $currency_spacial_price);
}


function clonHouse($bid, $option) {
    global $database, $my, $mosConfig_live_site, $realestatemanager_configuration;
    if(count($bid)>1){
      echo "<script> alert('". _REALESTATE_MANAGER_ADMIN_CLON_ALERT .
        "'); window.history.go(-1); </script>\n";
              exit();         
    }
    
    $bid = $bid[0];
    
    $house_true = new mosRealEstateManager($database);
    // load the row from the db table
    $house_true->load(intval($bid));
    
    $house_true->setCatIds();
    $house = clone $house_true;
    $house->id = null;
    $house->owner_id = null;  
    $house->store(); 
    $house->id_true = $house_true->id;
    $house->saveCatIds($house->catid);

    $numeric_houseids = Array();
    
    
     
    
    // get list of categories
    $categories = array();

    $query = "SELECT  id ,name, parent_id as parent"
            . "\n FROM #__rem_main_categories"
            . "\n WHERE section='com_realestatemanager'"
            . "\n AND published > 0"
            . "\n ORDER BY parent_id, ordering";

    $database->setQuery($query);
    $rows = $database->loadObjectList();

    // establish the hierarchy of the categories
    $children = array();
    // first pass - collect children
    foreach ($rows as $v) {
        $pt = $v->parent;
        $list = @$children[$pt] ? $children[$pt] : array();
        array_push($list, $v);
        $children[$pt] = $list;
    }

    // second pass - get an indent list of the items
    $list = houseLibraryTreeRecurse(0, '', array(), $children);

    foreach ($list as $i => $item) {
        $item->text = $item->treename;
        $item->value = $item->id;
        $list[$i] = $item;
    }
    $categories = array_merge($categories, $list);

    //if (count($categories) <= 1) {
    //mosRedirect("index.php?option=com_realestatemanager&section=categories",
    //_REALESTATE_MANAGER_ADMIN_IMPEXP_ADD);
    //}
    if (trim($house->id) != "")
        $house->setCatIds();
    $clist = mosHTML :: selectList($categories, 'catid[]',
     'class="inputbox" multiple', 'value', 'text', $house->catid);

    //get Rating
    $retVal2 = mosRealEstateManagerOthers :: getRatingArray();
    $rating = null;
    for ($i = 0, $n = count($retVal2); $i < $n; $i++) {
        $help = $retVal2[$i];
        $rating[] = mosHTML :: makeOption($help[0], $help[1]);
    }

    //delete ehouse?
    $help = str_replace($mosConfig_live_site, "", $house->edok_link);
    $delete_ehouse_yesno[] = mosHTML :: makeOption($help, _REALESTATE_MANAGER_YES);
    $delete_ehouse_yesno[] = mosHTML :: makeOption('0', _REALESTATE_MANAGER_NO);
    $delete_edoc = mosHTML :: RadioList($delete_ehouse_yesno, 'delete_edoc',
       'class="inputbox"', '0', 'value', 'text');

    // fail if checked out not by 'me'
    if ($house->checked_out && $house->checked_out <> $my->id) {
        mosRedirect("index.php?option=$option", _REALESTATE_MANAGER_IS_EDITED);
    }

    if ($bid) {
        $house->checkout($my->id);
    } else {
        // initialise new record
        $house->published = 0;
        $house->approved = 0;
    }

//*****************************   begin for reviews **************************//
    $database->setQuery("select a.* from #__rem_review a " .
            " WHERE a.fk_houseid=" . $bid . " ORDER BY date ;");
    $reviews = $database->loadObjectList();
//**********************   end for reviews   *****************************//

    // select list for listing type
    $listing_type[] = mosHtml::makeOption(0, _REALESTATE_MANAGER_OPTION_SELECT);
    $listing_type[] = mosHtml::makeOption(1, _REALESTATE_MANAGER_OPTION_FOR_RENT);
    $listing_type[] = mosHtml::makeOption(2, _REALESTATE_MANAGER_OPTION_FOR_SALE);
    $listing_type_list = mosHTML :: selectList($listing_type, 'listing_type', 
      'class="inputbox" size="1"', 'value', 'text', $house->listing_type);

     // select list for listing status
    $listing_status[] = mosHtml::makeOption(0, _REALESTATE_MANAGER_OPTION_SELECT);
    $listing_status1 = explode(',', _REALESTATE_MANAGER_OPTION_LISTING_STATUS);
    $i = 1;
    foreach ($listing_status1 as $listing_status2) {
        $listing_status[] = mosHtml::makeOption($i, $listing_status2);
        $i++;
    }
    $listing_status_list = mosHTML :: selectList($listing_status, 'listing_status', 
      'class="inputbox" size="1"', 'value', 'text', $house->listing_status);

    // select list for property type
    $property_type[] = mosHtml::makeOption(0, _REALESTATE_MANAGER_OPTION_SELECT);
    $property_type1 = explode(',', _REALESTATE_MANAGER_OPTION_PROPERTY_TYPE);
    $i = 1;
    foreach ($property_type1 as $property_type2) {
        $property_type[] = mosHtml::makeOption($i, $property_type2);
        $i++;
    }
    $property_type_list = mosHTML :: selectList($property_type, 'property_type', 
      'class="inputbox" size="1"', 'value', 'text', $house->property_type);

    if (trim($house->id) != "") {
        $query = "select main_img from #__rem_photos WHERE fk_houseid='$house->id' order by img_ordering,id";
        $database->setQuery($query);
        $house_temp_photos = $database->loadObjectList();

        foreach ($house_temp_photos as $house_temp_photo) {
            $house_photos[] = array($house_temp_photo->main_img, 
              rem_picture_thumbnail($house_temp_photo->main_img, 
                $realestatemanager_configuration['foto']['high'], 
                $realestatemanager_configuration['foto']['width']));
        }

        $query = "select image_link from #__rem_houses WHERE id='$house->id'";
        $database->setQuery($query);
        $house_photo = $database->loadResult();

        if ($house_photo != '')
            $house_photo = array($house_photo, rem_picture_thumbnail($house_photo, 
              $realestatemanager_configuration['foto']['high'],
               $realestatemanager_configuration['foto']['width']));
    }
///////////START check video/audio files\\\\\\\\\\\\\\\\\\\\\\
    $tracks = array();
    $videos = array();
    if (!empty($house->id)) { 
      $database->setQuery("SELECT * FROM #__rem_video_source WHERE fk_house_id=" . $bid);
      $videos = $database->loadObjectList();
    }
    $youtube = new stdClass();
    for ($i = 0;$i < count($videos);$i++) {
      if (!empty($videos[$i]->youtube)) {
        $youtube->code = $videos[$i]->youtube;
        $youtube->id = $videos[$i]->id;
        break;
      }
    }
    if (!empty($house->id)) { //check video file
      $database->setQuery("SELECT * FROM #__rem_track_source WHERE fk_house_id=" . $bid);
      $tracks = $database->loadObjectList();
    }
    if(count($videos)){
        for($i = 0; $i < count($videos); $i++){
            $query = "INSERT INTO
                        `#__rem_video_source`
                      SET
                        `fk_house_id` = ".$house->id.",
                        `sequence_number` = '".$videos[$i]->sequence_number."',
                        `src` = '".$videos[$i]->src."',
                        `type` = '".$videos[$i]->type."',
                        `media` = '".$videos[$i]->media."',
                        `youtube` = '".$videos[$i]->youtube."'";  
            $database->setQuery($query);
            $database->query();
        }
    }
    if(count($tracks)){
        for($i = 0; $i < count($tracks); $i++){
            $query = "INSERT INTO
                        `#__rem_track_source`
                      SET
                        `fk_house_id` = ".$house->id.",
                        `sequence_number` = '".$tracks[$i]->sequence_number."',
                        `src` = '".$tracks[$i]->src."',
                        `kind` = '".$tracks[$i]->kind."',
                        `scrlang` = '".$tracks[$i]->scrlang."',
                        `label` = '".$tracks[$i]->label."'";
            $database->setQuery($query);
            $database->query();
        }
    }
////////////////////////////////END check video/audio files \\\\\\\\\\\\\\\\\\
    if (trim($bid) != "") {
        $query = "select * from #__rem_rent_sal WHERE fk_houseid='$bid' order by `yearW`, `monthW`";
        $database->setQuery($query);
        $house_rent_sal = $database->loadObjectList();
     
        if(count($house_rent_sal)){
            for($i = 0; $i < count($house_rent_sal); $i++){
                $query = "INSERT INTO
                            `#__rem_rent_sal`
                          SET
                            `fk_houseid` = ".$house->id.",
                            `monthW` = '".$house_rent_sal[$i]->monthW."',
                            `yearW` = '".$house_rent_sal[$i]->yearW."',
                            `week` = '".$house_rent_sal[$i]->week."',
                            `weekend` = '".$house_rent_sal[$i]->weekend."',
                            `price_from` = '".$house_rent_sal[$i]->price_from."',
                            `price_to` = '".$house_rent_sal[$i]->price_to."',    
                            `special_price` = ".$house_rent_sal[$i]->special_price.",
                            `comment_price` = '".$house_rent_sal[$i]->comment_price."',
                            `priceunit` = '".$house_rent_sal[$i]->priceunit."'";  
                $database->setQuery($query);
                $database->query();
            }
        }    
    }

    $query = "SELECT * ";
    $query .= "FROM #__rem_feature as f ";
    $query .= "WHERE f.published = 1 ";
    $query .= "ORDER BY f.categories";
    $database->setQuery($query);
    $house_feature = $database->loadObjectList();

    for ($i = 0; $i < count($house_feature); $i++) {
        $feature = "";
        if (!empty($house->id_true)) {
            $query = "SELECT id ";
            $query .= "FROM #__rem_feature_houses ";
            $query .= "WHERE fk_featureid =" . $house_feature[$i]->id .
             " AND fk_houseid =" . $house->id_true;
            $database->setQuery($query);
            $feature = $database->loadResult();
            if ($feature)
                $house_feature[$i]->check = 1; else
                $house_feature[$i]->check = 0;
        } else {
            $house_feature[$i]->check = 0;
        }
    }

    $currencys = explode(';', $realestatemanager_configuration['currency']);
    foreach ($currencys as $row) {
        if ($row != '') {
            $row = explode("=", $row);
            $temp_currency[] = mosHTML::makeOption($row[0], $row[0]);
        }
    }
    $currency = mosHTML :: selectList($temp_currency, 'priceunit', 'class="inputbox" size="1"',
       'value', 'text', $house->priceunit);
    $currency_spacial_price = mosHTML :: selectList($temp_currency, 'currency_spacial_price',
       'class="inputbox" size="1"', 'value', 'text', $house->priceunit);
    $query = "SELECT lang_code, title FROM #__languages";
    $database->setQuery($query);
    $languages = $database->loadObjectList();

    $languages_row[] = mosHTML::makeOption('*', 'All');
    if (version_compare(JVERSION, '2.5', 'ge')) {
        foreach ($languages as $language) {
            $languages_row[] = mosHTML::makeOption($language->lang_code, $language->title);
        }
    }
    $languages = mosHTML :: selectList($languages_row, 'language', 'class="inputbox" size="1"',
       'value', 'text', $house->language);

    $associate_house = null;
    for ($i = 6; $i <= 10; $i++) {
        $extraOption = '';
        $extraOption[] = mosHtml::makeOption(0, _REALESTATE_MANAGER_OPTION_SELECT);
        $name = "_REALESTATE_MANAGER_EXTRA" . $i . "_SELECTLIST";
        $extra = explode(',', constant($name));
        $j = 1;
        foreach ($extra as $extr) {
            $extraOption[] = mosHTML::makeOption($j, $extr);
            $j++;
        }

        switch ($i) {
            case 6:
                $extraSelect = $house->extra6;
                break;
            case 7:
                $extraSelect = $house->extra7;
                break;
            case 8:
                $extraSelect = $house->extra8;
                break;
            case 9:
                $extraSelect = $house->extra9;
                break;
            case 10:
                $extraSelect = $house->extra10;
                break;
        }
        $extra_list[] = mosHTML :: selectList($extraOption, 'extra' . $i, 'class="inputbox" size="1"', 
          'value', 'text', $extraSelect);
    }
    HTML_realestatemanager :: editHouse($option, $house, $clist, $ratinglist, $delete_edoc,$videos,$youtube, $tracks, 
      $reviews, $listing_status_list, $property_type_list, $listing_type_list, $house_photo,$house_temp_photos,
      $house_photos, $house_rent_sal, $house_feature, $currency, $languages, $extra_list,
      $currency_spacial_price,$associate_house);

    
}
function editHouse($option, $bid) {

    global $database, $my, $mosConfig_live_site, $realestatemanager_configuration;

    $house = new mosRealEstateManager($database);
    // load the row from the db table
    $house->load(intval($bid));

    $numeric_houseids = Array();
/************************************    language     *********************/
    $associateArray = array();
    if($bid){
        $call_from = 'backend';
        $associateArray = edit_house_associate($house,$call_from);
    }
/**************************************************************************/
    // get list of categories
    $categories = array();

    $query = "SELECT  id ,name, language, parent_id as parent"
            . "\n FROM #__rem_main_categories"
            . "\n WHERE section='com_realestatemanager'"
            . "\n AND published > 0"
            . "\n ORDER BY parent_id, ordering";

    $database->setQuery($query);
    $rows = $database->loadObjectList();

    // establish the hierarchy of the categories
    $children = array();
    // first pass - collect children
    foreach ($rows as $v) {
        $pt = $v->parent;
        $list = @$children[$pt] ? $children[$pt] : array();
        array_push($list, $v);
        $children[$pt] = $list;
    }

    // second pass - get an indent list of the items
    $list = houseLibraryTreeRecurse(0, '', array(), $children);

    foreach ($list as $i => $item) {
        if($item->language != "*"){
        $item->text = $item->treename . " (" . $item->language . ")";
        } else {
        $item->text = $item->treename;
        }
        $item->value = $item->id;
        $list[$i] = $item;
    }
    $categories = array_merge($categories, $list);

    //if (count($categories) <= 1) {
    //mosRedirect("index.php?option=com_realestatemanager&section=categories",
    //_REALESTATE_MANAGER_ADMIN_IMPEXP_ADD);
    //}
    if (trim($house->id) != "")
        $house->setCatIds();
    $clist = mosHTML :: selectList($categories, 'catid[]', 'class="inputbox" multiple',
     'value', 'text', $house->catid);

    //get Rating
    $retVal2 = mosRealEstateManagerOthers :: getRatingArray();
    $rating = null;
    for ($i = 0, $n = count($retVal2); $i < $n; $i++) {
        $help = $retVal2[$i];
        $rating[] = mosHTML :: makeOption($help[0], $help[1]);
    }

    //delete ehouse?
    $help = str_replace($mosConfig_live_site, "", $house->edok_link);
    $delete_ehouse_yesno[] = mosHTML :: makeOption($help, _REALESTATE_MANAGER_YES);
    $delete_ehouse_yesno[] = mosHTML :: makeOption('0', _REALESTATE_MANAGER_NO);
    $delete_edoc = mosHTML :: RadioList($delete_ehouse_yesno,
     'delete_edoc', 'class="inputbox"', '0', 'value', 'text');

    // fail if checked out not by 'me'
    if ($house->checked_out && $house->checked_out <> $my->id) {
        mosRedirect("index.php?option=$option", _REALESTATE_MANAGER_IS_EDITED);
    }

    if ($bid) {
        $house->checkout($my->id);
    } else {
        // initialise new record
        $house->published = 0;
        $house->approved = 0;
    }

//*****************************   begin for reviews **************************//
    if($bid != 0 ){
      $database->setQuery("select a.* from #__rem_review a " .
              " WHERE a.fk_houseid=" . $bid . " ORDER BY date ;");
      $reviews = $database->loadObjectList();
    } else {
       $reviews = array(); 
    }
//**********************   end for reviews   *****************************//
    // select list for listing type
    $listing_type[] = mosHtml::makeOption(0, _REALESTATE_MANAGER_OPTION_SELECT);
    $listing_type[] = mosHtml::makeOption(1, _REALESTATE_MANAGER_OPTION_FOR_RENT);
    $listing_type[] = mosHtml::makeOption(2, _REALESTATE_MANAGER_OPTION_FOR_SALE);
    $listing_type_list = mosHTML :: selectList($listing_type,
     'listing_type', 'class="inputbox" size="1"', 'value', 'text', $house->listing_type);

    // select list for listing status
    $listing_status[] = mosHtml::makeOption(0, _REALESTATE_MANAGER_OPTION_SELECT);
    $listing_status1 = explode(',', _REALESTATE_MANAGER_OPTION_LISTING_STATUS);
    $i = 1;
    foreach ($listing_status1 as $listing_status2) {
        $listing_status[] = mosHtml::makeOption($i, $listing_status2);
        $i++;
    }
    $listing_status_list = mosHTML :: selectList($listing_status,
     'listing_status', 'class="inputbox" size="1"', 'value', 'text', $house->listing_status);

    // select list for property type
    $property_type[] = mosHtml::makeOption(0, _REALESTATE_MANAGER_OPTION_SELECT);
    $property_type1 = explode(',', _REALESTATE_MANAGER_OPTION_PROPERTY_TYPE);
    $i = 1;
    foreach ($property_type1 as $property_type2) {
        $property_type[] = mosHtml::makeOption($i, $property_type2);
        $i++;
    }
    $property_type_list = mosHTML :: selectList($property_type, 'property_type',
     'class="inputbox" size="1"', 'value', 'text', $house->property_type);

   if (trim($house->id) != "") {
        $query = "select main_img from #__rem_photos WHERE fk_houseid='$house->id' order by img_ordering,id";
        $database->setQuery($query);
        $house_temp_photos = $database->loadObjectList();

        foreach ($house_temp_photos as $house_temp_photo) {
            $house_photos[] = array($house_temp_photo->main_img,
             rem_picture_thumbnail($house_temp_photo->main_img, '150', '150'));
        }

        $query = "select image_link from #__rem_houses WHERE id='$house->id'";
        $database->setQuery($query);
        $house_photo = $database->loadResult();

        if ($house_photo != '')
            $house_photo = array($house_photo, rem_picture_thumbnail($house_photo, '150', '150'));
    }
    if (trim($house->id) != "") {
        $query = "select * from #__rem_rent_sal WHERE fk_houseid='$house->id' order by `yearW`, `monthW`";
        $database->setQuery($query);
        $house_rent_sal = $database->loadObjectList();
    }

///////////START check video/audio files\\\\\\\\\\\\\\\\\\\\\\
    $tracks = array();
    $videos = array();
    $youtubeId = "";
    if (!empty($house->id)) { 
      $database->setQuery("SELECT * FROM #__rem_video_source WHERE fk_house_id=" . $house->id);
      $videos = $database->loadObjectList();
    }
    $youtube = new stdClass();
    for ($i = 0;$i < count($videos);$i++) {
      if (!empty($videos[$i]->youtube)) {
        $youtube->code = $videos[$i]->youtube;
        $youtube->id = $videos[$i]->id;
        break;
      }
    }
    if (!empty($house->id)) { //check video file
      $database->setQuery("SELECT * FROM #__rem_track_source WHERE fk_house_id=" . $house->id);
      $tracks = $database->loadObjectList();
    }
////////////////////////////////END check video/audio files \\\\\\\\\\\\\\\\\\
    $query = "SELECT * ";
    $query .= "FROM #__rem_feature as f ";
    $query .= "WHERE f.published = 1 ";
    $query .= "ORDER BY f.categories";
    $database->setQuery($query);
    $house_feature = $database->loadObjectList();

    

    for ($i = 0; $i < count($house_feature); $i++) {
        $feature = "";
        if (!empty($house->id)) {
            $query = "SELECT id ";
            $query .= "FROM #__rem_feature_houses ";
            $query .= "WHERE fk_featureid =" . $house_feature[$i]->id .
             " AND fk_houseid =" . $house->id;
            $database->setQuery($query);
            $feature = $database->loadResult();
             
            if ($feature)
                $house_feature[$i]->check = 1; else
                $house_feature[$i]->check = 0;
               
        } else {
            $house_feature[$i]->check = 0;
        }
    }

    $currencys = explode(';', $realestatemanager_configuration['currency']);
    foreach ($currencys as $row) {
        if ($row != '') {
            $row = explode("=", $row);
//            if ($house->priceunit == $row[0])
//                $house->price = $row[1];
            $temp_currency[] = mosHTML::makeOption($row[0], $row[0]);
        }
    }
    $currency = mosHTML :: selectList($temp_currency, 'priceunit',
     'class="inputbox" size="1"', 'value', 'text', $house->priceunit);
    $currency_spacial_price = mosHTML :: selectList($temp_currency,
     'currency_spacial_price', 'class="inputbox" size="1"', 'value', 'text', $house->priceunit);



    $query = "SELECT lang_code, title FROM #__languages";
    $database->setQuery($query);
    $languages = $database->loadObjectList();

    $languages_row[] = mosHTML::makeOption('*', 'All');
    if (version_compare(JVERSION, '2.5', 'ge')) {
        foreach ($languages as $language) {
            $languages_row[] = mosHTML::makeOption($language->lang_code, $language->title);
        }
    }
    $languages = mosHTML :: selectList($languages_row, 'language',
     'class="inputbox" size="1"', 'value', 'text', $house->language);

    for ($i = 6; $i <= 10; $i++) {
        $extraOption = '';
        $extraOption[] = mosHtml::makeOption(0, _REALESTATE_MANAGER_OPTION_SELECT);
        $name = "_REALESTATE_MANAGER_EXTRA" . $i . "_SELECTLIST";
        $extra = explode(',', constant($name));
        $j = 1;
        foreach ($extra as $extr) {
            $extraOption[] = mosHTML::makeOption($j, $extr);
            $j++;
        }

        switch ($i) {
            case 6:
                $extraSelect = $house->extra6;
                break;
            case 7:
                $extraSelect = $house->extra7;
                break;
            case 8:
                $extraSelect = $house->extra8;
                break;
            case 9:
                $extraSelect = $house->extra9;
                break;
            case 10:
                $extraSelect = $house->extra10;
                break;
        }
        $extra_list[] = mosHTML :: selectList($extraOption, 'extra' . $i,
         'class="inputbox" size="1"', 'value', 'text', $extraSelect);
    }
    HTML_realestatemanager :: editHouse(
            $option, 
            $house, 
            $clist, 
            $ratinglist, 
            $delete_edoc,
            $videos,$youtube, $tracks, 
            $reviews,
            $listing_status_list, 
            $property_type_list, 
            $listing_type_list, 
            $house_photo,
            $house_temp_photos, 
            $house_photos, 
            $house_rent_sal, 
            $house_feature, 
            $currency, 
            $languages, 
            $extra_list,
            $currency_spacial_price, 
            $associateArray);
}

function getMonth($month) {

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



function saveHouse($option, $task) {

    global $database, $my, $mosConfig_absolute_path, $mosConfig_live_site, $realestatemanager_configuration;
    // checking how the other info should be provided
    $house = new mosRealEstateManager($database);
    $post = JRequest::get('post', JREQUEST_ALLOWHTML);
    if (!$house->bind($post)) {
        echo "<script> alert('" . addslashes($house->getError()) .
         "'); window.history.go(-1); </script>\n";
        exit();
    }

    if (($_POST['owner_id'] == 0))
        $house->owner_id = $_POST['owner_id'];
    if(($_POST['owner_id'] == '')){
        $house->owner_id = $my->id;
    }
        
    $house->contacts = $_POST['contacts'];
    
/*************Call function to Save changes for associated houses*******************/
    save_house_associate();
/***********************************************************************************/

    if (isset($_POST['yearW']) || isset($_POST['monthW'])) {
        $id = $_POST['id'];
        $monthW = $_POST['monthW'];
        $yearW = $_POST['yearW'];
        $week = $_POST['week'];
        $midweek = $_POST['midweek'];
        $weekend = $_POST['weekend'];
        for ($i = 0; $i < count($_POST['yearW']); $i++) {
            //if (($week[$i]!='') and ($weekend[$i]!='') and ($midweek[$i]!='')) {
            $database->setQuery(
              "INSERT INTO #__rem_rent_sal (fk_houseid, monthW, yearW, week, weekend, midweek) VALUES ("
               . $id . ", " . $monthW[$i] . ", " . $yearW[$i] . ", '" . $week[$i] . "', '"
                . $weekend[$i] . "', '" . $midweek[$i] . "')");
            $database->query();
            //}
        }
    } //end if

    if (isset($_POST['edok_link']))
        $house->edok_link = $_POST['edok_link'];
    //delete ehouse file if neccesary
    $delete_edoc = mosGetParam($_POST, 'delete_edoc', 0);
    if ($delete_edoc != '0') {
        $retVal = unlink($mosConfig_absolute_path . $delete_edoc);
        $house->edok_link = "";
    }

    //storing e-house
    if (isset($_FILES['edoc_file'])){
        $edfile = $_FILES['edoc_file'];
        $uid = md5(uniqid(rand(), 1));
        $edfile['name'] = $uid . $edfile['name'];
        $newpath = JPATH_COMPONENT . '/edocs/' . $edfile['name'];
    }
    //check if fileupload is correct
    if ($realestatemanager_configuration['edocs']['allow']
            && intval($edfile['error']) > 0
            && intval($edfile['error']) < 4) {

        echo "<script> alert('" . _REALESTATE_MANAGER_LABEL_EDOCUMENT_UPLOAD_ERROR .
        "'); window.history.go(-1); </script>\n";
        exit();
    } else if ($realestatemanager_configuration['edocs']['allow']
            && isset($_FILES['edoc_file']) && intval($edfile['error']) != 4) {

        $uploaddir = $mosConfig_absolute_path . $realestatemanager_configuration['edocs']['location'];
        $file_new = $uploaddir . $uid . $_FILES['edoc_file']['name'];
        ///
        $ext = pathinfo($_FILES['edoc_file']['name'], PATHINFO_EXTENSION);
        $ext = strtolower($ext);
        $allowed_exts = explode(",", $realestatemanager_configuration['allowed_exts']);
        foreach ($allowed_exts as $key => $allowed_ext) {
            $allowed_exts[$key] = strtolower($allowed_ext);
        }

        $file['type'] = $_FILES['edoc_file']['type'];
        $db = JFactory::getDbo();
        $db->setQuery("SELECT mime_type FROM #__rem_mime_types WHERE `mime_ext` = "
         . $db->quote($ext) . " and mime_type = " . $db->quote($file['type']));
        $file_db_mime = $db->loadResult();
        if ($file_db_mime != $file['type']) {
            echo "<script> alert(' " . _REALESTATE_MANAGER_FILE_MIME_TYPE_NOT_MATCH
              . " - " . $_FILES['edoc_file']['name'] . "'); window.history.go(-1); </script>\n";
            exit();
        }
        ////
        if (!copy($_FILES['edoc_file']['tmp_name'], $file_new)) {
            echo "<script> alert('error: not copy'); window.history.go(-1); </script>\n";
            exit();
        } else {
            $house->edok_link = $mosConfig_live_site .
                    $realestatemanager_configuration['edocs']['location'] . $edfile['name'];
        }
    }

    if (is_string($house)) {
        echo "<script> alert('" . $house . "'); window.history.go(-1); </script>\n";
        exit();
    }

    $house->published = 0;
    $house->approved = 0;

    $house->date = date("Y-m-d H:i:s");
    if (!$house->check()) {
        echo "<script> alert('" . addslashes($house->getError()) .
         "'); window.history.go(-1); </script>\n";
        exit();
    }

    if (get_magic_quotes_gpc()) {
        $house->description = stripslashes($house->description);
    }
    if (!$house->store()) {
        echo "<script> alert('" . addslashes($house->getError()) .
         "'); window.history.go(-1); </script>\n";
        exit();
    }

    $house->saveCatIds($house->catid);
    $house->checkin();

    // saving main image
    $uploaddir = $mosConfig_absolute_path . '/components/com_realestatemanager/photos/';
    
    if($_REQUEST['idtrue']){
                      
        $uploaddir = $mosConfig_absolute_path . '/components/com_realestatemanager/photos/';   
        $code = guid();
        $house_true_id = $_REQUEST['idtrue'];
            
        $query = "select main_img from #__rem_photos WHERE fk_houseid='$house_true_id' order by img_ordering,id";
        $database->setQuery($query);
        $house_temp_photos = $database->loadObjectList();    
        
        $query = "select image_link from #__rem_houses WHERE id='$house_true_id' order by id";
        $database->setQuery($query);
        $house_mail_photos = $database->loadObject();            
        
        function createNewName($name){ 
            $regExp = '/^[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{10}/';
            
            if(preg_match($regExp, $name)){  
                $substrName = createNewName(mb_substr($name, 36, strlen($name)));
            }else{ 
                $substrName = $name;
            }
            return $substrName;
        }
        
        $house_mail_photos_clon = $code.createNewName($house_mail_photos->image_link);            
         
        if (copy($uploaddir.$house_mail_photos->image_link, $uploaddir.$house_mail_photos_clon)){ 
            $database->setQuery(
              "UPDATE #__rem_houses SET image_link='$house_mail_photos_clon' WHERE id=" . $house->id);
       
            
        }
        
        if (!$database->query()){                       
            echo "<script> alert('" . $database->getErrorMsg() . "');</script>\n";      
        }         

        foreach($house_temp_photos as $val){
            $trueImg = $uploaddir.$val->main_img;
            $nameImgWithoutCode = createNewName($val->main_img);
            $file_name = $code.$nameImgWithoutCode;
            $clonImg = $uploaddir.$file_name;
                      
            if (copy($trueImg, $clonImg)){                      
                $database->setQuery(
                  "INSERT INTO #__rem_photos (fk_houseid, main_img) VALUES ( '$house->id','$file_name')");
                    
                if (!$database->query()){
                    echo "<script> alert('" . $database->getErrorMsg() . "');</script>\n";
                }
            }
        }

        $file_new_url = str_replace($realestatemanager_configuration['edocs']['location'],
         $realestatemanager_configuration['edocs']['location'].$code, $_REQUEST['edocument_Link']);

        $file_name = explode($realestatemanager_configuration['edocs']['location'], $_REQUEST['edocument_Link']);        
        $file_new = $mosConfig_absolute_path.$realestatemanager_configuration['edocs']['location'].$code.$file_name[1];
        $file_true = $mosConfig_absolute_path.$realestatemanager_configuration['edocs']['location'].$file_name[1];

        
        if (copy($file_true, $file_new)){
            $sql="UPDATE #__rem_houses SET edok_link ='$file_new_url' WHERE id=" . $house->id;
            $database->setQuery($sql);
             if (!$database->query())
                {
                    echo "<script> alert('" . $database->getErrorMsg() . "');</script>\n";
                }            
        }
        //edok_link 

        $house->edok_link=$file_new_url;
        
        //end clon
    }
    
    if ($_FILES['image_link']['name'] != '') {
        ///
        $ext = pathinfo($_FILES['image_link']['name'], PATHINFO_EXTENSION);
        $ext = strtolower($ext);
        $allowed_exts = explode(",", $realestatemanager_configuration['allowed_exts_img']);
        foreach ($allowed_exts as $key => $allowed_ext) {
            $allowed_exts[$key] = strtolower($allowed_ext);
        }

        $file['type'] = $_FILES['image_link']['type'];
        $db = JFactory::getDbo();
        $db->setQuery("SELECT mime_type FROM #__rem_mime_types WHERE `mime_ext` = " .
         $db->quote($ext) . " and mime_type = " . $db->quote($file['type']));
        $file_db_mime = $db->loadResult();
        if ($file_db_mime != $file['type']) {
            echo "<script> alert(' " . _REALESTATE_MANAGER_FILE_MIME_TYPE_NOT_MATCH
              . " - " . $_FILES['image_link']['name'] . "'); window.history.go(-1); </script>\n";
            exit();
        }
        ///
        $code = guid();
        $uploadfile = $uploaddir . $code . "_" . $_FILES['image_link']['name'];
        $file_name = $code . "_" . $_FILES['image_link']['name'];
        if (copy($_FILES['image_link']['tmp_name'], $uploadfile)) {
            $info = getimagesize($uploaddir . $file_name, $imageinfo);
            $file_width = $info[0];
            $file_height = $info[1];

            if ($file_width > $realestatemanager_configuration['fotoupload']['width'] || $file_height > $realestatemanager_configuration['fotoupload']['high']) {
                $tmp_file = rem_picture_thumbnail($file_name, $realestatemanager_configuration['fotoupload']['width'], $realestatemanager_configuration['fotoupload']['high']);
                copy($uploaddir . $tmp_file, $uploaddir . $file_name);
                unlink($uploaddir . $tmp_file);                
            }

            $database->setQuery("UPDATE #__rem_houses SET image_link='$file_name' WHERE id=" . $house->id);
            if (!$database->query()) {
                echo "<script> alert('" . $database->getErrorMsg() . "');</script>\n";
            }
        }
    } //end if

    /********************* if count photo group > count photo user not published******************/
    $count_foto_for_single_group = '';
    $user_group = userGID_REM($my->id);       
    $user_group_mas = explode(',', $user_group);
    $max_count_foto = 6;
    // foreach ($user_group_mas as $value) {            
    //     $count_foto_for_single_group =
    //      $realestatemanager_configuration['user_manager_rem'][$value]['count_foto'];
    //     if($count_foto_for_single_group>$max_count_foto){
    //         $max_count_foto = $count_foto_for_single_group;
    //     }            
    // }
    $count_foto_for_single_group = $max_count_foto;
    $query = "select main_img from #__rem_photos WHERE fk_houseid='$house->id' order by img_ordering,id";
    $database->setQuery($query);
    $house_temp_photos = $database->loadObjectList();
    if(count($house_temp_photos) != 0)
    {
        $count_foto_for_single_group = $count_foto_for_single_group - count($house_temp_photos);
    }
/**************************************************************************************************/

    //SAVE FILES TO FOLDER photos
    $uploaddir = $mosConfig_absolute_path . '/components/com_realestatemanager/photos/';

    if (array_key_exists("new_photo_file", $_FILES)) {
        for ($i = 0; $i < $count_foto_for_single_group; $i++) {
            if (!empty($_FILES['new_photo_file']['name'][$i])) {
                $code = guid();
                ////
                $ext = pathinfo($_FILES['new_photo_file']['name'][$i], PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                $allowed_exts = explode(",", $realestatemanager_configuration['allowed_exts_img']);
                foreach ($allowed_exts as $key => $allowed_ext) {
                    $allowed_exts[$key] = strtolower($allowed_ext);
                }
                if (!in_array($ext, $allowed_exts)) {
                    echo "<script> alert(' File ext. not allowed to upload! - " .
                     $_FILES['new_photo_file']['name'][$i] . "'); window.history.go(-1); </script>\n";
                    exit();
                }
                $file['type'] = $_FILES['new_photo_file']['type'][$i];
                $db = JFactory::getDbo();
                $db->setQuery("SELECT mime_type FROM #__rem_mime_types WHERE `mime_ext` = " .
                 $db->quote($ext) . " and mime_type = " . $db->quote($file['type']));
                $file_db_mime = $db->loadResult();
                if ($file_db_mime != $file['type']) {
                    echo "<script> alert(' " . _REALESTATE_MANAGER_FILE_MIME_TYPE_NOT_MATCH
                      . " - " . $_FILES['new_photo_file']['name'][$i] . "'); window.history.go(-1); </script>\n";
                    exit();
                }
                ////
                $uploadfile = $uploaddir . $code . "_" . $_FILES['new_photo_file']['name'][$i];
                if (copy($_FILES['new_photo_file']['tmp_name'][$i], $uploadfile)) {
                    $file_name = $code . "_" . $_FILES['new_photo_file']['name'][$i];
                    $info = getimagesize($uploaddir . $file_name, $imageinfo);
                    $file_width = $info[0];
                    $file_height = $info[1];

                    if ($file_width > $realestatemanager_configuration['fotoupload']['width'] || $file_height > $realestatemanager_configuration['fotoupload']['high']) {
                        $tmp_file = rem_picture_thumbnail($file_name, $realestatemanager_configuration['fotoupload']['width'], $realestatemanager_configuration['fotoupload']['high']);
                        copy($uploaddir . $tmp_file, $uploaddir . $file_name);
                        unlink($uploaddir . $tmp_file);                
                    }

                    $database->setQuery(
                    "INSERT INTO #__rem_photos (fk_houseid, main_img) VALUES ( '$house->id','$file_name')");
                    if (!$database->query()) {
                        echo "<script> alert('" . $database->getErrorMsg() . "');</script>\n";
                    }
                }
            }
        }
    } //end if
    //ordering_photo
    if(JRequest::getVar('rem_img_ordering')){
        $ordering = JRequest::getVar('rem_img_ordering');
        $ordering = explode(',', $ordering);
        foreach ($ordering as $key => $value) {
            $query = "UPDATE #__rem_photos SET img_ordering = $key WHERE main_img='".$value."'";
            $database->setQuery($query);
            $database->query();
        }
    }
    //end ordering
  /////////////save video/tracks functions\\\\\\\\\\\\\\\\\\\\\\
    storeVideo($house);
    storeTrack($house);
  /////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    //CHECK FILES - SETED FOR DEL
    if (array_key_exists("del_main_photo", $_POST)) {
        $del_main_photo = $_POST['del_main_photo'];
        if ($del_main_photo != '') {
            $file_inf = pathinfo($del_main_photo);
            $file_type = '.' . $file_inf['extension'];
            $file_name = basename($del_main_photo, $file_type);

            $path = $mosConfig_absolute_path . '/components/com_realestatemanager/photos/';
            $check_files = JFolder::files($path, '^' . $file_name . '.*$', false, true);

            foreach ($check_files as $check_file) {
                unlink($check_file);
            }

            $database->setQuery("UPDATE #__rem_houses SET image_link='' WHERE id=" . $house->id);
            if (!$database->query()) {
                echo "<script> alert('" . $database->getErrorMsg() . "');</script>\n";
            }
        }
    } //end if
    if(isset($_POST['del_photos'])){
        if (count($_POST['del_photos']) != 0) {
            if (count($_POST['del_photos']) != 0) {
                for ($i = 0; $i < count($_POST['del_photos']); $i++) {
                    $del_photo = $_POST['del_photos'][$i];
                    $database->setQuery("DELETE FROM #__rem_photos WHERE main_img='$del_photo'");
                    if ($database->query()) {
                        $file_inf = pathinfo($del_photo);
                        $file_type = '.' . $file_inf['extension'];
                        $file_name = basename($del_photo, $file_type);

                        $path = $mosConfig_absolute_path . '/components/com_realestatemanager/photos/';
                        $check_files = JFolder::files($path, '^' . $file_name . '.*$', false, true);
                        foreach ($check_files as $check_file) {
                            unlink($check_file);
                        }
                    } else {
                        echo '<script>alert("Can\'t delete");window.history.go(-1);</script>';
                    }
                }
            }
        }
    }
    if(isset($_POST['del_rent_sal'])){
        if (count($_POST['del_rent_sal']) != 0) {
            for ($i = 0; $i < count($_POST['del_rent_sal']); $i++) {
                $del_rent_sal = $_POST['del_rent_sal'][$i];
                $database->setQuery("DELETE FROM #__rem_rent_sal WHERE id ='$del_rent_sal'");
                $database->query();
            }
        }
    }
    $house->checkin();

    if (isset($_POST['ffeature'])) {
        $feature = $_POST['ffeature'];
        $database->setQuery("DELETE FROM #__rem_feature_houses WHERE fk_houseid = " . $house->id);
        $database->query();
        for ($i = 0; $i < count($feature); $i++) {
            $database->setQuery(
            "INSERT INTO #__rem_feature_houses (fk_houseid, fk_featureid) VALUES ("
             . $house->id . ", " . $feature[$i] . ")");
            $database->query();
        }
    } else {
        $database->setQuery("DELETE FROM #__rem_feature_houses WHERE fk_houseid = " . $house->id);
        $database->query();
    }
    deleteVideos($house->id);
    deleteTracks($house->id);

    switch ($task) {
        case 'apply':
            mosRedirect("index.php?option=" . $option . "&task=edit&bid[]=" . $house->id);
            break;

        case 'save':
            mosRedirect("index.php?option=" . $option);
            break;
    }
}

/**
 * Deletes one or more records
 * @param array - An array of unique category id numbers
 * @param string - The current author option
 */
function removeHouses($bid, $option,$if_clon=NULL) {
    
    global $database, $mosConfig_absolute_path;
    if (!is_array($bid) || count($bid) < 1) {
        echo "<script> alert('". _REALESTATE_MANAGER_ADMIN_SELECT_ONE_ITEM .
          "'); window.history.go(-1);</script>\n";
        exit;
    }
    
    for($i = 0; $i < count($bid); $i++){
        
        $query = "select associate_house from #__rem_houses where id =".$bid[$i];
        $database->setQuery($query);
        $houseAssociateHouse = $database->loadResult(); 
        
        $assocHouseObj = unserialize($houseAssociateHouse);
        $idWhereChange = array();   
        if(!empty($assocHouseObj)){
            foreach ($assocHouseObj as $key => $value) {
                if($value == $bid[$i]){
                    $assocHouseObj[$key] = null;
                }else if($value){
                    $idWhereChange[] = $value;
                }
            }
        
            $stringIdWhereChange = implode(',', $idWhereChange); 
            $newAssocSerialize = serialize($assocHouseObj);
            if(!empty($stringIdWhereChange)){
                $query = 
                  "update #__rem_houses set associate_house ='$newAssocSerialize' " .
                   " where id in($stringIdWhereChange)";
                $database->setQuery($query);
                $database->query();                   
            }     
        }    
    }

    if (count($bid)) {
        
        $bids = implode(',', $bid);
        foreach ($bid as $h_id) {
              $sql = "SELECT src FROM #__rem_video_source WHERE fk_house_id =". $h_id;
              $database->setQuery($sql);
              $videos = $database->loadColumn();
              if ($videos) {
                foreach($videos as $name) {
                  if (substr($name, 0, 4) != "http" && file_exists($mosConfig_absolute_path . $name)) 
                    unlink($mosConfig_absolute_path . $name);
                }
              }
              $sql = "DELETE FROM #__rem_video_source 
                      WHERE (fk_house_id = $h_id)";
              $database->setQuery($sql);
              $database->query();
                
              $sql = "SELECT src FROM #__rem_track_source WHERE fk_house_id =". $h_id;
              $database->setQuery($sql);
              $track = $database->loadColumn();
              if ($track) {
                foreach($track as $name) {
                  if (substr($name, 0, 4) != "http" && file_exists($mosConfig_absolute_path . $name)) 
                    unlink($mosConfig_absolute_path . $name);
                }
              }
              $sql = "DELETE FROM #__rem_track_source 
                      WHERE (fk_house_id = $h_id)";
              $database->setQuery($sql);
              $database->query();
        }
        $database->setQuery("SELECT * FROM  #__rem_houses WHERE id IN (" . $bids . ")");

        $del_rent = $database->loadObjectList();

        for ($i = 0; $i < count($del_rent); $i++) {
              if ($del_rent[$i]->image_link != '' and !$if_clon)
              {
                   $path = $mosConfig_absolute_path . '/components/com_realestatemanager/photos';
                   $del_photo_mask_inf = pathinfo($del_rent[$i]->image_link);
                   $del_photo_mask_type = '.' . $del_photo_mask_inf['extension'];
                   $del_photo_mask = basename($del_rent[$i]->image_link, $del_photo_mask_type);
                   $check_files = JFolder::files($path, '^' . $del_photo_mask . '.*$', false, true);

                   if (!empty($check_files))
                   {
                      foreach ($check_files as $check_file) {
                          unlink($check_file);
                      }
                   }
              }  
        }

        /* $database->setQuery("DELETE FROM #__rem_rent WHERE fk_houseid=".$del_rent[0]->houseid);
          if (!$database->query()) {
          echo "<script> alert('".addslashes($database->getErrorMsg())."'); window.history.go(-1); </script>\n";
          } */

        $database->setQuery("DELETE FROM #__rem_review WHERE fk_houseid IN ($bids)");
        if (!$database->query()) {
            echo "<script> alert('" . addslashes($database->getErrorMsg()) .
             "'); window.history.go(-1); </script>\n";
        }

        $database->setQuery("SELECT thumbnail_img, main_img FROM #__rem_photos WHERE fk_houseid IN ($bids)");
        $del_photos = $database->loadObjectList();
        for ($i = 0; $i <= count($del_photos); $i++) {
              if ($del_photos[$i]->main_img != '')
              {
                   $path = $mosConfig_absolute_path . '/components/com_realestatemanager/photos';
                   $del_photo_mask_inf = pathinfo($del_photos[$i]->main_img);
                   $del_photo_mask_type = '.' . $del_photo_mask_inf['extension'];
                   $del_photo_mask = basename($del_photos[$i]->main_img, $del_photo_mask_type);
                   $check_files = JFolder::files($path, '^' . $del_photo_mask . '.*$', false, true);

                   if (!empty($check_files))
                   {
                      foreach ($check_files as $check_file) {
                          unlink($check_file);
                      }
                   }
               } 
        }

        $database->setQuery("DELETE FROM #__rem_photos WHERE fk_houseid IN ($bids)");
        if (!$database->query()) {
            echo "<script> alert('" . addslashes($database->getErrorMsg()) .
             "'); window.history.go(-1); </script>\n";
        }

        $database->setQuery("DELETE FROM #__rem_categories WHERE iditem IN ($bids)");
        if (!$database->query()) {
            echo "<script> alert('" . addslashes($database->getErrorMsg()) .
             "'); window.history.go(-1); </script>\n";
        }
        $database->setQuery("DELETE FROM #__rem_feature_houses WHERE fk_houseid IN ($bids)");
        if (!$database->query()) {
             echo "<script> alert('" . addslashes($database->getErrorMsg()) .
              "'); window.history.go(-1); </script>\n";
        }

        $database->setQuery("DELETE FROM #__rem_houses WHERE id IN ($bids)");
        if (!$database->query()) {
            echo "<script> alert('" . addslashes($database->getErrorMsg()) .
             "'); window.history.go(-1); </script>\n";
        }
    }
    
//    if(removeHouse){
//        for($j = 0; $j < count($houseAssociateHouse); $j++){
//            removeHouses($houseAssociateHouse, $option, $if_clon);
//        }
//    }
    
    mosRedirect("index.php?option=$option");
}

/**
 * Publishes or Unpublishes one or more records
 * @param array - An array of unique category id numbers
 * @param integer - 0 if unpublishing, 1 if publishing
 * @param string - The current author option
 */
function publishHouses($bid, $publish, $option) {

    global $database, $my;

    $catid = mosGetParam($_POST, 'catid', array(0));

    if (!is_array($bid) || count($bid) < 1) {
        $action = $publish ? 'publish' : 'unpublish';
        echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
        exit;
    }

    $bids = implode(',', $bid);

    $database->setQuery("UPDATE #__rem_houses SET published='$publish'" .
            "\nWHERE id IN ($bids) AND (checked_out=0 OR (checked_out='$my->id'))");
    if (!$database->query()) {
        echo "<script> alert('" . addslashes($database->getErrorMsg()) .
         "'); window.history.go(-1); </script>\n";
        exit();
    }

    if (count($bid) == 1) {
        $row = new mosRealEstateManager($database);
        $row->checkin($bid[0]);
    }

    mosRedirect("index.php?option=$option");
}

/**
 * Approve or Unapprove one or more records
 * @param array - An array of unique category id numbers
 * @param integer - 0 if unapprove, 1 if approve
 * @param string - The current author option
 */
function approveHouses($bid, $approve, $option) {

    global $database, $my;
    //echo $bid[0];exit;
    $catid = mosGetParam($_POST, 'catid', array(0));

    if (!is_array($bid) || count($bid) < 1) {
        $action = $approve ? 'approve' : 'unapprove';
        echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
        exit;
    }

    $bids = implode(',', $bid);

    $database->setQuery("UPDATE #__rem_houses SET approved='$approve'" .
            "\nWHERE id IN ($bids) AND (checked_out=0 OR (checked_out='$my->id'))");
    if (!$database->query()) {
        echo "<script> alert('" . addslashes($database->getErrorMsg()) .
         "'); window.history.go(-1); </script>\n";
        exit();
    }

    if (count($bid) == 1) {
        $row = new mosRealEstateManager($database);
        $row->checkin($bid[0]);
    }

    mosRedirect("index.php?option=$option");
}

/**
 * Moves the order of a record
 * @param integer - The increment to reorder by
 */
function orderHouses($bid, $inc, $option) {

    global $database;

    $house = new mosRealEstateManager($database);
    $house->load($bid);
    $house->move($inc);
    mosRedirect("index.php?option=$option");
}

/**
 * Cancels an edit operation
 * @param string - The current author option
 */
function cancelHouse($option) {

    global $database;
    $row = new mosRealEstateManager($database);    
    if($_REQUEST['idtrue']){        
        $bid[]=$_REQUEST['id'];                
        removeHouses($bid,$option,TRUE);
    } 
    $row->bind($_POST);
    $row->checkin();
    mosRedirect("index.php?option=$option");
}

function configure_save_frontend($option) {
    global $my, $realestatemanager_configuration, $mosConfig_live_site, $mosConfig_absolute_path;

    $str = '';
    $supArr = array();
    $supArr = mosGetParam($_POST, 'edocs_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $realestatemanager_configuration['edocs']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'reviews_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $realestatemanager_configuration['reviews']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'paypal_buy_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $realestatemanager_configuration['paypal_buy']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'paypal_buy_sale_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $realestatemanager_configuration['paypal_buy_sale']['registrationlevel'] = $str;
    
    $str = '';
    $supArr = mosGetParam($_POST, 'rentrequest_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $realestatemanager_configuration['rentrequest']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'houserequest_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $realestatemanager_configuration['houserequest']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'buyrequest_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $realestatemanager_configuration['buyrequest']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'location_tab_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $realestatemanager_configuration['location_tab']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'reviews_tab_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $realestatemanager_configuration['reviews_tab']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'contacts_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $realestatemanager_configuration['contacts']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'owner_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $realestatemanager_configuration['owner']['registrationlevel'] = $str;

//_____cb
    $str = '';
    $supArr = mosGetParam($_POST, 'cb_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $realestatemanager_configuration['cb']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'cb_myhouse_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $realestatemanager_configuration['cb_myhouse']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'cb_edit_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $realestatemanager_configuration['cb_edit']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'cb_rent_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $realestatemanager_configuration['cb_rent']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'cb_buy_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $realestatemanager_configuration['cb_buy']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'cb_history_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $realestatemanager_configuration['cb_history']['registrationlevel'] = $str;


//___end cb

    $str = '';
    $supArr = mosGetParam($_POST, 'price_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $realestatemanager_configuration['price']['registrationlevel'] = $str;

    //***********begin show map for search layout
    $realestatemanager_configuration['searchlayout_map']['show'] = mosGetParam($_POST, 'searchlayout_map');
    $str = '';
    $supArr = mosGetParam($_POST, 'searchlayout_map_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $realestatemanager_configuration['searchlayout_map']['registrationlevel'] = $str;
    //***********end show map for search layout
    //***********begin show order by form for search layout
    $realestatemanager_configuration['searchlayout_orderby']['show'] = mosGetParam($_POST, 'searchlayout_orderby');
    $str = '';
    $supArr = mosGetParam($_POST, 'searchlayout_orderby_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $realestatemanager_configuration['searchlayout_orderby']['registrationlevel'] = $str;
    //***********end show order by form for search layout
    //***********begin show search option
    $realestatemanager_configuration['search_option']['show'] = mosGetParam($_POST, 'search_option');
    $str = '';
    $supArr = mosGetParam($_POST, 'search_option_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $realestatemanager_configuration['search_option']['registrationlevel'] = $str;
    //***********end show search option
//___cb

    $realestatemanager_configuration['cb']['show'] = mosGetParam($_POST, 'cb_show', 0);
    $realestatemanager_configuration['cb_myhouse']['show'] = mosGetParam($_POST, 'cb_show_myhouse', 0);
    $realestatemanager_configuration['cb_edit']['show'] = mosGetParam($_POST, 'cb_show_edit', 0);
    $realestatemanager_configuration['cb_rent']['show'] = mosGetParam($_POST, 'cb_show_rent', 0);
    $realestatemanager_configuration['cb_buy']['show'] = mosGetParam($_POST, 'cb_show_buy', 0);
    $realestatemanager_configuration['cb_history']['show'] = mosGetParam($_POST, 'cb_show_history', 0);

    $realestatemanager_configuration['location_tab']['show'] = mosGetParam($_POST, 'location_tab_show', 0);
    $realestatemanager_configuration['reviews_tab']['show'] = mosGetParam($_POST, 'reviews_tab_show', 0);
    //paypal
    
        
    if(isset($_POST['plugin_name_select'])){  

        $realestatemanager_configuration['plugin_name_select'] =
         mosGetParam($_POST, 'plugin_name_select', 0);
    }
    
    $realestatemanager_configuration['paypal_buy_status']['show'] =
     mosGetParam($_POST, 'paypal_buy_status_show', 0);
    $realestatemanager_configuration['paypal_buy_status_sale']['show'] =
     mosGetParam($_POST, 'paypal_buy_status_sale_show', 0);
    // $realestatemanager_configuration['cancel_url'] =addslashes($_POST['cancel_url']);
    // $realestatemanager_configuration['success_url'] = addslashes($_POST['success_url']);
   
    $realestatemanager_configuration['paypal_real_or_test']['show'] =
     mosGetParam($_POST, 'paypal_real_or_test', 0);
    
    $realestatemanager_configuration['special_price']['show'] =
     mosGetParam($_POST, 'special_price', 0);

    $realestatemanager_configuration['reviews']['show'] =
     mosGetParam($_POST, 'reviews_show', 0);

    $realestatemanager_configuration['rentstatus']['show'] =
     mosGetParam($_POST, 'rentstatus_show', 0);

    $realestatemanager_configuration['buystatus']['show'] =
     mosGetParam($_POST, 'buystatus_show', 0);

    $realestatemanager_configuration['edocs']['show'] = mosGetParam($_POST, 'edocs_show', 0);
    $realestatemanager_configuration['videos_tracks']['show'] = mosGetParam($_POST, 'videos_tracks_allow', 0);

    $realestatemanager_configuration['price']['show'] = mosGetParam($_POST, 'price_show', 0);

    $realestatemanager_configuration['thumb_param']['show'] =
     mosGetParam($_POST, 'thumb_param_show', 0);
    $realestatemanager_configuration['foto']['high'] = mosGetParam($_POST, 'foto_high');
    $realestatemanager_configuration['foto']['width'] = mosGetParam($_POST, 'foto_width');
    $realestatemanager_configuration['fotomain']['high'] = mosGetParam($_POST, 'fotomain_high');
    $realestatemanager_configuration['fotomain']['width'] = mosGetParam($_POST, 'fotomain_width');
    $realestatemanager_configuration['fotogallery']['high'] =
     mosGetParam($_POST, 'fotogallery_high');
    $realestatemanager_configuration['fotogallery']['width'] =
     mosGetParam($_POST, 'fotogallery_width');
    $realestatemanager_configuration['fotocategory']['high'] =
     mosGetParam($_POST, 'fotocategory_high');
    $realestatemanager_configuration['fotocategory']['width'] =
     mosGetParam($_POST, 'fotocategory_width');
    $realestatemanager_configuration['page']['items'] = mosGetParam($_POST, 'page_items');
    $realestatemanager_configuration['license']['show'] = mosGetParam($_POST, 'license_show');

    //add for show in category picture
    $realestatemanager_configuration['cat_pic']['show'] = mosGetParam($_POST, 'cat_pic_show');

    //add for show subcategory 
    $realestatemanager_configuration['subcategory']['show'] =
     mosGetParam($_POST, 'subcategory_show');

    //view type
    $realestatemanager_configuration['view_house'] = mosGetParam($_POST, 'view_house');
    //calendar show
    $realestatemanager_configuration['calendar']['show'] = mosGetParam($_POST, 'calendar_show');

    //***********begin Calendar list
    $str = '';
    $supArr = mosGetParam($_POST, 'calendarlist_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $realestatemanager_configuration['calendarlist']['registrationlevel'] = $str;
    //***********end Calendar list
    $realestatemanager_configuration['price_format'] = $_POST['patern'];
    $realestatemanager_configuration['date_format'] = mosGetParam($_POST, 'date_format');
    $realestatemanager_configuration['datetime_format'] = mosGetParam($_POST, 'datetime_format');
    $realestatemanager_configuration['price_unit_show'] = $_POST['price_unit_show'];
  
    //show location map
    $realestatemanager_configuration['location_map'] = mosGetParam($_POST, 'location_map', 0);

    mosRealEstateManagerOthers :: setParams();
}


function configure_save_backend($option) {

    global $my, $realestatemanager_configuration, $mosConfig_live_site, $mosConfig_absolute_path;

    $gtree = get_group_children_tree_rem();
    foreach($gtree as $g){
        $realestatemanager_configuration['user_manager_rem'][$g->value]['count_homes'] = 
          intval(mosGetParam($_POST, 'count_homes' . $g->value, "0"));
        $realestatemanager_configuration['user_manager_rem'][$g->value]['count_foto'] =
         intval(mosGetParam($_POST, 'count_foto' . $g->value, "0"));
    }
    //$realestatemanager_configuration['tabs']['show']=mosGetParam($_POST, 'tabs_show', "");
    $realestatemanager_configuration['review_email']['address'] =
            mosGetParam($_POST, 'review_email_address', ""); //back--1
    $realestatemanager_configuration['edocs']['allow'] = mosGetParam($_POST, 'edocs_allow', 0);
    $realestatemanager_configuration['edocs']['location'] =
     mosGetParam($_POST, 'edocs_location', "/components/com_realestatemanager/edocs/");

    $realestatemanager_configuration['calendar']['placeholder'] =
     mosGetParam($_POST, 'calendar_placeholder', "");

    $realestatemanager_configuration['manager_feature_category'] =
     mosGetParam($_POST, 'manager_feature_category', 0);

    $realestatemanager_configuration['sale_separator'] = mosGetParam($_POST, 'sale_separator', 0);

    //paypal   
   
    $realestatemanager_configuration['pay_pal_buy']['business'] =
     mosGetParam($_POST, 'pay_pal_buy_business', "");
    $realestatemanager_configuration['pay_pal_buy']['return'] =
     mosGetParam($_POST, 'pay_pal_buy_return', "");
    $realestatemanager_configuration['pay_pal_buy']['image_url'] =
     mosGetParam($_POST, 'pay_pal_buy_image_url', "");
    $realestatemanager_configuration['pay_pal_buy']['cancel_return'] =
     mosGetParam($_POST, 'pay_pal_buy_cancel_return', "");
    $realestatemanager_configuration['pay_pal_rent']['business'] =
     mosGetParam($_POST, 'pay_pal_rent_business', "");
    $realestatemanager_configuration['pay_pal_rent']['return'] =
     mosGetParam($_POST, 'pay_pal_rent_return', "");
    $realestatemanager_configuration['pay_pal_rent']['image_url'] =
     mosGetParam($_POST, 'pay_pal_rent_image_url', "");
    $realestatemanager_configuration['pay_pal_rent']['cancel_return'] =
     mosGetParam($_POST, 'pay_pal_rent_cancel_return', "");
   
    // 0--0--0--0--0--0--0--0--0--0--0--0--0--0--0--0--0--0--0--0--0--0--0--0--    

    $realestatemanager_configuration['plugin_name_select'] = mosGetParam($_POST, 'plugin_name_select', "");
    

    // 0--0--0--0--0--0--0--0--0--0--0--0--0--0--0--0--0--0--0--0--0--0--0--0-- 
    
    $realestatemanager_configuration['extra1'] = mosGetParam($_POST, 'extra1', '');
    $realestatemanager_configuration['extra2'] = mosGetParam($_POST, 'extra2', '');
    $realestatemanager_configuration['extra3'] = mosGetParam($_POST, 'extra3', '');
    $realestatemanager_configuration['extra4'] = mosGetParam($_POST, 'extra4', '');
    $realestatemanager_configuration['extra5'] = mosGetParam($_POST, 'extra5', '');
    $realestatemanager_configuration['extra6'] = mosGetParam($_POST, 'extra6', '');
    $realestatemanager_configuration['extra7'] = mosGetParam($_POST, 'extra7', '');
    $realestatemanager_configuration['extra8'] = mosGetParam($_POST, 'extra8', '');
    $realestatemanager_configuration['extra9'] = mosGetParam($_POST, 'extra9', '');
    $realestatemanager_configuration['extra10'] = mosGetParam($_POST, 'extra10', '');

    $realestatemanager_configuration['calendar']['placeholder'] =
     mosGetParam($_POST, 'calendar_placeholder', "");
    //$realestatemanager_configuration['featuredmanager']['placeholder'] =
    // mosGetParam($_POST, 'featuredmanager_placeholder', "");
    $realestatemanager_configuration['currency'] = mosGetParam($_POST, 'currency', "");
    //---------------------start check currency-----------------------------//
    //this array from paypal plugin,if you change something here, don't forget to change it in the paypal.php    
    //---------------------------end check-------------------------//
    $realestatemanager_configuration['allowed_exts'] = mosGetParam($_POST, 'allowed_exts', "");
    $realestatemanager_configuration['allowed_exts_img'] = mosGetParam($_POST, 'allowed_exts_img', "");
    $realestatemanager_configuration['allowed_exts_video'] = mosGetParam($_POST, 'allowed_exts_video', "");
    $realestatemanager_configuration['allowed_exts_track'] = mosGetParam($_POST, 'allowed_exts_track', "");

    $realestatemanager_configuration['fotoupload']['high'] = mosGetParam($_POST, 'fotoupload_high');
    $realestatemanager_configuration['fotoupload']['width'] = mosGetParam($_POST, 'fotoupload_width');
    $realestatemanager_configuration['api_key'] = mosGetParam($_POST, 'api_key', "");

    mosRealEstateManagerOthers :: setParams();
}

function configure($option) {
    //configure_frontend
    global $my, $realestatemanager_configuration, $acl, $database;
    global $mosConfig_absolute_path;
    $yesno[] = mosHTML :: makeOption('1', _REALESTATE_MANAGER_YES);
    $yesno[] = mosHTML :: makeOption('0', _REALESTATE_MANAGER_NO);
    $daynight[] = mosHTML :: makeOption('1', _REALESTATE_MANAGER_ADMIN_CONFIG_RENT_BEFORE_END_NOTIFY_DAYS);
    $daynight[] = mosHTML :: makeOption('0', _REALESTATE_MANAGER_RENT_PER_NIGHT);
    $lists = array();
    
    $gtree = get_group_children_tree_rem();
    
    foreach($gtree as $g) {
        $t['value'] = $g->value;
        $t['role'] = str_replace('&nbsp;', '', $g->text);
        $t['count_homes'] = '<input type="text" name="count_homes' . $g->value . '" value="' .
         $realestatemanager_configuration['user_manager_rem'][$g->value]['count_homes'] .
         '" class="inputbox" size="3" maxlength="3" />';
        $t['count_foto'] = '<input type="text" name="count_foto' . $g->value . '" value="' .
           $realestatemanager_configuration['user_manager_rem'][$g->value]['count_foto'] . 
           '" class="inputbox" size="3" maxlength="3" />';
        $lists['user_manager_rem'][] = $t;
    }

    // _______________- community builder section -_______________
    $f = "";
    $s = explode(',', $realestatemanager_configuration['cb_myhouse']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['cb_myhouse']['show'] = mosHTML :: RadioList($yesno, 'cb_show_myhouse', 'class="inputbox"', 
      $realestatemanager_configuration['cb_myhouse']['show'], 'value', 'text');

    $lists['cb_myhouse']['registrationlevel'] = mosHTML::selectList($gtree, 'cb_myhouse_registrationlevel[]', 
      'size="4" multiple="multiple"', 'value', 'text', $f);

    $f = "";
    $s = explode(',', $realestatemanager_configuration['cb_edit']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['cb_edit']['show'] = mosHTML :: RadioList($yesno, 'cb_show_edit', 'class="inputbox"', 
      $realestatemanager_configuration['cb_edit']['show'], 'value', 'text');

    $lists['cb_edit']['registrationlevel'] = mosHTML::selectList($gtree, 'cb_edit_registrationlevel[]', 
      'size="4" multiple="multiple"', 'value', 'text', $f);

    $f = "";
    $s = explode(',', $realestatemanager_configuration['cb_rent']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['cb_rent']['show'] = mosHTML :: RadioList($yesno, 'cb_show_rent', 'class="inputbox"', 
      $realestatemanager_configuration['cb_rent']['show'], 'value', 'text');

    $lists['cb_rent']['registrationlevel'] = mosHTML::selectList($gtree, 'cb_rent_registrationlevel[]', 
      'size="4" multiple="multiple"', 'value', 'text', $f);

    $f = "";
    $s = explode(',', $realestatemanager_configuration['cb_buy']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['cb_buy']['show'] = mosHTML :: RadioList($yesno, 'cb_show_buy', 'class="inputbox"',
     $realestatemanager_configuration['cb_buy']['show'], 'value', 'text');

    $lists['cb_buy']['registrationlevel'] = mosHTML::selectList($gtree, 'cb_buy_registrationlevel[]', 
      'size="4" multiple="multiple"', 'value', 'text', $f);

    $f = "";
    $s = explode(',', $realestatemanager_configuration['cb_history']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['cb_history']['show'] = mosHTML :: RadioList($yesno, 'cb_show_history', 'class="inputbox"', 
      $realestatemanager_configuration['cb_history']['show'], 'value', 'text');

    $lists['cb_history']['registrationlevel'] = mosHTML::selectList($gtree, 'cb_history_registrationlevel[]', 
      'size="4" multiple="multiple"', 'value', 'text', $f);
    // _______________- end community builder section -_______________

    $f = "";
    $s = explode(',', $realestatemanager_configuration['reviews']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['reviews']['show'] = mosHTML :: RadioList($yesno, 'reviews_show', 'class="inputbox"', 
      $realestatemanager_configuration['reviews']['show'], 'value', 'text');
    $lists['reviews']['registrationlevel'] = mosHTML::selectList($gtree, 'reviews_registrationlevel[]', 
      'size="4" multiple="multiple"', 'value', 'text', $f);

    $f = "";
    $s = explode(',', $realestatemanager_configuration['location_tab']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['location_tab']['show'] = mosHTML :: RadioList($yesno, 'location_tab_show', 'class="inputbox"',
     $realestatemanager_configuration['location_tab']['show'], 'value', 'text');

    $lists['location_tab']['registrationlevel'] = mosHTML::selectList($gtree, 'location_tab_registrationlevel[]',
     'size="4" multiple="multiple"', 'value', 'text', $f);

    $f = "";
    $s = explode(',', $realestatemanager_configuration ['reviews_tab']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['reviews_tab']['show'] = mosHTML :: RadioList($yesno, 'reviews_tab_show', 'class="inputbox"',
     $realestatemanager_configuration['reviews_tab']['show'], 'value', 'text');

    $lists['reviews_tab']['registrationlevel'] = mosHTML::selectList($gtree, 'reviews_tab_registrationlevel[]',
     'size="4" multiple="multiple"', 'value', 'text', $f);

    $f = "";
    $s = explode(',', $realestatemanager_configuration['rentrequest']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['rentstatus']['show'] = mosHTML :: RadioList($yesno, 'rentstatus_show', 'class="inputbox"',
     $realestatemanager_configuration['rentstatus']['show'], 'value', 'text');

    $lists['rentrequest']['registrationlevel'] = mosHTML::selectList($gtree, 'rentrequest_registrationlevel[]',
     'size="4" multiple="multiple"', 'value', 'text', $f);

    $f = "";
    $s = explode(',', $realestatemanager_configuration['houserequest']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['housestatus']['show'] = mosHTML :: RadioList($yesno, 'housestatus_show', 'class="inputbox"',
     $realestatemanager_configuration['housestatus']['show'], 'value', 'text');

    $lists['houserequest']['registrationlevel'] = mosHTML::selectList($gtree, 'houserequest_registrationlevel[]',
     'size="4" multiple="multiple"', 'value', 'text', $f);

    $f = "";
    $s = explode(',', $realestatemanager_configuration['buyrequest']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['buystatus']['show'] = mosHTML :: RadioList($yesno, 'buystatus_show', 'class="inputbox"',
     $realestatemanager_configuration['buystatus']['show'], 'value', 'text');

    $lists['buyrequest']['registrationlevel'] = mosHTML::selectList($gtree, 'buyrequest_registrationlevel[]',
     'size="4" multiple="multiple"', 'value', 'text', $f);

    $f = "";
    $s = explode(',', $realestatemanager_configuration['edocs']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['edocs']['registrationlevel'] = mosHTML::selectList($gtree, 'edocs_registrationlevel[]',
     'size="4" multiple="multiple"', 'value', 'text', $f);

    $lists['edocs']['show'] = mosHTML :: RadioList($yesno, 'edocs_show', 'class="inputbox"',
     $realestatemanager_configuration['edocs']['show'], 'value', 'text');

    $lists['videos_tracks']['show'] = mosHTML::RadioList($yesno, 'videos_tracks_allow', 'class="inputbox"',
        $realestatemanager_configuration['videos_tracks']['show'], 'value', 'text');
    $lists['videos']['location'] = '<input disabled="disabled" type="text" name="videos_location" value="' .
        $realestatemanager_configuration['videos']['location'] . '" class="inputbox" size="50" maxlength="50" title="" />';
    $lists['tracks']['location'] = '<input disabled="disabled" type="text" name="tracks_location" value="' .
         $realestatemanager_configuration['tracks']['location'] . '" class="inputbox" size="50" maxlength="50" title="" />';

    $f = "";
    $s = explode(',', $realestatemanager_configuration['price']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['price']['show'] = mosHTML :: RadioList($yesno, 'price_show', 'class="inputbox"',
     $realestatemanager_configuration['price']['show'], 'value', 'text');

    $lists['price']['registrationlevel'] = mosHTML::selectList($gtree, 'price_registrationlevel[]',
     'size="4" multiple="multiple"', 'value', 'text', $f);

    $f = "";
    $s = explode(',', $realestatemanager_configuration['reviews']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['reviews']['show'] = mosHTML :: RadioList($yesno, 'reviews_show', 'class="inputbox"',
     $realestatemanager_configuration['reviews']['show'], 'value', 'text');
    $lists['reviews']['registrationlevel'] = mosHTML::selectList($gtree, 'reviews_registrationlevel[]',
     'size="4" multiple="multiple"', 'value', 'text', $f);

    //********** Calendar list ************
    $f = "";
    $s = explode(',', $realestatemanager_configuration['calendarlist']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);
    $lists['calendar']['show'] = mosHTML :: RadioList($yesno, 'calendar_show', 'class="inputbox"', $realestatemanager_configuration['calendar']['show'], 'value', 'text');
    $lists['calendarlist']['registrationlevel'] = mosHTML::selectList($gtree, 'calendarlist_registrationlevel[]', 'size="4" multiple="multiple"', 'value', 'text', $f);
    //********** END Calendar list ************
    //show location map
    $lists['location_map'] = mosHTML :: RadioList($yesno, 'location_map', 'class="inputbox"',
     $realestatemanager_configuration['location_map'], 'value', 'text');

    $lists['thumb_param']['show'] = mosHTML :: RadioList($yesno, 'thumb_param_show', 'class="inputbox"',
     $realestatemanager_configuration['thumb_param']['show'], 'value', 'text');

    $lists['foto']['high'] = '<input type="text" name="foto_high"
    value="' . $realestatemanager_configuration['foto']['high'] .
            '" class="inputbox" size="4" maxlength="4" title="" />';

    $lists['foto']['width'] = '<input type="text" name="foto_width"
    value="' . $realestatemanager_configuration['foto']['width'] .
            '" class="inputbox" size="4" maxlength="4" title="" />';

    $lists['fotomain']['high'] = '<input type="text" name="fotomain_high"
    value="' . $realestatemanager_configuration['fotomain']['high'] .
            '" class="inputbox" size="4" maxlength="4" title="" />';

    $lists['fotomain']['width'] = '<input type="text" name="fotomain_width"
    value="' . $realestatemanager_configuration['fotomain']['width'] .
            '" class="inputbox" size="4" maxlength="4" title="" />';

    $lists['fotogallery']['high'] = '<input type="text" name="fotogallery_high"
    value="' . $realestatemanager_configuration['fotogallery']['high'] .
            '" class="inputbox" size="4" maxlength="4" title="" />';

    $lists['fotogallery']['width'] = '<input type="text" name="fotogallery_width"
    value="' . $realestatemanager_configuration['fotogallery']['width'] .
            '" class="inputbox" size="4" maxlength="4" title="" />';

    $lists['fotocategory']['high'] = '<input type="text" name="fotocategory_high"
    value="' . $realestatemanager_configuration['fotocategory']['high'] .
            '" class="inputbox" size="4" maxlength="4" title="" />';

    $lists['fotocategory']['width'] = '<input type="text" name="fotocategory_width"
    value="' . $realestatemanager_configuration['fotocategory']['width'] .
            '" class="inputbox" size="4" maxlength="4" title="" />';

    $lists['page']['items'] = '<input type="text" name="page_items"
    value="' . $realestatemanager_configuration['page']['items'] .
            '" class="inputbox" size="3" maxlength="3" title="" />';

    $lists['license']['show'] = mosHTML :: RadioList($yesno, 'license_show',
     'class="inputbox"', $realestatemanager_configuration['license']['show'], 'value', 'text');

    $txt = $realestatemanager_configuration['license']['text'];
    $lists['rent_form'] = $realestatemanager_configuration['rent_form'];

    $lists['rent_form'] = $realestatemanager_configuration['rent_form'];
    $lists['buy_form'] = $realestatemanager_configuration['buy_form'];

    //add for show in category picture
    $lists['cat_pic']['show'] = mosHTML :: RadioList($yesno, 'cat_pic_show',
     'class="inputbox"', $realestatemanager_configuration['cat_pic']['show'], 'value', 'text');

    //add for show subcategory 
    $lists['subcategory']['show'] = mosHTML :: RadioList($yesno, 'subcategory_show',
     'class="inputbox"', $realestatemanager_configuration['subcategory']['show'], 'value', 'text');
    
    //******   begin show map for search layout  *****
    $f = "";
    $s = explode(',', $realestatemanager_configuration['searchlayout_map']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['searchlayout_map']['show'] = mosHTML :: RadioList($yesno, 'searchlayout_map',
     'class="inputbox"', $realestatemanager_configuration['searchlayout_map']['show'], 'value', 'text');

    $lists['searchlayout_map']['registrationlevel'] = mosHTML::selectList($gtree,
     'searchlayout_map_registrationlevel[]', 'size="4" multiple="multiple"', 'value', 'text', $f);
    //*******   end show map for search layout   *******
    //******   begin show order by form for search layout  *****
    $f = "";
    $s = explode(',', $realestatemanager_configuration['searchlayout_orderby']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['searchlayout_orderby']['show'] = mosHTML :: RadioList($yesno, 'searchlayout_orderby',
     'class="inputbox"', $realestatemanager_configuration['searchlayout_orderby']['show'], 'value', 'text');

    $lists['searchlayout_orderby']['registrationlevel'] = mosHTML::selectList($gtree,
     'searchlayout_orderby_registrationlevel[]', 'size="4" multiple="multiple"', 'value', 'text', $f);
    //*******   end show order by form for search layout   *******

//******   begin show search_option  *****
    $f = "";
    $s = explode(',', $realestatemanager_configuration['search_option']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['search_option']['show'] = mosHTML :: RadioList($yesno, 'search_option',
     'class="inputbox"', $realestatemanager_configuration['search_option']['show'], 'value', 'text');


    $lists['search_option']['registrationlevel'] = mosHTML::selectList($gtree,
     'search_option_registrationlevel[]', 'size="4" multiple="multiple"', 'value', 'text', $f);
//*******   end show search_option   *******

//configure_backend

    $lists['edocs']['allow'] = mosHTML :: RadioList($yesno, 'edocs_allow', 'class="inputbox"',
     $realestatemanager_configuration['edocs']['allow'], 'value', 'text');

    $lists['edocs']['location'] = '<input type="text" name="edocs_location" value="'
     . $realestatemanager_configuration['edocs']['location']
      . '" class="inputbox" size="50" maxlength="50" title="" disabled="disabled"/>';

    $lists['photos']['location'] = 
    '<input type="text" name="photos_location" value="/components/com_realestatemanager/photos/" '
    .' class="inputbox" size="50" maxlength="50" title="" disabled="disabled"/>';

    //show category for feature manager
    $lists['manager_feature_category'] =
     mosHTML :: RadioList($yesno, 'manager_feature_category', 'class="inputbox"',
     $realestatemanager_configuration['manager_feature_category'], 'value', 'text');
     $lists['calendar']['show'] = mosHTML :: RadioList($yesno, 'calendar_show', 'class="inputbox"', $realestatemanager_configuration['calendar']['show'], 'value', 'text');
    //show sale_separator
    $lists['sale_separator'] = mosHTML :: RadioList($yesno, 'sale_separator', 'class="inputbox"',
     $realestatemanager_configuration['sale_separator'], 'value', 'text');

    $lists['extra1'] = mosHTML :: RadioList($yesno, 'extra1', 'class="inputbox"',
     $realestatemanager_configuration['extra1'], 'value', 'text');
    $lists['extra2'] = mosHTML :: RadioList($yesno, 'extra2', 'class="inputbox"',
     $realestatemanager_configuration['extra2'], 'value', 'text');
    $lists['extra3'] = mosHTML :: RadioList($yesno, 'extra3', 'class="inputbox"',
     $realestatemanager_configuration['extra3'], 'value', 'text');
    $lists['extra4'] = mosHTML :: RadioList($yesno, 'extra4', 'class="inputbox"',
     $realestatemanager_configuration['extra4'], 'value', 'text');
    $lists['extra5'] = mosHTML :: RadioList($yesno, 'extra5', 'class="inputbox"',
     $realestatemanager_configuration['extra5'], 'value', 'text');
    $lists['extra6'] = mosHTML :: RadioList($yesno, 'extra6', 'class="inputbox"',
     $realestatemanager_configuration['extra6'], 'value', 'text');
    $lists['extra7'] = mosHTML :: RadioList($yesno, 'extra7', 'class="inputbox"',
     $realestatemanager_configuration['extra7'], 'value', 'text');
    $lists['extra8'] = mosHTML :: RadioList($yesno, 'extra8', 'class="inputbox"',
     $realestatemanager_configuration['extra8'], 'value', 'text');
    $lists['extra9'] = mosHTML :: RadioList($yesno, 'extra9', 'class="inputbox"',
     $realestatemanager_configuration['extra9'], 'value', 'text');
    $lists['extra10'] = mosHTML :: RadioList($yesno, 'extra10', 'class="inputbox"',
     $realestatemanager_configuration['extra10'], 'value', 'text');

   $lists['currency'] = '<input type="text" name="currency" value="'
    . $realestatemanager_configuration['currency'] .
     '" class="inputbox" size="50" maxlength="500" title=""/>';
    $lists['allowed_exts'] = '<input type="text" name="allowed_exts" value="'
     . $realestatemanager_configuration['allowed_exts'] .
      '" class="inputbox" size="50" maxlength="1500" title=""/>';
    $lists['allowed_exts_img'] = '<input type="text" name="allowed_exts_img" value="'
     . $realestatemanager_configuration['allowed_exts_img'] .
      '" class="inputbox" size="50" maxlength="1500" title=""/>';

    $lists['allowed_exts_video'] = '<input type="text" name="allowed_exts_video" value="' .
     $realestatemanager_configuration['allowed_exts_video'] .
      '" class="inputbox" size="50" maxlength="1500" title=""/>';
    $lists['allowed_exts_track'] = '<input type="text" name="allowed_exts_track" value="' .
     $realestatemanager_configuration['allowed_exts_track'] .
      '" class="inputbox" size="50" maxlength="1500" title=""/>';

    $lists['fotoupload']['high'] = '<input type="text" name="fotoupload_high"
    value="' . $realestatemanager_configuration['fotoupload']['high'] .
            '" class="inputbox" size="4" maxlength="4" title="" />';

    $lists['fotoupload']['width'] = '<input type="text" name="fotoupload_width"
    value="' . $realestatemanager_configuration['fotoupload']['width'] .
            '" class="inputbox" size="4" maxlength="4" title="" />';


    $lists['api_key'] = '<input type="text" id="api_key" name="api_key" value="' .
     $realestatemanager_configuration['api_key'] .
      '" class="inputbox" size="50" maxlength="1500" title=""/>';
    
    //paypal
    $f = "";
    $s = explode(',', $realestatemanager_configuration['paypal_buy']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);
    $lists['paypal_buy_status']['show'] = mosHTML :: RadioList($yesno, 'paypal_buy_status_show',
     'class="inputbox"', $realestatemanager_configuration['paypal_buy_status']['show'], 'value', 'text');
    $lists['paypal_buy']['registrationlevel'] = mosHTML::selectList($gtree,
     'paypal_buy_registrationlevel[]', 'size="4" multiple="multiple"', 'value', 'text', $f);
    
    $f = "";
    $s = explode(',', $realestatemanager_configuration['paypal_buy_sale']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);
    $lists['paypal_buy_status_sale']['show'] = mosHTML :: RadioList($yesno, 'paypal_buy_status_sale_show',
     'class="inputbox"', $realestatemanager_configuration['paypal_buy_status_sale']['show'], 'value', 'text');
    $lists['paypal_buy_sale']['registrationlevel'] = mosHTML::selectList($gtree,
     'paypal_buy_sale_registrationlevel[]', 'size="4" multiple="multiple"', 'value', 'text', $f);
    

   
    $lists['paypal_real_or_test']['show'] =
     mosHTML :: RadioList($yesno, 'paypal_real_or_test', 'class="inputbox"',
      $realestatemanager_configuration['paypal_real_or_test']['show'], 'value', 'text');
    
    $lists['special_price']['show'] = mosHTML :: RadioList($daynight, 'special_price', 'class="inputbox"',
     $realestatemanager_configuration['special_price']['show'], 'value', 'text');
    
    //PayPal
    
//     $lists['pay_pal_buy_business'] = '<input type="text" name="pay_pal_buy_business" value="' .
//      $realestatemanager_configuration['pay_pal_buy']['business'] .
//       '" class="inputbox" size="50" maxlength="500" title=""/>';
//    $lists['pay_pal_buy_return'] = '<input type="text" name="pay_pal_buy_return" value="'
//     . $realestatemanager_configuration['pay_pal_buy']['return'] .
//      '" class="inputbox" size="50" maxlength="500" title=""/>';
//     $lists['pay_pal_buy_image_url'] = '<input type="text" name="pay_pal_buy_image_url" value="' .
//      $realestatemanager_configuration['pay_pal_buy']['image_url'] .
//       '" class="inputbox" size="50" maxlength="500" title=""/>';
//     $lists['pay_pal_buy_cancel_return'] = '<input type="text" name="pay_pal_buy_cancel_return" value="' .
//      $realestatemanager_configuration['pay_pal_buy']['cancel_return'] .
//       '" class="inputbox" size="50" maxlength="500" title=""/>';
//     $lists['pay_pal_rent_business'] = '<input type="text" name="pay_pal_rent_business" value="'
//      . $realestatemanager_configuration['pay_pal_rent']['business'] .
//       '" class="inputbox" size="50" maxlength="500" title=""/>';
//     $lists['pay_pal_rent_return'] = '<input type="text" name="pay_pal_rent_return" value="'
//      . $realestatemanager_configuration['pay_pal_rent']['return'] .
//       '" class="inputbox" size="50" maxlength="500" title=""/>';
//     $lists['pay_pal_rent_image_url'] = '<input type="text" name="pay_pal_rent_image_url" value="'
//      . $realestatemanager_configuration['pay_pal_rent']['image_url'] .
//       '" class="inputbox" size="50" maxlength="500" title=""/>';
//     $lists['pay_pal_rent_cancel_return'] = '<input type="text" name="pay_pal_rent_cancel_return" value="'
//      . $realestatemanager_configuration['pay_pal_rent']['cancel_return'] .
//       '" class="inputbox" size="50" maxlength="500" title=""/>';
    $lists['cancel_url'] = stripslashes($realestatemanager_configuration['cancel_url']);
    $lists['success_url'] = stripslashes($realestatemanager_configuration['success_url']);
    //***************************************************

    $view_house = getLayoutsRem('com_realestatemanager','view_house');
    $lists['view_house'] = mosHTML::selectList($view_house, 'view_house', 'size="1" ',
     'value', 'text', $realestatemanager_configuration['view_house']);
    
  /////////////////////////////////////////////////////
  
  
    $money_ditlimer = array();
    $money_ditlimer[] = JHtml::_('select.option', ".", "Point (12.134.123,12)");
    $money_ditlimer[] = JHtml::_('select.option', ",", "Comma (12,134,123.12)");
    $money_ditlimer[] = JHtml::_('select.option', "space", "Space (12 134 123,12)");
    $money_ditlimer[] = JHtml::_('select.option', "other", "Your delimiter: ");
    $price_unit_show = array(_REALESTATE_PRICE_UNIT_SHOW_AFTER, _REALESTATE_PRICE_UNIT_SHOW_BEFORE);
    $price_unit_show = null;
    $price_unit_show[] = mosHTML :: makeOption('1', _REALESTATE_PRICE_UNIT_SHOW_AFTER);
    $price_unit_show[] = mosHTML :: makeOption('0', _REALESTATE_PRICE_UNIT_SHOW_BEFORE);

    $selecter = '';
    switch ($realestatemanager_configuration['price_format']) {
        case '.':
            $selecter = '.';
            break;
        case ',':
            $selecter = ',';
            break;
        case '&nbsp;':
            $selecter = 'space';
            break;
        default:
            $selecter = 'other';
    }
    // 1 - affter 0 - beffore
    $lists['price_unit_show'] = mosHTML :: RadioList($price_unit_show, 'price_unit_show',
     'class="inputbox"', $realestatemanager_configuration['price_unit_show'], 'value', 'text');
    $lists['money_ditlimer'] = mosHTML::selectList($money_ditlimer, 'money_select',
     'size="1"  onchange="set_pricetype(this)"', 'value', 'text', $selecter);
    $lists['date_format'] = '<input type="text" name="date_format" value="'
     . $realestatemanager_configuration['date_format'] . '" class="inputbox"  title="" />';
    $lists['datetime_format'] = '<input type="hidden" name="datetime_format" value="'
     . $realestatemanager_configuration['datetime_format'] . '" class="inputbox" title="" />';

    $plugin_name_select = array();
    $plugin_name_mass = getSelect();
    $selecter_plugin_name = '';
    if(!$plugin_name_mass){
        $lists['plugin_name_select'] = '<div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">×</button>'
                            . _REALESTATE_MANAGER_MESSAGE_INSTALL_PLUGIN .
                        '</div>';
    }else{
    
        foreach ($plugin_name_mass as $value) {
            if(isset($realestatemanager_configuration['plugin_name_select'])
             && !empty($realestatemanager_configuration['plugin_name_select'])
              && $realestatemanager_configuration['plugin_name_select'] == $value){
                $selecter_plugin_name = $value;
            }
            $plugin_name_select[] = JHtml::_('select.option', "$value", "$value");          
        }
        
        $lists['plugin_name_select'] = mosHTML::selectList($plugin_name_select,
         'plugin_name_select', 'size="1"', 'value', 'text', $selecter_plugin_name);
    }


    HTML_realestatemanager :: showConfiguration($lists, $option, $txt);
}

//----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------


  function getSelect(){
                    			$db = JFactory::getDBO();
					$condtion = array(0 => '\'payment\'');
					$condtionatype = join(',',$condtion);
					if(JVERSION >= '1.6.0')
					{
						$query = "SELECT extension_id as id,name,element,enabled as published
								  FROM #__extensions
								  WHERE folder in ($condtionatype) AND enabled=1";
					}
					else
					{
						$query = "SELECT id,name,element,published
								  FROM #__plugins
								  WHERE folder in ($condtionatype) AND published=1";
					}
					$db->setQuery($query);
					$gatewayplugin = $db->loadobjectList();
                                        if(!getNamePluginForValSelect($gatewayplugin)){
                                            
                                        }else{
                                            return getNamePluginForValSelect($gatewayplugin);
                                        }
                                        
       }
       
 function getNamePluginForValSelect($gatewayplugin){
          
          $new_plugin_name = array();
          
          $retr = count($gatewayplugin);
          
           if($retr>0){
          
                for($i=0;$i<$retr;$i++){
                      $plugin_name_strtolower = mb_strtolower(trim($gatewayplugin[$i]->name)); 
                      $plugin_name_mass = explode(" ", $plugin_name_strtolower);
                      $lenght_mass = count($plugin_name_mass);                       
                      array_push($new_plugin_name,$plugin_name_mass[$lenght_mass-1]);
                }
                return $new_plugin_name;
          
          }
          else{
                return false;
          }
          
      }

//----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------

function rent($option, $bid) {
    global $database, $my;
    if (!is_array($bid) || count($bid) !== 1) {
        echo "<script> alert('". _REALESTATE_MANAGER_ADMIN_SELECT_ONE_ITEM ."'); window.history.go(-1);</script>\n";
        exit;
    }
    $bid_house = implode(',', $bid);
    $select = "SELECT a.*, cc.name AS category, l.id as rentid, l.rent_from as rent_from, " .
            "l.rent_return as rent_return, l.rent_until as rent_until, " .
            "l.user_name as user_name, l.user_email as user_email " .
            "\nFROM #__rem_houses AS a" .
            "\nLEFT JOIN #__rem_categories as hc on hc.iditem = a.id" .
            "\nLEFT JOIN #__rem_main_categories AS cc ON cc.id = hc.idcat" .
            "\nLEFT JOIN #__rem_rent AS l ON l.id = a.fk_rentid" .
            "\nWHERE a.id = $bid_house";
    $database->setQuery($select);
    $house1 = $database->loadObject();
    if ($house1->listing_type != 1) {
              ?>
              <script type = "text/JavaScript" language = "JavaScript">
                  alert("<?php echo _REALESTATE_MANAGER_ADMIN_NOT_FOR_RENT ?>");
                  window.history.go(-1);
              </script>
              <?php
  
              exit;
    }
    $bids = implode(',', $bid);
    $bids = getAssociateHouses($bids);
    $houses_assoc[]= $house1;
    if($bids){
        $select = "SELECT a.*, cc.name AS category, l.id as rentid, l.rent_from as rent_from, " .
                "l.rent_return as rent_return, l.rent_until as rent_until, " .
                "l.user_name as user_name, l.user_email as user_email " .
                "\nFROM #__rem_houses AS a" .
                "\nLEFT JOIN #__rem_categories as hc on hc.iditem = a.id" .
                "\nLEFT JOIN #__rem_main_categories AS cc ON cc.id = hc.idcat" .
                "\nLEFT JOIN #__rem_rent AS l ON l.id = a.fk_rentid" .
                "\nWHERE a.id in ($bids)";
        $database->setQuery($select);
        $houses_assoc = $database->loadObjectList(); 
        //for rent or not
        $count = count($houses_assoc);
        for ($i = 0; $i < $count; $i++) {
            if ($houses_assoc[$i]->listing_type != 1) {
                ?>
                <script type = "text/JavaScript" language = "JavaScript">
                    alert("<?php echo _REALESTATE_MANAGER_ADMIN_NOT_FOR_RENT_ASOC ?>");
                    window.history.go(-1);
                </script>
                <?php
    
                exit;
            }
        }
    }
// get list of categories

    $userlist[] = mosHTML :: makeOption('-1', '----------');
    $database->setQuery("SELECT id AS value, name AS text from #__users ORDER BY name");
    $userlist = array_merge($userlist, $database->loadObjectList());
    $usermenu = mosHTML :: selectList($userlist, 'userid', 'class="inputbox" size="1"', 'value', 'text', '-1');

    HTML_realestatemanager :: showRentHouses($option, $house1, $houses_assoc, $usermenu, "rent");
}


function edit_rent($option, $bid) {

    global $database, $my;
    if (!is_array($bid) || count($bid) !== 1) {
        echo "<script> alert('". _REALESTATE_MANAGER_ADMIN_SELECT_ONE_ITEM ."'); window.history.go(-1);</script>\n";
        exit;
    }
    
    $bid_house = implode(',', $bid);
    $select = "SELECT a.*, cc.name AS category, l.id as rentid, l.rent_from as rent_from, " .
            "l.rent_return as rent_return, l.rent_until as rent_until, " .
            "l.user_name as user_name, l.user_email as user_email " .
            "\nFROM #__rem_houses AS a" .
            "\nLEFT JOIN #__rem_categories as hc on hc.iditem = a.id" .
            "\nLEFT JOIN #__rem_main_categories AS cc ON cc.id = hc.idcat" .
            "\nLEFT JOIN #__rem_rent AS l ON l.fk_houseid = a.id" .
            "\nWHERE a.id = $bid_house";
    $database->setQuery($select);
    $house1 = $database->loadObject();
    if ($house1->listing_type != 1) {
      ?>
      <script type = "text/JavaScript" language = "JavaScript">
          alert("<?php echo _REALESTATE_MANAGER_ADMIN_NOT_FOR_RENT ?>");
          window.history.go(-1);
      </script>
      <?php
      exit;
    }
    
    
    
    $bids = implode(',', $bid);
    $bids = getAssociateHouses($bids);
    if($bids == "") $bids = implode(',', $bid);
    $houses_rents_assoc= array();
    $title_assoc = array();
    if($bids){

    
        $select = "SELECT a.*, cc.name AS category, l.id as rentid, l.rent_from as rent_from, " .
                "l.rent_return as rent_return, l.rent_until as rent_until, " .
                "l.user_name as user_name, l.user_email as user_email " .
                "\nFROM #__rem_houses AS a" .
                "\nLEFT JOIN #__rem_categories as hc on hc.iditem = a.id" .
                "\nLEFT JOIN #__rem_main_categories AS cc ON cc.id = hc.idcat" .
                "\nLEFT JOIN #__rem_rent AS l ON l.fk_houseid = a.id" .
                "\nWHERE a.id in ($bids)";
        $database->setQuery($select);
        $houses_rents_assoc = $database->loadObjectList();
      
        $select = "SELECT a.htitle  " .
                "\nFROM #__rem_houses AS a" .
                "\nLEFT JOIN #__rem_rent AS l ON l.fk_houseid = a.id" .
                "\nWHERE a.id in ($bids)"; 
        $database->setQuery($select);
        $title_assoc = $database->loadObjectList();
    
        $count = count($houses_rents_assoc);
        for ($i = 0; $i < $count; $i++) {
            if ($houses_rents_assoc[$i]->listing_type != 1) {
                ?>
                <script type = "text/JavaScript" language = "JavaScript">
                    alert("<?php echo _REALESTATE_MANAGER_ADMIN_NOT_FOR_RENT_ASOC ?>");
                    window.history.go(-1);
                </script>
                <?php
                exit;
            }
        }
       
        $is_rent_out = false;
        for ($i = 0; $i < count($houses_rents_assoc); $i++) {
      
          if ( $houses_rents_assoc[$i]->rent_from != '' && $houses_rents_assoc[$i]->rent_return == '' )
          {
            $is_rent_out = true ;
            break ;
          }
        }

        if ( !$is_rent_out ){
            ?>
            <script type = "text/JavaScript" language = "JavaScript">
                alert("<?php echo _REALESTATE_MANAGER_ADMIN_HOUSE_NOT_IN_RENT ?>");
                window.history.go(-1);
            </script>
            <?php
            exit;
        }

      //check rent_return == null count for all assosiate
        $ids = explode(',', $bids);
        $rent_count = -1;
        $all_assosiate_rent = array();
        $count = count($ids);
        for ($i = 0; $i < $count; $i++) {
        
            $query = "SELECT * FROM #__rem_rent WHERE fk_houseid = " . $ids[$i] . 
             " and rent_return is null ORDER BY rent_from"; 
            // print_r($query);
            $database->setQuery($query);
            $all_assosiate_rent_item = $database->loadObjectList();
    
            if ( $rent_count != -1 && $rent_count != count($all_assosiate_rent_item) )
            {
                ?>
                <script type = "text/JavaScript" language = "JavaScript">
                    alert("<?php echo _REALESTATE_MANAGER_ADMIN_RENT_ASSOCIATED ?>");
                    window.history.go(-1);
                </script>
                <?php
    
                exit;
            }
            $rent_count = count($all_assosiate_rent_item);
           // print_r($rent_count);exit;
            $all_assosiate_rent[] = $all_assosiate_rent_item;
        }     
    }

    // get list of users
    $userlist[] = mosHTML :: makeOption('-1', '----------');
    $database->setQuery("SELECT id AS value, name AS text from #__users ORDER BY name");
    $userlist = array_merge($userlist, $database->loadObjectList());
    $usermenu = mosHTML :: selectList($userlist, 'userid', 'class="inputbox" size="1"', 'value', 'text', '-1');

    HTML_realestatemanager :: editRentHouses($option, $house1, $houses_rents_assoc,
     $title_assoc, $usermenu, $all_assosiate_rent, "edit_rent");
}


function rent_return($option, $bid) {
    
    global $database, $my;
    if (!is_array($bid) || count($bid) !== 1) {
        echo "<script> alert('". _REALESTATE_MANAGER_ADMIN_SELECT_ONE_ITEM .
          "'); window.history.go(-1);</script>\n";
        exit;
    }
    
    $bid_house = implode(',', $bid);
    $select = "SELECT a.*, cc.name AS category, l.id as rentid, l.rent_from as rent_from, " .
            "l.rent_return as rent_return, l.rent_until as rent_until, " .
            "l.user_name as user_name, l.user_email as user_email " .
            "\nFROM #__rem_houses AS a" .
            "\nLEFT JOIN #__rem_categories as hc on hc.iditem = a.id" .
            "\nLEFT JOIN #__rem_main_categories AS cc ON cc.id = hc.idcat" .
            "\nLEFT JOIN #__rem_rent AS l ON l.fk_houseid = a.id" .
            "\nWHERE a.id = $bid_house";
    $database->setQuery($select);
    $house1 = $database->loadObject();
    if ($house1->listing_type != 1) {
              ?>
              <script type = "text/JavaScript" language = "JavaScript">
                  alert("<?php echo _REALESTATE_MANAGER_ADMIN_NOT_FOR_RENT ?>");
                  window.history.go(-1);
              </script>
              <?php
  
              exit;
    }    
    $bids = implode(',', $bid);
    $bids = getAssociateHouses($bids);
    if($bids == "") $bids = implode(',', $bid);
    $houses_rents_assoc = array();
    $title_assoc = array();
    if($bids){
        $select = "SELECT a.*, cc.name AS category, l.id as rentid, l.rent_from as rent_from, " .
                "l.rent_return as rent_return, l.rent_until as rent_until, " .
                "l.user_name as user_name, l.user_email as user_email " .
                "\nFROM #__rem_houses AS a" .
                "\nLEFT JOIN #__rem_categories as hc on hc.iditem = a.id" .
                "\nLEFT JOIN #__rem_main_categories AS cc ON cc.id = hc.idcat" .
                "\nLEFT JOIN #__rem_rent AS l ON l.fk_houseid = a.id" .
                "\nWHERE a.id in ($bids)";
        $database->setQuery($select);
        $houses_rents_assoc = $database->loadObjectList();
      
        $select = "SELECT a.htitle " .
                "\nFROM #__rem_houses AS a" .
                "\nLEFT JOIN #__rem_rent AS l ON l.fk_houseid = a.id" .
                "\nWHERE a.id in ($bids)"; 
        $database->setQuery($select);
        $title_assoc = $database->loadObjectList();
    
    
        $count = count($houses_rents_assoc);
        for ($i = 0; $i < $count; $i++) {
            if ($houses_rents_assoc[$i]->listing_type != 1) {
                ?>
                <script type = "text/JavaScript" language = "JavaScript">
                    alert("<?php echo _REALESTATE_MANAGER_ADMIN_NOT_FOR_RENT_ASOC ?>");
                    window.history.go(-1);
                </script>
                <?php
                exit;
            }
        }
            
       
        $is_rent_out = false;
        for ($i = 0; $i < count($houses_rents_assoc); $i++) {
      
          if ( $houses_rents_assoc[$i]->rent_from != '' && $houses_rents_assoc[$i]->rent_return == '' )
          {
            $is_rent_out = true ;
            break ;
          }
        }        
        
        if ( !$is_rent_out )
        {
            ?>
            <script type = "text/JavaScript" language = "JavaScript">
                alert("<?php echo _REALESTATE_MANAGER_ADMIN_ALERT_NOT_IN_RENT ?>");
                window.history.go(-1);
            </script>
            <?php
            exit;
        }
    
        //check rent_return == null count for all assosiate
      $ids = explode(',', $bids);
      $rent_count = -1;
      $all_assosiate_rent = array();
      $count = count($ids);
      for ($i = 0; $i < $count; $i++) {
      
          $query = "SELECT * FROM #__rem_rent WHERE fk_houseid = " . $ids[$i] . 
           " and rent_return is null ORDER BY rent_from"; 
          // print_r($query);
          $database->setQuery($query);
          $all_assosiate_rent_item = $database->loadObjectList();
  
          if ( $rent_count != -1 && $rent_count != count($all_assosiate_rent_item) )
          {
              ?>
              <script type = "text/JavaScript" language = "JavaScript">
                  alert("<?php echo _REALESTATE_MANAGER_ADMIN_RENT_ASSOCIATED ?>");
                  window.history.go(-1);
              </script>
              <?php
              exit;
          }
          $rent_count = count($all_assosiate_rent_item);
         // print_r($rent_count);exit;
          $all_assosiate_rent[] = $all_assosiate_rent_item;
      }
    }
    // get list of users
    $userlist[] = mosHTML :: makeOption('-1', '----------');
    $database->setQuery("SELECT id AS value, name AS text from #__users ORDER BY name");
    $userlist = array_merge($userlist, $database->loadObjectList());
    $usermenu = mosHTML :: selectList($userlist, 'userid', 'class="inputbox" size="1"', 'value', 'text', '-1');

    HTML_realestatemanager :: editRentHouses($option, $house1, $houses_rents_assoc,
     $title_assoc, $usermenu, $all_assosiate_rent, "rent_return");
}

    
function saveRent($option, $bids, $task = "") {
    global $database, $realestatemanager_configuration; 
    if (!is_array($bids) || count($bids) < 1) {
      echo "<script> alert('Select an item to rent'); window.history.go(-1);</script>\n";
      exit;
    }
    $id = mosGetParam($_POST, 'id');
    $ids[] = $id ;  
    $ids = implode(',', $ids);
    $ids = getAssociateHouses($ids);
    if($ids == "")  $ids = $id;
    $ids = explode(',', $ids);
    //print_r($_POST);exit;
    $data = JFactory::getDBO();
    $houseid = mosGetParam($_POST, 'houseid');
    $rent_from = data_transform_rem(mosGetParam($_POST, 'rent_from'));
    $rent_until = data_transform_rem(mosGetParam($_POST, 'rent_until'));
    if ($rent_from > $rent_until) {
      echo "<script> alert('" . $rent_from . " more then " . $rent_until . "'); window.history.go(-1); </script>\n";
      exit();
    }   
    if ($task == "edit_rent") {
      $check_vids = implode(',', $bids);      
      if ($check_vids == 0 || count($bids) > 1){
        echo "<script> alert('". _REALESTATE_MANAGER_ADMIN_SELECT_ONE_ITEM ."'); window.history.go(-1);</script>\n";
        exit;
      }
      $rent = new mosRealEstateManager_rent($database);
      $a_ids = explode(',', $bids[0]);
      for($j = 0, $k = count($a_ids); $j < $k; $j++){
        $rent->load($a_ids[$j]);
        $query = "SELECT * FROM #__rem_rent where fk_houseid= " . $rent->fk_houseid . " AND rent_return is NULL ";
        $database->setQuery($query);
        $rentTerm = $database->loadObjectList();
        $rent_from = substr($rent_from, 0, 10);
        $rent_until = substr($rent_until, 0, 10);
        foreach ($rentTerm as $oneTerm){
          if ($a_ids[$j] == $oneTerm->id)                      
            continue;
          $oneTerm->rent_from = substr($oneTerm->rent_from, 0, 10);
          $oneTerm->rent_until = substr($oneTerm->rent_until, 0, 10);
          $returnMessage = checkRentDayNightREM (($oneTerm->rent_from),
            ($oneTerm->rent_until), $rent_from, $rent_until, $realestatemanager_configuration);
          if($a_ids[$j] !== $oneTerm->id && strlen($returnMessage) > 0){                 
            echo "<script> alert('$returnMessage'); window.history.go(-1); </script>\n";          
            exit;
          }       
        }
        $rent->rent_from = $rent_from;
        if (mosGetParam($_POST, 'rent_until') != ""){
          $rent->rent_until = data_transform_rem(mosGetParam($_POST, 'rent_until'));
        } else{
            $rent->rent_until = null;
        }
        $rent->fk_houseid = $ids[$i];
        // $rent->fk_houseid = $id;
        $userid = mosGetParam($_POST, 'userid');
            if ($userid == "-1") {
                $rent->user_name = mosGetParam($_POST, 'user_name', '');
                $rent->user_email = mosGetParam($_POST, 'user_email', '');
            } else {
                $rent->getRentTo(intval($userid));
            }

            if (!$rent->check($rent)) {
                echo "<script> alert('" . addslashes($rent->getError()) .
                  "'); window.history.go(-1); </script>\n";
                exit();
            }

            if (!$rent->store()) {
                echo "<script> alert('" . addslashes($rent->getError()) .
                 "'); window.history.go(-1); </script>\n";
                exit();
            }
            $rent->checkin(); 
          }
    }
    
    if ($task !== "edit_rent") {
      $checkh = mosGetParam($_POST, 'checkHouse');
      if ($checkh != "on") {
          echo "<script> alert('". _REALESTATE_MANAGER_ADMIN_SELECT_ONE_ITEM .
            "'); window.history.go(-1);</script>\n";
          exit;
      }  
      for($i = 0, $n = count($ids); $i < $n; $i++){
          $rent = new mosRealEstateManager_rent($database);
            $query = "SELECT * FROM #__rem_rent where fk_houseid= " . $ids[$i] . " AND rent_return is NULL ";
            $database->setQuery($query);
            $rentTerm = $database->loadObjectList();
            $rent_from = substr($rent_from, 0, 10);
            $rent_until = substr($rent_until, 0, 10);
            foreach ($rentTerm as $oneTerm){
                $oneTerm->rent_from = substr($oneTerm->rent_from, 0, 10);
                $oneTerm->rent_until = substr($oneTerm->rent_until, 0, 10);
                $returnMessage = checkRentDayNightREM (($oneTerm->rent_from),
                  ($oneTerm->rent_until), $rent_from, $rent_until, $realestatemanager_configuration);
                if(strlen($returnMessage) > 0){                 
                    echo "<script> alert('$returnMessage'); window.history.go(-1); </script>\n";          
                    exit;
                }       
            }
            
            $rent->rent_from = $rent_from;
            $rent->rent_until = $rent_until;
            $rent->fk_houseid = $ids[$i];
            $userid = mosGetParam($_POST, 'userid');

            if ($userid == "-1") {
                $rent->user_name = mosGetParam($_POST, 'user_name', '');
                $rent->user_email = mosGetParam($_POST, 'user_email', '');
            } else {
                $rent->getRentTo(intval($userid));
                $rent->fk_userid = intval($userid);
            }

            if (!$rent->check($rent)) {
                echo "<script> alert('" . addslashes($rent->getError()) .
                 "'); window.history.go(-1); </script>\n";
                exit();
            }

            if (!$rent->store()) {
                echo "<script> alert('" . addslashes($rent->getError()) .
                 "'); window.history.go(-1); </script>\n";
                exit();
            }

            $rent->checkin();
            $house = new mosRealEstateManager($database);
            $house->load($ids[$i]);
            $house->fk_rentid = $rent->id;
            $house->store();
            $house->checkin();
        }
    }
        mosRedirect("index.php?option=$option");  
}

function saveRent_return($option, $lids) {

    global $database;
    $houseid = mosGetParam($_POST, 'houseid');
    $id = mosGetParam($_POST, 'id'); 
    $check_vids = implode(',', $lids);      
      if ($check_vids == 0 || count($lids) > 1)
      {
          echo "<script> alert('". _REALESTATE_MANAGER_ADMIN_SELECT_ONE_ITEM .
            "'); window.history.go(-1);</script>\n";
          exit;
      }

    $r_ids = explode(',', $lids[0]);   
    $rent = new mosRealEstateManager_rent($database);
    for ($i = 0, $n = count($r_ids); $i < $n; $i++) {
        
        $rent->load($r_ids[$i]); 
    
        if ($rent->rent_return != null) {
            echo "<script> alert('". _REALESTATE_MANAGER_ADMIN_RENT_ALERT_RETURNED .
             "'); window.history.go(-1);</script>\n";
            exit;
        }

        $rent->rent_return = date("Y-m-d H:i:s");

        if (!$rent->check($rent)) {
            echo "<script> alert('" . addslashes($rent->getError()) .
             "'); window.history.go(-1); </script>\n";
            exit;
        }

        if (!$rent->store()) {
            echo "<script> alert('" . addslashes($rent->getError()) .
             "'); window.history.go(-1); </script>\n";
            exit;
        }

        $rent->checkin();

        $is_update_house_lend = true;
        if ($is_update_house_lend) {
            
            $house = new mosRealEstateManager($database);
            $house->load($id);
           
            $query = "SELECT * FROM #__rem_rent where fk_houseid= " . $id . " AND rent_return is NULL";
            $database->setQuery($query);
            $info_rents = $database->loadObjectList();

            if (isset($info_rents[0])) {
                $house->fk_rentid = $info_rents[0]->id;
                $is_update_house_lend = FALSE;
            } else {
                $house->fk_rentid = 0;
            }

            $house->store();
            $house->checkin();
        }
    }

        mosRedirect("index.php?option=$option");
}

function showLanguageManager($option) {
    global $database, $mainframe, $mosConfig_list_limit, $menutype, $mosConfig_absolute_path;

    $section = "com_realestatemanager";

    $search['const'] = mosGetParam($_POST, 'search_const', '');
    $search['const_value'] = mosGetParam($_POST, 'search_const_value', '');
    $search['languages'] = $mainframe->getUserStateFromRequest(
      "search_languages{$option}", 'search_languages', '');
    $search['sys_type']  = $mainframe->getUserStateFromRequest(
      "search_sys_type{$option}", 'search_sys_type', '');

    $where_query = array();
    if ($search['const'] != '')
        $where_query[] = "c.const LIKE '%" . $search['const'] . "%'";
    if ($search['const_value'] != '')
        $where_query[] = "cl.value_const LIKE '%" . $search['const_value'] . "%'";
    if ($search['languages'] != '')
        $where_query[] = "cl.fk_languagesid = " .$database->quote( $search['languages']) . " ";
    if ($search['sys_type'] != '')
        $where_query[] = "c.sys_type LIKE '%" . $search['sys_type'] . "%'";

    $where = "";
    $i = 0;
    if (count($where_query) > 0)
        $where = "WHERE ";
    foreach ($where_query as $item) {
        if ($i == 0)
            $where .= "( $item ) ";
        else
            $where .= "AND ( $item ) ";
        $i++;
    }

    $query = "SELECT cl.id, cl.value_const, c.sys_type, l.title, c.const ";
    $query .= "FROM #__rem_const_languages as cl ";
    $query .= "LEFT JOIN #__rem_languages AS l ON cl.fk_languagesid=l.id ";
    $query .= "LEFT JOIN #__rem_const AS c ON cl.fk_constid=c.id $where";

    $database->setQuery($query);
    $const_languages = $database->loadObjectList();

    $sectionid = $mainframe->getUserStateFromRequest("sectionid{$section}{$section}", 'sectionid', 0);
    $limit = $mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mosConfig_list_limit);
    $limitstart = $mainframe->getUserStateFromRequest("view{$section}limitstart", 'limitstart', 0);
    $levellimit = $mainframe->getUserStateFromRequest("view{$option}limit$menutype", 'levellimit', 10);

    $total = count($const_languages);

    $pageNav = new JPagination($total, $limitstart, $limit); // for J 1.6

    $const_languages = array_slice($const_languages, $pageNav->limitstart, $pageNav->limit);

    $query = "SELECT sys_type FROM #__rem_const GROUP BY sys_type";
    $database->setQuery($query);
    $sys_types = $database->loadObjectList();

    $sys_type_row[] = mosHTML::makeOption('', '--Select sys type--');
    foreach ($sys_types as $sys_type) {
        $sys_type_row[] = mosHTML::makeOption($sys_type->sys_type, $sys_type->sys_type);
    }

    $search['sys_type'] = mosHTML :: selectList($sys_type_row, 'search_sys_type',
     'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value',
      'text', $search['sys_type']);

    $query = "SELECT id, title FROM #__rem_languages";
    $database->setQuery($query);
    $languages = $database->loadObjectList();

    $languages_row[] = mosHTML::makeOption('', '--Select language--');
    foreach ($languages as $language) {
        $languages_row[] = mosHTML::makeOption($language->id, $language->title);
    }

    $search['languages'] = mosHTML :: selectList($languages_row, 'search_languages',
     'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value',
      'text', $search['languages']);

    if(JRequest::getVar('task','') == 'loadLang'){
        loadConstRem();
        language_check();  
    }

    HTML_realestatemanager :: showLanguageManager($const_languages, $pageNav, $search);
}

function editLanguageManager($section = '', $bid = 0) {
    global $database, $my, $acl, $realestatemanager_configuration;
    global $mosConfig_absolute_path, $mosConfig_live_site;

    $row = new mosRealEstateManager_language($database); // for 1.6
    // load the row from the db table
    $row->load($bid);

    $query = "SELECT * FROM #__rem_const WHERE id = " . $row->fk_constid;
    $database->setQuery($query);
    $const = $database->loadObject();

    $lists['const'] = $const->const;
    $lists['sys_type'] = $const->sys_type;

    $query = "SELECT title FROM #__rem_languages WHERE id = " . $row->fk_languagesid;
    $database->setQuery($query);
    $language = $database->loadResult();

    $lists['languages'] = $language;

    HTML_realestatemanager::editLanguageManager($row, $lists);
}

function saveLanguageManager() {
    global $database, $mosConfig_absolute_path;

    $row = new mosRealEstateManager_language($database); // for 1.6
    $post = JRequest::get('post', JREQUEST_ALLOWHTML);

    if (!$row->bind($post)) {
        echo "<script> alert(\"" . $row->getError() . "\"); window.history.go(-1); </script>\n";
        exit();
    }

    if (!$row->check()) {
        echo "<script> alert(\"" . $row->getError() . "\"); window.history.go(-1); </script>\n";
        exit();
    }

    if (!$row->store()) {
        echo "<script> alert(\"" . $row->getError() . "\"); window.history.go(-1); </script>\n";
        exit();
    }

    mosRedirect('index.php?option=com_realestatemanager&section=language_manager');
}

function cancelLanguageManager() {
    global $database, $mosConfig_absolute_path;

    $row = new mosRealEstateManager_language($database); // for 1.6
    $row->bind($_POST);
    mosRedirect('index.php?option=com_realestatemanager&section=language_manager');
}

function showFeaturedManager($option) {
    global $database, $mainframe, $mosConfig_list_limit, $menutype, $lists;

    $section = "com_realestatemanager";

    $query = "SELECT * FROM #__rem_feature";
    $database->setQuery($query);
    $features = $database->loadObjectList();

    $sectionid = $mainframe->getUserStateFromRequest("sectionid{$section}{$section}", 'sectionid', 0);
    $limit = $mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mosConfig_list_limit);
    $limitstart = $mainframe->getUserStateFromRequest("view{$section}limitstart", 'limitstart', 0);
    $levellimit = $mainframe->getUserStateFromRequest("view{$option}limit$menutype", 'levellimit', 10);

    $total = count($features);

    $pageNav = new JPagination($total, $limitstart, $limit); // for J 1.6

    $features = array_slice($features, $pageNav->limitstart, $pageNav->limit);

    HTML_realestatemanager :: showFeaturedManager($features, $pageNav, $lists);
}

function editFeaturedManager($section = '', $uid = 0) {
    global $database, $my, $acl, $realestatemanager_configuration;
    global $mosConfig_absolute_path, $mosConfig_live_site;

    $row = new mosRealEstateManager_feature($database); // for 1.6
    // load the row from the db table
    $row->load($uid);

    // build the html radio buttons for published
    $lists['published'] = mosHTML::yesnoRadioList('published', 'class="inputbox"', $row->published);

    //Select list for number of doors
    $categories[] = mosHtml::makeOption("", _REALESTATE_MANAGER_OPTION_SELECT);
	if(trim($realestatemanager_configuration['featuredmanager']['placeholder'] != '')){
		$categ = explode(',', $realestatemanager_configuration['featuredmanager']['placeholder']);
		for ($i = 0; $i < count($categ); $i++){
			$categories[] = mosHtml::makeOption($categ[$i], $categ[$i]);
		}
	}
	$lists['categories'] = mosHTML :: selectList($categories, 'categories',
	 'class="inputbox" size="1"', 'value', 'text', $row->categories);

    HTML_realestatemanager::editFeaturedManager($row, $lists);
}

function saveFeaturedManager() {
    global $database, $mosConfig_absolute_path;

    $row = new mosRealEstateManager_feature($database); // for 1.6
    $post = JRequest::get('post', JREQUEST_ALLOWHTML);
   
    if (!$row->bind($post)) {
        echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
        exit();
    }

    if (!$row->check()) {
        echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
        exit();
    }

    if (!$row->store()) {
        echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
        exit();
    }

    mosRedirect('index.php?option=com_realestatemanager&section=featured_manager');
}

function cancelFeaturedManager() {
    global $database;
    $row = new mosRealEstateManager_feature($database); // for 1.6
    $row->bind($_POST);
    mosRedirect('index.php?option=com_realestatemanager&section=featured_manager');
}

function removeFeaturedManager($section, $fids) {
    global $database;

    if (count($fids) < 1) {
        echo "<script> alert('". _REALESTATE_MANAGER_ADMIN_ONE_AMENITY_ALERT ."'); window.history.go(-1);</script>\n";
        exit;
    }

    foreach ($fids as $fid)
        removeFeaturedManagerFromDB($fid);

    mosRedirect('index.php?option=com_realestatemanager&section=featured_manager');
}

function removeFeaturedManagerFromDB($fid) {
    global $database, $my, $mosConfig_absolute_path;

    $database->setQuery("SELECT image_link FROM #__rem_feature WHERE id=$fid");
    $image_link = $database->loadResult();
    unlink($mosConfig_absolute_path . '/components/com_realestatemanager/featured_ico/' . $image_link);

    $sql = "DELETE FROM #__rem_feature WHERE id = $fid ";
    $database->setQuery($sql);
    $database->query();
}

function publishFeaturedManager($section, $featureid = null, $cid = null, $publish = 1) {
    global $database, $my;

    if (!is_array($cid))
        $cid = array();
    if ($featureid)
        $cid[] = $featureid;

    if (count($cid) < 1) {
        $action = $publish ? _PUBLISH : _DML_UNPUBLISH;
        echo "<script> alert('" . _DML_SELECTCATTO . " $action'); window.history.go(-1);</script>\n";
        exit;
    }

    $cids = implode(',', $cid);

    $query = "UPDATE #__rem_feature SET published='$publish'"
            . "\nWHERE id IN ($cids)";
    $database->setQuery($query);
    if (!$database->query()) {
        echo "<script> alert(\"" . $database->getErrorMsg() . "\"); window.history.go(-1); </script>\n";
        exit();
    }

    if (count($cid) == 1) {
        $row = new mosRealEstateManager_feature($database); // for 1.6
        $row->checkin($cid[0]);
    }
    mosRedirect('index.php?option=com_realestatemanager&section=featured_manager');
}

function save_featured_category($option) {
    global $realestatemanager_configuration;

    $realestatemanager_configuration['featuredmanager']['placeholder'] =
     mosGetParam($_POST, 'featuredmanager_placeholder', "");

    mosRealestateManagerOthers :: setParams();
}

function rent_history($option, $bid) {
  global $database, $my,$mainframe;
  if (!is_array($bid) || count($bid) !== 1) {
    echo "<script> alert('". _REALESTATE_MANAGER_ADMIN_SELECT_ONE_ITEM ."'); window.history.go(-1);</script>\n";
    exit;
  }
  $bid_house = implode(',', $bid);
  $select = "SELECT a.*, cc.name AS category, l.id as rentid, l.rent_from as rent_from, " .
          "l.rent_return as rent_return, l.rent_until as rent_until, " .
          "l.user_name as user_name, l.user_email as user_email " .
          "\nFROM #__rem_houses AS a" .
          "\nLEFT JOIN #__rem_categories as hc on hc.iditem = a.id" .
          "\nLEFT JOIN #__rem_main_categories AS cc ON cc.id = hc.idcat" .
          "\nLEFT JOIN #__rem_rent AS l ON l.id = a.fk_rentid" .
          "\nWHERE a.id = $bid_house";
  $database->setQuery($select);
  $house1 = $database->loadObject();
  if ($house1->listing_type != 1) {
    ?>
    <script type = "text/JavaScript" language = "JavaScript">
      alert("<?php echo _REALESTATE_MANAGER_ADMIN_NOT_FOR_RENT ?>");
      window.history.go(-1);
    </script>
    <?php
    exit;
  }
  $bids = implode(',', $bid);
  $bids = getAssociateHouses($bids);
  $houses_assoc[]= $house1;
  if($bids){
    $select = "SELECT a.*, cc.name AS category, l.id as rentid, l.rent_from as rent_from, " .
            "l.rent_return as rent_return, l.rent_until as rent_until, " .
            "l.user_name as user_name, l.user_email as user_email " .
            "\nFROM #__rem_houses AS a" .
            "\nLEFT JOIN #__rem_categories as hc on hc.iditem = a.id" .
            "\nLEFT JOIN #__rem_main_categories AS cc ON cc.id = hc.idcat" .
            "\nLEFT JOIN #__rem_rent AS l ON l.id = a.fk_rentid" .
            "\nWHERE a.id in ($bids)";
    $database->setQuery($select);
    $houses_assoc = $database->loadObjectList(); 
    //for rent or not
    $count = count($houses_assoc);
    for ($i = 0; $i < $count; $i++) {
      if ($houses_assoc[$i]->listing_type != 1) {
        ?>
        <script type = "text/JavaScript" language = "JavaScript">
          alert("<?php echo _REALESTATE_MANAGER_ADMIN_NOT_FOR_RENT_ASOC ?>");
          window.history.go(-1);
        </script>
        <?php
        exit;
      }
    }
  }
  HTML_realestatemanager::showRentHistory($option, $house1, $houses_assoc, $usermenu, "rent");
}

function users_rent_history($option, $bid){
    global $database, $my,$mainframe;
    $owner = $mainframe->getUserStateFromRequest("owner_h{$option}", 'owner_h', '-1'); //add nik    
    $allrent = '';
    if($owner !=-1){
        $select = "SELECT l.rent_from as rent_from, " .
                "a.htitle, a.houseid,l.rent_return as rent_return, l.rent_until as rent_until, " .
                "l.user_name as user_name, l.user_email as user_email " .
                "\n FROM #__rem_rent AS l" .
                "\n LEFT JOIN #__rem_houses AS a ON l.fk_houseid = a.id" .
                "\n WHERE l.user_name = ".$database->Quote($owner).
                "\n OR l.fk_userid = ".$database->Quote($owner) ." ORDER BY l.rent_return";
        $database->setQuery($select);
        $allrent = $database->loadObjectList();
    }
    $userlist[] = mosHTML :: makeOption('-1', 'Select User');
    $database->setQuery("SELECT DISTINCT fk_userid AS value, user_name AS text from #__rem_rent ORDER BY user_name");
    $userlist = array_merge($userlist, $database->loadObjectList());
    foreach ($userlist as $value) {
        if(!$value->value)
            $value->value = $value->text;
    }
    $usermenu = mosHTML :: selectList($userlist, 'owner_h', 'class="inputbox input-medium" size="1" 
        onchange="document.adminForm.submit();"', 'value', 'text', $owner);

    HTML_realestatemanager::showUsersRentHistory($option, $allrent,  $usermenu);
}

function checkFile() {
    $path = $_GET["path"];
    $filename = basename($_GET["file"]);
    $file = $path . $filename;
    if (file_exists($file)) {
        echo "The file with such name already is!";
    } else {
        echo "";
    }
}
