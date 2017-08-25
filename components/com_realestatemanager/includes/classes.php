<?php
/**
* This file provides compatibility for Real Estate Manager on Joomla! 1.0.x and Joomla! 1.5
*
*/
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . 
    basename(__FILE__) . ' is not allowed.');

if (!defined('_VALID_MOS') && !defined('_JEXEC'))
    die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
/**
 *
 * @package  RealEstateManager
 * @copyright 2012 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com); 
 * Homepage: http://www.ordasoft.com
 * @version: 3.9 Free
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *
 */

JLoader::register('JPaneTabs',  JPATH_LIBRARIES.DS.'joomla'.DS.'html'.DS.'pane.php');

/**
 * Legacy class, replaced by full MVC implementation.  See {@link JController}
 *
 * @deprecated	As of version 1.5
 * @package	Joomla.Legacy
 * @subpackage	1.5
 */
if ( !class_exists('mosAbstractTasker')) {
  class mosAbstractTasker
  {
	  function __construct()
	  {
		  jexit( 'mosAbstractTasker deprecated, use JController instead' );
	  }
  }
}

/**
 * Legacy class, removed
 *
 * @deprecated	As of version 1.5
 * @package	Joomla.Legacy
 * @subpackage	1.5
 */
if ( !class_exists('mosEmpty')) {
  class mosEmpty
  {
	  function def( $key, $value='' )
	  {
		  return 1;
	  }
	  function get( $key, $default='' )
	  {
		  return 1;
	  }
  }
}

/**
 * Legacy class, removed
 *
 * @deprecated	As of version 1.5
 * @package	Joomla.Legacy
 * @subpackage	1.5
 */
if ( !class_exists('MENU_Default')) {
  class MENU_Default
  {
	  function __construct()
	  {
		  JToolBarHelper::publishList();
		  JToolBarHelper::unpublishList();
		  JToolBarHelper::addNew();
		  JToolBarHelper::editList();
		  JToolBarHelper::deleteList();
		  JToolBarHelper::spacer();
	  }
  }
}

/**
 * Legacy class, use {@link JPanel} instead
 *
 * @deprecated	As of version 1.5
 * @package	Joomla.Legacy
 * @subpackage	1.5
 */
if ( !class_exists('mosTabs') && version_compare(JVERSION,"3.0.0","lt")) {
  class mosTabs extends JPaneTabs
  {
	  var $useCookies = false;
  
	  function __construct( $useCookies, $xhtml = null) {
		  parent::__construct( array('useCookies' => $useCookies) );
	  }
  
	  function startTab( $tabText, $paneid ) {
		  echo $this->startPanel( $tabText, $paneid);
	  }
  
	  function endTab() {
		  echo $this->endPanel();
	  }
  
	  function startPane( $tabText ){
		  echo parent::startPane( $tabText );
	  }
  
	  function endPane(){
		  echo parent::endPane();
	  }
  }
} elseif (!class_exists('mosTabs') && version_compare(JVERSION,"3.0.0","ge"))
{
    class mosTabs
    {
      var $useCookies = false;


      function startTab( $tabText, $paneid ) {
          echo JHtml::_('tabs.panel', JText::_($tabText), 'panel_'.$paneid.'_id');
      }

      function endTab() {

      }

      function startPane( $tabText ){
          $options = array(
              'onActive' => 'function(title, description){
                    description.setStyle("display", "block");
                    title.addClass("open").removeClass("closed");
              }',
              'onBackground' => 'function(title, description){
              description.setStyle("display", "none");
              title.addClass("closed").removeClass("open");
              }',
              'startOffset' => 0,  // 0 starts on the first tab, 1 starts the second, etc...
              'useCookie' => true, // this must not be a string. Don't use quotes.
          );
          echo JHtml::_('tabs.start', 'tab_group_id', $options);
      }

      function endPane(){
          echo JHtml::_('tabs.end');
      }


    }
}
