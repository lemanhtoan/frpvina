<?php
/*
 * @package OS_Touch_Slider
 * @subpackage  mod_realestate_os_touchslider
 * @copyright Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru);
 * @Homepage: http://www.ordasoft.com
 * @version: 1.2
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die ('Restricted access');

if(!class_exists('modRemOsTouchSliderHelper')) {
  class modRemOsTouchSliderHelper {

      static function getWhereUsergroupsString( $table_alias ) {
          global $my;

          if ( isset($my->id) AND $my->id != 0 ) {
            $usergroups_sh = modRemOsTouchSliderHelper::getGroupsByUser ($my->id,'');
            //$usergroups_sh = '-2'.$usergroups_sh;
          } else {
             $usergroups_sh = array ();
          }
          $usergroups_sh[] = -2;
          $s = '';
          for ($i=0; $i<count($usergroups_sh); $i++) {
            $g = $usergroups_sh[$i];
            $s .= " $table_alias.params LIKE '%,{$g}' or $table_alias.params = '{$g}' " .
             " or $table_alias.params LIKE '{$g},%' or $table_alias.params LIKE '%,{$g},%' ";
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

      static function sefRelToAbs( $value ) {
        //Need check!!!
        // Replace all &amp; with & as the router doesn't understand &amp;
        $url = str_replace('&amp;', '&', $value);
        if(substr(strtolower($url),0,9) != "index.php") return $url;
        $uri    = JURI::getInstance();
        $prefix = $uri->toString(array('scheme', 'host', 'port'));
        return $prefix.JRoute::_($url);
      }

      static function getImagesFromRemSlideShow($params,$langContent) {

        if(!is_numeric($max = $params->get('count_house'))) $max = 20;
        if(!is_numeric($limit_title = $params->get('limit_title'))) $limit_title = 15;

        $cat_id = trim($params->get('cat_id') );
        if($cat_id != "" ) $cat_id = " and c.id in ( ". $cat_id ." ) " ;

        $house_id = trim($params->get('house_id') );
        if($house_id != "" ) $house_id = " and h.id in ( ". $house_id ." ) " ;

        // build query to get slides
        $db = JFactory::getDBO();

        $s=modRemOsTouchSliderHelper::GetWhereUserGroupsString("c");
        $temp_sort=$params->get('sort_by');

         if (isset($langContent)) {

            $lang = $langContent;
            $query = "SELECT lang_code FROM #__languages WHERE sef = '$lang'";
            $db->setQuery($query);
            $lang = $db->loadResult();
            $lang = " and ( h.language = '$lang' or h.language like 'all' or h.language like '' " .
               " or h.language like '*' or h.language is null) " .
               " AND ( c.language = '$lang' or c.language like 'all' or c.language like '' " .
               " or c.language like '*' or c.language is null) ";
        } else {
            $lang = "";
        }

        switch($temp_sort) {
            case 4 : $sql_sort_top = ' CAST( h.hits AS SIGNED) ASC ' ; break;
            case 3 : $sql_sort_top = ' h.htitle ASC ' ; break;
            case 2 : $sql_sort_top = ' CAST( h.price AS SIGNED) ASC '; break;
            case 1 : $sql_sort_top = ' h.date DESC '; break;
            case 0 : $sql_sort_top = ' RAND() '; break;
        }

        $selectstring = "SELECT h.htitle AS title,h.id,h.image_link as src, h.description , " .
           " h.hits,h.price,h.priceunit,h.hlocation, h.hcountry, h.hregion, h.hcity, h.date,hc.idcat
          \nFROM #__rem_houses AS h
          \nLEFT JOIN #__rem_categories AS hc ON hc.iditem=h.id
          \nLEFT JOIN #__rem_main_categories AS c ON c.id=hc.idcat
          \nWHERE  ($s) $lang and  h.published=1 and h.approved=1 and c.published = 1 ".
          $cat_id . $house_id . " ORDER BY ".$sql_sort_top." LIMIT 0, $max;";

        $db->setQuery($selectstring);
        $slides = $db->loadObjectList();

        foreach($slides as $slide){
            $slide->address =
             modRemOsTouchSliderHelper::getSlideAddress($slide,$params->get('limit_address'));
            $slide->price = $slide->price;
            $slide->priceunit = $slide->priceunit;
            $slide->image = modRemOsTouchSliderHelper::getSlideImage($slide,$params);
            $slide->link = modRemOsTouchSliderHelper::getSlideLink($slide,$params);
            $slide->description =
             modRemOsTouchSliderHelper::getSlideDescription($slide, $params->get('limit_desc'));
            if(strlen($slide->title)>$limit_title)
               $slide->title = substr($slide->title, 0, $limit_title)."..";
        }
        return $slides;
      }

      static function getSlideImage($slide,$params) {
        global $realestatemanager_configuration;
        $image_source_type = $params->get('image_source_type');
        $mosConfig_absolute_path=JPATH_BASE;
        $mosConfig_live_site=JURI::base(true);
        if($realestatemanager_configuration['thumb_param']['show'] == 1)
          $index = "_2_";
        else
          $index = "_1_";
        $imageURL = $slide->src ;
        if($imageURL!=''){
            $file_pth= pathinfo($imageURL);
            $file_type=".".$file_pth['extension'];
            if(array_key_exists  ( 'filename' , $file_pth  ) ) $file_name=$file_pth['filename'];
            else $file_name = substr($imageURL, 0,strlen($imageURL)-strlen($file_pth['extension'] ) -1 );

            $file1=$mosConfig_absolute_path . '/components/com_realestatemanager/photos/'.
             $file_name . $file_type;
            $img1= './components/com_realestatemanager/photos/'. $file_name . $file_type;
            if($image_source_type == 0 ) {
              if( file_exists($file1) ) return $img1;
            }

            $file2 = $mosConfig_absolute_path .'/components/com_realestatemanager/photos/'.
                      $file_name ."_".$realestatemanager_configuration['fotomain']['high']
                      ."_".$realestatemanager_configuration['fotomain']['width'].$index. $file_type;
            $img2 = '/components/com_realestatemanager/photos/'.
                      $file_name ."_".$realestatemanager_configuration['fotomain']['high']
                      ."_".$realestatemanager_configuration['fotomain']['width'].$index. $file_type;
            if($image_source_type == 1 ) {
              if( file_exists($file2) ) return $img2;
              else return $img1;
            }

            if($image_source_type == 2 ) {
              $file3 = $mosConfig_absolute_path .'/components/com_realestatemanager/photos/'.
                      $file_name ."_".$realestatemanager_configuration['fotogallery']['high']
                      ."_".$realestatemanager_configuration['fotogallery']['width'].$index. $file_type;
              $img3 = '/components/com_realestatemanager/photos/'.
                      $file_name ."_".$realestatemanager_configuration['fotogallery']['high']
                      ."_".$realestatemanager_configuration['fotogallery']['width'].$index. $file_type;
              if( file_exists($file3) ) return $img3;
              else if( file_exists($file2) ) return $img2;
              else return $img1;
            }
        }
        else $imageURL = "./components/com_realestatemanager/images/no-img_eng_big.gif";
        return $imageURL ;
      }
      static function isHttpUrl($url) 
      {
          $findme   = 'http';
          $pos = strpos($url, $findme);
          if($pos === false) $url = JURI::base().$url;
          return $url;
      }

      static function getSlideLink($slide,$params) {
        $link = '';
        $db = JFactory::getDBO();

        if( $params->get('ItemId', '') != "" ) {
          $ItemId_tmp=$params->get('ItemId', '');
        } else {
          $selectstring = "SELECT id  FROM #__menu " .
           " WHERE menutype like '%menu%' AND link LIKE '%option=com_realestatemanager%' " .
           " AND params LIKE '%back_button%' ";
          $db->setQuery($selectstring);
          $ItemId_tmp_from_db = $db->loadResult();
          $ItemId_tmp=$ItemId_tmp_from_db;
        }

        $link = 'index.php?option=com_realestatemanager&amp;task=view&amp;id='.
          $slide->id.'&amp;catid='.$slide->idcat.'&amp;Itemid='.$ItemId_tmp;

        return modRemOsTouchSliderHelper::sefRelToAbs($link);
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

        $tmp = trim(strip_tags($slide->hcountry)) ;
        if($tmp != "" ) $address = $tmp;

        $tmp = trim(strip_tags($slide->hregion)) ;
        if($tmp != "" &&  $address != "" ) $address .= ", " . $tmp;
        else if( $tmp != "" ) $address =  $tmp;

        $tmp = trim(strip_tags($slide->hcity)) ;
        if($tmp != "" &&  $address != "" ) $address .= ", " . $tmp;
        else if( $tmp != "" ) $address =  $tmp;

        $tmp = trim(strip_tags($slide->hlocation)) ;
        if($tmp != "" &&  $address != "" ) $address .= ", " . $tmp;
        else if( $tmp != "" ) $address =  $tmp;

        if($limit && $limit < strlen($address)) {
          $limit = strpos($address, ' ', $limit);
          $address = substr($address, 0, $limit);
          if(preg_match('/[A-Za-z0-9]$/', $address)) $address.=' ...';
          $address = nl2br($address);
        }
        return $address;
    }

  }
}