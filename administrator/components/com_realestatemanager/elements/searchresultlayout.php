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
require_once ( JPATH_SITE.'/components/com_realestatemanager/functions.php' );
if (version_compare(JVERSION, "1.6.0", "ge") ){
    class JFormFieldSearchResultlayout extends JFormField{
        protected function getInput(){
            $search_result_layout = getLayoutsRem('com_realestatemanager','search_result');
            $layouts = Array();
            $layouts[] = JHtml::_('select.option', '', 'Use Global');
            foreach($search_result_layout as $value){
                $layouts[] = JHtml::_('select.option', "$value", "$value"); 
            }
            return JHtml::_('select.genericlist', $layouts, $this->name, 'class="inputbox"', 'value', 'text', $this->value, $this->name);
        }
    }
} 
else {
    echo "Sanity test. Error version check!"; 
    exit;
}
