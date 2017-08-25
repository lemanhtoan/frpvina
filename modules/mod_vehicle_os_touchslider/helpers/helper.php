<?php
/*
* @version 2.1
* @package VehicleManager - slideShow
* @copyright 2012 OrdaSoft
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @author 2012 Andrey Kvasnekskiy (akbet@ordasoft.com )
* @description VehicleManager - slideShow for Vehicle Manager Component
*/

// no direct access
defined('_JEXEC') or die ('Restricted access');
$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'] = JPATH_SITE;
require_once ($mosConfig_absolute_path .
     "/components/com_vehiclemanager/functions.php");
if (!array_key_exists('vehiclemanager_configuration', $GLOBALS))
{
    require_once ($mosConfig_absolute_path .
     "/administrator/components/com_vehiclemanager/admin.vehiclemanager.class.conf.php");
    $GLOBALS['vehiclemanager_configuration'] = $vehiclemanager_configuration;
} else
    global $vehiclemanager_configuration;

if(!class_exists('modVehSlideShowHelper')) {
    class modVehSlideShowHelper  {

      static function getWhereUsergroupsString( $table_alias ) {
        global $my;

        if ( isset($my->id) AND $my->id != 0 ) { 
          $usergroups_sh = modVehSlideShowHelper::getGroupsByUser ($my->id,'');       
          //$usergroups_sh = '-2'.$usergroups_sh;   
        } else {
           $usergroups_sh = array ();
        }
        $usergroups_sh[] = -2; 
        $s = '';
        for ($i=0; $i<count($usergroups_sh); $i++) {  
          $g = $usergroups_sh[$i];
          $s .= " $table_alias.params LIKE '%,{$g}' or " .
           " $table_alias.params = '{$g}' or $table_alias.params LIKE '{$g},%' or " .
            " $table_alias.params LIKE '%,{$g},%' ";
          if ( ($i+1)<count($usergroups_sh) )
            $s .= ' or ';
        } 
        return $s;
      } 

      static function getGroupsByUser ( $uid, $recurse ) {
           $database = JFactory::getDBO() ;
          $usergroups = array ();

          if ( $recurse == 'RECURSE' ) {
          // [1]: Recurse getting the usergroups
            $id_group = array ();
            $q1 = "SELECT group_id FROM `#__user_usergroup_map` WHERE user_id={$uid}";
            $database->setQuery($q1);
            $rows1 = $database->loadObjectList();
            foreach ($rows1 as $v) {
              $id_group[] = $v->group_id;
            }
            for ($k=0; $k<count($id_group); $k++) {
              $q = "SELECT g2.id FROM `#__usergroups` g1 " .
               " LEFT JOIN `#__usergroups` g2 ON g1.lft > g2.lft AND g1.lft < g2.rgt " .
                " WHERE g1.id={$id_group[$k]} ORDER BY g2.lft";
              $database->setQuery($q);
              $rows = $database->loadObjectList();
              foreach ($rows as $r) {
                $usergroups[] = $r->id;
              }
            }
            $usergroups = array_unique($usergroups);
          }
          return $usergroups;        
      }

      static function sefRelToAbs($value) {
        //Need check!!!
        // Replace all &amp; with & as the router doesn't understand &amp;
        $url = str_replace('&amp;', '&', $value);
        if(substr(strtolower($url),0,9) != "index.php") return $url;
        $uri    = JURI::getInstance();
        $prefix = $uri->toString(array('scheme', 'host', 'port'));
        return $prefix.JRoute::_($url);
      }

      static function getImagesFromVehSlideShow($params,$langContent) {
        global $vehiclemanager_configuration;
          $mosConfig_absolute_path=JPATH_BASE;
          $mosConfig_live_site=JURI::base(true);
          $image_source_type = $params->get('image_source_type');
          switch ($image_source_type) {
            case "1": 
              $img_height = $vehiclemanager_configuration['fotomain']['high'];
                      $img_width = $vehiclemanager_configuration['fotomain']['width'];
                break;
            case "2": 
                $img_height = $vehiclemanager_configuration['foto']['high'];
                $img_width = $vehiclemanager_configuration['foto']['width'];
                break;
            default:$img_height = $vehiclemanager_configuration['fotoupload']['high'];
                $img_width = $vehiclemanager_configuration['fotoupload']['width'];
                break;
          }
        if(!is_numeric($max = $params->get('count_car'))) $max = 20;

        if(!is_numeric($limit_title = $params->get('limit_title'))) $limit_title = 15;

        $cat_id = trim($params->get('cat_id'));
        if($cat_id != "" ) $cat_id = " and c.id in (". $cat_id .")" ;

        $car_id = trim($params->get('car_id') );
        if($car_id != "" ) $car_id = " and v.id in ( ". $car_id ." ) " ;

        // build query to get slides
        $db = JFactory::getDBO();

        $s=modVehSlideShowHelper::GetWhereUserGroupsString("c");
        $temp_sort=$params->get('sort_by');

        switch($temp_sort) {
          case 4 : $sql_sort_top = ' CAST( v.hits AS SIGNED) ASC ' ; break;
          case 3 : $sql_sort_top = ' v.vtitle ASC ' ; break;
          case 2 : $sql_sort_top = ' CAST( v.price AS SIGNED) ASC '; break;
          case 1 : $sql_sort_top = ' v.date DESC '; break;
          case 0 : $sql_sort_top = ' RAND() '; break;
        }

        if (isset($langContent))
        {
            $lang = $langContent;
            $query = "SELECT lang_code FROM #__languages WHERE sef = '$lang'";
            $db->setQuery($query);
            $lang = $db->loadResult();
            $lang = " and (v.language like 'all' or v.language like '' or " .
             " v.language like '*' or v.language is null or v.language like '$lang') " .
              " AND (c.language like 'all' or c.language like '' or c.language like '*' " .
               " or c.language is null or c.language like '$lang') ";
        } else
        {
            $lang = "";
        }


        $selectstring = "SELECT v.vtitle AS title,v.id,v.image_link as src, v.description, " .
         " v.hits,v.price,v.priceunit,v.vlocation, v.country, v.city, v.date,vc.idcat
            \nFROM #__vehiclemanager_vehicles AS v
            \nLEFT JOIN #__vehiclemanager_categories AS vc ON vc.iditem=v.id
            \nLEFT JOIN #__vehiclemanager_main_categories AS c ON c.id=vc.idcat
            \nWHERE ($s) $lang and v.published=1 and v.approved=1 and c.published = 1 " .
             $cat_id . $car_id . " ORDER BY " . $sql_sort_top . " LIMIT 0, $max;";

        $db->setQuery($selectstring);
        $slides = $db->loadObjectList();      

        foreach($slides as $slide){
          $slide->address = modVehSlideShowHelper::getSlideAddress($slide,$params->get('limit_address'));
          $slide->price = $slide->price;
          $slide->priceunit = $slide->priceunit;
          $slide->image = vm_picture_thumbnail($slide->src,$img_height,$img_width);
          $slide->link = modVehSlideShowHelper::getSlideLink($slide,$params);
          $slide->description = modVehSlideShowHelper::getSlideDescription($slide, $params->get('limit_desc'));
          if(strlen($slide->title)>$limit_title)
             $slide->title = substr($slide->title, 0, $limit_title)."..";
        }

        return $slides;
      }
     
      static function getSlideLink($slide,$params) {
          $link = '';
          $db = JFactory::getDBO();

          if( $params->get('ItemId', '') != "" ) {
            $ItemId_tmp=$params->get('ItemId', '');
          } else {
            $selectstring = "SELECT id  FROM #__menu WHERE menutype like '%menu%' AND " .
             " link LIKE '%option=com_vehiclemanager%' AND params LIKE '%back_button%' ";
            $db->setQuery($selectstring);
            $ItemId_tmp_from_db = $db->loadResult();
            $ItemId_tmp=$ItemId_tmp_from_db;
          }

          $link = 'index.php?option=com_vehiclemanager&amp;task=view_vehicle&amp;id=' .
            $slide->id . '&amp;catid=' . $slide->idcat . '&amp;Itemid=' . $ItemId_tmp;

          return modVehSlideShowHelper::sefRelToAbs($link);

      }
      static function isHttpUrl($url)
      {
          $findme   = 'http';
          $pos = strpos($url, $findme);
          if($pos === false) $url = JURI::base().'components/com_vehiclemanager/photos/'.$url;
          return $url;
      }

      static function getSlideDescription($slide, $limit) {
        $desc = strip_tags($slide->description);
        if($limit && $limit < strlen($desc)) {
          $limit = strpos($desc, ' ', $limit);
          $desc = substr($desc, 0, $limit);
          if(preg_match('/[A-Za-z0-9]$/', $desc)) $desc.=' ...';
          $desc = nl2br($desc);
        } else { // no limit or limit greater than description
          $desc = $slide->description;
        }
        return $desc;
      }

      static function getSlideAddress($slide, $limit) {

        $address = "";

        $tmp = trim(strip_tags($slide->country));
        if ($tmp != "")
            $address = $tmp;

        $tmp = trim(strip_tags($slide->city));
        if ($tmp != "" && $address != "")
            $address .= ", " . $tmp;
        else if ($tmp != "")
            $address = $tmp;

        $tmp = trim(strip_tags($slide->vlocation));
        if ($tmp != "" && $address != "")
            $address .= ", " . $tmp;
        else if ($tmp != "")
            $address = $tmp;

        if ($limit && $limit < strlen($address)) {
            $limit = strpos($address, ' ', $limit);
            $address = substr($address, 0, $limit);
            if (preg_match('/[A-Za-z0-9]$/', $address))
                $address.=' ...';
            $address = nl2br($address);
        }
        return $address;
      }
  }
}
