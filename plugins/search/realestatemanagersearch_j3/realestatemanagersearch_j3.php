<?php
defined('_JEXEC') or die('Restricted access');

/**
 * @copyright 2012 OrdaSoft
 * @author 2012 Andrey Kvasnevsky
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @package Search
 * @description Search plugin for RealEstateManager Component
 */
 
if (version_compare(JVERSION, "1.6.0", "lt"))
{ 
 function plgSearchRealestateAreas()
  {
	static $areas = array(
		'realestate' => 'RealEstate'
	);
	return $areas;
   }
if( !function_exists( 'getWhereUsergroupsCondition')) {
  function getWhereUsergroupsCondition ( $table_alias = "c" ) {
    global $my;

       
    if ( !isset ($my) )  {
        if ( $my  = JFactory::getUser() ) {
            $gid = $my->gid;
               } else  $gid = 0;

        } else $gid = $my->gid;

    $usergroups_sh = array ( $gid, -2 );
    $s = '';
    for ($i=0; $i<count($usergroups_sh); $i++) {
      $g = $usergroups_sh[$i];
      $s .= " $table_alias.params LIKE '%,{$g}' or $table_alias.params = '{$g}' or $table_alias.params LIKE '{$g},%' or $table_alias.params LIKE '%,{$g},%' ";
      if ( ($i+1)<count($usergroups_sh) )
        $s .= ' or ';
    }
    return $s;
      }
   }
  




function onContentSearch( $text ,$phrase='', $ordering='', $areas=null){
 
        if (is_array($areas)){
            if (!array_intersect($areas, array_keys( $this->onSearchAreas()))) return array();
        }
        if(!function_exists('sefRelToAbs')){
            function sefRelToAbs( $value ){
                //Need check!!!
                //Replace all &amp; with & as the router doesn't understand &amp;
                $url = str_replace('&amp;', '&', $value);
                if(substr(strtolower($url),0,9) != "index.php") return $url;
                $uri = JURI::getInstance();
                $prefix = $uri->toString(array('scheme', 'host', 'port'));
                return $prefix.JRoute::_($url);
            }
          }
        
        
        $ItemId_tmp_from_params=0;
        $database = &JFactory::getDBO();
        $database->setQuery("SELECT id FROM #__menu WHERE link LIKE'%option=com_realestatemanager%' AND params LIKE '%back_button%' ");
        $ItemId_tmp_from_db = $database->loadResult();
        if($ItemId_tmp_from_params=="") $ItemId=$ItemId_tmp_from_db; else $ItemId=$ItemId_tmp_from_params;
        $order = '';
        switch($ordering){
            case 'newest':
                $order = 'ORDER BY h.id DESC';
                break;
            case 'oldest':
                $order = 'ORDER BY h.id';
                break;
            case 'popular':
                $order = 'ORDER BY h.hits';
                break;
            case 'alpha':
                $order = 'ORDER BY h.htitle';
                break;
            case 'category':
                $order = 'ORDER BY category';
                break;
        }
        $text =  preg_replace ('/\s\s+/','%',trim( $text ));
        if ($text == '') { return array(); }
        switch($phrase){
            case 'exact':
                $text = preg_replace ('/\s\s+/',' ',trim($text));
                $where = " (h.htitle LIKE '%$text%')"
                        ." OR (h.hlocation LIKE '%$text%')"
                        ." OR (h.listing_type LIKE '%$text%')"
                        ." OR (h.description LIKE '%$text%'"
                        ." OR (h.property_type LIKE '%$text%')"
                        ." AND (h.published = '1'))";
                break;
            case 'all':
            case 'any':
            default:
                $text =  preg_replace('/\s\s+/',' ',trim($text));
                $words = explode(' ', $text);
                $wheres = array();
                foreach ($words as $word){
                    $word = $database->Quote('%'.$database->getEscaped($word, true).'%', false);
                    $wheres2 = array();
                    $wheres2[] = " h.description LIKE $word";
                    $wheres2[] = " h.htitle LIKE $word ";
                    $wheres2[] = " h.hlocation LIKE $word ";
                    $wheres2[] = " h.listing_type LIKE $word"; 
                    $wheres2[] = " h.property_type LIKE $word"; 
                    $wheres[] = implode(' OR ', $wheres2);
                }
                $where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
                break;
        }

        $s = getWhereUsergroupsCondition("c");

        $query = "SELECT h.htitle AS title, h.date AS created, h.description  AS text ,"
                ." CONCAT( 'index.php?option=com_realestatemanager"
                ."&task=view&id=', h.id,'&catid=', c.id,'&Itemid=', $ItemId) AS href,"
                ." '2' AS browsernav, 'RealEstateManager' AS section, c.title AS category"
                ." FROM #__rem_houses AS h "
                ." LEFT JOIN #__rem_categories AS hc ON h.id=hc.iditem "
                ." LEFT JOIN #__rem_main_categories AS c ON c.id=hc.idcat "
                ." WHERE h.published = '1' AND ($s)"
                ." AND $where GROUP BY h.id $order";
        $database->setQuery($query);
        return $database->loadObjectList();
    
}

}


