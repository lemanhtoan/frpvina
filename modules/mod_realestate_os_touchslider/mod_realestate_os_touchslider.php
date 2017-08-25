<?php
/*
 * @package OS_Touch_Slider
 * @subpackage  mod_realestate_os_touchslider
 * @copyright Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Sergey Bunyaev(sergey@bunyaev.ru); Sergey Solovyev(solovyev@solovyev.in.ua)
 * @Homepage: http://www.ordasoft.com
 * @version: 1.2
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
require_once dirname(__FILE__).'/helpers/images.php';
require_once dirname(__FILE__).'/helpers/helper.php';

global $realestatemanager_configuration;

$database = JFactory::getDBO();
// load language
$languagelocale = "";
$query = "SELECT l.title, l.lang_code, l.sef ";
$query .= "FROM #__rem_const_languages as cl ";
$query .= "LEFT JOIN #__rem_languages AS l ON cl.fk_languagesid=l.id ";
$query .= "LEFT JOIN #__rem_const AS c ON cl.fk_constid=c.id ";
$query .= "GROUP BY  l.title";
$database->setQuery($query);
$languages = $database->loadObjectList();

$lang = JFactory::getLanguage();
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

if ($languagelocale == '')
    $languagelocale = "en-GB";

global $langContent;
$langContent = substr($languagelocale, 0, 2);

$query = "SELECT c.const, cl.value_const ";
$query .= "FROM #__rem_const_languages as cl ";
$query .= "LEFT JOIN #__rem_languages AS l ON cl.fk_languagesid=l.id ";
$query .= "LEFT JOIN #__rem_const AS c ON cl.fk_constid=c.id ";
$query .= "WHERE l.lang_code = '$languagelocale'";
$database->setQuery($query);
$langConst = $database->loadObjectList();

foreach ($langConst as $item) {
   if(!defined($item->const) )  define($item->const, $item->value_const);
}


$show_type =$params->get('type','horizontal');
JHtml::_('stylesheet',JURI::base()."/modules/mod_realestate_os_touchslider/assets/css/idangerous-swiper.css");
if($params->get('jquery-local',1) == "1" && $params->get('jquery',1) == "1") {
    JHtml::_('script',JURI::base()."/modules/mod_realestate_os_touchslider/assets/js/jquery-1.7.1.min.js");
} elseif ($params->get('jquery',1) == "1") {
    JHtml::_('script','//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
}
if($params->get('no-conflict',0) == '1') {
    $doc =JFactory::getDocument();
    $doc->addScriptDeclaration("jQuery.noConflict();");
}

if($params->get('width')){
  $width = $params->get('width');
}else{
  $width = 100;
}
if($params->get('is_percentage') == 'percentage'){
  $format='%';
}else{
  $format='px';
}
if(!is_numeric($height = $params->get('height'))) $height = 180;

JHtml::_('script',JURI::base()."/modules/mod_realestate_os_touchslider/assets/js/idangerous-swiper.js");

$arrows = $params->get('arrows','1');
$respect_quite = $params->get('respect_quite','1');
$dots = $params->get('dots','1');
$autoplay = $params->get('autoplay','1');
$sufix = $params->get('sufix','');
require JModuleHelper::getLayoutPath('mod_realestate_os_touchslider', $params->get('layout', 'default'));
?>
<div style="text-align: center;"><a href="http://ordasoft.com" style="font-size: 10px;">Powered by OrdaSoft!</a></div>