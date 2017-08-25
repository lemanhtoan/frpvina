<?php
/**
 * sh404SEF support for com_realestatemanager component.
 * Version 1.5
 * @copyright 2009 OrdaSoft
 * @author 2009 Sergey Drughinin-OrdaSoft(Sergey.dru@gmail.com)
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL 
 * Tested with RealEstateManager 1.0 , Joomla 1.5.11 and sh404SEF 1.0.7
 * 
 */
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

require_once ( JPATH_BASE .'/components/com_realestatemanager/functions.php' );
// ------------------  standard plugin initialize function - don't change ---------------------------
$shLangName = '';
$shLangIso = '';
$title = array();
$shItemidString = '';
$dosef = shInitializePlugin( $lang, $shLangName, $shLangIso, $option);
// ------------------  standard plugin initialize function - don't change ---------------------------

// $database is directly needed by some functions, so we need to create it here. 
$GLOBALS['database'] = $database = JFactory::getDBO();
global $database;

// remove common URL from GET vars list, so that they don't show up as query string in the URL
shRemoveFromGETVarsList('option');
shRemoveFromGETVarsList('lang');
if (!empty($Itemid))
  shRemoveFromGETVarsList('Itemid');
if (!empty($limit))  
shRemoveFromGETVarsList('limit');
if (isset($limitstart)) 
  shRemoveFromGETVarsList('limitstart'); // limitstart can be zero

if($option=='com_realestatemanager') {
  $title[] = 'RealEstateManager';
  if ( (!isset($task) OR $task == '' ) AND  isset($view)  ) $task = $view;

  if(isset($task)) {
    switch($task) {
      case 'show_search':
        $title[] = 'ShowSearch';
	    break;
	  case 'search':
	    $title[] = 'Search';
	    break;
	  case 'view':
	    // $title[] = 'View';
	    shRemoveFromGETVarsList($task);
	    break;
	  case 'showCategory':
	    // $title[] = "showCategory"; 
	    shRemoveFromGETVarsList($task);
      break;
	  case 'review':
	    $title[] = 'Review';
	    break;
    case 'alone_category':
      shRemoveFromGETVarsList($task);
      break;
    case 'all_houses':
      shRemoveFromGETVarsList($task);
      break;
    case 'all_categories':
      shRemoveFromGETVarsList($task);
      break;      
    default:
      $title[] = $task;
      break;
    }
    shRemoveFromGETVarsList('task');
    shRemoveFromGETVarsList('layout');
    shRemoveFromGETVarsList('view');
    shRemoveFromGETVarsList('letindex');
    shRemoveFromGETVarsList('name');
  }
  // add active menu name
  $title[] = getMenuTitle($option, (isset($task) ? @$task : null), $Itemid, null, $shLangName ); 

  $s = getWhereUsergroupsCondition("c");
   
  //To get name of category
  if(isset($catid)) {
    	if($catid>0) {
        $query = "SELECT c.id, c.name, c.parent_id FROM #__rem_main_categories AS c
                 \nWHERE ($s) AND c.id = ".intval($catid);
        $database->setQuery($query);
        $row = null;
        $row = $database->loadObject();
        if(isset($row)) {
          $cattitle = array();
          $cattitle[] = $row->id. '_' .$row->name;
          shRemoveFromGETVarsList('catid');
       

          while(isset($row) && $row->parent_id>0) {
            $query = "SELECT  name, c.id AS catid, parent_id 
                      FROM #__rem_main_categories AS c
                      WHERE ($s) AND c.id = ".intval($row->parent_id);
          
            $database->setQuery($query);
            $row = $database->loadObject();
            if(isset($row) && $row->name!=''){
              $cattitle[] = $row->name;
            }
          }
          $title = array_merge($title,array_reverse($cattitle));
       
        }
      } else {
            shRemoveFromGETVarsList('catid');
      }
  }

//To get Name of the houses
if(isset($id)) {
    $query = "SELECT h.id, h.htitle FROM #__rem_houses AS h
             \nLEFT JOIN #__rem_categories AS hc ON hc.iditem=h.id
             \nLEFT JOIN #__rem_main_categories AS c ON c.id=hc.idcat
             \nWHERE ($s) AND h.id=".intval($id);
    $database->setQuery($query);
    $row = null;
    $row = $database->loadObject();
    if(isset($row)) {
      $title[]=$row->id. '_' .$row->htitle;
      shRemoveFromGETVarsList('id');
      shRemoveFromGETVarsList('tab');
//      shRemoveFromGETVarsList('tabs-2');
    }
  }
}
if(isset($Itemid)) {
      // $title[]=$Itemid;
      shRemoveFromGETVarsList('Itemid');
}
// ------------------  standard plugin finalize function - don't change ---------------------------  
if ($dosef){
   $string = shFinalizePlugin( $string, $title, $shAppendString, $shItemidString, 
      (isset($limit) ? @$limit : null), (isset($limitstart) ? @$limitstart : null), 
      (isset($shLangName) ? @$shLangName : null));
}      
// ------------------  standard plugin finalize function - don't change ---------------------------
?>
