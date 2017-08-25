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
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

require('./components/com_realestatemanager/realestatemanager.class.conf.php');

$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path']=JPATH_SITE;

// load language
$lang_def_en=0;
$lang =JFactory::getLanguage();
foreach ($lang->getLocale() as $locale) {
          $mosConfig_lang=$locale;
    if (file_exists($mosConfig_absolute_path."/components/com_realestatemanager/language/{$mosConfig_lang}.php" ))     {  
          include_once($mosConfig_absolute_path."/components/com_realestatemanager/language/{$mosConfig_lang}.php" );
          $lang_def_en=1;
           break;
    } 
}

?>
<div style="clear: both;"></div>
<table class="adminform">
	<tr>
		<td>
			<h3><?php echo _REALESTATE_MANAGER_DOC_GENERAL_INFO;?></h3>
		</td>
	</tr>
	<tr>
		<td>
			<strong> <?php echo _REALESTATE_MANAGER_DOC_VERSION;?></strong>
		</td>
		<td>
			<strong>v <?php echo $realestatemanager_configuration['release']['version'];?></strong>
		</td>
	</tr>
	<tr>
		<td>
			<strong><?php echo _REALESTATE_MANAGER_DOC_RELEASE_DATE;?></strong>
		</td>
		<td>
			<strong><?php echo $realestatemanager_configuration['release']['date'];?></strong>
		</td>
	</tr>
	<tr>
		<td>
			<strong><?php echo _REALESTATE_MANAGER_DOC_PROJECTLINK;?></strong>
		</td>
		<td>
			<strong>
				<a href="http://www.ordasoft.com" target="blank">www.ordasoft.com</a>
			</strong>
		</td>
	</tr>
	<tr>
		<td>
			<strong><?php echo _REALESTATE_MANAGER_DOC_PROJECT_HOST;?></strong>
		</td>
		<td>
			<strong>Andrey Kvasnevskiy (<a href="mailto:akbet@ordasoft.com" >akbet@ordasoft.com</a>)
			</strong>
		</td>
	</tr>
  <tr>
    <td valign="top">
      <strong><?php echo _REALESTATE_MANAGER_DOC_LICENSE;?></strong>
    </td>
    <td>
      <strong>
        <a href="<?php echo $mosConfig_live_site."/administrator/components/com_realestatemanager/doc/LICENSE.txt"; ?>" 
           target="blank">License</a>
      </strong>
      <br />
       <?php echo _REALESTATE_MANAGER_DOC_WARRANTY;?>
    </td>
  </tr>
  <tr>
    <td valign="top">
      <strong>README:</strong>
    </td>
    <td>
      <strong>
        <a href="<?php echo $mosConfig_live_site."/administrator/components/com_realestatemanager/doc/README.txt"; ?>" 
           target="blank">README</a>
      </strong>
    </td>
  </tr>
	<tr >
		<td valign="top">
			<strong><?php echo _REALESTATE_MANAGER_DOC_DEVELOP;?></strong>
		</td>
		<td>
			<ul>
        <li><b>v 3.8 PRO</b> OrdaSoft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru" >akbet@mail.ru</a>),
                  Roman Akoev(<a href="mailto:akoevroman@gmail.com" >akoevroman@gmail.com</a>), Dmitry Smirnov(<a href="mailto:dmitriiua21@gmail.com" >dmitriiua21@gmail.com</a>)</li>
        <li><b>v 3.7 PRO</b> OrdaSoft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru" >akbet@mail.ru</a>),
                              Roman Akoev(<a href="mailto:akoevroman@gmail.com" >akoevroman@gmail.com</a>)</li>
        <li><b>v 3.6 PRO</b> OrdaSoft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru" >akbet@mail.ru</a>),
                              Roman Akoev(<a href="mailto:akoevroman@gmail.com" >akoevroman@gmail.com</a>)</li>
        <li><b>v 3.5 PRO</b> OrdaSoft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru" >akbet@mail.ru</a>)</li>
      	<li><b>v 3.3 PRO</b> OrdaSoft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru" >akbet@mail.ru</a>)</li>
      	<li><b>v 3.0 PRO</b> OrdaSoft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru" >akbet@mail.ru</a>)</li>
      	<li><b>v 2.3 PRO</b> OrdaSoft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru" >akbet@mail.ru</a>)</li>
        <li><b>v 2.2 PRO</b> OrdaSoft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru" >akbet@mail.ru</a>)</li>
        <li><b>v 2.1 PRO</b> OrdaSoft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru" >akbet@mail.ru</a>)</li>
        <li><b>v 2.0 PRO</b> OrdaSoft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru" >akbet@mail.ru</a>)</li>
        <li><b>v 1.0.1 PRO</b> OrdaSoft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru" >akbet@mail.ru</a>), Marina Radchenko(<a href="mailto:mar_radch@mail.ru" >mar_radch@mail.ru</a>), Sergey Drughinin(<a href="mailto:Sergey.dru@gmail.com" >mar_radch@mail.ru</a>)</li>
			</ul>
		</td>
	</tr>
</table>