if (version_compare(JVERSION, "1.6.0", "lt"))
{
   
  $mainframe->registerEvent( 'onSearch','onContentSearch' );                                                          
  $mainframe->registerEvent( 'onSearchAreas', 'plgSearchRealestateAreas' );
} 
else
{ 
//===========================================JooMla 2.5.3===========================================
    jimport('joomla.plugin.plugin');
jimport('joomla.html.parameter');

require_once (JPATH_BASE .'/components/com_realestatemanager/functions.php');
       

class plgSearchRealestatemanagerSearch_j3 extends JPlugin{
    
    var $ItemId;
    public function __construct(& $subject, $config){
        parent::__construct($subject, $config);
        $this->loadLanguage();    
        $params = new JRegistry($config['params']);        
        $this->ItemId=$params->get('ItemId',0);
       
    }

    function onContentSearchAreas(){
        static $areas = array('realestateman' => 'RealEstate Manager');
        return $areas;
    }

    public function onSearchAreas(){ // We get here when input box [RealEstate Manager] was enabled
        static $areas = array('realestateman' => 'RealEstateManager');
       
        return $areas;
    }

    public function onContentSearch( $text ,$phrase='', $ordering='', $areas=null){
         
        if (is_array($areas)){
            if (!array_intersect($areas, array_keys( $this->onSearchAreas()))) return array();
        }
        if(!function_exists('sefRelToAbs')){
            function sefRelToAbs( $value ){               
               //Need check!!!
                //Replace all &amp; with & as the router doesn't understand &amp;
                $url = str_replace('&amp;', '&', $value);           
                if(substr(strtolower($url),0,9) != "index.php") return $url;
                $uri = JURI::getInstance();
                $prefix = $uri->toString(array('scheme', 'host', 'port'));
                return $prefix.JRoute::_($url);
            }
        }
        $ItemId_tmp_from_params=$this->ItemId;
        $database = JFactory::getDBO();
        $database->setQuery("SELECT id FROM #__menu WHERE link LIKE'%option=com_realestatemanager%' AND params LIKE '%back_button%' ");
        $ItemId_tmp_from_db = $database->loadResult();
        if($ItemId_tmp_from_params=="") $ItemId=$ItemId_tmp_from_db; else $ItemId=$ItemId_tmp_from_params;
        $order = '';
        switch($ordering){
            case 'newest':
                $order = 'ORDER BY h.id DESC';
                break;
            case 'oldest':
                $order = 'ORDER BY h.id';
                break;
            case 'popular':
                $order = 'ORDER BY h.hits';
                break;
            case 'alpha':
                $order = 'ORDER BY h.htitle';
                break;
            case 'category':
                $order = 'ORDER BY category';
                break;
        }
        
        $text =  preg_replace ('/\s\s+/','%',trim( $text ));
        
        if ($text == '') { return array(); }
        switch($phrase){
            case 'exact':
                
                $text = preg_replace ('/\s\s+/',' ',trim($text));
                $where = " (h.htitle LIKE '%$text%')"
                        ." OR (h.hlocation LIKE '%$text%')"
                        ." OR (h.listing_type LIKE '%$text%')"
                        ." OR (h.description LIKE '%$text%'"
                        ." OR (h.property_type LIKE '%$text%')"
                        ." AND (h.published = '1'))";
                break;
            case 'all':
            case 'any':
            default:
                
                $text =  preg_replace('/\s\s+/',' ',trim($text));
                $words = explode(' ', $text);
                
                $wheres = array();
                foreach ($words as $word){
                    
                    $word = $database->Quote('%'.$word.'%', false);          
                    $wheres2 = array();
                    $wheres2[] = " h.description LIKE $word";
                    $wheres2[] = " h.htitle LIKE $word ";
                    $wheres2[] = " h.hlocation LIKE $word ";
                    $wheres2[] = " h.listing_type LIKE $word"; 
                    $wheres2[] = " h.property_type LIKE $word"; 
                    $wheres[] = implode(' OR ', $wheres2);
                }
                $where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
                break;
        }

        $s = getWhereUsergroupsCondition("c");

        $query = "SELECT h.htitle AS title, h.date AS created, h.description  AS text ,"
                ." CONCAT( 'index.php?option=com_realestatemanager"
                ."&task=view&id=', h.id,'&catid=', c.id,'&Itemid=', $ItemId) AS href,"
                ." '2' AS browsernav, 'RealEstateManager' AS section, c.title AS category"
                ." FROM #__rem_houses AS h "
                ." LEFT JOIN #__rem_categories AS hc ON h.id=hc.iditem "
                ." LEFT JOIN #__rem_main_categories AS c ON c.id=hc.idcat "
                ." WHERE h.published = '1' AND ($s)"
                ." AND $where GROUP BY h.id $order";
        $database->setQuery($query);
        return $database->loadObjectList();
    }
  }
}

?>
