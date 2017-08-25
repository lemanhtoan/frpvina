<?php
/**
 *
 * @package  RealEstateManager
 * @copyright 2012 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com); 
 * Homepage: http://www.ordasoft.com
 * @version: 3.9 Free
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *
 */

if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . 
    basename(__FILE__) . ' is not allowed.');
    
global $mosConfig_lang;
 
$database = JFactory::getDBO();


  // load language
  $languagelocale = "";
  $query = "SELECT l.title, l.lang_code, l.sef "
          . "FROM #__rem_const_languages as cl "
          . "LEFT JOIN #__rem_languages AS l ON cl.fk_languagesid=l.id "
          . "LEFT JOIN #__rem_const AS c ON cl.fk_constid=c.id "
          . "GROUP BY  l.title";
  $database->setQuery($query);
  $languages = $database->loadObjectList();
  
  $lang = JFactory::getLanguage();
  foreach ($languages as $language) {
      if ($lang->getTag() == $language->lang_code) {
          $mosConfig_lang = $language->lang_code;
          $languagelocale = $language->lang_code;
          break;
      }
  }
  
  if ($languagelocale == '') {
    foreach ($lang->getLocale() as $locale) {
        foreach ($languages as $language) {
            if (strtolower($locale) == strtolower($language->title) 
                || strtolower($locale) == strtolower($language->lang_code) 
                || strtolower($locale) == strtolower($language->sef) ) {
                $mosConfig_lang = $locale;
                $languagelocale = $language->lang_code;
                break;
            }
        }
    } 
  }
  
  
  if (isset($_REQUEST['option']) && $_REQUEST['option'] == 'com_installer')
      $languagelocale = "en-GB";
  
  if ($languagelocale == '')
      $languagelocale = "en-GB";
   
  
  $query = "SELECT c.const, cl.value_const ";
  $query .= "FROM #__rem_const_languages as cl ";
  $query .= "LEFT JOIN #__rem_languages AS l ON cl.fk_languagesid=l.id ";
  $query .= "LEFT JOIN #__rem_const AS c ON cl.fk_constid=c.id ";
  $query .= "WHERE l.lang_code = '$languagelocale'";

  $database->setQuery($query);
  $langConst = $database->loadObjectList();

        
  foreach ($langConst as $item) {
      defined($item->const) or define($item->const, $item->value_const);
  }




  $query = "SELECT l.lang_code "
          . "FROM #__languages as l " ;
  $database->setQuery($query);
  $languages = $database->loadObjectList();

 global $langContent;
 foreach ($lang->getLocale() as $locale) {

      foreach ($languages as $language) {


          if (strtolower($locale) == strtolower($language->lang_code)
              || strtolower(str_replace(array('_','-'), '', $locale)) 
              == strtolower((str_replace(array('_','-'), '', $language->lang_code)))
              
              ) {

              $langContent = $language->lang_code;


              break;
          }
      }
  }

